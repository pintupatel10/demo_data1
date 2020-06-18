<?php

namespace App\Http\Controllers\admin;

use App\Contactus;
use App\Homelayout;
use App\Hotelcollection;
use App\Http\Controllers\Controller;
use App\Menu;
use App\MenuType;
use App\Newslayout;
use App\Servicedetail;
use App\ServiceLayout;
use App\Ticketcollection;
use App\Tourcollection;
use App\Transportationcollection;
use Illuminate\Http\Request;
use Activity;
use App\Http\Requests;

class MenuTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role');
        $this->middleware('accessright:Access Site Setup');

    }

    public function index()
    {
        if ($db = Menu::find(1)) {
            return redirect('admin/site/menutype/'.$db->id .'/edit');

        } else {
            return redirect('admin/site/menutype/create');

        }
    }
    /*public function index()
    {
        $data['mainmenu']="Site Setup";
        $data['menu']="MenuType";
        // $data['menutype']=MenuType::orderBy('displayorder','DESC')->Paginate($this->pagination);
        $data['menutype']=Menu::orderBy('id','ASC')->get();
        return view('admin.site.menutype.index',$data);
    }
*/
    public function create()
    {
        $data=[];
        $data['mainmenu']="Site Setup";
        $data['menu']="Menu Control";
        //English;
        $data['home_eng']=Homelayout::where('status','active')->where('language','English')->get();
        $data['news_eng']=Newslayout::where('status','active')->where('language','English')->get();
        $data['contact_eng']=Contactus::where('status','active')->where('language','English')->get();
         $data['service_eng']=ServiceLayout::where('language','English')->get();
         $data['hotel_eng']=Hotelcollection::where('status','active')->where('language','English')->get();
        $data['tour_eng']=Tourcollection::where('status','active')->where('language','English')->get();
        $data['ticket_eng']=Ticketcollection::where('status','active')->where('language','English')->get();
        $data['transportation_eng']=Transportationcollection::where('status','active')->where('language','English')->get();
        //traditional chinese
        $data['home_tra_chinese']=Homelayout::where('status','active')->where('language','繁中')->get();
        $data['news_tra_chinese']=Newslayout::where('status','active')->where('language','繁中')->get();
        $data['contact_tra_chinese']=Contactus::where('status','active')->where('language','繁中')->get();
        $data['service_tra_chinese']=ServiceLayout::where('language','繁中')->get();
        $data['hotel_tra_chinese']=Hotelcollection::where('status','active')->where('language','繁中')->get();
        $data['tour_tra_chinese']=Tourcollection::where('status','active')->where('language','繁中')->get();
        $data['ticket_tra_chinese']=Ticketcollection::where('status','active')->where('language','繁中')->get();
        $data['transportation_tra_chinese']=Transportationcollection::where('status','active')->where('language','繁中')->get();

        //simple chinese
         $data['home_sim_chinese']=Homelayout::where('status','active')->where('language','簡')->get();
         $data['news_sim_chinese']=Newslayout::where('status','active')->where('language','簡')->get();
         $data['contact_sim_chinese']=Contactus::where('status','active')->where('language','簡')->get();
         $data['service_sim_chinese']=ServiceLayout::where('language','簡')->get();
         $data['hotel_sim_chinese']=Hotelcollection::where('status','active')->where('language','簡')->get();
         $data['tour_sim_chinese']=Tourcollection::where('status','active')->where('language','簡')->get();
         $data['ticket_sim_chinese']=Ticketcollection::where('status','active')->where('language','簡')->get();
         $data['transportation_sim_chinese']=Transportationcollection::where('status','active')->where('language','簡')->get();
        return view('admin.site.menutype.create',$data);
    }
    public function store(Request $request)
    {
       $input = $request->all();
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);
        $count_display_order = Menu::count();
        if ($count_display_order >= 0) {
            $c = stripslashes($count_display_order);
            $c = $c + 1;
        }
        $input['displayorder'] = $c;
        $menu=Menu::create($input);
        $input['menu_id']=$menu->id;
        $input['language']="English";

         $i=explode(',~,',$request['itm_sel']);
         $itm =explode(',%,',$i[0]);
           if(isset($itm[1])) {
                 $input['list_id'] = $itm[1];
           }
          $menutype=MenuType::create($input);

        $input_tra_chinese['menu_id']=$menu->id;
        $input_tra_chinese['language'] = "繁中";


         $i1=explode(',~,',$request['itm_tra_chinese']);
         $itm_tra_chinese =explode(',%,',$i1[1]);
        if(isset($itm_tra_chinese[1])) {
             $input_tra_chinese['list_id'] = $itm_tra_chinese[1];
        }
       $menutype=MenuType::create($input_tra_chinese);

        $input_sim_chinese['menu_id']=$menu->id;
        $input_sim_chinese['language'] = "簡";

        $i2=explode(',~,',$request['itm_sim_chinese']);
         $item_sim_chinese =explode(',%,',$i2[2]);

        if(isset($item_sim_chinese[1])) {
             $input_sim_chinese['list_id'] = $item_sim_chinese[1];
        }
       $menutype=MenuType::create($input_sim_chinese);

        Activity::log('Menu Control [Id = '.$menutype->id.'] has been inserted');
        \Session::flash('success', 'Menu Control has been inserted successfully!');
        return redirect('admin/site/menutype');
    }
    public function edit($id)
    {
        $data['mainmenu']="Site Setup";
        $data['menu']="Menu Control";
         $data['menutype'] = Menu::findOrFail($id);
        //English;
         $data['mtype']=MenuType::where('menu_id',$id)->where('language','English')->first();

        $i_sel=explode(",",$data['mtype']['list_id']);

        $home='';
        $news='';
        $contact='';
        $service='';
        $hotel='';
        $tour='';
        $transportation='';
        $ticket='';
        foreach($i_sel as $key =>$v){
            $s=explode('-',$v);
            if($s[0] == 'HM'){
                $home.=$s[1].",";
            }
            if($s[0] == 'NW'){
                $news.=$s[1].",";
            }
            if($s[0] == 'CN'){
                $contact.=$s[1].",";
            }
            if($s[0] == 'SR'){
                 $service.=$s[1].",";
            }
            if($s[0] == 'HT'){
                $hotel.=$s[1].",";
            }
            if($s[0] == 'TO'){
                $tour.=$s[1].",";
            }
            if($s[0] == 'TI'){
                $ticket.=$s[1].",";
            }
            if($s[0] == 'TR'){
                $transportation.=$s[1].",";
            }

        }
        $data['home_eng']=Homelayout::where('status','active')->where('language','English')->get();
        foreach($data['home_eng'] as $key => $val){
            $mode = explode(",",$home);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['home_eng'][$key]);
                }
            }
        }
        $data['news_eng']=Newslayout::where('status','active')->where('language','English')->get();
        foreach($data['news_eng'] as $key => $val){
             $mode = explode(",",$news);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['news_eng'][$key]);
                }
            }
        }
        $data['contact_eng']=Contactus::where('status','active')->where('language','English')->get();
        foreach($data['contact_eng'] as $key => $val){
            $mode = explode(",",$contact);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['contact_eng'][$key]);
                }
            }
        }
        $data['service_eng']=ServiceLayout::where('language','English')->get();
        foreach($data['service_eng'] as $key => $val){
              $mode = explode(",",$service);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['service_eng'][$key]);
                }
            }
        }
        $data['hotel_eng']=Hotelcollection::where('status','active')->where('language','English')->get();
        foreach($data['hotel_eng'] as $key => $val){
            $mode = explode(",",$hotel);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['hotel_eng'][$key]);
                }
            }
        }

        $data['tour_eng']=Tourcollection::where('status','active')->where('language','English')->get();
        foreach($data['tour_eng'] as $key => $val){
            $mode = explode(",",$tour);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['tour_eng'][$key]);
                }
            }
        }
        $data['ticket_eng']=Ticketcollection::where('status','active')->where('language','English')->get();
        foreach($data['ticket_eng'] as $key => $val){
            $mode = explode(",",$ticket);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['ticket_eng'][$key]);
                }
            }
        }
         $data['transportation_eng']=Transportationcollection::where('status','active')->where('language','English')->get();
        foreach($data['transportation_eng'] as $key => $val){
            $mode = explode(",",$transportation);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['transportation_eng'][$key]);
                }
            }
        }

        //     Right side selected
        $mode1=array();
        foreach ($i_sel as $m) {
            $mode1 = array_merge($mode1, explode('-', $m));
        }
        $data['mode50']=$mode1;

       //English end

        //traditional chinese;

        $data['mtype_tra_chinese']=MenuType::where('menu_id',$id)->where('language','繁中')->first();
        $i_sel_tra_chinese=explode(",",$data['mtype_tra_chinese']['list_id']);

        $home_tra_chinese='';
        $news_tra_chinese='';
        $contact_tra_chinese='';
        $service_tra_chinese='';
        $hotel_tra_chinese='';
        $tour_tra_chinese='';
        $transportation_tra_chinese='';
        $ticket_tra_chinese='';
        foreach($i_sel_tra_chinese as $key =>$v){
            $s=explode('-',$v);
            if($s[0] == 'HM'){
                $home_tra_chinese.=$s[1].",";
            }
            if($s[0] == 'NW'){
                $news_tra_chinese.=$s[1].",";
            }
            if($s[0] == 'CN'){
                $contact_tra_chinese.=$s[1].",";
            }
            if($s[0] == 'SR'){
                 $service_tra_chinese.=$s[1].",";
            }
            if($s[0] == 'HT'){
                $hotel_tra_chinese.=$s[1].",";
            }
            if($s[0] == 'TO'){
                $tour_tra_chinese.=$s[1].",";
            }
            if($s[0] == 'TI'){
                $ticket_tra_chinese.=$s[1].",";
            }
            if($s[0] == 'TR'){
                $transportation_tra_chinese.=$s[1].",";
            }

        }
         $data['home_tra_chinese']=Homelayout::where('status','active')->where('language','繁中')->get();
        foreach($data['home_tra_chinese'] as $key => $val){
            $mode = explode(",",$home_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['home_tra_chinese'][$key]);
                }
            }
        }

        $data['news_tra_chinese']=Newslayout::where('status','active')->where('language','繁中')->get();
        foreach($data['news_tra_chinese'] as $key => $val){
             $mode = explode(",",$news_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['news_tra_chinese'][$key]);
                }
            }
        }
        $data['contact_tra_chinese']=Contactus::where('status','active')->where('language','繁中')->get();
        foreach($data['contact_tra_chinese'] as $key => $val){
            $mode = explode(",",$contact_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['contact_tra_chinese'][$key]);
                }
            }
        }
        $data['service_tra_chinese']=ServiceLayout::where('language','繁中')->get();
        foreach($data['service_tra_chinese'] as $key => $val){
              $mode = explode(",",$service_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['service_tra_chinese'][$key]);
                }
            }
        }
        $data['hotel_tra_chinese']=Hotelcollection::where('status','active')->where('language','繁中')->get();
        foreach($data['hotel_tra_chinese'] as $key => $val){
            $mode = explode(",",$hotel_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['hotel_tra_chinese'][$key]);
                }
            }
        }

        $data['tour_tra_chinese']=Tourcollection::where('status','active')->where('language','繁中')->get();
        foreach($data['tour_tra_chinese'] as $key => $val){
            $mode = explode(",",$tour_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['tour_tra_chinese'][$key]);
                }
            }
        }
        $data['ticket_tra_chinese']=Ticketcollection::where('status','active')->where('language','繁中')->get();
        foreach($data['ticket_tra_chinese'] as $key => $val){
            $mode = explode(",",$ticket_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['ticket_tra_chinese'][$key]);
                }
            }
        }
         $data['transportation_tra_chinese']=Transportationcollection::where('status','active')->where('language','繁中')->get();
        foreach($data['transportation_tra_chinese'] as $key => $val){
            $mode = explode(",",$transportation_tra_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['transportation_tra_chinese'][$key]);
                }
            }
        }

        //     Right side selected
        $mode11=array();
        foreach ($i_sel_tra_chinese as $m) {
            $mode11 = array_merge($mode11, explode('-', $m));
        }
        $data['mode51']=$mode11;
        //traditional chinese end

        //simple chinese;
          $data['mtype_sim_chinese']=MenuType::where('menu_id',$id)->where('language','簡')->first();
        $i_sel_sim_chinese=explode(",",$data['mtype_sim_chinese']['list_id']);

        $home_sim_chinese='';
        $news_sim_chinese='';
        $contact_sim_chinese='';
        $service_sim_chinese='';
        $hotel_sim_chinese='';
        $tour_sim_chinese='';
        $transportation_sim_chinese='';
        $ticket_sim_chinese='';
        foreach($i_sel_sim_chinese as $key =>$v){
            $s=explode('-',$v);
            if($s[0] == 'HM'){
                $home_sim_chinese.=$s[1].",";
            }
            if($s[0] == 'NW'){
                $news_sim_chinese.=$s[1].",";
            }
            if($s[0] == 'CN'){
                $contact_sim_chinese.=$s[1].",";
            }
            if($s[0] == 'SR'){
                 $service_sim_chinese.=$s[1].",";
            }
            if($s[0] == 'HT'){
                $hotel_sim_chinese.=$s[1].",";
            }
            if($s[0] == 'TO'){
                $tour_sim_chinese.=$s[1].",";
            }
            if($s[0] == 'TI'){
                $ticket_sim_chinese.=$s[1].",";
            }
            if($s[0] == 'TR'){
                $transportation_sim_chinese.=$s[1].",";
            }

        }
         $data['home_sim_chinese']=Homelayout::where('status','active')->where('language','簡')->get();
        foreach($data['home_sim_chinese'] as $key => $val){
            $mode = explode(",",$home_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['home_sim_chinese'][$key]);
                }
            }
        }

        $data['news_sim_chinese']=Newslayout::where('status','active')->where('language','簡')->get();
        foreach($data['news_sim_chinese'] as $key => $val){
             $mode = explode(",",$news_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['news_sim_chinese'][$key]);
                }
            }
        }
        $data['contact_sim_chinese']=Contactus::where('status','active')->where('language','簡')->get();
        foreach($data['contact_sim_chinese'] as $key => $val){
            $mode = explode(",",$contact_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['contact_sim_chinese'][$key]);
                }
            }
        }
        $data['service_sim_chinese']=ServiceLayout::where('language','簡')->get();
        foreach($data['service_sim_chinese'] as $key => $val){
              $mode = explode(",",$service_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['service_sim_chinese'][$key]);
                }
            }
        }
        $data['hotel_sim_chinese']=Hotelcollection::where('status','active')->where('language','簡')->get();
        foreach($data['hotel_sim_chinese'] as $key => $val){
            $mode = explode(",",$hotel_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['hotel_sim_chinese'][$key]);
                }
            }
        }

        $data['tour_sim_chinese']=Tourcollection::where('status','active')->where('language','簡')->get();
        foreach($data['tour_sim_chinese'] as $key => $val){
            $mode = explode(",",$tour_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['tour_sim_chinese'][$key]);
                }
            }
        }
        $data['ticket_sim_chinese']=Ticketcollection::where('status','active')->where('language','簡')->get();
        foreach($data['ticket_sim_chinese'] as $key => $val){
            $mode = explode(",",$ticket_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['ticket_sim_chinese'][$key]);
                }
            }
        }
         $data['transportation_sim_chinese']=Transportationcollection::where('status','active')->where('language','簡')->get();
        foreach($data['transportation_sim_chinese'] as $key => $val){
            $mode = explode(",",$transportation_sim_chinese);
            foreach ($mode as $mod){
                if($mod == $val['id']){
                    unset($data['transportation_sim_chinese'][$key]);
                }
            }
        }

        //     Right side selected
        $mode12=array();
        foreach ($i_sel_sim_chinese as $m) {
            $mode12 = array_merge($mode12, explode('-', $m));
        }
        $data['mode52']=$mode12;

        return view('admin/site/menutype/edit',$data);

    }

    public function update(Request $request, $id)
    {
        $input1=$request->all();
        $menu = Menu::findOrFail($id);
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);
        $menu->update($input1);
        $m1=MenuType::where('menu_id',$id)->where('language','English')->first();
        $menu1 = MenuType::findOrFail($m1->id);
        $input['menu_id']=$id;
        $input['language']="English";

        $i=explode(',~,',$request['itm_sel']);
        $itm =explode(',%,',$i[0]);
        if(isset($itm[1])) {
            $input['list_id'] = $itm[1];
        }
        else{
            $input['list_id']="";
        }

        $menu1->update($input);

        $m2=MenuType::where('menu_id',$id)->where('language','繁中')->first();
        $menu2 = MenuType::findOrFail($m2->id);

        $input_tra_chinese['menu_id']=$id;
        $input_tra_chinese['language'] = "繁中";

        $i1=explode(',~,',$request['itm_tra_chinese']);
        $itm_tra_chinese =explode(',%,',$i1[1]);
        if(isset($itm_tra_chinese[1])) {
            $input_tra_chinese['list_id'] = $itm_tra_chinese[1];
        }
        else{
            $input_tra_chinese['list_id']="";
        }
        $menu2->update($input_tra_chinese);

        $m3=MenuType::where('menu_id',$id)->where('language','簡')->first();
        $menu3 = MenuType::findOrFail($m3->id);

        $input_sim_chinese['menu_id']=$id;
        $input_sim_chinese['language'] = "簡";

        $i2=explode(',~,',$request['itm_sim_chinese']);
        $item_sim_chinese =explode(',%,',$i2[2]);

        if(isset($item_sim_chinese[1])) {
            $input_sim_chinese['list_id'] = $item_sim_chinese[1];
        }
        else{
            $input_sim_chinese['list_id']="";
        }
        $menu3->update($input_sim_chinese);

        Activity::log('Menu Control has been Updated');
        \Session::flash('success', 'Menu Control has been Updated successfully!');
        return redirect('admin/site/menutype');
        //$url = $request->only('redirects_to');
        //return redirect()->to($url['redirects_to']);
        //return redirect()->back();

    }
    public function destroy($id)
    {
        $menutype = Menu::findOrFail($id);
        $menutype->delete();
        \Session::flash('danger','Menu Control has been deleted successfully!');
        return redirect('admin/site/menutype');
    }
    public function assign(Request $request)
    {
        $menu = Menu::findorFail($request['id']);
        $menu['status']="active";
        $menu->update($request->all());
        return $request['id'];
    }

    public function unassign(Request $request)
    {
        $menu = Menu::findorFail($request['id']);
        $menu['status']="inactive";
        $menu->update($request->all());
        return $request['id'];
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
            $services = Menu::findOrFail($pid1);
            $input['displayorder'] = $disp1;
            $services->update($input);
        }
        \Session::flash('success', 'Display order Updated successfully!');
        return redirect('admin/services');
    }
    public function reorder(Request $request){
        $array = $_POST['arrayorder'];
        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {

                $menu = Menu::findOrFail($idval);
                $input['displayorder'] = $count;
                $menu->update($input);
                $count ++;
            }
            echo 'Display order change successfully.';
        }
    }

}
