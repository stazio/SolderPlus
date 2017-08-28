<?php

class UpdateUtils {

	public static $githubclient;
	const OWNER = 'stazio', REPO = 'solderplus';

	public static function init() {
	
		self::$githubclient = new \Github\Client(new \Github\HttpClient\CachedHttpClient(array('cache_dir' => storage_path() . '/github-api-cache')));

	}

	public static function getUpdateCheck() {
		$allVersions = self::getAllVersions();

		if(!array_key_exists('error', $allVersions)) {
			$version = self::getLatestVersion();
			if (!isset($version['tag_name']) || self::version_compare(self::getLatestVersion()['tag_name'], SOLDER_VERSION, '>')) {
				return true;
			}
		}
		
		return false;
		
	}

	public static function getLatestVersion() {

		$allVersions = self::getAllVersions();
		if(array_key_exists('error', $allVersions)) {
			return $allVersions;
		}
		return $allVersions[0];

	}

	public static function getAllVersions() {

		try {
			return self::$githubclient->api('repo')->releases()->all(self::OWNER, self::REPO);
		} catch (RuntimeException $e){
			return array('error' => 'Unable to pull version from Github - ' . $e->getMessage());
		}

	}

	public static function getCommitInfo($commit = null) {

		if (is_null($commit)) {
			$commit = self::getLatestVersion()['commit']['sha'];		
		}

		try {
			return self::$githubclient->api('repo')->commits()->show(self::OWNER, self::REPO, $commit);
		} catch (RuntimeException $e){
			return array('error' => 'Unable to pull commit info from Github - ' . $e->getMessage());
		}

	}

	public static function getLatestChangeLog($branch = 'master') {

		try {
			return self::$githubclient->api('repo')->commits()->all(self::OWNER, self::REPO, array('sha' => $branch));
		} catch(RuntimeException $e){
			return array('error' => 'Unable to pull changelog from Github - ' . $e->getMessage());
		}

	}

	private static function version_compare($first, $second, $op) {
		return $first != $second;
	}
}