<?php

namespace App\Http\Controllers\admin;

use App\TicketPricegroup;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketPricegroupController extends Controller
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
        $data['menu']="Ticket pricegroup";
        $data['pricegroup']=TicketPricegroup::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.ticket.pricegroup.index',$data);
    }

    public function create($detailid)
    {
        $data=[];
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket pricegroup";
        $data['detail']=$detailid;
        return view('admin.ticket.pricegroup.create',$data);
    }
    public function store(Request $request,$detailid)
    {
        $image = new TicketPricegroup($request->all());

        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TicketPricegroup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/
        $image['detailid'] = $detailid;
        $image->save();

        Activity::log('TicketPricegroup -'.$image->title.'  [Id = '.$image->id.'] has been inserted');

        return redirect('admin/ticket/ticketlist/'.$detailid.'/edit');
    }

    public function edit($id,$detailid)
    {
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket pricegroup";
        $data['detail'] = $id;
        $data['pricegroup'] = TicketPricegroup::findOrFail($detailid);
        $data['price']=$data['pricegroup']->TicketPrice()->get();
        $data['volume']=$data['pricegroup']->TicketVolume()->get();
        return view('admin.ticket.pricegroup.edit',$data);
    }

    public function update(Request $request, $id,$detailid)
    {
        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);


        $pricegroup = TicketPricegroup::findOrFail($detailid);
        $input = $request->all();
        $input['detailid'] = $id;
        $pricegroup->update($input);

        Activity::log('TicketPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been Updated');
        return redirect('admin/ticket/ticketlist/'.$id.'/edit');

    }
    public function destroy($id,$detailid)
    {
        $data['detail'] = $id;
        $pricegroup = TicketPricegroup::findOrFail($detailid);
        $pricegroup->delete();

        Activity::log('TicketPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been deleted');
        return redirect('admin/ticket/ticketlist/'.$id.'/edit');
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
            $pricegroup = TicketPricegroup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $pricegroup->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/ticket/pricegroup');
    }

    public function assign(Request $request)
    {
        $pricegroup = TicketPricegroup::findorFail($request['id']);
        $pricegroup['status']="active";
        $pricegroup->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $pricegroup = TicketPricegroup::findorFail($request['id']);
        $pricegroup['status']="inactive";
        $pricegroup->update($request->all());
        return $request['id'];
    }
}
