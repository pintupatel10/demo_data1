<?php

namespace App\Http\Controllers\admin;

use App\HotelLayout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;

class HotelLayoutController extends Controller
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
        $data['menu']="Hotel Layout";
        // $data['hotellayout']=HotelLayout::orderBy('displayorder','DESC')->Paginate($this->pagination);
        $data['hotellayout']=HotelLayout::orderBy('displayorder',"ASC")->get();
        return view('admin.hotel.layout.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel Layout";
        return view('admin.hotel.layout.create',$data);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'language' => 'required',
            'status' => 'required',
        ]);
        $hotel = new HotelLayout($request->all());
        /* ADD DISPLAY ORDER */
        $count_display_order = HotelLayout::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $hotel->displayorder = $c;
        /*---------------------*/

        $hotel->description = $request->description;
        $hotel->save();

        Activity::log('HotelLayout [Id = '.$hotel->id.'] has been inserted');
        \Session::flash('success', 'HotelLayout has been inserted successfully!');
        return redirect('admin/hotel/layout');
    }

    public function edit($id)
    {
        $data['mainmenu']="Hotel";
        $data['menu']="Hotel Layout";
        $data['hotellayout'] = HotelLayout::findOrFail($id);
        //$data['modes_selected'] = explode(",",$data['hotellayout']['language']);
        return view('admin.hotel.layout.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'description' => 'required',
            'language'=> 'required',
            'status' => 'required',
        ]);


        $hotellayout = HotelLayout::findOrFail($id);
        $input = $request->all();
        //$input['language'] = implode(',',$request->language);
        $hotellayout->update($input);


        Activity::log('HotelLayout [Id = '.$hotellayout->id.'] has been Updated');
        \Session::flash('success', 'HotelLayout has been Updated successfully!');

        return redirect('admin/hotel/layout');
        // $url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);

    }
    public function destroy($id)
    {
        $hotellayout = HotelLayout::findOrFail($id);
       
        $hotellayout->delete();

        Activity::log('HotelLayout [Id = '.$hotellayout->id.'] has been deleted');
        \Session::flash('danger','HotelLayout has been deleted successfully!');
        return redirect('admin/hotel/layout');
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
            $hotellayout = HotelLayout::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $hotellayout->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/hotel/layout');
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {
                $post = hotelLayout::findOrFail($idval);
                $input['displayorder'] = $count;
                $post->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }
    public function assign(Request $request)
    {
        $hoteldetail = hotelLayout::findorFail($request['id']);
        $hoteldetail['status']="active";
        $hoteldetail->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $hoteldetail = hotelLayout::findorFail($request['id']);
        $hoteldetail['status']="inactive";
        $hoteldetail->update($request->all());
        return $request['id'];
    }
}
