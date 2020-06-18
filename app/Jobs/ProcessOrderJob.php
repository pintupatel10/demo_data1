<?php

namespace App\Jobs;

use App\Emailset;
use App\Helpers\DisneylandHelper;
use App\Helpers\DisneylandTicketHelper;
use App\Helpers\OceanParkTicketHelper;
use App\Helpers\TurbojetTicketHelper;
use App\Jobs\Job;
use App\Order;
use App\OrderProduct;
use App\TicketList;
use App\TourList;
use App\TransportationList;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessOrderJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $order_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::find($this->order_id);
        if (!$order || $order->{Order::COLUMN_STATUS} != Order::STATUS_IN_QUEUE)
            return;

        \App::setLocale($order->{Order::COLUMN_LANGUAGE});

        $attachments = [];

        // Reserve Disneyland and Oceanpark Ticket
        foreach ($order->products as $order_product)
        {
            if ($order_product->{OrderProduct::COLUMN_TYPE} == OrderProduct::TYPE_TOUR)
                $product = TourList::find($order_product->{OrderProduct::COLUMN_PRODUCT_ID});
            else if ($order_product->{OrderProduct::COLUMN_TYPE} == OrderProduct::TYPE_TICKET)
                $product = TicketList::find($order_product->{OrderProduct::COLUMN_PRODUCT_ID});
            else
                continue;

            if ($product && $product->isDisneylandType())
            {
                $reservations = DisneylandTicketHelper::reserveTicket($order_product);
                foreach ($reservations as $reservation)
                {
                    $attachments[] = [
                        'path' => $reservation->getLetterStoragePath(),
                        'name' => $reservation->getAttachmentName(),
                    ];
                }
            }

            if ($product && $product->isOceanParkType())
            {
                $reservations = OceanParkTicketHelper::reserveTicket($order_product);
                foreach ($reservations as $reservation)
                {
                    $attachments[] = [
                        'path' => $reservation->getLetterStoragePath(),
                        'name' => $reservation->getAttachmentName(),
                    ];
                }
            }
        }

        // Reserve Turbojet Tickets
        foreach ($order->products as $order_product)
        {
            if ($order_product->{OrderProduct::COLUMN_TYPE} == OrderProduct::TYPE_TRANSPORTATION)
                $product = TransportationList::find($order_product->{OrderProduct::COLUMN_PRODUCT_ID});
            else if ($order_product->{OrderProduct::COLUMN_TYPE} == OrderProduct::TYPE_COMBO)
                $product = TourList::find($order_product->{OrderProduct::COLUMN_PRODUCT_ID});
            else
                continue;

            if (!$product->isTurbojetType())
                continue;

            $reservation = TurbojetTicketHelper::reserve($order_product);
            if ($reservation)
            {
                $attachments[] = [
                    'path' => $reservation->getLetterStoragePath(),
                    'name' => $reservation->getAttachmentName(),
                ];
            }
        }

        // Send emails to Garyline
        $emails = collect();
        foreach ($order->products as $order_product)
        {
            $diff_hours = Carbon::now()->diffInHours($order_product->{OrderProduct::COLUMN_DATE});
            $email_type = "";

            if ($diff_hours < 48)
            {
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "tour")              $email_type = Emailset::Type_Tour_within_48;
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "transportation")    $email_type = Emailset::Type_Transportation_within_48;
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "ticket")            $email_type = Emailset::Type_Ticket_within_48;
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "combo")             $email_type = Emailset::Type_Combo_within_48;
            }
            else
            {
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "tour")              $email_type = Emailset::Type_Tour_outof_48;
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "transportation")    $email_type = Emailset::Type_Transportation_outof_48;
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "ticket")            $email_type = Emailset::Type_Ticket_outof_48;
                if ($order_product->{OrderProduct::COLUMN_TYPE} == "combo")             $email_type = Emailset::Type_Combo_outof_48;
            }

            $email_set = Emailset::where('type', $email_type)->first();
            if ($email_set) {
                $emails = $emails->merge($email_set->MailAddress->pluck('mail_address'));
            }

            // Update order product status
            $order_product->{OrderProduct::COLUMN_STATUS} = OrderProduct::STATUS_PENDING;
            $order_product->save();
        }

        // Send emails to customer
        $emails = $emails->merge($order->products()->pluck(OrderProduct::COLUMN_EMAIL)->unique());
        foreach ($emails->unique() as $email)
        {
            \Mail::send('website.cart.email', compact('order'), function ($m) use ($email, $order, $attachments) {
                foreach ($attachments as $attachment)
                {
                    $m->attach($attachment['path'], ['as' => $attachment['name']]);
                }

                $m->to($email)->subject("[Order $order->id] " . trans('email.subject'));
            });
        }

        // Update order status
        $order->{Order::COLUMN_STATUS} = Order::STATUS_PENDING;
        $order->save();
    }
}
