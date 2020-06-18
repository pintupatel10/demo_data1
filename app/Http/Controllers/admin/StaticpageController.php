<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Activity;
use Illuminate\Http\Request;
use App\Staticpage;
use App\Http\Requests;

class StaticpageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access T & C');
    }

    public function edit($id)
    {
        $data['mainmenu']="TERMS & CONDITIONS";
        $data['menu']="TERMS & CONDITIONS";
        $data['id'] = $id;
        $data['staticpage'] = Staticpage::findOrFail($id);
        return view('admin.terms.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $staticpage = Staticpage::findOrFail($id);
        $input = $request->all();
        $staticpage->update($input);

        Activity::log('TERMS & CONDITIONS [Id = '.$staticpage->id.'] has been Updated');
        \Session::flash('success', 'TERMS & CONDITIONS has been Updated successfully!');
        return redirect('admin/terms/'.$id.'/edit');
    }
    
}
