<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $primaryKey = 'address_id';

    protected $fillable = [
        'customer_id',
        'firstname',
        'lastname',
        'company',
        'address_1',
        'address_2',
        'city',
        'postcode',
        'country_id'
    ];

    public static function searchCustomerAdress($customer_id,$searchValue,$order_by,$dir,$length)
    {
        $addresses = CustomerAddress::where('customer_id', $customer_id)
        ->where(function($query) use ($searchValue,$customer_id){
            $query->orWhere('firstname', 'like', '%'.$searchValue.'%');
            $query->orWhere('lastname', 'like', '%'.$searchValue.'%');
            $query->orWhere('company', 'like', '%'.$searchValue.'%');
            $query->orWhere('address_1', 'like', '%'.$searchValue.'%');
            $query->orWhere('address_2', 'like', '%'.$searchValue.'%');
            $query->orWhere('city', 'like', '%'.$searchValue.'%');
            $query->orWhere('postcode', 'like', '%'.$searchValue.'%');
        })
        ->leftJoin('countries', 'countries.country_id', '=', 'customer_addresses.country_id')
        ->select('countries.name AS country_name', 'customer_addresses.address_id','customer_addresses.firstname', 'customer_addresses.lastname', 'customer_addresses.company', 'customer_addresses.address_1', 'customer_addresses.address_2','customer_addresses.city','customer_addresses.postcode','customer_addresses.country_id')
        ->orderBy($order_by, $dir)
        ->paginate($length);
        return $addresses;
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id','customer_id');
    }

    public function country()
    {
        return $this->hasOne('App\Country','country_id','country_id');
    }
}
