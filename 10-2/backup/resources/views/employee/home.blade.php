@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">


        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">

                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                            
                                    </div><!-- end card header -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

                            <div class="row">
                                  <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate bg-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                                        Today Followup</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                        <span class="counter-value"
                                                            data-target="{{ $todayCount }}">0</span>
                                                    </h4>
                                                    <a href="{{ route('EMPvisit.today') }}"
                                                        class="text-decoration-underline text-white-50">View
                                                        Today Followup</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate bg-success">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                                        Overdue Followup</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                        <span class="counter-value"
                                                            data-target="{{ $overdueCount }}">0</span>
                                                    </h4>
                                                    <a href="{{ route('EMPvisit.overdue') }}"
                                                        class="text-decoration-underline text-white-50">View
                                                        Overdue Followup</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                                        <i class="fa fa-clock"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate bg-secondary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                                       Payment Reminder</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                        <span class="counter-value"
                                                            data-target="{{ $PaymentReminder }}">0</span>
                                                    </h4>
                                                    <a href="{{ route('EMPcustOrder.index', ['date' => \Carbon\Carbon::today()->toDateString()]) }}"
                                                        class="text-decoration-underline text-white-50">View
                                                         Payment Reminder</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                                        <i class="fa-regular fa-credit-card"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6"></div>

                              <div class="col-xl-3 col-md-6">
                                    <div class="card card-animate bg-secondary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                                        Product Show</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                        <span class="counter-value"
                                                            data-target="{{ $attendedClient }}">0</span>
                                                    </h4>
                                                    <a href="#"
                                                        class="text-decoration-underline text-white-50">View
                                                        Product Show</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                                        <i class="fa fa-users"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-animate bg-danger">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                                        Product Sold</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                        <span class="counter-value"
                                                            data-target="{{ $convertedClient }}">0</span>
                                                    </h4>
                                                    <a href="#"
                                                        class="text-decoration-underline text-white-50">View
                                                        Product Sold</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                                        <i class="fa fa-users"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-animate bg-info">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                                        Converted Ratio</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                        <span class="counter-value" data-target="{{ $convertedRatio }}">0</span><span class="text-white">%</span>
                                                    </h4>

                                                    <a href="#"
                                                        class="text-decoration-underline text-white-50">View
                                                        Converted Ratio</a>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                                        <i class="fa fa-users"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © {{ env('APP_NAME') }}
                    </div>

                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->


@endsection
