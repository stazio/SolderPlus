<?php

class Modfile {

    // Static functions
    public static function getFilepath($name, $version) {
        return self::getModFolder() . "$name/$name-$version.zip";
    }

    public static function getModFolder() {
    	if (Cache::has('solder.mod_folder'))
    		Cache::add('solder.mod_folder', Config::get('solder.repo_location') . 'mods/', 120);
        return Cache::get('solder.mod_folder', Config::get('solder.repo_location') . 'mods/');
    }

    public static function getModFolderURL() {
	    if (Cache::has('solder.mirror_url'))
		    Cache::add('solder.mirror_url', Config::get('solder.mirror_url') . 'mods/', 120);
	    return Cache::get('solder.mirror_url', Config::get('solder.mirror_url') . 'mods/');
    }

    public static function getMcModInfo($path) {
	    $zip = new ZipArchive();
	    $zip2 = new ZipArchive();
	    if ($zip->open($path)){
		    for ($i = 0; $i < $zip->numFiles; $i++) {
			    $name = $zip->getNameIndex($i);
			    if (ends_with($name, '.jar')) {
				    $file = $zip->getFromName($name);
				    $path = explode('/', $name);
				    array_pop($path);
				    $path = implode('/', $path);
				    if (!is_dir(storage_path('temp/'. $path)))
					    mkdir(storage_path('temp/'. $path), 0777, true);
				    file_put_contents(storage_path('temp/'. $name), $file);
				    if ($zip2->open(storage_path('temp/'. $name)) === TRUE) {
					    if ($mcmod = $zip2->getFromName('mcmod.info')) {
						    $json = json_decode($mcmod, true);
						    if (isset($json['modList']))
						    	return $json['modList'][0]; // New FML format(?)
						    else {
						    	if (isset($json[0]))
						    		return $json[0]; // Old FML format(?)
						    	else
						    		return $json; // Uhhhhhhh, maybe?
						    }
					    }
				    }
				    $zip2->close();
			    }
		    }
	    }
	    return null;
    }
}