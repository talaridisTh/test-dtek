<?php

namespace App\Http\Controllers;

use App\CustomerAddress;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;

use App\Customer;
use App\TaxClass;
use App\CustomerGroup;
use App\Country;
use App\Order;
use App\User;
use Auth;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['search']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Session::get('skipAjax');
        $skipAjax = false;

        if(!is_null($data) && is_bool($data)) {
            $skipAjax = $data;
        }
        $data = [];
        $customers = array();
        //to be defined by auth system
        if($request->ajax() && !$skipAjax) {
            $columns     = ['customer_id', 'customer_name', 'tax_id', 'phone', 'mobile', 'company_name', 'email', 'fax'];
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
            $customers = Customer::getCustomers($searchValue,$columns[$column],$dir,$length);

            return [
                'draw' => $draw,
                'recordsTotal' => $customers->total(),
                'recordsFiltered' => $customers->total(),
                'data' => $customers
            ];
        }

        $data['title'] = "Πελάτες";
        $data['notifications'] = User::getNotifications();

        return view('customer/list', compact('customers', 'data'));
    }

    public function search(Request $request) {
        $term = trim($request->q);

        if(empty($term)) {
            return response()->json([
                'customers' => []
            ]);
        }

        $customers = Customer::select('customer_id as id', 'customer_name as text')
                    ->where('customers.customer_name', 'like', '%'.$term.'%')
                    ->orWhere('customers.phone', 'like', '%'.$term.'%')
                    ->orWhere('customers.mobile', 'like', '%'.$term.'%')
                    ->orWhere('customers.tax_id', 'like', '%'.$term.'%')
                    ->limit(20)->get();

        return response()->json([
            "customers" => $customers
        ]);
    }

    public function getOrders(Request $request) {
        $customer_id = (int) $request->customer_id;

        $customer = Customer::findOrFail($customer_id);

        $orders = Order::select('order_id', 'created_at')
                        ->where('orders.customer_id', $customer_id)
                        ->orderBy('created_at', 'ASC')
                        ->get();
        return response()->json([
            "orders" => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['customer'] = array();
        $data['isEdit'] = false;
        $data['action'] = route('customers.store');
        $data['method'] = 'POST';
        $data['taxclasses'] = TaxClass::all();
        $data['countries'] = Country::all();
        $data['customer_groups'] = CustomerGroup::all();
        $data['title'] = "Νέος Πελάτης";
        $data['notifications'] = User::getNotifications();
        return view('customer/create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $validatedData = $request->validated();
        $customer = Customer::create($validatedData);
        return response()->json([
            'success' => 'Ο πελάτης δημιουργήθηκε με επιτυχία!',
            'customer_id' =>$customer->customer_id,
            'redirect_to' => route('customers.edit', $customer->customer_id),
            'skipAjax' => true]);
        //return redirect('/customers')->with(['success' => 'Ο πελάτης δημιουργήθηκε με επιτυχία!','customer_id' =>$customer->customer_id, 'skipAjax' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $addresses = $customer->addresses;
        $orders_total = $customer->getOrderTotal();
        $orders_paid = $customer->getOrdersPaidTotal();
        $payments_total_no_order = $customer->getPaymentsTotalNoOrder();
        $credit = $orders_total - $orders_paid - $payments_total_no_order;
        $whole_total = $orders_total - $credit;
        $balances = [
            'orders_total' => $orders_total,
            'orders_paid' => $orders_paid,
            'payments_total_no_order' => $payments_total_no_order,
            'credit' => $credit,
            'whole_total' => $whole_total
        ];
        $data = [];
        $data['title'] = "Πελάτης : " . $customer['customer_name'];
        $data['notifications'] = User::getNotifications();

        return view('customer/show', compact('customer', 'addresses', 'balances', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $taxclasses = TaxClass::all();
        $countries = Country::all();
        $customer_groups = CustomerGroup::all();
        $data = array(
            'customer'  => $customer,
            'taxclasses'=> $taxclasses,
            'countries' => $countries,
            'customer_groups' => $customer_groups,
        );

        $data['isEdit'] = true;
        $data['action'] = route('customers.update', $id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Πελάτη : " . $customer['customer_name'];
        $data['notifications'] = User::getNotifications();
        return view('customer/create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        $validatedData = $request->validated();
        $customer = Customer::findOrFail($id);

        if(isset($validatedData['password']) && isset($validatedData['password2']) && $validatedData['password'] == $validatedData['password2'])
        {
            $customer->updateWebsitePassword($validatedData['password']);
        }
        $customer->update($validatedData);

        return response()->json(array(
            'status' => 'success',
            'msg' => 'Ο πελάτης αποθηκεύτηκε με επιτυχία!',
            'redirect_to' => route('customers.edit', $customer->customer_id)
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CustomerAddress::where('customer_id',$id)->delete();
        $customer = Customer::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
