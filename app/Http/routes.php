<?php

/* ADMIN ROUTES */

Route::group(['prefix' => 'admin','admin/home','admin/news','admin/contact'], function()
{
    Route::auth();
    Route::get('dashboard', 'admin\DashboardController@index');
    Route::get('/', function () {
        return redirect('admin/dashboard');
    });

    Route::resource('users', 'admin\UserController');

    Route::resource('staff','admin\StaffController');
    Route::resource('group','admin\GroupController');
    Route::get('emailcollect/{id}','admin\EmailAdvertiseController@destroyemailcollect');
    Route::resource('emailadvertise','admin\EmailAdvertiseController');

    Route::post('site/sitelogo/update_display_order', 'admin\SitelogoController@update_display_order');
    Route::resource('site/sitelogo','admin\SitelogoController');

	Route::get('site/emailset/emaildelete/{id}','admin\EmailsetController@emaildelete');
    Route::post('site/emailset/update_display_order','admin\EmailsetController@update_display_order');
    Route::resource('site/emailset','admin\EmailsetController');

    Route::post('site/menutype/update_display_order','admin\MenuTypeController@update_display_order');
    Route::resource('site/menutype','admin\MenuTypeController');

    Route::post('home/layout/update_display_order', 'admin\HomelayoutController@update_display_order');
    Route::resource('home/layout','admin\HomelayoutController');

    Route::post('home/popup/update_display_order', 'admin\HomepopupController@update_display_order');
    Route::resource('home/popup','admin\HomepopupController');

    Route::post('home/post/update_display_order', 'admin\HomepostController@update_display_order');
    Route::resource('home/post','admin\HomepostController');

	Route::post('services/update_display_order', 'admin\ServicedetailController@update_display_order');
    Route::post('services/service_menu', 'admin\ServicedetailController@service_menu');
    Route::patch('services/service_menu_update', 'admin\ServicedetailController@service_menu_update');
    Route::resource('services','admin\ServicedetailController');

    Route::post('news/layout/update_display_order', 'admin\NewslayoutController@update_display_order');
    Route::resource('news/layout','admin\NewslayoutController');

    Route::post('news/post/update_display_order', 'admin\NewspostController@update_display_order');
    Route::resource('news/post','admin\NewspostController');

    Route::post('contact/contact_record/update_display_order', 'admin\ContactrecordController@update_display_order');
    Route::get('contact/contact_record/{id}/destroy','admin\ContactrecordController@destroy');
    Route::resource('contact/contact_record','admin\ContactrecordController');

    Route::get('contact/contact_us/emaildelete/{id}','admin\ContactusController@emaildelete');
    Route::post('contact/contact_us/update_display_order', 'admin\ContactusController@update_display_order');
    Route::resource('contact/contact_us','admin\ContactusController');

    Route::post('tour/collection/update_display_order', 'admin\TourcollectionController@update_display_order');
    Route::resource('tour/collection','admin\TourcollectionController');

   // Route::post('tour/grouplist/update_display_order', 'admin\TourGrouplistController@update_display_order');
   // Route::resource('tour/grouplist','admin\TourGrouplistController');

    Route::post('tour/tourgroup/update_display_order', 'admin\TourGroupController@update_display_order');
    Route::resource('tour/tourgroup','admin\TourGroupController');

    Route::post('tour/list/update_display_order', 'admin\TourlistController@update_display_order');
    Route::resource('tour/list','admin\TourlistController');

    //Route::post('tour/tourdetail_full/update_display_order', 'admin\TourDetailfullController@update_display_order');
    //Route::resource('tour/tourdetail_full','admin\TourDetailfullController');

    Route::post('tour/tourlist/update_display_order', 'admin\TourListController@update_display_order');
    Route::resource('tour/tourlist','admin\TourListController');

    Route::post('tour/checkpoint/update_display_order', 'admin\TourCheckpointController@update_display_order');
    Route::get('tour/{detailid}/checkpoint/{id}/destroy','admin\TourCheckpointController@destroy');
    Route::resource('tour/{detailid}/checkpoint','admin\TourCheckpointController');

    Route::post('tour/{detailid}/pricegroup/{id}/turbojet-timetable','admin\TourPricegroupController@turbojetTimetable');
    Route::get('tour/{detailid}/pricegroup/{id}/destroy','admin\TourPricegroupController@destroy');
    Route::resource('tour/{detailid}/pricegroup','admin\TourPricegroupController');

    Route::get('tour/{detailid}/{pricegroupid}/price/{id}/destroy','admin\TourPriceController@destroy');
    Route::resource('tour/{detailid}/{pricegroupid}/price','admin\TourPriceController');

    Route::get('tour/{detailid}/{pricegroupid}/inventory/{id}/destroy','admin\TourInventoryController@destroy');
    Route::resource('tour/{detailid}/{pricegroupid}/inventory','admin\TourInventoryController');

    Route::delete('tour/{cid}/filter/{id}/destroy','admin\TourFilterController@destroy');
    Route::resource('tour/{cid}/filter','admin\TourFilterController');

    Route::resource('coupon','admin\CouponController');
    Route::post('hotel/layout/update_display_order', 'admin\HotelLayoutController@update_display_order');

    Route::resource('hotel/layout','admin\HotelLayoutController');
    Route::post('hotel/hoteldetail/update_display_order', 'admin\HotelDetailController@update_display_order');
    Route::resource('hotel/hoteldetail','admin\HotelDetailController');

    Route::post('hotel/collection/update_display_order', 'admin\HotelcollectionController@update_display_order');
    Route::resource('hotel/collection','admin\HotelcollectionController');

    Route::resource('hotel/hotelcontact','admin\HotelContactController');


    Route::post('ticket/ticketlist/update_display_order', 'admin\TicketListController@update_display_order');
    Route::resource('ticket/ticketlist','admin\TicketListController');

    Route::post('ticket/checkpoint/update_display_order', 'admin\TicketCheckpointController@update_display_order');
    Route::get('ticket/{detailid}/checkpoint/{id}/destroy','admin\TicketCheckpointController@destroy');
    Route::resource('ticket/{detailid}/checkpoint','admin\TicketCheckpointController');

    Route::post('ticket/pricegroup/update_display_order', 'admin\TicketPricegroupController@update_display_order');
    Route::get('ticket/{detailid}/pricegroup/{id}/destroy','admin\TicketPricegroupController@destroy');
    Route::resource('ticket/{detailid}/pricegroup','admin\TicketPricegroupController');

    Route::post('ticket/price/update_display_order', 'admin\TicketPriceController@update_display_order');
    Route::get('ticket/{detailid}/{pricegroupid}/price/{id}/destroy','admin\TicketPriceController@destroy');
    Route::resource('ticket/{detailid}/{pricegroupid}/price','admin\TicketPriceController');

    Route::post('ticket/inventory/update_display_order', 'admin\TicketInventoryController@update_display_order');
    Route::get('ticket/{detailid}/{pricegroupid}/inventory/{id}/destroy','admin\TicketInventoryController@destroy');
    Route::resource('ticket/{detailid}/{pricegroupid}/inventory','admin\TicketInventoryController');

    Route::post('ticket/volume/update_display_order', 'admin\TicketVolumeController@update_display_order');
    Route::get('ticket/{detailid}/{pricegroupid}/volume/{id}/destroy','admin\TicketVolumeController@destroy');
    Route::resource('ticket/{detailid}/{pricegroupid}/volume','admin\TicketVolumeController');

    Route::post('ticket/ticketgroup/update_display_order', 'admin\TicketGroupController@update_display_order');
    Route::resource('ticket/ticketgroup','admin\TicketGroupController');

    Route::post('ticket/collection/update_display_order', 'admin\TicketcollectionController@update_display_order');
    Route::resource('ticket/collection','admin\TicketcollectionController');

    Route::get('ticket/{cid}/filter/{id}/destroy','admin\TicketFilterController@destroy');
    Route::resource('ticket/{cid}/filter','admin\TicketFilterController');

    Route::post('transportation/transportationlist/update_display_order', 'admin\TransportationListController@update_display_order');
    Route::resource('transportation/transportationlist','admin\TransportationListController');

    Route::post('transportation/checkpoint/update_display_order', 'admin\TransportationCheckpointController@update_display_order');
    Route::get('transportation/{detailid}/checkpoint/{id}/destroy','admin\TransportationCheckpointController@destroy');
    Route::resource('transportation/{detailid}/checkpoint','admin\TransportationCheckpointController');

    Route::post('transportation/pricegroup/update_display_order', 'admin\TransportationPricegroupController@update_display_order');
    Route::get('transportation/{detailid}/pricegroup/{id}/destroy','admin\TransportationPricegroupController@destroy');
    Route::resource('transportation/{detailid}/pricegroup','admin\TransportationPricegroupController');

    Route::post('transportation/price/update_display_order', 'admin\TransportationPriceController@update_display_order');
    Route::get('transportation/{detailid}/{pricegroupid}/price/{id}/destroy','admin\TransportationPriceController@destroy');
    Route::resource('transportation/{detailid}/{pricegroupid}/price','admin\TransportationPriceController');

    Route::post('transportation/Timetable/update_display_order', 'admin\TransportationTimetableController@update_display_order');
    Route::get('transportation/{detailid}/{pricegroupid}/timetable/{id}/destroy','admin\TransportationTimetableController@destroy');
    Route::resource('transportation/{detailid}/{pricegroupid}/timetable','admin\TransportationTimetableController');

    Route::post('transportation/transportationgroup/update_display_order', 'admin\TransportationGroupController@update_display_order');
    Route::resource('transportation/transportationgroup','admin\TransportationGroupController');

    Route::post('transportation/collection/update_display_order', 'admin\TransportationcollectionController@update_display_order');
    Route::resource('transportation/collection','admin\TransportationcollectionController');

    Route::get('transportation/{cid}/filter/{id}/destroy','admin\TransportationFilterController@destroy');
    Route::resource('transportation/{cid}/filter','admin\TransportationFilterController');

    Route::get('hotel/{cid}/filter/{id}/destroy','admin\HotelFilterController@destroy');
    Route::resource('hotel/{cid}/filter','admin\HotelFilterController');




	 Route::resource('transaction/orderlist','admin\TransactionOrderlistController');
     Route::get('transaction/orderlist/{id}/destroy', 'admin\TransactionOrderlistController@destroy');
     Route::post('transaction/orderlist/{id}/edit', 'admin\TransactionOrderlistController@up');

    Route::resource('transaction/{orderid}/productlist','admin\TransactionProductlistController');
    Route::post('transaction/{orderid}/{id}/add','admin\TransactionProductlistController@add');

    Route::resource('transaction/customer','admin\TransactioncustomerController');

    Route::get('transaction/{customerid}/order/{id}/destroy','admin\TransactionorderController@destroy');
    Route::resource('transaction/{customerid}/order','admin\TransactionorderController');

    Route::resource('transaction/{customerid}/{orderid}/product','admin\TransactionproductController');

    Route::post('privatetour/privatetourlist/update_display_order', 'admin\PrivateTourlistController@update_display_order');
    Route::resource('privatetour/privatetourlist','admin\PrivateTourlistController');

    Route::post('privatetour/checkpoint/update_display_order', 'admin\PrivateTourCheckpointController@update_display_order');
    Route::get('privatetour/{detailid}/checkpoint/{id}/destroy','admin\PrivateTourCheckpointController@destroy');
    Route::resource('privatetour/{detailid}/checkpoint','admin\PrivateTourCheckpointController');

    Route::get('privatetour/{detailid}/pricegroup/{id}/destroy','admin\PrivateTourPricegroupController@destroy');
    Route::resource('privatetour/{detailid}/pricegroup','admin\PrivateTourPricegroupController');

    Route::get('privatetour/{detailid}/{pricegroupid}/price/{id}/destroy','admin\PrivateTourPriceController@destroy');
    Route::resource('privatetour/{detailid}/{pricegroupid}/price','admin\PrivateTourPriceController');


    Route::post('privatetransportation/privatetransportationlist/update_display_order', 'admin\PrivateTransportationlistController@update_display_order');
    Route::resource('privatetransportation/privatetransportationlist','admin\PrivateTransportationlistController');

    Route::post('privatetransportation/checkpoint/update_display_order', 'admin\PrivateTransportationCheckpointController@update_display_order');
    Route::get('privatetransportation/{detailid}/checkpoint/{id}/destroy','admin\PrivateTransportationCheckpointController@destroy');
    Route::resource('privatetransportation/{detailid}/checkpoint','admin\PrivateTransportationCheckpointController');

    Route::get('privatetransportation/{detailid}/pricegroup/{id}/destroy','admin\PrivateTransportationPricegroupController@destroy');
    Route::resource('privatetransportation/{detailid}/pricegroup','admin\PrivateTransportationPricegroupController');

    Route::get('privatetransportation/{detailid}/{pricegroupid}/price/{id}/destroy','admin\PrivateTransportationPriceController@destroy');
    Route::resource('privatetransportation/{detailid}/{pricegroupid}/price','admin\PrivateTransportationPriceController');

    Route::resource('terms', 'admin\StaticpageController');

    Route::get('report','admin\ReportController@index');
    Route::post('report/export', 'admin\ReportController@export');

    Route::get('preview','admin\ImagesController@preview');
    Route::get('images/{id}/destroy', 'admin\ImagesController@destroy');
    Route::resource('images', 'admin\ImagesController');


    Route::get('disneyland-ticket/{type}/{type_id}/create', 'admin\DisneylandTicketController@create');
    Route::post('disneyland-ticket/{type}/{type_id}', 'admin\DisneylandTicketController@store');
    Route::get('disneyland-ticket/{type}/{type_id}/{id}', 'admin\DisneylandTicketController@show');
    Route::put('disneyland-ticket/{type}/{type_id}/{id}', 'admin\DisneylandTicketController@update');
    Route::delete('disneyland-ticket/{type}/{type_id}/{id}', 'admin\DisneylandTicketController@destroy');



    Route::get('oceanpark-ticket/{type}/{type_id}/create', 'admin\OceanParkTicketController@create');
    Route::post('oceanpark-ticket/{type}/{type_id}', 'admin\OceanParkTicketController@store');
    Route::get('oceanpark-ticket/{type}/{type_id}/{id}', 'admin\OceanParkTicketController@show');
    Route::put('oceanpark-ticket/{type}/{type_id}/{id}', 'admin\OceanParkTicketController@update');
    Route::delete('oceanpark-ticket/{type}/{type_id}/{id}', 'admin\OceanParkTicketController@destroy');

    Route::get('order/{order_id}/reply', 'admin\OrderController@show_reply');
    Route::post('order/{order_id}/send-reply', 'admin\OrderController@send_reply');
    Route::put('order/{order_id}/send-confirm', 'admin\OrderController@send_confirmation');
    Route::put('order/{order_id}/complete', 'admin\OrderController@complete_order_product');
    Route::put('order/{order_id}/cancel', 'admin\OrderController@cancel_order_product');
    Route::get('order/{order_id}/confirmation/{id}', 'admin\OrderController@show_order_confirmation');
    Route::get('order/{order_id}/attachment/{id}', 'admin\OrderController@download_message_attachment');
    Route::get('order/{order_id}/{order_product_id}', 'admin\OrderController@show_order_product');
    Route::put('order/{order_id}/{order_product_id}', 'admin\OrderController@update_order_product');
    Route::get('order/{order_id}/{order_product_id}/confirm', 'admin\OrderController@show_order_product_confirm');
    Route::put('order/{order_id}/{order_product_id}/confirm', 'admin\OrderController@update_order_product_confirm');
    Route::put('order/{order_id}/{order_product_id}/audit-remark', 'admin\OrderController@update_order_product_audit_remark');
    Route::get('order/{order_id}/{order_product_id}/disneyland-letter/{id}', 'admin\OrderController@download_disneyland_letter');
    Route::get('order/{order_id}/{order_product_id}/oceanpark-letter/{id}', 'admin\OrderController@download_oceanpark_letter');
    Route::get('order/{order_id}/{order_product_id}/turbojet-letter/{id}', 'admin\OrderController@download_turbojet_letter');
    Route::get('order/{order_id}/{order_product_id}/attachment/{id}', 'admin\OrderController@download_attachment');
    Route::resource('order', 'admin\OrderController', ['except' => ['create', 'store', 'edit', 'destroy']]);

    Route::get('customer', 'admin\CustomerController@index');
    Route::get('customer/{encoded}', 'admin\CustomerController@show');
    Route::post('customer/export', 'admin\CustomerController@export');

    Route::post('checkpoint/update_display_order','admin\CheckpointController@update_display_order');
    Route::resource('checkpoint', 'admin\CheckpointController');

    Route::resource('turbojet-coupon', 'admin\TurbojetCouponController', ['except' => ['edit']]);
    Route::post('turbojet-holiday/upload', 'admin\TurbojetHolidayController@upload');
    Route::resource('turbojet-holiday', 'admin\TurbojetHolidayController', ['except' => ['edit']]);
    Route::get('turbojet-virtual-report', 'admin\TurbojetVirtualReportController@index');
    Route::get('turbojet-virtual-report/{id}', 'admin\TurbojetVirtualReportController@download');

});

