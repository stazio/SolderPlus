<?php

class SolderController extends BaseController {

	public function __construct()
	{
		parent::__construct();
	}

	public function getConfigure()
	{
		return View::make('solder.configure');
	}

    public function postConfigure() {

        $mirror_url = Input::get('mirror_url', Config::get('solder.mirror_url'));
        $repo_location = Input::get('repo_location', '');
        $md5_connect_timeout = Input::get('md5_connect_timeout', Config::get('solder.md5_connect_timeout'));
        $md5_file_timeout = Input::get('md5_file_timeout', Config::get('solder.md5_file_timeout'));

        if (!starts_with($repo_location, 'http')) {
            if (!starts_with($repo_location, '/')) {
                $repo_location = base_path($repo_location);
            }
            $res = InstallController::validateURIs($repo_location, $mirror_url);
            if ($res !== true) {
                if (is_array($res)) {
                    $warning = $res[1];
                }else
                    return Redirect::back()->withErrors([$res]);
            }
        }else
            $warning =
                "It is recommended to NOT use an URL (starts with http or https) because mod uploads will not work then!";

       ConfUtils::saveAll([
           'solder.mirror_url' => $mirror_url,
           'solder.repo_location' => $repo_location,
           'solder.md5_connect_timeout' => $md5_connect_timeout,
           'solder.md5_file_timeout' => $md5_file_timeout
       ]);

       if (isset($warning))
           return Redirect::action('SolderController@getConfigure')->
           with('warning', $warning);
       else
       return Redirect::action('SolderController@getConfigure')->
       with('success', 'Settings were updated successfully.');
    }

	public function getUpdate()
	{
		$rawChangeLog = UpdateUtils::getLatestChangeLog();
		$changelog = array_key_exists('error', $rawChangeLog) ? $rawChangeLog : array_slice($rawChangeLog, 0, 10);
		$latestCommit = array_key_exists('error', $rawChangeLog) ? $rawChangeLog : $rawChangeLog[0];

		$rawLatestVersion = UpdateUtils::getLatestVersion();
		$latestVersion = array_key_exists('error', $rawLatestVersion) ? $rawLatestVersion : $rawLatestVersion['tag_name'];

		$latestData = array('version' => $latestVersion,
							'commit' => $latestCommit);
		
		return View::make('solder.update')->with('changelog', $changelog)->with('currentVersion', SOLDER_VERSION)->with('latestData', $latestData);
	}

	public function getUpdateCheck()
	{
		if (Request::ajax())
		{

			if(UpdateUtils::getUpdateCheck()){
				Cache::put('update', true, 60);
				return Response::json(array(
									'status' => 'success',
									'update' => true
									));
			} else {
				if(Cache::get('update')){
					Cache::forget('update');
				}
				return Response::json(array(
									'status' => 'success',
									'update' => false
									));
			}
		}

		return Response::view('errors.missing', array(), 404);
	}

	public function getCacheMinecraft() {
		if (Request::ajax())
		{
			$reason = '';
			try {
				$reason = MinecraftUtils::getMinecraft(true);
			}
			catch (Exception $e) {
				return Response::json(array(
									'status' => 'error',
									'reason' => $e->getMessage()
									));
			}

			if (Cache::has('minecraftversions')){
				return Response::json(array(
									'status' => 'success',
									'reason' => $reason
									));
			} else {
				return Response::json(array(
									'status' => 'error',
									'reason' => 'An unknown error has occured.'
									));
			}
		}

		return Response::view('errors.missing', array(), 404);
	}
}