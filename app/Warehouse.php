<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Warehouse extends Model
{
    protected $primaryKey = 'warehouse_id';

    protected $fillable = [
        'warehouse_id',
        'name'
    ];

    static function getWarehouses($limit = 50,$offset = 0)
    {
        $warehouses = DB::table('warehouses')->offset($offset)->limit($limit);

        return $warehouses;
    }

    public function shelves()
    {
        return $this->hasMany('App\Shelf', 'warehouse_id', 'warehouse_id');
    }
}
