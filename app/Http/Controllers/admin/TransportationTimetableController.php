<?php

namespace App\Http\Controllers\admin;

use App\TransportationTimetable;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportationTimetableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transportation');
    }


    public function index()
    {
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation TimeTable";
        $data['time']=TransportationTimetable::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.transportation.timetable.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation TimeTable";
        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        return view('admin.transportation.timetable.create',$data);
    }
    public function store(Request $request,$detailid,$pricegroupid)
    {
        $image = new TransportationTimetable($request->all());

        $this->validate($request, [

        ]);


        $image['detailid'] = $detailid;
        $image['pricegroupid'] = $pricegroupid;

        if (!empty($request['Weekend/Weekday'])) {
            $image['Weekend/Weekday'] = implode(',', $request['Weekend/Weekday']);
        }
        $image->save();

        Activity::log('TransportationTimetable [Id = '.$image->id.'] has been inserted');
        return redirect('admin/transportation/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation TimeTable";
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['time'] = TransportationTimetable::findOrFail($pricegroupid);
        $data['modes_selected'] = explode(",",$data['time']['Weekend/Weekday']);
        return view('admin.transportation.timetable.edit',$data);
    }

    public function update(Request $request,$id,$detailid,$pricegroupid)
    {
        $this->validate($request, [

        ]);

        $time = TransportationTimetable::findOrFail($pricegroupid);
        $input = $request->all();
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;
        $input['Weekend/Weekday'] = implode(',', $request['Weekend/Weekday']);


        $time->update($input);

        Activity::log('TransportationTimetable [Id = '.$time->id.'] has been updated');
        return redirect('admin/transportation/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $time = TransportationTimetable::findOrFail($pricegroupid);
        $time->delete();

        Activity::log('TransportationTimetable [Id = '.$time->id.'] has been deleted');
        return redirect('admin/transportation/'.$id.'/pricegroup/'.$detailid.'/edit');
    }

    public function update_display_order(Request $request)
    {

        $count1 = $request->count;
        for($i = 1;$i <= $count1;$i++)
        {
            $request->pid1;
            if(isset($request["disp".$i]))
            {
                $disp1 = $request["disp".$i];
                $pid1 = $request["pid".$i];
            }
            $time = TransportationTimetable::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $time->update($input);
        }
        return redirect('admin/transportation/timetable');
    }

    public function assign(Request $request)
    {
        $time = TransportationTimetable::findorFail($request['id']);
        $time['status']="active";
        $time->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $time = TransportationTimetable::findorFail($request['id']);
        $time['status']="inactive";
        $time->update($request->all());
        return $request['id'];
    }
}
