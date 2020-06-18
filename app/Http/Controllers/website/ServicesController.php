<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Servicedetail;
use App\Sitelogo;
use Illuminate\Http\Request;

use App\Http\Requests;

class ServicesController extends Controller
{
    public function index(Request $request)
    {
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Service";
            $data['service'] = Servicedetail::where('status','active')->where('language','=',$cookie)
               ->orderBy('displayorder','ASC')->get();
      //  return count($data['service']);
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();

        if(!count($data['service'])){
            abort(404);
        }
        return view('website.services',$data);
    }
}
