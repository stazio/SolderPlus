<?php
/**
 * Created by PhpStorm.
 * User: staz
 * Date: 7/28/17
 * Time: 7:42 PM
 */

class InstallTest extends TestCase
{
    public function setUp() {
        InstallController::setStage(1);
    }

    public function tearDown()
    {
        InstallController::setStage(true);
    }

    public function testInstallRedirect() {
        if (!InstallController::isInstalled()) {
            $response = $this->call('GET', '/');
            $this->assertRedirectedTo('/install/stage' . InstallController::getStage());
        }
    }

    public function testGetStage1() {
        $response = $this->call('GET', '/install/stage1');
        $this->assertResponseOk();
    }

    public function testSetStage1() {
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
        $this->assertResponseOk();
    }

    /**
     * @depends testGetStage1
     */
    public function testGetStage2() {
        $response = $this->call('GET', '/install/stage2');
        $this->assertResponseOk();
    }

    public function testSetStage2() {
        $driver = getenv('DB');
        $response = $this->call('POST', '/install/stage1', [
            'app_url' => Config::get("app.url"),
            'mod_url' => Config::get('solder.repo_location'),
            'mirror_url' => Config::get('solder.mirror_url')
        ]);
        $this->assertResponseOk();
    }

    /**
     * @depends testGetStage2
     */
    public function testGetStage3() {
        $response = $this->call('GET', '/install/stage3');
        $this->assertResponseOk();
    }

    public function testSetStage3() {
        $driver = getenv('DB');
        $response = $this->call('POST', '/install/stage1', [
            'driver' => $driver,
            'email'=> Config::get("database.connections.$driver.host"),
            'username'=> Config::get("database.connections.$driver.database"),
            'password'=> Config::get("database.connections.$driver.port"),
        ]);
        $this->assertResponseOk();
    }

    /**
     * @depends testGetStage3
     */
    public function testGetStage4() {
        $response = $this->call('GET', '/install/stage4');
        $this->assertResponseOk();
    }

    public function testSetStage4() {
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
        $this->assertResponseOk();
    }

    /**
     * @depends testGetStage4
     */
    public function testGetStage5() {
        $response = $this->call('GET', '/install/stage5');
        $this->assertResponseOk();
    }

    public function testSetStage5() {
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
        $this->assertResponseOk();
    }
}