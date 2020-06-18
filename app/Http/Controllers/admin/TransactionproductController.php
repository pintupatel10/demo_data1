<?php

namespace App\Http\Controllers\admin;

use App\Transactionprice;
use App\Transactionproduct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransactionproductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Transaction');

    }
    public function edit($customerid,$id,$orderid)
    {
        $data['mainmenu']="Transaction";
        $data['menu']="Transaction Product";
        $data['customer']=$customerid;
        $data['order'] = $id;
        $data['product'] = Transactionproduct::findOrFail($orderid);
        $data['price'] = Transactionprice::where('customerid',$id)->get();
        $data['modes_selected'] = explode(",",$data['product']['title']);
        return view('admin.transaction.product.edit',$data);
    }

    public function update(Request $request,$customerid, $id,$orderid)
    {
        $this->validate($request, [
           // 'email' => 'required',
            //'email' => 'required',
        ]);
        $data['customer']=$customerid;
        $product = Transactionproduct::findOrFail($orderid);
        $input = $request->all();
        $input['order'] = $id;
        $product->update($input);

        return redirect('admin/transaction/'.$customerid.'/order/'.$id.'/edit');
    }
}
