<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('hasModelPermission')) {
    function hasModelPermission($key, $model)
    {
        $post=DB::table('customers')
            ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
            ->leftJoin('features', 'subscriptions.feature_id', '=', 'features.id')
            ->where('customers.admin_id', auth()->id())
            ->where('key', $key)
            ->select('features.key', 'subscriptions.number_of_records', 'customers.id as customer_id')
            ->get()
            ->first();

        if(!$post){
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