@extends('adminlte::page')

@section('title', 'Bilrey Group Employee Portal')

@section('content_header')
    <h1>Employee Profile</h1>
@stop

@section('content')
    <form action="{{route('profile.post')}}" method="POST">
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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="employeeName" >Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $employee->name) }}" readonly style="text-transform:uppercase">
                    <div class="text-red">
                        @if($errors->has('name'))
                            {{ $errors->first('name') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="employeeAddress" >Address</label>
                    <input type="text" class="form-control" name="address" value="{{ old('address', $employee->address) }}" style="text-transform:uppercase">
                    <div class="text-red">
                        @if($errors->has('address'))
                            {{ $errors->first('address') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="employeeMobile" >Contact Number</label>
                    <input type="text" class="form-control" name="mobile" value="{{ old('mobile', $employee->mobile) }}">
                    <div class="text-red">
                        @if($errors->has('mobile'))
                            {{ $errors->first('mobile') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="employeeCompanyID">Company</label>
                    <select class="form-control" name="company_id" id="company_id"> <option value="{{ old('company_id', $employee->company_id) }}">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}"
                                    @if ($company->id == old('company_id', $employee->company_id))
                                    selected="selected"
                                @endif
                            > {{ $company->name }} </option>
                        @endforeach
                    </select>
                    <div class="text-red">
                        @if($errors->has('company_id'))
                            {{ $errors->first('company_id') }}
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="employeeMobile" >Position/Designation</label>
                    <input type="text" class="form-control" name="designation" value="{{ old('designation', $employee->designation) }}">
                    <div class="text-red">
                        @if($errors->has('designation'))
                            {{ $errors->first('designation') }}
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

@section('js')
    <script> console.log('Hi!'); </script>
@stop

