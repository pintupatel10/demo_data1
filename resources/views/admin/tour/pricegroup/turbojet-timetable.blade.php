{!! Form::open(['id' => 'turbojet-timetable-form', 'url' => url('admin/tour/'.$detail.'/pricegroup/'.$pricegroup->id . '/turbojet-timetable'), 'method' => 'post', 'class' => 'form-horizontal','files'=>true]) !!}
    <span class='btn btn-success btn-file' style="margin-bottom: 10px;">
        <i class='fa fa-upload'></i> Upload Timetable <input name='turbojet-timetable' type='file'>
    </span>
{!! Form::close() !!}

<table id="table-turbojet-timetable" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>From</th>
        <th>To</th>
        <th>Class</th>
        <th>Time</th>
        <th>Weekday</th>
        <th>Weekend</th>
        <th>Holiday</th>
        <th>Non Holiday</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($pricegroup->turbojet_timetables()->orderBy('time', 'asc')->get() as $timetable)
        <tr>
            <td>{{ $timetable->{\App\TurbojetTimetable::COLUMN_FROM} }}</td>
            <td>{{ $timetable->{\App\TurbojetTimetable::COLUMN_TO} }}</td>
            <td>{{ $timetable->{\App\TurbojetTimetable::COLUMN_CLASS} }}</td>
            <td>{{ $timetable->{\App\TurbojetTimetable::COLUMN_TIME} }}</td>
            <td>@if ($timetable->{App\TurbojetTimetable::COLUMN_IS_WEEKDAY})<i class="fa fa-check"></i>@endif</td>
            <td>@if ($timetable->{App\TurbojetTimetable::COLUMN_IS_WEEKEND})<i class="fa fa-check"></i>@endif</td>
            <td>@if ($timetable->{App\TurbojetTimetable::COLUMN_IS_HOLIDAY})<i class="fa fa-check"></i>@endif</td>
            <td>@if ($timetable->{App\TurbojetTimetable::COLUMN_IS_NON_HOLIDAY})<i class="fa fa-check"></i>@endif</td>
            <td>{{ $timetable->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


<script type="text/javascript">
    $(function (){
        $("input[name=turbojet-timetable]").change(function (){
            $("#turbojet-timetable-form").submit();
        });


        $('#table-turbojet-timetable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });
    });
</script>