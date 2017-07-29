<?php

/**
 * Class Build
 * @property int id
 * @property int modpack_id
 * @property string version
 * @property string created_at
 * @property string updated_at
 * @property string minecraft
 * @property string forge
 * @property boolean is_published
 * @property boolean is_private
 * @property string min_java
 * @property int min_memory
 * @property Modversion[] modversions
 * @property Modpack modpack
 */

class Build extends Eloquent {
	public $timestamps = true;

	public function modpack()
	{
		return $this->belongsTo('Modpack');
	}

	public function modversions()
	{
		return $this->belongsToMany('Modversion')->withTimestamps();
	}

	public function buildServerPack() {

        if (!file_exists(Config::get('solder.repo_location') . "serverpacks/" .
            $this->modpack->slug))
	    mkdir(Config::get('solder.repo_location') . "serverpacks/" .
            $this->modpack->slug, 0777, true);

	    if (!file_exists('/tmp/solderFileTemp/'))
	        mkdir('/tmp/solderFileTemp/');

        $versions = $this->modversions;
	    foreach ($versions as $version) {
            $fileZip = new ZipArchive();
	        if ($fileZip->open($version->filepath) === TRUE) {
                $fileZip->extractTo(Config::get('solder.repo_location') . "serverpacks/" .
                    $this->modpack->slug . "/root/");
                $fileZip->close();
            }
        }

        $zip = new ZipArchive();
        $zip->open(Config::get('solder.repo_location') . "serverpacks/" .
            $this->modpack->slug . "/" . $this->version . ".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);


        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(Config::get('solder.repo_location') . "serverpacks/" .
                $this->modpack->slug . "/root/"),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen(Config::get('solder.repo_location') . "serverpacks/" .
                        $this->modpack->slug . "/root") + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }


        $zip->close();

        //$this->addDir(Config::get('solder.repo_location') . "serverpacks/" .
          //  $this->modpack->slug . "/root/", "", $zip);
    }
}