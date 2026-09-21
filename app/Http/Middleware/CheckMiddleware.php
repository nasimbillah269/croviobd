<?php

namespace App\Http\Middleware;

use App\Models\General;
use Closure;
use Illuminate\Http\Request;

class CheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $general=General::first();

        // if($general->commingsoon_mode && url('admin*')!=url()->current()){
        //    return view('index');
        // }else{
        //     return $next($request);
        // }
        return $next($request);
        
    }
}
