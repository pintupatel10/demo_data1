<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\OrderMessage;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessOrderMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $order_message_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_message_id)
    {
        $this->order_message_id = $order_message_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_message = OrderMessage::find($this->order_message_id);
        if (!$order_message)
            return;


        if ($order_message->{OrderMessage::COLUMN_TYPE} == OrderMessage::TYPE_ENQUIRE)
        {
            \Mail::send('admin.orders.emails.enquire', compact('order_message'), function ($m) use ($order_message) {
                $m->to("robot@shineway-enterprise.com")->subject("[Order #$order_message->order_id] Enquiry from Guest");
            });

            $order_message->{OrderMessage::COLUMN_NOTIFICATION_SENT_AT} = Carbon::now();
            $order_message->save();
        }
    }
}
