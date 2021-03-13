<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Mail\PrepareOrder;
use App\Mail\SendOffer;
use Illuminate\Support\Facades\Mail;
use App\Manufacturer;
use App\Order;
use App\Customer;
use App\CustomerAddress;
use App\CustomerGroup;
use App\TaxClass;
use App\Country;
use App\OrderProduct;
use App\OrderReturnedProduct;
use App\Payment;
use App\Product;
use App\ProductQuantity;
use App\Shelf;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use Auth;
use PDF;

class OrderController extends Controller
{
    public static $environmental_tax = 0.5208;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $data = Session::get('skipAjax');
        $skipAjax = false;
        $orders=array();

        if(!is_null($data) && is_bool($data)) {
            $skipAjax = $data;
        }
        //to be defined by auth system
        if($request->ajax() && !$skipAjax) {
            $columns     = ['order_id', 'customer_id', 'order_total', 'order_status', 'created_at'];
            $draw        = $request->draw;
            $start       = $request->start; //Start is the offset
            $length      = $request->length; //How many records to show
            $column      = $request->order[0]['column']; //Column to orderBy
            $dir         = $request->order[0]['dir']; //Direction of orderBy
            $searchValue = $request->search['value']; //Search value

            //Sets the current page
            Paginator::currentPageResolver(function () use ($start, $length) {
                return ($start / $length + 1);
            });

            $user_id = Auth::id();
            $customer_id = (int) $request->customer_id;

            $searchParams = [];
            $order_status = (int) $request->input('order_status');
            if($order_status > 0) {
                $searchParams['status'] = $order_status;
            }

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
            if($customer_id > 0) {
                $orders = Order::getOrders($searchValue,$columns[$column],$dir,$length, $searchParams, $customer_id);
            } else {
                $orders = Order::getOrders($searchValue,$columns[$column],$dir,$length, $searchParams, -1);
            }

            return [
                'draw' => $draw,
                'recordsTotal' => $orders->total(),
                'recordsFiltered' => $orders->total(),
                'data' => $orders
            ];
        }
        $data = [];
        $data['title'] = "Παραγγελίες";
        $data['notifications'] = User::getNotifications();

