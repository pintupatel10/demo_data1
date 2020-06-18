<?php

namespace App\Http\Controllers\admin;

use App\Transactioncustomer;
use App\Transactionorder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransactioncustomerController extends Controller
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
        $data['menu']="Transaction Customer";

        if($request['from'] && $request['to']){
           // $data['customer']=Transactioncustomer::where('post',$request['post'])->orderBy('id','ASC')->get();
         //return   $data['customer']= Transactioncustomer::whereBetween('created_at', [$request['from']."00:00:00", $request['to']."00:00:00"])->get();
            $data['order']=Transactionorder::orderBy('id','ASC')->get();
            // $data['orderlist']=TransactionProductlist::with('TransactionOrderlist')->where('post',$request['post'])->orderBy('id','ASC')->get();
            // $data['orderlist']=TransactionProductlist::where('post',$request['post'])->get();
        }
        else{
            $data['order']=Transactionorder::orderBy('id','ASC')->get();
        }
        return view('admin.transaction.customer.index',$data);
    }

    public function edit($id)
    {
        $data['mainmenu']="Transaction";
        $data['menu']="Transaction Customer";
//        $data['customer'] = Transactioncustomer::findOrFail($id);
//        $data['order']=$data['customer']->Transactionorder()->get();
//        $data['product']=$data['customer']->Transactionproduct()->get();

        $data['order'] = Transactionorder::findOrFail($id);
        $data['customer']=$data['order']->Transactioncustomer()->get();
        $data['product']=$data['order']->Transactionproduct()->get();
        $data['price']=$data['order']->Transactionprice()->get();
        return view('admin.transaction.customer.edit',$data);
    }
}
