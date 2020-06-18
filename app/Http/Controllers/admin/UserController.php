<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Image;
use App\User;
use Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;


class UserController extends Controller
{
    public function __construct()
    {
    $this->middleware('auth');
    $this->middleware('role');
    }

    public function index()
    {
        $data['mainmenu']="Users";
        $data['menu']="User";
        $data['article']=User::OrderBy('id','DESC')->Paginate($this->pagination);
      //  return view('admin.users.index',$data);
        return redirect('admin/dashboard');
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Users";
        $data['menu']="User";
        return view('admin.users.create',$data);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'confirmed',
            'status' => 'required',
        ]);

        $article= User::create($request->all());
       // return $article;

        Activity::log('User [Id = '.$article->id.'] has been inserted');
        \Session::flash('success', 'User has been inserted successfully!');
        return redirect('admin/users');
    }

    public function edit($id)
    {
        $data['mainmenu']="Users";
        $data['menu']="User";
        $data['article'] = User::findOrFail($id);
        return view('admin.users.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'confirmed',
            'status' => 'required',
        ]);

        $article = User::findOrFail($id);
        $input = $request->all();

        $article->update($input);

        Activity::log('User [Id = '.$article->id.'] has been Updated');
        \Session::flash('success', 'User has been Updated successfully!');
        return redirect('admin/users/'.$id.'/edit');
    }
    public function destroy($id)
    {
        $article = \App\User::findOrFail($id);
        $article->delete();

        Activity::log('User [Id = '.$article->id.'] has been deleted');
        \Session::flash('danger','User has been deleted successfully!');
        return redirect('/admin/users');
    }
}
