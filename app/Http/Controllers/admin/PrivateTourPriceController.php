<?php

namespace App\Http\Controllers\admin;

use App\TourPrice;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrivateTourPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Private Tour');

    }


    public function index()
    {
        $data['mainmenu']="Private Tour";
        $data['menu']="Private Tour price";
        $data['tourprice']=TourPrice::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.privatetour.price.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Private Tour";
        $data['menu']="Private Tour price";
        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        return view('admin.privatetour.price.create',$data);
    }
    public function store(Request $request,$detailid,$pricegroupid)
    {

        //$image = $request->all();
        $image = new TourPrice($request->all());
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'status' => 'required',
            'dquota' => 'required|numeric',
        ]);

        $image['detailid'] = $detailid;
        $image['pricegroupid'] = $pricegroupid;

        $image->save();

        Activity::log('PrivateTourPrice -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        return redirect('admin/privatetour/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {
        $data['mainmenu']="Private Tour";
        $data['menu']="Private Tour price";
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['tourprice'] = TourPrice::findOrFail($pricegroupid);
        return view('admin.privatetour.price.edit',$data);
    }

    public function update(Request $request,$id,$detailid,$pricegroupid)
    {
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'status' => 'required',
            'dquota' => 'required|numeric',
        ]);


        $tourprice = TourPrice::findOrFail($pricegroupid);
        $input = $request->all();
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;

        $tourprice->update($input);

        Activity::log('PrivateTourPrice -'.$tourprice->title.' [Id = '.$tourprice->id.'] has been Updated');
        return redirect('admin/privatetour/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $tourprice = TourPrice::findOrFail($pricegroupid);
        $tourprice->delete();

        Activity::log('PrivateTourPrice -'.$tourprice->title.' [Id = '.$tourprice->id.'] has been deleted');
        return redirect('admin/privatetour/'.$id.'/pricegroup/'.$detailid.'/edit');
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
        return redirect('admin/privatetour/price');
    }

}