Route::get('api/cms/staff/assign','admin\StaffController@assign');
Route::get('api/cms/staff/unassign','admin\StaffController@unassign');

Route::get('api/cms/group/assign','admin\GroupController@assign');
Route::get('api/cms/group/unassign','admin\GroupController@unassign');

Route::get('api/cms/site/sitelogo/assign','admin\SitelogoController@assign');
Route::get('api/cms/site/sitelogo/unassign','admin\SitelogoController@unassign');

Route::get('api/cms/site/emailset/assign','admin\EmailsetController@assign');
Route::get('api/cms/site/emailset/unassign','admin\EmailsetController@unassign');

Route::get('api/cms/home/layout/assign','admin\HomelayoutController@assign');
Route::get('api/cms/home/layout/unassign','admin\HomelayoutController@unassign');

Route::get('api/cms/home/popup/assign','admin\HomepopupController@assign');
Route::get('api/cms/home/popup/unassign','admin\HomepopupController@unassign');

Route::get('api/cms/home/post/assign','admin\HomepostController@assign');
Route::get('api/cms/home/post/unassign','admin\HomepostController@unassign');

Route::post('api/cms/home/post/reorder', 'admin\HomepostController@reorder');

Route::get('api/cms/services/assign','admin\ServicedetailController@assign');
Route::get('api/cms/services/unassign','admin\ServicedetailController@unassign');

