<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Manufacturer extends Model
{
    protected $primaryKey = 'manufacturer_id';

    protected $fillable = [
        'manufacturer_id',
        'name',
        'image',
    ];

    static function getManufacturerByProductId($productId) {
        $manufacturer = DB::table('manufacturers AS m')
                            ->select('m.name')
                            ->leftJoin('products AS p', 'm.manufacturer_id', '=', 'p.manufacturer_id')
                            ->where('p.id', '=', $productId)
                            ->first();
        return $manufacturer;
    }
}
