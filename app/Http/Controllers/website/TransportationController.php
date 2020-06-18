<?php

namespace App\Http\Controllers\website;

use App\Helpers\CalendarHelper;
use App\Http\Controllers\Controller;
use App\Sitelogo;
use App\Staticpage;
use App\TransportationCheckpoint;
use App\Transportationcollection;
use App\TransportationFilter;
use App\TransportationGroup;
use App\TransportationList;
use App\TransportationPrice;
use App\TransportationPricegroup;
use App\TransportationQuota;
use App\TransportationTimetable;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class TransportationController extends Controller
{
    public function index(Request $request,$cid)
    {
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Transportation";
        $data['menuid']=$cid;
         /*$data['transportation'] = Transportationcollection::with('TransportationFilter')->where('status','active')->where('language','=',$cookie)
            ->orderBy('displayorder','ASC')->get();*/
       // return $data['transportation']['TransportationFilter'];
        $data['transportation']=Transportationcollection::where('id',$cid)->where('status','active')->where('language','=',$cookie)->first();
        $data['TransportationFilter']=TransportationFilter::where('cid',$cid)->where('status','active')->orderBy('displayorder','ASC')->get();
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();

        if(empty($data['transportation'])){
            abort(404);
        }

        return view('website.transportation.transportation',$data);
    }

    public function showfull(Request $request,$cid,$id){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Transportation";
        $data['menuid']=$cid;
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
          $data['transportation']=TransportationList::with(['Transportationhighlight'])->where('id',$id)->where('status','active')->first();
        $data['checkpoints']=TransportationCheckpoint::where('detailid',$id)->where('status', 'active')->get();

        $data['TransportationPricegroup']=TransportationPricegroup::where('detailid',$id)->where('status', 'active')->get();

//        if(!empty($data['transportation']) && $data['transportation']->display=="Simplified"){
//            return view('website.transportation.simpledetail',$data);
//        }
        if(empty($data['transportation'])){
            abort(404);
        }
        return view('website.transportation.fulldetail',$data);
    }

    public function showsimple(Request $request,$cid,$id){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Transportation";
        $data['menuid']=$cid;
        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();

        $data['transportation']=TransportationList::with(['Transportationhighlight','TransportationPricegroup.TransportationPrice'])->where('id',$id)->where('status','active')->first();
        $data['checkpoints']=TransportationCheckpoint::where('detailid',$id)->where('status', 'active')->get();

        $data['TransportationPricegroup']=TransportationPricegroup::where('detailid',$id)->where('status', 'active')->get();

//        if(!empty($data['transportation']) && $data['transportation']->display=="Simplified"){
//            return view('website.transportation.simpledetail',$data);
//        }

        if(empty($data['transportation'])){
            abort(404);
        }

        return view('website.transportation.simpledetail',$data);
    }

    public function showgroup(Request $request,$cid,$id){
        $cookie = $request->cookie('language');
        if(empty($cookie) && $cookie==""){
            $cookie = "English";
        }
        $data['cookie']=$cookie;
        $data['menuactive']="Transportation";
        $data['menuid']=$cid;

        $data['sitelogo'] = Sitelogo::where('status','active')->where('language','=',$cookie)->get();
         $data['group']=TransportationGroup::where('id',$id)->where('status','active')->first();

        if(empty($data['group'])){
            abort(404);
        }
        
        return view('website.transportation.grouplist',$data);
    }
}
