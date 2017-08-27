<?php

class BaseController extends Controller {

	public function __construct()
	{
		if (file_exists(app_path('version.php')))
			include_once(app_path('version.php'));
		else {
			// If version.php does not exist (or is ill-definde); include a dev only version
			if (!defined('SOLDER_STREAM'))
				define('SOLDER_STREAM', 'DEV');

			if (!defined('SOLDER_VERSION'))
				define('SOLDER_VERSION', 'indev');
		}

		UpdateUtils::init();
	}

	public function showLogin()
	{
		return View::make('dashboard.login');
	}

	public function postLogin()
	{
		$email = Input::get('email');
		$password = Input::get('password');
		$remember = Input::get('remember') ? true : false;

		$credentials = array(
			'email' => $email,
			'password' => $password,
			);

		if ( Auth::attempt($credentials, $remember)) {

			Auth::user()->last_ip = Request::ip();
			Auth::user()->save();

			//Check for update on login
			if(UpdateUtils::getUpdateCheck()){
				Cache::put('update', true, 60);
			}

			return Redirect::to('dashboard/');
		} else {
			return Redirect::to('login')->with('login_failed',"Invalid Email/Password");
		}
	}

	public function validate($rules) {
		$validation = Validator::make(Input::all(), $rules);

		if ($validation->fails()) {
			return Redirect::back()->withErrors($validation->messages());
		}
		return false;
	}

	public function validateAJAX($rules) {
		$validation = Validator::make(Input::all(), $rules);

		if ($validation->fails()) {
			$msg = [];
			foreach ($validation->messages()->toArray() as $error) {
				$msg[] = implode("<br>", $error);
			}
			return $this->error(implode("<br>", $msg));
		}
		return false;
	}

	public function error($message, $arr=[]) {
		if (!isset($arr['status']))
		$arr['status'] = "error";
		if (!isset($arr['reason']))
		$arr['reason'] = $message;
		return Response::json($arr);
	}

	public function success($arr=[]) {
		if (!isset($arr['status']))
			$arr['status'] = 'success';
		return Response::json($arr);
	}
}
