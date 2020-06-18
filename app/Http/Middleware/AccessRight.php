<?php

namespace App\Http\Middleware;

use App\Group;
use Closure;
use Illuminate\Support\Facades\Auth;

class AccessRight
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$access)
    {
        $user_group = Auth::user()->group_id;
        if ($user_group == 0) {
            return $next($request);
        }
        $group = Group::where('id', $user_group)->first();
        if (!empty($group)) {
            $right = explode(',', $group->accessright);
            $check = in_array($access, $right);
            if ($check) {
                return $next($request);
            }
        }
        return redirect('admin/dashboard');
    }

}
