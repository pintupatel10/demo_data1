<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Disneyland Tickets</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body table-responsive">
        <div><a href="{{ url("admin/disneyland-ticket/$price_type/$price_id/create") }}" ><button class="btn bg-orange margin" type="button">Add Ticket</button></a></div>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Event Code</th>
                <th>Ticket Code</th>
                <th>Pickup ID</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($price->disneyland_tickets as $ticket)
                <tr>
                    <td>{{ $ticket->{App\DisneylandTicket::COLUMN_ID} }}</td>
                    <td>{{ $ticket->{App\DisneylandTicket::COLUMN_EVENT_CODE} }}</td>
                    <td>{{ $ticket->{App\DisneylandTicket::COLUMN_TICKET_CODE} }}</td>
                    <td>{{ $ticket->{App\DisneylandTicket::COLUMN_PICKUP_ID} }}</td>
                    <td>
                        <div class="btn-group-horizontal">
                            <a href="{{ url("admin/disneyland-ticket/$price_type/$price_id/$ticket->id") }}"> <button class="btn btn-success" type="button"><i class="fa fa-edit"></i></button></a>
                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal{{$ticket['id']}}"><i class="fa fa-trash"></i></button>
                        </div>
                    </td>
                </tr>

                <div id="myModal{{$ticket['id']}}" class="fade modal modal-danger" role="dialog" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete Disneyland Ticket</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this Disneyland Ticket ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-outline" onclick="delete_item('{{ $ticket['id'] }}')">Delete</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    {{--{{ Form::close() }}--}}
                </div>
            @endforeach

        </table>


        {!! Form::open(['id' => 'disneyland-delete-form', 'url' => url("admin/disneyland-ticket/$price_type/$price_id"), 'method' => 'delete', 'class' => 'form-horizontal']) !!}
        {{ Form::close() }}

        <script type="text/javascript">
            function delete_item(id) {
                var url = $("#disneyland-delete-form").attr("action") + "/" + id;
                $("#disneyland-delete-form").attr("action", url).submit();
            }
        </script>

    </div>

</div>