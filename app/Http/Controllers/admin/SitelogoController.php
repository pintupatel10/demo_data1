<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Images;
use App\Sitelogo;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SitelogoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Site Setup');
    }


    public function index()
    {
        $data['mainmenu']="Site Setup";
        $data['menu']="Site Logo";
       // $data['sitelogo']=Sitelogo::orderBy('displayorder','DESC')->Paginate($this->pagination);
        $data['sitelogo']=Sitelogo::orderBy('id','ASC')->get();
        return view('admin.site.sitelogo.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Site Setup";
        $data['menu']="Site Logo";
        $data['image_center'] = Images::get();
        return view('admin.site.sitelogo.create',$data);
    }
    public function store(Request $request)
    {
        $image = new Sitelogo($request->all());

        $this->validate($request, [
            'language' => 'required',
            'name' => 'required',
           // 'path' => 'required|mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Sitelogo::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/

        if(!empty($image->language))
        {
            $image->language=implode(',',$image->language);
        }
        $image->name = $request->name;


        if(!empty($request->image_name))
        {
            $image->path = $request->image_name;
        }
        else {
                if($photo = $request->file('path'))
                {
                    $root = base_path() . '/public/resource/images/' ;
                    $name = str_random(20).".".$photo->getClientOriginalExtension();
                    if (!file_exists($root)) {
                        mkdir($root, 0777, true);
                    }
                    $image_path = "resource/images/".$name;
                    $photo->move($root,$name);
                    $image['path'] = $image_path;
                    ImageHelper::generateThumbnail($image_path);

                    $new['image'] = $image_path;
                    $i=Images::create($new);
                }
                else{
                    return back()->withInput()->withErrors(['image' => 'please select image']);
                }
        }
        $image['user_id'] = Auth::user()->id;
        $image->status = $request->status;
        $image->save();

        Activity::log('Site Logo -'.$image->name.'  [Id = '.$image->id.'] has been inserted');
        \Session::flash('success', 'Site Logo has been inserted successfully!');
        return redirect('admin/site/sitelogo');
    }

    public function edit($id)
    {
        $data['mainmenu']="Site Setup";
        $data['menu']="Site Logo";
        $data['sitelogo'] = Sitelogo::findOrFail($id);
        $data['image_center'] = Images::get();
        $data['modes_selected'] = explode(",",$data['sitelogo']['language']);
        return view('admin/site/sitelogo/edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'language' => 'required',
            'name' => 'required',
            //'path' => 'mimes:jpeg,jpg,bmp,png',
            'status' => 'required',
        ]);


        $sitelogo = Sitelogo::findOrFail($id);
        $input = $request->all();

        if(!empty($request->image_name))
        {
            $input['path'] = $request->image_name;
        }
        else {
            if ($photo = $request->file('path')) {
                $root = base_path() . '/public/resource/Sitelogo/';
                $name = str_random(20) . "." . $photo->getClientOriginalExtension();
                $mimetype = $photo->getMimeType();
                $explode = explode("/", $mimetype);
                $type = $explode[0];
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $photo->move($root, $name);
                $image_path = "resource/Sitelogo/" . $name;
                ImageHelper::generateThumbnail($image_path);
                $input['path'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }
        $input['language'] = implode(',',$request->language);
        $sitelogo->update($input);

        Activity::log('Site Logo -'.$sitelogo->name.' [Id = '.$sitelogo->id.'] has been Updated');
        \Session::flash('success', 'Site Logo has been Updated successfully!');
        return redirect('admin/site/sitelogo');
        //$url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);

    }
    public function destroy($id)
    {
        $sitelogo = Sitelogo::findOrFail($id);
        $sitelogo->delete();

        Activity::log('Site Logo -'.$sitelogo->name.' [Id = '.$sitelogo->id.'] has been deleted');
        \Session::flash('danger','Site Logo has been deleted successfully!');
        return redirect('admin/site/sitelogo');
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
            $sitelogo = Sitelogo::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $sitelogo->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/site/sitelogo');
    }

    public function assign(Request $request)
    {
        $sitelogo = Sitelogo::findorFail($request['id']);
        $sitelogo['status']="active";
        $sitelogo->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $sitelogo = Sitelogo::findorFail($request['id']);
        $sitelogo['status']="inactive";
        $sitelogo->update($request->all());
        return $request['id'];
    }
}