Route::post('api/cms/services/reorder', 'admin\ServicedetailController@reorder');

Route::get('api/cms/news/layout/assign','admin\NewslayoutController@assign');
Route::get('api/cms/news/layout/unassign','admin\NewslayoutController@unassign');

Route::get('api/cms/news/post/assign','admin\NewspostController@assign');
Route::get('api/cms/news/post/unassign','admin\NewspostController@unassign');

Route::post('api/cms/news/post/reorder', 'admin\NewspostController@reorder');

Route::get('api/cms/contact/contact_record/assign','admin\ContactrecordController@assign');
Route::get('api/cms/contact/contact_record/unassign','admin\ContactrecordController@unassign');

Route::get('api/cms/contact/contact_us/assign','admin\ContactusController@assign');
Route::get('api/cms/contact/contact_us/unassign','admin\ContactusController@unassign');

Route::get('api/cms/tour/collection/assign','admin\TourcollectionController@assign');
Route::get('api/cms/tour/collection/unassign','admin\TourcollectionController@unassign');

Route::get('api/cms/tour/tourgroup/assign','admin\TourGroupController@assign');
Route::get('api/cms/tour/tourgroup/unassign','admin\TourGroupController@unassign');

Route::get('api/cms/tour/tourlist/assign','admin\TourListController@assign');
Route::get('api/cms/tour/tourlist/unassign','admin\TourListController@unassign');

