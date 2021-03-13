<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFutureQuantity extends Model
{
    protected $primaryKey = 'product_future_quantity_id';

    protected $fillable = [
        'product_future_quantity_id',
        'product_id',
        'stock',
        'arrival_date'
    ];

    static function searchFutureQuantities($searchValue,$length, $date_filter = '')
    {
        $future_quantities = ProductFutureQuantity::where(function($query) use ($searchValue){
            $query->orWhere('product_index', 'like', '%'.$searchValue.'%');
        })->where(function($query) use ($date_filter) {
            if(isset($date_filter) && $date_filter != '') {
                $query->where('product_future_quantities.arrival_date', $date_filter);
            }
        });

        $future_quantities = $future_quantities->leftJoin('products', 'products.id', '=', 'product_future_quantities.product_id')
        ->leftJoin('manufacturers', 'manufacturers.manufacturer_id', '=', 'products.manufacturer_id')
        ->leftJoin('product_indices', 'product_indices.product_id', '=', 'products.id')
        ->select('products.id as id', 'manufacturers.name as manufacturer_name', 'model', 'width', 'height_percentage', 'radial_structure', 'diameter', 'product_future_quantities.stock', 'product_future_quantities.arrival_date', 'product_future_quantity_id')
        ->selectRaw("GROUP_CONCAT(DISTINCT CONCAT(
            COALESCE(manufacturers.name,''),' ',COALESCE(products.name,''),' ', COALESCE(products.width,''),'/',COALESCE(products.height_percentage,''),COALESCE(products.radial_structure,''), COALESCE(products.diameter,''),' ',COALESCE(products.weight_flag,''),COALESCE(products.speed_flag,''),' ',COALESCE(products.model,'')
        )) as product_full_name")
        ->orderBy("product_full_name", "ASC")
        ->groupBy('product_future_quantities.product_future_quantity_id');

        if($length > 0) $future_quantities = $future_quantities->paginate($length);
        else $future_quantities = $future_quantities->get();
        
        return $future_quantities;
    }

    public function product()
    {
       return $this->belongsTo('App\Product', 'id', 'product_id');
    }
}
