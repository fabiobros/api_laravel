<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['name', 'slug', 'city', 'state'];

    public static function createPlace($data)
    {
        return self::create($data);
    }
}
