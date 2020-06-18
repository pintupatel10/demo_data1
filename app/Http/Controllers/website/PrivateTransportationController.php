<?php

namespace App\Http\Controllers\website;

use App\Sitelogo;
use App\TransportationCheckpoint;
use App\TransportationList;
use App\TransportationPricegroup;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrivateTransportationController extends Controller
{
    public function showfull(Request $request, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "PrivateTransportation";
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['transportation'] = TransportationList::with(['TransportationPricegroup', 'TransportationPrice'])->where('post','=','Private')->where('id', $id)->where('status', 'active')->first();
        $data['checkpoints']=TransportationCheckpoint::where('detailid',$id)->where('status', 'active')->get();

        $data['TransportationPricegroup'] = TransportationPricegroup::where('detailid', $id)->where('status', 'active')->get();

        if(empty($data['transportation']) || $data['transportation']->payment_status != TransportationList::Payment_STATUS_NOTPAID){
            abort(404);
        }

        return view('website.privatetransportation.fulldetail', $data);
    }
}
