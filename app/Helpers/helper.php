<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('hasModelPermission')) {
    function hasModelPermission($key, $model)
    {
        return true;
        $post = DB::table('customers')
            ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
            ->leftJoin('features', 'subscriptions.feature_id', '=', 'features.id')
            ->where('customers.admin_id', auth()->id())
            ->where('key', $key)
            ->select('features.key', 'subscriptions.number_of_records', 'customers.id as customer_id')
            ->get()
            ->first();

        if (!$post) {
            return false;
        }
        $productsCount = $model::where('customer_id', $post->customer_id)
            ->count();

        if ($productsCount < $post->number_of_records) {
            return true;
        } else {
            return false;
        }
    }
}

//get the number of records based on the model key
if (!function_exists('getNumberOfModelRecords')) {
    function getNumberOfModelRecords($Modelkey)
    {
        $post = DB::table('customers')
            ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
            ->leftJoin('features', 'subscriptions.feature_id', '=', 'features.id')
            ->where('customers.admin_id', auth()->id())
            ->where('key', $Modelkey)
            ->select('features.key', 'subscriptions.number_of_records', 'customers.id as customer_id')
            ->get()
            ->first();

        if (!$post) {
            return 0;
        }
        return $post->number_of_records;
    }
}

// format the number of records 1000 to 1k
if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'k';
        }
        return $number;
    }
}