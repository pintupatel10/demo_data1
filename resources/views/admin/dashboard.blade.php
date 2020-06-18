@extends('admin.layouts.app')
@section('content')


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('admin/dashboard') }}"> <i class="fa fa-dashboard"></i>  {{ $menu }} </a></li>
            </ol>

            @include ('admin.error')
        </section>

        <section class="content">
            <div class="row">

                <!-- Today Order -->
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $today_count }}</h3>
                            <p>Orders Today</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <a href="{{ $today_url }}" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Yesterday Order -->
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $yesterday_count }}</h3>
                            <p>Orders Yesterday</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <a href="{{ $yesterday_url }}" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Yesterday Order -->
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>${{ $yesterday_amount }}</h3>
                            <p>Amount Yesterday</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-usd"></i>
                        </div>
                        <a href="{{ $yesterday_url }}" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- InComplete Order -->
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $incomplete_count }}</h3>
                            <p>In-Complete Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <a href="{{ $incomplete_url }}" class="small-box-footer">
                            More info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Amount Summary in 30 Days</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="amountChart"></canvas>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Action Logs</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Staff No. with Name</th>
                            <th>Description</th>
                            <th>Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($latestActivities as $list)
                            <tr>
                                <td>{{ $list['id'] }}</td>
                                <td>{{$list['user']['number']}}-{{$list['user']['name']}}</td>
                                <td>{{$list['text']}}</td>
                                <td>{{$list['created_at']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@section("jquery")
    <script src="{{ URL::asset('assets/plugins/chartjs/Chart.min.js')}}"></script>
    <script type="text/javascript">

        $(function (){

            var amountChartData = {
                labels: {!! json_encode($amount_chart_labels) !!},
                datasets: [
                    {
                        label: "Amount",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: {!! json_encode($amount_chart_data) !!}
                    }
                ]
            };

            var amountChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: false,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: false,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: false,
                //String - A legend template
                {{--legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",--}}
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };

            var amountChartCanvas = $("#amountChart").get(0).getContext("2d");
            var lineChart = new Chart(amountChartCanvas);
            lineChart.Line(amountChartData, amountChartOptions);
        });

    </script>
@endsection