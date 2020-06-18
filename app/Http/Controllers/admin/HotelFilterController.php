<?php

namespace App\Http\Controllers\admin;

use App\Hotelcollection;
use App\HotelDetail;
use App\HotelFilter;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HotelFilterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Hotel');

    }

    public function create($cid)
    {

        $data=[];
        $a=array();
        $b=array();
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel Filter";
        $data['detail']=$cid;

         $lists = Hotelcollection::where('id', '=', $cid)->where('deleted_at',null)->first();
        $mode = explode(",",$lists['hotel']);
         foreach ($mode as $mod){
             $q=HotelDetail::where('id',$mod)->get()->toArray();
             $a=array_merge($a,$q);
         }
         $data['mode']=$a;
         return view('admin.hotel.filter.create',$data);
     }
     public function store(Request $request,$cid)
     {
          $input=$request->all();
           $this->validate($request, [
             'name' => 'required',
             'status' => 'required'
         ]);

         /* ADD DISPLAY ORDER */
        $count_display_order = HotelFilter::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
         $input['displayorder'] = $c;
        /*---------------------*/
         $input['cid'] = $cid;

        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $input['hotel_list'] = $itm[1];
        }
         $hotel=HotelFilter::create($input);

         Activity::log('HotelFilter  -'.$hotel->name.' [Id = '.$hotel->id.'] has been inserted');
        return redirect('admin/hotel/collection/'.$cid.'/edit');
    }

    public function edit($cid,$id)
    {
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel Filter";
        $a=array();
        $b=array();

        $data['detail'] = $cid;
         $data['filter'] = HotelFilter::findOrFail($id);

         $lists = Hotelcollection::where('id', '=', $cid)->where('deleted_at',null)->first();

         $mode = explode(",",$lists['hotel']);
        foreach ($mode as $mod){
            $q=HotelDetail::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
          $data['mode3']=$a;

       foreach($data['mode3'] as $key => $val){
           $mode = explode(",",$data['filter']['hotel_list']);
           foreach ($mode as $mod){
               if($mod == $val['id']){
                   unset($data['mode3'][$key]);
               }
           }
       }
        $mode33 = explode(",",$data['filter']['hotel_list']);
        foreach ($mode33 as $mod){
            $q=HotelDetail::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$q);
        }
        $data['mode50']=$b;
        return view('admin.hotel.filter.edit',$data);
    }

    public function update(Request $request, $id,$cid)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);


        $filter = HotelFilter::findOrFail($cid);
        $input = $request->all();
        $input['cid'] = $id;
        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $input['hotel_list'] = $itm[1];
        }
        else{
            $input['hotel_list']="";
        }
        $filter->update($input);

        Activity::log('HotelFilter  -'.$filter->name.' [Id = '.$filter->id.'] has been Updated');
        return redirect('admin/hotel/collection/'.$id.'/edit');
    }
    public function destroy($id,$cid)
    {
        $data['detail'] = $id;
        $filter = HotelFilter::findOrFail($cid);
        $filter->delete();

        Activity::log('HotelFilter -'.$filter->name.' [Id = '.$filter->id.'] has been deleted');
        return redirect('admin/hotel/collection/'.$id.'/edit');
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
            $filter = HotelFilter::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $filter->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/hotel/filter');
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $filter = HotelFilter::findOrFail($idval);
                $input['displayorder'] = $count;
                $filter->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }

    public function assign(Request $request)
    {
        $filter = HotelFilter::findorFail($request['id']);
        $filter['status']="active";
        $filter->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $filter = HotelFilter::findorFail($request['id']);
        $filter['status']="inactive";
        $filter->update($request->all());
        return $request['id'];
    }
}
