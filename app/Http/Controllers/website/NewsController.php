<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Newslayout;
use App\Newspost;
use App\Sitelogo;
use Illuminate\Http\Request;

use App\Http\Requests;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="News";
         $data['layout'] = Newslayout::where('status','active')->where('language','=',$cookie)->get();
         $data['post'] = Newspost::where('status','active')->where('language','=',$cookie)->orderBy('displayorder', 'ASC')->get();
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
        return view('website.news',$data);
    }
}
