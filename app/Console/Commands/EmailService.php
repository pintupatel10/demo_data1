<?php

namespace App\Console\Commands;

use App\Emailset;
use App\Order;
use App\OrderMessage;
use App\OrderMessageAttachment;
use App\OrderProduct;
use App\User;
use Illuminate\Console\Command;
use PhpImap\Mailbox;
use Storage;

class EmailService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start email service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $transport = Swift_SmtpTransport::newInstance('mail.shineway-enterprise.com', 587, 'tls')
//            ->setUsername('robot@shineway-enterprise.com')
//            ->setPassword('abcd1234');
//
//        $mailer = Swift_Mailer::newInstance($transport);
//
//        $message = Swift_Message::newInstance()
//            ->setTo('matthew@shineway-enterprise.com')
//            ->setFrom('robot@shineway-enterprise.com')
//            ->setSubject('Test Raw Email');
//            //->setBody("Test Body");
//
//        $mime = Swift_MimePart::newInstance("body of the original message\r\n> Quota Test");
//        $mime->setEncoder(new Swift_Mime_ContentEncoder_RawContentEncoder);
//        $message->attach($mime);
//        $mailer->send($message);


        //return;

        if (!Storage::exists('email-attachment'))
            Storage::makeDirectory('email-attachment');

        $temp_dir = storage_path('app/email-attachment');

        $mailbox = new Mailbox("{" . env("MAIL_HOST") .":143/imap/novalidate-cert}INBOX", env("MAIL_USERNAME"), env("MAIL_PASSWORD"), $temp_dir);
        $mail_ids = $mailbox->searchMailbox('UNSEEN');

        $this->info("Unread Message: " . sizeof($mail_ids));

        foreach ($mail_ids as $mail_id)
        {
            try {
                $mail = $mailbox->getMail($mail_id);
                //$mailbox->markMailAsUnread($mail_id);

                // Ignore SPAM
                if (starts_with($mail->subject, "***Spam***"))
                    continue;

                preg_match("/\[Order (GLC-\d*-\d*)\]/", $mail->subject, $re);
                if (sizeof($re) == 2)
                    $order = Order::find($re[1]);
                else
                    $order = null;


                if ($order)
                {
                    \App::setLocale($order->{Order::COLUMN_LANGUAGE});

                    $isFromGuest = stripos($mail->subject, "Enquire from Guest") === false;

                    $content = \EmailReplyParser\EmailReplyParser::parseReply($mail->textPlain);

                    $order_message = new OrderMessage();
                    $order_message->{OrderMessage::COLUMN_ORDER_ID} = $order->{Order::COLUMN_ID};
                    $order_message->{OrderMessage::COLUMN_CONTACT_VIA} = OrderMessage::CONTACT_VIA_EMAIL;
                    $order_message->{OrderMessage::COLUMN_TYPE} = $isFromGuest ? OrderMessage::TYPE_ENQUIRE : OrderMessage::TYPE_REPLY;
                    $order_message->{OrderMessage::COLUMN_MESSAGE} = $content;
                    $order_message->{OrderMessage::COLUMN_FROM_ADDRESS} = $mail->fromAddress;

                    if (!$isFromGuest)
                    {
                        $user = User::where('email', $mail->fromAddress)->first();
                        if ($user)
                            $order_message->{OrderMessage::COLUMN_REPLY_BY} = $user->id;
                    }

                    $order_message->save();

                    $order_message = $order_message->fresh();

                    // Save attachments
                    $attachments = [];
                    foreach ($mail->getAttachments() as $incoming)
                    {
                        $attachment = new OrderMessageAttachment();
                        $attachment->{OrderMessageAttachment::COLUMN_ORDER_MESSAGE_ID} = $order_message->{OrderMessage::COLUMN_ID};
                        $attachment->{OrderMessageAttachment::COLUMN_NAME} = $incoming->name;
                        $attachment->save();

                        // Get the attachment id and then move file to storage
                        $attachment = $attachment->fresh();

                        $attachment_dir = storage_path(OrderMessageAttachment::STORAGE_FOLDER);
                        if (!file_exists($attachment_dir))
                            mkdir($attachment_dir, 0777, true);

                        $attachment_filename = $attachment->{OrderMessageAttachment::COLUMN_ID} . '.' . pathinfo($incoming->name, PATHINFO_EXTENSION);

                        rename($incoming->filePath, $attachment_dir . '/' . $attachment_filename);

                        // Upload the db record
                        $attachment->{OrderMessageAttachment::COLUMN_PATH} = $attachment_filename;
                        $attachment->save();

                        $attachments[] = $attachment;

                    }

                    // Send email
                    if ($isFromGuest)
                    {
                        if ($order->notification_users->count() > 0)
                            $addresses = $order->notification_users->pluck('email')->unique();
                        else {
                            $emailset = Emailset::where('type', Emailset::TYPE_DEFAULT_ORDER_NOTIFICATION)->first();
                            if ($emailset)
                                $addresses = $emailset->MailAddress->pluck('mail_address');
                            else
                                $addresses = [];
                        }
                        $view = "admin.orders.emails.enquire";
                        $subject = "[Order $order->id] Enquire from Guest";
                    }
                    else
                    {
                        $addresses = $order->products()->pluck(OrderProduct::COLUMN_EMAIL)->unique();
                        $view = "admin.orders.emails.reply";
                        $subject = "[Order $order->id] " . trans('email.reply-subject');
                    }

                    foreach ($addresses as $address)
                    {
                        \Mail::send($view, compact('order', 'order_message'), function ($m) use ($address, $order, $subject, $attachments) {
                            foreach ($attachments as $attachment)
                            {
                                $m->attach($attachment->getStoragePath(), ['as' => $attachment->{OrderMessageAttachment::COLUMN_NAME}]);
                            }

                            $m->to($address)->subject($subject);
                        });
                    }

                }
                else
                {
                    // Unclassified message
                    $emailset = Emailset::where('type', Emailset::TYPE_DEFAULT_ORDER_NOTIFICATION)->first();
                    if ($emailset)
                        $addresses = $emailset->MailAddress->pluck('mail_address');
                    else
                        $addresses = [];

                    foreach ($addresses as $address)
                    {
                        \Mail::send('admin.orders.emails.forward', ['original' => $mail], function ($message) use ($mail, $address) {
                            $message->to($address);
                            $message->subject("FWD: " . $mail->subject);
                        });
                    }
                }


            }
            catch(\Exception $e)
            {
                $this->error($e->getMessage());
            }


//            \Mail::send('admin.email.auto-reply', ['original' => $mail], function ($message) use ($mail) {
//                $message->to($mail->fromAddress);
//                $message->subject("Re: " . $mail->subject);
//
////                $headers = $message->getSwiftMessage()->getHeaders();
////                $headers->addTextHeader('References', $mail->messageId);
////                $headers->addTextHeader('In-Reply-To', $mail->messageId);
//
////                dd($message->getSwiftMessage());
////
////                if ($mail->textHtml)
////                    $message->getSwiftMessage()->addPart($mail->textHtml, 'text/html');
////                else if ($mail->textPlain)
////                    $message->getSwiftMessage()->addPart($mail->textPlain, 'text/plain');
//            });
        }

    }
}
