@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Home</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn"></i>
                        Important Announcements
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="callout callout-info">
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop

@extends('footer')


@section('js')
    <script> console.log('Hi!'); </script>
@stop
