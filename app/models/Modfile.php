<?php

class Modfile {

    // Static functions
    public static function getFilepath($name, $version) {
        return self::getModFolder() . "$name/$name-$version.zip";
    }

    public static function getModFolder() {
    	if (Cache::has('solder.mod_folder'))
    		Cache::add('solder.mod_folder', Config::get('solder.repo_location') . '/mods/', 120);
        return Cache::get('solder.mod_folder', Config::get('solder.repo_location') . '/mods/');
    }

    public static function getModFolderURL() {
	    if (Cache::has('solder.mirror_url'))
		    Cache::add('solder.mirror_url', Config::get('solder.mirror_url') . '/mods/', 120);
	    return Cache::get('solder.mirror_url', Config::get('solder.mirror_url') . '/mods/');
    }
}