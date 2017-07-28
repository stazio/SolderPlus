<?php

class BaseTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		Session::start();

        if (!Mod::find(1)->get()) {
            $creator = -1;
            $creatorIP = Request::ip();

            $user = new User();
            $user->id = 1;
            $user->email = 'test@test.com';
            $user->username = 'Test User';
            $user->password = Hash::make('Password');
            $user->created_ip = $creatorIP;
            $user->created_by_user_id = $creator;
            $user->updated_by_ip = $creatorIP;
            $user->updated_by_user_id = $creator;
            $user->save();
        }

		Route::enableFilters();
	}

	public function testLoginGet()
	{
		$this->call('GET', '/login');

		$this->assertResponseOk();
	}

	public function testUnauthorizedLogin()
	{
		$credentials = array(
			'email' => 'test@admin.com',
			'password' => 'ifail',
			'remember' => false
		);

		$response = $this->call('POST', '/login', $credentials);
		$this->assertRedirectedTo('/login');
		$this->assertSessionHas('login_failed');
	}

	public function testAuthorizedLogin()
	{
		$credentials = array(
			'email' => 'admin@admin.com',
			'password' => 'admin',
			'remember' => false
		);

		$response = $this->call('POST', '/login', $credentials);
		$this->assertRedirectedTo('/dashboard');
	}

	public function testIndex()
	{
		$user = User::find(1);
		$this->be($user);

		$this->call('GET', '/');

		$this->assertRedirectedTo('/dashboard');
	}

	public function testUnauthorizedAccess()
	{
		$this->call('GET', '/dashboard');

		$this->assertRedirectedTo('/login');
	}

	public function testDashboard()
	{
		$user = User::find(1);
		$this->be($user);

		$this->call('GET', '/dashboard');

		$this->assertResponseOk();
	}
}
