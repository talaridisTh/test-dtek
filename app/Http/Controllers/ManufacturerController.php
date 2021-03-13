<?php

namespace App\Http\Controllers;

use App\Manufacturer;
use App\Http\Requests\ManufacturerRequest;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Session;
use Auth;
use Exception;
use Illuminate\Support\Facades\Storage;

class ManufacturerController extends Controller
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
            $columns     = ['manufacturer_id', 'name', 'image'];
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

            $items = Manufacturer::where(function($query) use ($searchValue){
                $query->Where('name', 'like', '%'.$searchValue.'%');
            })
            ->select('manufacturer_id', 'name', 'image')
            ->orderBy($columns[$column], $dir)
            ->paginate($length);

            return [
                'draw' => $draw,
                'recordsTotal' => $items->total(),
                'recordsFiltered' => $items->total(),
                'data' => $items
            ];
        }
        $data = [];
        $data['title'] = "Κατασκευαστές";
        $data['notifications'] = User::getNotifications();
    
        return view('manufacturer/list', compact('items', 'data'));
    }

    public function create()
    {
        $data = array();
        $data['item'] = array();
        $data['isEdit'] = false;
        $data['action'] = route('manufacturers.store');
        $data['method'] = 'POST';
        $data['title'] = "Νέος Κατασκευαστής";
        $data['notifications'] = User::getNotifications();

        return  view('manufacturer/create', compact('data'));
    }

    public function store(ManufacturerRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }      

        $manufacturer = Manufacturer::create($validatedData);
        return redirect('/manufacturers')->with(['success' => 'Manufacturer is successfully saved', 'skipAjax' => true]);
    }

    public function show($id)
    {
        $manufacturer = Manufacturer::findOrFail($id);
        $data = [];
        $data['title'] = "Κατασκευαστής " . $manufacturer['name'];
        $data['notifications'] = User::getNotifications();

        return view('manufacturer/show', compact('manufacturer', 'data'));
    }

    public function edit($id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        $data = array(
            'manufacturer' => $manufacturer
        );

        $data['isEdit'] = true;
        $data['action'] = route('manufacturers.update', $id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Κατασκευαστή " . $manufacturer['name'];
        $data['notifications'] = User::getNotifications();

        return  view('manufacturer/create', compact('data'));
    }

    public function update(ManufacturerRequest $request, $id)
    {
        $validatedData = $request->validated();
        $manufacturer = Manufacturer::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;

            try{
                $path = public_path('images').'\\'.$manufacturer->image;
                unlink($path);                
            }
            catch(Exception $e){
            }
        }  
        $manufacturer->update($validatedData);

        return array(
            'status' => 'success', 
            'msg' => 'Item is successfully saved'
        );
    }

    public function destroy($id)
    {
        Manufacturer::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
