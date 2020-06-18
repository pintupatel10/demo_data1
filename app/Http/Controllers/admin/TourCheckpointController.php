<?php

namespace App\Http\Controllers\admin;

use App\Checkpoint;
use App\Helpers\ImageHelper;
use App\Images;
use App\Tourcheckpoint;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TourCheckpointController extends Controller
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
        $data['menu']="Tour checkpoint";
        $data['checkpoint']=Tourcheckpoint::orderBy('id','ASC')->get();
        return view('admin.tour.checkpoint.index',$data);
    }

    public function create($detailid)
    {
        $data=[];
        $data['mainmenu']="Tour";
        $data['image_center'] = Images::get();
        $data['menu']="Tour checkpoint";
        $data['detail']=$detailid;
        return view('admin.tour.checkpoint.create',$data);
    }
    public function store(Request $request,$detailid)
    {
       // $input=$request->all();
        $display_order = Tourcheckpoint::count();
        if ($display_order >= 0) {
            $cd = stripslashes($display_order);
            $cd = $cd + 1;
        }
        $input1['displayorder'] = $cd;

        if(!empty($request['checkpoint_id'])){
            $get_new=Checkpoint::where('id',$request['checkpoint_id'])->first();
        }
        else {
            $this->validate($request, [
                'title' => 'required',
                // 'image' => 'required',
                'status' => 'required',
            ]);

            /* ADD DISPLAY ORDER */
            $count_display_order = Checkpoint::count();
            if ($count_display_order >= 0) {
                $c = stripslashes($count_display_order);
                $c = $c + 1;
            }
            $input['displayorder'] = $c;
            $input['status']=$request['status'];
            $input['title']=$request['title'];
            $input['description']=$request['description'];

            /*---------------------*/
            // $input['detailid'] = $detailid;
            if (!empty($request->image_name)) {
                $input['image'] = $request->image_name;
            } else {
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
                    $i = Images::create($new);
                }
            }
            $checkpoint = Checkpoint::create($input);
            $get_new=Checkpoint::where('id',$checkpoint->id)->first();
        }
        $input1['detailid'] = $detailid;
        //$input1['checkpoint_id'] = $get_new->id;
        $input1['status'] = $get_new->status;
        $input1['image']=$get_new->image;
        $input1['title']=$get_new->title;
        $input1['description']=$get_new->description;

        $tichtkpoint=Tourcheckpoint::create($input1);

        Activity::log('TourCheckpoint -'.$tichtkpoint->title.' [Id = '.$tichtkpoint->id.'] has been inserted');
        return redirect('admin/tour/tourlist/'.$detailid.'/edit');
    }

    public function edit($id,$detailid)
    {
        $data['mainmenu']="Tour";
        $data['image_center'] = Images::get();
        $data['menu']="Tour checkpoint";
        $data['detail'] = $id;
        $data['checkpoint'] = Tourcheckpoint::findOrFail($detailid);

        return view('admin.tour.checkpoint.edit',$data);
    }

    public function update(Request $request, $id,$detailid)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);


        $checkpoint = Tourcheckpoint::findOrFail($detailid);
        $input = $request->all();
        $input['detailid'] = $id;
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

        $checkpoint->update($input);

        Activity::log('TourCheckpoint -'.$checkpoint->title.' [Id = '.$checkpoint->id.'] has been Updated');
        return redirect('admin/tour/tourlist/'.$id.'/edit');
    }
    public function destroy($id,$detailid)
    {
        $data['detail'] = $id;
        $checkpoint = Tourcheckpoint::findOrFail($detailid);
        $checkpoint->delete();

        Activity::log('TourCheckpoint  -'.$checkpoint->title.' [Id = '.$checkpoint->id.'] has been deleted');
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
            $checkpoint = Tourcheckpoint::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $checkpoint->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/tour/checkpoint');
    }

    public function assign(Request $request)
    {
        $checkpoint = Tourcheckpoint::findorFail($request['id']);
        $checkpoint['status']="active";
        $checkpoint->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $checkpoint = Tourcheckpoint::findorFail($request['id']);
        $checkpoint['status']="inactive";
        $checkpoint->update($request->all());
        return $request['id'];
    }
}
