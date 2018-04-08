<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $fillable = ['key', 'value'];

    public static function getValue($key)
    {
        return static::whereKey($key)->value('value');
    }

    public static function setValue($key, $value)
    {
        return static::updateOrCreate(['key' => $key],['value' => $value]);
    }

    public static function setValues(array $values)
    {
        return array_map(function($key, $value){
            return static::updateOrCreate(['key' => $key],['value' => $value]);
        }, array_keys($values), $values);
    }

    public static function getValues(array $keys)
    {
        return array_map(function($key){
            return static::whereKey($key)->value('value');
        }, $keys);
    }

}
