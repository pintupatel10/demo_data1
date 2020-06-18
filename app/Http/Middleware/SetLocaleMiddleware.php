<?php

namespace App\Http\Middleware;

use Closure;

class SetLocaleMiddleware
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
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }

        if ($cookie == '繁中') {
            \App::setLocale('zh-hk');
        }
        else if ($cookie == '簡')
        {
            \App::setLocale('zh-cn');
        }
        else
        {
            \App::setLocale('en');
        }

        return $next($request);
    }
}
