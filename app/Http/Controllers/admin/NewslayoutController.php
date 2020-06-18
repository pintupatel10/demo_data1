<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Images;
use App\Newslayout;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NewslayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access News');


    }


    public function index()
    {
        $data['mainmenu']="News";
        $data['menu']="News Layout";
        //$data['layout']=Newslayout::orderBy('displayorder','DESC')->Paginate($this->pagination);
        $data['layout']=Newslayout::orderBy('id','ASC')->get();
        return view('admin.news.layout.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="News";
        $data['menu']="News Layout";
        $data['image_center'] = Images::get();
        return view('admin.news.layout.create',$data);
    }
    public function store(Request $request)
    {
        $image = new Newslayout($request->all());
        $this->validate($request, [
            'menu_name' => 'required',
            'title' => 'required',
           // 'image' => 'required|mimes:jpeg,jpg,bmp,png',
            'language' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Newslayout::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/

        $image->title = $request->title;

        if(!empty($request->image_name))
        {
            $image->image = $request->image_name;
        }
        else {
                if($photo = $request->file('image'))
                {
                    $root = base_path() . '/public/resource/images/' ;
                    $name = str_random(20).".".$photo->getClientOriginalExtension();
                    if (!file_exists($root)) {
                        mkdir($root, 0777, true);
                    }
                    $image_path = "resource/images/".$name;
                    ImageHelper::generateThumbnail($image_path);
                    $photo->move($root,$name);
                    $image['image'] = $image_path;

                    $new['image'] = $image_path;
                    $i=Images::create($new);
                }
                else{
                    return back()->withInput()->withErrors(['image' => 'please select image']);
                }
        }
        if(!empty($image->language))
        {
            $image->language=implode(',',$image->language);
        }
        $image->status = $request->status;
        $image->save();


        Activity::log('Newslayout -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        \Session::flash('success', 'Newslayout has been inserted successfully!');
        return redirect('admin/news/layout');
    }

    public function edit($id)
    {
        $data['mainmenu']="News";
        $data['menu']="News Layout";
        $data['layout'] = Newslayout::findOrFail($id);
        $data['image_center'] = Images::get();
        $data['modes_selected'] = explode(",",$data['layout']['language']);
        return view('admin.news.layout.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'menu_name' => 'required',
            'title' => 'required',
           // 'image' => 'mimes:jpeg,jpg,bmp,png',
            'language' => 'required',
            'status' => 'required',
        ]);


        $layout = Newslayout::findOrFail($id);
        $input = $request->all();

        if(!empty($request->image_name))
        {
            $input['image'] = $request->image_name;
        }
        else {
            if ($photo = $request->file('image')) {
                $root = base_path() . '/public/resource/images/';
                $name = str_random(20) . "." . $photo->getClientOriginalExtension();
                $mimetype = $photo->getMimeType();
                $explode = explode("/", $mimetype);
                $type = $explode[0];
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $photo->move($root, $name);
                $image_path = "resource/images/" . $name;
                ImageHelper::generateThumbnail($image_path);

                $input['image'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }
        $input['language'] = implode(',',$request->language);
        $layout->update($input);

        Activity::log('Newslayout -'.$layout->title.' [Id = '.$layout->id.'] has been Updated');
        \Session::flash('success', 'Newslayout has been Updated successfully!');
        return redirect('admin/news/layout');
        //$url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);

    }
    public function destroy($id)
    {
        $layout = Newslayout::findOrFail($id);
        $layout->delete();

        Activity::log('Newslayout -'.$layout->title.' [Id = '.$layout->id.'] has been deleted');
        \Session::flash('danger','Newslayout has been deleted successfully!');
        return redirect('admin/news/layout');
    }

    public function update_display_order(Request $request)
    {

        $count1 = $request->count;
        for($i = 1;$i <= $count1;$i++)
        {
            $request->pid1;
            if(isset($request["disp".$i]))
            {
                $disp1 = $request["disp".$i];
                $pid1 = $request["pid".$i];
            }
            $layout = Newslayout::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $layout->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/news/layout');
    }

    public function assign(Request $request)
    {
        $layout = Newslayout::findorFail($request['id']);
        $layout['status']="active";
        $layout->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $layout = Newslayout::findorFail($request['id']);
        $layout['status']="inactive";
        $layout->update($request->all());
        return $request['id'];
    }
}
