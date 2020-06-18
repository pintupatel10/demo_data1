<?php

namespace App\Http\Controllers\admin;

use App\TurbojetCoupon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TurbojetCouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Turbojet Setting');
    }

    public function index()
    {
        $data=[];
        $data['mainmenu'] = 'Turbojet';
        $data['menu'] = 'Turbojet Coupon';
        $data['coupons'] = TurbojetCoupon::all();
        return view('admin.turbojet-coupon.index', $data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu'] = 'Turbojet';
        $data['menu'] = 'Turbojet Coupon';
        return view('admin.turbojet-coupon.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $coupon = new TurbojetCoupon($request->all());
        $coupon->{TurbojetCoupon::COLUMN_IS_WEEKDAY} = $request->has('is_weekday');
        $coupon->{TurbojetCoupon::COLUMN_IS_WEEKEND} = $request->has('is_weekend');
        $coupon->{TurbojetCoupon::COLUMN_IS_DAY} = $request->has('is_day');
        $coupon->{TurbojetCoupon::COLUMN_IS_NIGHT} = $request->has('is_night');
        $coupon->save();

        return redirect("admin/turbojet-coupon");
    }

    public function show($id)
    {
        $data=[];
        $data['mainmenu'] = 'Turbojet';
        $data['menu'] = 'Turbojet Coupon';
        $data['coupon'] = TurbojetCoupon::findOrFail($id);
        return view('admin.turbojet-coupon.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $coupon = TurbojetCoupon::findOrFail($id);
        $coupon->fill($request->all());
        $coupon->{TurbojetCoupon::COLUMN_IS_WEEKDAY} = $request->has('is_weekday');
        $coupon->{TurbojetCoupon::COLUMN_IS_WEEKEND} = $request->has('is_weekend');
        $coupon->{TurbojetCoupon::COLUMN_IS_DAY} = $request->has('is_day');
        $coupon->{TurbojetCoupon::COLUMN_IS_NIGHT} = $request->has('is_night');
        $coupon->save();

        return redirect("admin/turbojet-coupon");
    }

    public function destroy($id)
    {
        $coupon = TurbojetCoupon::findOrFail($id);
        $coupon->delete();

        return redirect("admin/turbojet-coupon");
    }
}
