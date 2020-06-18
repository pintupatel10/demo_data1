<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Activity;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       return $request;
//        $spy = new Activity();
//        $spy->email=Auth::user()->email;
//        $spy->user_id=Auth::user()->id;
//        $spy->save();
        Activity::log('pintu that you wish to log');
        return $next($request);
    }
}
