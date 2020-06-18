<?php

namespace App\Http\Controllers\admin;

use App\Checkpoint;
use App\Helpers\ImageHelper;
use App\Images;
use App\TransportationCheckpoint;
use App\Transportationhighlight;
use App\TransportationList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportationListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transportation');

    }

    public function index()
    {
        $data['mainmenu'] = "Transportation";
        $data['menu'] = "Transportation List";
        $data['detail'] = TransportationList::where('post','=','Public')->get();
        return view('admin.transportation.transportationlist.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['image_center'] = Images::get();
        $data['mainmenu'] = "Transportation";
        $data['menu'] = "Transportation List";
        return view('admin.transportation.transportationlist.create', $data);
    }

    public function store(Request $request)
    {
        $detail = new TransportationList($request->all());

        $this->validate($request, [
            'title' => 'required',
            'transportation_type' => 'required',
            'transportation_code' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
        ]);

        $count_display_order = TransportationList::count();
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

                $detail['image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }

        if (!empty($request['language'])) {
            $detail['language'] = implode(',', $request['language']);
        }
        
        $detail['post'] = 'Public';

        $detail->save();

        Activity::log('Transportation List -'.$detail->title.' [Id = '.$detail->id.'] has been inserted');
        \Session::flash('success', 'Transportation List has been inserted successfully!');
        return redirect('admin/transportation/transportationlist');
    }

    public function edit($id)
    {
        $data['mainmenu'] = "Transportation";
        $data['menu'] = "Transportation List";
        $data['image_center'] = Images::get();
        $data['detail'] = TransportationList::findOrFail($id);
        //$data['checkpoint']=$data['detail']->TransportationCheckpoint()->get();

        $data['checkpoint']=TransportationCheckpoint::where('detailid',$id)->get();
        $data['checkpoint_center']=Checkpoint::where('status','active')->get();

        $data['pricegroup']=$data['detail']->TransportationPricegroup()->get();
        $data['price']=$data['detail']->TransportationPrice()->get();
        $data['time']=$data['detail']->TransportationTimetable()->get();
        $data['modes_selected'] = explode(",",$data['detail']['language']);
        return view('admin.transportation.transportationlist.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'transportation_type' => 'required',
            'transportation_code' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
        ]);
        $detail = TransportationList::findOrFail($id);
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

        $input['language'] = implode(',', $request->language);

        $count1 = $input['theValue1213'];

        if (isset($input['remove_id1']) && $input['remove_id1'] != "") {
            $remove_image = explode(",", $input['remove_id1']);
            foreach ($remove_image as $val) {
                $address = Transportationhighlight::findOrFail($val);
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
                $address = Transportationhighlight::findOrFail($update_id1[$j]);
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
                Transportationhighlight::create($addr);
            }
        }
        $detail->update($input);

        Activity::log('Transportation List -'.$detail->title.' [Id = '.$detail->id.'] has been Updated');
        \Session::flash('success', 'Transportation List has been Updated successfully!');
        return redirect('admin/transportation/transportationlist');
    }

    public function destroy($id)
    {
        $detail = TransportationList::findOrFail($id);
        $detail->delete();

        Activity::log('Transportation List -'.$detail->title.' [Id = '.$detail->id.'] has been deleted');
        \Session::flash('danger', 'Transportation List has been deleted successfully!');
        return redirect('admin/transportation/transportationlist');
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
            $detail = TransportationList::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $detail->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/transportation/transportationlist');
    }

    public function assign(Request $request)
    {
        $detail = TransportationList::findorFail($request['id']);
        $detail['status']="active";
        $detail->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $detail = TransportationList::findorFail($request['id']);
        $detail['status']="inactive";
        $detail->update($request->all());
        return $request['id'];
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $detail = TransportationList::findOrFail($idval);
                $input['displayorder'] = $count;
                $detail->update($input);
                $count++;
            }
            echo 'Display order change successfully.';
        }
    }
}
