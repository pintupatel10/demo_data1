<?php

namespace App\Http\Controllers\admin;

use App\HotelContact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class HotelContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Hotel');

    }
    public function index()
    {
        $data['mainmenu']="Hotel";
        $data['menu']="Contact Record";
        $data['contact']=HotelContact::orderBy('id','ASC')->get();
        return view('admin.hotel.contact_record.index',$data);
    }
    public function show($id)
    {
        $data['mainmenu']="Hotel";
        $data['menu']="Contact Record";
        $data['hotel'] = HotelContact::findorFail($id);
        return view('admin.hotel.contact_record.view',$data);
    }
}
