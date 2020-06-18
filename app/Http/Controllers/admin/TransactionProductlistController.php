<?php

namespace App\Http\Controllers\admin;

use App\Transactionchangehistory;
use App\Transactionprice;
use App\Transactionproduct;
use App\TransactionProductlist;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionProductlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transaction');

    }
    public function edit($orderid,$id)
    {

        $data['mainmenu']="Transaction";
        $data['menu']="Transaction ProductList";
         $data['orderlist'] = $orderid;
         $data['productlist'] = Transactionproduct::findOrFail($id);
         $data['price'] = Transactionprice::where('orderid',$orderid)->get();
         $data['history'] = Transactionchangehistory::where('orderid',$orderid)->orderBy('id','DESC')->get();
        $data['modes_selected'] = explode(",",$data['productlist']['title']);
        return view('admin.transaction.productlist.edit',$data);
    }

    public function update(Request $request,$orderid,$id)
    {
        $productlist = Transactionproduct::findOrFail($id);
        $input = $request->all();
        if($productlist->date != $request['date'] || $productlist->time != $request['time']){
            $input['status']="Pending";
            $input1['orderid']=$orderid;
            $input1['productid']=$id;
            $input1['change_from']=$productlist->date." ".$productlist->time;
            $input1['change_to']=$request['date']." ".$request['time'];
            $input1['change_by']=Auth::user()->name;
            $history=Transactionchangehistory::create($input1);
        }
        $input['orderlist'] = $orderid;

        $productlist->update($input);
        return redirect('admin/transaction/orderlist/'.$orderid.'/edit');
    }

    public function add(Request $request,$orderid,$id)
    {
        //return $id.'/'.$orderid;
        $history = Transactionchangehistory::find($id);
        $input2 = $request->all();
        $history->update($input2);
        \Session::flash('success','Remark has been successfully inserted!');
        return redirect()->back();
    }

    public function assign(Request $request)
    {
        
        $productlist = Transactionproduct::findorFail($request['id']);
        $productlist['status']="Pending";
        $productlist->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $productlist = Transactionproduct::findorFail($request['id']);
        $productlist['status']="Confirmed";
        $productlist->update($request->all());
        return $request['id'];
    }
}
