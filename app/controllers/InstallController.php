<?php

class InstallController extends BaseController {

    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter(function() {
            if ($this->isInstalled()) {
                return Redirect::to("/");
            }

            $stage = $this->getStage();
            if ($stage === false)
                $stage = 1 ;

            if ('/install/stage' . $stage != Request::getPathInfo())
                return Redirect::to('/install/stage' . $stage);
        });
    }

    public function getIndex()
    {
        return Redirect::to('/install/stage1');
    }


    // Stage 1 - Database
    public function getStage1() {
        return Response::view('install.stage1');
    }

    public function postStage1()
    {
        Log::info(shell_exec('php ' . base_path('artisan') . ' key:generate'));
        $driver = Input::get('driver');
        if ($driver == 'sqlite') {
            if (!copy(app_path('database-sample/production.sqlite'), app_path('database/production.sqlite')))
                return Redirect::back()->
                withErrors(['Failed to move database-sample/production.sqlite to database/production.sqlite']);
        } else {
            $host = Input::get('host');
            $database = Input::get('database');
            $port = Input::get('port', -1);
            $username = Input::get('username');
            $password = Input::get('password');
            $prefix = Input::get('prefix', '');

            if ($res = $this->validate([
                'driver' => 'required',
                'host' => 'required',
                'username' => 'required'
            ])) return $res;

            $dsn = null;
            switch ($driver) {
                case 'mysql':
                    $dsn = $port > 0
                        ? "mysql:host={$host};port={$port};dbname={$database}"
                        : "mysql:host={$host};dbname={$database}";
                    break;

                case 'pgsql':
                    $dsn = $port > 0
                        ? "pgsql:host={$host};port={$port};dbname={$database}"
                        : "pgsql:host={$host};dbname={$database}";
                    break;

                case 'sqlsrv':
                    $dsn = $port > 0
                        ? "sqlsrv:Server={$host},{$port};Database={$database}"
                        : "sqlsrv:Server={$host};Database={$database}";
                    break;
            }

            try {
                new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            } catch (PDOException $e) {
                return Redirect::back()->withErrors([$e->getMessage()]);
            }

            Config::write('database.default', $driver);
            Config::write("database.connections.$driver.host", $host);
            Config::write("database.connections.$driver.database", $database);
            Config::write("database.connections.$driver.username", $username);
            Config::write("database.connections.$driver.password", $password);
            Config::write("database.connections.$driver.prefix", $prefix);
        }

        Log::info(shell_exec('php ' . base_path('artisan') . ' migrate:install --force'));
        Log::info(shell_exec('php ' . base_path('artisan') . ' migrate --force'));

        $this->setStage(2);

        return Redirect::refresh();
    }

    // Stage 2 - Solder Settings
    public function getStage2() {
        return Response::view('install.stage2');
    }

    public function postStage2() {
        $warning = null;
        if ($res = $this->validate([
            'app_url'    => 'required|url',
            'mod_uri'    => '',
            'mirror_url' => 'required|url'
        ]))return $res;

        $app_url = Input::get('app_url');
        $mod_uri = Input::get('mod_uri');
        $mirror_url = Input::get('mirror_url');

        // Make sure this is a reasonable application url!
        if (!ends_with('/', $app_url))
            $app_url .= "/";

        if (!$this->validateAppURL($app_url))
            return Redirect::back()->withErrors(['Application URL is invalid!']);

        if (!ends_with('/', $mirror_url)) {
            $mirror_url .= "/";
        }

        // Check's if this is a local file...
        if (starts_with($mod_uri, 'http')) {
            $warning = "Because you used a URL, you will not be able to upload mods to Solder!";
            $result = UrlUtils::get_url_contents($mirror_url, '');
            if (!$result['success'])
                return Redirect::back()->withErrors(['The Mirror URL is invalid!']);
        }else {
            $mod_uri = realpath($mod_uri) . "/";

            // Let us test if the mirror URL and the repo URI are the same.
            if (!is_dir($mod_uri))
                mkdir($mod_uri, 0777, true);

            if (file_exists($mod_uri . 'install_test'))
                unlink($mod_uri . 'install_test');

            $rand = random_int(0, 255212);
            if (file_put_contents($mod_uri . 'install_test', $rand)) {
                $result = UrlUtils::get_url_contents($mirror_url . "install_test", null);
                if (!$result['success'])
                    return Redirect::back()->withErrors(['The Mirror URL is invalid!']);

                $data = $result['data'];
                if ($data != strval($rand))
                    return Redirect::back()->withErrors(['The Mirror URL / mod URI pair is invalid!']);
            }else
                $warning = "There was a failure testing the uploading capabilities of the designated Repository Location.";
        }

        Config::write('solder.mirror_url', $mirror_url);
        Config::write('solder.repo_location', $mod_uri);
        Config::write('app.url', $app_url);

        $this->setStage(3);

        if ($warning)
            return Redirect::to('/install/stage3')->with('warning', $warning);
        else
            return Redirect::refresh();
    }

    //STAGE 3 - User Creation
    public function getStage3() {
        return Response::view('install.stage3');
    }

    public function postStage3() {
        if ($res = $this->validate([
            'email'     => 'required|email|unique:users',
            'username'  => 'required|min:3|max:30|unique:users',
            'password'  => 'required|min:3'
        ]))return $res;

        $email = Input::get('email');
        $password = Input::get('password');

        $creator = -1;
        $creatorIP = Request::ip();

        $user = new User();
        $user->email = $email;
        $user->username = Input::get('username');
        $user->password = Hash::make($password);
        $user->created_ip = $creatorIP;
        $user->created_by_user_id = $creator;
        $user->updated_by_ip = $creatorIP;
        $user->updated_by_user_id = $creator;
        $user->save();

        $perm = new UserPermission();
        $perm->user_id = $user->id;
        $perm->solder_full = true;
        $perm->save();
        Config::write('solder.install_stage', true);
        Config::set('solder.install_stage', true);

        Session::clear();

        if ( Auth::attempt(array(
            'email' => $email,
            'password' => $password), false)) {
            Auth::user()->last_ip = Request::ip();
            Auth::user()->save();
            $this->setStage(true);
            return Redirect::refresh();
        } else {
            return Redirect::to('login')->with('login_failed',"Invalid Email/Password");
        }
    }

    // Stage 4 - API Key
    public function getStage4()
    {
        return Response::view('install.stage4');
    }

    public function postStage4() {
        if ($res = $this->validate([
            'name'     => 'required',
            'key'  => 'required'
        ]))return $res;

        $name = Input::get('name');
        $key = Input::get('key');

        Key::create([
            'name' => $name,
            'api_key' => $key
        ]);

        $this->setStage(5);
        return Redirect::refresh();
    }

    public function getStage5() {
        return Response::view('install.stage5');
    }

    public function postStage5() {
        $this->setStage(true);
        return Redirect::refresh();
    }

    // Private Functions
    public static function isInstalled() {
        return Cache::get('solder.install_stage',
            Config::get('solder.install_stage', true)) === true;
    }

    private function setStage($new) {
        Cache::put('solder.install_stage', $new, 30);
        Config::write('solder.install_stage', $new);
    }

    public static function getStage() {
        return Cache::get('solder.install_stage',
            Config::get('solder.install_stage'));
    }

    public static function validateAppURL($url) {
        $result = UrlUtils::get_url_contents($url . "api/", '');
        if ($result['success']) {
            $res = json_decode($result['data']);
            if ($res->api == "TechnicSolder")
                if ($res->is_plus == "true")
                    if ($res->version == SOLDER_VERSION)
                        if ($res->stream == SOLDER_STREAM)
                            return true;
        }
            return false;
    }

    //TODO implement this in this file somewhere.
    public static function validateURIs($mod_uri, $mirror_url) {
        $mod_uri = realpath($mod_uri) . "/";

        // Let us test if the mirror URL and the repo URI are the same.
        if (!is_dir($mod_uri))
            mkdir($mod_uri, 0777, true);

        if (file_exists($mod_uri . 'install_test'))
            unlink($mod_uri . 'install_test');

        $rand = random_int(0, 255212);
        if (file_put_contents($mod_uri . 'install_test', $rand)) {
            $result = UrlUtils::get_url_contents($mirror_url . "install_test", null);
            if (!$result['success'])
                return 'The Mirror URL is invalid!';

            $data = $result['data'];
            if ($data != strval($rand))
                return 'The Mirror URL / mod URI pair is invalid!';
        }else
            return [true, "There was a failure testing the uploading capabilities of the designated Repository Location."];
        return true;
    }
}