<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Homelayout;
use App\Images;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomelayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Home');
    }


    public function index()
    {
        $data['mainmenu']="Home";
        $data['menu']="Home Layout";
        $data['homelayout']=Homelayout::all();
        return view('admin.home.layout.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Home";
        $data['menu']="Home Layout";
        $data['image_center'] = Images::get();
        return view('admin.home.layout.create',$data);
    }
    public function store(Request $request)
    {

        $image = new Homelayout($request->all());
        $this->validate($request, [
            'menu_name' => 'required',
            'title' => 'required',
        //    'image' => 'required|mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Homelayout::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/

        $image->title = $request->title;

        if(!empty($request->image_name))
        {
            $image->image = $request->image_name;
        }
        else {

            if ($photo = $request->file('image')) {
                $photo1 = $request->file('image');
                $root = base_path() . '/public/resource/images/';
                $name = str_random(20) . "." . $photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/" . $name;
                ImageHelper::generateThumbnail($image_path);
                $photo->move($root, $name);
                $image['image'] = $image_path;


                $new['image'] = $image_path;
                $i=Images::create($new);

            }
            else{
                return back()->withInput()->withErrors(['image' => 'please select image']);
            }
        }

        if(!empty($image->language))
        {
            $image->language=implode(',',$image->language);
        }
        $image->status = $request->status;
        $image->save();

        Activity::log('Homelayout -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        \Session::flash('success', 'Homelayout has been inserted successfully!');
        return redirect('admin/home/layout');
    }

    public function edit($id)
    {
        $data['mainmenu']="Home";
        $data['menu']="Home Layout";
        $data['homelayout'] = Homelayout::findOrFail($id);
        $data['image_center'] = Images::get();
        $data['modes_selected'] = explode(",",$data['homelayout']['language']);
        return view('admin.home.layout.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'menu_name' => 'required',
            'title' => 'required',
            //'image' => 'mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);


        $homelayout = Homelayout::findOrFail($id);
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
        $input['language'] = implode(',',$request->language);
        $homelayout->update($input);

        Activity::log('Homelayout -'.$homelayout->title.' [Id = '.$homelayout->id.'] has been Updated');
        \Session::flash('success', 'Homelayout has been Updated successfully!');

        return redirect('admin/home/layout');
       // $url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);

    }
    public function destroy($id)
    {
        $homelayout = Homelayout::findOrFail($id);
        $homelayout->delete();
        Activity::log('Homelayout -'.$homelayout->title.' [Id = '.$homelayout->id.'] has been deleted');
        \Session::flash('danger','Homelayout has been deleted successfully!');
        return redirect('admin/home/layout');
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
            $homelayout = Homelayout::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $homelayout->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/home/layout');
    }

    public function assign(Request $request)
    {
        $homelayout = Homelayout::findorFail($request['id']);
        $homelayout['status']="active";
        $homelayout->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $homelayout = Homelayout::findorFail($request['id']);
        $homelayout['status']="inactive";
        $homelayout->update($request->all());
        return $request['id'];
    }
}
