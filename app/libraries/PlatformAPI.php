<?php


class PlatformAPI {

	private static $url = "https://api.technicpack.net/";
	private static $buildNum = 349;

	public static function search($name) {
		return self::getPlatform('search', ['q' => $name]);
	}

	public static function news() {
		return self::getPlatform('news');
	}

	public static function packInfo($slug) {
		$res = self::getPlatform('modpack/' . $slug);
		if (isset($res['error']))
			return false;
		return $res;
	}

	private static function getPlatform($request, $params=[]) {
		$paramStr = "";
		foreach ($params as $key => $val) {
			$paramStr .= "&" . urlencode($key) . "=" . urlencode($val);
		}

		return self::getJSON(self::$url . $request . "?build=" . self::$buildNum . $paramStr);
	}

	private static function getJSON($url, $assoc=true, $useCache=true) {
		if ($useCache && Cache::has("platform_api_key_$url"))
			return Cache::get("platform_api_key_$url");

		try {
			$raw = file_get_contents($url);
			Log::info("Fetched $url Result: $raw");
			if (!$raw)
				return false;
			$res = json_decode($raw, $assoc);
			Cache::put("platform_api_key_$url", $res, 5);
			return $res;
		}catch(Exception $e) {}
		return false;
	}
}