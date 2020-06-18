<?php

namespace App\Http\Controllers\admin;

use App\OceanParkTicket;
use App\TicketPrice;
use App\TourPrice;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OceanParkTicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
    }

    private function hasRight($type)
    {
        if ($type == 'tour')
            $access = 'Access Tour';
        else if ($type == 'ticket')
            $access = 'Access Ticket';
        else
            abort(404);

        $user_group = \Auth::user()->group_id;
        if ($user_group == 0) {
            return true;
        }
        $group = Group::where('id', $user_group)->first();
        if (!empty($group)) {
            $right = explode(',', $group->accessright);
            $check = in_array($access, $right);
            if ($check) {
                return true;
            }
        }
        return false;
    }

    private function getPrice($type, $type_id)
    {
        if (!self::hasRight($type))
            return redirect('admin/dashboard');

        if ($type == 'tour')
            return TourPrice::findOrFail($type_id);
        else if ($type == 'ticket')
            return TicketPrice::findOrFail($type_id);
        else
            abort(404);
    }

    public function create($type, $type_id)
    {
        if (!self::hasRight($type))
            return redirect('admin/dashboard');

        $data=[];
        $data['mainmenu'] = 'Tour';
        $data['menu'] = 'Tour List';
        $data['price_type'] = $type;
        $data['price_id'] = $type_id;
        return view('admin.oceanpark-ticket.create', $data);
    }

    public function store(Request $request, $type, $type_id)
    {
        if (!self::hasRight($type))
            return redirect('admin/dashboard');

        $this->validate($request, [
            'event_id' => 'required',
            'type' => 'required|in:ticket,package',
            'type_id' => 'required',
        ]);

        $ticket = new OceanParkTicket($request->all());
        $price = self::getPrice($type, $type_id);
        $price->oceanpark_tickets()->save($ticket);

        if ($type == 'tour')
            return redirect("admin/tour/$price->detailid/$price->pricegroupid/price/$price->id/edit");
        else
            return redirect("admin/ticket/$price->detailid/$price->pricegroupid/price/$price->id/edit");
    }

    public function show($type, $type_id, $id)
    {
        if (!self::hasRight($type))
            return redirect('admin/dashboard');

        $data=[];
        $data['mainmenu'] = 'Tour';
        $data['menu'] = 'Tour List';
        $data['price_type'] = $type;
        $data['price_id'] = $type_id;
        $data['price'] = self::getPrice($type, $type_id);
        $data['ticket'] = OceanParkTicket::findOrFail($id);
        return view('admin.oceanpark-ticket.edit', $data);
    }

    public function update(Request $request, $type, $type_id, $id)
    {
        if (!self::hasRight($type))
            return redirect('admin/dashboard');

        $this->validate($request, [
            'event_id' => 'required',
            'type' => 'required|in:ticket,package',
            'type_id' => 'required',
        ]);

        $ticket = OceanParkTicket::findOrFail($id);
        $ticket->update($request->all());

        $price = self::getPrice($type, $type_id);

        if ($type == 'tour')
            return redirect("admin/tour/$price->detailid/$price->pricegroupid/price/$price->id/edit");
        else
            return redirect("admin/ticket/$price->detailid/$price->pricegroupid/price/$price->id/edit");
    }

    public function destroy($type, $type_id, $id)
    {
        if (!self::hasRight($type))
            return redirect('admin/dashboard');

        $price = self::getPrice($type, $type_id);
        $ticket = OceanParkTicket::findOrFail($id);
        $ticket->delete();

        if ($type == 'tour')
            return redirect("admin/tour/$price->detailid/$price->pricegroupid/price/$price->id/edit");
        else
            return redirect("admin/ticket/$price->detailid/$price->pricegroupid/price/$price->id/edit");
    }
}
