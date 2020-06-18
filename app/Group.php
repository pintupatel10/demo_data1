<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','accessright','status',
    ];
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'in-active';

    public static $status = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'In Active',
    ];
    public static $access = [
        self::ACCESS_TOUR => 'Access Tour',
        self::ACCESS_TICKET => 'Access Ticket',
        self:: ACCESS_Transport=>'Access Transportation',
        self:: ACCESS_Private_Tour=>'Access Private Tour',
        self:: ACCESS_Hotel=>'Access Hotel',
        self:: Access_Staff_Management=>'Access Staff Management',
       // self:: Access_Staff=>'Access Staff',
        //self:: Access_Group=>'Access Group',
        self:: ACCESS_Home=>'Access Home',
        self:: ACCESS_Email=>'Access Email Advertise',
        self:: ACCESS_Site=>'Access Site Setup',
        self:: ACCESS_Sevice=>'Access Service',
        self:: ACCESS_News=>'Access News',
        self:: ACCESS_Contact=>'Access Contact',
        self:: ACCESS_Coupon=>'Access Coupon',
       // self::ACCESS_transaction=>'Access Transaction',
        self::Access_Report=>'Access Report',
        self::Access_static_page=>'Access T & C',
        self:: ACCESS_Private_Transport=>'Access Private Transportation',
        self:: ACCESS_Order=>'Access Order',
        self:: ACCESS_Customer=>'Access Customer',
        self:: ACCESS_Image_Center=>'Access Image Center',
        self:: ACCESS_Checkpoint_Center=>'Access Checkpoint Center',
        self:: ACCESS_Turbojet_Setting=>'Access Turbojet Setting',
    ];
    const ACCESS_TOUR = 'Access Tour';
    const ACCESS_TICKET= 'Access Ticket';
    const ACCESS_Transport= 'Access Transportation';
    const ACCESS_Private_Tour ='Access Private Tour';
    const ACCESS_Hotel = 'Access Hotel';
    const Access_Staff_Management = 'Access Staff Management';
    //const Access_Staff= 'Access Staff';
    //const Access_Group='Access Group';
    const ACCESS_Home ='Access Home';
    const ACCESS_Email='Access Email Advertise';
    const ACCESS_Site = 'Access Site Setup';
    const ACCESS_Sevice = 'Access Service';
    const ACCESS_News = 'Access News';
    const ACCESS_Contact = 'Access Contact';
    const ACCESS_Coupon = 'Access Coupon';
   // const ACCESS_transaction = 'Access Transaction';
    const Access_Report = 'Access Report';
    const Access_static_page = 'Access T & C';
    const ACCESS_Private_Transport ='Access Private Transportation';
    const ACCESS_Order='Access Order';
    const ACCESS_Customer='Access Customer';
    const ACCESS_Image_Center='Access Image Center';
    const ACCESS_Checkpoint_Center='Access Checkpoint Center';
    const ACCESS_Turbojet_Setting='Access Turbojet Setting';



    public function Staff()
    {
        return $this->hasMany('App\Staff','group_id');
    }

}
