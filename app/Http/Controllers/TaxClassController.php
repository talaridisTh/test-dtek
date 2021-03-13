<?php

namespace App\Http\Controllers;

use App\TaxClass;
use App\Http\Requests\TaxClassRequest;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Session;
use Auth;

class TaxClassController extends Controller
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
            $columns     = ['tax_class_id', 'name', 'type','amount'];
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

            $items = TaxClass::where(function($query) use ($searchValue){
                $query->Where('name', 'like', '%'.$searchValue.'%');
            })
            ->select('tax_class_id', 'name', 'type','amount')
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
        $data['title'] = "Φορολογικόι Συντελεστές";
        $data['notifications'] = User::getNotifications();
    
        return view('tax_class/list', compact('items', 'data'));
    }
  
    public function create()
    {
        $data = array();
        $data['taxclass'] = array();
        $data['isEdit'] = false;
        $data['action'] = route('taxclasses.store');
        $data['method'] = 'POST';
        $data['title'] = "Νέος Φορολογικός Συντελεστής";
        $data['notifications'] = User::getNotifications();
        
        return  view('tax_class/create', compact('data'));
    }

    public function store(TaxClassRequest $request)
    {
        $validatedData = $request->validated();
        $user_id = Auth::id();
        $validatedData['user_id'] = $user_id;
        $taxclass = TaxClass::create($validatedData);
        return redirect('/taxclasses')->with(['success' => 'Tax Class is successfully saved', 'skipAjax' => true]);
    }

    public function show($id)
    {
        $taxclass = TaxClass::findOrFail($id);
        $data = [];
        $data['title'] = "Φορολογικός Συντελεστής : " . $id;
        $data['notifications'] = User::getNotifications();

        return view('tax_class/show', compact('taxclass', 'data'));
    }

    public function edit($id)
    {
        $taxclass = Taxclass::findOrFail($id);

        $data = array(
            'taxclass' => $taxclass
        );

        $data['isEdit'] = true;
        $data['action'] = route('taxclasses.update', $id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Φορολογικού Συντελεστή : " . $id;
        $data['notifications'] = User::getNotifications();

        return  view('tax_class/create', compact('data'));
    }

    public function update(TaxClassRequest $request,$id)
    {
        $validatedData = $request->validated();
        Taxclass::findOrFail($id)->update($validatedData);

        return array(
            'status' => 'success', 
            'msg' => 'Item is successfully saved'
        );   
        return redirect('/taxclasses')->with('success', 'Tax Class is successfully updated');
    }

    public function destroy($id)
    {
        Taxclass::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
