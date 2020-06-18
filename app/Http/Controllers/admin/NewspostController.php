<?php

namespace App\Http\Controllers\admin;

use App\Helpers\ImageHelper;
use App\Images;
use App\Newspost;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NewspostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access News');

    }

    public function index(Request $request)
    {
        $data['mainmenu']="News";
        $data['menu']="News Post";
        $data['filter'] = $request['language'];
        if($request['language']=="English" || $request['language']=="繁中" || $request['language']=="簡"){

            $data['post']=Newspost::where('language',$request['language'])->Paginate($this->pagination);
        }
        else{
            $data['post']=Newspost::orderBy('displayorder','ASC')->Paginate($this->pagination);
        }

        return view('admin.news.post.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu']="News";
        $data['menu']="News Post";
        $data['image_center'] = Images::get();
        return view('admin.news.post.create',$data);
    }
    public function store(Request $request)
    {
        $image = new Newspost($request->all());
        $this->validate($request, [
            'title' => 'required',
            'language' => 'required',
            //'image_upload' => 'required|mimes:jpeg,jpg,bmp,png',
            'description' => 'required',
            'status' => 'required',
        ]);

        /* ADD DISPLAY ORDER */
        $count_display_order = Newspost::count();
        if($count_display_order >= 0)
        {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $image->displayorder = $c;
        /*---------------------*/

        $image->title = $request->title;
        if(!empty($image->language))
        {
            $image->language=implode(',',$image->language);
        }

        if(!empty($request->image_name))
        {
            $image->image_upload = $request->image_name;
        }
        else {
            if($photo = $request->file('image_upload'))
            {
                $root = base_path() . '/public/resource/images/' ;
                $name = str_random(20).".".$photo->getClientOriginalExtension();
                if (!file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                $image_path = "resource/images/".$name;
                ImageHelper::generateThumbnail($image_path);

                $photo->move($root,$name);
                $image['image_upload'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
            else{
                return back()->withInput()->withErrors(['image' => 'please select image']);
            }
        }
        $image->description = $request->description;
        $image->status = $request->status;
        $image->save();


        Activity::log('Newspost -'.$image->title.' [Id = '.$image->id.'] has been inserted');
        \Session::flash('success', 'Newspost has been inserted successfully!');
        return redirect('admin/news/post');
    }

    public function edit($id)
    {
        $data['mainmenu']="News";
        $data['menu']="News Post";
        $data['post'] = Newspost::findOrFail($id);
        $data['image_center'] = Images::get();
        $data['modes_selected'] = explode(",",$data['post']['language']);
        return view('admin.news.post.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'language' => 'required',
            //'image_upload' => 'mimes:jpeg,jpg,bmp,png',
            'description' => 'required',
            'status' => 'required',
        ]);


        $post = Newspost::findOrFail($id);
        $input = $request->all();

        if(!empty($request->image_name))
        {
            $input['image_upload'] = $request->image_name;
        }
        else {
            if ($photo = $request->file('image_upload')) {
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
                $input['image_upload'] = $image_path;

                $new['image'] = $image_path;
                $i=Images::create($new);
            }
        }
        $input['language'] = implode(',',$request->language);
        $post->update($input);

        Activity::log('Newspost -'.$post->title.' [Id = '.$post->id.'] has been Updated');
        \Session::flash('success', 'Newspost has been Updated successfully!');
        return redirect('admin/news/post');
        //$url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);

    }
    public function destroy($id)
    {
        $post = Newspost::findOrFail($id);
        $post->delete();

        Activity::log('Newspost -'.$post->title.' [Id = '.$post->id.'] has been deleted');
        \Session::flash('danger','Newspost has been deleted successfully!');
        return redirect('admin/news/post');
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
            $post = Newspost::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $post->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/news/post');
    }

    public function assign(Request $request)
    {
        $post = Newspost::findorFail($request['id']);
        $post['status']="active";
        $post->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $post = Newspost::findorFail($request['id']);
        $post['status']="inactive";
        $post->update($request->all());
        return $request['id'];
    }

    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $post = Newspost::findOrFail($idval);
                $input['displayorder'] = $count;
                $post->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }
}
