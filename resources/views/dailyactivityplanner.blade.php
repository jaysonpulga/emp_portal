@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Daily Activity Planner (DAP)</h1>
@stop

@section('content')
{{--    <form action="{{route('dailyactivityplanner.getdap')}}" id="myform2" method="POST">--}}
    <form action="" id="myform">
        @csrf
        <div class="row mb-2">
            <div class="col-md-3">
                {{--<div class="input-group">--}}
                    {{--<label class="mr-2">Work Date</label>--}}
                    {{--<input type="text" name="work_date" id="work_date" class="form-control mr-2 " value="{{old('work_date')}}" placeholder="yyyy-mm-dd" required>--}}
                    {{--<span class="input-group-btn">--}}
                    {{--<button type="button" class="btn btn-outline-primary" name="set_date" id="set_date" >Set</button>--}}
                {{--</span>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label class="mr-2">Work Date</label>
                    <input type="text" name="work_date" id="work_date" class="form-control mr-2 " value="{{old('work_date')}}" placeholder="yyyy-mm-dd" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-outline-primary" name="set_date" id="set_date" value="Submit">Set</button>
                </div>
            </div>
            <div class="col-md-9">
                <div class="btn-group float-right">
                    <button type="button" class="form-control btn-success mr-2" name="create_record" id="create_record">Add DAP Line</button>
                    <button type="button" class="form-control btn-primary" name="download_dap" id="download_dap">Download DAP</button>
                    {{--<button type="submit" class="form-control btn-primary" name="download_dap" id="download_dap">Download DAP</button>--}}
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <table id="dap_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="10%">From</th>
                    <th width="10%">To</th>
                    <th width="25%">Planned Activity</th>
                    <th width="25%">Actual Activity</th>
                    <th width="15%">Status</th>
                    <th width="15%">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    {{--<br />--}}
    {{--<br />--}}
@stop

@extends('footer')

<div class="modal fade" id="formModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add DAP Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="">Work Date</label>
                                <input type="text" name="transact_date" id="transact_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="activityInputFrom">From</label>
                                <select class="form-control" name="hour_from" id="from_dropdown"> <option value="{{ old('hour_from') }}">Select Work Hour</option>
                                    @foreach ($workhours as $workhour)
                                        <option value="{{ $workhour->id }}"
                                                @if ($workhour->name == old('hour_from'))
                                                selected="selected"
                                            @endif
                                        > {{ $workhour->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="activityInputTo">To</label>
                                <select class="form-control" name="hour_to" id="to_dropdown"> <option value="{{ old('hour_to') }}">Select Work Hour</option>
                                    @foreach ($workhours as $workhour)
                                        <option value="{{ $workhour->id }}"
                                                @if ($workhour->name == old('hour_to'))
                                                selected="selected"
                                            @endif
                                        > {{ $workhour->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Planned Activity</label>
                                <textarea class="form-control" name="planned_activity" id="planned_activity" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Actual Activity</label>
                                <textarea class="form-control" name="actual_activity" id="actual_activity" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Status</label>
                                <textarea class="form-control" name="status" id="status" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Attach a file</label>
                                <input type="file" name="image">
                            </div>
                            <div class="modal-footer justify-content-between">
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <input type="submit" name="action_button" id="action_button" class="btn btn-primary" value="Save" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="confirmModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure you want to delete this record?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function(){
            console.log('Ready');

            Date.prototype.toDateInputValue = (function() {
                var local = new Date(this);
                local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                return local.toJSON().slice(0,10);
            });

            $('#work_date').val(new Date().toDateInputValue());

            loadDailyActivity();

        });

        var table;

        function loadDailyActivity() {
            table = $('#dap_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
{{--                        url: "{{ route('dailyactivityplanner.index') }}",--}}
                        url: "{{ route('dailyactivityplanner.getdailyactivity') }}",
                        data: { "work_date": $('#work_date').val(),
                            _token: "{{csrf_token()}}"},
                        type: "post",

                    },
                    bDestroy: true,
                    columns: [
                        {
                            data: 'start_hour',
                            name: 'From'
                        },
                        {
                            data: 'end_hour',
                            name: 'To'
                        },
                        {
                            data: 'planned_activity',
                            name: 'planned_activity'
                        },
                        {
                            data: 'actual_activity',
                            name: 'actual_activity'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        }
                    ]
            });
        }

        $('#create_record').click(function(){
            $('.modal-title').text('Add DAP Detail');
            $('#hidden_id').val('');
            Date.prototype.toDateInputValue = (function() {
                var local = new Date(this);
                local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                return local.toJSON().slice(0,10);
            });
            $('#transact_date').val(new Date().toDateInputValue());
            $('#plannedactivity').val('');
            $('#actualactivity').val('');
            $('#status').val('');

            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formModal').modal('show');
        });

        $('#sample_form').on('submit', function(event){

            event.preventDefault();

            var action_url = '';
            if($('#action').val() == 'Add')
            {
                action_url = "{{ route('dailyactivityplanner.store') }}";
            }

            if($('#action').val() == 'Edit')
            {
                action_url = "{{ route('dailyactivityplanner.update') }}";
            }
            $.ajax({
                url: action_url,
                method:"POST",
                // data:$(this).serialize(),
                data: new FormData(this),   //use this to ajax post with file
                processData: false,
                contentType: false,
                // dataType:"json",
                success:function(data)
                {
                    var html = '';
                    if(data.errors)
                    {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++)
                        {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                    }
                    if(data.success)
                    {
                        // html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#sample_form')[0].reset();
                        $('#dap_table').DataTable().ajax.reload();
                        $('#formModal').modal('hide');
                    }
                    $('#form_result').html(html);
                }
            });
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url :"dailyactivityplanner/"+id+"/edit",
                dataType:"json",
                success:function(data)
                {
                    $('#hidden_id').val(id);

                    document.getElementById("transact_date").value = data.result.transact_date;
                    document.getElementById("transact_date").readOnly = true;

                    $('#from_dropdown').html('<option selected="selected" value="">Select Work Hour</option>');
                    $.each(data.workhours, function (key, value) {
                        if(value.id == data.result.hour_from) {
                            $('#from_dropdown').append('<option selected="selected" ' + 'value="' + value.id + '">' + value.name + ' </option>');
                        }
                        else {
                            $('#from_dropdown').append('<option value="' + value.id + '">' + value.name + '</option>');
                        }
                    });
                    $('#to_dropdown').html('<option selected="selected" value="">Select Work Hour</option>');
                    $.each(data.workhours, function (key, value) {
                        if(value.id == data.result.hour_to) {
                            $('#to_dropdown').append('<option selected="selected" ' + 'value="' + value.id + '">' + value.name + ' </option>');
                        }
                        else {
                            $('#to_dropdown').append('<option value="' + value.id + '">' + value.name + '</option>');
                        }
                    });
                    $('#planned_activity').val(data.result.planned_activity);
                    $('#actual_activity').val(data.result.actual_activity);
                    $('#status').val(data.result.status);
                    $('.modal-title').text('Edit DAP Detail');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        var dap_id;

        $(document).on('click', '.delete', function(){
            dap_id = $(this).attr('id');
            $('.modal-title').text('Confirmation');
            $('#ok_button').text('OK');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function(){
            $.ajax({
                url:"dailyactivityplanner/destroy/"+dap_id,
                beforeSend:function(){
                    $('#ok_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#dap_table').DataTable().ajax.reload();
                        // alert('Record Deleted');
                    }, 1000);
                }
            })
        });

        $('#download_dap').click(function(){
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                url: "dailyactivityplanner/getdap",
                method: "POST",
                data: { _token: "{{csrf_token()}}",
                    "work_date": $('#work_date').val(),
                },
                success: function(data, status, xhr) {
                    // alert('file should be downloaded');

                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : 'DAP.xlsx');

                    // The actual download
                    var blob = new Blob([data], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);

                },
                fail: function(data) {
                    alert('not downloaded');
                }
            })
        });

        jQuery.validator.addMethod(
            "dateFormat",
            function(value, element) {
                var check = false;
                var re = /^\d{1,4}\-\d{1,2}\-\d{2}$/;
                if( re.test(value)){
                    var adata = value.split('-');
                    var yyyy = parseInt(adata[0],10);
                    var mm = parseInt(adata[1],10);
                    var dd = parseInt(adata[2],10);
                    var xdata = new Date(yyyy,mm-1,dd);
                    if ( ( xdata.getFullYear() === yyyy ) && ( xdata.getMonth () === mm - 1 ) && ( xdata.getDate() === dd ) ) {
                        check = true;
                    }
                    else {
                        check = false;
                    }
                } else {
                    check = false;
                }
                return this.optional(element) || check;
            },
            "Invalid date format."
        );


        if ($('#myform').length > 0) {
            $('#myform').validate({

                rules: {
                    work_date: {
                        required: true,
                        date: true,
                        dateFormat: true
                    }
                },
                messages: {
                    work_date: {
                        required: "This field is required.",
                        date: "Invalid date."
                    },
                },
                submitHandler: function(myform) {

                    loadDailyActivity();

                    return false; // extra insurance preventing the default form action
                }
            })
        }

    </script>
@stop
