<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = [];

    public static function tableName(): string
    {
        return (new static())->getTable();
    }

    public static function routeKeyName(): string
    {
        return (new static())->getRouteKeyName();
    }

    public static function keyName(): string
    {
        return (new static())->getKeyName();
    }
}