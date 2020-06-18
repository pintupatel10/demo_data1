<?php

namespace App\Http\Controllers\admin;

use App\TransportationPrice;
use App\TransportationQuota;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportationPriceController extends Controller
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
        $data['menu']="Transportation price";
        $data['price']=TransportationPrice::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.transportation.price.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation price";
        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        return view('admin.transportation.price.create',$data);
    }
    public function store(Request $request,$detailid,$pricegroupid)
    {

        $image = $request->all();
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'status' => 'required',
            'report_price_type' => 'required',
            'special-quota' => 'required',
        ]);


        $image['detailid'] = $detailid;
        $image['pricegroupid'] = $pricegroupid;

        if (!empty($request['Weekend/Weekday'])) {
            $image['Weekend/Weekday'] = implode(',', $request['Weekend/Weekday']);
        }

        $pro = TransportationPrice::create($image);

        $special_quotas = json_decode($request->input('special-quota'));
        foreach ($special_quotas as $special_quota)
        {
            TransportationQuota::create([
                'quotaid' => $pro->id,
                'quota' => $special_quota->quota,
                'date' => $special_quota->from,
                'to' => $special_quota->to,
                'day_of_week' => $special_quota->day,
            ]);
        }

        Activity::log('TransportationPrice -'.$pro->title.' [Id = '.$pro->id.'] has been inserted');
        return redirect('admin/transportation/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {
        $data['mainmenu']="Transportation";
        $data['menu']="Transportation price";
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['price'] = TransportationPrice::findOrFail($pricegroupid);
        $data['modes_selected'] = explode(",",$data['price']['Weekend/Weekday']);
        return view('admin.transportation.price.edit',$data);
    }

    public function update(Request $request,$id,$detailid,$pricegroupid)
    {
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'status' => 'required',
            'report_price_type' => 'required',
            'special-quota' => 'required',
        ]);


        $price = TransportationPrice::findOrFail($pricegroupid);
        $input = $request->all();
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;
        $input['Weekend/Weekday'] = implode(',', $request['Weekend/Weekday']);

        // Delete removed quota
        $special_quotas = json_decode($request->input('special-quota'));
        $ids = collect($special_quotas)->pluck('id')->all();
        $price->TransportationQuota()->whereNotIn('id', $ids)->delete();

        // Create new or update existing quota
        foreach ($special_quotas as $special_quota)
        {
            if ($special_quota->id == 0) {
                TransportationQuota::create([
                    'quotaid' => $price->id,
                    'quota' => $special_quota->quota,
                    'date' => $special_quota->from,
                    'to' => $special_quota->to,
                    'day_of_week' => $special_quota->day,
                ]);
            }
            else
            {
                $quota = $price->TransportationQuota()->find($special_quota->id);
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

        Activity::log('TransportationPrice -'.$price->title.' [Id = '.$price->id.'] has been updated');
        return redirect('admin/transportation/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $price = TransportationPrice::findOrFail($pricegroupid);
        $price->delete();

        Activity::log('TransportationPrice  -'.$price->title.' [Id = '.$price->id.'] has been deleted');
        return redirect('admin/transportation/'.$id.'/pricegroup/'.$detailid.'/edit');
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
            $price = TransportationPrice::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $price->update($input);
        }
        return redirect('admin/transportation/price');
    }

    public function assign(Request $request)
    {
        $price = TransportationPrice::findorFail($request['id']);
        $price['status']="active";
        $price->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $price = TransportationPrice::findorFail($request['id']);
        $price['status']="inactive";
        $price->update($request->all());
        return $request['id'];
    }
}