        return view('order/list', compact('orders', 'data'));
    }

    public function addWaittingShelf(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|numeric',
            'shelf_id' => 'required|numeric',
        ]);

        $shelf = Shelf::findOrFail($validatedData['shelf_id']);
        if($shelf->is_product_shelf)
        {
            return  ['error'=>'Το ράφι δεν είναι αναμονής'];

        }
        $order_id = $shelf->isWaittingShelfFree();
        if($order_id != -1)
        {
            return ['error'=>'Το ράφι είναι πιασμένο απο την παραγκελία με αριθμό '.$order_id];
        }
        $order = Order::findOrFail($validatedData['order_id']);

        $order->waitting_shelf_id = $validatedData['shelf_id'];
        $order->order_status = 3;
        $order->update();
        return ['success'=>''];
    }

    public function create()
    {
        $data = array();
        $data['item'] = array();
        $data['customer_groups'] = CustomerGroup::all();
        $data['taxclasses'] = TaxClass::all();
        $data['countries'] = Country::all();
        $data['order_products'] = [];

        $data['isEdit'] = false;
        $data['action'] = route('orders.store');
        $data['update_action'] = route('orders.update', -1);
        $data['method'] = 'POST';

        $data['can_issue_invoice'] = true;
        $data['can_issue_credit_invoice'] = true;
        $data['title'] = "Νέα Παραγγελία";
        $data['notifications'] = User::getNotifications();
        $data['environmental_tax'] = OrderController::$environmental_tax;
        $data['order'] = [];

        return  view('order/create', compact('data'));
    }


    public function store(OrderRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['order_total'] = 0;
        $validatedData['shipping_method'] = (int) $validatedData['shipping_method'];
        $order = Order::create($validatedData);
        $order->shipping_method = $validatedData['shipping_method'];
        $order->update();
        return ([
            'success' => 'Order is successfully saved',
            'skipAjax' => true,
            'order_id' => $order->order_id,
            'redirect_to' => route('orders.edit', $order->order_id)
        ]);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $data['title'] = "Παραγγελία : " . $id;
        $data['notifications'] = User::getNotifications();

        return view('order/show', compact('order'));
    }

    public function edit(Order $order)
    {

      /*  ---->Δεν χρειαζετε η σειρα αυτη εβαλα σαν παραμετρο το model οποτε το παιρνει αυτοματα ποιο ειναι <---*/
//        $order = Order::with('customer')->findOrFail($id);

         /* --> 1ος τροπος βαζουμε εδω το να μην βρισκει τα null  <---  */
        /*  --> 2ος το βαζουμε στο model Customer ετσι το εκανα <---  */

        $data = array(
            'order' => $order,
            'customer' => $order->customer,
            'json_discounts' => $order->customer->group->discounts,
            'discounts' => json_decode($order->customer->group->discounts, true),
            'customer_addresses' => json_encode($order->customer->addresses),
//            'customer_addresses' => json_encode($order->customer->addresses()->whereNotNUll("address_1")->get())   /*1ος τροπος*/
        );

        $data['order_products'] =  $order->getFinalizedProducts();

        $order_returned_products = $order->getFinalizedReturnedProducts();

        if(count($order_returned_products) == 0)
            $data['order_returned_products'] = array();
        else
            $data['order_returned_products'] = $order_returned_products;

        $data['customer_groups'] = CustomerGroup::all();
        $data['taxclasses'] = TaxClass::all();
        $data['countries'] = Country::all();

        $data['payed'] = $order->getPayedTotal();
        $data['products_size'] = count($order->products);

        $data['can_issue_invoice'] = true;
        $invoices = $order->invoice;
        foreach($invoices as $inv) {
            if($inv->invoice_status == 0) {
                $data['can_issue_invoice'] = false;
                break;
            }
        }

        //it prints receipt
        if($order->type_of_receipt != 2)
            $data['can_issue_invoice'] = true;

        $returned = $order->returnedProducts;
        $data['can_issue_credit_invoice'] = sizeof($returned) > 0;
        $credit_invoices = $order->creditInvoice;
        foreach($credit_invoices as $inv) {
            if($inv->invoice_status == 0) {
                $data['can_issue_credit_invoice'] = false;
                break;
            }
        }

        $data['isEdit'] = true;
        $data['action'] = route('orders.update', $order->order_id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Παραγγελίας : " . $order->order_id;
        $data['notifications'] = User::getNotifications();
        $data['environmental_tax'] = OrderController::$environmental_tax;

        return  view('order/create', compact('data'));
    }

    public function update(OrderRequest $request,$id)
    {
        $return_products = $request->return_products;
        $order_returned_products = [];
        if(!is_null($return_products)) {
            foreach($return_products as $product_id => $return_qty) {
                $return_quantity = $return_qty['quantity'];
                $return_product_quantity_id = $return_qty['product_quantity_id'];
                $return_product_checked = isset($return_qty['checked']);
                if($return_product_checked) {
                    $order_returned_products[$product_id] = [
                        "quantity" => $return_quantity,
                        "product_quantity_id" => $return_product_quantity_id
                    ];
                }
            }
        }
        $validatedData = $request->validated();

        $order = Order::findOrFail($id);

        if($order->order_status != 3)
            $order->waitting_shelf_id = null;

        $decrease_stock = false;
        $increase_stock = false;

        //if status = sent decrease stock
        if($order->order_status < 4 && $validatedData['order_status'] == 4)
            $decrease_stock = true;

        //if status = canceled increase stock
        if($order->order_status != 7 && $validatedData['order_status'] == 7)
            $increase_stock = true;

        $order->shipping_method = $validatedData['shipping_method'];
        $order->update($validatedData);
        $previous_products = $order->products;

        $dont_change_products = [];
        foreach($validatedData['order_product'] as $order_product)
        {
            $pid = $order_product['product_id'];
            if(isset($order_returned_products[$pid])) {
                $qty = $order_returned_products[$pid]['quantity'];
                $qty_id = $order_returned_products[$pid]['product_quantity_id'];
                foreach($previous_products as $p) {
                    if($p->product_id == $pid && $p->product_quantity_id == $qty_id) {
                        $dont_change_products[] = $p;
                        break;
                    }
                }
            }
        }

        $order->clearProducts();

        $shipping_cost = (float) $order->shipping_cost;
        $payment_cost = (float) $order->payment_cost;

        $discount_type = (int) $order->discount_type;
        $discount_amount = (float) $order->discount_amount;

        $subtotal = 0;
        $returned_subtotal = 0;
        $returned = false;

        foreach($validatedData['order_product'] as &$order_product)
        {
            $found = false;
            $addProductInOrder = true;
            foreach($dont_change_products as $p) {
                if($p->product_id == $order_product['product_id'] && $p->product_quantity_id == $order_product['product_quantity_id']) {
                    $pid = $p->product_id;
                    $order_product['product_id'] = $pid;
                    $order_product['order_id'] = $id;
                    $order_product['product_quantity_id'] = $p->product_quantity_id;
                    $order_product['quantity'] = $p->quantity;
                    $order_product['price'] = $p->price;
                    $order_product['product_tax'] = $p->product_tax;
                    $order_product['tax_class_id'] = $p->tax_class_id;

                    if(isset($p->environmental_tax)) {
                        $order_product['environmental_tax'] = $p->environmental_tax;
                    } else {
                        $order_product['environmental_tax'] = 0;
                    }

                    $quantity = ProductQuantity::findOrFail($p->product_quantity_id);

                    $shelf = $quantity->shelf;
                    $warehouse = $quantity->warehouse;

                    $diff_qty = $order_product['quantity'];
                    //Increase stock with returned product quantity
                    if(isset($order_returned_products[$pid])) {
                        $qty = $order_returned_products[$pid]['quantity'];
                        $current_qty = $order_product['quantity'];
                        $diff_qty = (int) $current_qty - (int) $qty;
                        $quantity->stock += (int) $qty;
                        $quantity->update();
                    }

                    $order_product['shelf_id'] = $shelf->shelf_id;
                    $order_product['batch'] = $quantity->batch;
                    $order_product['warehouse_id'] = $warehouse->warehouse_id;
                    $product_qty = (int) $p->quantity;
                    $product_price = (float) $p->price;
                    $product_tax = (float) $p->product_tax;
                    $environmental_tax = (float) $order_product['environmental_tax'];

                    $found = true;
                    if(isset($qty)) {
                        $product_qty = $qty;
                        $order_product['quantity'] = $qty;
                    }

                    $product_total = $product_qty * ($product_price + $product_tax + $environmental_tax);
                    $returned_subtotal += (float) $product_total;

                    OrderReturnedProduct::create($order_product);
                    $addProductInOrder = $diff_qty > 0;
                    $order_product['quantity'] = $diff_qty;
                    if($addProductInOrder) {
                        $subtotal += $diff_qty * ($product_price + $product_tax + $environmental_tax);
                    }
                    $returned = true;
                    break;
                }
            }

            if(!$found) {
                $order_product['order_id'] = $id;
                $quantity = ProductQuantity::findOrFail($order_product['product_quantity_id']);

                $shelf = $quantity->shelf;
                $warehouse = $quantity->warehouse;

                if($decrease_stock)
                {
                    $quantity->stock -= (int)$order_product['quantity'];
                    $quantity->update();
                }

                if($increase_stock)
                {
                    $quantity->stock += (int)$order_product['quantity'];
                    $quantity->update();
                }

                $order_product['shelf_id'] = $shelf->shelf_id;
                $order_product['batch'] = $quantity->batch;
                $order_product['warehouse_id'] = $warehouse->warehouse_id;
                $product_qty = (int) $order_product['quantity'];
                $product_price = (float) $order_product['price'];
                $product_tax = (float) $order_product['product_tax'];

                if(!isset($order_product['environmental_tax'])) {
                    $order_product['environmental_tax'] = 0;
                }

                $environmental_tax = (float) $order_product['environmental_tax'];

                $product_total = $product_qty * ($product_price + $product_tax + $environmental_tax);

                $subtotal += (float) $product_total;
            }

            if($addProductInOrder) {
                OrderProduct::create($order_product);
            }
        }

        $final_discount = 0;
        $order_total = $subtotal + $shipping_cost + $payment_cost;
        if($discount_type == 1) {
            //percentage
            $percentage = $discount_amount / 100;
            $final_discount = $order_total * $percentage;
        } else if($discount_type == 2) {
            $final_discount = $discount_amount;
        }

        $order_total = $order_total - $final_discount;

        if($returned) {
            //Check if new order total is less than paid_total
            $paid = (float) $order->getPayedTotal();
            $new_order_total = $order_total;

            if($paid > $new_order_total) {
                //Add 2 payments
                $amount = $new_order_total - $paid;

                $payment_data = [
                    'customer_id' => $order->customer_id,
                    'amount' => (float) $amount,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'description' => "Πιστωτικό από την παραγγελία: " . $id
                ];
                Payment::create($payment_data);
                $payment_data['description'] = NULL;
                $payment_data['order_id'] = $id;
                Payment::create($payment_data);
            }
        }

        $order_total = number_format($order_total, 2, '.', '');

        $data = [
            'order_total' => $order_total
        ];
        $order->update($data);

        if(isset($request->sendOffer) && !is_null($request->sendOffer) && $request->sendOffer == 'true') {
            $offer = $this->sendOffer($id);
            //HTML $offer
            //TODO: SEND email here
        }

        return array(
            'status' => 'success',
            'msg' => 'Item is successfully saved',
            'redirect_to' => route('orders.edit', $id)
        );
    }

    private function sendOffer($id) {
        $order = Order::findOrFail($id);
        $data['customer'] = Customer::findOrFail($order['customer_id']);
        Mail::to($data['customer']->email)->send(new SendOffer($id));
    } 

    public function destroy($id)
    {

        $order = Order::findOrFail($id);
        $order->clearProducts();
        foreach ($order->invoice as $invoice) {
            $invoice->delete();
        }
        foreach ($order->creditInvoice as $invoice) {
            $invoice->delete();
        }
        foreach ($order->payments as $payment) {
            $payment->delete();
        }
        $order->delete();

        return array(
            'status' => 'success',
            'msg' => 'Item is successfully deleted'
        );
    }
}
