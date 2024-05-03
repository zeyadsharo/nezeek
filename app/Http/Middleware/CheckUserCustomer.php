<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $customer=auth()->user()->customer;
        if(!$customer){
            return redirect('/');
        }
        //check activation_state and next_payment date 
        if ($customer->activation_state == 1 && $customer->next_payment > now()) {
            return $next($request);
        }
        return redirect('/');
    }
}
