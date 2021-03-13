<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shelf extends Model
{
    protected $primaryKey = 'shelf_id';

    protected $fillable = [
        'shelf_id',
        'name',
        'warehouse_id',
        'is_product_shelf'
    ];

    static function getShelves($limit = 50,$offset = 0)
    {
        $shelves = DB::table('shelves')->offset($offset)->limit($limit);

        return $shelves;
    }

    //return -1 if free , else return order_id
    public function isWaittingShelfFree()
    {
        $result = DB::table('orders')->where('waitting_shelf_id',$this->shelf_id);
        
        if($result->count() == 0)
            return -1;

        return $result->get()[0]->order_id;
    }

    public function warehouse()
    {
       return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }
}
