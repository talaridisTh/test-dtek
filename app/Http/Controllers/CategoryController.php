<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Auth;
use Exception;
use Illuminate\Support\Facades\Storage;
use Session;

class CategoryController extends Controller
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
            $columns     = ['name','category_id','parent_name'];
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

            $categories = Category::getCategories($searchValue,$columns[$column],$dir,$length);

            return [
                'draw' => $draw,
                'recordsTotal' => $categories->total(),
                'recordsFiltered' => $categories->total(),
                'data' => $categories
            ];
        }

        $data = [];
        $data['title'] = "Κατηγορίες";
        $data['notifications'] = User::getNotifications();
        $categories = [];
        return view('category/list', compact('categories', 'data'));
    }

    public function create()
    {
        $data = array();
        $data['title'] = "Νέα Κατηγόρια";
        $data['notifications'] = User::getNotifications();
        $data['category'] = array();
        $data['categories'] = Category::getAllCategories();
        $data['isEdit'] = false;
        $data['action'] = route('categories.store');
        $data['method'] = 'POST';

        return view('category/create', compact('data'));
    }

    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }      

        $category = Category::create($validatedData);
        return redirect('/categories')->with(['success' => 'Η κατηγορία δημιουργήθηκε με επιτυχία!', 'skipAjax' => true]);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        $parent = $category->parent()->first();
        $data['title'] = "Κατηγόρια " . $category['name'];
        $data['notifications'] = User::getNotifications();
        return view('category/show', compact('category','parent', 'data'));
    }

    public function edit($id)
    {
        $data['category'] = Category::findOrFail($id);
        $data['categories'] = Category::getAllCategories();
        $data['isEdit'] = true;
        $data['action'] = route('categories.update', $id);
        $data['method'] = 'PUT';
        $data['title'] = "Επεξεργασία Κατηγόριας " . $data['category']['name'];
        $data['notifications'] = User::getNotifications();

        return view('category/create', compact('data'));
    }

    public function update(CategoryRequest $request,$id)
    {
        $validatedData = $request->validated();
        $category = Category::findOrFail($id);

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
        $category->update($validatedData);

        return array(
            'status' => 'success', 
            'msg' => 'Η κατηγορία αποθηκεύτηκε με επιτυχία!'
        );
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id)->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
