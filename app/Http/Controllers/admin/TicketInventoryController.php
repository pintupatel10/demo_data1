<?php

namespace App\Http\Controllers\admin;

use App\TicketInventory;
use App\TicketPrice;
use App\TicketQuota;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TicketInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Ticket');

    }


    public function index()
    {
        $data['mainmenu']="Ticket";
        $data['menu']="TicketInventory";
        $data['inventory']=TicketInventory::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.ticket.inventory.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Ticket";
        $data['menu']="TicketInventory";

        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        $data['name'] = DB::table('ticket_prices')->where('pricegroupid', '=', $pricegroupid)->where('deleted_at',null)->lists('title', 'title');
        return view('admin.ticket.inventory.create',$data);
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

        $pro = TicketInventory::create($inventory);

        $count4 = $request['theValue1216'];
        $address3=$request->all();
        for ($m = 1; $m <= $count4; $m++) {
            $address3['quota']=$address3['quota'.$m];
            $address3['date']=$address3['date'.$m];
            $address3['to']=$address3['to'.$m];
            $address3['quotaid'] = $pro->id;
            TicketQuota::create($address3);
        }

        Activity::log('TicketInventory -'.$pro->title.' [Id = '.$pro->id.'] has been inserted');
        return redirect('admin/ticket/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {

        $data['mainmenu']="Ticket";
        $data['menu']="TicketInventory";

        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['inventory'] = TicketInventory::findOrFail($pricegroupid);
        $data['name'] = DB::table('ticket_prices')->where('pricegroupid', '=', $detailid)->where('deleted_at',null)->lists('title', 'title');
        $data['modes'] = explode(",",$data['inventory']['title']);
        return view('admin.ticket.inventory.edit',$data);
    }

    public function update(Request $request,$id,$detailid,$pricegroupid)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);
        $inventory = TicketInventory::findOrFail($pricegroupid);
        $input = $request->all();
        $input['title'] = implode(',',$request->title);
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;


        $count4 = $input['theValue1216'];

        if (isset($input['remove_id4']) && $input['remove_id4'] != "") {
            $remove_image = explode(",", $input['remove_id4']);
            foreach ($remove_image as $val) {
                $address = TicketQuota::findOrFail($val);
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
                $address3 = TicketQuota::findOrFail($update_id4[$j]);
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
                TicketQuota::create($addr);
            }
        }
        $inventory->update($input);

        Activity::log('TicketInventory -'.$inventory->title.' [Id = '.$inventory->id.'] has been Updated');
        return redirect('admin/ticket/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $inventory = TicketInventory::findOrFail($pricegroupid);
        $inventory->delete();

        Activity::log('TicketInventory -'.$inventory->title.' [Id = '.$inventory->id.'] has been deleted');
        return redirect('admin/ticket/'.$id.'/pricegroup/'.$detailid.'/edit');
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
            $inventory = TicketInventory::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $inventory->update($input);
        }
        return redirect('admin/ticket/inventory');
    }

}
