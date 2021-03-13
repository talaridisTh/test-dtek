<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Dimensions;

class Product extends Model
{
    protected $fillable = [
        'manufacturer_id',
        'category_id',
        'name',
        'image',
        'model',
        'notify_quantity',
        'description',
        'width',
        'height_percentage',
        'radial_structure',
        'diameter',
        'fitting_position',
        'speed_flag',
        'weight_flag',
        'tube_type',
        'is_heavy',
        'comments'
    ];

    static function searchProducts($searchValue,$orderBy,$dir,$length, $searchParams = [],$product_id = -1)
    {


        $products = Product::where(function($query) use ($searchValue){
            $query->orWhere('width', 'like', '%'.$searchValue.'%');
            $query->orWhere('model', 'like', '%'.$searchValue.'%');
            $query->orWhere('products.name', 'like', '%'.$searchValue.'%');
            $query->orWhere('height_percentage', 'like', '%'.$searchValue.'%');
            $query->orWhere('product_index', 'like', '%'.$searchValue.'%');
        })->where(function($query) use ($searchParams) {
            if(isset($searchParams['manufacturer']) && $searchParams['manufacturer'] != -1) {
                $query->where('products.manufacturer_id', (int) $searchParams['manufacturer']);
            }
            if(isset($searchParams['category']) && $searchParams['category'] != -1) {
                $query->where('products.category_id', (int) $searchParams['category']);
            }
        })->where(function($query) use ($searchParams) {
            $index = 0;
            if(isset($searchParams['dimensions']))
                foreach($searchParams['dimensions'] as $dimension)
                {
                    if(empty($dimension))
                        continue;

                    if($index == 0)
                        $query->where('product_indices.product_index', 'like', $dimension. '%');
                    else
                        $query->orWhere('product_indices.product_index', 'like', $dimension. '%');

                    $index++;
                }
        })->orderByDesc("width");
/*task 4*/
        if(isset($searchParams['extra_dimension'])) {
            $products = $products->orWhere('product_indices.product_index', 'like', $searchParams['extra_dimension']. '%');
        }

        if($product_id != -1 && $product_id != null)
            $products = $products->where('products.id',$product_id);

        $products = $products->leftJoin('manufacturers', 'manufacturers.manufacturer_id', '=', 'products.manufacturer_id')
        ->leftJoin('product_indices', 'product_indices.product_id', '=', 'products.id')
        ->leftJoin('product_quantities', 'product_quantities.product_id', '=', 'products.id')
        ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'product_quantities.warehouse_id')
        ->leftJoin('product_future_quantities', 'product_future_quantities.product_id', '=', 'products.id')
        ->select('products.id as id', 'manufacturers.name as manufacturer_name','products.description','products.name','tube_type','model', 'width', 'height_percentage', 'radial_structure', 'diameter', 'speed_flag', 'weight_flag', 'fitting_position')
        ->selectRaw('SUM(COALESCE(product_quantities.stock, 0)) as total_qty')
        ->selectRaw('(CASE WHEN products.category_id in (18, 20) THEN 1 ELSE 0 END) as is_samprela')
        ->selectRaw("GROUP_CONCAT(DISTINCT CONCAT(product_quantities.stock,'::',warehouses.name,'::',warehouses.warehouse_id,'::',product_quantities.batch)) as stock_info")
        ->selectRaw("GROUP_CONCAT(DISTINCT CONCAT(product_future_quantities.stock,'::',product_future_quantities.arrival_date)) as future_stock_info");

