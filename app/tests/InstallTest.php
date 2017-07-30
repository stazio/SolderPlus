<?php
/**
 * Created by PhpStorm.
 * User: staz
 * Date: 7/28/17
 * Time: 7:42 PM
 */

class InstallTest //extends TestCase
{
    const STAGES = 5;

    public function tearDown() {
	    parent::tearDown();
	    InstallController::setStage(true);
    }

	public function testRedirectStage1() {
		InstallController::setStage(1);
		$response = $this->call('GET', '/');
		$this->assertRedirectedTo('/install/stage' . InstallController::getStage());
	}
	public function testRedirectStage2() {
		InstallController::setStage(2);
		$response = $this->call('GET', '/');
		$this->assertRedirectedTo('/install/stage' . InstallController::getStage());
	}
	public function testRedirectStage3() {
		InstallController::setStage(3);
		$response = $this->call('GET', '/');
		$this->assertRedirectedTo('/install/stage' . InstallController::getStage());
	}
	public function testRedirectStage4() {
		InstallController::setStage(4);
		$response = $this->call('GET', '/');
		$this->assertRedirectedTo('/install/stage' . InstallController::getStage());
	}
	public function testRedirectStage5() {
		InstallController::setStage(5);
		$response = $this->call('GET', '/');
		$this->assertRedirectedTo('/install/stage' . InstallController::getStage());
	}

	public function testGetStage1() {
		$i = 1;
		InstallController::setStage($i);
		$response = $this->call('GET', '/install/stage' . $i);
		$this->assertResponseOk();
	}
	public function testGetStage2() {
		$i = 2;
		InstallController::setStage($i);
		$response = $this->call('GET', '/install/stage' . $i);
		$this->assertResponseOk();
	}
	public function testGetStage3() {
		$i = 3;
		InstallController::setStage($i);
		$response = $this->call('GET', '/install/stage' . $i);
		$this->assertResponseOk();
	}
	public function testGetStage4() {
		$i = 4;
		InstallController::setStage($i);
		$response = $this->call('GET', '/install/stage' . $i);
		$this->assertResponseOk();
	}
	public function testGetStage5() {
		$i = 5;
		InstallController::setStage($i);
		$response = $this->call('GET', '/install/stage' . $i);
		$this->assertResponseOk();
	}

	public function testSetStage1() {
        InstallController::setStage(1);
        $driver = getenv('DB');
        $response = $this->call('POST', '/install/stage1', [
            'driver' => $driver,
            'host'=> Config::get("database.connections.$driver.host"),
            'database'=> Config::get("database.connections.$driver.database"),
            'port'=> Config::get("database.connections.$driver.port"),
            'username'=> Config::get("database.connections.$driver.username"),
            'password'=> Config::get("database.connections.$driver.password"),
            'prefix' => Config::get("database.connections.$driver.prefix")
        ]);
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @depends testSetStage1
     */
    public function testSetStage2() {
        $driver = getenv('DB');
        $response = $this->call('POST', '/install/stage2', [
            'app_url' => Config::get("app.url"),
            'mod_uri' => Config::get('solder.repo_location'),
            'mirror_url' => Config::get('solder.mirror_url')
        ]);
	    $this->assertEquals(302, $response->getStatusCode());    }

    /**
     * @depends testSetStage2
     */
    public function testSetStage3() {
        $driver = getenv('DB');
        $response = $this->call('POST', '/install/stage3', [
            'email' => 'admin@admin.com',
            'username' =>'admin',
            'password' => Hash::make('admin'),
        ]);
	    $this->assertEquals(302, $response->getStatusCode());    }

    /**
     * @depends testSetStage3
     */
    public function testSetStage4() {
        $driver = getenv('DB');
        $response = $this->call('POST', '/install/stage4', [
            'key' => 'sfIvEcNueZtwKsTAIYOIYng1iuPAgavJsfIvEcNueZtwKsTAIYOIYng1iuPAgavJ',
            'name' => 'Test Key',
        ]);
	    $this->assertEquals(302, $response->getStatusCode());    }

    /**
     * @depends testSetStage4
     */
    public function testSetStage5() {
        $driver = getenv('DB');
        $response = $this->call('POST', '/install/stage5');
	    $this->assertEquals(302, $response->getStatusCode());    }

    /**
     * @depends testSetStage5
     */
    public function testSetStageDone()
    {
        $this->assertTrue(InstallController::isInstalled());
        $this->assertTrue(InstallController::getStage() === true);
        $this->be(User::find(1));
        $this->call('GET', '/');
        $this->assertResponseOk();
    }
}