<?php

namespace App\Http\Controllers\admin;

use App\Coupon;
use App\Http\Controllers\Controller;
use App\TourList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Site Setup');
    }

    public function index()
    {
        $data['mainmenu']="Coupon";
        $data['menu']="Coupon";
        $data['coupon']=Coupon::OrderBy('id','DESC')->Paginate($this->pagination);
         return view('admin.coupon.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Coupon";
        $data['menu']="Coupon";
        $data['tour']=TourList::where('post','=','Public')->lists('title','id');
        return view('admin.coupon.create',$data);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'couponcode' => 'required',
            'type' => 'required',
            'discountby' => 'required',
            'discount' => 'required',
            'quota' => 'required',
            'tourlist_id' => 'required',
            'status' => 'required',
        ]);
        $input=$request->all();
        if(!empty($request['tourlist_id'])) {
            $input['tourlist_id'] = implode(',',$request['tourlist_id']);
        }
        if(!empty($request['orderdate'])){
            $date=explode('-',$request['orderdate']);
            $input['orderdate_start']=$date[0];
            $input['orderdate_end']=$date[1];
        }
        $coupon= Coupon::create($input);
        // return $coupon;
        Activity::log('Coupon -'.$coupon->title.' [Id = '.$coupon->id.'] has been inserted');
        \Session::flash('success', 'Coupon has been inserted successfully!');
        return redirect('admin/coupon');
    }

    public function edit($id)
    {
        $data['mainmenu']="Coupon";
        $data['menu']="Coupon";
        $data['coupon'] = Coupon::findOrFail($id);
        $start=str_replace('-','/',$data['coupon']['orderdate_start']);
        $end= str_replace('-','/',$data['coupon']['orderdate_end']);
        $data['coupon']['orderdate']=$start.'-'.$end;
        $data['tour']=TourList::where('post','=','Public')->lists('title','id');
        $data['tour_selected']=explode(",",$data['coupon']['tourlist_id']);
        return view('admin.coupon.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'couponcode' => 'required',
            'type' => 'required',
            'discountby' => 'required',
            'discount' => 'required',
            'quota' => 'required',
            'tourlist_id' => 'required',
            'status' => 'required',
        ]);

        $coupon = Coupon::findOrFail($id);
        $input = $request->all();
        if(!empty($request['tourlist_id'])) {
            $input['tourlist_id'] = implode(',',$request['tourlist_id']);
        }
        if(!empty($request['orderdate'])){
            $date=explode('-',$request['orderdate']);
            $input['orderdate_start']=$date[0];
            $input['orderdate_end']=$date[1];
        }
        $coupon->update($input);
        Activity::log('Coupon -'.$coupon->title.' [Id = '.$coupon->id.'] has been Updated');
        \Session::flash('success', 'Coupon has been Updated successfully!');
        return redirect('admin/coupon');
    }
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        Activity::log('Coupon -'.$coupon->title.' [Id = '.$coupon->id.'] has been deleted');
        \Session::flash('danger','Coupon has been deleted successfully!');
        return redirect('/admin/coupon');
    }
    public function assign(Request $request)
    {
        $services = Coupon::findorFail($request['id']);
        $services['status']="active";
        $services->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $services = Coupon::findorFail($request['id']);
        $services['status']="inactive";
        $services->update($request->all());
        return $request['id'];
    }
}
