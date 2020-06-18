<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Homepopup;
use App\Images;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomepopupController extends Controller
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
        $data['menu']="Home PopUp";
       // $data['popup']=Homepopup::orderBy('displayorder','DESC')->Paginate($this->pagination);
        $data['popup']=Homepopup::orderBy('id','ASC')->get();
        return view('admin.home.popup.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Home";
        $data['menu']="Home PopUp";
        $data['image_center'] = Images::get();
        return view('admin.home.popup.create',$data);
    }
    public function store(Request $request)
    {
        $image = new Homepopup($request->all());
        $this->validate($request, [
           // 'image' => 'required|mimes:jpeg,jpg,bmp,png',
            'url' => 'required',
            'language' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Homepopup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/

        if(!empty($request->image_name))
        {
            $image->image = $request->image_name;
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
                    $image['image'] = $image_path;

                        $new['image'] = $image_path;
                        $i=Images::create($new);
                    }
                    else{
                        return back()->withInput()->withErrors(['image' => 'please select image']);
                    }
        }

        if (false === strpos($request->url, '://')) {
            $image->url = 'http://' . $request->url;
        }
        else{
            $image->url = $request->url;
        }

        if(!empty($image->language))
        {
            $image->language=implode(',',$image->language);
        }
        $image->status = $request->status;
        $image->save();

        Activity::log('Homepopup  [Id = '.$image->id.'] has been inserted');
        \Session::flash('success', 'Homepopup has been inserted successfully!');
        return redirect('admin/home/popup');
    }

    public function edit($id)
    {
        $data['mainmenu']="Home";
        $data['menu']="Home PopUp";
        $data['popup'] = Homepopup::findOrFail($id);
        $data['image_center'] = Images::get();
        $data['modes_selected'] = explode(",",$data['popup']['language']);
        return view('admin.home.popup.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
           // 'image' => 'mimes:jpeg,jpg,bmp,png',
            'url' => 'required',
            'language' => 'required',
            'status' => 'required',
        ]);


        $popup = Homepopup::findOrFail($id);
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

        if (false === strpos($input['url'], '://')) {
            $input['url'] = 'http://' . $input['url'];
        }

        $input['language'] = implode(',',$request->language);
        $popup->update($input);

        Activity::log('Homepopup [Id = '.$popup->id.'] has been Updated');
        \Session::flash('success', 'Homepopup has been Updated successfully!');
        return redirect('admin/home/popup');
        //$url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);
    }
    public function destroy($id)
    {
        $popup = Homepopup::findOrFail($id);
        $popup->delete();

        Activity::log('Homepopup [Id = '.$popup->id.'] has been deleted');
        \Session::flash('danger','Homepopup has been deleted successfully!');
        return redirect('admin/home/popup');
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
            $popup = Homepopup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $popup->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/home/popup');
    }

    public function assign(Request $request)
    {
        $popup = Homepopup::findorFail($request['id']);
        $popup['status']="active";
        $popup->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $popup = Homepopup::findorFail($request['id']);
        $popup['status']="inactive";
        $popup->update($request->all());
        return $request['id'];
    }
}
