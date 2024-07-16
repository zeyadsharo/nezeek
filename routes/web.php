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
    return redirect('/admin');
});

//test route
Route::get('/test', function () {
   $features=   DB::table('customers')
        ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
        ->leftJoin('features', 'subscriptions.feature_id', '=', 'features.id')
        ->where('customers.admin_id', auth()->id())
        ->where('key', 'posts') 
        ->select('features.key', 'subscriptions.number_of_records','customers.id as customer_id')
        ->get()
        ->first();
       return $features;
     
});
