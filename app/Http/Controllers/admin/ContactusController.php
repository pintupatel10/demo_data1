<?php

namespace App\Http\Controllers\admin;

use App\Contactemail;
use App\Contactus;
use App\Helpers\ImageHelper;
use App\Images;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ContactusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Contact');
    }

    public function index()
    {
        $data['mainmenu']="Contact";
        $data['menu']="Contact us";
        $data['contact']=Contactus::orderBy('id','ASC')->get();
        return view('admin.contact.contact_us.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Contact";
        $data['menu']="Contact us";
        $data['image_center'] = Images::get();

        return view('admin.contact.contact_us.create',$data);
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'menu_name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'address_map' => 'required',
            'language' => 'required',
            'status' => 'required',
        ]);

        $count_display_order = Contactus::count();
        if ($count_display_order >= 0) {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $input['displayorder'] = $c;
        /*---------------------*/

        if(!empty($request->image_name))
        {
            $input['image'] = $request->image_name;
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
                $photo->move($root,$name);
                ImageHelper::generateThumbnail($image_path);
                $image['image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['image' => 'please select image']);
            }
        }

        if (!empty($request['language'])) {
            $input['language'] = implode(',', $request['language']);
        }
       
        $pro = Contactus::create($input);

//        $input['contactid'] = $pro->id;
//        $input['email_receiver'] = $request['email_receiver'];
//        $contactemail =Contactemail::create($input);
//        $count= $request['theValue1212'];
//        for($i=1;$i<=$count;$i++){
//             $input['contactid'] = $pro->id;
//             $input['email_receiver'] = $request['email_receiver'.$i];
//             $contactemail =Contactemail::create($input);
//            }
            //$input['place_id']=$place->id;

        Activity::log('Contact us -'.$pro->title.'[Id = '.$pro->id.'] has been inserted');

        \Session::flash('success', 'Contact us has been inserted successfully!');
        return redirect('admin/contact/contact_us');
    }

    public function edit($id)
    {
        $data['mainmenu']="Contact";
        $data['menu']="Contact us";
        $data['image_center'] = Images::get();
        $data['contact'] = Contactus::with('contactemail')->findOrFail($id);
        $data['modes_selected'] = explode(",",$data['contact']['language']);
        return view('admin.contact.contact_us.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'menu_name' => 'required',
            'title' => 'required',
            'description' => 'required',
            'address_map' => 'required',
            'language' => 'required',
            'status' => 'required',
        ]);

        $detail = Contactus::findOrFail($id);
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

        $input['language'] = implode(',',$request->language);

        $detail->update($input);

//        if (isset($request['remove_id']) && $request['remove_id'] != "") {
//            $remove_image = explode(",", $request['remove_id']);
//            foreach ($remove_image as $val) {
//                $con = Contactemail::findOrFail($val);
//                $con->forceDelete();
//            }
//        }
//        if ((isset($input['remove_id']) && $input['remove_id'] != "") && (isset($input['attribute_id']) && $input['attribute_id'] != "")) {
//            $update_id = explode(",", $input['attribute_id']);
//            $remove_address = explode(",", $input['remove_id']);
//            foreach ($update_id as $key=>$val) {
//                if (in_array($val,$remove_address)){
//                    unset($update_id[$key]);
//                }
//            }
//        }
//        else if(isset($input['attribute_id']) && $input['attribute_id'] != "") {
//            $update_id = explode(",", $input['attribute_id']);
//        }
//
//        $count= $request['theValue1212'];
//        $test=0;
//        for ($i = 1; $i <= $count; $i++) {
//            $j = $i - 1;
//
//            if (!empty($update_id1[$j])) {
//                $address = Contactemail::findOrFail($update_id1[$j]);
//            }
//            else{
//                $addr['email_receiver']=$request['email_receiver'.$i];
//                $addr['contactid'] = $detail->id;
//                if(!empty($addr['email_receiver'])) {
//                    Contactemail::create($addr);
//                }
//            }
//
//            if($test != 1) {
//               $test = 1;
//                $a['email_receiver'] = $request['email_receiver'];
//                $a['contactid'] = $detail->id;
//                if(!empty($request['email_receiver'])) {
//                    Contactemail::create($a);
//                }
//            }
//        }
        Activity::log('Contact us - '.$detail->title.'[Id = '.$detail->id.'] has been Updated');
        \Session::flash('success', 'Contact us has been Updated successfully!');

        return redirect('admin/contact/contact_us');

    }
    public function destroy($id)
    {
        $contact = Contactus::findOrFail($id);
        $contact->delete();
        Activity::log('Contact us  -'.$contact->title.'[Id = '.$contact->id.'] has been deleted');
        \Session::flash('danger','Contact us has been deleted successfully!');
        return redirect('admin/contact/contact_us');
    }

    public function emaildelete($id)
    {
        $contact = Contactemail::findOrFail($id);
        $contact->delete();
        \Session::flash('danger','Contact email has been deleted successfully!');
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
            $contact = Contactus::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $contact->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/contact/contact_us');
    }

    public function assign(Request $request)
    {
        $contact = Contactus::findorFail($request['id']);
        $contact['status']="active";
        $contact->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $contact = Contactus::findorFail($request['id']);
        $contact['status']="inactive";
        $contact->update($request->all());
        return $request['id'];
    }
}
