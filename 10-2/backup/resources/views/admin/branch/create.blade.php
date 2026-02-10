@extends('layouts.app')

@section('title', 'Add Branch')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Messages --}}
            @include('common.alert')

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Add Branch</h4>
                        <div class="page-title-right">
                            <a href="{{ route('branch.index') }}"
                                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('branch.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="row">
                                        <div class="col-md-6 mt-4">                        
                                           <div class="form-group {{ $errors->has('branch_name') ? 'has-error' : '' }}">
                                                <label for="branch_name">Branch Name <span style="color:red;">*</span></label>
                                                <input type="text" name="branch_name" id="branch_name" class="form-control" value="{{ old('branch_name') }}" maxlength="100" placeholder="Enter Branch Name" required>
                                                @if($errors->has('branch_name'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('branch_name') }}
                                                    </span>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-4">                        
                                           <div class="form-group {{ $errors->has('branch_ip') ? 'has-error' : '' }}">
                                                <label for="branch_ip">Branch IP <span style="color:red;">*</span></label>
                                                <input type="text" name="branch_ip" id="branch_ip" class="form-control" value="{{ old('branch_ip') }}" placeholder="Enter Branch IP" onkeyup="this.value = this.value.replace(/[^0-9.]/g, '')" title="Please enter a valid IPv4 address like 192.168.1.1"  maxlength="20" required>
                                                @if($errors->has('branch_ip'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('branch_ip') }}
                                                    </span>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-4">                        
                                           <div class="form-group {{ $errors->has('branch_address') ? 'has-error' : '' }}">
                                                <label for="branch_address">Branch Address <span style="color:red;">*</span></label>
                                                <textarea  name="branch_address" id="branch_address" class="form-control" value="{{ old('branch_address') }}" placeholder="Enter Branch Address" maxlength="255">{{ old('branch_address') }}</textarea>
                                                @if($errors->has('branch_address'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('branch_address') }}
                                                    </span>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-4">                        
                                           <div class="form-group {{ $errors->has('branch_emailId') ? 'has-error' : '' }}">
                                                <label for="branch_emailId">Branch Email <span style="color:red;">*</span></label>
                                                <input type="email" name="branch_emailId" id="branch_emailId" class="form-control" value="{{ old('branch_emailId') }}" placeholder="Enter Branch Email" maxlength="100">
                                                @if($errors->has('branch_emailId'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('branch_emailId') }}
                                                    </span>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-4">                        
                                           <div class="form-group {{ $errors->has('branch_phone') ? 'has-error' : '' }}">
                                                <label for="branch_phone">Branch Phone <span style="color:red;">*</span></label>
                                                <input type="text" name="branch_phone" id="branch_phone" class="form-control" value="{{ old('branch_phone') }}" placeholder="Enter Branch Phone" minlength="5" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                @if($errors->has('branch_phone'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('branch_phone') }}
                                                    </span>
                                                @endif

                                            </div>
                                        </div>
                                     </div>                                    
                                        <div class="card-footer mt-2">
                                            <div class="mb-3" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                            <button type="reset" class="btn btn-primary float-right mr-3 mb-3 mx-2" >Clear</button>
                                    </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
