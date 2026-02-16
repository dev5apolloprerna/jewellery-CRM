@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Edit Branch</h4>
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
                                <form action="{{ route('branch.update', [$BranchMaster->branch_id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="branch_id" id="branch_id" value="{{ $BranchMaster->branch_id }}">
                                    <div class="row">
                                         
                                            <div class="col-md-6 mt-4">                        
                                               <div class="form-group {{ $errors->has('branch_name') ? 'has-error' : '' }}">
                                                    <label for="branch_name">Branch Name <span style="color:red;">*</span></label>
                                                <input type="text" id="branch_name" name="branch_name" class="form-control" value="{{ $BranchMaster->branch_name }}"  maxlength="100" placeholder="Enter Branch Name" required>
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
                                                <input type="text" name="branch_ip" id="branch_ip" class="form-control" value="{{ $BranchMaster->branch_ip }}" onkeyup="this.value = this.value.replace(/[^0-9.]/g, '')" title="Please enter a valid IPv4 address like 192.168.1.1" placeholder="Enter Branch IP" maxlength="20" required>
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
                                                <textarea  name="branch_address" id="branch_address" class="form-control" placeholder="Enter Branch Address" maxlength="100" required>{{ $BranchMaster->branch_address }}</textarea>
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
                                                <input type="email" name="branch_emailId" id="branch_emailId" class="form-control" value="{{ $BranchMaster->branch_emailId }}" placeholder="Enter Branch Email" maxlength="100" required>
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
                                                <input type="phone" name="branch_phone" id="branch_phone" class="form-control" value="{{ $BranchMaster->branch_phone }}" placeholder="Enter Branch Phone" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" minlength="5" maxlength="10" required>
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
                                                class="btn btn-success btn-user float-right" >Update</button>
                                                <a class="btn btn-primary float-right mr-3"
                                                    href="{{ route('branch.index') }}">Cancel</a>
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
