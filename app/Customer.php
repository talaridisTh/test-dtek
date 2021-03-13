<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\CustomerAddress;
use App\Order;
use App\Payment;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_group_id',
        'customer_name',
        'phone',
        'mobile',
        'fax',
        'tax_id',
        'tax_office',
        'company_name',
        'company_kind',
        'email',
        'comments'
    ];

    public function addresses()
    {
        return $this->hasMany('App\CustomerAddress','customer_id','customer_id');
    }

    public function group()
    {
        return $this->hasOne('App\CustomerGroup', 'customer_group_id', 'customer_group_id');
    }

    public static function getCustomers($searchValue,$orderBy,$direction,$length)
    {
        $customers = Customer::leftJoin('customer_addresses', 'customer_addresses.customer_id', '=', 'customers.customer_id')
            ->leftJoin('tax_classes', 'tax_classes.tax_class_id', '=', 'customers.tax_id')
            ->select('tax_classes.name AS tax_name', 'customers.customer_id', 'customers.customer_name','customers.tax_id', 'customers.phone', 'customers.mobile', 'customers.company_name', 'customers.email', 'customers.fax');

        if(!empty($searchValue)){
            $customers = $customers->where('customers.customer_name', 'like', '%'.$searchValue.'%')
                ->orWhere('customers.phone', 'like', '%'.$searchValue.'%')
                ->orWhere('customers.mobile', 'like', '%'.$searchValue.'%')
                ->orWhere('customers.email', 'like', '%'.$searchValue.'%')
                ->orWhere('customers.company_name', 'like', '%'.$searchValue.'%')
                ->orWhere('customers.tax_id', 'like', '%'.$searchValue.'%')
                ->orWhere('customer_addresses.address_1', 'like', '%'.$searchValue.'%')
                ->orWhere('customer_addresses.city', 'like', '%'.$searchValue.'%')
                ->orWhere('customer_addresses.firstname', 'like', '%'.$searchValue.'%')
                ->orWhere('customer_addresses.lastname', 'like', '%'.$searchValue.'%');
        }

        $customers = $customers->orderBy($orderBy, $direction)
        ->groupBy('customers.customer_id')
        ->paginate($length);

        return $customers;
    }

    public function updateWebsitePassword($password)
    {
        $salt = substr(md5(uniqid(rand(), true)),0,9);
        $password = sha1($salt . sha1($salt . sha1($password)));
        DB::update("UPDATE `tyreprodb`.`oc_customer` SET `status`=1,salt = ?, password = ?, code = '' WHERE customer_id = ?", array($salt,$password,$this->customer_id));
    }

    public function orders()
    {
        return $this->hasMany('App\Order','customer_id','customer_id');
    }

    public function getOrderTotal()
    {
        return Order::where('customer_id', $this->customer_id)->where('order_status', '<>', '7')->sum('order_total');
    }

    public function getOrdersPaidTotal()
    {
        return Payment::leftJoin('orders', 'orders.order_id', '=', 'payments.order_id')
                        ->where('payments.customer_id', $this->customer_id)
                        ->where('orders.order_status', '<>', '7')
                        ->sum('amount');
    }

    public function getPaymentsTotalNoOrder()
    {
        return Payment::where('payments.customer_id', $this->customer_id)
                        ->whereNull('payments.order_id')
                        ->sum('amount');
    }
}
