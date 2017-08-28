<?php

class BaseController extends Controller {

	public function __construct()
	{


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

	public  static function validate($rules,  $messages = []) {
		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails()) {
			return Redirect::back()->withErrors($validation->messages());
		}
		return false;
	}

	public static  function validateAJAX($rules,  $messages = []) {
		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails()) {
			$msg = [];
			foreach ($validation->messages()->toArray() as $error) {
				$msg[] = implode("<br>", $error);
			}
			return self::error(implode("<br>", $msg));
		}
		return false;
	}

	public static function error($message, $arr=[]) {
		if (!isset($arr['status']))
			$arr['status'] = "error";
		if (!isset($arr['error']))
			$arr['error'] = $message;
		if (!isset($arr['reason']))
			$arr['reason'] = $message;
		return Response::json($arr);
	}

	public static  function success($arr=[]) {
		if (!isset($arr['status']))
			$arr['status'] = 'success';
		return Response::json($arr);
	}
}
