<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\User;
use App\Warehouse;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Session;
use Auth;

class WarehouseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
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
        //to be defined by auth system
        if($request->ajax() && !$skipAjax) {
            $columns     = ['warehouse_id', 'name'];
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

            $warehouses = Warehouse::where(function($query) use ($searchValue){
                $query->orWhere('name', 'like', '%'.$searchValue.'%');
            })
            ->select('warehouse_id', 'name')
            ->orderBy($columns[$column], $dir)
            ->paginate($length);

            return [
                'draw' => $draw,
                'recordsTotal' => $warehouses->total(),
                'recordsFiltered' => $warehouses->total(),
                'data' => $warehouses
            ];
        }
        $data = [];
        $data['title'] = "Αποθήκες";
        $data['notifications'] = User::getNotifications();
        return view('warehouse/list', compact('warehouses', 'data'));
    }

    public function findShelves(Request $request) {
        $warehouse_id = (int) $request->input('warehouse_id');
        $shelves = Warehouse::find($warehouse_id)->shelves()->get();

        $shelves = $shelves->toArray();
        usort($shelves, function($a, $b) {
           return strnatcmp($a['name'], $b['name']);
        });

        return response()->json([
            'shelves' => $shelves
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
        $data['warehouse'] = array();
        $data['isEdit'] = false;
        $data['action'] = route('warehouses.store');
        $data['method'] = 'POST';
        $data['title'] = "Νέα Αποθήκη";
        $data['notifications'] = User::getNotifications();
        return  view('warehouse/create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\WarehouseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseRequest $request)
    {
        $validatedData = $request->validated();
        $user_id = Auth::id();
        $validatedData['user_id'] = $user_id;
        $warehouse = Warehouse::create($validatedData);
        return redirect('/warehouses')->with(['success' => 'Warehouse is successfully saved', 'skipAjax' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $data = [];
        $data['title'] = "Αποθήκη : " . $id;
        $data['notifications'] = User::getNotifications();

        return view('warehouse/show', compact('warehouse', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $data = array(
            'warehouse' => $warehouse
        );

        $data['isEdit'] = true;
        $data['action'] = route('warehouses.update', $id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Αποθήκης : " . $id;
        $data['notifications'] = User::getNotifications();

        return  view('warehouse/create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseRequest $request, $id)
    {
        $validatedData = $request->validated();
        Warehouse::where('warehouse_id', $id)->update($validatedData);

        return array(
            'status' => 'success', 
            'msg' => 'Warehouse is successfully saved'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $warehouses = Warehouse::findOrFail($id)->delete();
        
        return response()->json([
            'status' => 'success'
        ]);
    }
}
