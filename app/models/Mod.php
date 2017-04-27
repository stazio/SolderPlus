<?php

class Mod extends Eloquent {

    const MOD_TYPE_UNIVERSAL = 0, MOD_TYPE_SERVER = 1, MOD_TYPE_CLIENT = 2;

	public $timestamps = true;

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