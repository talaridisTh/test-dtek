<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductQuantityRequest;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Session;

use App\Product;
use App\Order;
use App\OrderProduct;
use App\Manufacturer;
use App\Category;
use App\ProductFutureQuantity;
use App\ProductQuantity;
use App\Warehouse;
use App\Shelf;
use App\User;
use Auth;
use Exception;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        $data = Session::get('skipAjax');
        $skipAjax = false;
        $products = array();
        if(!is_null($data) && is_bool($data)) {
            $skipAjax = $data;
        }
        //to be defined by auth system
        if($request->ajax() && !$skipAjax) {
            $columns     = ['manufacturer_name', 'model', 'width', 'height_percentage', 'radial_structure', 'diameter'];
            $draw        = $request->draw;
            $start       = $request->start; //Start is the offset
            $length      = $request->length; //How many records to show
            $column      = $request->order[0]['column']; //Column to orderBy
            $dir         = 'ASC'; //Direction of orderBy
            $searchValue = $request->search['value']; //Search value

            //Sets the current page
            Paginator::currentPageResolver(function () use ($start, $length) {
                return ($start / $length + 1);
            });

            $searchParams = [];
            $searchParams['manufacturer'] = (int) $request->input('manufacturer');
            $searchParams['category'] = (int) $request->input('category');
            $searchParams['dimensions'] = array();
            $searchParams['dimensions']  = $request->input('dimensions');
            if(!empty($request->input('enforce_order_by_qty')) && $request->input('enforce_order_by_qty') == 1) {
                $searchParams['enforce_order_by_qty']  = $request->input('enforce_order_by_qty');
            }
            $hasDimension = false;
            $appendDimensions = [];
            for($i = 0; $i < sizeof($searchParams['dimensions']); $i++) {
                if($searchParams['dimensions'][$i] != null) {
                    $current_dim = $searchParams['dimensions'][$i];
                    if(!preg_match("/[.\-\/]/i", $current_dim)) {
                        $appendDimensions[] = $current_dim[0] . '.' . substr($current_dim, 1, strlen($current_dim));
                    }
                    $hasDimension = true;
                }
            }

            if(!empty($searchValue)) {
                if(!preg_match("/[.\-\/]/i", $searchValue)) {
                    $searchParams['extra_dimension'] = $searchValue[0] . '.' . substr($searchValue, 1, strlen($searchValue));
                }
                $hasDimension = true;
            }

            $searchParams['dimensions'] = array_merge($searchParams['dimensions'], $appendDimensions);
            //$columns_column = 'radial_structure';
            $columns_column = 'diameter';
            if($column < sizeof($columns)) {
                $columns_column = isset($columns[$column-1]) ? $columns[$column-1] : 'diameter';
            }
            if($hasDimension) {
                $columns_column = "product_full_name";
            }
            $products = Product::searchProducts($searchValue, $columns_column, $dir, $length, $searchParams);

			//dump($products);exit;
            return [
                'draw' => $draw,
                'recordsTotal' => $products->total(),
                'recordsFiltered' => $products->total(),
                'data' => $products
            ];
        }

        $data = [];
        $data['title'] = "Προϊόντα";
        $data['notifications'] = User::getNotifications();
        $products = [];
        return view('product/list', compact('products', 'categories', 'manufacturers', 'data'));
    }

    public function futureQuantities(Request $request) {
        $data = Session::get('skipAjax');
        $skipAjax = false;

        $warehouses = Warehouse::all();

        if(!is_null($data) && is_bool($data)) {
            $skipAjax = $data;
        }
        //to be defined by auth system
        if($request->ajax() && !$skipAjax) {
            $columns     = ['name', 'stock', 'arrival_date'];
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

            $date_filter  = $request->input('date_filter');
            if(!empty($date_filter) && $date_filter != '') {
                // 10/10/2019
                $date_filter_arr = explode('/', $date_filter);
                if(sizeof($date_filter_arr) == 3) {
                    $date_filter = implode("-", array_reverse($date_filter_arr));
                }
            }

            $future_quantities = ProductFutureQuantity::searchFutureQuantities($searchValue, $length, $date_filter);

            foreach ($future_quantities as &$future_quantity) {
                $future_quantity['product_details'] = Product::findOrFail($future_quantity['id']);
            }

            return [
                'draw' => $draw,
                'recordsTotal' => $future_quantities->total(),
                'recordsFiltered' => $future_quantities->total(),
                'data' => $future_quantities
            ];
        }

        $data = [];
        $data['title'] = "Μελλοντικές Ποσότητες";
        $data['notifications'] = User::getNotifications();

        return view('product/future_quantities', compact('warehouses', 'data'));
    }

    public function applyFutureQuantity(Request $request) {
        $product_future_quantity_id = (int) $request->input('product_future_quantity_id');
        $warehouse_id = (int) $request->input('warehouse_id');
        $shelf_id = (int) $request->input('shelf_id');
        $batch = (int) $request->input('batch');

        if($product_future_quantity_id < 1) {
            return response()->json([
                "status" => "error",
                "msg"    => "Η μελλοντική ποσότητα δε βρέθηκε!"
            ]);
        }

        $product_future_quantity = ProductFutureQuantity::find($product_future_quantity_id);
        if($product_future_quantity == null || empty($product_future_quantity)) {
            return response()->json([
                "status" => "error",
                "msg"    => "Η μελλοντική ποσότητα δε βρέθηκε!"
            ]);
        }

        $warehouse = Warehouse::find($warehouse_id);
        if($warehouse == null || empty($warehouse)) {
            return response()->json([
                "status" => "error",
                "msg"    => "Η αποθήκη δε βρέθηκε!"
            ]);
        }

        $shelf = Shelf::find($shelf_id);
        if($shelf == null || empty($shelf)) {
            return response()->json([
                "status" => "error",
                "msg"    => "Το ράφι δε βρέθηκε!"
            ]);
        }

        $stock = $product_future_quantity->stock;
        $product_id = $product_future_quantity->product_id;

        $data = [
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'shelf_id' => $shelf_id,
            'batch' => $batch,
            'stock' => $stock,
        ];

        $p_qty = ProductQuantity::where('product_id', $product_id)
                        ->where('warehouse_id', $warehouse_id)
                        ->where('shelf_id', $shelf_id)
                        ->where('batch', $batch)
                        ->get();

        if(!empty($p_qty) && sizeof($p_qty) > 0) {
            $p_qty = $p_qty[0];
            $product_quantity_id = $p_qty->product_quantity_id;
            $old_stock = $p_qty->stock;

            $new_stock = $old_stock + $stock;
            $data = [
                'stock' => $new_stock
            ];
            ProductQuantity::find($product_quantity_id)->update($data);
        } else {
            ProductQuantity::insert($data);
        }

        ProductFutureQuantity::find($product_future_quantity_id)->delete();
        return response()->json([
            "status" => "success"
        ]);

    }

    public function deleteFutureQuantity(Request $request)
    {
        $product_future_quantity_id = (int) $request->input('product_future_quantity_id');
        ProductFutureQuantity::find($product_future_quantity_id)->delete();
        return response()->json([
            "status" => "success"
        ]);
    }

    public function applyQuantity(Request $request)
    {
        $product_id = (int)$request->input('product_id');
        $warehouse_id = (int)$request->input('warehouse_id');
        $shelf_id = (int)$request->input('shelf_id');
        $batch = (int)$request->input('batch');
        $stock = (int)$request->input('stock');

        if ($product_id < 1) {
            return response()->json([
                "status" => "error",
                "msg" => "Το προιόν δεν βρέθηκε!"
            ]);
        }

        $product = Product::find($product_id);
        if ($product == null || empty($product)) {
            return response()->json([
                "status" => "error",
                "msg" => "Το προιόν δεν βρέθηκε!"
            ]);
        }

        $warehouse = Warehouse::find($warehouse_id);
        if ($warehouse == null || empty($warehouse)) {
            return response()->json([
                "status" => "error",
                "msg" => "Η αποθήκη δε βρέθηκε!"
            ]);
        }

        $shelf = Shelf::find($shelf_id);
        if ($shelf == null || empty($shelf)) {
            return response()->json([
                "status" => "error",
                "msg" => "Το ράφι δε βρέθηκε!"
            ]);
        }

        $data = [
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'shelf_id' => $shelf_id,
            'batch' => $batch,
            'stock' => $stock,
        ];

        $p_qty = ProductQuantity::where('product_id', $product_id)
            ->where('warehouse_id', $warehouse_id)
            ->where('shelf_id', $shelf_id)
            ->where('batch', $batch)
            ->get();

        if (!empty($p_qty) && sizeof($p_qty) > 0) {
            $p_qty = $p_qty[0];
            $product_quantity_id = $p_qty->product_quantity_id;
            $old_stock = $p_qty->stock;

            $new_stock = $old_stock + $stock;
            $data = [
                'stock' => $new_stock
            ];
            ProductQuantity::find($product_quantity_id)->update($data);
        } else {
            ProductQuantity::insert($data);
        }

        return response()->json([
            "status" => "success"
        ]);
    }

     public function search(Request $request) {
        $term = trim($request->q);
        $orderByStock = $request->stock_order;
        if(empty($term) && empty($request->product_id)) {
            return response()->json([
                'products' => []
            ]);
        }
        $searchParams = array();
        if(!empty($term)) {
            $searchParams = array(
                "extra_dimension" => $term,
                    "dimensions" => [null,null,null]
            );
            if(!preg_match("/[.\-\/]/i", $term)) {
                $searchParams['extra_dimension'] = $term[0] . '.' . substr($term, 1, strlen($term));
            }
        }

        if(!empty($request->instock)) {
            $searchParams['instock'] = $request->instock;
        }

        $product_id = $request->product_id;
        if($orderByStock == 1)
            $products = Product::searchProducts($term, 'product_full_name', 'ASC', 0, $searchParams,$product_id);
        else
            $products = Product::searchProducts($term, 'products.id', 'DESC', 0, $searchParams,$product_id);
        return response()->json([
            "products" => $products
        ]);
    }

    public function getProductOrder(Request $request) {
        $product_id = (int) $request->product_id;
        $order_id = (int) $request->order_id;
        $additional_stock = [];
        $discounts = [];
        if($order_id > 0) {
            $order = Order::find($order_id);
            //Check if order status is SENT (status where stock is decreased)
            if($order && $order->order_status == 4) {
                $order_product = OrderProduct::where('order_id', $order_id)->where('product_id', $product_id)->get();
                foreach($order_product as $p) {
                    $additional_stock[$p->product_quantity_id] =  $p->quantity;
                }
            }
            $discounts = json_decode($order->customer->group->discounts, true);
        }
        $product = Product::find($product_id);
        if(!$product) {
            return response()->json([
                'prices' => [],
                'quantities' => [],
                'discounts' => [
                    'return' => 0,
                    'discount' => 0
                ]
            ]);
        }

        $discount_key = $product->manufacturer_id . "::" . $product->category_id;
        $current_discounts = !isset($discounts[$discount_key]) ? [
            'return' => 0,
            'discount' => 0
        ] : $discounts[$discount_key];

        $prices = $product->prices();
        $quantities = $product->quantities;
        foreach($quantities as &$qty) {
            $qty->shelf;
            if(isset($additional_stock[$qty->product_quantity_id])) {
                $qty->stock += $additional_stock[$qty->product_quantity_id];
            }
        }

        return response()->json([
            'prices' => $prices,
            'quantities' => $quantities,
            'discounts' => [
                'return' => $current_discounts['return'],
                'discount' => $current_discounts['discount']
            ]
        ]);
    }

    public function create()
    {
        $data = array();
        $data['product'] = array();
        $data['isEdit'] = false;
        $data['action'] = route('products.store');
        $data['method'] = 'POST';
        $data['manufacturers'] = Manufacturer::all();
        $data['categories'] = Category::all();
        $quantities = [];
        $future_quantities = [];
        $warehouses = Warehouse::all();
        $json_warehouses = json_encode($warehouses);
        $json_shelves = json_encode(Shelf::all());
        $data['title'] = "Νέο Προϊόν";
        $data['notifications'] = User::getNotifications();

        return  view('product/create', compact('data', 'quantities', 'future_quantities', 'json_warehouses', 'json_shelves'));
    }

    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $product = Product::create($validatedData);
        $product->savePrices($validatedData);
        $product->saveIndexes();

        if(!empty($validatedData['qty']))
        {
            foreach($validatedData['qty'] as &$qty)
                $qty['product_id'] = $product->id;
            ProductQuantity::insert($validatedData['qty']);
        }

        if(!empty($validatedData['future_qty']))
        {
            foreach($validatedData['future_qty'] as &$qty)
                $qty['product_id'] = $product->id;
            ProductFutureQuantity::insert($validatedData['future_qty']);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }

        return response()->json(array(
            'status' => 'success',
            'msg' => 'Product is successfully saved!',
            'redirect_to' => route('products.edit', $product->id),
        ));

