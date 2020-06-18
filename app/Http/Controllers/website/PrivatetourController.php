<?php

namespace App\Http\Controllers\website;

use App\Sitelogo;
use App\Tourcheckpoint;
use App\TourList;
use App\TourPricegroup;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrivatetourController extends Controller
{
    public function showfull(Request $request, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "PrivateTour";
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['tour'] = TourList::with(['TourPricegroup', 'TourPrice'])->where('post','=','Private')->where('id', $id)->where('status', 'active')->first();
        $data['checkpoints']=TourCheckpoint::where('detailid',$id)->where('status', 'active')->get();

        $data['TourPricegroup'] = TourPricegroup::where('detailid', $id)->where('status', 'active')->get();

        if(empty($data['tour']) || $data['tour']->payment_status != TourList::Payment_STATUS_NOTPAID){
            abort(404);
        }
        
        return view('website.privatetour.fulldetail', $data);
    }
}
