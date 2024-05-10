<?php

use App\Models\Feature;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//test route
Route::get('/test', function () {
   $features=   DB::table('customers')
        ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
        ->leftJoin('features', 'subscriptions.feature_id', '=', 'features.id')
        ->where('customers.admin_id', auth()->id())
        ->where('key', 'products') 
        ->select('features.key', 'subscriptions.number_of_records','customers.id as customer_id')
        ->get()
        ->first();
        //count Product key products
     $productsCount= Product::where('customer_id',$features->customer_id)
      ->count();
      $keyPermistion='products';
      //check if the number of records is less than the number of products
        if($productsCount < $features->number_of_records){
            //return true
            return true;    
        }else{
            //return false
            return false;
        }
});
