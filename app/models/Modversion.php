<?php

/**
 * Class Modversion
 * @property int id
 * @property int mod_id
 * @property string version
 * @property string md5
 * @property string created_at
 * @property string updated_at
 * @property int filesize
 * @property Mod mod
 * @property Build[] builds
 */
class Modversion extends Eloquent {
	protected $table = 'modversions';
	public $timestamps = true;


	public function mod()
	{
		return $this->belongsTo('Mod');
	}

	public function builds()
	{
		return $this->belongsToMany('Build')->withTimestamps();
	}

	public function humanFilesize($unit = "")
	{
		$size = $this->filesize;
		if( (!$unit && $size >= 1<<30) || $unit == "GB")
			return number_format($size/(1<<30),2)." GB";
		if( (!$unit && $size >= 1<<20) || $unit == "MB")
			return number_format($size/(1<<20),2)." MB";
		if( (!$unit && $size >= 1<<10) || $unit == "KB")
			return number_format($size/(1<<10),2)." KB";
		return number_format($size)." bytes";
	}
}
