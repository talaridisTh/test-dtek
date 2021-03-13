<?php

namespace App;
use DB;
use App\Payment;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_id',
        'address_id',
        'order_total',
        'discount_type',
        'discount_amount',
        'shipping_cost',
        'payment_cost',
        'manage_cost',
        'payment_type',
        'payment_type_number',
        'comments',
        'type_of_receipt',
        'order_status',
        'waitting_shelf_id',
        'order_status'
    ];

    public static function getOrders($searchValue,$orderBy,$direction,$length,$searchParams = [], $customer_id = -1)
    {
        $orders = Order::where(function($query) use ($searchValue){
            $query->orWhere('order_id', 'like', '%'.$searchValue.'%');
            $query->orWhere('customers.customer_name', 'like', '%'.$searchValue.'%');
        })->where(function($query) use ($searchParams) {
            if(isset($searchParams['status']) && $searchParams['status'] != '') {
                $query->where('order_status', $searchParams['status']);
            }
            if(isset($searchParams['from']) && $searchParams['from'] != '') {
                $query->where('orders.created_at', '>=', $searchParams['from']);
            }
            if(isset($searchParams['to']) && $searchParams['to'] != '') {
                $query->where('orders.created_at', '<=', $searchParams['to']);
            }
        });

        if($customer_id != -1) {
            $orders = $orders->where('orders.customer_id', $customer_id);
        }

        $orders = $orders->leftJoin('customers', 'orders.customer_id', '=', 'customers.customer_id')
        ->select('order_id', 'customers.customer_id', 'customers.customer_name', 'order_total', 'order_status', 'manage_cost', 'payment_cost', 'shipping_cost', 'discount_amount', 'discount_type', 'orders.created_at')
        ->orderBy($orderBy,$direction);
        if($length > 0) $orders = $orders->paginate($length);
        else $orders = $orders->get();
        foreach($orders as &$o) {
            $o['products'] = $o->getFinalizedProducts();
            $products = $o['products'];
            $total_discount_from_product = 0;
            $o['paid'] = $o->getPayedTotal();
            $paid = $o['paid'];
            $subtotal = 0;
            $tax_total = 0;
            $environmental_tax_total = 0;

            foreach($products as $p) {
                $current_price = $p['price'];
                $current_tax = $p['product_tax'];
                $current_qty = (int) $p['quantity'];

                $subtotal += ($current_price * $current_qty);

                $tax_total += ($current_tax * $current_qty);

                $environmental_tax = 0;
                if($p['environmental_tax'] != null) $environmental_tax = $p['environmental_tax'];
                if($environmental_tax == null || $environmental_tax  < 0) $environmental_tax  = 0;
                $environmental_tax_total += ($environmental_tax * $current_qty);

                $discount_perc = 0;
                if($p['discounts'] != null && $p['discounts']['discount'] != null) {
                    $discount_perc = $p['discounts']['discount'];
                }
                if($discount_perc == null || $discount_perc < 0) $discount_perc = 0;
                $discount_total = $current_price * $current_qty * ($discount_perc / 100);
                if($discount_total == null) {
                    $discount_total = 0;
                }
                $total_discount_from_product += ($discount_total * 100)/100;
            }
            $total_discount_from_product = round($total_discount_from_product*100)/100;

            $shipping_cost = $o->shipping_cost;
            if($shipping_cost == null) $shipping_cost = 0;
            $payment_cost = $o->payment_cost;
            if($payment_cost == null) $payment_cost = 0;

            $total = $subtotal + $tax_total + $shipping_cost + $payment_cost + $environmental_tax_total;

            $discount_amount = $o->discount_amount;
            if($discount_amount == null) $discount_amount = 0;
            
            $final_discount = 0;
            $discount_type = $o->discount_type;
            if($discount_type == 1) {
                //percentage
                $percentage = $discount_amount / 100;
                $final_discount = $total * $percentage;
            } else if($discount_type == 2) {
                $final_discount = $discount_amount;
            }

            $manage_cost = $o->manage_cost;
            if($manage_cost == null || $manage_cost < 0) $manage_cost = 0;

            $total += $manage_cost;

            $total_with_discount = $total - $final_discount - $total_discount_from_product;
            $remainder = $total_with_discount - $paid;

            $o['order_total'] = number_format($total_with_discount, 2, '.', '');
            $o['remainder'] = number_format($remainder, 2, '.', '');
        }
        return $orders;
    }

    public function products()
    {
        return $this->hasMany('App\OrderProduct','order_id','order_id');
    }


    public function getInventoryProducts()
    {
        $order_products = $this->hasMany('App\OrderProduct','order_id','order_id')->get();
        foreach($order_products as &$pr) {
            $details = $pr->details;
            $manufacturer_name = $details->manufacturer->name;
            $pr['details'] = $details;
            $pr['manufacturer_name'] = $manufacturer_name;
            $pr['shelf'] = Shelf::findOrFail($pr->shelf_id)->name;
            $pr['batch'] = $pr->batch;
        }
        return $order_products;
    }

    public function getFinalizedProducts()
    {
        $discount = json_decode($this->customer->group->discounts, true);
        $order_products = $this->hasMany('App\OrderProduct','order_id','order_id')->get();
        foreach($order_products as &$pr) {
            $details = $pr->details;
            $manufacturer_name = $details->manufacturer->name;
            $pr['details'] = $details;
            $pr['manufacturer_name'] = $manufacturer_name;
            $discount_index = $pr['details']['manufacturer_id'] . "::" . $pr['details']['category_id'];
            $pr['discounts'] = !isset($discount[$discount_index]) ? [
                'return' => 0,
                'discount' => 0
            ] : $discount[$discount_index];

            $order_product_full_price = (float) $pr['price'] * $pr['quantity'];
            $pr['discounts_totals'] = [
                'return' => ($pr['discounts']['return'] / 100) * $order_product_full_price,
                'discount' => ($pr['discounts']['discount']/ 100) * $order_product_full_price
            ];
        }
        return $order_products;
    }

    public function getFinalizedReturnedProducts()
    {
        $order_returned_products = $this->returnedProducts;
        $counter = 0;
        foreach($order_returned_products as $pr) {
            $details = $pr->details;
            $manufacturer_name = $details->manufacturer->name;
            $order_returned_products[$counter] = $pr;
            $order_returned_products[$counter]['details'] = $details;
            $order_returned_products[$counter]['manufacturer_name'] = $manufacturer_name;
            $shelf_name = $pr->product_quantity->shelf->name;
            $order_returned_products[$counter]['shelf_name'] = $shelf_name;
            $counter++;
        }
        return $order_returned_products;
    }

    public function returnedProducts()
    {
        return $this->hasMany('App\OrderReturnedProduct', 'order_id', 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id','customer_id');
    }

    public function clearProducts()
    {
        OrderProduct::where('order_id',$this->order_id)->delete();
    }

    public function invoice()
    {
        return $this->hasMany('App\Invoice', 'order_id', 'order_id');
    }

    public function creditInvoice()
    {
        return $this->hasMany('App\CreditInvoice', 'order_id', 'order_id');
    }

    public function getPayedTotal()
    {
        return Payment::where('order_id',$this->order_id)->sum('amount');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment','order_id','order_id');
    }
    public function waittingShelf()
    {
        return $this->hasOne('App\Shelf','shelf_id','waitting_shelf_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('is_active', function(Builder $builder) {
            $builder->where('order_status','>', 0);
        });
    }
}
