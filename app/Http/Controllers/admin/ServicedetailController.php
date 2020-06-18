<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Images;
use App\Servicedetail;
use App\ServiceLayout;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ServicedetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Service');
    }
    public function index()
    {
        $data['mainmenu']="Services";
        $data['menu']="Services";
        $data['menu_name']=ServiceLayout::get();
        $data['services']=Servicedetail::orderBy('displayorder','ASC')->Paginate($this->pagination);
        return view('admin.services.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Services";
        $data['menu']="Services";
        $data['image_center'] = Images::get();
        return view('admin.services.create',$data);
    }
    public function store(Request $request)
    {
        $image = new Servicedetail($request->all());
        $this->validate($request, [
            'title' => 'required',
            //'image' => 'mimes:jpeg,jpg,bmp,png',
            'url' => 'required',
            'language' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Servicedetail::count();
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

        Activity::log('Services -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        \Session::flash('success', 'Services has been inserted successfully!');
        return redirect('admin/services');
    }

    public function edit($id)
    {
        $data['mainmenu']="Services";
        $data['menu']="Services";
        $data['services'] = Servicedetail::findOrFail($id);
        $data['image_center'] = Images::get();
        $data['modes_selected'] = explode(",",$data['services']['language']);
        return view('admin.services.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
           // 'image' => 'mimes:jpeg,jpg,bmp,png',
            'url' => 'required',
            'language' => 'required',
            'status' => 'required',
        ]);
        
        $services = Servicedetail::findOrFail($id);
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
        $services->update($input);

        Activity::log('Services -'.$services->title.' [Id = '.$services->id.'] has been Updated');
        \Session::flash('success', 'Services has been Updated successfully!');
        return redirect('admin/services');
    }
    public function destroy($id)
    {
        $services = Servicedetail::findOrFail($id);
        $services->delete();

        Activity::log('Services -'.$services->title.'  [Id = '.$services->id.'] has been deleted');
        \Session::flash('danger','Services has been deleted successfully!');
        return redirect('admin/services');
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
            $services = Servicedetail::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $services->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/services');
    }

    public function assign(Request $request)
    {
        $services = Servicedetail::findorFail($request['id']);
        $services['status']="active";
        $services->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $services = Servicedetail::findorFail($request['id']);
        $services['status']="inactive";
        $services->update($request->all());
        return $request['id'];
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $services = Servicedetail::findOrFail($idval);
                $input['displayorder'] = $count;
                $services->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }
    public function service_menu(Request $request)
    {
        $this->validate($request,[
            'menu_english' => 'required',
            'menu_traditional' => 'required',
            'menu_simple' => 'required',
        ]);

        $input1['menu_name']=$request['menu_english'];
        $input1['language']="English";
        $service=ServiceLayout::create($input1);

        $input2['menu_name']=$request['menu_traditional'];
        $input2['language']="繁中";
        $service=ServiceLayout::create($input2);

        $input3['menu_name']=$request['menu_simple'];
        $input3['language']="簡";
        $service=ServiceLayout::create($input3);
        \Session::flash('success', 'Service Menu has been inserted successfully!');
        return redirect('admin/services');
        \Session::flash('success', 'Service Menu has been inserted successfully!');
        return redirect('admin/services');

      
    }

    public function service_menu_update(Request $request)
    {
        $this->validate($request,[
             'menu_english' => 'required',
             'menu_traditional' => 'required',
             'menu_simple' => 'required',
            ]);

        $service1=ServiceLayout::findOrFail(1);
        $input1['menu_name']=$request['menu_english'];
        $input1['language']="English";
        $service1->update($input1);

        $service2=ServiceLayout::findOrFail(2);
        $input2['menu_name']=$request['menu_traditional'];
        $input2['language']="繁中";
        $service2->update($input2);

        $service3=ServiceLayout::findOrFail(3);
        $input3['menu_name']=$request['menu_simple'];
        $input3['language']="簡";
        $service3->update($input3);
        \Session::flash('success', 'Service Menu has been Updated successfully!');
        return redirect('admin/services');

    }
}
