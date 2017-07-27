<?php

/**
 * Class Mod
 *
 * @property int id
 * @property string name
 * @property string description
 * @property string author
 * @property string link
 * @property string created_at
 * @property string updated_at
 * @property string pretty_name
 * @property string donatelink
 * @property int mod_type
 * @property Modversion[] versions
 */

class Mod extends Eloquent {

    const MOD_TYPE_UNIVERSAL = 0, MOD_TYPE_SERVER = 1, MOD_TYPE_CLIENT = 2;

	public $timestamps = true;

    public static function notInBuild(Build $build)
    {
        $mods = Mod::query();
        $versions = $build->modversions()->get();
        /** @var Modversion $mod */
        foreach ($versions as $mod) {Log::info($mod->mod_id);
            $mods->where('id', '!=', $mod->mod_id);
        }
        return $mods->get();
    }

    public static function getByName($mod)
    {
        return self::where('name', '=', $mod);
    }

    public function versions()
	{
		return $this->hasMany('Modversion');
	}

    public function isUniversalMod() {
        return $this->mod_type == Mod::MOD_TYPE_UNIVERSAL;
    }

    public function isServerMod() {
        return $this->mod_type == Mod::MOD_TYPE_SERVER;
    }

    public function isClientMod() {
        return $this->mod_type == Mod::MOD_TYPE_CLIENT;
    }
}
