<?php

namespace App\Http\Controllers\admin;

use App\TransportationPrice;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrivateTransportationPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Private Transportation');

    }
    public function index()
    {
        $data['mainmenu']="Private Transportation";
        $data['menu']="Private Transportation price";
        $data['transportationprice']=TransportationPrice::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.privatetransportation.price.index',$data);
    }

    public function create($detailid,$pricegroupid)
    {
        $data=[];
        $data['mainmenu']="Private Transportation";
        $data['menu']="Private Transportation price";
        $data['detail']=$detailid;
        $data['detail1']=$pricegroupid;
        return view('admin.privatetransportation.price.create',$data);
    }
    public function store(Request $request,$detailid,$pricegroupid)
    {
        $image = new TransportationPrice($request->all());
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'status' => 'required',
            'dquota' => 'required|numeric',
        ]);

        $image['detailid'] = $detailid;
        $image['pricegroupid'] = $pricegroupid;

        $image->save();

        Activity::log('privatetransportationprice -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        return redirect('admin/privatetransportation/'.$detailid.'/pricegroup/'.$pricegroupid.'/edit');
    }

    public function edit($id,$detailid,$pricegroupid)
    {
        $data['mainmenu']="Private Transportation";
        $data['menu']="Private Transportation price";
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $data['transportationprice'] = TransportationPrice::findOrFail($pricegroupid);
        return view('admin.privatetransportation.price.edit',$data);
    }

    public function update(Request $request,$id,$detailid,$pricegroupid)
    {
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required|Numeric',
            'status' => 'required',
            'dquota' => 'required|numeric',
        ]);


        $transportationprice = TransportationPrice::findOrFail($pricegroupid);
        $input = $request->all();
        $input['detailid'] = $id;
        $input['pricegroupid'] = $detailid;

        $transportationprice->update($input);

        Activity::log('privatetransportationPrice -'.$transportationprice->title.' [Id = '.$transportationprice->id.'] has been Updated');
        return redirect('admin/privatetransportation/'.$id.'/pricegroup/'.$detailid.'/edit');
    }
    public function destroy($id,$detailid,$pricegroupid)
    {
        $data['detail'] = $id;
        $data['detail1'] = $detailid;
        $transportationprice = TransportationPrice::findOrFail($pricegroupid);
        $transportationprice->delete();

        Activity::log('privatetransportationPrice -'.$transportationprice->title.' [Id = '.$transportationprice->id.'] has been deleted');
        return redirect('admin/privatetransportation/'.$id.'/pricegroup/'.$detailid.'/edit');
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
            $transportationprice = TransportationPrice::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $transportationprice->update($input);
        }
        return redirect('admin/privatetransportation/price');
    }
}
