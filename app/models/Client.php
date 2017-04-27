<?php

/**
 * Class Client
 * @property int id
 * @property string name
 * @property string uuid
 * @property string created_at
 * @property string updated_at
 */
class Client extends Eloquent {
	public $timestamps = true;

	public function modpacks()
	{
		return $this->belongsToMany('Modpack')->withTimestamps();
	}

}