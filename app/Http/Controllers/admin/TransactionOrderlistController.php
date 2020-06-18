<?php

namespace App\Http\Controllers\admin;

use App\Quota;
use App\TicketPrice;
use App\TicketQuota;
use App\TourPrice;
use App\Transactionorder;
use App\TransactionOrderlist;
use App\Transactionprice;
use App\Transactionproduct;
use App\TransactionProductlist;
use App\TransportationPrice;
use App\TransportationQuota;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransactionOrderlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transaction');

    }

    public function index(Request $request)
    {

        $data['mainmenu']="Transaction";
        $data['menu']="Transaction OrderList";
        $data['filter'] = $request['post'];
        if($request['post']=="Public" || $request['post']=="Private"){
            $data['orderlist']=Transactionproduct::with('Transactionorder')->where('post',$request['post'])->orderBy('id','ASC')->get();
            // $data['orderlist']=TransactionProductlist::where('post',$request['post'])->get();
        }
        else{
            // $data['orderlist']=TransactionOrderlist::orderBy('id','ASC')->get();
            $data['orderlist']=Transactionproduct::with('Transactionorder')->get();
        }
        //$data['orderlist']=Transactionorder::orderBy('id','ASC')->get();
        return view('admin.transaction.orderlist.index',$data);
    }

    public function edit($id)
    {
        $data['mainmenu']="Transaction";
        $data['menu']="Transaction OrderList";
        $data['orderlist'] = Transactionorder::find($id);
       // $data['price'] = Transactionprice::where('id',$id)->get();
        $data['product']=$data['orderlist']->Transactionproduct()->get();
        $data['price']=$data['orderlist']->Transactionprice()->get();
        $data['history']=$data['orderlist']->Transactionchangehistory()->get();
        return view('admin.transaction.orderlist.edit',$data);
    }

    public function up(Request $request,$id)
    {

        $orderlist1 = Transactionorder::find($id);
        $input1 = $request->all();
        if(isset($orderlist1)){
            $orderlist1->update($input1);
        }
        return redirect()->back();
    }

    public function update(Request $request,$id)
    {

        $this->validate($request, [
            // 'email' => 'required|email',

        ]);
        /* this code is for updating status of productlist*/

        $orderlist = Transactionproduct::with('TransactionPrice')->findOrFail($id);

        /*update quota */
        if($orderlist->status != 'Cancelled') {
            foreach ($orderlist['TransactionPrice'] as $trprice) {
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
        $orderlist->update($input);

       if($orderlist->status == 'Confirm'){


        $filepath="";
        if($file = $request->file('attachment')){
            $root = base_path() . '/public/resource/Attachment/';
            $name1 = str_random(20).".".$file->getClientOriginalExtension();
            if (!file_exists($root)) {
                mkdir($root, 0777, true);
            }
            $filepath = "resource/Attachment/".$name1;
            $file->move($root,$name1);
        }
        $uid = md5(uniqid(time()));
        $sender_email='info@grayline.com.hk';
        $sender_name='Gray Line Tours of Hong Kong';
        $to=$request['email'];
        $sub="Order Confirmation";
        $msg=$request['content'];
// header
        $header = "From: ".$sender_name." <".$sender_email.">\r\n";
        $header .= "Reply-To: $sender_name <$sender_email>\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

// message & attachment
        $nmessage = "--".$uid."\r\n";
        $nmessage .= "Content-type:text/html; charset=iso-8859-1\r\n";
        $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $nmessage .= $msg."\r\n\r\n";
        $nmessage .= "--".$uid."\r\n";
        if($request->file('attachment')) {
            $file = $filepath;
            $content = file_get_contents($file);
            $content = chunk_split(base64_encode($content));
            $name = basename($file);

            $nmessage .= "Content-Type: application/octet-stream; name=\"" . $name . "\"\r\n";
            $nmessage .= "Content-Transfer-Encoding: base64\r\n";
            $nmessage .= "Content-Disposition: attachment; filename=\"" . $name . "\"\r\n\r\n";
            $nmessage .= $content . "\r\n\r\n";
        }
        $nmessage .= "--".$uid."--";
        mail($to, $sub, $nmessage, $header);
        if($filepath != "" && file_exists($filepath)) {
            unlink($filepath);
        }
       }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $orderlist = Transactionorder::findOrFail($id);
        $orderlist->delete();

        \Session::flash('danger','OrderList has been deleted successfully!');
        return redirect('admin/transaction/orderlist');
    }

    public function type(Request $request){
        return "ok";
    }
}
