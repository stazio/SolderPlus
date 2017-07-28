<?php

/**
 * Class Key
 * API Key's
 * @property int id
 * @property string name
 * @property string api_key
 * @property string created_at
 * @property string updated_at
 */
class Key extends Eloquent {
    protected $fillable = ['name', 'api_key'];
}