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
        $dir = Config::get('solder.repo_location') . "serverpacks/" .
            $this->modpack->slug . "/root";

        if (is_file($dir))
            unlink($dir);

	    if (!is_dir($dir))
	        mkdir($dir, 0777, true);

        $versions = $this->modversions;
	    foreach ($versions as $version) {
	        if ($version->mod->isUniversalMod() || $version->mod->isServerMod()) {
                $fileZip = new ZipArchive();
                if ($fileZip->open($version->filepath) === TRUE) {
                    $fileZip->extractTo($dir);
                    $fileZip->close();
                }
            }
        }

        $zip = new ZipArchive();
	    if (file_exists($this->server_pack_file_path))
	        unlink($this->server_pack_file_path);

        $zip->open($this->server_pack_file_path, ZipArchive::CREATE);

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