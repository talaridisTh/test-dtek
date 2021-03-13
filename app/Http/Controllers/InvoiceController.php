<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Invoice;
use App\Customer;
use App\CustomerAddress;
use App\Mail\PrepareOrder;
use App\User;
use App\Order;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Session;
use Auth;
use PDF;

class InvoiceController extends Controller
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
            $columns     = ['invoice_id', 'order_id', 'invoice_date', 'invoice_status'];
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

            $invoice = Invoice::getInvoices($order_id,$searchValue,$columns[$column],$dir,$length, $customer_id, $searchParams);

            return [
                'draw' => $draw,
                'recordsTotal' => $invoice->total(),
                'recordsFiltered' => $invoice->total(),
                'data' => $invoice
            ];
        }
        $data = [];
        $data['title'] = "Τιμολόγια";
        $data['notifications'] = User::getNotifications();
        $invoices = [];
        return view('invoice/list', compact('invoices', 'data'));
    }

    public function show($id)
    {
        $data['action'] = route('download-invoice', $id);
        $data['invoice'] = Invoice::findOrFail($id);
        $data['order'] = $data['invoice']->order;
        $data['discounts'] = json_decode($data['order']->customer->group->discounts, true);
        $data['enviromental_tax'] = OrderController::$environmental_tax;

        $data['payed'] = $data['order']->getPayedTotal();
        $data['products'] = $data['order']->getFinalizedProducts();
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

        return view('invoice/show', $data);
    }

    public function download(Request $request, $id) {
        $data['action'] = route('download-invoice', $id);

        $order_id = (int) $request->input('order_id');
        if(isset($order_id) && $order_id > 0) {
            $data['invoice'] = Invoice::where('order_id', $order_id)->first();
            if(is_null($data['invoice'])) {
                return response()->json([
                    'status' => 'success'
                ]);
            }
        } else {
            $data['invoice'] = Invoice::findOrFail($id);
        }
        $data['order'] = $data['invoice']->order;
        $data['discounts'] = json_decode($data['order']->customer->group->discounts, true);
        $data['products'] =  $data['order']->getFinalizedProducts();
        $data['enviromental_tax'] = OrderController::$environmental_tax;

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
        $en = $request->input('en');
        if(isset($en) && $en == 1) {
            $pdf = PDF::loadView('invoice/en/pdf', $data);
        } else {
            $pdf = PDF::loadView('invoice/pdf', $data);
        }

        $inform = $request->input('inform');
        if(isset($inform) && $inform == 1) {
            $inform = true;
        } else {
            $inform = false;
        }

        if($data['order']['order_status'] == 4 || $inform)
        {
            $this->informWarehouse($data['order']->order_id,$pdf->download('invoice.pdf'));
        }

        $not_download = $request->input('not_download');
        if(isset($not_download) && $not_download == 1) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        return $pdf->download('invoice.pdf');
    }

    public function orderReceipt(Request $request,$id)
    {
        $data['action'] = route('download-receipt', $id);
        $data['order'] = Order::findOrFail($id);
        $data['discounts'] = json_decode(Customer::findOrFail($data['order']->customer_id)->group->discounts, true);
        $data['enviromental_tax'] = OrderController::$environmental_tax;

        $data['payed'] = $data['order']->getPayedTotal();
        $data['products'] = $data['order']->getFinalizedProducts();
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

        return view('receipt/show', $data);
    }

    private function informWarehouse($order_id,$pdf)
    {
        Mail::to("apothikidnd@yahoo.com")->send(new PrepareOrder($order_id,$pdf));
    }

    public function receiptDownload(Request $request, $id)
    {
        $data['order'] = Order::findOrFail($id);
        $data['discounts'] = json_decode($data['order']->customer->group->discounts, true);
        $data['products'] =  $data['order']->getFinalizedProducts();
        $data['enviromental_tax'] = OrderController::$environmental_tax;

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

        $en = $request->input('en');
        if(isset($en) && $en == 1) {
            $pdf = PDF::loadView('receipt/en/pdf', $data);
        } else {
            $pdf = PDF::loadView('receipt/pdf', $data);
        }

        $inform = $request->input('inform');
        if(isset($inform) && $inform == 1) {
            $inform = true;
        } else {
            $inform = false;
        }

        if($data['order']['order_status'] == 4 || $inform)
        {
            $this->informWarehouse($id, $pdf->download('receipt.pdf'));
        }

        $not_download = $request->input('not_download');
        if(isset($not_download) && $not_download == 1) {
            return response()->json([
                'status' => 'success'
            ]);
        }
        return $pdf->download('receipt.pdf');
    }

    public function store(InvoiceRequest $request)
    {
        $validatedData = $request->validated();
        $invoice = Invoice::create($validatedData);
        return (['success' => 'Το τιμολόγιο δημιουργήθηκε με επιτυχία!', 'skipAjax' => true,'invoice_id'=>$invoice->invoice_id]);
    }

    public function update(InvoiceRequest $request, $id)
    {
        $validatedData = $request->validated();
        Invoice::findOrFail($id)->update($validatedData);

        return array(
            'status' => 'success',
            'msg' => 'Το τιμολόγιο αποθηκεύτηκε με επιτυχία!'
        );
    }
}