Route::post('api/cms/tour/tourlist/reorder', 'admin\TourListController@reorder');


Route::get('api/cms/tour/tourlist/{detailid}/assign','admin\TourCheckpointController@assign');
Route::get('api/cms/tour/tourlist/{detailid}/unassign','admin\TourCheckpointController@unassign');

Route::get('api/cms/tour/tourlist/{detailid}/pricegroup/assign','admin\TourPricegroupController@assign');
Route::get('api/cms/tour/tourlist/{detailid}/pricegroup/unassign','admin\TourPricegroupController@unassign');

Route::get('api/cms/tour/tourlist/{detailid}/pricegroup/{pricegroupid}/assign','admin\TourPriceController@assign');
Route::get('api/cms/tour/tourlist/{detailid}/pricegroup/{pricegroupid}/unassign','admin\TourPriceController@unassign');

Route::get('api/cms/tour/collection/{cid}/assign','admin\TourFilterController@assign');
Route::get('api/cms/tour/collection/{cid}/unassign','admin\TourFilterController@unassign');

Route::get('api/cms/coupon/assign','admin\CouponController@assign');
Route::get('api/cms/coupon/unassign','admin\CouponController@unassign');

Route::post('api/cms/hotel/layout/reorder', 'admin\HotelLayoutController@reorder');
Route::get('api/cms/hotel/layout/assign','admin\HotelLayoutController@assign');
Route::get('api/cms/hotel/layout/unassign','admin\HotelLayoutController@unassign');


