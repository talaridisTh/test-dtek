<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    
    public function index(Request $request)
    {
        $data = Session::get('skipAjax');
        $skipAjax = false;

        if(!is_null($data) && is_bool($data)) {
            $skipAjax = $data;
        }
        //to be defined by auth system
        if($request->ajax() && !$skipAjax) {
            $columns     = ['payment_id', 'invoice_id', 'order_id', 'customer_id', 'amount', 'description', 'date_of_payment'];
            $draw        = $request->draw;
            $start       = $request->start; //Start is the offset
            $length      = $request->length; //How many records to show
            $column      = $request->order[0]['column']; //Column to orderBy
            $dir         = $request->order[0]['dir']; //Direction of orderBy
            $searchValue = $request->search['value']; //Search value
            $customer_id = $request->customer_id; //customer_id
            $order_id = $request->order_id; //order_id
            
            //Sets the current page
            Paginator::currentPageResolver(function () use ($start, $length) {
                return ($start / $length + 1);
            });

            $payment = Payment::getPayments($customer_id,$order_id,$searchValue,$columns[$column],$dir,$length);

            return [
                'draw' => $draw,
                'recordsTotal' => $payment->total(),
                'recordsFiltered' => $payment->total(),
                'data' => $payment
            ];
        }
    }

    public function store(PaymentRequest $request)
    {
        $validatedData = $request->validated();
        $payment = Payment::create($validatedData);
        return (['success' => 'Η πληρωμή δημιουργήθηκε με επιτυχία!', 'skipAjax' => true]);
    }

    public function update(PaymentRequest $request,$id)
    {
        $validatedData = $request->validated();
        Payment::findOrFail($id)->update($validatedData);

        return array(
            'status' => 'success', 
            'msg' => 'Η πληρωμή αποθηκεύτηκε με επιτυχία!'
        );
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
