<?php

namespace App\Http\Controllers\admin;

use App\TurbojetHoliday;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TurbojetHolidayController extends Controller
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
        $data['menu'] = 'Turbojet Holiday';
        $data['holidays'] = TurbojetHoliday::orderBy('date', 'asc')->get();
        return view('admin.turbojet-holiday.index', $data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu'] = 'Turbojet';
        $data['menu'] = 'Turbojet Holiday';
        return view('admin.turbojet-holiday.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date',
        ]);

        $holiday = new TurbojetHoliday($request->all());
        $holiday->save();

        return redirect("admin/turbojet-holiday");
    }

    public function show($id)
    {
        $data=[];
        $data['mainmenu'] = 'Turbojet';
        $data['menu'] = 'Turbojet Holiday';
        $data['holiday'] = TurbojetHoliday::findOrFail($id);
        return view('admin.turbojet-holiday.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'date' => 'required|date',
        ]);

        $holiday = TurbojetHoliday::findOrFail($id);
        $holiday->update($request->all());

        return redirect("admin/turbojet-holiday");
    }

    public function destroy($id)
    {
        $coupon = TurbojetHoliday::findOrFail($id);
        $coupon->delete();

        return redirect("admin/turbojet-holiday");
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'turbojet-holiday' => 'required|file',
        ]);

        try
        {
            \DB::beginTransaction();

            $result = \Excel::load($request->file('turbojet-holiday')->getRealPath())->get();

            TurbojetHoliday::query()->delete();

            foreach ($result as $item)
            {
                $holiday = new TurbojetHoliday();
                $holiday->{TurbojetHoliday::COLUMN_DATE} = $item['holiday_date'];
                $holiday->save();
            }

            \DB::commit();
            \Session::flash('success', 'Turbojet Holiday has been uploaded successfully!');
            return back();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            \Session::flash('danger', 'Something wrong when processing your uploaded holiday file! ' . $e->getMessage());
            return back();
        }
    }
}
