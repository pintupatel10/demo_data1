<?php

namespace App\Http\Controllers\admin;

use App\Ticketcollection;
use App\TicketGroup;
use App\TicketList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketcollectionController extends Controller
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
        $data['menu']="Ticket Collection";
        $data['collection']=Ticketcollection::orderBy('id','ASC')->get();
        return view('admin.ticket.collection.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Collection";
        $data['name']=TicketList::all();
        $data['name1']=TicketGroup::all();
        return view('admin.ticket.collection.create',$data);
    }

    public function store(Request $request)
    {
        $ticket = new Ticketcollection($request->all());
        $this->validate($request, [
            'name' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);


        /* ADD DISPLAY ORDER */
        $count_display_order = Ticketcollection::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $ticket->displayorder = $c;
        /*---------------------*/

        if(!empty($ticket->language))
        {
            $ticket->language=implode(',',$ticket->language);
        }

        $ticket['group_list']=$request['grp_sel'];
        $ticket['ticket_list']= $request['itm_sel'];
        $ticket['description']=$request['description'];
        $ticket->save();

        Activity::log('Ticket Collection  -'.$ticket->title.' [Id = '.$ticket->id.'] has been inserted');
        \Session::flash('success', 'Ticket Collection has been inserted successfully!');
        return redirect('admin/ticket/collection');
    }

    public function edit($id)
    {
        $a=array();
        $b=array();
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Collection";
        $data['collection'] = Ticketcollection::findOrFail($id);
        $data['name']=TicketList::all();
        $data['name1'] = TicketGroup::all();
        $data['filter']=$data['collection']->TicketFilter()->orderBy('displayorder','ASC')->Paginate($this->pagination);
        $data['modes_selected'] = explode(",",$data['collection']['language']);

        foreach($data['name1'] as $key => $val){
            $mode1 = explode(",",$data['collection']['group_list']);
            foreach ($mode1 as $mod){
                if($mod == $val['id']){
                    unset($data['name1'][$key]);
                }
            }
        }
        $mode1 = explode(",",$data['collection']['group_list']);
        $list=implode(',',$mode1);
        foreach ($mode1 as $mod){
            $p=TicketGroup::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$p);
        }

        $data['mode1'] = $b;

        foreach($data['name'] as $key => $val){
            $mode = explode(",",$data['collection']['ticket_list']);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['name'][$key]);
                }
            }
        }
        $mode = explode(",",$data['collection']['ticket_list']);
        $list=implode(',',$mode);
        foreach ($mode as $mod){
            $q=TicketList::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
        $data['mode']=$a;

        return view('admin.ticket.collection.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $collection = Ticketcollection::findOrFail($id);
        $input = $request->all();

        $input['language'] = implode(',',$request->language);

        $input['group_list']= $request['grp_sel'];
        $input['ticket_list'] =$request['itm_sel'];
        $input['description']=$request['description'];

        $collection->update($input);

        Activity::log('Ticket Collection -'.$collection->title.' [Id = '.$collection->id.'] has been Updated');
        \Session::flash('success', 'Ticket Collection has been Updated successfully!');
        return redirect('admin/ticket/collection');
    }
    public function destroy($id)
    {
        $collection = Ticketcollection::findOrFail($id);
        $collection->delete();

        Activity::log('Ticket Collection -'.$collection->title.' [Id = '.$collection->id.'] has been deleted');
        \Session::flash('danger','Ticket Collection has been deleted successfully!');
        return redirect('admin/ticket/collection');
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
            $collection = Ticketcollection::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $collection->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/ticket/collection');
    }

    public function assign(Request $request)
    {
        $collection = Ticketcollection::findorFail($request['id']);
        $collection['status']="active";
        $collection->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $collection = Ticketcollection::findorFail($request['id']);
        $collection['status']="inactive";
        $collection->update($request->all());
        return $request['id'];
    }
}
