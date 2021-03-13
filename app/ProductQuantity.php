<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductQuantity extends Model
{
    protected $primaryKey = 'product_quantity_id';

    protected $fillable = [
        'product_quantity_id',
        'product_id',
        'warehouse_id',
        'shelf_id',
        'batch',
        'stock'
    ];

    public function product()
    {
       return $this->belongsTo('App\Product', 'id', 'product_id');
    }

    public function warehouse(){
        return $this->belongsTo('App\Warehouse', 'warehouse_id', 'warehouse_id');
    }

    public function shelf(){
        return $this->belongsTo('App\Shelf', 'shelf_id', 'shelf_id');
    }
}