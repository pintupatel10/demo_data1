<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Images;
use App\TransportationGroup;
use App\TransportationList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportationGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transportation');

    }
    public function index()
    {
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation Group";
        $data['groupdetail']=TransportationGroup::orderBy('id','ASC')->get();
        return view('admin.transportation.transportationgroup.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['image_center'] = Images::get();

        $data['mainmenu']="Transportation";
        $data['menu']="Transportation Group";
        $data['name']=TransportationList::where('post','=','Public')->get();

        return view('admin.transportation.transportationgroup.create',$data);
    }
    public function store(Request $request)
    {
        $transportation = new TransportationGroup($request->all());
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
        $count_display_order = TransportationGroup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $transportation->displayorder = $c;
        /*---------------------*/

        if(!empty($request->portrait_image_name))
        {
            $transportation->portrait_image = $request->portrait_image_name;
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
                $transportation['portrait_image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['portrait_image' => 'please select image']);
            }
        }

        if(!empty($request->landscape_image_name))
        {
            $transportation->landscape_image = $request->landscape_image_name;
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
                $transportation['landscape_image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['landscape_image' => 'please select image']);
            }
        }

        if(!empty($transportation->language))
        {
            $transportation->language=implode(',',$transportation->language);
        }
        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $transportation['transportation_list'] = $itm[1];
        }

       // $transportation['transportation_list']= $request['itm_sel'];
        $transportation->save();
        Activity::log('Transportation Group -'.$transportation->title.' [Id = '.$transportation->id.'] has been inserted');
        \Session::flash('success', 'Transportation Group has been inserted successfully!');
        return redirect('admin/transportation/transportationgroup');
    }

    public function edit($id)
    {
        $data['image_center'] = Images::get();
        $b=array();
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation Group";
        $data['groupdetail'] = TransportationGroup::findOrFail($id);

        $lists = TransportationList::where('post','=','Public')->get();
        foreach($lists as $key => $val){
            $mode = explode(",",$data['groupdetail']['transportation_list']);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($lists[$key]);
                }
            }
        }
        $data['mode3']=$lists;
        $mode33 = explode(",",$data['groupdetail']['transportation_list']);
        foreach ($mode33 as $mod){
            $q=TransportationList::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$q);
        }
        $data['mode50']=$b;
        return view('admin.transportation.transportationgroup.edit',$data);
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
        $groupdetail = TransportationGroup::findOrFail($id);
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
            $input['transportation_list'] = $itm[1];
        }
        else{
            $input['transportation_list']="";
        }
       // $input['transportation_list']=$request['itm_sel'];
        $groupdetail->update($input);

        Activity::log('Transportation Group -'.$groupdetail->title.' [Id = '.$groupdetail->id.'] has been Updated');
        \Session::flash('success', 'Transportation Group has been Updated successfully!');
        return redirect('admin/transportation/transportationgroup');
    }
    public function destroy($id)
    {
        $groupdetail = TransportationGroup::findOrFail($id);

        $groupdetail->delete();

        Activity::log('Transportation Group -'.$groupdetail->title.' [Id = '.$groupdetail->id.'] has been deleted');
        \Session::flash('danger','Transportation Group has been deleted successfully!');
        return redirect('admin/transportation/transportationgroup');
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
            $groupdetail = TransportationGroup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $groupdetail->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/transportation/transportationgroup');
    }

    public function assign(Request $request)
    {
        $groupdetail = TransportationGroup::findorFail($request['id']);
        $groupdetail['status']="active";
        $groupdetail->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $groupdetail = TransportationGroup::findorFail($request['id']);
        $groupdetail['status']="inactive";
        $groupdetail->update($request->all());
        return $request['id'];
    }
}
