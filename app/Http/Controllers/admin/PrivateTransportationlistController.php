<?php

namespace App\Http\Controllers\admin;

use App\Checkpoint;
use App\Helpers\ImageHelper;
use App\Images;
use App\TransportationCheckpoint;
use App\TransportationList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PrivateTransportationlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Private Transportation');
    }

    public function index()
    {
        $data['mainmenu'] = "Private Transportation";
        $data['menu'] = "Private Transportation List";
        $data['detail'] = TransportationList::where('post','=','Private')->get();
        return view('admin.privatetransportation.privatetransportationlist.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['image_center'] = Images::get();
        $data['mainmenu'] = "Private Transportation";
        $data['menu'] = "Private Transportation List";
        $data['name'] = Auth::user()->name;
        return view('admin.privatetransportation.privatetransportationlist.create', $data);
    }

    public function store(Request $request)
    {
        $detail = new TransportationList($request->all());

        $this->validate($request, [
            'link' => 'required',
            'title' => 'required',
            'transportation_code' => 'required',
            'description' => 'required',
            'status' => 'required',
            'payment_status' => 'required',
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
        $detail['name'] = Auth::user()->name;
        if (!empty($request['language'])) {
            $detail['language'] = implode(',', $request['language']);
        }
        $detail['post'] = 'Private';
        $detail->save();

        Activity::log('Private Transportation List  -'.$detail->title.' [Id = '.$detail->id.'] has been inserted');
        \Session::flash('success', 'Private Transportation List has been inserted successfully!');
        return redirect('admin/privatetransportation/privatetransportationlist');
    }

    public function edit($id)
    {
        $data['mainmenu'] = "Private Transportation";
        $data['image_center'] = Images::get();
        $data['menu'] = "Private Transportation List";
        $data['detail'] = TransportationList::findOrFail($id);
        $data['name']=$data['detail']->name;
        $data['checkpoint']=TransportationCheckpoint::where('detailid',$id)->get();
        $data['checkpoint_center']=Checkpoint::where('status','active')->get();
        $data['pricegroup']=$data['detail']->TransportationPricegroup()->get();
        $data['transportationprice']=$data['detail']->TransportationPrice()->get();
        $data['modes_selected'] = explode(",",$data['detail']['language']);
        return view('admin.privatetransportation.privatetransportationlist.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'link' => 'required',
            'title' => 'required',
            'transportation_code' => 'required',
            'description' => 'required',
            'status' => 'required',
            'payment_status' => 'required',
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

        $detail['name'] = Auth::user()->name;
        $input['language'] = implode(',',$request->language);
        $detail->update($input);


        Activity::log('Private Transportation List -'.$detail->title.' [Id = '.$detail->id.'] has been Updated');

        \Session::flash('success', 'Private Transportation List has been Updated successfully!');

        return redirect('admin/privatetransportation/privatetransportationlist');
    }

    public function destroy($id)
    {
        $detail = TransportationList::findOrFail($id);
        $detail->delete();

        Activity::log('Private Transportation List -'.$detail->title.' [Id = '.$detail->id.'] has been deleted');
        \Session::flash('danger', 'Private Transportation List has been deleted successfully!');
        return redirect('admin/privatetransportation/privatetransportationlist');
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
        return redirect('admin/privatetransportation/privatetransportationlist');
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
}
