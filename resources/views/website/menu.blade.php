<?php $cart_total=\Gloudemans\Shoppingcart\Facades\Cart::count();?>

<?php
$menu = \App\MenuType::where('language',$cookie)->first();
$i_sel=explode(",",$menu['list_id']);

$mode50=array();
foreach ($i_sel as $m) {
    $mode50 = array_merge($mode50, explode('-', $m));
}
?>
<nav class="navbar navbar-default for-mobile navbar-fixed-top" xmlns="http://www.w3.org/1999/html">
    <div class="container-fluid ">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            @foreach($sitelogo as $list)
                @if($list['path']!="" && file_exists($list['path']))
                    <img src="{{ url($list->path) }}" alt="logo" style="max-width: 50%; text-align: center; margin: 0 auto; max-width:80px" class="img-responsive">
                @endif
            @endforeach
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                for($i=0;$i<count($mode50);$i++) {
                if ($mode50[$i] == 'HM') {
                $value = App\Homelayout::where('id', $mode50[$i+1])->where('status','active')->first();
                if(isset($value->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='Home') class="active" @endif> <a href="{{ url('/')}}"> {{$value['menu_name']}} </a> </li>
                <?php
                }
                }
                if ($mode50[$i] == 'NW') {
                $value1 = App\Newslayout::where('id', $mode50[$i+1])->where('status','active')->first();
                if(isset($value1->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='News') class="active" @endif><a href="{{url('news')}}">{{$value1['menu_name']}}</a></li>

                <?php
                }
                }
                if ($mode50[$i] == 'CN') {
                $value2 = App\Contactus::where('id', $mode50[$i+1])->where('status','active')->first();
                if(isset($value2->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='Contact') class="active" @endif ><a href="{{url('contactus')}}">{{$value2['menu_name']}}</a></li>

                <?php
                }
                }
                if ($mode50[$i] == 'SR') {
                $value3 = App\ServiceLayout::where('id', $mode50[$i+1])->first();
                if(isset($value3->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='Service') class="active" @endif ><a href="{{url('services')}}">{{$value3['menu_name']}}</a></li>
                <?php
                }
                }
                if ($mode50[$i] == 'HT') {
                $value4 = App\Hotelcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                if(isset($value4->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='Hotel' && $menuid==$value4->id) class="active" @endif ><a href="{{url('hotel/'.$value4->id)}}">{{$value4['menu_name']}}</a></li>

                <?php
                }
                }
                if ($mode50[$i] == 'TO') {
                $value5 = App\Tourcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                if(isset($value5->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='Tour' && $menuid==$value5->id) class="active" @endif><a href="{{url('tour/'.$value5->id)}}">{{$value5['name']}}</a></li>
                <?php
                }
                }
                if ($mode50[$i] == 'TI') {
                $value6 = App\Ticketcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                if(isset($value6->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='Ticket' && $menuid==$value6->id) class="active" @endif ><a href="{{'ticket/'.$value6->id}}">{{$value6['name']}}</a></li>
                <?php
                }
                }
                if ($mode50[$i] == 'TR'){
                $value7 = App\Transportationcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                if(isset($value7->id)){
                ?>
                <li @if(isset($menuactive) && $menuactive=='Transportation' && $menuid==$value7->id) class="active" @endif ><a href="{{url('transportation/'.$value7->id)}}">{{$value7['name']}}</a></li>
                <?php
                }
                }
                }
                ?>

                <li class="hidden-lg hidden-md"><a href="{{ url('cart') }}"><i class="fa fa-shopping-cart shopping-cart">
                        </i><span class="badge">{{$cart_total}}</span> &nbsp;
                        @if($cookie == 'English')
                            Shopping Cart
                        @elseif($cookie == '繁中')
                            購物車
                        @elseif($cookie == '簡')
                            购物车
                        @endif </a> </li>
                <div class="clearfix" style="padding:10px;">&nbsp;</div>
                <div class="col-md-12">
                    <div class="col-md-6 padding-0 text-left"><a href="{{url('contactus')}}"> <i class="fa fa-envelope blue-color"></i></a> </div>
                    <div class="col-md-6 padding-0 text-right blue-color"> <a href="{{url('home/繁')}}">繁</a>／<a href="{{url('home/簡')}}">簡</a>／<a href="{{url('home/Eng')}}">Eng</a> </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="copyright_div col-md-12 col-lg-12 col-sm-12 col-xs-12"> Copyright&copy;2016. Gray Line Tours of Hong Kong Ltd. All rights reserved.</div>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 for-desktop padding-0">
    <div class="affix col-md-2 col-lg-2 col-sm-2 col-xs-2 pd-0">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 logo-div padding-0" style="padding: 10px 0 20px 0"> @foreach($sitelogo as $list)
                @if($list['path']!="" && file_exists($list['path']))
                    <img src="{{ url($list->path) }}" alt="logo" style="max-width: 80%; text-align: center; margin: 0 auto;">
                @endif
            @endforeach
        </div>
        <nav class="navbar navbar-inverse sidebar " role="navigation">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
                    <ul class="nav navbar-nav side-nav">
                        <?php
                        for($i=0;$i<count($mode50);$i++) {
                        if ($mode50[$i] == 'HM') {
                        $value = App\Homelayout::where('id', $mode50[$i+1])->where('status','active')->first();
                        if(isset($value->id)){
                        ?>
                        <li @if(isset($menuactive) && $menuactive=='Home') class="active" @endif> <a href="{{ url('/')}}"> {{$value['menu_name']}} </a> </li>
                        <?php
                                }
                        }
                        if ($mode50[$i] == 'NW') {
                        $value1 = App\Newslayout::where('id', $mode50[$i+1])->where('status','active')->first();
                            if(isset($value1->id)){
                        ?>
                        <li @if(isset($menuactive) && $menuactive=='News') class="active" @endif><a href="{{url('news')}}">{{$value1['menu_name']}}</a></li>

                        <?php
                        }
                                }
                        if ($mode50[$i] == 'CN') {
                        $value2 = App\Contactus::where('id', $mode50[$i+1])->where('status','active')->first();
                            if(isset($value2->id)){
                            ?>
                        <li @if(isset($menuactive) && $menuactive=='Contact') class="active" @endif ><a href="{{url('contactus')}}">{{$value2['menu_name']}}</a></li>

                        <?php
                        }
                                }
                        if ($mode50[$i] == 'SR') {
                        $value3 = App\ServiceLayout::where('id', $mode50[$i+1])->first();
                            if(isset($value3->id)){
                        ?>
                        <li @if(isset($menuactive) && $menuactive=='Service') class="active" @endif ><a href="{{url('services')}}">{{$value3['menu_name']}}</a></li>
                        <?php
                        }
                                }
                        if ($mode50[$i] == 'HT') {
                        $value4 = App\Hotelcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                            if(isset($value4->id)){
                            ?>
                        <li @if(isset($menuactive) && $menuactive=='Hotel' && $menuid==$value4->id) class="active" @endif ><a href="{{url('hotel/'.$value4->id)}}">{{$value4['menu_name']}}</a></li>

                        <?php
                         }
                        }
                        if ($mode50[$i] == 'TO') {
                        $value5 = App\Tourcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                            if(isset($value5->id)){
                        ?>
                        <li @if(isset($menuactive) && $menuactive=='Tour' && $menuid==$value5->id) class="active" @endif><a href="{{url('tour/'.$value5->id)}}">{{$value5['name']}}</a></li>
                        <?php
                        }
                        }
                        if ($mode50[$i] == 'TI') {
                        $value6 = App\Ticketcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                            if(isset($value6->id)){
                            ?>
                        <li @if(isset($menuactive) && $menuactive=='Ticket' && $menuid==$value6->id) class="active" @endif ><a href="{{url('ticket/'.$value6->id)}}">{{$value6['name']}}</a></li>
                        <?php
                        }
                        }
                        if ($mode50[$i] == 'TR'){
                        $value7 = App\Transportationcollection::where('id', $mode50[$i+1])->where('status','active')->first();
                        if(isset($value7->id)){
                            ?>
                        <li @if(isset($menuactive) && $menuactive=='Transportation' && $menuid==$value7->id) class="active" @endif ><a href="{{url('transportation/'.$value7->id)}}">{{$value7['name']}}</a></li>
                        <?php
                        }
                        }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="clearfix" style="">&nbsp;</div>
        <div class="shopping_cart_div col-md-12 col-lg-12 col-sm-12 col-xs-12" onclick="window.location = '{{url('cart')}}';" style="cursor:pointer">
            <i class="fa fa-shopping-cart shopping-cart"></i>
            <span class="badge">{{$cart_total}}</span> &nbsp;
            @if($cookie == 'English')
                Shopping Cart
            @elseif($cookie == '繁中')
                購物車
            @elseif($cookie == '簡')
                购物车
            @endif
        </div>
        <div class="clearfix" style="padding:10px;">&nbsp;</div>
        <div class="col-md-12">
            <div class="col-md-6 padding-0 text-left"><a href="{{url('contactus')}}"> <i class="fa fa-envelope blue-color"></i> </a></div>
            <div class="col-md-6 padding-0 text-right blue-color"> <a href="{{url('home/繁')}}">繁</a>／<a href="{{url('home/簡')}}">簡</a>／<a href="{{url('home/Eng')}}">Eng</a> </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="copyright_div col-md-12 col-lg-12 col-sm-12 col-xs-12"> Copyright&copy;2016. Gray Line Tours of Hong Kong Ltd. All rights reserved.</div>
    </div>
</div>

