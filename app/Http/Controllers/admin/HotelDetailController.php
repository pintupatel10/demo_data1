<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\HotelDetail;
use App\Http\Controllers\Controller;
use App\Images;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;

class HotelDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Hotel');

    }

    public function index()
    {
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel List";
        //$data['hoteldetail']=HotelDetail::orderBy('displayorder','DESC')->Paginate($this->pagination);
        $data['hoteldetail']=HotelDetail::orderBy('id','ASC')->get();
        return view('admin.hotel.hoteldetail.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Hotel";
        $data['image_center'] = Images::get();
        $data['menu']="Hotel List";
        return view('admin.hotel.hoteldetail.create',$data);
    }
    public function store(Request $request)
    {
        $hotel = new HotelDetail($request->all());
        $this->validate($request, [
            'title' => 'required',
            'language' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
            'description' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = HotelDetail::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $hotel->displayorder = $c;
        /*---------------------*/

        $hotel->title = $request->title;

        if(!empty($request->image_name))
        {
            $hotel['image'] = $request->image_name;
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
                $hotel['image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['image' => 'please select image']);
            }
        }
        $hotel->description = $request->description;
        $hotel->status = $request->status;
        $hotel->save();


        Activity::log('Hotel List -'.$hotel->title.' [Id = '.$hotel->id.'] has been inserted');
        \Session::flash('success', 'Hotel List has been inserted successfully!');
        return redirect('admin/hotel/hoteldetail');
    }

    public function edit($id)
    {
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel List";
        $data['image_center'] = Images::get();
        $data['hoteldetail'] = HotelDetail::findOrFail($id);
     //   $data['modes_selected'] = explode(",",$data['hoteldetail']['language']);
        return view('admin.hotel.hoteldetail.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'language' => 'required',
            'image' => 'mimes:jpeg,jpg,bmp,png',
            'description' => 'required',
            'status' => 'required',
        ]);


        $hoteldetail = HotelDetail::findOrFail($id);
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
       // $input['language'] = implode(',',$request->language);
        $hoteldetail->update($input);

        Activity::log('Hotel List  -'.$hoteldetail->title.' [Id = '.$hoteldetail->id.'] has been Updated');
        \Session::flash('success', 'Hotel List has been Updated successfully!');
        return redirect('admin/hotel/hoteldetail');


    }
    public function destroy($id)
    {
        $hoteldetail = HotelDetail::findOrFail($id);
      
        $hoteldetail->delete();

        Activity::log('Hotel List  -'.$hoteldetail->title.' [Id = '.$hoteldetail->id.'] has been deleted');
        \Session::flash('danger','Hotel List has been deleted successfully!');
        return redirect('admin/hotel/hoteldetail');
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
            $hoteldetail = HotelDetail::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $hoteldetail->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/hotel/hoteldetail');
    }

    public function assign(Request $request)
    {
        $hoteldetail = HotelDetail::findorFail($request['id']);
        $hoteldetail['status']="active";
        $hoteldetail->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $hoteldetail = HotelDetail::findorFail($request['id']);
        $hoteldetail['status']="inactive";
        $hoteldetail->update($request->all());
        return $request['id'];
    }

}
