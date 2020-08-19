@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Download Daily Movement Tracker</h1>
@stop

@section('content')
    <form action="{{route('downloaddmt.getdmt')}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="mr-2">Work Date</label>
                    <input type="text" name="work_date" id="work_date" class="form-control mr-2 " value="{{old('work_date', $work_date)}}" placeholder="yyyy-mm-dd" required>
                    <div class="text-red">
                        @if($errors->has('work_date'))
                            {{ $errors->first('work_date') }}
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
