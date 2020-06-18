<?php

namespace App\Http\Controllers\admin;

use App\Quota;
use App\TourInventory;
use App\TourPrice;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TourInventoryController extends Controller
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
        $data['menu']="TourInventory";
        $data['inventory']=TourInventory::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.tour.inventory.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Tour";
        $data['menu']="TourInventory";

        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        $data['name'] = DB::table('tour_prices')->where('pricegroupid', '=', $pricegroupid)->where('deleted_at',null)->lists('title', 'title');
        return view('admin.tour.inventory.create',$data);
    }
    public function store(Request $request,$detailid,$pricegroupid)
    {

        $inventory = $request->all();
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);



        if (!empty($request['title'])) {
            $inventory['title'] = implode(',', $request['title']);
        }

        $inventory['detailid'] = $detailid;
        $inventory['pricegroupid'] = $pricegroupid;

        $pro = TourInventory::create($inventory);

        $count4 = $request['theValue1216'];
        $address3=$request->all();
        for ($m = 1; $m <= $count4; $m++) {
            $address3['quota']=$address3['quota'.$m];
            $address3['date']=$address3['date'.$m];
            $address3['to']=$address3['to'.$m];
            $address3['quotaid'] = $pro->id;
            Quota::create($address3);
        }

        Activity::log('TourInventory -'.$pro->title.' [Id = '.$pro->id.'] has been inserted');
        return redirect('admin/tour/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {

        $data['mainmenu']="Tour";
        $data['menu']="TourInventory";

        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['inventory'] = TourInventory::findOrFail($pricegroupid);
        $data['name'] = DB::table('tour_prices')->where('pricegroupid', '=', $detailid)->where('deleted_at',null)->lists('title', 'title');
        $data['modes'] = explode(",",$data['inventory']['title']);
        return view('admin.tour.inventory.edit',$data);
    }

    public function update(Request $request,$id,$detailid,$pricegroupid)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);
        $inventory = TourInventory::findOrFail($pricegroupid);
        $input = $request->all();
        $input['title'] = implode(',',$request->title);
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;


        $count4 = $input['theValue1216'];

        if (isset($input['remove_id4']) && $input['remove_id4'] != "") {
            $remove_image = explode(",", $input['remove_id4']);
            foreach ($remove_image as $val) {
                $address = Quota::findOrFail($val);
                $address->forceDelete();
            }
        }

        if ((isset($input['remove_id4']) && $input['remove_id4'] != "") && (isset($input['inventory_id']) && $input['inventory_id'] != "")) {
            $update_id4 = explode(",", $input['inventory_id']);
            $remove_address = explode(",", $input['remove_id4']);
            foreach ($update_id4 as $key=>$val) {
                if (in_array($val,$remove_address)){
                    unset($update_id4[$key]);
                }
            }
        }
        else if(isset($input['inventory_id']) && $input['inventory_id'] != "") {
            $update_id4 = explode(",", $input['inventory_id']);
        }

        for ($i = 1; $i <= $count4; $i++) {
            $j = $i - 1;
            if (!empty($update_id4[$j])) {
                $address3 = Quota::findOrFail($update_id4[$j]);
                if (!empty($address3)){
                    $addr['quota']=$input['quota'.$i];
                    $addr['date']=$input['date'.$i];
                    $addr['to']=$input['to'.$i];
                    $address3->update($addr);
                }
            }
            else{
                $addr['quota']=$input['quota'.$i];
                $addr['date']=$input['date'.$i];
                $addr['to']=$input['to'.$i];
                $addr['quotaid'] = $inventory->id;
                Quota::create($addr);
            }
        }
        $inventory->update($input);

        Activity::log('TourInventory  -'.$inventory->title.'  [Id = '.$inventory->id.'] has been updated');
        return redirect('admin/tour/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $inventory = TourInventory::findOrFail($pricegroupid);
        $inventory->delete();

        Activity::log('TourInventory -'.$inventory->title.' [Id = '.$inventory->id.'] has been deleted');
        return redirect('admin/tour/'.$id.'/pricegroup/'.$detailid.'/edit');
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
            $tourprice = TourInventory::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $tourprice->update($input);
        }
        return redirect('admin/tour/inventory');
    }

}
