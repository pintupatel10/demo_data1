<?php

namespace App\Http\Controllers\admin;

use App\Ticketcollection;
use App\TicketFilter;
use App\TicketGroup;
use App\TicketList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TicketFilterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Ticket');

    }

    public function create($cid)
    {
        $data=[];
        $data['detail']=$cid;
        $a=array();
        $b=array();
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Filter";
        $data['collection'] = Ticketcollection::findOrFail($cid);
        $data['name']=TicketList::all();
        $data['name1'] = TicketGroup::all();
        $data['filter']=$data['collection']->TicketFilter()->get();
        $data['modes_selected'] = explode(",",$data['collection']['language']);


        /* ==== TICKET GROUP ===*/
        $mode1 = explode(",",$data['collection']['group_list']);
        //$list=implode(',',$mode1);
        foreach ($mode1 as $mod){
            $p=TicketGroup::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$p);
        }

        $data['mode1'] = $b;

        /* ==== TICKET LIST === */
        $mode = explode(",",$data['collection']['ticket_list']);
        //$list=implode(',',$mode);
        foreach ($mode as $mod){
            $q=TicketList::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
        $data['mode']=$a;

        return view('admin.ticket.filter.create',$data);
    }
    public function store(Request $request,$cid)
    {
        $filter = new TicketFilter($request->all());
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TicketFilter::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $filter->displayorder = $c;
        /*---------------------*/
        $filter['cid'] = $cid;

        // $filter['group_list']  = $request['grp_sel'];
        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $filter['ticket_list'] = $itm[1];
        }
        $filter->save();

        Activity::log('TicketFilter -'.$filter->name.' [Id = '.$filter->id.'] has been inserted');
        return redirect('admin/ticket/collection/'.$cid.'/edit');
    }

    public function edit($cid,$id)
    {
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Filter";
        // $a=array();
        // $b=array();

        $c=array();
        $d=array();
        // $name=array();
        $data['detail'] = $cid;
        $data['filter'] = TicketFilter::findOrFail($id);
        $data['collection'] = Ticketcollection::findOrFail($cid);
        $data['name']=TicketList::all();
        $data['name1'] = TicketGroup::all();

        $i_sel=explode(",",$data['filter']['ticket_list']);

        $ticket='';
        $grp='';
        foreach($i_sel as $key =>$v){
            $s=explode('-',$v);
            if($s[0] == 'L'){
                $ticket.=$s[1].",";
            }
            if($s[0] == 'G'){
                $grp.=$s[1].",";
            }
        }

        /* EDIT MODE NOT SELECTED MENUS */
        ///// TICKET GROUP//////

        $mode4 = explode(",",$data['collection']['group_list']);
        $mode44 = explode(",",$grp);
        $mode4 = array_diff($mode4, $mode44);
        foreach ($mode4 as $mod){
            $p=TicketGroup::where('id',$mod)->get()->toArray();
            $c=array_merge($c,$p);
        }
        $data['mode4'] = $c;

        /* === TICKET LIST === */
        $mode3 = explode(",",$data['collection']['ticket_list']);
        $mode33 = explode(",",$ticket);
        $mode3 = array_diff($mode3, $mode33);
        foreach ($mode3 as $mod){
            $q=TicketList::where('id',$mod)->get()->toArray();
            $d=array_merge($d,$q);
        }
        $data['mode3']=$d;

        /* EDIT MODE SELECTED MENUS */

        $mode1=array();
        foreach ($i_sel as $m) {
            $mode1 = array_merge($mode1, explode('-', $m));
        }
        $data['mode50']=$mode1;
        return view('admin.ticket.filter.edit',$data);
    }

    public function update(Request $request, $id,$cid)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $filter = TicketFilter::findOrFail($cid);
        $input = $request->all();
        $input['cid'] = $id;
        // $input['group_list']  = $request['grp_sel'];
        // return  $input['ticket_list'] = $request['itm_sel'];

        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $input['ticket_list'] = $itm[1];
        }
        else{
            $input['ticket_list']="";
        }

        $filter->update($input);

        Activity::log('TicketFilter -'.$filter->name.' [Id = '.$filter->id.'] has been Updated');
        return redirect('admin/ticket/collection/'.$id.'/edit');
    }
    public function destroy($id,$cid)
    {
        $data['detail'] = $id;
        $filter = TicketFilter::findOrFail($cid);
        $filter->delete();

        Activity::log('TicketFilter -'.$filter->name.' [Id = '.$filter->id.'] has been deleted');
        return redirect('admin/ticket/collection/'.$id.'/edit');
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
            $filter = TicketFilter::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $filter->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/ticket/filter');
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $filter = TicketFilter::findOrFail($idval);
                $input['displayorder'] = $count;
                $filter->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }

    public function assign(Request $request)
    {
        $filter = TicketFilter::findorFail($request['id']);
        $filter['status']="active";
        $filter->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $filter = TicketFilter::findorFail($request['id']);
        $filter['status']="inactive";
        $filter->update($request->all());
        return $request['id'];
    }
}
