<?php

namespace App\Http\Controllers;

use App\CustomerGroup;
use App\Http\Requests\CustomerGroupRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

use App\Manufacturer;
use App\Category;
use App\User;
use Auth;
use Session;

use function Psy\debug;

class CustomerGroupController extends Controller
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
        $groups = [];
        if(!is_null($data) && is_bool($data)) {
            $skipAjax = $data;
        }
        //to be defined by auth system
        if($request->ajax() && !$skipAjax) {
            $columns     = ['customer_group_id', 'name'];
            $draw        = $request->draw;
            $start       = $request->start; //Start is the offset
            $length      = $request->length; //How many records to show
            $column      = $request->order[0]['column']; //Column to orderBy
            $dir         = $request->order[0]['dir']; //Direction of orderBy
            $searchValue = $request->search['value']; //Search value
            // $customer_id = $request->search['customer_id']; //customer_id
            $customer_id = $request->customer_id; //customer_id

            //Sets the current page
            Paginator::currentPageResolver(function () use ($start, $length) {
                return ($start / $length + 1);
            });

            $groups = CustomerGroup::where(function($query) use ($searchValue){
                $query->Where('name', 'like', '%'.$searchValue.'%');
            })
            ->select('customer_group_id', 'name')
            ->orderBy($columns[$column], $dir)
            ->paginate($length);

            return [
                'draw' => $draw,
                'recordsTotal' => $groups->total(),
                'recordsFiltered' => $groups->total(),
                'data' => $groups
            ];
        }
        $data = [];
        $data['title'] = "Ομάδα Πελατών";
        $data['notifications'] = User::getNotifications();
        return view('customergroups/list', compact('groups', 'data'));
    }

    public function create()
    {
        $data = array();
        $data['customer'] = array();
        $data['isEdit'] = false;
        $data['action'] = route('customergroups.store');
        $data['method'] = 'POST';
        $data['title'] = "Νέα Ομάδα Πελατών";
        $data['notifications'] = User::getNotifications();

        $data['manufacturers'] = Manufacturer::all();
        $data['categories'] = Category::all();
        $data['discounts'] = [];
        return view('customergroups/create', compact('data'));
    }

    public function store(CustomerGroupRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['discounts'] = urldecode($validatedData['discounts']);
        CustomerGroup::create($validatedData);
        return redirect('/customergroups')->with(['success' => 'Customer Group is successfully saved', 'skipAjax' => true]);
    }

    public function show($id)
    {
        $group = CustomerGroup::findOrFail($id);
        $data = [];
        $data['manufacturers'] = Manufacturer::all();
        $data['categories'] = Category::all();
        $discounts = $group->discounts;
        if(is_null($discounts)) {
            $discounts = [];
        } else {
            $discounts = json_decode($discounts, true);
        }
        $data['discounts'] = $discounts;
        $data['title'] = "Ομάδα Πελατών";
        $data['notifications'] = User::getNotifications();

        return view('customergroups/show', compact('group', 'data'));
    }

    public function edit($id)
    {
        $data['customer_group'] = CustomerGroup::findOrFail($id);

        $data['isEdit'] = true;
        $data['action'] = route('customergroups.update', $id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Ομάδας Πελατών";
        $data['notifications'] = User::getNotifications();
        $data['manufacturers'] = Manufacturer::all();
        $data['categories'] = Category::all();
        $discounts = $data['customer_group']->discounts;
        if(is_null($discounts)) {
            $discounts = [];
        } else {
            $discounts = json_decode($discounts, true);
        }
        $data['discounts'] = $discounts;

        return view('customergroups/create', compact('data'));
    }

    public function update(CustomerGroupRequest $request,$id)
    {
        $validatedData = $request->validated();
        $validatedData['discounts'] = urldecode($validatedData['discounts']);
        CustomerGroup::findOrFail($id)->update($validatedData);

        return array(
            'status' => 'success',
            'msg' => 'Customer Group is successfully saved'
        );
    }

    public function destroy($id)
    {
        CustomerGroup::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
