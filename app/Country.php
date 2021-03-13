<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $primaryKey = 'country_id';

    protected $fillable = [
        'name',
        'iso_code_2',
        'iso_code_3'
    ];

    public static function getCountries()
    {
        return Country::all();
    }
}
