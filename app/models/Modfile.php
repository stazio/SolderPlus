<?php

class Modfile {

    // Static functions
    public static function getFilepath($name, $version) {
        return self::getModFolder() . "$name/$name-$version.zip";
    }

    public static function getModFolder() {
        return Config::get('solder.repo_location') . '/mods/';
    }
}