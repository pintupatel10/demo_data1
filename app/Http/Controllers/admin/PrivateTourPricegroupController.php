<?php

namespace App\Http\Controllers\admin;

use App\TourPricegroup;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrivateTourPricegroupController extends Controller
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
        $data['menu']="Private Tour pricegroup";
        $data['pricegroup']=TourPricegroup::orderBy('displayorder','DESC')->Paginate($this->pagination);
        return view('admin.privatetour.pricegroup.index',$data);
    }

    public function create($detailid)
    {
        $data=[];
        $data['mainmenu']="Private Tour";
        $data['menu']="Private Tour pricegroup";
        $data['detail']=$detailid;
        return view('admin.privatetour.pricegroup.create',$data);
    }
    public function store(Request $request,$detailid)
    {
        $image = new TourPricegroup($request->all());

        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = TourPricegroup::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/
        $image['detailid'] = $detailid;
        $image->save();

        Activity::log('PrivateTourPricegroup -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        return redirect('admin/privatetour/privatetourlist/'.$detailid.'/edit');
    }

    public function edit($id,$detailid)
    {
        $data['mainmenu']="Private Tour";
        $data['menu']="Private Tour pricegroup";
        $data['detail'] = $id;
        $data['pricegroup'] = TourPricegroup::findOrFail($detailid);
        $data['tourprice']=$data['pricegroup']->TourPrice()->get();
        return view('admin.privatetour.pricegroup.edit',$data);
    }

    public function update(Request $request, $id,$detailid)
    {
        $this->validate($request, [
            'title' => 'required',
            'report_code' => 'required',
            'status' => 'required',
        ]);


        $pricegroup = TourPricegroup::findOrFail($detailid);
        $input = $request->all();
        $input['detailid'] = $id;
        $pricegroup->update($input);

        Activity::log('PrivateTourPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been Updated');
        return redirect('admin/privatetour/privatetourlist/'.$id.'/edit');

    }
    public function destroy($id,$detailid)
    {
        $data['detail'] = $id;
        $pricegroup = TourPricegroup::findOrFail($detailid);
        $pricegroup->delete();

        Activity::log('PrivateTourPricegroup -'.$pricegroup->title.' [Id = '.$pricegroup->id.'] has been deleted');
        return redirect('admin/privatetour/privatetourlist/'.$id.'/edit');
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
            $pricegroup = TourPricegroup::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $pricegroup->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/privatetour/pricegroup');
    }
}