Route::get('api/cms/hotel/hoteldetail/assign','admin\HotelDetailController@assign');
Route::get('api/cms/hotel/hoteldetail/unassign','admin\HotelDetailController@unassign');

Route::get('api/cms/hotel/collection/assign','admin\HotelcollectionController@assign');
Route::get('api/cms/hotel/collection/unassign','admin\HotelcollectionController@unassign');

Route::post('api/cms/hotel/collection/reorder', 'admin\HotelcollectionController@reorder');


Route::get('api/cms/hotel/collection/{cid}/assign','admin\HotelFilterController@assign');
Route::get('api/cms/hotel/collection/{cid}/unassign','admin\HotelFilterController@unassign');

Route::get('api/cms/ticket/ticketlist/assign','admin\TicketListController@assign');
Route::get('api/cms/ticket/ticketlist/unassign','admin\TicketListController@unassign');

Route::post('api/cms/ticket/ticketlist/reorder', 'admin\TicketListController@reorder');

Route::get('api/cms/ticket/ticketlist/{detailid}/assign','admin\TicketCheckpointController@assign');
Route::get('api/cms/ticket/ticketlist/{detailid}/unassign','admin\TicketCheckpointController@unassign');

Route::get('api/cms/ticket/ticketlist/{detailid}/pricegroup/assign','admin\TicketPricegroupController@assign');
Route::get('api/cms/ticket/ticketlist/{detailid}/pricegroup/unassign','admin\TicketPricegroupController@unassign');

