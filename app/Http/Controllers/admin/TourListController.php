<?php

namespace App\Http\Controllers\admin;

use App\Checkpoint;
use App\Helpers\ImageHelper;
use App\Images;
use App\Tourcheckpoint;
use App\TourDetailsimplified;
use App\Tourhighlight;
use App\TourInventory;
use App\TourList;
use App\TourPrice;
use App\TourPricegroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TourListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Tour');

    }

    public function index()
    {
        $data['mainmenu'] = "Tour";
        $data['menu'] = "Tour List";
       //$data['detail'] = TourList::OrderBy('displayorder','ASC')->Paginate($this->pagination);
        $data['detail'] = TourList::where('post','=','Public')->get();
        return view('admin.tour.tourlist.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['mainmenu'] = "Tour";
        $data['image_center'] = Images::get();
        $data['menu'] = "Tour List";
        return view('admin.tour.tourlist.create', $data);
    }

    public function store(Request $request)
    {
        $detail = new TourList($request->all());

        $this->validate($request, [
            'title' => 'required',
            'tour_type' => 'required',
            'tour_code' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
            //'image' => 'required|mimes:jpeg,jpg,bmp,png',
        ]);

        $count_display_order = TourList::count();
        if ($count_display_order >= 0) {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $detail['displayorder'] = $c;
        /*---------------------*/

//        if($photo = $request->file('image'))
//        {
//            $root = base_path() . '/public/resource/tourdetail_fullscreen/' ;
//            $name = str_random(20).".".$photo->getClientOriginalExtension();
//            if (!file_exists($root)) {
//                mkdir($root, 0777, true);
//            }
//            $image_path = "resource/tourdetail_fullscreen/".$name;
//            $photo->move($root,$name);
//            $detail['image'] = $image_path;
//            ImageHelper::generateThumbnail($image_path);
//        }
        if(!empty($request->image_name))
        {
            $detail['image'] = $request->image_name;
        }
        else {
            if($photo = $request->file('image'))
            {
                $root = base_path() . '/public/resource/images/';
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
            //ImageHelper::generateThumbnail($image_path);

        if (!empty($request['tour_type'])) {
            $detail['tour_type'] = implode(',', $request['tour_type']);
        }
        if (!empty($request['language'])) {
            $detail['language'] = implode(',', $request['language']);
        }
        $detail['post'] = 'Public';
        
        $detail->save();

        Activity::log('Tour List -'.$detail->title.' [Id = '.$detail->id.'] has been inserted');
        \Session::flash('success', 'Tour List has been inserted successfully!');
        return redirect('admin/tour/tourlist');
    }

    public function edit($id)
    {
        $data['mainmenu'] = "Tour";
        $data['menu'] = "Tour List";
        $data['image_center'] = Images::get();
        $data['detail'] = TourList::findOrFail($id);
        $data['modes'] = explode(",",$data['detail']['tour_type']);
       //  $data['checkpoint']=$data['detail']->Tourcheckpoint()->get();
         $data['checkpoint']=Tourcheckpoint::where('detailid',$id)->get();
          $data['checkpoint_center']=Checkpoint::where('status','active')->get();
        $data['detail']->Tourcheckpoint()->get();
        $data['pricegroup']=$data['detail']->TourPricegroup()->get();
        $data['tourprice']=$data['detail']->TourPrice()->get();
        $data['modes_selected'] = explode(",",$data['detail']['language']);
        return view('admin.tour.tourlist.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'tour_type' => 'required',
            'tour_code' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
           // 'image' => 'mimes:jpeg,jpg,bmp,png',
        ]);
        $detail = TourList::findOrFail($id);

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
        $input['tour_type'] = implode(',',$request->tour_type);
        $input['language'] = implode(',',$request->language);


        $count1 = $input['theValue1213'];

        if (isset($input['remove_id1']) && $input['remove_id1'] != "") {
            $remove_image = explode(",", $input['remove_id1']);
            foreach ($remove_image as $val) {
                $address = Tourhighlight::findOrFail($val);
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
                $address = Tourhighlight::findOrFail($update_id1[$j]);
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
                Tourhighlight::create($addr);
            }
        }
        $detail->update($input);

        Activity::log('Tour List -'.$detail->title.' [Id = '.$detail->id.'] has been Updated');
        \Session::flash('success', 'Tour List has been Updated successfully!');
        return redirect('admin/tour/tourlist');
        //$url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);
    }

    public function destroy($id)
    {
        $detail = TourList::with('Tourcheckpoint')->findOrFail($id);

        $detail->delete();

        Activity::log('Tour List -'.$detail->title.' [Id = '.$detail->id.'] has been deleted');
        \Session::flash('danger', 'Tour List has been deleted successfully!');
        return redirect('admin/tour/tourlist');
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
            $detail = TourList::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $detail->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/tour/tourlist');
    }

    public function assign(Request $request)
    {
        $detail = TourList::findorFail($request['id']);
        $detail['status']="active";
        $detail->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $detail = TourList::findorFail($request['id']);
        $detail['status']="inactive";
        $detail->update($request->all());
        return $request['id'];
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $detail = TourList::findOrFail($idval);
                $input['displayorder'] = $count;
                $detail->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }
}
