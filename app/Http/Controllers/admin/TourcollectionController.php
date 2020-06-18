<?php

namespace App\Http\Controllers\admin;

use App\Tourcollection;
use App\TourDetailsimplified;
use App\TourFilter;
use App\TourGroup;
use App\TourGroupdetail;
use App\TourList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TourcollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Tour');

    }


    public function index()
    {
        $data['mainmenu']="Tour";
        $data['menu']="Tour Collection";
        $data['collection']=Tourcollection::orderBy('id','ASC')->get();
        return view('admin.tour.collection.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Tour";
        $data['menu']="Tour Collection";
        $data['name']=TourList::where('post','=','Public')->get();
        $data['name1']=TourGroup::all();
        return view('admin.tour.collection.create',$data);
    }

    public function store(Request $request)
    {
        $collection = new Tourcollection($request->all());
        $this->validate($request, [
            'name' => 'required',
            'language' => 'required',
            'description' => 'required',

            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Tourcollection::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $collection->displayorder = $c;
        /*---------------------*/
        if(!empty($collection->language))
        {
            $collection->language=implode(',',$collection->language);
        }
        $collection['group_list'] = $request['grp_sel'];
        $collection['tour_list'] = $request['itm_sel'];
        $collection['description']=$request['description'];

        $collection->save();

        Activity::log('Tour Collection -'.$collection->title.'  [Id = '.$collection->id.'] has been inserted');
        \Session::flash('success', 'Tour Collection has been inserted successfully!');
        return redirect('admin/tour/collection');
    }

    public function edit($id)
    {
        $a=array();
        $b=array();
        $data['count']='';
        $data['mainmenu']="Tour";
        $data['menu']="Tour Collection";
        $data['collection'] = Tourcollection::findOrFail($id);
        $data['name']=TourList::where('post','=','Public')->get();
        $data['name1'] = TourGroup::all();
        //$data['filter']=TourFilter::orderBy('displayorder','ASC')->Paginate($this->pagination);
        $data['filter']=$data['collection']->TourFilter()->orderBy('displayorder','ASC')->Paginate($this->pagination);
        $data['modes_selected'] = explode(",",$data['collection']['language']);

        foreach($data['name1'] as $key => $val){
            $mode1 = explode(",",$data['collection']['group_list']);
            foreach ($mode1 as $mod){
                if($mod == $val['id']){
                    unset($data['name1'][$key]);
                }
            }
        }
         $mode1 = explode(",",$data['collection']['group_list']);
         $list=implode(',',$mode1);
        foreach ($mode1 as $mod){
            $p=TourGroup::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$p);
        }

         $data['mode1'] = $b;

        foreach($data['name'] as $key => $val){
            $mode = explode(",",$data['collection']['tour_list']);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['name'][$key]);
                }
            }
        }
        $mode = explode(",",$data['collection']['tour_list']);
        $list=implode(',',$mode);
        foreach ($mode as $mod){
            $q=TourList::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
        $data['mode']=$a;

        return view('admin.tour.collection.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $collection = Tourcollection::findOrFail($id);
        $input = $request->all();
        $input['language'] = implode(',',$request->language);
        $input['group_list']= $request['grp_sel'];
        $input['tour_list'] = $request['itm_sel'];
        $input['description']=$request['description'];
        $collection->update($input);

        Activity::log('Tour Collection -'.$collection->title.' [Id = '.$collection->id.'] has been Updated');
        \Session::flash('success', 'Tour Collection has been Updated successfully!');
        return redirect('admin/tour/collection');
    }
    public function destroy($id)
    {
        $collection = Tourcollection::findOrFail($id);
        $collection->delete();
        Activity::log('Tour Collection -'.$collection->title.'  [Id = '.$collection->id.'] has been deleted');
        \Session::flash('danger','Tour Collection has been deleted successfully!');
        return redirect('admin/tour/collection');
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
            $collection = Tourcollection::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $collection->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/tour/collection');
    }

    public function assign(Request $request)
    {
        $collection = Tourcollection::findorFail($request['id']);
        $collection['status']="active";
        $collection->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $collection = Tourcollection::findorFail($request['id']);
        $collection['status']="inactive";
        $collection->update($request->all());
        return $request['id'];
    }
}
