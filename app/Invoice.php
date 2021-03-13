<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'order_id',
        'invoice_date',
        'invoice_status'
    ];

    public static function getInvoices($order_id,$searchValue,$order_by,$dir,$length, $customer_id, $searchParams = [])
    {
        $invoices = Invoice::where(function($query) use ($searchValue,$order_id)
        {
            $query->orWhere('invoice_date', 'like', '%'.$searchValue.'%');
        });
        if($order_id != null) $invoices = $invoices->where('order_id', $order_id);
        if($customer_id != null) {
            $invoices = $invoices->join('orders', 'orders.order_id', '=', 'invoices.order_id');
            $invoices = $invoices->where('orders.customer_id', $customer_id);
        }
        $invoices = $invoices->where(function($query) use ($searchParams) {
            if(isset($searchParams['invoice_status']) && $searchParams['invoice_status'] != '-1') {
                $query->where('invoice_status', $searchParams['invoice_status']);
            }
            if(isset($searchParams['from']) && $searchParams['from'] != '') {
                $query->where('invoices.invoice_date', '>=', $searchParams['from']);
            }
            if(isset($searchParams['to']) && $searchParams['to'] != '') {
                $query->where('invoices.invoice_date', '<=', $searchParams['to']);
            }
        });


        $invoices = $invoices->select('invoice_id', 'invoices.order_id', 'invoice_date', 'invoice_status')
        ->orderBy($order_by, $dir)
        ->paginate($length);
        return $invoices;
    }

    public function order()
    {
        return $this->belongsTo("App\Order","order_id","order_id");
    }
}
