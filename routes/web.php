<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::get('/customers/search', 'CustomerController@search');
Route::get('/customers/getOrders', 'CustomerController@getOrders');

Route::get('/products/search', 'ProductController@search');
Route::get('/products/getProductOrder', 'ProductController@getProductOrder');
Route::get('/products/futureQuantities', 'ProductController@futureQuantities')->name('products.futureQuantities');
Route::post('/products/applyFutureQuantity', 'ProductController@applyFutureQuantity')->name('products.applyFutureQuantity');
Route::post('/products/deleteFutureQuantity', 'ProductController@deleteFutureQuantity')->name('products.deleteFutureQuantity');
Route::post('/products/applyQuantity', 'ProductController@applyQuantity')->name('products.applyQuantity');
Route::get('/warehouses/findShelves', 'WarehouseController@findShelves');

Route::resource('customers', 'CustomerController');
Route::resource('products', 'ProductController');
Route::resource('manufacturers', 'ManufacturerController');
Route::resource('warehouses', 'WarehouseController');
Route::resource('shelves', 'ShelfController');
Route::resource('taxclasses', 'TaxClassController');
Route::resource('orders', 'OrderController');
Route::resource('payments', 'PaymentController');
Route::resource('invoices', 'InvoiceController');
Route::get('receipt/{Order}', 'InvoiceController@orderReceipt');
Route::get('receipt/download/{id}', 'InvoiceController@receiptDownload')->name('download-receipt');
Route::get('/invoices/download/{id}', 'InvoiceController@download')->name('download-invoice');
Route::resource('creditinvoices', 'CreditInvoiceController');

Route::get('/addresses/getCustomerAddresses', 'CustomerAddressController@getCustomerAddresses');
Route::resource('addresses', 'CustomerAddressController');
Route::resource('customergroups', 'CustomerGroupController');
Route::resource('categories', 'CategoryController');

Route::get('/order/addBarcodeProduct', 'OrderController@addBarCodeProduct');
Route::get('/order/addWaittingShelf', 'OrderController@addWaittingShelf');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