Route::get('api/cms/ticket/ticketlist/{detailid}/pricegroup/{pricegroupid}/assign','admin\TicketPriceController@assign');
Route::get('api/cms/ticket/ticketlist/{detailid}/pricegroup/{pricegroupid}/unassign','admin\TicketPriceController@unassign');

Route::get('api/cms/ticket/ticketlist/{detailid}/pricegroup/{pricegroupid}/volume/assign','admin\TicketVolumeController@assign');
Route::get('api/cms/ticket/ticketlist/{detailid}/pricegroup/{pricegroupid}/volume/unassign','admin\TicketVolumeController@unassign');


Route::get('api/cms/ticket/ticketgroup/assign','admin\TicketGroupController@assign');
Route::get('api/cms/ticket/ticketgroup/unassign','admin\TicketGroupController@unassign');

Route::get('api/cms/ticket/collection/assign','admin\TicketcollectionController@assign');
Route::get('api/cms/ticket/collection/unassign','admin\TicketcollectionController@unassign');

Route::get('api/cms/ticket/collection/{cid}/assign','admin\TicketFilterController@assign');
Route::get('api/cms/ticket/collection/{cid}/unassign','admin\TicketFilterController@unassign');

Route::get('api/cms/transportation/transportationlist/assign','admin\TransportationListController@assign');
Route::get('api/cms/transportation/transportationlist/unassign','admin\TransportationListController@unassign');

