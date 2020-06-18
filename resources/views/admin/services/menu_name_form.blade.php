
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">

                    <h3 class="box-title">Services Menu Name </h3>
                </div>
               <?php

                $get=App\ServiceLayout::count();
                ?>
                 @if($get)
                     <?php   $servicemenu=\App\ServiceLayout::findOrFail(1);?>
                    {!! Form::model($servicemenu,['url' => url('admin/services/service_menu_update'), 'method' => 'patch', 'class' => 'form-horizontal','files'=>true]) !!}

                 @else
                {!! Form::open(['url' => url('admin/services/service_menu'), 'method' => 'post', 'class' => 'form-horizontal','files'=>true]) !!}

                @endif
                    <div class="box-body">

                    <div class="form-group{{ $errors->has('menu_english') ? ' has-error' : '' }}">
                        <label class="col-sm-2 control-label" for="menu_english">English </label>
                        <div class="col-sm-5">
                            {!! Form::text('menu_english',isset($menu_name[0])?$menu_name[0]['menu_name']:null, ['class' => 'form-control', 'placeholder' => 'English']) !!}
                            @if ($errors->has('menu_english'))
                                <span class="help-block">
                       <strong>{{ $errors->first('menu_english') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('menu_traditional') ? ' has-error' : '' }}">
                        <label class="col-sm-2 control-label" for="menu_traditional">繁中 </label>
                        <div class="col-sm-5">
                            {!! Form::text('menu_traditional', isset($menu_name[1])?$menu_name[1]['menu_name']:null, ['class' => 'form-control', 'placeholder' => '繁中']) !!}
                            @if ($errors->has('menu_traditional'))
                                <span class="help-block">
                <strong>{{ $errors->first('menu_traditional') }}</strong>
            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('menu_simple') ? ' has-error' : '' }}">
                        <label class="col-sm-2 control-label" for="menu_simple">簡 </label>
                        <div class="col-sm-5">
                            {!! Form::text('menu_simple', isset($menu_name[2])?$menu_name[2]['menu_name']:null, ['class' => 'form-control', 'placeholder' => '簡']) !!}
                            @if ($errors->has('menu_simple'))
                                <span class="help-block">
                <strong>{{ $errors->first('simple') }}</strong>
            </span>
                            @endif
                        </div>
                    </div>
                    <button class="btn btn-info pull" type="submit">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
