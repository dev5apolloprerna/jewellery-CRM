@extends('layouts.app')

@section('content')
<h2>{{ isset($order) ? 'Edit Order' : 'Add Order' }}</h2>


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">{{ isset($order) ? 'Edit Order' : 'Add Order' }}    </h4>
                            <div class="page-title-right">
                                <a href="{{ route('EMPcustOrder.index', ['id' => $order->cust_id ?? $id]) }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">

                                    <form method="POST" action="{{ isset($order) ? route('EMPcustOrder.update', $order->order_id) : route('EMPcustOrder.store') }}">
                                        @csrf
                                        @if(isset($order))
                                            @method('PUT')
                                        @endif
                                        <div class="row gy-4">
                                        <input type="hidden" name="cust_id" value="{{ $order->cust_id ?? ($id ?? '') }}" class="form-control" required><br>
                                        <input type="hidden" name="branch_id" value="{{ $branch_id }}" class="form-control" required><br>
                                         <div class="col-lg-6 col-md-6">
                                            <label for="emp_name" class="form-label">Employee Name <span style="color:red;">*</span></label>
                                            <select class="form-control" name="emp_id" id="emp_id" required>
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->emp_id }}" {{ old('emp_id', $order->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
                                                        {{ $emp->emp_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('emp_id') <small class="text-danger">{{ $message }}</small> @enderror

                                        </div>

                                        

                                        <div class="col-lg-6 col-md-6">
                                             <label>Amount <span style="color:red;">*</span></label>
                                            <input type="number" name="amount" value="{{ $order->amount ?? '' }}" class="form-control"  placeholder="Enter Amount" maxlength="50" required><br>
                                            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label>Net Total<span style="color:red;">*</span></label>
                                            <input type="number" name="net_total" value="{{ $order->net_total ?? '' }}" placeholder="Enter Net Total" class="form-control" maxlength="50" required><br>
                                            @error('net_total') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label>Advance Payment <span style="color:red;">*</span></label>
                                            <input type="number" name="advance_payment" value="{{ $order->advance_payment ?? '' }}" class="form-control" maxlength="50" placeholder="Enter Advance Payment" required><br>
                                            @error('advance_payment') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label>Remark <span style="color:red;">*</span></label>
                                            <textarea name="remark" class="form-control" maxlength="255" placeholder="Enter Remark" required>{{ $order->remark ?? '' }}</textarea><br>
                                            @error('remark') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label>Rate Type <span style="color:red;">*</span></label>
                                            <input type="text" name="rate_type" value="{{ $order->rate_type ?? '' }}" placeholder="Enter Rate Type" class="form-control" maxlength="100" required><br>
                                            @error('rate_type') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                      <div class="col-lg-6 col-md-6">
                                            <label>Delivery Type <span style="color:red;">*</span></label>
                                            <input type="text" name="delivery_type" value="{{ $order->delivery_type ?? '' }}" placeholder="Enter Delivery Type" class="form-control" maxlength="50" required><br>
                                            @error('delivery_type') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                        <div class="card-footer mt-2">
                                            <div class="mb-3" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                                @if(isset($order))
                                                 <a class="btn btn-primary float-right mr-3 mb-3 mx-2"  href="{{ route('EMPcustOrder.index', ['id' => $order->cust_id ?? $id]) }}">Cancel</a>

                                                @else
                                                <button type="reset" class="btn btn-primary float-right mr-3 mb-3 mx-2" >Clear</button>

                                                @endif
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
    </div>
@endsection
