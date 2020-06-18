<?php

namespace App\Http\Controllers\website;

use App\Contactrecord;
use App\Contactus;
use App\Emailset;
use App\Http\Controllers\Controller;
use App\Sitelogo;
use Illuminate\Http\Request;
use App\Http\Requests;

class ContactusController extends Controller
{
    public function index(Request $request)
    {
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Contact";
        $data['contact']=Contactus::where('status','active')->where('language','=',$cookie)->first();
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();

        if(empty($data['contact'])){
            abort(404);
        }

        return view('website.contactus',$data);
    }

    public function store(Request $request){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Contact";
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

           $email=Emailset::with('MailAddress')->where('type','contact us email address')->first();
         if(isset($email['MailAddress'][0]['mail_address'])){
             $to=$email['MailAddress'][0]['mail_address'];
         }
        $sender_name=$request['firstname'];
       // $to='ilaxo.chirag@gmail.com';
        $sender_email=$request['email'];
        $msg=$request['message'];
        $sub="Inquiry";
        if(!empty($request['email'])){
                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                $headers .= "From: $sender_name <$sender_email>\r\n";
                $headers .= "Reply-To: $sender_name <$sender_email>\r\n";
                $success=mail($to,$sub,$msg,$headers);
        }
        $contactus=Contactrecord::create($request->all());
        \Session::flash('success', 'Email  has been Send successfully!');
        return redirect('contactus');
    }
}