//        return redirect('/products')->with(['success' => 'Product is successfully saved', 'skipAjax' => true]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $quantities = $product->quantities;
        if(is_null($quantities)) $quantities = [];
        $future_quantities = $product->futureQuantities;
        if(is_null($future_quantities)) $future_quantities = [];
        $manufacturer = Manufacturer::getManufacturerByProductId($id);
        $manufacturer = (array)$manufacturer;
        if(is_null($product->category)) $category = null;
        else $category = $product->category->name;

        $prices = $product->prices();
        $data = [];
        $data['title'] = "Προϊόν : " . $id;
        $data['notifications'] = User::getNotifications();

        return view('product/show', compact('product', 'manufacturer', 'category', 'quantities', 'future_quantities', 'prices', 'data'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $data = array(
            'product' => $product
        );

        $data['manufacturers'] = Manufacturer::all();
        $data['categories'] = Category::all();

        $data['prices'] = $product->prices();

        $data['isEdit'] = true;
        $data['action'] = route('products.update', $id);
        $data['method'] = 'PUT';
        $data['product_id'] = $product->id;

        $quantities = $product->quantities;

        $future_quantities = $product->futureQuantities;
        $warehouses = Warehouse::all();
        $json_warehouses = json_encode($warehouses);
        $json_shelves = json_encode(Shelf::all());
        $data['title'] = "Επεξεργασία Προϊόντος : " . $id;
        $data['notifications'] = User::getNotifications();

        return  view('product/create', compact('data', 'quantities', 'future_quantities', 'warehouses', 'json_warehouses', 'json_shelves'));
    }

    public function update(ProductRequest $request, $id)
    {
        $validatedData = $request->validated();
        $product = Product::findOrFail($id);

        if($validatedData['category_id'] == -1)
            unset($validatedData['category_id']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
            try{
                $path = public_path('images').'\\'.$product->image;
                unlink($path);
            }
            catch(Exception $e){
            }
        }

        $product->update($validatedData);
        $product->savePrices($validatedData);
        $product->saveIndexes();

        if(!empty($validatedData['qty']))
        {
            $existingIds = array();

            foreach($validatedData['qty'] as $qty)
                if(isset($qty['product_quantity_id']))
                    $existingIds[] = $qty['product_quantity_id'];


            $quantities = ProductQuantity::where('product_id',$id)->whereNotIn('product_quantity_id',$existingIds);
            if(!empty($quantities))
                $quantities->delete();

            foreach($validatedData['qty'] as $qty)
            {
                if(!isset($qty['product_quantity_id']))
                {
                    $qty['product_id'] = $product->id;
                    ProductQuantity::insert($qty);
                }
                else
                    ProductQuantity::findOrFail($qty['product_quantity_id'])->update($qty);
            }
        } else {
            // If no qty is sent, then remove all product quantities
            $quantities = ProductQuantity::where('product_id',$id);
            if(!empty($quantities)) $quantities->delete();
        }

        ProductFutureQuantity::where('product_id',$product->id)->delete();
        if(!empty($validatedData['future_qty']))
        {
            foreach($validatedData['future_qty'] as &$qty)
                $qty['product_id'] = $product->id;
            ProductFutureQuantity::insert($validatedData['future_qty']);
        }

        return response()->json(array(
            'status' => 'success',
            'msg' => 'Product is successfully saved!',
            'redirect_to' => route('products.edit', $product->id),
        ));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->clearProductRows();
        $product->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
