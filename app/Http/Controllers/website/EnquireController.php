<?php

namespace App\Http\Controllers\website;

use App\Emailset;
use App\Order;
use App\OrderMessage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EnquireController extends Controller
{
    public function index($language, $enquire_no)
    {
        $order = Order::where(Order::COLUMN_ENQUIRY_NO, $enquire_no)->first();
        if (!$order)
            abort(404);

        \App::setLocale($language);

        return view('website.enquire.index', compact('language', 'enquire_no'));
    }

    public function enquire(Request $request, $language, $enquire_no)
    {
        $this->validate($request, [
            'order-no' => 'required',
            'message' => 'required',
        ]);

        $order = Order::where(Order::COLUMN_ENQUIRY_NO, $enquire_no)->first();
        if (!$order)
            abort(404);

        if ($order->{Order::COLUMN_ID} != $request->input('order-no'))
            return back()->withInput()->withErrors(['order-no' => 'Invalid Order No']);

        $order_message = new OrderMessage();
        $order_message->{OrderMessage::COLUMN_ORDER_ID} = $order->{Order::COLUMN_ID};
        $order_message->{OrderMessage::COLUMN_CONTACT_VIA} = OrderMessage::CONTACT_VIA_WEB;
        $order_message->{OrderMessage::COLUMN_TYPE} = OrderMessage::TYPE_ENQUIRE;
        $order_message->{OrderMessage::COLUMN_MESSAGE} = $request->input('message');
        $order_message->save();

        if ($order->notification_users->count() > 0)
            $emails = $order->notification_users->pluck('email')->unique();
        else {
            $emailset = Emailset::where('type', Emailset::TYPE_DEFAULT_ORDER_NOTIFICATION)->first();
            if ($emailset)
                $emails = $emailset->MailAddress->pluck('mail_address');
            else
                $emails = [];
        }

        foreach ($emails as $email)
        {
            \Mail::send('admin.orders.emails.enquire', compact('order_message'), function ($m) use ($email, $order) {
                $m->to($email)->subject("[Order $order->id] Enquire from Guest");
            });
        }

        $request->session()->flash('success', "Success");
        return redirect("enquire/$language/$enquire_no");
    }
}
