<?php

namespace App\Http\Controllers\admin;

use App\Contactemail;
use App\EmailAdvertise;
use App\EmailCollect;
use App\EmailList;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailAdvertiseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Email Advertise');

    }

    public function index()
    {
        $data['mainmenu'] = "EmailAdvertise";
        $data['menu']="EmailAdvertise";
        $data['emailadvertise']=EmailAdvertise::orderBy('created_at','DESC')->get();
        return view('admin.emailadvertise.index',$data);
    }

    public function create()
    {
        $data=[];
        $data['mainmenu'] = "EmailAdvertise";
        $data['menu']="EmailAdvertise";
        $data['emailcollection']=EmailCollect::Paginate($this->pagination);
       // $data['emaillist']=DB::table('contact_emails')->lists('email_receiver','email_receiver');
        //$data['groupname']=DB::table('groups')->where('deleted_at',null)->lists('name','id');
        return view('admin.emailadvertise.create',$data);
    }
    public function store(Request $request)
    {
         $sender_email='karman.fung@grayline.com.hk';
        // $sender_email='info@grayline.com.hk';
        $sender_name='Gray Line Tours of Hong Kong';
        $input=$request->all();
        $this->validate($request, [
        ]);
         $msg=$request['content'];
        $sub=$request['subject'];
        $input['sendingdate'] = Carbon::now();
        $total_email=count($request['email']);
        $input['no_of_mail']=$total_email;
        $emailadvertise = EmailAdvertise::create($input);
        if(!empty($request['email'])){
            foreach ($input['email'] as $key=>$to) {
                $input['email']=$to;
                $input['advertise_id']=$emailadvertise['id'];
                $emaillist=EmailList::create($input);

                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                $headers .= "From: $sender_name <$sender_email>\r\n";
                $headers .= "Reply-To: $sender_name <$sender_email>\r\n";
                $success=mail($to,$sub,$msg,$headers);
            }
        }
               /* Mail::send('admin.emailadvertise.email',$input, function($message) use ($input,$sender_email)
                {
                    $message->to($input['email']);
                    $message->subject($input['subject']);
                    $message->from($sender_email);
                    $message->attach('path_to_pdf_file', array(
                            'as' => 'pdf-report.zip',
                            'mime' => 'application/pdf')
                    );
                });*/


        if($success){
            $emailadvertise['status']='Success';
        }
        else{
            $emailadvertise['status']='Draft';
        }
        $emailadvertise->save();

        Activity::log('EmailAdvertise [Id = '.$emailadvertise->id.'] has been inserted');
        \Session::flash('success', 'EmailAdvertise has been inserted successfully!');
        return redirect('admin/emailadvertise');
    }

    public function edit($id)
    {
        $a=array();
        $data['mainmenu'] = "EmailAdvertise";
        $data['menu']="EmailAdvertise";
        $data['emailadvertise'] = EmailAdvertise::with('EmailList')->findOrFail($id);
        $data['email_sel'] = EmailList::where('advertise_id',$id)->get();
        foreach($data['email_sel'] as $key =>$val){
            $get=EmailCollect::where('email',$val['email'])->get()->toArray();
            //$arr=$a->toArray();
            $a=array_merge($a,$get);
        }
        $final=array_values($a);
        $data['email_selected']=$final;
       //$data['email_selected'] = explode(",",$data['emailadvertise']['email']);
        return view('admin.emailadvertise.edit',$data);
    }
  /*  public function update(Request $request, $id)
    {
        $this->validate($request, [

        ]);

        $emailadvertise = EmailAdvertise::findOrFail($id);
        $input = $request->all();
        //   $input['group_id'] = implode(',',$request->group_id);
        $emailadvertise->update($input);
        \Session::flash('success', 'EmailAdvertise has been Updated successfully!');

        $url = $request->only('redirects_to');
        return redirect()->to($url['redirects_to']);

    }
  */
    public function destroy($id)
    {
        $emailadvertise = EmailAdvertise::findOrFail($id);
        $emailadvertise->delete();
        Activity::log('EmailAdvertise [Id = '.$emailadvertise->id.'] has been deleted');
        \Session::flash('danger','EmailAdvertise has been deleted successfully!');
        return redirect('admin/emailadvertise');
    }

    public function destroyemailcollect($id)
    {
        $emailadvertise = EmailCollect::findOrFail($id);
        $emailadvertise->delete();
        \Session::flash('danger','Email has been deleted successfully!');
        return redirect()->back();
    }

}
