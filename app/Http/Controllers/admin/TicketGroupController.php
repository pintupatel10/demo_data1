<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Images;
use App\TicketGroup;
use App\TicketList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketGroupController extends Controller
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
        $data['menu']="Ticket Group";
        $data['groupdetail']=TicketGroup::orderBy('id','ASC')->get();
        return view('admin.ticket.ticketgroup.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Ticket";
        $data['image_center'] = Images::get();
        $data['menu']="Ticket Group";
        $data['name']=TicketList::get();

        return view('admin.ticket.ticketgroup.create',$data);
    }
    public function store(Request $request)
    {
        $ticket = new TicketGroup($request->all());
        $this->validate($request, [
            'portrait_image' => 'mimes:jpeg,jpg,bmp,png',
            'landscape_image' => 'mimes:jpeg,jpg,bmp,png',
            'title' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
            'select_sentence'=>'required',

        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TicketGroup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $ticket->displayorder = $c;
        /*---------------------*/

        if(!empty($request->portrait_image_name))
        {
            $ticket->portrait_image = $request->portrait_image_name;
        }
        else {
            if($photo = $request->file('portrait_image'))
            {
                $root = base_path() . '/public/resource/images/' ;
                $name = str_random(20).".".$photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/".$name;
                ImageHelper::generateThumbnail($image_path);

                $photo->move($root,$name);
                $ticket['portrait_image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['portrait_image' => 'please select image']);
            }
        }

        if(!empty($request->landscape_image_name))
        {
            $ticket->landscape_image = $request->landscape_image_name;
        }
        else {
            if($photo = $request->file('landscape_image'))
            {
                $root = base_path() . '/public/resource/images/' ;
                $name = str_random(20).".".$photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/".$name;
                ImageHelper::generateThumbnail($image_path);

                $photo->move($root,$name);
                $ticket['landscape_image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['landscape_image' => 'please select image']);
            }
        }
        if(!empty($ticket->language))
        {
            $ticket->language=implode(',',$ticket->language);
        }
        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $ticket['ticket_list'] = $itm[1];
        }
      //  $ticket['ticket_list']= $request['itm_sel'];
        $ticket->save();

        Activity::log('Ticket Group -'.$ticket->title.' [Id = '.$ticket->id.'] has been inserted');
        \Session::flash('success', 'Ticket Group has been inserted successfully!');
        return redirect('admin/ticket/ticketgroup');
    }

    public function edit($id)
    {
        $b=array();
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket Group";
        $data['image_center'] = Images::get();
        $data['groupdetail'] = TicketGroup::findOrFail($id);

        $lists = TicketList::get();
        foreach($lists as $key => $val){
            $mode = explode(",",$data['groupdetail']['ticket_list']);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($lists[$key]);
                }
            }
        }
        $data['mode3']=$lists;
        $mode33 = explode(",",$data['groupdetail']['ticket_list']);
        foreach ($mode33 as $mod){
            $q=TicketList::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$q);
        }
        $data['mode50']=$b;

        return view('admin.ticket.ticketgroup.edit',$data);
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'portrait_image' => 'mimes:jpeg,jpg,bmp,png',
            'landscape_image' => 'mimes:jpeg,jpg,bmp,png',
            'title' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
            'select_sentence'=>'required',
        ]);

        $groupdetail = TicketGroup::findOrFail($id);
        $input = $request->all();

        if(!empty($request->portrait_image_name))
        {
            $input['portrait_image'] = $request->portrait_image_name;
        }
        else {
            if($photo = $request->file('portrait_image'))
            {
                $root = base_path() . '/public/resource/images/' ;
                $name = str_random(20).".".$photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/".$name;
                ImageHelper::generateThumbnail($image_path);

                $photo->move($root,$name);
                $input['portrait_image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }

        if(!empty($request->landscape_image_name))
        {
            $input['landscape_image'] = $request->landscape_image_name;
        }
        else {
            if($photo = $request->file('landscape_image'))
            {
                $root = base_path() . '/public/resource/images/' ;
                $name = str_random(20).".".$photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/".$name;
                ImageHelper::generateThumbnail($image_path);
                $photo->move($root,$name);
                $input['landscape_image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }

        $input['language'] = implode(',',$request->language);
        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $input['ticket_list'] = $itm[1];
        }
        else{
            $input['ticket_list']="";
        }
      //  $input['ticket_list']= $request['itm_sel'];
        $groupdetail->update($input);

        Activity::log('Ticket Group -'.$groupdetail->title.' [Id = '.$groupdetail->id.'] has been Updated');
        \Session::flash('success', 'Ticket Group has been Updated successfully!');
        return redirect('admin/ticket/ticketgroup');
    }
    public function destroy($id)
    {
        $groupdetail = TicketGroup::findOrFail($id);
       
        $groupdetail->delete();

        Activity::log('Ticket Group -'.$groupdetail->title.' [Id = '.$groupdetail->id.'] has been deleted');
        \Session::flash('danger','Ticket Group has been deleted successfully!');
        return redirect('admin/ticket/ticketgroup');
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
            $groupdetail = TicketGroup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $groupdetail->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/ticket/ticketgroup');
    }

    public function assign(Request $request)
    {
        $groupdetail = TicketGroup::findorFail($request['id']);
        $groupdetail['status']="active";
        $groupdetail->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $groupdetail = TicketGroup::findorFail($request['id']);
        $groupdetail['status']="inactive";
        $groupdetail->update($request->all());
        return $request['id'];
    }
}
