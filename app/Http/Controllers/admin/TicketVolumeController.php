<?php

namespace App\Http\Controllers\admin;

use App\TicketVolume;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TicketVolumeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Ticket');

    }


    public function index()
    {
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Volume";
        $data['volume']=TicketVolume::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.ticket.volume.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Volume";

        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        $data['name'] = DB::table('ticket_prices')->where('pricegroupid', '=', $pricegroupid)->where('deleted_at',null)->lists('title', 'id');
        $data['name1'] = DB::table('ticket_prices')->where('pricegroupid', '=', $pricegroupid)->where('deleted_at',null)->lists('title', 'id');
        return view('admin.ticket.volume.create',$data);
    }
    public function store(Request $request,$detailid,$pricegroupid)
    {

       // $volume = new TicketVolume($request->all());
        $volume=$request->all();
        $this->validate($request, [
            'discount' => 'Numeric',
            'discount1' => 'Numeric',
            'status' => 'required',
        ]);
        $volume['date']=$request['date'];
        $volume['to']=$request['to'];
        $volume['title1'] = $request['title1'];

        $volume['detailid'] = $detailid;
        $volume['pricegroupid'] = $pricegroupid;

        if (!empty($request['title'])) {
            $volume['title'] = implode(',', $request['title']);

        $volume['title'];
        $start=$volume['date'];
        $end=$volume['to'];

        $check_duplicate = TicketVolume::where('type', '=', 'Multiple')->where('pricegroupid', $pricegroupid)->where('status', 'active')->where('title','like', '%'.$volume['title'].'%' )->get();
        foreach($check_duplicate as $key1 => $check) {
            $start_db = strtotime($check['date']);
            $end_db = strtotime($check['to']);
            $startdate = strtotime($start);
            $enddate = strtotime($end);
            if (($start_db >= $startdate && $end_db <= $enddate) || ($start_db <= $startdate && $end_db >= $enddate) || ($enddate <= $end_db && $enddate >= $start_db) || ($startdate >= $start_db && $startdate <= $end_db))
            {
                return redirect()->back()->with('message', 'each title cannot apply more than 1 multiple discount in duplicate period!');
            }

        }
//        return $check_duplicate = TicketVolume::where('type', '=', 'Multiple')->where('pricegroupid', $pricegroupid)->where('status', 'active')->where('title','like', '%'.$volume['title'].'%' )
//        ->where(function($query) use ($start, $end,$pricegroupid,$volume)
//            {
//               $query->where('date','>=',$start)
//                   ->where('to','<=',$end)
//                   ->where('type', '=', 'Multiple')
//                   ->where('pricegroupid', $pricegroupid)
//                   ->where('status', 'active')
//                   ->where('title','like', '%'.$volume['title'].'%' )
//                   ->where('deleted_at',null);
//
//            })
//            ->orWhere(function($query) use ($start, $end,$pricegroupid,$volume)
//             {
//                $query->where('date','<=',$start)
//                ->where('to','>=',$end)
//                    ->where('type', '=', 'Multiple')
//                    ->where('pricegroupid',$pricegroupid)
//                    ->where('status', 'active')
//                    ->where('title','like', '%'.$volume['title'].'%' )
//                     ->where('deleted_at',null);
//            })
//            ->get();

        }

        $insert=TicketVolume::create($volume);

        Activity::log('TicketVolume [Id = '.$insert->id.'] has been inserted');

        return redirect('admin/ticket/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {

        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Volume";

        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['volume'] = TicketVolume::findOrFail($pricegroupid);
        $data['name'] = DB::table('ticket_prices')->where('pricegroupid', '=', $detailid)->where('deleted_at',null)->lists('title', 'id');
        $data['name1'] = DB::table('ticket_prices')->where('pricegroupid', '=', $detailid)->where('deleted_at',null)->lists('title', 'id');
        $data['modes'] = explode(",",$data['volume']['title']);
        $data['modes1'] = explode(",",$data['volume']['title1']);
        return view('admin.ticket.volume.edit',$data);
    }

    public function update(Request $request,$detailid,$pricegroupid,$id)
    {
     //   return 'id'.$id.'-'.'$pricegroupid'.$pricegroupid;
        $this->validate($request, [
            'discount' => 'Numeric',
            'discount1' => 'Numeric',
            'status' => 'required',
        ]);
        $volume = TicketVolume::findOrFail($id);
        $input = $request->all();
        if (!empty($request['title'])) {
            $input['title'] = implode(',',$request['title']);

            $start=$input['date'];
            $end=$input['to'];
//return 'id-'.$id.'$pricegroupid-'.$pricegroupid.'$detailid-'.$detailid;
             $check_duplicate = TicketVolume::where('id','!=',$id)->where('type', '=', 'Multiple')->where('pricegroupid', $pricegroupid)->where('status', 'active')->where('title','like', '%'.$volume['title'].'%' )->get();
            foreach($check_duplicate as $key1 => $check) {
                $start_db = strtotime($check['date']);
                $end_db = strtotime($check['to']);
                $startdate = strtotime($start);
                $enddate = strtotime($end);
                if (($start_db >= $startdate && $end_db <= $enddate) || ($start_db <= $startdate && $end_db >= $enddate) || ($enddate <= $end_db && $enddate >= $start_db) || ($startdate >= $start_db && $startdate <= $end_db))
                {
                    return redirect()->back()->with('message', 'each title cannot apply more than 1 multiple discount in duplicate period!');
                }

            }

//            $check_duplicate = TicketVolume::where('id','!=',$id)->where('type','Multiple')->where('pricegroupid', $pricegroupid)->where('deleted_at','')->where('status', 'active')->where('title','like', '%'.$input['title'].'%' )
//                ->where(function($query) use ($start, $end,$id,$pricegroupid)
//                {
//                     $query->where('type', '=', 'Multiple')
//                    ->where('pricegroupid', $pricegroupid)
//                        ->where('date' ,'>=',$start)
//                        ->where('to','<=',$end)
//                        ->where('id','!=',$id);
//                })
//                ->orWhere(function($query) use ($start,$end,$id,$pricegroupid)
//                {
//                    $query->where('type', '=', 'Multiple')
//                        ->where('pricegroupid', $pricegroupid)
//                    ->where('date' ,'<=',$start)
//                        ->where('to','>=',$end)
//                        ->where('id','!=',$id);
//                })
//                ->count();

//            if($check_duplicate > 0){
//                return redirect()->back()->with('message', 'each title cannot apply more than 1 multiple discount in duplicate period!');            }
//
       }
       // $input['title'] = implode(',',$request->title);
        $input['title1'] = $request->title1;
        $input['detailid'] = $detailid;
        $input['pricegroupid'] = $pricegroupid;

        $volume->update($input);

        Activity::log('TicketVolume [Id = '.$volume->id.'] has been updated');
        return redirect('admin/ticket/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }
    public function destroy($detailid,$pricegroupid,$id)
    {
        $data['detail'] = $detailid;
        $data['detail1'] = $id;
        $volume = TicketVolume::findOrFail($id);
        $volume->delete();

        Activity::log('TicketVolume [Id = '.$volume->id.'] has been deleted');
        return redirect('admin/ticket/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
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
            $volume = TicketVolume::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $volume->update($input);
        }
        return redirect('admin/ticket/volume');
    }

    public function assign(Request $request)
    {
        $volume = TicketVolume::findorFail($request['id']);
        $volume['status']="active";
        $volume->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $volume = TicketVolume::findorFail($request['id']);
        $volume['status']="inactive";
        $volume->update($request->all());
        return $request['id'];
    }

}
