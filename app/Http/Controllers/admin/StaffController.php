<?php

namespace App\Http\Controllers\admin;

use App\Group;
use App\Helpers\ImageHelper;
use App\Staff;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
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
        $data['menu']="Staff";
        $data['staff']=Staff::where('role','Staff')->Paginate($this->pagination);
        return view('admin.staff.staff.index',$data);
    }
      public function show($id){
        $data['mainmenu'] = "Staff";
        $data['menu']="Staff";
        $data['staff']=Staff::where('id',$id)->get();
        return view('admin.staff.staff.index',$data);
      }
    public function create()
    {
        $data=[];
        $data['mainmenu'] = "Staff";
        $data['menu']="Staff";
       // $data['name']=Staff::lists('role','id')->all();
        $data['groupname']=DB::table('groups')->where('deleted_at',null)->lists('name','id');
        return view('admin.staff.staff.create',$data);
    }
	
    public function store(Request $request)
    {
        $input=$request->all();
        //$staff = new User($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'number' => 'required|unique:users,number',
            'password' => 'confirmed',
            'group_id' => 'required',
            'status' => 'required',
        ]);
        $input['user_id'] = Auth::user()->id;
        $input['role']='Staff';

        if($photo = $request->file('image'))
        {
            $root = base_path() . '/public/resource/staffprofile/' ;
            $name = str_random(20).".".$photo->getClientOriginalExtension();
            if (!file_exists($root)) {
                mkdir($root, 0777, true);
            }
            $image_path = "resource/staffprofile/".$name;
            ImageHelper::generateThumbnail($image_path);
            $photo->move($root,$name);
            $input['image'] = $image_path;
        }

         $staff = Staff::create($input);
        //$staff->save();

        Activity::log('Staff -'.$staff->name.'  [Id = '.$staff->id.'] has been inserted');
        \Session::flash('success', 'Staff has been inserted successfully!');
        return redirect('admin/staff');
    }

    public function edit($id)
    {

        $data['mainmenu'] = "Staff";
        $data['menu']="Staff";
        $data['staff'] = Staff::findOrFail($id);
        $data['groupname']=DB::table('groups')->where('deleted_at',null)->lists('name','id');
        $data['modes_selected'] = explode(",",$data['staff']['group_id']);
        return view('admin.staff.staff.edit',$data);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'password' => 'confirmed',
            'number' => 'required|unique:users,number,'.$id.',id',
            'group_id' => 'required',
            'status' => 'required',
        ]);

        $staff = Staff::findOrFail($id);
        $input = $request->all();
     //   $input['group_id'] = implode(',',$request->group_id);

        if($photo = $request->file('image'))
        {
            $root = base_path() . '/public/resource/staffprofile/' ;
            $name = str_random(20).".".$photo->getClientOriginalExtension();
            if (!file_exists($root)) {
                mkdir($root, 0777, true);
            }
            $image_path = "resource/staffprofile/".$name;
            ImageHelper::generateThumbnail($image_path);
            $photo->move($root,$name);
            $input['image'] = $image_path;
        }

        $staff->update($input);

        Activity::log('Staff -'.$staff->name.' [Id = '.$staff->id.'] has been Updated');
        \Session::flash('success', 'Staff has been Updated successfully!');

        $url = $request->only('redirects_to');
        return redirect()->to($url['redirects_to']);

    }
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        Activity::log('Staff -'.$staff->name.' [Id = '.$staff->id.'] has been deleted');
        \Session::flash('danger','Staff has been deleted successfully!');
        return redirect('admin/staff'); 
    }

  /*  public function update_display_order(Request $request)
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

            $staff = Staff::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $staff->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/staff');
    }
  */

    public function assign(Request $request)
    {
        $staff = Staff::findorFail($request['id']);
        $staff['status']="active";
        $staff->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $staff = Staff::findorFail($request['id']);
        $staff['status']="inactive";
        $staff->update($request->all());
        return $request['id'];
    }
}
