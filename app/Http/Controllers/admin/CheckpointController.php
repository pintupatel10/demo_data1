<?php

namespace App\Http\Controllers\admin;

use App\Checkpoint;
use App\Helpers\ImageHelper;
use App\Images;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CheckpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Checkpoint Center');

    }
    public function index()
    {
        $data['mainmenu']="Checkpoint";
        $data['menu']="Checkpoint";
        $data['checkpoint']=Checkpoint::orderBy('id','ASC')->get();
        return view('admin.checkpoint.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Checkpoint";
        $data['image_center'] = Images::get();
        $data['menu']="Checkpoint";
       // $data['detail']=$detailid;
        return view('admin.checkpoint.create',$data);
    }
    public function store(Request $request)
    {
        $image = new Checkpoint($request->all());
        $this->validate($request, [
            'title' => 'required',
            // 'image' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Checkpoint::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/
       // $image['detailid'] = $detailid;
        if(!empty($request->image_name))
        {
            $image['image'] = $request->image_name;
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

                $image['image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }

        $image->save();

        Activity::log('Checkpoint -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        // \Session::flash('success', 'Tour Checkpoint has been inserted successfully!');
        return redirect('admin/checkpoint');
    }

    public function edit($id)
    {
        $data['mainmenu']="Checkpoint";
        $data['image_center'] = Images::get();
        $data['menu']="Checkpoint";
      //  $data['detail'] = $id;
        $data['checkpoint'] = Checkpoint::findOrFail($id);

        return view('admin.checkpoint.edit',$data);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);


        $checkpoint = Checkpoint::findOrFail($id);
        $input = $request->all();
       // $input['detailid'] = $id;
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

        Activity::log('Checkpoint -'.$checkpoint->title.' [Id = '.$checkpoint->id.'] has been Updated');
        // \Session::flash('success', 'Tour Checkpoint has been Updated successfully!');
        return redirect('admin/checkpoint');
    }
    public function destroy($id)
    {
        $data['detail'] = $id;
        $checkpoint = Checkpoint::findOrFail($id);

//        if(file_exists($checkpoint->image)) {
//            unlink($checkpoint->image);
//        }
        $checkpoint->delete();

        Activity::log('Checkpoint -'.$checkpoint->title.'  [Id = '.$checkpoint->id.'] has been deleted');
        //\Session::flash('danger','Tour Checkpoint has been deleted successfully!');
        return redirect('admin/checkpoint');
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
            $checkpoint = Checkpoint::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $checkpoint->update($input);
        }
        Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/checkpoint');
    }

    public function assign(Request $request)
    {
        $checkpoint = Checkpoint::findorFail($request['id']);
        $checkpoint['status']="active";
        $checkpoint->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $checkpoint = Checkpoint::findorFail($request['id']);
        $checkpoint['status']="inactive";
        $checkpoint->update($request->all());
        return $request['id'];
    }
}
