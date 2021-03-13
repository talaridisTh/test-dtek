<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\CreditInvoice;
use App\Customer;
use App\CustomerAddress;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Session;
use Auth;
use PDF;

class CreditInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            $columns     = ['credit_invoice_id', 'order_id', 'invoice_date', 'invoice_status'];
            $draw        = $request->draw;
            $start       = $request->start; //Start is the offset
            $length      = $request->length; //How many records to show
            $column      = $request->order[0]['column']; //Column to orderBy
            $dir         = $request->order[0]['dir']; //Direction of orderBy
            $searchValue = $request->search['value']; //Search value
            $order_id = $request->order_id; //order_id

            //Sets the current page
            Paginator::currentPageResolver(function () use ($start, $length) {
                return ($start / $length + 1);
            });

            $customer_id = (int) $request->customer_id;
            if($customer_id < 0) $customer_id = null;

            $searchParams = [];

            $invoice_status = (int) $request->input('invoice_status');
            $searchParams['invoice_status'] = $invoice_status;

            $from = $request->input('from');
            if(!is_null($from) && $from != "") {
                $from = explode('/', $from);
                if(sizeof($from) == 3) {
                    $from = $from[2] . "-" . $from[1] . "-" . $from[0] . " 00:00:00";
                    $searchParams['from'] = $from;
                }
            }
            $to = $request->input('to');
            if(!is_null($to) && $to != "") {
                $to = explode('/', $to);
                if(sizeof($to) == 3) {
                    $to = $to[2] . "-" . $to[1] . "-" . $to[0] . " 23:59:59";
                    $searchParams['to'] = $to;
                }
            }


            $cinvoice = CreditInvoice::getCreditInvoices($order_id,$searchValue,$columns[$column],$dir,$length, $customer_id, $searchParams);

            return [
                'draw' => $draw,
                'recordsTotal' => $cinvoice->total(),
                'recordsFiltered' => $cinvoice->total(),
                'data' => $cinvoice
            ];
        }
        $data = [];
        $data['title'] = "Πιστωτικά Τιμολόγια";
        $data['notifications'] = User::getNotifications();

        return view('invoice/credit_list', compact('cinvoice', 'data'));

    }
    public function show($id)
    {
        $data['invoice'] = CreditInvoice::findOrFail($id);
        $data['order'] = $data['invoice']->order;
        $returned_products = $data['order']->getFinalizedReturnedProducts();

        $data['products'] = $returned_products;

        $data['customer'] = Customer::findOrFail($data['order']['customer_id']);
        $data['customer_address'] = CustomerAddress::find($data['order']['address_id']);
        $data['store_info'] = array(
            'logo'          => asset('assets/media/logos/tyrepro_logo.png'),
            'name'          => 'Name',
            'owner'         => 'Owner 1',
            'profession'    => 'profession',
            'address'       => 'address',
            'city'          => 'city',
            'phone'         => 'phone',
            'fax'           => '',
            'email'         => 'email',
            'afm'           => 'afm',
            'doy'           => 'doy',
            'argemi'        => 'argemi',
        );

        $pdf = PDF::loadView('invoice/credit_show', $data);

        return $pdf->stream();
    }
    public function store(InvoiceRequest $request)
    {
        $validatedData = $request->validated();
        $invoice = CreditInvoice::create($validatedData);
        return (['success' => 'Το τιμολόγιο δημιουργήθηκε με επιτυχία!', 'skipAjax' => true,'credit_invoice_id'=>$invoice->credit_invoice_id]);
    }

    public function update(InvoiceRequest $request, $id)
    {
        $validatedData = $request->validated();
        CreditInvoice::findOrFail($id)->update($validatedData);

        return array(
            'status' => 'success',
            'msg' => 'Το τιμολόγιο αποθηκεύτηκε με επιτυχία!'
        );
    }
}
