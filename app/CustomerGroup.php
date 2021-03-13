<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $primaryKey = 'customer_group_id';

    protected $fillable = [
        'name',
        'discounts'
    ];
}