        if($orderBy == 'product_full_name') {
            $products = $products->selectRaw("GROUP_CONCAT(DISTINCT CONCAT(
                COALESCE(manufacturers.name,''),' ',COALESCE(products.name,''),' ', COALESCE(products.width,''),'/',COALESCE(products.height_percentage,''),COALESCE(products.radial_structure,''), COALESCE(products.diameter,''),' ',COALESCE(products.weight_flag,''),COALESCE(products.speed_flag,''),' ',COALESCE(products.model,'')
            )) as product_full_name");
        }

        if(isset($searchParams['instock']) && $searchParams['instock'] == 1) {
            $products = $products->having('total_qty','>',0);
        }

        if($orderBy == 'total_qty')
            $products = $products->having('total_qty','>',0);

        if(isset($searchParams['enforce_order_by_qty'])) {
            $products = $products->orderBy('total_qty', 'DESC');
        }

        if(!empty($searchParams['dimensions'])) {
            $index = 0;
            $order_by_str = '';
            foreach($searchParams['dimensions'] as $dim) {

                if(empty($dimension))
                    continue;

                $order_by_str .= "case when `product_indices`.`product_index` like '$dim%' then 0 else 1 end";
                if($index < sizeof($searchParams['dimensions']) - 1) $order_by_str .= ',';
                $index++;
            }
            if(!empty($order_by_str))
                $products = $products->orderByRaw($order_by_str);
        } else {
            $products = $products->orderBy($orderBy,$dir);
        }
		if ($orderBy == 'diameter') {
			$products = $products->orderBy('diameter',$dir)->orderBy('width',$dir)->orderBy('height_percentage',$dir);
		} else {
			$products = $products->orderBy($orderBy,$dir);
        }

        if(!isset($searchParams['enforce_order_by_qty'])) {
            $products = $products->orderBy('total_qty', 'DESC');
        }
		//$products = $products->orderBy('total_qty', 'DESC');
        $products = $products->groupBy('products.id');

        if($length > 0) $products = $products->paginate($length);
        else $products = $products->get();

        return $products;
    }

    public function saveIndexes()
    {
        

        ProductIndex::where('product_id',$this->id)->delete();

        $index1 = $this->width.$this->height_percentage.$this->radial_structure.$this->diameter;
        $index2 = $this->width.$this->height_percentage.$this->diameter;


        $indexes[] = array('product_id'=>$this->id,'product_index' => $index1);
        if($index1 != $index2) {
            $indexes[] = array('product_id'=>$this->id,'product_index' => $index2);
        }
        $indexes[] = array('product_id'=>$this->id,'product_index' => $this->width.'/'.$this->height_percentage.$this->radial_structure.$this->diameter);
        $indexes[] = array('product_id'=>$this->id,'product_index' => $this->width.'/'.$this->height_percentage.$this->radial_structure.'-'.$this->diameter);

        ProductIndex::insert($indexes);
    }

    public function sales()
    {
        return $this->hasMany('App\Product','sale_product','id','id');
    }

    public function prices()
    {
        return DB::table('product_prices')->where('product_id',$this->id)->first();
    }

    public function savePrices($attributes)
    {
        DB::table('product_prices')->where('product_id',$this->id)->delete();
        DB::table('product_prices')->insert(
            ['general_price'=>$attributes['general_price'],
            'wholesale_price'=>$attributes['wholesale_price'],
            'buying_price'=>$attributes['buying_price'],
            'product_id'=>$this->id
            ]
        );
    }

    public function indexes()
    {
        return $this->hasMany('App/ProductIndex','product_id','product_id');
    }

    public function clearProductRows()
    {
        DB::table('product_prices')->where('product_id',$this->id)->delete();
        DB::table('product_future_quantities')->where('product_id',$this->id)->delete();
        DB::table('product_quantities')->where('product_id',$this->id)->delete();
        DB::table('product_indices')->where('product_id',$this->id)->delete();
    }

    public function quantities()
    {
        return $this->hasMany('App\ProductQuantity', 'product_id', 'id')->orderBy('batch','asc');
    }

    public function futureQuantities()
    {
        return $this->hasMany('App\ProductFutureQuantity', 'product_id', 'id');
    }

    public function manufacturer()
    {
        return $this->hasOne('App\Manufacturer','manufacturer_id','manufacturer_id');
    }

    public function category()
    {
        return $this->hasOne('App\Category','category_id','category_id');
    }
}
