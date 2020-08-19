@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Daily Movement Tracker</h1>
@stop

@section('content')
    <form action="" id="myform">
        <div class="row mb-2">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="mr-2">Work Date From</label>
                    <input type="text" name="work_date_from" id="work_date_from" class="form-control mr-2 " value="{{old('work_date_from')}}" placeholder="yyyy-mm-dd">
                </div>
                <div class="form-group">
                    <label class="mr-2">Work Date To</label>
                    <input type="text" name="work_date_to" id="work_date_to" class="form-control mr-2 " value="{{old('work_date_to')}}" placeholder="yyyy-mm-dd">
                </div>
                {{--<div class="input-group">--}}
                    {{--<span class="input-group-addon mr-2">-</span>--}}
                {{--</div>--}}
                <div class="form-group">
                    <button type="submit" class="btn btn-outline-primary" name="set_date" id="set_date" value="Submit">Set</button>
                </div>
            </div>
            <div class="col-md-9">
                <div class="btn-group float-right">
                    <button type="button" name="create_record" id="create_record" class="btn btn-success">Add Daily Movement</button>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-12">
            <table id="dmt_table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="15%">Date</th>
                    <th width="25%">Places You Have Gone</th>
                    <th width="25%">People You Have Met</th>
                    <th width="20%">Mode of Transportation</th>
                    <th width="15%">Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@extends('footer')

<div class="modal fade" id="formModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Daily Movement</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="">Work Date</label>
                                <input type="text" name="transact_date" id="transact_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Where did you go today?</label>
                                <textarea class="form-control" name="places" id="places" rows="4" placeholder="Own Residence, Petron Office, Malumanay Office, Dorm, Aramismis Staffhouse, NHA QC Office, SM Makati etc."></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Who are the people you interacted today with for more than 15 minutes?</label>
                                <textarea class="form-control" name="people" id="people" rows="4" placeholder="Please provide the names"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">What are the modes of transportation that you used?</label>
                                <textarea class="form-control" name="modeoftranspo" id="modeoftranspo" rows="4" placeholder="Example: Grandia Shuttle, Adventure Shuttle, Bus, Grab Car, Taxi, Jeep, LRT, MRT, UV, Tricycle etc."></textarea>
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

            var curr = new Date; // get current date
            var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
            var last = first + 6; // last day is the first day + 6

            var firstday = new Date(curr.setDate(first)).toDateInputValue();
            var lastday = new Date(curr.setDate(last)).toDateInputValue();

            $('#work_date_from').val(firstday);
            $('#work_date_to').val(lastday);

            loadDailyActivity();

        });

        var table;

        function loadDailyActivity() {
            table = $('#dmt_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dailymovementtracker.getdailymovement') }}",
                    data: { "work_date_from": $('#work_date_from').val(),
                        "work_date_to": $('#work_date_to').val(),
                        _token: "{{csrf_token()}}"},
                    type: "post",
                },
                bDestroy: true,
                columns: [
                    {
                        data: 'transact_date',
                        name: 'transact_date'
                    },
                    {
                        data: 'places',
                        name: 'places'
                    },
                    {
                        data: 'people',
                        name: 'people'
                    },
                    {
                        data: 'modeoftranspo',
                        name: 'modeoftranspo'
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
            $('.modal-title').text('Add Daily Movement');
            $('#hidden_id').val('');
            Date.prototype.toDateInputValue = (function() {
                var local = new Date(this);
                local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                return local.toJSON().slice(0,10);
            });
            $('#transact_date').val(new Date().toDateInputValue());
            $('#places').val('');
            $('#people').val('');
            $('#modeoftranspo').val('');

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
                action_url = "{{ route('dailymovementtracker.store') }}";
            }

            if($('#action').val() == 'Edit')
            {
                action_url = "{{ route('dailymovementtracker.update') }}";
            }
            $.ajax({
                url: action_url,
                method:"POST",
                data:$(this).serialize(),
                dataType:"json",
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
                        $('#dmt_table').DataTable().ajax.reload();
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
                url :"dailymovementtracker/"+id+"/edit",
                dataType:"json",
                success:function(data)
                {
                    $('#hidden_id').val(id);
                    document.getElementById("transact_date").value = data.result.transact_date;
                    document.getElementById("transact_date").readOnly = true;
                    $('#places').val(data.result.places);
                    $('#people').val(data.result.people);
                    $('#modeoftranspo').val(data.result.modeoftranspo);
                    $('.modal-title').text('Edit Daily Movement');
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
                url:"dailymovementtracker/destroy/"+dap_id,
                beforeSend:function(){
                    $('#ok_button').text('Deleting...');
                },
                success:function(data)
                {
                    setTimeout(function(){
                        $('#confirmModal').modal('hide');
                        $('#dmt_table').DataTable().ajax.reload();
                        // alert('Record Deleted');
                    }, 1000);
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

        jQuery.validator.addMethod("greaterThan",
            function(value, element, params) {

                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) >= new Date($(params).val());
                }

                return isNaN(value) && isNaN($(params).val())
                    || (Number(value) >= Number($(params).val()));
            },'Must be greater than from value.');


        if ($('#myform').length > 0) {
            $('#myform').validate({

                rules: {
                    work_date_from: {
                        required: true,
                        date: true,
                        dateFormat: true
                    },
                    work_date_to: {
                        required: true,
                        date: true,
                        dateFormat: true,
                        greaterThan: "#work_date_from"
                    }
                },
                messages: {
                    work_date_from: {
                        required: "This field is required.",
                        date: "Invalid date."
                    },
                    work_date_to: {
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