Route::post('api/cms/transportation/transportationlist/reorder', 'admin\TransportationListController@reorder');

Route::get('api/cms/transportation/transportationlist/{detailid}/assign','admin\TransportationCheckpointController@assign');
Route::get('api/cms/transportation/transportationlist/{detailid}/unassign','admin\TransportationCheckpointController@unassign');

Route::get('api/cms/transportation/transportationlist/{detailid}/pricegroup/assign','admin\TransportationPricegroupController@assign');
Route::get('api/cms/transportation/transportationlist/{detailid}/pricegroup/unassign','admin\TransportationPricegroupController@unassign');

Route::get('api/cms/transportation/transportationlist/{detailid}/pricegroup/{pricegroupid}/assign','admin\TransportationPriceController@assign');
Route::get('api/cms/transportation/transportationlist/{detailid}/pricegroup/{pricegroupid}/unassign','admin\TransportationPriceController@unassign');

Route::get('api/cms/transportation/transportationlist/{detailid}/pricegroup/{pricegroupid}/time/assign','admin\TransportationTimetableController@assign');
Route::get('api/cms/transportation/transportationlist/{detailid}/pricegroup/{pricegroupid}/time/unassign','admin\TransportationTimetableController@unassign');


Route::get('api/cms/transportation/transportationgroup/assign','admin\TransportationGroupController@assign');
Route::get('api/cms/transportation/transportationgroup/unassign','admin\TransportationGroupController@unassign');

Route::get('api/cms/transportation/collection/assign','admin\TransportationcollectionController@assign');
Route::get('api/cms/transportation/collection/unassign','admin\TransportationcollectionController@unassign');

Route::get('api/cms/transportation/collection/{cid}/assign','admin\TransportationFilterController@assign');
Route::get('api/cms/transportation/collection/{cid}/unassign','admin\TransportationFilterController@unassign');

Route::post('api/cms/site/menutype/reorder', 'admin\MenuTypeController@reorder');
Route::get('api/cms/site/menutype/assign','admin\MenuTypeController@assign');
Route::get('api/cms/site/menutype/unassign','admin\MenuTypeController@unassign');

Route::get('api/cms/staff/assign','admin\StaffController@assign');
Route::get('api/cms/staff/unassign','admin\StaffController@unassign');

Route::get('api/cms/checkpoint/assign','admin\CheckpointController@assign');
Route::get('api/cms/checkpoint/unassign','admin\CheckpointController@unassign');

Route::get('api/cms/privatetour/privatetourlist/assign','admin\PrivateTourListController@assign');
Route::get('api/cms/privatetour/privatetourlist/unassign','admin\PrivateTourListController@unassign');

Route::get('api/cms/privatetransportation/privatetransportationlist/assign','admin\PrivateTransportationListController@assign');
Route::get('api/cms/privatetransportation/privatetransportationlist/unassign','admin\PrivateTransportationListController@unassign');

Route::post('api/cms/filter/reorder', 'admin\TourFilterController@reorder');
Route::post('api/cms/filter1/reorder', 'admin\TicketFilterController@reorder');

Route::post('api/cms/filter2/reorder', 'admin\TransportationFilterController@reorder');
Route::post('api/cms/filter3/reorder', 'admin\HotelFilterController@reorder');


