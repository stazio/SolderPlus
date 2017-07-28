<?php

class BaseController extends Controller {

	public function __construct()
	{
		if(!defined('SOLDER_STREAM')) {
			define('SOLDER_STREAM', 'PROD');
		}

		if(!defined('SOLDER_VERSION')) {
			define('SOLDER_VERSION', 'v1.0.0');
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
}
