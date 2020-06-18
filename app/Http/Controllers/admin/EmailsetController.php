<?php

namespace App\Http\Controllers\admin;

use App\Emailset;
use App\MailAddress;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmailsetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Site Setup');
    }

    public function index()
    {
        $data['mainmenu']="Site Setup";
        $data['menu']="Email setup";
        $data['email']=Emailset::orderBy('id','ASC')->get();
        return view('admin.site.emailset.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Site Setup";
        $data['menu']="Email setup";
        return view('admin.site.emailset.create',$data);
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'type' => 'required',
            'status' => 'required',
        ]);

        $count_display_order = Emailset::count();
        if ($count_display_order >= 0) {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $input['displayorder'] = $c;
        /*---------------------*/

        if (!empty($request['type'])) {
            $input['type'] = implode(',', $request['type']);
        }

        $pro = Emailset::create($input);

        $input['emailid'] = $pro->id;
        $input['mail_address'] = $request['mail_address'];
        $contactemail =MailAddress::create($input);
        $count= $request['theValue1212'];
        for($i=1;$i<=$count;$i++){
            $input['emailid'] = $pro->id;
            $input['mail_address'] = $request['mail_address'.$i];
            $contactemail =MailAddress::create($input);
        }

        Activity::log('Email setup -'.$contactemail->type.' [Id = '.$contactemail->id.'] has been inserted');
        \Session::flash('success', 'Email setup has been inserted successfully!');
        return redirect('admin/site/emailset');
    }

    public function edit($id)
    {
        $data['mainmenu']="Site Setup";
        $data['menu']="Email setup";
        $data['email'] = Emailset::with('MailAddress')->findOrFail($id);
        $data['modes_selected'] = explode(",",$data['email']['type']);
        return view('admin.site.emailset.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type' => 'required',
            'status' => 'required',
        ]);

        $detail = Emailset::findOrFail($id);
        $input = $request->all();
        $input['type'] = implode(',',$request->type);
        $detail->update($input);

        if (isset($request['remove_id']) && $request['remove_id'] != "") {
            $remove_image = explode(",", $request['remove_id']);
            foreach ($remove_image as $val) {
                $con = MailAddress::findOrFail($val);
                $con->forceDelete();
            }
        }
        if ((isset($input['remove_id']) && $input['remove_id'] != "") && (isset($input['attribute_id']) && $input['attribute_id'] != "")) {
            $update_id = explode(",", $input['attribute_id']);
            $remove_address = explode(",", $input['remove_id']);
            foreach ($update_id as $key=>$val) {
                if (in_array($val,$remove_address)){
                    unset($update_id[$key]);
                }
            }
        }
        else if(isset($input['attribute_id']) && $input['attribute_id'] != "") {
            $update_id = explode(",", $input['attribute_id']);
        }

        $count= $request['theValue1212'];
        $test=0;
        for ($i = 1; $i <= $count; $i++) {
            $j = $i - 1;

            if (!empty($update_id1[$j])) {
                $address = MailAddress::findOrFail($update_id1[$j]);
            }
            else{
                $addr['mail_address']=$request['mail_address'.$i];
                $addr['emailid'] = $detail->id;
                if(!empty($addr['mail_address'])) {
                    MailAddress::create($addr);
                }
            }

            if($test != 1) {
                $test = 1;
                $a['mail_address'] = $request['mail_address'];
                $a['emailid'] = $detail->id;
                if(!empty($request['mail_address'])) {
                    MailAddress::create($a);
                }
            }
        }

        Activity::log('Email setup -'.$detail->type.' [Id = '.$detail->id.'] has been Updated');
        \Session::flash('success', 'Email setup has been Updated successfully!');

        return redirect('admin/site/emailset');

    }
    public function destroy($id)
    {
        $email = Emailset::findOrFail($id);
        $email->delete();

        Activity::log('Email setup -'.$email->type.' [Id = '.$email->id.'] has been deleted');
        \Session::flash('danger','Email setup has been deleted successfully!');
        return redirect('admin/site/emailset');
    }

    public function emaildelete($id)
    {
        $email = MailAddress::findOrFail($id);
        $email->delete();
        \Session::flash('danger','Email has been deleted successfully!');
        return redirect()->back();

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
            $email = Emailset::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $email->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/site/emailset');
    }

    public function assign(Request $request)
    {
        $email = Emailset::findorFail($request['id']);
        $email['status']="active";
        $email->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $email = Emailset::findorFail($request['id']);
        $email['status']="inactive";
        $email->update($request->all());
        return $request['id'];
    }
}
