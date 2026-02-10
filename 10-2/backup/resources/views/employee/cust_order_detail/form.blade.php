@extends('layouts.app')

@section('content')
<h2>{{ isset($detail) ? 'Edit Order Detail' : 'Add Order Detail' }}</h2>


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">{{ isset($detail) ? 'Edit Order Detail' : 'Add Order Detail' }}    </h4>
                            <div class="page-title-right">
                                <a href="{{ url()->previous() }}"
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

                                      <form method="POST" action="{{ isset($detail)  ? route('EMPcustOrderDetail.update', $detail->detail_order_id) : route('EMPcustOrderDetail.store', $order->order_id) }}">

                                        @csrf
                                        @if(isset($detail))
                                            @method('PUT')
                                        @endif

                                        <div class="row gy-4">
                                        <input type="hidden" name="cust_id" value="{{ $order->order_id ?? '' }}" class="form-control" required><br>
                                        <input type="hidden" name="branch_id" value="{{ $branch_id ?? '' }}" class="form-control" required><br>

                                         <div class="col-lg-6 col-md-6">
                                             <label for="product_id" class="form-label">Product <span style="color:red;">*</span></label>
                                                <select class="form-control" name="product_id" id="product_id" required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->product_id }}" {{ old('product_id', $detail->product_id ?? '') == $product->product_id ? 'selected' : '' }}>
                                                            {{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('product_id') <small class="text-danger">{{ $message }}</small> @enderror

                                            </div>

                                        

                                        <div class="col-lg-6 col-md-6">
                                             <label for="karat" class="form-label">Karat <span style="color:red;">*</span></label>
                                            <input type="text" name="karat" class="form-control" value="{{ old('karat', $detail->karat ?? '') }}" maxlength="100" placeholder="Enter Karat" required>
                                            @error('karat') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                             <label for="color_id" class="form-label">Color <span style="color:red;">*</span></label>
                                                <select class="form-control" name="color_id" id="color_id" required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($color as $c)
                                                        <option value="{{ $c->color_id }}" {{ old('color_id', $detail->color_id ?? '') == $c->color_id ? 'selected' : '' }}>
                                                            {{ $c->color_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('color_id') <small class="text-danger">{{ $message }}</small> @enderror

                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label for="weight" class="form-label">Weight <span style="color:red;">*</span></label>
                                            <input type="text" step="0.01" name="weight" class="form-control" value="{{ old('weight', $detail->weight ?? '') }}" maxlength="50" placeholder="Enter Weight"  required>
                                            @error('weight') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                             <label for="size" class="form-label">Size <span style="color:red;">*</span></label>
                                            <input type="text" name="size" class="form-control"  value="{{ old('size', $detail->size ?? '') }}" maxlength="50" placeholder="Enter Size" required>
                                            @error('size') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                           <label for="refer_tag_number" class="form-label">Reference Tag Number <span style="color:red;">*</span></label>
                                             <input type="text" name="refer_tag_number" class="form-control"  value="{{ old('refer_tag_number', $detail->refer_tag_number ?? '') }}" placeholder="Enter Reference Tag Number"  maxlength="50" required>
                                            @error('refer_tag_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                      <div class="col-lg-6 col-md-6">
                                           <label for="status" class="form-label">Status <span style="color:red;">*</span></label>
                                             <input type="text" name="status" placeholder="Enter Status" class="form-control" value="{{ old('status', $detail->status ?? '') }}" maxlength="50" required>
                                            @error('status') <small class="text-danger">{{ $message }}</small> @enderror

                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                          <label for="amount" class="form-label">Amount <span style="color:red;">*</span></label>
                                            <input type="number" step="0.01" name="amount" class="form-control"  value="{{ old('amount', $detail->amount ?? '') }}" maxlength="50" placeholder="Enter Amount" required>
                                            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror

                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                          <label for="net_total" class="form-label">Net Total <span style="color:red;">*</span></label>
                                            <input type="number" step="0.01" name="net_total" placeholder="Enter Net Total" class="form-control"  value="{{ old('net_total', $detail->net_total ?? '') }}" maxlength="50" required>
                                            @error('net_total') <small class="text-danger">{{ $message }}</small> @enderror

                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                          <label for="given_to" class="form-label">Given To <span style="color:red;">*</span></label>
                                            <input type="text" name="given_to" class="form-control" placeholder="Enter Given To" value="{{ old('given_to', $detail->given_to ?? '') }}" maxlength="50" required>
                                            @error('given_to') <small class="text-danger">{{ $message }}</small> @enderror

                                        </div>
                                    </div>
                                         <div class="card-footer mt-2">
                                            <div class="mb-3" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                                @if(isset($detail))
                                            <a class="btn btn-primary float-right mr-3 mb-3 mx-2"
                                                href="{{ route('EMPcustOrder.index', ['id' => $order->cust_id ?? $id]) }}">Cancel</a>
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
