<?php

namespace App\Http\Controllers;

use App\CustomerAddress;
use App\Http\Requests\CustomerAddressRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Session;
use Auth;


class CustomerAddressController extends Controller
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
            $columns     = ['address_id','firstname', 'lastname', 'company', 'address_1', 'address_2','city','postcode','country_id'];
            $draw        = $request->draw;
            $start       = $request->start; //Start is the offset
            $length      = $request->length; //How many records to show
            $column      = $request->order[0]['column']; //Column to orderBy
            $dir         = $request->order[0]['dir']; //Direction of orderBy
            $searchValue = $request->search['value']; //Search value
            $customer_id = $request->customer_id; //customer_id

            //Sets the current page
            Paginator::currentPageResolver(function () use ($start, $length) {
                return ($start / $length + 1);
            });

            $addresses = CustomerAddress::searchCustomerAdress($customer_id,$searchValue,$columns[$column],$dir,$length);

            return [
                'draw' => $draw,
                'recordsTotal' => $addresses->total(),
                'recordsFiltered' => $addresses->total(),
                'data' => $addresses
            ];
        }
    }

    public function getCustomerAddresses(Request $request)
    {
        $customer_id = (int) trim($request->customer_id);

        if(empty($customer_id)) {
            return response()->json([
                'customer_addresses' => []
            ]);
        }

        $customer_addresses = CustomerAddress::select('customer_addresses.*', 'countries.name as country_name')
                    ->where('customer_id', $customer_id)
                    ->leftJoin('countries', 'countries.country_id', '=', 'customer_addresses.country_id')
                    ->get();

        return response()->json([
            "customer_addresses" => $customer_addresses
        ]);
    }

    public function store(CustomerAddressRequest $request)
    {
        $validatedData = $request->validated();
        $address_id = CustomerAddress::create($validatedData)->address_id;
        return response()->json([
            'success' => 'Η διεύθυνση δημιουργήθηκε με επιτυχία!',
            'address_id' => $address_id,
            'redirect_to' => route('customers.edit', $validatedData['customer_id']),
        ]);
    }

    public function update(CustomerAddressRequest $request, $id)
    {
        $validatedData = $request->validated();
        CustomerAddress::findOrFail($id)->update($validatedData);

        return response()->json(array(
            'status' => 'success',
            'msg' => 'Η διεύθυνση αποθηκέυτηκε με επιτυχία!',
            'redirect_to' => route('customers.edit', $validatedData['customer_id']),
        ));
    }

    public function destroy($id)
    {
        CustomerAddress::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
