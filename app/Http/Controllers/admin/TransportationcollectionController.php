<?php

namespace App\Http\Controllers\admin;

use App\Transportationcollection;
use App\TransportationGroup;
use App\TransportationList;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportationcollectionController extends Controller
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
        $data['menu']="Transportation Collection";
        $data['collection']=Transportationcollection::orderBy('id','ASC')->get();
        return view('admin.transportation.collection.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation Collection";
        $data['name']=TransportationList::where('post','=','Public')->get();
        $data['name1']=TransportationGroup::all();
        return view('admin.transportation.collection.create',$data);
    }

    public function store(Request $request)
    {
        $transportation = new Transportationcollection($request->all());
        $this->validate($request, [
            'name' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);


        /* ADD DISPLAY ORDER */
        $count_display_order = Transportationcollection::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $transportation->displayorder = $c;
        /*---------------------*/

        if(!empty($transportation->language))
        {
            $transportation->language=implode(',',$transportation->language);
        }
        $transportation['description']=$request['description'];
        $transportation['group_list']= $request['grp_sel'];
        $transportation['transportation_list']= $request['itm_sel'];
        $transportation->save();

        Activity::log('Transportation Collection -'.$transportation->title.' [Id = '.$transportation->id.'] has been inserted');
        \Session::flash('success', 'Transportation Collection has been inserted successfully!');
        return redirect('admin/transportation/collection');
    }

    public function edit($id)
    {
        $a=array();
        $b=array();
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation Collection";
        $data['collection'] = Transportationcollection::findOrFail($id);
        $data['name']=TransportationList::where('post','=','Public')->get();
        $data['name1'] = TransportationGroup::all();
        $data['filter']=$data['collection']->TransportationFilter()->orderBy('displayorder','ASC')->Paginate($this->pagination);
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
            $p=TransportationGroup::where('id',$mod)->get()->toArray();
            $b=array_merge($b,$p);
        }

        $data['mode1'] = $b;

        foreach($data['name'] as $key => $val){
            $mode = explode(",",$data['collection']['transportation_list']);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['name'][$key]);
                }
            }
        }
        $mode = explode(",",$data['collection']['transportation_list']);
        $list=implode(',',$mode);
        foreach ($mode as $mod){
            $q=TransportationList::where('id',$mod)->get()->toArray();
            $a=array_merge($a,$q);
        }
        $data['mode']=$a;

        return view('admin.transportation.collection.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'language' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $collection = Transportationcollection::findOrFail($id);
        $input = $request->all();

        $input['language'] = implode(',',$request->language);

        $input['group_list']= $request['grp_sel'];
        $input['transportation_list']= $request['itm_sel'];
        $input['description']=$request['description'];
        $collection->update($input);

        Activity::log('Transportation Collection -'.$collection->title.' [Id = '.$collection->id.'] has been Updated');
        \Session::flash('success', 'Transportation Collection has been Updated successfully!');
        return redirect('admin/transportation/collection');
    }
    public function destroy($id)
    {
        $collection = Transportationcollection::findOrFail($id);
        $collection->delete();

        Activity::log('Transportation Collection -'.$collection->title.' [Id = '.$collection->id.'] has been deleted');
        \Session::flash('danger','Transportation Collection has been deleted successfully!');
        return redirect('admin/transportation/collection');
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
            $collection = Transportationcollection::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $collection->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/transportation/collection');
    }

    public function assign(Request $request)
    {
        $collection = Transportationcollection::findorFail($request['id']);
        $collection['status']="active";
        $collection->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $collection = Transportationcollection::findorFail($request['id']);
        $collection['status']="inactive";
        $collection->update($request->all());
        return $request['id'];
    }
}
