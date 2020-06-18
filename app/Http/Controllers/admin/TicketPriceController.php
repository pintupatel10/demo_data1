<?php

namespace App\Http\Controllers\admin;

use App\TicketPrice;
use App\TicketQuota;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketPriceController extends Controller
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
        $data['menu']="Ticket price";
        $data['price']=TicketPrice::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.ticket.price.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket price";
        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        return view('admin.ticket.price.create',$data);
    }
    public function store(Request $request,$detailid,$pricegroupid)
    {

        $image = $request->all();
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'volume' => 'required',
            'report_price_type' => 'required',
            'status' => 'required',
            'special-quota' => 'required',
        ]);

        

        $image['detailid'] = $detailid;
        $image['pricegroupid'] = $pricegroupid;

        $pro = TicketPrice::create($image);

        $special_quotas = json_decode($request->input('special-quota'));
        foreach ($special_quotas as $special_quota)
        {
            TicketQuota::create([
                'quotaid' => $pro->id,
                'quota' => $special_quota->quota,
                'date' => $special_quota->from,
                'to' => $special_quota->to,
                'day_of_week' => $special_quota->day,
            ]);
        }

        Activity::log('TicketPrice -'.$pro->title.' [Id = '.$pro->id.'] has been inserted');
        return redirect('admin/ticket/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {
        $data['mainmenu']="Ticket";
        $data['menu']="Ticket price";
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['price'] = TicketPrice::findOrFail($pricegroupid);
        return view('admin.ticket.price.edit',$data);
    }

    public function update(Request $request,$id,$detailid,$pricegroupid)
    {
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'volume' => 'required',
            'status' => 'required',
            'report_price_type' => 'required',
            'special-quota' => 'required',
        ]);

        $price = TicketPrice::findOrFail($pricegroupid);
        $input = $request->all();
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;

        // Delete removed quota
        $special_quotas = json_decode($request->input('special-quota'));
        $ids = collect($special_quotas)->pluck('id')->all();
        $price->TicketQuota()->whereNotIn('id', $ids)->delete();

        // Create new or update existing quota
        foreach ($special_quotas as $special_quota)
        {
            if ($special_quota->id == 0) {
                TicketQuota::create([
                    'quotaid' => $price->id,
                    'quota' => $special_quota->quota,
                    'date' => $special_quota->from,
                    'to' => $special_quota->to,
                    'day_of_week' => $special_quota->day,
                ]);
            }
            else
            {
                $quota = $price->TicketQuota()->find($special_quota->id);
                if ($quota)
                {
                    $quota->update([
                        'quota' => $special_quota->quota,
                        'date' => $special_quota->from,
                        'to' => $special_quota->to,
                        'day_of_week' => $special_quota->day,
                    ]);
                }
            }
        }

        $price->update($input);

        Activity::log('TicketPrice -'.$price->title.' [Id = '.$price->id.'] has been Updated');
        return redirect('admin/ticket/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $price = TicketPrice::findOrFail($pricegroupid);
        $price->delete();

        Activity::log('TicketPrice -'.$price->title.'  [Id = '.$price->id.'] has been deleted');
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
            $price = TicketPrice::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $price->update($input);
        }
        return redirect('admin/ticket/price');
    }

    public function assign(Request $request)
    {
        $price = TicketPrice::findorFail($request['id']);
        $price['status']="active";
        $price->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $price = TicketPrice::findorFail($request['id']);
        $price['status']="inactive";
        $price->update($request->all());
        return $request['id'];
    }
}
