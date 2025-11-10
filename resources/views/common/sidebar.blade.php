<?php 
if(auth()->user())
{
$roleid = auth()->user()->role_id;
}else{

$roleid = Auth::guard('web_employees')->user()->role_id;
}
?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu"></span></li>
                @if($roleid == '1' && $roleid != '2')

                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('home')) {{ 'active' }} @endif"
                        href="{{ route('home') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list text-white"></i> Master Entry </a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('branch.index')) {{ 'active' }} @endif"
                                    href="{{ route('branch.index') }}">
                                    <i class="fa fa-code-branch"></i>
                                    <span data-key="t-dashboards">Branch Master</span>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('customerCategory.index')) {{ 'active' }} @endif"
                                    href="{{ route('customerCategory.index') }}">
                                    <i class="fa fa-user-tie"></i>
                                    <span data-key="t-dashboards">Customer Category</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('castMaster.index')) {{ 'active' }} @endif"
                                    href="{{ route('castMaster.index') }}">
                                <i class="fa-solid fa-hands-praying"></i>
                                    <span data-key="t-dashboards">Customer Cast</span>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('color.index')) {{ 'active' }} @endif"
                                    href="{{ route('color.index') }}">
                                    <i class="fa fa-paint-brush"></i>
                                    <span data-key="t-dashboards">Color Master</span>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('closeReason.index')) {{ 'active' }} @endif"
                                    href="{{ route('closeReason.index') }}">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span data-key="t-dashboards">Close Reason</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('productCategory.index')) {{ 'active' }} @endif"
                                    href="{{ route('productCategory.index') }}">
                                    <i class="fa fa-gem"></i>
                                    <span data-key="t-dashboards">Product Category</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('product.index')) {{ 'active' }} @endif"
                                    href="{{ route('product.index') }}">
                                    <i class="fa fa-box"></i>
                                    <span data-key="t-dashboards">Product Master</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('purity.index')) {{ 'active' }} @endif"
                                    href="{{ route('purity.index') }}">
                                    <i class="fa fa-coins"></i>
                                    <span data-key="t-dashboards">Purity Master</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('orderStatus.index')) {{ 'active' }} @endif"
                                    href="{{ route('orderStatus.index') }}">
                                    <i class="fas fa-truck"></i>
                                    <span data-key="t-dashboards">Delivery Status Master</span>
                                </a>
                            </li>
                             
                        </ul>
                    </div>
                </li>
             
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('empMaster.index')) {{ 'active' }} @endif"
                        href="{{ route('empMaster.index') }}">
                        <i class="fa-solid fa-users"></i>
                        <span data-key="t-dashboards">Employee Master</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('vendorMaster.index')) {{ 'active' }} @endif"
                        href="{{ route('vendorMaster.index') }}">
                        <i class="fas fa-store"></i>
                        <span data-key="t-dashboards">Vendor Master</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('customer.index')) {{ 'active' }} @endif"
                        href="{{ route('customer.index') }}">
                        <i class="fa-solid fa-user"></i>
                        <span data-key="t-dashboards">Customer Master</span>
                    </a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('custOrder.index')) {{ 'active' }} @endif"
                        href="{{ route('custOrder.index') }}">
                        <i class="
                        fas fa-shopping-bag"></i>
                        <span data-key="t-dashboards">Order Product</span>
                    </a>
                </li>
              <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list text-white"></i>Reports</a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('reports.stock_analysis')) {{ 'active' }} @endif"
                                    href="{{ route('reports.stock_analysis') }}">
                                    <i class="fas fa-chart-line"></i>
                                    <span data-key="t-dashboards">Storck Analysis</span>
                                </a>
                            </li>                            
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('reports.staff_analysis')) {{ 'active' }} @endif"
                                    href="{{ route('reports.staff_analysis') }}">
                                    <i class="fa fa-users"></i>
                                    <span data-key="t-dashboards">Sales Staff Analysis</span>
                                </a>
                            </li>   

                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('reports.cancel_reason_report')) {{ 'active' }} @endif"
                                    href="{{ route('reports.cancel_reason_report') }}">
                                    <i class="fas fa-user-times"></i>
                                    <span data-key="t-dashboards">Cancel Reason Report</span>
                                </a>
                            </li>  
                            <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('reports.monthly_conversion')) {{ 'active' }} @endif"
                                    href="{{ route('reports.monthly_conversion') }}">
                                    <i class="fas fa-calendar"></i>
                                    <span data-key="t-dashboards">Monthly Conversion</span>
                                </a>
                            </li>  
                             <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('reports.visit_report')) {{ 'active' }} @endif"
                                    href="{{ route('reports.visit_report') }}">
                                    <i class="fa fa-user-check"></i>
                                    <span data-key="t-dashboards">Visit Report</span>
                                </a>
                            </li>   
                             <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('reports.order_report')) {{ 'active' }} @endif"
                                    href="{{ route('reports.order_report') }}">
                                    <i class="fa fa-receipt"></i>
                                    <span data-key="t-dashboards">Order Report</span>
                                </a>
                            </li>    
                             <li class="nav-item">
                                <a class="nav-link menu-link @if (request()->routeIs('reports.collection_report')) {{ 'active' }} @endif"
                                    href="{{ route('reports.collection_report') }}">
                                    <i class="fa fa-file"></i>
                                    <span data-key="t-dashboards">Sales Staff Order Report</span>
                                </a>
                            </li>  
                        </ul>
                    </div>
                </li>
                 @endif
            @if(($roleid == '2' && $roleid != '1'))
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('userhome')) {{ 'active' }} @endif"
                        href="{{ route('userhome') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('EMPcustomer.index')) {{ 'active' }} @endif"
                        href="{{ route('EMPcustomer.index') }}">
                        <i class="fa-solid fa-user"></i>
                        <span data-key="t-dashboards">Customer Master</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('EMPcustOrder.index')) {{ 'active' }} @endif"
                        href="{{ route('EMPcustOrder.index') }}">
                        <i class="
                        fas fa-shopping-bag"></i>
                        <span data-key="t-dashboards">Order Product</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>