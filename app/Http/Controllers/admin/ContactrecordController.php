<?php

namespace App\Http\Controllers\admin;

use App\Contactrecord;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ContactrecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Contact');
    }


    public function index()
    {
        $data['mainmenu']="Contact";
        $data['menu']="Contact Record";
        $data['contact']=Contactrecord::orderBy('created_at','DESC')->get();
        return view('admin.contact.contact_record.index',$data);
    }
    public function show($id)
    {
        $data['mainmenu'] = "Contact";
        $data['menu']="Contact Record";
        $data['user'] = Contactrecord::findorFail($id);
        return view('admin.contact.contact_record.view',$data);
    }
    public function destroy(Request $request,$id)
    {
        $contact = Contactrecord::findOrFail($id);
        $input = $request->all();
        $input['follow_up'] = Auth::user()->name;
        $contact->update($input);
        \Session::flash('success','User has Follow up by successfully!');
        return redirect('admin/contact/contact_record');
    }
}
