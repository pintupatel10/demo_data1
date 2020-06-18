<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Images;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Image Center');
    }
    public function index()
    {
        $data['mainmenu']="Image Center";
        $data['menu']="Image Center";
        $data['images']=Images::Paginate($this->pagination);
        return view('admin.images.index',$data);
    }

    public function preview(){
        $data['mainmenu']="Image Center";
        $data['menu']="Image Center";
        $data['images']=Images::get();
        return view('admin.images.preview',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="Image Center";
        $data['menu']="Image Center";
        return view('admin.images.create',$data);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
           // 'images' => 'required|mimes:jpeg,jpg,bmp,png',
        ]);
        $count= $request['cnt'];
        for($i=0;$i<$count;$i++) {

            if($photo = $request->file('images')[$i])
            {
                $root = base_path().'/public/resource/images/';
                $name = str_random(20).".".$photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/".$name;
                ImageHelper::generateThumbnail($image_path);
                $photo->move($root,$name);
                $input['image'] = $image_path;
                $image=Images::create($input);
            }
        }
        if($count>=1) {

            Activity::log('Images [Id = '.$image->id.'] has been inserted');
            \Session::flash('success','Images has been inserted successfully!');
        }
        return redirect('admin/images');
    }
    public function edit($id)
    {
        $data['mainmenu']="Image Center";
        $data['menu']="Image Center";
        $data['image'] = Images::findOrFail($id);
        return view('admin/images//edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,bmp,png',
        ]);
        $image = Images::findOrFail($id);

        $sp = explode('/',$image['image']);

        if($photo = $request->file('image'))
        {
            if(file_exists($image->image)) {
                unlink($image->image);
            }

            $root = base_path().'/public/resource/images/';
            $name1 = $sp[2];

            if (!file_exists($root)) {
                mkdir($root, 0777, true);
            }
            $image_path = "resource/images/".$name1;
            ImageHelper::generateThumbnail($image_path);
            $photo->move($root,$name1);
            $input['image'] = $image_path;
        }
        $image->update($input);

        Activity::log('Images [Id = '.$image->id.'] has been Updated');
        \Session::flash('success','Images has been Updated successfully!');
        return redirect('admin/images');
    }

    public function destroy($id)
    {
        $image = Images::findOrFail($id);
        $image->delete();

        Activity::log('Images [Id = '.$image->id.'] has been deleted');
        \Session::flash('danger','Images has been deleted successfully!');
        return redirect('admin/images');
    }

}
