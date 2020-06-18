<?php

namespace App\Http\Controllers\website;

use App\Helpers\CalendarHelper;
use App\Sitelogo;
use App\Staticpage;
use App\Ticketcheckpoint;
use App\Ticketcollection;
use App\TicketFilter;
use App\TicketGroup;
use App\TicketList;
use App\TicketPrice;
use App\TicketPricegroup;
use App\TicketVolume;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function index(Request $request, $cid)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Ticket";
        $data['menuid'] = $cid;
        $data['ticket'] = Ticketcollection::where('id', $cid)->where('status', 'active')->where('language','=',$cookie)->first();
        $data['ticketfilter'] = TicketFilter::where('cid', $cid)->where('status', 'active')->orderBy('displayorder', 'ASC')->get();
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();

        if(empty($data['ticket'])){
            abort(404);
        }

        return view('website.ticket.ticket', $data);
    }

    public function showfull(Request $request, $cid, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Ticket";
        $data['menuid'] = $cid;
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['ticket'] = TicketList::with(['Tickethighlight'])->where('id', $id)->where('status', 'active')->first();
        $data['checkpoints'] = Ticketcheckpoint::where('detailid', $id)->where('status', 'active')->get();

        $data['TicketPricegroup']=TicketPricegroup::where('detailid',$id)->where('status', 'active')->get();

        if(empty($data['ticket'])){
            abort(404);
        }

        return view('website.ticket.fulldetail', $data);
    }

    public function showsimple(Request $request, $cid, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Ticket";
        $data['menuid'] = $cid;
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();

        $data['ticket'] = TicketList::with(['Tickethighlight', 'TicketPricegroup.TicketPrice'])->where('id', $id)->where('status', 'active')->first();
        $data['checkpoints'] = Ticketcheckpoint::where('detailid', $id)->where('status', 'active')->get();

        $data['TicketPricegroup']=TicketPricegroup::where('detailid',$id)->where('status', 'active')->get();

        if(empty($data['ticket'])){
            abort(404);
        }

        return view('website.ticket.simpledetail', $data);
    }

    public function showgroup(Request $request, $cid, $id)
    {
        $cookie = $request->cookie('language');
        if (empty($cookie) && $cookie == "") {
            $cookie = "English";
        }
        $data['cookie'] = $cookie;
        $data['menuactive'] = "Ticket";
        $data['menuid'] = $cid;
        $data['sitelogo'] = Sitelogo::where('status', 'active')->where('language', '=', $cookie)->get();
        $data['group'] = TicketGroup::where('id', $id)->where('status', 'active')->first();
        
        if(empty($data['group'])){
            abort(404);
        }

        return view('website.ticket.grouplist', $data);
    }
}