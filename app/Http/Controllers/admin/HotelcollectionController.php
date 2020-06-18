<?php

namespace App\Http\Controllers\admin;

use App\Hotelcollection;
use App\HotelDetail;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HotelcollectionController extends Controller
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
        $data['menu']="Hotel Collection";
       // $data['collection']=Hotelcollection::orderBy('id','ASC')->get();
        $data['collection']=Hotelcollection::orderBy('displayorder','ASC')->Paginate($this->pagination);;
        return view('admin.hotel.collection.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel Collection";
        $data['name']=HotelDetail::all();
        return view('admin.hotel.collection.create',$data);
    }

    public function store(Request $request)
    {
        $hotel = new Hotelcollection($request->all());
        $this->validate($request, [
            'title' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);


        /* ADD DISPLAY ORDER */
        $count_display_order = Hotelcollection::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $hotel->displayorder = $c;
        /*---------------------*/

        if(!empty($hotel->language))
        {
            $hotel->language=implode(',',$hotel->language);
        }

       // $a1= explode(',',$request['itm_sel']);
       // $b1=explode(',',$request['itm_removed']);
        //$d1=array_diff($a1,$b1);
        //$hotel['hotel'] = implode(',',$d1);
        $hotel['hotel']=$request['itm_sel'];
        $hotel->save();

        Activity::log('Hotel Collection  -'.$hotel->title.' [Id = '.$hotel->id.'] has been inserted');
        \Session::flash('success', 'Hotel Collection has been inserted successfully!');
        return redirect('admin/hotel/collection');
    }

    public function edit($id)
    {
        $a=array();
        $b=array();
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel Collection";
        $data['collection'] = Hotelcollection::findOrFail($id);
        $data['name']=HotelDetail::all();
        $data['filter']=$data['collection']->HotelFilter()->orderBy('displayorder','ASC')->Paginate($this->pagination);
        $data['modes_selected'] = explode(",",$data['collection']['language']);


       /* foreach($data['name'] as $key => $val){
            $mode = explode(",",$data['collection']['hotel']);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['name'][$key]);
                }
            }
        }
        $mode = explode(",",$data['collection']['hotel']);
        $list=implode(',',$mode);
        foreach ($mode as $mod){
            $q=HotelDetail::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
        $data['mode']=$a;*/

        foreach($data['name'] as $key => $val){
            $mode = explode(",",$data['collection']['hotel']);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['name'][$key]);
                }
            }
        }
        $mode = explode(",",$data['collection']['hotel']);
       // $list=implode(',',$mode);
        foreach ($mode as $mod){
            $q=HotelDetail::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
        $data['mode']=$a;


        return view('admin.hotel.collection.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $collection = Hotelcollection::findOrFail($id);
        $input = $request->all();

        $input['language'] = implode(',',$request->language);

        //$a1= explode(',',$request['itm_sel']);
//        $b1=explode(',',$request['itm_removed']);
//        $d1=array_diff($a1,$b1);
//        $input['hotel'] = implode(',',$d1);
        $input['hotel']=$request['itm_sel'];

        $collection->update($input);

        Activity::log('Hotel Collection -'.$collection->title.' [Id = '.$collection->id.'] has been Updated');
        \Session::flash('success', 'Hotel Collection has been Updated successfully!');
        return redirect('admin/hotel/collection');
    }
    public function destroy($id)
    {
        $collection = Hotelcollection::findOrFail($id);
        $collection->delete();

        Activity::log('Hotel Collection -'.$collection->title.' [Id = '.$collection->id.'] has been deleted');
        \Session::flash('danger','Hotel Collection has been deleted successfully!');
        return redirect('admin/hotel/collection');
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
            $collection = Hotelcollection::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $collection->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/hotel/collection');
    }

    public function assign(Request $request)
    {
        $collection = Hotelcollection::findorFail($request['id']);
        $collection['status']="active";
        $collection->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $collection = Hotelcollection::findorFail($request['id']);
        $collection['status']="inactive";
        $collection->update($request->all());
        return $request['id'];
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {
                $collection = Hotelcollection::findOrFail($idval);
                $input['displayorder'] = $count;
                $collection->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }
}
