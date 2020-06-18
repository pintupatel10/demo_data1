<?php

namespace App\Http\Controllers\admin;

use App\Checkpoint;
use App\Helpers\ImageHelper;
use App\Images;
use App\Ticketcheckpoint;
use App\Tickethighlight;
use App\TicketList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Ticket');

    }

    public function index()
    {
        $data['mainmenu'] = "Ticket";
        $data['menu'] = "Ticket List";
        $data['detail'] = TicketList::OrderBy('displayorder','ASC')->Paginate($this->pagination);
        return view('admin.ticket.ticketlist.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['mainmenu'] = "Ticket";
        $data['image_center'] = Images::get();

        $data['menu'] = "Ticket List";
        return view('admin.ticket.ticketlist.create', $data);
    }

    public function store(Request $request)
    {
        $detail = new TicketList($request->all());

        $this->validate($request, [
            'title' => 'required',
            'ticket_type' => 'required',
            'ticket_code' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
        ]);

        $count_display_order = TicketList::count();
        if ($count_display_order >= 0) {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $detail['displayorder'] = $c;
        /*---------------------*/
        if(!empty($request->image_name))
        {
            $detail['image'] = $request->image_name;
        }
        else {
            if($photo = $request->file('image'))
            {
                $root = base_path() . '/public/resource/images/' ;
                $name = str_random(20).".".$photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/".$name;
                ImageHelper::generateThumbnail($image_path);
                $photo->move($root,$name);
                $detail['image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['image' => 'please select image']);
            }
        }
        if (!empty($request['ticket_type'])) {
            $detail['ticket_type'] = implode(',', $request['ticket_type']);
        }
        if (!empty($request['language'])) {
            $detail['language'] = implode(',', $request['language']);
        }

        $detail['post'] = 'Public';
        
        $detail->save();

        Activity::log('Ticket List -'.$detail->title.' [Id = '.$detail->id.'] has been inserted');
        \Session::flash('success', 'Ticket List has been inserted successfully!');
        return redirect('admin/ticket/ticketlist');
    }

    public function edit($id)
    {
        $data['mainmenu'] = "Ticket";
        $data['menu'] = "Ticket List";
        $data['image_center'] = Images::get();
        $data['detail'] = TicketList::findOrFail($id);
        $data['modes'] = explode(",",$data['detail']['ticket_type']);
      //  $data['checkpoint']=$data['detail']->Ticketcheckpoint()->get();
        $data['checkpoint']=Ticketcheckpoint::where('detailid',$id)->get();
        $data['checkpoint_center']=Checkpoint::where('status','active')->get();
        $data['pricegroup']=$data['detail']->TicketPricegroup()->get();
        $data['price']=$data['detail']->TicketPrice()->get();
        $data['volume']=$data['detail']->TicketVolume()->get();
        $data['modes_selected'] = explode(",",$data['detail']['language']);
        return view('admin.ticket.ticketlist.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'ticket_type' => 'required',
            'ticket_code' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
        ]);
        $detail = TicketList::findOrFail($id);
        $input = $request->all();


        if(!empty($request->image_name))
        {
            $input['image'] = $request->image_name;
        }
        else {
            if ($photo = $request->file('image')) {
                $root = base_path() . '/public/resource/images/';
                $name = str_random(20) . "." . $photo->getClientOriginalExtension();
                $mimetype = $photo->getMimeType();
                $explode = explode("/", $mimetype);
                $type = $explode[0];
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $photo->move($root, $name);
                $image_path = "resource/images/" . $name;
                ImageHelper::generateThumbnail($image_path);

                $input['image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }
        $input['ticket_type'] = implode(',',$request->ticket_type);
        $input['language'] = implode(',',$request->language);

        $count1 = $input['theValue1213'];

        if (isset($input['remove_id1']) && $input['remove_id1'] != "") {
            $remove_image = explode(",", $input['remove_id1']);
            foreach ($remove_image as $val) {
                $address = Tickethighlight::findOrFail($val);
                $address->forceDelete();
            }
        }

        if ((isset($input['remove_id1']) && $input['remove_id1'] != "") && (isset($input['attribute_id']) && $input['attribute_id'] != "")) {
            $update_id1 = explode(",", $input['attribute_id']);
            $remove_address = explode(",", $input['remove_id1']);
            foreach ($update_id1 as $key=>$val) {
                if (in_array($val,$remove_address)){
                    unset($update_id1[$key]);
                }
            }
        }
        else if(isset($input['attribute_id']) && $input['attribute_id'] != "") {
            $update_id1 = explode(",", $input['attribute_id']);
        }

        for ($i = 1; $i <= $count1; $i++) {
            $j = $i - 1;
            if (!empty($update_id1[$j])) {
                $address = Tickethighlight::findOrFail($update_id1[$j]);
                if (!empty($address)){
                    $addr['title']=$input['title'.$i];
                    $addr['content']=$input['content'.$i];
                    $address->update($addr);
                }
            }
            else{
                $addr['title']=$input['title'.$i];
                $addr['content']=$input['content'.$i];
                $addr['detailid'] = $detail->id;
                Tickethighlight::create($addr);
            }
        }

        $detail->update($input);

        Activity::log('Ticket List -'.$detail->title.' [Id = '.$detail->id.'] has been Updated');
        \Session::flash('success', 'Ticket List has been Updated successfully!');
        return redirect('admin/ticket/ticketlist');

    }

    public function destroy($id)
    {
        $detail = TicketList::findOrFail($id);
        
        $detail->delete();

        Activity::log('Ticket List -'.$detail->title.' [Id = '.$detail->id.'] has been deleted');
        \Session::flash('danger', 'Ticket List has been deleted successfully!');
        return redirect('admin/ticket/ticketlist');
    }

    public function update_display_order(Request $request)
    {
        $count1 = $request->count;
        for ($i = 1; $i <= $count1; $i++) {
            $request->pid1;
            if (isset($request["disp" . $i])) {
                $disp1 = $request["disp" . $i];
                $pid1 = $request["pid" . $i];
            }
            $detail = TicketList::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $detail->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/ticket/ticketlist');
    }

    public function assign(Request $request)
    {
        $detail = TicketList::findorFail($request['id']);
        $detail['status']="active";
        $detail->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $detail = TicketList::findorFail($request['id']);
        $detail['status']="inactive";
        $detail->update($request->all());
        return $request['id'];
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $detail = TicketList::findOrFail($idval);
                $input['displayorder'] = $count;
                $detail->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }
}
