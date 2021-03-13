<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShelfRequest;
use App\Shelf;
use App\User;
use App\Warehouse;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Session;
use Auth;

class ShelfController extends Controller
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
            $columns     = ['shelf_id', 'name', 'warehouse_name', 'is_product_shelf'];
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

            $shelves = Shelf::where(function($query) use ($searchValue){
                $query->orWhere('shelves.name', 'like', '%'.$searchValue.'%');
                $query->orWhere('warehouses.name', 'like', '%'.$searchValue.'%');
            })
            ->select('shelf_id', 'warehouses.name as warehouse_name', 'shelves.name', 'is_product_shelf')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'shelves.warehouse_id')

            ->orderBy($columns[$column], $dir)
            ->paginate($length);

            return [
                'draw' => $draw,
                'recordsTotal' => $shelves->total(),
                'recordsFiltered' => $shelves->total(),
                'data' => $shelves
            ];
        }
        $data = [];
        $data['title'] = "Ράφια";
        $data['notifications'] = User::getNotifications();
    
        return view('shelf/list', compact('shelves', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['shelf'] = array();
        $data['isEdit'] = false;
        $data['action'] = route('shelves.store');
        $data['method'] = 'POST';
        $data['warehouses'] = Warehouse::all();
        $data['title'] = "Νέο Ράφι";
        $data['notifications'] = User::getNotifications();

        return  view('shelf/create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ShelfRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShelfRequest $request)
    {
        $validatedData = $request->validated();
        $user_id = Auth::id();
        $validatedData['user_id'] = $user_id;
        $shelves = Shelf::create($validatedData);
        return redirect('/shelves')->with(['success' => 'Το ράφι αποθηκεύτηκε με επιτυχία!', 'skipAjax' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shelf = Shelf::findOrFail($id);
        $warehouse = $shelf->warehouse;
        $data = [];
        $data['title'] = "Ράφι : " . $id;
        $data['notifications'] = User::getNotifications();

        return view('shelf/show', compact('shelf', 'warehouse', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shelf = Shelf::findOrFail($id);
        $warehouse = $shelf->warehouse;

        $data = array(
            'shelf'     => $shelf,
            'warehouse' => $warehouse
        );

        $data['warehouses'] = Warehouse::all();

        $data['isEdit'] = true;
        $data['action'] = route('shelves.update', $id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Ραφιού : " . $id;
        $data['notifications'] = User::getNotifications();

        return  view('shelf/create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ShelfRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShelfRequest $request, $id)
    {
        $validatedData = $request->validated();
        Shelf::where('shelf_id', $id)->update($validatedData);

        return array(
            'status' => 'success', 
            'msg' => 'Shelf is successfully saved'
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
        $shelves = Shelf::findOrFail($id)->delete();
        
        return response()->json([
            'status' => 'success'
        ]);
    }
}
