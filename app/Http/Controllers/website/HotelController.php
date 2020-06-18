<?php

namespace App\Http\Controllers\website;

use App\Hotelcollection;
use App\HotelContact;
use App\HotelDetail;
use App\HotelFilter;
use App\Http\Controllers\Controller;
use App\Sitelogo;
use Illuminate\Http\Request;

use App\Http\Requests;

class HotelController extends Controller
{
    public function index(Request $request,$cid)
    {
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Hotel";
        $data['menuid']=$cid;
       /*  $data['hotel'] = Hotelcollection::with('HotelFilter')->where('status','active')->where('language','=',$cookie)
            ->orderBy('displayorder','ASC')->first();*/
        $data['hotel']=Hotelcollection::where('id',$cid)->where('status','active')->where('language','=',$cookie)->first();
        $data['htl_filter']=HotelFilter::where('cid',$cid)->where('status','active')->orderBy('displayorder','ASC')->get();
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();

        if(empty($data['hotel'])){
            abort(404);
        }

        return view('website.hotel.hotellist',$data);
    }
    public function store(Request $request,$id){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;

        if($cookie=='English') {
            $this->validate($request, [
                'title' => 'required',
                'lastname' => 'required',
                'firstname' => 'required',
                'email' => 'required|email',
                'message' => 'required',
            ]);
        }

        if($cookie=='繁中'){
            if(!$request['title']) {
                return back()->withInput()->withErrors(['title' => '請輸入稱謂']);
            }
            if(!$request['lastname']) {
                return back()->withInput()->withErrors(['lastname' => '請輸入姓氏']);
            }
            if(!$request['firstname']) {
                return back()->withInput()->withErrors(['firstname' => '名字字段是必填字段']);
            }
            if(!$request['email']) {
                return back()->withInput()->withErrors(['email' => '請輸入電郵']);
            }
            if(!$request['message']) {
                return back()->withInput()->withErrors(['message' => '消息字段是必需的']);
            }
        }

        if($cookie=='簡'){
            if(!$request['title']) {
                return back()->withInput()->withErrors(['title' => '请输入称谓']);
            }
            if(!$request['lastname']) {
                return back()->withInput()->withErrors(['lastname' => '请输入姓氏']);
            }
            if(!$request['firstname']) {
                return back()->withInput()->withErrors(['firstname' => '名字字段是必填字段']);
            }
            if(!$request['email']) {
                return back()->withInput()->withErrors(['email' => '请输入电邮']);
            }
            if(!$request['message']) {
                return back()->withInput()->withErrors(['message' => '消息字段是必需的']);
            }
        }

           $to="";
            $email=HotelDetail::where('id',$id)->lists('email');
            if(isset($email[0])){
              $to=$email[0];
             }
            $sender_name=$request['firstname'];
            $sender_email=$request['email'];
            $msg=$request['message'];
            $sub="Hotel Inquiry";
            if(!empty($request['email'])){
                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                $headers .= "From: $sender_name <$sender_email>\r\n";
                $headers .= "Reply-To: $sender_name <$sender_email>\r\n";
                $success=mail($to,$sub,$msg,$headers);
            }
           $contact=HotelContact::create($request->all());
            \Session::flash('success', 'Email  has been Send successfully!');
//            return redirect('hoteldetail/'.$id);
            return redirect()->back();
    }
    public function show(Request $request,$cid,$id){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Hotel";
        $data['menuid']=$cid;
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
        $data['hotel']=HotelDetail::where('id',$id)->first();
        return view('website.hotel.hoteldetail',$data);
    }
}
