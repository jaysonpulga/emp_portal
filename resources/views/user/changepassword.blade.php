@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Change Password</h1>
@stop

@section('content')
    <form action="{{route('password.update')}}" method="POST">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Please check the form below for errors.
            </div>
        @endif
        @if(session()->has('message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" class="form-control" name="old_password" value="{{ old('old_password') }}">
                    <div class="text-red">
                        @if($errors->has('old_password'))
                            {{ $errors->first('old_password') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="form-control" name="password" value="{{ old('password') }}">
                    <div class="text-red">
                        @if($errors->has('password'))
                            {{ $errors->first('password') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}">
                    <div class="text-red">
                        @if($errors->has('password_confirmation'))
                            {{ $errors->first('password_confirmation') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
@stop

@extends('footer')


@section('js')
    <script> console.log('Hi!'); </script>
@stop
