<hr>
<h1> Change History </h1>
<br>
<div class="box">

    <div class="box-body">
        @include ('admin.error')
        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Change From</th>
                <th>Change To</th>
                <th>Change By</th>
                <th>Edit Time</th>
                <th>Remark</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($history as $list)
                <tr>
                    <td>{{$list['change_from']}}</td>
                    <td>{{$list['change_to']}}</td>
                    <td>{{$list['change_by']}}</td>
                    <td>{{$list['updated_at']}}</td>
                    <td width="1%"><button class="btn btn-info" type="button" data-toggle="modal" data-target="#myModal{{$list['id']}}"><i class="fa fa-edit"></i></button>  {{$list['remark1']}}</td>
                </tr>

                <div id="myModal{{$list['id']}}" class="fade modal modal-info" role="dialog" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Remark</h4>
                            </div>
                            {!! Form::model($history, ['url' => url('admin/transaction/'.$orderlist."/".$list['id'].'/add'), 'method' => 'POST', 'class' => 'form-horizontal','files'=>true]) !!}
                            <div class="modal-body">

                                <textarea name="remark1" style="color:black;width: 100%;height: 200px;">{{$list['remark1']}}</textarea>
                                    {{--{!! Form::textarea('remark1', null, ['class' => 'form-control', 'placeholder' => '']) !!}--}}

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
                                {{ Form::submit('Add', array('class'=>'button btn btn-primary','id'=>'mdl_save_change'))}}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                 </div>
            @endforeach
        </table>

    </div>
</div>
