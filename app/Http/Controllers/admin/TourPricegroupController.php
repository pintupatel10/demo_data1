<?php

namespace App\Http\Controllers\admin;

use App\TourPricegroup;
use App\TurbojetTicket;
use App\TurbojetTimetable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TourPricegroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Tour');

    }


    public function index()
    {
        $data['mainmenu']="Tour";
        $data['menu']="Tour pricegroup";
        $data['pricegroup']=TourPricegroup::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.tour.pricegroup.index',$data);
    }

    public function create($detailid)
    {
        $data=[];
        $data['mainmenu']="Tour";
        $data['menu']="Tour pricegroup";
        $data['detail']=$detailid;
        return view('admin.tour.pricegroup.create',$data);
    }
    public function store(Request $request,$detailid)
    {
        $image = new TourPricegroup($request->all());

        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TourPricegroup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/
        $image['detailid'] = $detailid;
        $image->save();

        Activity::log('TourPricegroup -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        return redirect('admin/tour/tourlist/'.$detailid.'/edit');
    }

    public function edit($id,$detailid)
    {
        $data['mainmenu']="Tour";
        $data['menu']="Tour pricegroup";
        $data['detail'] = $id;
        $data['pricegroup'] = TourPricegroup::findOrFail($detailid);
        $data['tourprice']=$data['pricegroup']->TourPrice()->get();
        return view('admin.tour.pricegroup.edit',$data);
    }

    public function update(Request $request, $id,$detailid)
    {
        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);


        $pricegroup = TourPricegroup::findOrFail($detailid);
        if ($pricegroup->TourList->isTurbojetType())
        {
            $this->validate($request, [
                'turbojet_ticket.city_1' => 'required',
                'turbojet_ticket.city_1_code' => 'required',
                'turbojet_ticket.city_2' => 'required',
                'turbojet_ticket.city_2_code' => 'required',
                'turbojet_ticket.top_up_fee' => 'numeric',
            ], [], [
                'turbojet_ticket.city_1' => 'City 1 Name',
                'turbojet_ticket.city_1_code' => 'City 1 Code',
                'turbojet_ticket.city_2' => 'City 2 Name',
                'turbojet_ticket.city_2_code' => 'City 2 Code',
                'turbojet_ticket.top_up_fee' => 'Top Up Fee',
            ]);

            if ($pricegroup->turbojet_ticket)
                $pricegroup->turbojet_ticket->update($request->input('turbojet_ticket'));
            else{
                $ticket = new TurbojetTicket();
                $ticket->fill($request->input('turbojet_ticket'));
                $pricegroup->turbojet_ticket()->save($ticket);
            }
        }

        $input = $request->all();
        $input['detailid'] = $id;
        $pricegroup->update($input);

        Activity::log('TourPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been updated');
        return redirect('admin/tour/tourlist/'.$id.'/edit');

    }
    public function destroy($id,$detailid)
    {
        $data['detail'] = $id;
        $pricegroup = TourPricegroup::findOrFail($detailid);
        $pricegroup->delete();

        Activity::log('TourPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been deleted');
        return redirect('admin/tour/tourlist/'.$id.'/edit');
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
            $pricegroup = TourPricegroup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $pricegroup->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/tour/pricegroup');
    }

    public function assign(Request $request)
    {
        $pricegroup = TourPricegroup::findorFail($request['id']);
        $pricegroup['status']="active";
        $pricegroup->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $pricegroup = TourPricegroup::findorFail($request['id']);
        $pricegroup['status']="inactive";
        $pricegroup->update($request->all());
        return $request['id'];
    }

    public function turbojetTimetable(Request $request, $id, $detailid)
    {
        $this->validate($request, [
            'turbojet-timetable' => 'required|file',
        ]);

        $pricegroup = TourPricegroup::findOrFail($detailid);
        if (!$pricegroup->turbojet_ticket)
            abort(404);

        try
        {
            \DB::beginTransaction();

            $result = \Excel::load($request->file('turbojet-timetable')->getRealPath())->get();

            $cities = [$pricegroup->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_1_CODE}, $pricegroup->turbojet_ticket->{TurbojetTicket::COLUMN_CITY_2_CODE}];

            $pricegroup->turbojet_timetables()->delete();

            foreach ($result as $item)
            {
                if (in_array($item['from'], $cities) && in_array($item['to'], $cities) && in_array($item['class'], TurbojetTimetable::$classes)) {
                    $timetable = new TurbojetTimetable();
                    $timetable->{TurbojetTimetable::COLUMN_FROM} = $item['from'];
                    $timetable->{TurbojetTimetable::COLUMN_TO} = $item['to'];
                    $timetable->{TurbojetTimetable::COLUMN_CLASS} = $item['class'];
                    $timetable->{TurbojetTimetable::COLUMN_TIME} = Carbon::parse($item['time'])->format('H:i');
                    $timetable->{TurbojetTimetable::COLUMN_IS_WEEKDAY} = $item['weekday'] == "Y";
                    $timetable->{TurbojetTimetable::COLUMN_IS_WEEKEND} = $item['weekend'] == "Y";
                    $timetable->{TurbojetTimetable::COLUMN_IS_HOLIDAY} = $item['holiday'] == "Y";
                    $timetable->{TurbojetTimetable::COLUMN_IS_NON_HOLIDAY} = $item['non_holiday'] == "Y";
                    $pricegroup->turbojet_timetables()->save($timetable);
                }
            }

            \DB::commit();
            \Session::flash('success', 'Turbojet timetable has been uploaded successfully!');
            return back();
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            \Session::flash('danger', 'Something wrong when processing your uploaded timetable! ' . $e->getMessage());
            return back();
        }
    }
}
