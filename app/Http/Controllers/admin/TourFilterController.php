<?php

namespace App\Http\Controllers\admin;

use App\Tourcollection;
use App\TourFilter;
use App\TourGroup;
use App\TourList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TourFilterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Tour');

    }

    public function create($cid)
    {
        $data=[];
        $data['detail']=$cid;
        $a=array();
        $b=array();
        $data['mainmenu']="Tour";
        $data['menu']="Tour Filter";
        $data['collection'] = Tourcollection::findOrFail($cid);
        $data['name']=TourList::all();
        $data['name1'] = TourGroup::all();
        $data['filter']=$data['collection']->TourFilter()->get();
        $data['modes_selected'] = explode(",",$data['collection']['language']);


        /* ==== TOUR GROUP ===*/
        $mode1 = explode(",",$data['collection']['group_list']);
        //$list=implode(',',$mode1);
        foreach ($mode1 as $mod){
            $p=TourGroup::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$p);
        }

        $data['mode1'] = $b;

        /* ==== TOUR LIST === */
        $mode = explode(",",$data['collection']['tour_list']);
        //$list=implode(',',$mode);
        foreach ($mode as $mod){
            $q=TourList::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
        $data['mode']=$a;

        return view('admin.tour.filter.create',$data);
    }
    public function store(Request $request,$cid)
    {
        $filter = new TourFilter($request->all());
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TourFilter::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $filter->displayorder = $c;
        /*---------------------*/
        $filter['cid'] = $cid;

       // $filter['group_list']  = $request['grp_sel'];
        $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $filter['tour_list'] = $itm[1];
        }
        $filter->save();

        Activity::log('TourFilter -'.$filter->name.' [Id = '.$filter->id.'] has been inserted');
        return redirect('admin/tour/collection/'.$cid.'/edit');
    }

    public function edit($cid,$id)
    {
        $data['mainmenu']="Tour";
        $data['menu']="Tour Filter";
       // $a=array();
       // $b=array();

        $c=array();
        $d=array();
       // $name=array();
        $data['detail'] = $cid;
        $data['filter'] = TourFilter::findOrFail($id);
        $data['collection'] = Tourcollection::findOrFail($cid);
        $data['name']=TourList::all();
        $data['name1'] = TourGroup::all();

          $i_sel=explode(",",$data['filter']['tour_list']);

        $tour='';
        $grp='';
        foreach($i_sel as $key =>$v){
             $s=explode('-',$v);
            if($s[0] == 'L'){
                $tour.=$s[1].",";
            }
            if($s[0] == 'G'){
                $grp.=$s[1].",";
            }
        }

        /* EDIT MODE NOT SELECTED MENUS */
                ///// TOUR GROUP//////

                 $mode4 = explode(",",$data['collection']['group_list']);
         $mode44 = explode(",",$grp);
                $mode4 = array_diff($mode4, $mode44);
                foreach ($mode4 as $mod){
                    $p=TourGroup::where('id',$mod)->get()->toArray();
                    $c=array_merge($c,$p);
                }
                $data['mode4'] = $c;

                /* === TOUR LIST === */
                $mode3 = explode(",",$data['collection']['tour_list']);
                $mode33 = explode(",",$tour);
                $mode3 = array_diff($mode3, $mode33);
                foreach ($mode3 as $mod){
                    $q=TourList::where('id',$mod)->get()->toArray();
                    $d=array_merge($d,$q);
                }
                $data['mode3']=$d;

        /* EDIT MODE SELECTED MENUS */

        $mode1=array();
        foreach ($i_sel as $m) {
            $mode1 = array_merge($mode1, explode('-', $m));
            }
          $data['mode50']=$mode1;
        return view('admin.tour.filter.edit',$data);
    }

    public function update(Request $request, $id,$cid)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $filter = TourFilter::findOrFail($cid);
        $input = $request->all();
        $input['cid'] = $id;
       // $input['group_list']  = $request['grp_sel'];
      // return  $input['tour_list'] = $request['itm_sel'];

       $itm=explode(',~,',$request['itm_sel']);
        if(isset($itm[1])) {
            $input['tour_list'] = $itm[1];
        }
        else{
            $input['tour_list']="";
        }

        $filter->update($input);

        Activity::log('TourFilter -'.$filter->name.' [Id = '.$filter->id.'] has been updated');
        return redirect('admin/tour/collection/'.$id.'/edit');
    }
    public function destroy($id,$cid)
    {
        $data['detail'] = $id;
        $filter = TourFilter::findOrFail($cid);
        $filter->delete();
        Activity::log('TourFilter -'.$filter->name.' [Id = '.$filter->id.'] has been deleted');
        return redirect('admin/tour/collection/'.$id.'/edit');
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
            $filter = TourFilter::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $filter->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/tour/filter');
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $filter = TourFilter::findOrFail($idval);
                $input['displayorder'] = $count;
                $filter->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }

    public function assign(Request $request)
    {
        $filter = TourFilter::findorFail($request['id']);
        $filter['status']="active";
        $filter->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $filter = TourFilter::findorFail($request['id']);
        $filter['status']="inactive";
        $filter->update($request->all());
        return $request['id'];
    }
}
