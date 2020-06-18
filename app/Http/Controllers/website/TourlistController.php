<?php

namespace App\Http\Controllers\website;

use App\Coupon;
use App\Helpers\CalendarHelper;
use App\Sitelogo;
use App\Staticpage;
use App\Tourcheckpoint;
use App\Tourcollection;
use App\TourFilter;
use App\TourGroup;
use App\TourList;
use App\TourPrice;
use App\TourPricegroup;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TourlistController extends Controller
{
    public function index(Request $request, $cid)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Tour";
        $data['menuid'] = $cid;
        $data['tour'] = Tourcollection::where('status', 'active')->where('id', $cid)->where('language', '=', $cookie)->orderBy('displayorder', 'ASC')->first();
        $data['tourfilter'] = TourFilter::where('status', 'active')->where('cid', $cid)->orderBy('displayorder', 'ASC')->get();
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();

        if(empty($data['tour'])){
            abort(404);
        }
        
        return view('website.tour.tour', $data);
    }

    public function showfull(Request $request, $cid, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Tour";
        $data['menuid'] = $cid;
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['tour'] = TourList::with(['Tourhighlight', 'TourPricegroup', 'TourPrice'])->where('id', $id)->where('status', 'active')->first();

        $data['TourPricegroup'] = TourPricegroup::where('detailid', $id)->where('status', 'active')->get();

        if(empty($data['tour'])){
            abort(404);
        }

        $data['checkpoints'] = Tourcheckpoint::where('detailid', $id)->where('status', 'active')->get();
        return view('website.tour.fulldetail', $data);
    }

    public function showsimple(Request $request, $cid, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Tour";
        $data['menuid'] = $cid;
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['tour'] = TourList::with(['Tourhighlight', 'TourPricegroup', 'TourPrice'])->where('id', $id)->where('status', 'active')->first();

        $data['TourPricegroup'] = TourPricegroup::where('detailid', $id)->where('status', 'active')->get();

        if(empty($data['tour'])){
            abort(404);
        }

        $data['checkpoints'] = Tourcheckpoint::where('detailid', $id)->where('status', 'active')->get();
        return view('website.tour.simpledetail', $data);
    }

    public function showgroup(Request $request, $cid, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Tour";
        $data['menuid'] = $cid;
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['group'] = TourGroup::where('status', 'active')->where('id', $id)->first();

        if(empty($data['group'])){
            abort(404);
        }

        return view('website.tour.grouplist', $data);
    }
}