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
 * @property boolean is_server_pack
 * @property boolean server_pack_is_built
 * @property string server_pack_file_path
 * @property string server_pack_url
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

    public function getServerPackFilePathAttribute() {
        return Config::get('solder.repo_location') . "serverpacks/" .
        $this->modpack->slug . "/" . $this->modpack->slug . "-" . $this->version . ".zip";
    }

    public function getServerPackUrlAttribute() {
	    return Config::get('solder.mirror_url') . "mods/" . "serverpacks/" .
            $this->modpack->slug . "/" . $this->modpack->slug . "-" . $this->version . ".zip";
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
	        if ($version->mod->isUniversalMod() || $version->mod->isServerMod()) {
                $fileZip = new ZipArchive();
                if ($fileZip->open($version->filepath) === TRUE) {
                    $fileZip->extractTo(Config::get('solder.repo_location') . "serverpacks/" .
                        $this->modpack->slug . "/root/");
                    $fileZip->close();
                }
            }
        }

        $zip = new ZipArchive();
        $zip->open($this->server_pack_file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $dir = Config::get('solder.repo_location') . "serverpacks/" .
            $this->modpack->slug . "/root";
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($dir) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        //$this->addDir(Config::get('solder.repo_location') . "serverpacks/" .
          //  $this->modpack->slug . "/root/", "", $zip);
    }
}