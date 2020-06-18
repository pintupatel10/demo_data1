<?php

namespace App\Http\Controllers\admin;

use App\Quota;
use App\TicketPrice;
use App\TicketQuota;
use App\TourPrice;
use App\Transactioncustomer;
use App\Transactionorder;
use App\Transactionproduct;
use App\TransportationPrice;
use App\TransportationQuota;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransactionorderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transaction');

    }

    public function index()
    {
        $data['mainmenu']="Transaction";
        $data['menu']="Transaction Customer";
        $data['order']=Transactionorder::orderBy('id','ASC')->get();
       // $data['product']= Transactionproduct::all();
        return view('admin.transaction.order.index',$data);
    }

    public function edit($id,$customerid)
    {
        $data['mainmenu']="Transaction";
        $data['menu']="Transaction Customer";
        $data['detail'] = $id;
        $data['order'] = Transactioncustomer::findOrFail($customerid);
        $data['product']=$data['order']->Transactionproduct()->get();
        $data['price']=$data['order']->Transactionprice()->get();
        return view('admin.transaction.order.edit',$data);
    }
    public function update(Request $request,$order,$id)
    {

        $this->validate($request, [
            // 'email' => 'required|email',

        ]);

        /* this code is for updating status of product*/
        $order1 = Transactionproduct::with('TransactionPrice')->findOrFail($id);

//        $orderlist = Transactionproduct::with('TransactionPrice')->findOrFail($id);
//        $input['status']=$request['status'];
//        $orderlist->update($input);
        /*update quota */
        if($order1->status != 'Cancelled') {
            foreach ($order1['TransactionPrice'] as $trprice) {
                $type = $trprice['type'];
                $quotatype = $trprice['price_type'];
                $pid = $trprice['price_id'];
                $qty = $trprice['qty'];
                if ($type == 'tour') {
                    if ($quotatype == 'df') {

                        $price = TourPrice::findorFail($pid);
                        $avlquota = $price->dquota;
                        if ($avlquota != -1 || $avlquota != '') {
                            $newquota = $avlquota + $qty;
                            $check['dquota'] = $newquota;
                            $price->update($check);
                        }

                    }
                    if ($quotatype == 'sp') {
                        $spprice = Quota::findorFail($pid);
                        $avlquota = $spprice->quota;
                        if ($avlquota != -1 || $avlquota != '') {
                            $newquota = $avlquota + $qty;
                            $check1['quota'] = $newquota;
                            $spprice->update($check1);
                        }
                    }
                }

                if ($type == 'ticket') {
                    if ($quotatype == 'df') {
                        $price = TicketPrice::findorFail($pid);
                        $avlquota = $price->dquota;
                        if ($avlquota != -1 || $avlquota != '') {
                            $newquota = $avlquota + $qty;
                            $check['dquota'] = $newquota;
                            $price->update($check);
                        }
                    }
                    if ($quotatype == 'sp') {
                        $spprice = TicketQuota::findorFail($pid);
                        $avlquota = $spprice->quota;
                        if ($avlquota != -1 || $avlquota != '') {
                            $newquota = $avlquota + $qty;
                            $check1['quota'] = $newquota;
                            $spprice->update($check1);
                        }
                    }
                }
                if ($type == 'transportation') {
                    if ($quotatype == 'df') {
                        $price = TransportationPrice::findorFail($pid);
                        $avlquota = $price->dquota;
                        if ($avlquota != -1 || $avlquota != '') {
                            $newquota = $avlquota + $qty;
                            $check['dquota'] = $newquota;
                            $price->update($check);
                        }
                    }
                    if ($quotatype == 'sp') {
                        $spprice = TransportationQuota::findorFail($pid);
                        $avlquota = $spprice->quota;
                        if ($avlquota != -1 || $avlquota != '') {
                            $newquota = $avlquota + $qty;
                            $check1['quota'] = $newquota;
                            $spprice->update($check1);
                        }
                    }
                }
            }
        }
        /*update quota end */
        $input['status']=$request['status'];
        $order1->update($input);

        if($order1->status == 'Confirm') {
            $filepath = "";
            if ($file = $request->file('attachment')) {
                $root = base_path() . '/public/resource/Attachment/';
                $name1 = str_random(20) . "." . $file->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $filepath = "resource/Attachment/" . $name1;
                $file->move($root, $name1);
            }
            $uid = md5(uniqid(time()));
            $sender_email = 'info@grayline.com.hk';
            $sender_name = 'Gray Line Tours of Hong Kong';
            $to = $request['email'];
            $sub = "Order Confirmation";
            $msg = $request['content'];
// header
            $header = "From: " . $sender_name . " <" . $sender_email . ">\r\n";
            $header .= "Reply-To: $sender_name <$sender_email>\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";

// message & attachment
            $nmessage = "--" . $uid . "\r\n";
            $nmessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
            $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $nmessage .= $msg . "\r\n\r\n";
            $nmessage .= "--" . $uid . "\r\n";
            if ($request->file('attachment')) {
                $file = $filepath;
                $content = file_get_contents($file);
                $content = chunk_split(base64_encode($content));
                $name = basename($file);

                $nmessage .= "Content-Type: application/octet-stream; name=\"" . $name . "\"\r\n";
                $nmessage .= "Content-Transfer-Encoding: base64\r\n";
                $nmessage .= "Content-Disposition: attachment; filename=\"" . $name . "\"\r\n\r\n";
                $nmessage .= $content . "\r\n\r\n";
            }
            $nmessage .= "--" . $uid . "--";
            mail($to, $sub, $nmessage, $header);
            /*
            $sender_email='info@grayline.com.hk';
            $sender_name='Gray Line Tours of Hong Kong';
            $to=$request['email'];
            $sub="Order Confirmation";
            $msg=$request['content'];
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "From: $sender_name <$sender_email>\r\n";
            $headers .= "Reply-To: $sender_name <$sender_email>\r\n";
            $success=mail($to,$sub,$msg,$headers);
            */
            if ($filepath != "" && file_exists($filepath)) {
                unlink($filepath);
            }
        }
        return redirect()->back();
    }
    public function destroy($customerid,$id)
    {
        $order = Transactionorder::findOrFail($id);
        $order->delete();
        \Session::flash('danger','order has been deleted successfully!');
        return redirect('admin/transaction/customer/'.$customerid.'/edit');
    }
}
