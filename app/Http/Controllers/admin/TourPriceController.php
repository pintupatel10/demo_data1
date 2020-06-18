<?php

namespace App\Http\Controllers\admin;

use App\Quota;
use App\TourPrice;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TourPriceController extends Controller
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
        $data['menu']="Tour price";
        $data['tourprice']=TourPrice::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.tour.price.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Tour";
        $data['menu']="Tour price";
        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        return view('admin.tour.price.create',$data);
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

        $pro = TourPrice::create($image);

        $special_quotas = json_decode($request->input('special-quota'));
        foreach ($special_quotas as $special_quota)
        {
            Quota::create([
                'quotaid' => $pro->id,
                'quota' => $special_quota->quota,
                'date' => $special_quota->from,
                'to' => $special_quota->to,
                'day_of_week' => $special_quota->day,
            ]);
        }

        Activity::log('TourPrice -'.$pro->title.' [Id = '.$pro->id.'] has been inserted');
        return redirect('admin/tour/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {
        $data['mainmenu']="Tour";
        $data['menu']="Tour price";
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['tourprice'] = TourPrice::findOrFail($pricegroupid);
        return view('admin.tour.price.edit',$data);
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
      
        $tourprice = TourPrice::findOrFail($pricegroupid);
        $input = $request->all();
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;


        // Delete removed quota
        $special_quotas = json_decode($request->input('special-quota'));
        $ids = collect($special_quotas)->pluck('id')->all();
        $tourprice->Quota()->whereNotIn('id', $ids)->delete();

        // Create new or update existing quota
        foreach ($special_quotas as $special_quota)
        {
            if ($special_quota->id == 0) {
                Quota::create([
                    'quotaid' => $tourprice->id,
                    'quota' => $special_quota->quota,
                    'date' => $special_quota->from,
                    'to' => $special_quota->to,
                    'day_of_week' => $special_quota->day,
                ]);
            }
            else
            {
                $quota = $tourprice->Quota()->find($special_quota->id);
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

        $tourprice->update($input);

        Activity::log('TourPrice  -'.$tourprice->title.'  [Id = '.$tourprice->id.'] has been updated');
        return redirect('admin/tour/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $tourprice = TourPrice::findOrFail($pricegroupid);
        $tourprice->delete();

        Activity::log('TourPrice -'.$tourprice->title.' [Id = '.$tourprice->id.'] has been deleted');
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
            $tourprice = TourPrice::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $tourprice->update($input);
        }
       // \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/tour/price');
    }

    public function assign(Request $request)
    {
        $tourprice = TourPrice::findorFail($request['id']);
        $tourprice['status']="active";
        $tourprice->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $tourprice = TourPrice::findorFail($request['id']);
        $tourprice['status']="inactive";
        $tourprice->update($request->all());
        return $request['id'];
    }
}
