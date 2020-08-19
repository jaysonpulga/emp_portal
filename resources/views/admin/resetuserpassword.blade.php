@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Reset User Password</h1>
@stop

@section('content')
    <form action="{{route('resetuserpassword.reset')}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="">Employee Email</label>
                    <input type="text" name="email" class="form-control" value="{{old('email')}}" placeholder="Email">
                    <div class="text-red">
                        @if($errors->has('email'))
                            {{ $errors->first('email') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="">New Password</label>
                    <input type="password" name="password" class="form-control" value="{{old('password')}}" placeholder="New Password">
                    <div class="text-red">
                        @if($errors->has('password'))
                            {{ $errors->first('password') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" value="{{old('password_confirmation')}}" placeholder="Confirm Password">
                    <div class="text-red">
                        @if($errors->has('password_confirmation'))
                            {{ $errors->first('password_confirmation') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                @if(session()->has('message'))
                    <div class="text text-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </div>
        </div>
    </form>
@stop

@extends('footer')


@section('js')
    <script> console.log('Hi!'); </script>
@stop
