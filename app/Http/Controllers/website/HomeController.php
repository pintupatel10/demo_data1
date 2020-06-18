<?php

namespace App\Http\Controllers\website;

use App\Homelayout;
use App\Homepopup;
use App\Homepost;
use App\Sitelogo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $cookie = $request->cookie('language');
            if(empty($cookie) && $cookie==""){
                $cookie = "English";
            }
        $data['cookie']=$cookie;
        $data['menuactive']="Home";
        $data['layout'] = Homelayout::where('status','active')->where('language','=',$cookie)->get();
        $data['post'] = Homepost::where('status','active')->where('language','=',$cookie)->orderBy('displayorder', 'ASC')->get();
        $data['popup'] = Homepopup::where('status','active')->where('language','=',$cookie)->inRandomOrder()->first();
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
        return view('website.index',$data);
    }

    public function setcookie($lng)
    {
        if($lng=="Eng"){
            $lng="English";
        }
        if($lng=="繁"){
            $lng="繁中";
        }
       // $response->withCookie(cookie('name', 'value', $minutes));
       // $response->withCookie(cookie()->forever('language',$lng));
        $cookie = Cookie::make('language',$lng);
        return redirect('/')->withCookie($cookie);
    }
}
