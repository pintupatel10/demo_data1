<?php

namespace App\Http\Controllers\admin;

use App\TransportationPricegroup;
use App\TurbojetTicket;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportationPricegroupController extends Controller
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
        $data['menu']="Transportation pricegroup";
        $data['pricegroup']=TransportationPricegroup::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.transportation.pricegroup.index',$data);
    }

    public function create($detailid)
    {
        $data=[];
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation pricegroup";
        $data['detail']=$detailid;
        return view('admin.transportation.pricegroup.create',$data);
    }
    public function store(Request $request,$detailid)
    {
        $image = new TransportationPricegroup($request->all());

        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'charge' => 'required|Numeric',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TransportationPricegroup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/
        $image['detailid'] = $detailid;
        $image->save();
        Activity::log('TransportationPricegroup  -'.$image->title.'[Id = '.$image->id.'] has been inserted');
        return redirect('admin/transportation/transportationlist/'.$detailid.'/edit');
    }

    public function edit($id,$detailid)
    {
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation pricegroup";
        $data['detail'] = $id;
        $data['pricegroup'] = TransportationPricegroup::findOrFail($detailid);
        $data['price']=$data['pricegroup']->TransportationPrice()->get();
        $data['time']=$data['pricegroup']->TransportationTimetable()->get();
        return view('admin.transportation.pricegroup.edit',$data);
    }

    public function update(Request $request, $id,$detailid)
    {
        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'charge' => 'required|Numeric',
            'status' => 'required',
        ]);

        $pricegroup = TransportationPricegroup::findOrFail($detailid);
        if ($pricegroup->TransportationList->isTurbojetType())
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

        Activity::log('TransportationPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been updated');
        return redirect('admin/transportation/transportationlist/'.$id.'/edit');

    }
    public function destroy($id,$detailid)
    {
        $data['detail'] = $id;
        $pricegroup = TransportationPricegroup::findOrFail($detailid);
        $pricegroup->delete();

        Activity::log('TransportationPricegroup  -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been deleted');
        return redirect('admin/transportation/transportationlist/'.$id.'/edit');
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
            $pricegroup = TransportationPricegroup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $pricegroup->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/transportation/pricegroup');
    }

    public function assign(Request $request)
    {
        $pricegroup = TransportationPricegroup::findorFail($request['id']);
        $pricegroup['status']="active";
        $pricegroup->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $pricegroup = TransportationPricegroup::findorFail($request['id']);
        $pricegroup['status']="inactive";
        $pricegroup->update($request->all());
        return $request['id'];
    }
}
