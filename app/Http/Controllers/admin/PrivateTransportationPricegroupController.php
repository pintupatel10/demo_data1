<?php

namespace App\Http\Controllers\admin;

use App\TransportationPricegroup;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrivateTransportationPricegroupController extends Controller
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
        $data['menu']="Private Transportation pricegroup";
        $data['pricegroup']=TransportationPricegroup::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.privatetransportation.pricegroup.index',$data);
    }

    public function create($detailid)
    {
        $data=[];
        $data['mainmenu']="Private Transportation";
        $data['menu']="Private Transportation pricegroup";
        $data['detail']=$detailid;
        return view('admin.privatetransportation.pricegroup.create',$data);
    }
    public function store(Request $request,$detailid)
    {
        $image = new TransportationPricegroup($request->all());

        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TransportationPricegroup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/
        $image['detailid'] = $detailid;
        $image->save();

        Activity::log('PrivateTransportationPricegroup -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        return redirect('admin/privatetransportation/privatetransportationlist/'.$detailid.'/edit');
    }

    public function edit($id,$detailid)
    {
        $data['mainmenu']="Private Transportation";
        $data['menu']="Private Transportation pricegroup";
        $data['detail'] = $id;
        $data['pricegroup'] = TransportationPricegroup::findOrFail($detailid);
        $data['transportationprice']=$data['pricegroup']->TransportationPrice()->get();
        return view('admin.privatetransportation.pricegroup.edit',$data);
    }

    public function update(Request $request, $id,$detailid)
    {
        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);


        $pricegroup = TransportationPricegroup::findOrFail($detailid);
        $input = $request->all();
        $input['detailid'] = $id;
        $pricegroup->update($input);

        Activity::log('PrivateTransportationPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been Updated');
        return redirect('admin/privatetransportation/privatetransportationlist/'.$id.'/edit');

    }
    public function destroy($id,$detailid)
    {
        $data['detail'] = $id;
        $pricegroup = TransportationPricegroup::findOrFail($detailid);
        $pricegroup->delete();

        Activity::log('PrivateTransportationPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been deleted');
        return redirect('admin/privatetransportation/privatetransportationlist/'.$id.'/edit');
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
            $pricegroup = TransportationPricegroup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $pricegroup->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/privatetransportation/pricegroup');
    }
}
