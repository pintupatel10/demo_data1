<?php

namespace App\Http\Controllers\admin;

use App\Group;
use App\Http\Controllers\Controller;
use App\Staff;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Staff Management');
    }

    public function index()
    {
        $data['mainmenu'] = "Staff";
        $data['menu']="Group";
        $data['group']=Group::Paginate($this->pagination);
        return view('admin.staff.group.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu'] = "Staff";
        $data['menu']="Group";
       // $data['name']=Group::lists('role','id')->all();
        return view('admin.staff.group.create',$data);
    }
    public function store(Request $request)
    {
        $input=$request->all();
        //$group = new User($request->all());
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);
        if(!empty($request['accessright'])) {
            $input['accessright'] = implode(',',$request['accessright']);
        }
        $group = Group::create($input);
        //$group->save();

        Activity::log('Group -'.$group->name.' [Id = '.$group->id.'] has been inserted');
        \Session::flash('success', 'Group has been inserted successfully!');
        return redirect('admin/group');
    }

    public function edit($id)
    {

        $data['mainmenu'] = "Staff";
        $data['menu']="Group";
        $data['group'] = Group::findOrFail($id);
        $data['accessselected']= explode(",",$data['group']['accessright']);
        $data['staff']=Staff::where('group_id',$id)->Paginate($this->pagination);
        return view('admin.staff.group.edit',$data);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $group = Group::findOrFail($id);

        $input = $request->all();
        if(!empty($request['accessright'])) {
            $input['accessright'] = implode(',',$request['accessright']);
        }
        $group->update($input);

        Activity::log('Group -'.$group->name.' [Id = '.$group->id.'] has been Updated');
        \Session::flash('success', 'Group has been Updated successfully!');

        $url = $request->only('redirects_to');
        return redirect()->to($url['redirects_to']);

    }
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        Activity::log('Group -'.$group->name.' [Id = '.$group->id.'] has been deleted');
        \Session::flash('danger','Group has been deleted successfully!');
        return redirect('admin/group');
    }

    public function assign(Request $request)
    {
        $group = Group::findorFail($request['id']);
        $group['status']="active";
        $group->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $group = Group::findorFail($request['id']);
        $group['status']="inactive";
        $group->update($request->all());
        return $request['id'];
    }
}
