<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxClass extends Model
{
    protected $primaryKey = 'tax_class_id';
    
    protected $fillable = [
        'name',
        'type',
        'model',
        'amount'
    ];
}
