@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <h1>
                    Turbojet Virtual Reports
                </h1>

            </h1>
            <ol class="breadcrumb">
                <li class="active">View</li>
            </ol>
        </section>

        <section class="content">

            @include("admin.error")

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Turbojet Virtual Reports</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body table-responsive">

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Report Date</th>
                            <th>Record Count</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td>{{ $report->{App\TurbojetVirtualReport::COLUMN_ID} }}</td>
                                <td>{{ $report->{App\TurbojetVirtualReport::COLUMN_DATE}->format('Y-m-d') }}</td>
                                <td>{{ $report->{App\TurbojetVirtualReport::COLUMN_RECORD_COUNT} }}</td>
                                <td>{{ $report->created_at }}</td>
                                <td>
                                    <div class="btn-group-horizontal">
                                        <a href="{{ url("admin/turbojet-virtual-report/$report->id") }}"> <button class="btn btn-default" type="button"><i class="fa fa-download"></i> Download</button></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </table>


                </div>

            </div>
        </section>
    </div>
@endsection



