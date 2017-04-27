<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * Class User
 * @property int id
 * @property string username
 * @property string email
 * @property string password
 * @property string created_ip
 * @property string last_ip
 * @property string created_at
 * @property string updated_at
 * @property string remember_token
 * @property string updated_by_ip
 * @property string created_by_user_id
 * @property string updated_by_user_id
 */
class User extends Eloquent implements UserInterface, RemindableInterface {
	public $timestamps = true;

		/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
	    return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
	    return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
	    return $this->email;
	}

	public function permission()
	{
		return $this->hasOne('UserPermission');
	}

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}
}