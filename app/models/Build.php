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
}
