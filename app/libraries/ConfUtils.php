<?php

class ConfUtils {
    public static function save($key, $value) {
        Config::write($key, $value);
        Config::set($key, $value);
        Cache::put("conf_$key", $value, 1);
    }


    public static function get($key) {
        if (Cache::has("conf_$key"))
            return Cache::get("conf_$key");
        return Config::get($key);
    }

    public static function saveAll($arr) {
        foreach ($arr as $key => $value) {
            ConfUtils::save($key, $value);
        }
    }
}