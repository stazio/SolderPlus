<?php

/**
 * Class UserPermission
 * @property int id
 * @property int user_id
 * @property boolean solder_full
 * @property boolean solder_users
 * @property boolean mods_create
 * @property boolean mods_manage
 * @property boolean mods_delete
 * @property boolean modpacks
 * @property boolean solder_keys
 * @property boolean solder_clients
 * @property boolean modpacks_create
 * @property boolean modpacks_manage
 * @property boolean modpacks_delete
 */
class UserPermission extends Eloquent {
	protected $table = 'user_permissions';
	public $timestamps = true;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function setModpacksAttribute($modpack_array)
	{
		if (is_array($modpack_array))
		{
			$this->attributes['modpacks'] = implode(',',$modpack_array);
		} else {
			$this->attributes['modpacks'] = null;
		}
		
	}

	public function getModpacksAttribute($value)
	{
		return preg_split ('/[,]+/', $value, -1,PREG_SPLIT_NO_EMPTY);
	}
}