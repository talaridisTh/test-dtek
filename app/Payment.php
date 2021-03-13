<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'customer_id',
        'amount',
        'description',
        'date_of_payment'
    ];

    public static function getPayments($customer_id,$order_id,$searchValue,$order_by,$dir,$length)
    {
        $payments = Payment::where(function($query) use ($searchValue,$customer_id,$order_id)
        {
            if($customer_id != null)
                $query->where('customer_id', $customer_id);

             if($order_id != null)
                $query->where('order_id',$order_id);
            
            if($searchValue != null)
                $query->orWhere('date_of_payment', 'like', '%'.$searchValue.'%');
        })
        ->select('payment_id', 'order_id', 'customer_id', 'amount', 'description', 'date_of_payment')
        ->orderBy($order_by, $dir)
        ->paginate($length);

        return $payments;
    }   
}