//website
Route::get('/', 'website\HomeController@index');
Route::get('home/{lng}','website\HomeController@setcookie');
Route::get('services','website\ServicesController@index');
Route::get('news','website\NewsController@index');
Route::get('contactus','website\ContactusController@index');
Route::post('contactus/store','website\ContactusController@store');

Route::get('ticket/{cid}','website\TicketController@index');
Route::get('ticket/{cid}/ticketfulllist/{id}','website\TicketController@showfull');
Route::get('ticket/{cid}/ticketsimplelist/{id}','website\TicketController@showsimple');
Route::get('ticket/{cid}/ticketgroup/{id}','website\TicketController@showgroup');


Route::get('tour/{cid}','website\TourlistController@index');
Route::get('tour/{cid}/tourfulllist/{id}','website\TourlistController@showfull');
Route::get('tour/{cid}/toursimplelist/{id}','website\TourlistController@showsimple');
Route::get('tour/{cid}/tourgroup/{id}','website\TourlistController@showgroup');
//Route::get('tour/validate_date/{id}/{date}','website\TourlistController@validate_date');
Route::get('privatetour/privatetourlist/{id}','website\PrivatetourController@showfull');
Route::get('privatetransportation/privatetransportationlist/{id}','website\PrivateTransportationController@showfull');

Route::get('transportation/{cid}','website\TransportationController@index');
Route::get('transportation/{cid}/fulllist/{id}','website\TransportationController@showfull');
Route::get('transportation/{cid}/simplelist/{id}','website\TransportationController@showsimple');
Route::get('transportation/{cid}/group/{id}','website\TransportationController@showgroup');


Route::get('reserve/{type}/{cid}/{price_group_id}', 'website\ReserveController@reserve');
Route::post('api/reserve/{type}/{cid}/{price_group_id}/quota', 'website\ReserveController@quota');
Route::post('api/reserve/{type}/{cid}/{price_group_id}/price', 'website\ReserveController@price');
Route::post('api/reserve/transportation/{price_group_id}/timetable','website\ReserveController@timetable');
Route::post('api/reserve/transportation/{price_group_id}/turbojet','website\ReserveController@turbojetTimetable');
Route::post('api/reserve/combo/{price_group_id}/turbojet','website\ReserveController@comboTurbojetTimetable');

Route::get('hotel/{id}','website\HotelController@index');
Route::get('hotel/{cid}/detail/{id}','website\HotelController@show');
Route::post('hotelcontact/store/{id}','website\HotelController@store');


Route::post('cart','website\CartController@add');
Route::get('cart','website\CartController@show');
Route::get('cart/remove/{rowid}','website\CartController@remove');
Route::get('checkout','website\CartController@checkout');
Route::get('api/checkout/{method}','website\CartController@getPaymentForm');
Route::get('notify', 'website\CartController@cancelNotify');
Route::post('notify','website\CartController@notify');
Route::get('thankyou','website\CartController@thankyou');
Route::get('turbojet-qrcode/{qrcode}', 'website\CartController@turbojetQRCode');

Route::get('terms/{language}', 'website\TermsController@index');

Route::get('enquire/{language}/{enquire_no}', 'website\EnquireController@index');
Route::post('enquire/{language}/{enquire_no}', 'website\EnquireController@enquire');

Route::get('test', function (){

    $order_product_id = 'GLC-20170427-10060-2';
    $ref_no = str_replace('-', '', $order_product_id);
    $ref_no = substr($ref_no, 0, 3) . substr($ref_no, 5) . 'A';
    $ref_no++;
    dd($ref_no);

//    $response = App\Helpers\DisneylandApiHelper::reserveOrder('GLC170427100602A', 'WSVT', 'Kwok', 'zh_TW', 4, [
//        ['ticketId' => '1DGE',
//        'qty' => 1]
//    ]);
//
//    dd($response);

//    $payment_history = \App\PaymentHistory::orderBy('id', 'desc')->first();
//    return view('admin.orders.emails.payment-history', compact('payment_history'));
});
