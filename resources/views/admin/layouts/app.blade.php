<?php
$user_group = \Illuminate\Support\Facades\Auth::user()->group_id;
$group = \App\Group::where('id', $user_group)->first();
$right=array();
if (!empty($group)) {
    $right = explode(',', $group->accessright);
}
?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Grayline | {{ $menu }}</title>
    <link rel="icon" href="{!! asset('favicon.ico') !!}"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/iCheck/flat/blue.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/morris/morris.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('website/css/callout.css')}}">

    <style type="text/css">
        .select2-container .select2-selection--single {
            height: 34px !important;
        }
    </style>

    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="{{ URL::asset('assets/plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

    <header class="main-header">
        <a href="{{ url('admin/dashboard') }}" class="logo">
            <span class="logo-mini"><b>G</b>L</span>
            <span class="logo-lg"><b>Grayline</b> Admin</span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            @if(isset(Auth::user()->role) == 'Staff')
                                @if(Auth::user()->image!="" && file_exists(Auth::user()->image))
                                    <img src="{{ url(Auth::user()->image) }}" width="40" class="user-image" alt="User Image">
                                @else
                                    <img src="{{ URL::asset('assets/dist/img/profile.png') }}" class="user-image" alt="User Image">
                                @endif
                            @else
                                <img src="{{ URL::asset('assets/dist/img/avatar.png') }}" class="user-image" alt="User Image">
                            @endif

                            <span class="hidden-xs">{{ $user = Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">

                            <li class="user-header">
                                {{--<img src="{{ URL::asset('assets/dist/img/avatar.png') }}" class="img-circle"--}}
                                {{--alt="User Image">--}}

                                @if(isset(Auth::user()->role) == 'Staff')

                                    @if(Auth::user()->image!="" && file_exists(Auth::user()->image))
                                        <img src="{{ url(Auth::user()->image) }}" width="40" class="img-circle" alt="User Image">
                                    @else
                                        <img src="{{ URL::asset('assets/dist/img/profile.png') }}" class="img-circle" alt="User Image">
                                    @endif
                                @else
                                    <img src="{{ URL::asset('assets/dist/img/avatar.png') }}" class="img-circle" alt="User Image">
                                @endif

                                <p>
                                    {{ $user = Auth::user()->name }} <br>
                                    <small></small>
                                </p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ url('admin/users/'.$user = Auth::user()->id).'/edit' }}"
                                       class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('admin/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">

                <li class="@if($mainmenu=='Dashboard') active  @endif">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <?php
                $access='Access Staff Management';
                $check = in_array($access, $right);
                ?>

                @if(Auth::User()->role == "admin" || $check == 1)
                    <li class="treeview @if($mainmenu=='Staff') active  @endif">
                        <a href="#">
                            <i class="fa fa-group"></i> <span>Staff Management</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="@if(isset($menu) && $menu=='Staff') active @endif">
                                <a href="{{ url('admin/staff') }}"><i class="fa fa-circle-o"></i><span>Staff</span></a>
                            </li>
                            <li class="@if(isset($menu) && $menu=='Group') active @endif">
                                <a href="{{ url('admin/group') }}"><i class="fa fa-circle-o"></i><span>Group</span></a>
                            </li>
                        </ul>
                    </li>

                @endif

                <?php
                $access1='Access Site Setup';
                $check1 = in_array($access1, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check1)
                    <li class="treeview @if($mainmenu=='Site Setup') active  @endif">
                        <a href="#">
                            <i class="fa fa-file-image-o"></i> <span>Site Setup</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <li class="@if(isset($menu) && $menu=='Site Logo') active @endif">
                                <a href="{{ url('admin/site/sitelogo/') }}"><i class="fa fa-circle-o"></i>Site Logo</a></li>
                            <li class="@if(isset($menu) && $menu=='Email setup') active @endif">
                                <a href="{{ url('admin/site/emailset/') }}"><i class="fa fa-circle-o"></i>Email setup</a></li>

                            <li class="@if(isset($menu) && $menu=='Menu Control') active @endif">
                                <a href="{{ url('admin/site/menutype/') }}"><i class="fa fa-circle-o"></i>Menu Control</a></li>
                        </ul>
                    </li>
                @endif
                <?php
                $access2='Access Email Advertise';
                $check2 = in_array($access2, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check2)
                    <li class="@if(isset($menu) && $menu=='EmailAdvertise') active @endif">
                        <a href="{{ url('admin/emailadvertise') }}"><i class="fa fa-envelope-o"></i><span> Email Advertise</span></a>
                    </li>
                @endif
                <?php
                $access3='Access Home';
                $check3 = in_array($access3, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check3)
                    <li class="treeview @if($mainmenu=='Home') active  @endif">
                        <a href="#">
                            <i class="fa fa-home"></i> <span>Home</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <li class="@if(isset($menu) && $menu=='Home Layout') active @endif">
                                <a href="{{ url('admin/home/layout') }}"><i class="fa fa-circle-o"></i>Home Layout</a></li>

                            <li class="@if(isset($menu) && $menu=='Home PopUp') active @endif">
                                <a href="{{ url('admin/home/popup/') }}"><i class="fa fa-circle-o"></i>Home PopUp</a></li>

                            <li class="@if(isset($menu) && $menu=='Home Post') active @endif">
                                <a href="{{ url('admin/home/post/') }}"><i class="fa fa-circle-o"></i>Home Post</a></li>

                        </ul>
                    </li>

                @endif
                <?php
                $access4='Access Service';
                $check4 = in_array($access4, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check4)
                    <li class="@if(isset($menu) && $menu=='Services') active @endif">
                        <a href="{{ url('admin/services') }}"><i class="fa fa-sliders"></i><span> Services</span></a>
                    </li>

                @endif
                <?php
                $access5='Access News';
                $check5 = in_array($access5, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check5)
                    <li class="treeview @if($mainmenu=='News') active  @endif">
                        <a href="#">
                            <i class="fa fa-newspaper-o"></i> <span>News</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <li class="@if(isset($menu) && $menu=='News Layout') active @endif">
                                <a href="{{ url('admin/news/layout') }}"><i class="fa fa-circle-o"></i>News Layout</a></li>


                            <li class="@if(isset($menu) && $menu=='News Post') active @endif">
                                <a href="{{ url('admin/news/post/') }}"><i class="fa fa-circle-o"></i>News Post</a></li>

                        </ul>
                    </li>

                @endif
                <?php
                $access6='Access Contact';
                $check6 = in_array($access6, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check6)
                    <li class="treeview @if($mainmenu=='Contact') active  @endif">
                        <a href="#">
                            <i class="fa fa-map-o"></i> <span>Contact</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <li class="@if(isset($menu) && $menu=='Contact Record') active @endif">
                                <a href="{{ url('admin/contact/contact_record') }}"><i class="fa fa-circle-o"></i>Contact Record</a></li>


                            <li class="@if(isset($menu) && $menu=='Contact us') active @endif">
                                <a href="{{ url('admin/contact/contact_us/') }}"><i class="fa fa-circle-o"></i>Contact Us</a></li>

                        </ul>
                    </li>
                @endif
                <?php
                $access7='Access Tour';
                $check7 = in_array($access7, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check7)
                    <li class="treeview @if($mainmenu=='Tour') active  @endif">

                        <a href="#">
                            <i class="fa fa-plane"></i> <span>Tour</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li class="@if(isset($menu) && $menu=='Tour Collection') active @endif">
                                <a href="{{ url('admin/tour/collection') }}"><i class="fa fa-circle-o"></i>Tour Collection</a></li>


                            <li class="@if(isset($menu) && $menu=='Tour Group') active @endif">
                                <a href="{{ url('admin/tour/tourgroup') }}"><i class="fa fa-circle-o"></i>Tour Group</a></li>

                            <li class="@if(isset($menu) && $menu=='Tour List') active @endif">
                                <a href="{{ url('admin/tour/tourlist') }}"><i class="fa fa-circle-o"></i>Tour List</a></li>
                        </ul>

                    </li>
                @endif
                <?php
                $access8='Access Private Tour';
                $check8 = in_array($access8, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check8)
                    <li class="treeview @if($mainmenu=='Private Tour') active  @endif">

                        <a href="#">
                            <i class="fa fa-plane"></i> <span>Private Tour</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">

                            <li class="@if(isset($menu) && $menu=='Private Tour List') active @endif">
                                <a href="{{ url('admin/privatetour/privatetourlist') }}"><i class="fa fa-circle-o"></i>Private Tour List</a></li>

                        </ul>

                    </li>
                @endif
                <?php
                $access9='Access Transportation';
                $check9 = in_array($access9, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check9)
                    <li class="treeview @if($mainmenu=='Transportation') active  @endif">

                        <a href="#">
                            <i class="fa fa-bus"></i> <span>Transportation</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">

                            <li class="@if(isset($menu) && $menu=='Transportation Collection') active @endif">
                                <a href="{{ url('admin/transportation/collection') }}"><i class="fa fa-circle-o"></i>Transportation Collection</a></li>

                            <li class="@if(isset($menu) && $menu=='Transportation Group') active @endif">
                                <a href="{{ url('admin/transportation/transportationgroup') }}"><i class="fa fa-circle-o"></i>Transportation Group</a></li>

                            <li class="@if(isset($menu) && $menu=='Transportation List') active @endif">
                                <a href="{{ url('admin/transportation/transportationlist') }}"><i class="fa fa-circle-o"></i>Transportation List</a></li>
                        </ul>

                    </li>
                @endif

                <?php
                $access16='Access Private Transportation';
                $check16 = in_array($access16, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check16)
                    <li class="treeview @if($mainmenu=='Private Transportation') active  @endif">

                        <a href="#">
                            <i class="fa fa-bus"></i> <span>Private Transportation</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li class="@if(isset($menu) && $menu=='Private Transportation List') active @endif">
                                <a href="{{ url('admin/privatetransportation/privatetransportationlist') }}"><i class="fa fa-circle-o"></i>Private Transportation List</a></li>
                        </ul>

                    </li>
                @endif

                <?php
                $access10='Access Ticket';
                $check10 = in_array($access10, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check10)
                    <li class="treeview @if($mainmenu=='Ticket') active  @endif">

                        <a href="#">
                            <i class="fa fa-ticket"></i> <span>Ticket</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li class="@if(isset($menu) && $menu=='Ticket Collection') active @endif">
                                <a href="{{ url('admin/ticket/collection') }}"><i class="fa fa-circle-o"></i>Ticket Collection</a></li>

                            <li class="@if(isset($menu) && $menu=='Ticket Group') active @endif">
                                <a href="{{ url('admin/ticket/ticketgroup') }}"><i class="fa fa-circle-o"></i>Ticket Group</a></li>

                            <li class="@if(isset($menu) && $menu=='Ticket List') active @endif">
                                <a href="{{ url('admin/ticket/ticketlist') }}"><i class="fa fa-circle-o"></i>Ticket List</a></li>
                        </ul>

                    </li>

                @endif
                <?php
                $access11='Access Coupon';
                $check11 = in_array($access11, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check11)
                    <li class="@if(isset($menu) && $menu=='Coupon') active @endif">
                        <a href="{{ url('admin/coupon') }}"><i class="fa fa-qrcode"></i><span> Coupon</span></a>
                    </li>

                @endif
                <?php
                $access12='Access Hotel';
                $check12 = in_array($access12, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check12)
                    <li class="treeview @if($mainmenu=='Hotel') active  @endif">
                        <a href="#">
                            <i class="fa fa-hotel"></i> <span>Hotel</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">

                            <li class="@if(isset($menu) && $menu=='Hotel Collection') active @endif">
                                <a href="{{ url('admin/hotel/collection') }}"><i class="fa fa-circle-o"></i>Hotel Collection</a></li>

                            <li class="@if(isset($menu) && $menu=='Hotel List') active @endif">
                                <a href="{{ url('admin/hotel/hoteldetail/') }}"><i class="fa fa-circle-o"></i>Hotel List</a></li>

                            <li class="@if(isset($menu) && $menu=='Contact Record') active @endif">
                                <a href="{{ url('admin/hotel/hotelcontact/') }}"><i class="fa fa-circle-o"></i>Contact Record</a></li>

                        </ul>
                    </li>

                @endif
                <?php
                $access13='Access Order';
                $check13 = in_array($access13, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check13)
                    <li class="@if(isset($menu) && $menu=='Order') active @endif">
                        <a href="{{ url('admin/order') }}"><i class="fa fa-money"></i><span> Order</span></a>
                    </li>
                @endif

                <?php
                $access130='Access Customer';
                $check1300 = in_array($access130, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check1300)
                    <li class="@if(isset($menu) && $menu=='Customer') active @endif">
                        <a href="{{ url('admin/customer') }}"><i class="fa fa-user-o"></i><span> Customer</span></a>
                    </li>
                @endif
                <?php
                $access14='Access Report';
                $check14 = in_array($access14, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check14)
                    <li class="@if(isset($menu) && $menu=='Report') active @endif">
                        <a href="{{ url('admin/report') }}"><i class="fa fa-file-excel-o"></i><span> Report </span></a>
                    </li>
                @endif


                <?php
                $access140='Access Image Center';
                $check1400 = in_array($access140, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check1400)
                        <li class="@if(isset($menu) && $menu=='Image Center') active @endif">
                        <a href="{{ url('admin/images') }}"><i class="fa fa-picture-o"></i><span> Images Center </span></a>
                        </li>
                @endif


                <?php
                $access150='Access Checkpoint Center';
                $check1500 = in_array($access150, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check1500)
                         <li class="@if(isset($menu) && $menu=='Checkpoint') active @endif">
                            <a href="{{ url('admin/checkpoint') }}"><i class="fa fa-picture-o"></i><span> Checkpoint Center </span></a>
                         </li>
                @endif

                <?php
                $access15='Access T & C';
                $check15 = in_array($access15, $right);
                ?>
                @if(Auth::User()->role == "admin" || $check15)
                    <li class="@if(isset($menu) && $menu=='TERMS & CONDITIONS') active @endif">
                        <a href="{{ url('admin/terms/1/edit') }}"><i class="fa fa-list-alt"></i><span> TERMS & CONDITIONS </span></a>
                    </li>
                @endif

                @if(Auth::User()->role == "admin" || in_array('Access Turbojet Setting', $right))
                <li class="treeview @if($mainmenu=='Turbojet') active  @endif">
                    <a href="#">
                        <i class="fa fa-ship"></i> <span>Turbojet</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <li class="@if(isset($menu) && $menu=='Turbojet Coupon') active @endif">
                            <a href="{{ url('admin/turbojet-coupon') }}"><i class="fa fa-percent"></i>Turbojet Coupon</a></li>

                        <li class="@if(isset($menu) && $menu=='Turbojet Holiday') active @endif">
                            <a href="{{ url('admin/turbojet-holiday') }}"><i class="fa fa-cog"></i>Turbojet Holiday</a></li>

                        <li class="@if(isset($menu) && $menu=='Turbojet Virtual Report') active @endif">
                            <a href="{{ url('admin/turbojet-virtual-report') }}"><i class="fa fa-file-text-o"></i>Turbojet Virtual Seating</a></li>

                    </ul>
                </li>
                @endif

            </ul>
        </section>
    </aside>

    @yield('content')

    <footer class="main-footer">
        <strong>Grayline</strong>
    </footer>
    <div class="control-sidebar-bg"></div>
</div>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
    $('.calltype').click(function () {
        alert(this.val());
    });
</script>

<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ URL::asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ URL::asset('assets/plugins/morris/morris.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/knob/jquery.knob.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/fastclick/fastclick.js')}}"></script>
<script src="{{ URL::asset('assets/dist/js/app.min.js')}}"></script>
<script>
    $(function () {
        $(".select2").select2();
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });


        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });
        $("#example3").DataTable({"paging": false});
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });

        $('#example4').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true
        });
        $('#example5').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true
        });

        $('#example6').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": true
        });

        $('#reservation').daterangepicker({
            format: 'YYYY/MM/DD'
        });

        $('#datepicker').datepicker({
            format: 'yyyy-m-d',
            autoclose: true
        });
        $('#datepicker1').datepicker({
            format: 'yyyy-m-d',
            autoclose: true
        });
        $('#datepicker2').datepicker({
            format: 'yyyy-m-d',
            autoclose: true
        });
        $('#datepicker3').datepicker({
            format: 'yyyy-m-d',
            autoclose: true
        });
        $('#birthdate').datepicker({
            format: 'yyyy-m-d',
            autoclose: true
        });

        $('#effective_year').datepicker({
            format: 'yyyy-m-d',
            autoclose: true
        });

        $(".timepicker").timepicker({
            showInputs: false,
            showMeridian: false,
        });

    });
</script>

@yield('jquery')


</body>
</html>
