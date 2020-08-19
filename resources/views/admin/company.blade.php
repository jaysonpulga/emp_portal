@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Companies</h1>
@stop

@section('content')
    <div align="right">
        <button type="button" name="create_record" id="create_record" class="btn btn-success">Add Company</button>
    </div>
    <br />
    <div class="col-12">
        <table id="company_table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th width="35%">ID</th>
                <th width="35%">Name</th>
                <th width="30%">Action</th>
            </tr>
            </thead>
        </table>
    </div>
    <br />
    <br />
@stop

@extends('footer')

<div class="modal fade" id="formModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Company</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-md-4">Name</label>
                        <div class="col-md-8">
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" class="btn btn-primary" value="Save" />
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
            $('#company_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('company.index') }}",
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ]
            });

            $('#create_record').click(function(){
                $('.modal-title').text('Add Company');
                $('#name').val('');
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
                    action_url = "{{ route('company.store') }}";
                }

                if($('#action').val() == 'Edit')
                {
                    action_url = "{{ route('company.update') }}";
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
                            $('#company_table').DataTable().ajax.reload();
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
                    url :"company/"+id+"/edit",
                    dataType:"json",
                    success:function(data)
                    {
                        $('#name').val(data.result.name);
                        $('#hidden_id').val(id);
                        $('.modal-title').text('Edit Company');
                        $('#action_button').val('Edit');
                        $('#action').val('Edit');
                        $('#formModal').modal('show');
                    }
                })
            });

            var company_id;

            $(document).on('click', '.delete', function(){
                company_id = $(this).attr('id');
                $('.modal-title').text('Confirmation');
                $('#ok_button').text('OK');
                $('#confirmModal').modal('show');
            });

            $('#ok_button').click(function(){
                $.ajax({
                    url:"company/destroy/"+company_id,
                    beforeSend:function(){
                        $('#ok_button').text('Deleting...');
                    },
                    success:function(data)
                    {
                        setTimeout(function(){
                            $('#confirmModal').modal('hide');
                            $('#company_table').DataTable().ajax.reload();
                            // alert('Record Deleted');
                        }, 1000);
                    }
                })
            });
        });
    </script>
@stop
