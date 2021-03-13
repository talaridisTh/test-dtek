<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $primaryKey = 'order_product_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_quantity_id',
        'warehouse_id',
        'shelf_id',
        'batch',
        'name',
        'quantity',
        'price',
        'product_tax',
        'tax_class_id',
        'environmental_tax'
    ];

    public function order()
    {
        return $this->belongsTo('App\Order','order_id','order_id');    
    }

    public function quantity()
    {
        return $this->hasOne('App\ProductQuantity','product_quantity_id','product_quantity_id');
    }
    
    public function details()
    {
        return $this->hasOne('App\Product','id','product_id');
    }
}
