<?php
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CustomerCategoryController;
use App\Http\Controllers\Admin\CastMasterController;
use App\Http\Controllers\Admin\ProductCategoryController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BranchMasterController;
use App\Http\Controllers\Admin\EmployeeMasterController;
use App\Http\Controllers\Admin\VendorMasterController;
use App\Http\Controllers\Admin\CustomerMasterController;
use App\Http\Controllers\Admin\CustomerProductController;
use App\Http\Controllers\Admin\CustomerFollowupController;
use App\Http\Controllers\Admin\CustomerOrderController;
use App\Http\Controllers\Admin\CustomerOrderDetailController;
use App\Http\Controllers\Admin\OrderPaymentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CustomerVisiteController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\PurityController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\CloseReasonController;
use App\Http\Controllers\Admin\ReportController;


/*-----------------------------------Employee Controller---------------------------------*/
use App\Http\Controllers\Employee\EmployeeLoginController;
use App\Http\Controllers\Employee\EmployeeHomeController;
use App\Http\Controllers\Employee\EMPCustomerVisitController;


/*-----------------------------------Front Controller---------------------------------*/
use App\Http\Controllers\Front\FrontProductController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\ContactusController;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
     return view('errors.404');
});

Route::get('/login', function () {
    return redirect()->route('login');
});


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/edit', [HomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users
Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id?}', [UserController::class, 'edit'])->name('edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');
    Route::post('/password-update/{Id?}', [UserController::class, 'passwordupdate'])->name('passwordupdate');
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');
    Route::get('export/', [UserController::class, 'export'])->name('export');
});

//Category Master

Route::resource('admin/customerCategory', App\Http\Controllers\Admin\CustomerCategoryController::class);

Route::prefix('admin')->name('customerCategory.')->middleware('auth')->group(function () {
    Route::get('/customer-category/index', [CustomerCategoryController::class, 'index'])->name('index');
    Route::get('/customer-category/create', [CustomerCategoryController::class, 'create'])->name('create');
    Route::post('/customer-category/store', [CustomerCategoryController::class, 'store'])->name('store');
    Route::get('/customer-category/edit/{id?}', [CustomerCategoryController::class, 'editview'])->name('edit');
    Route::post('/customer-category/update/{id?}', [CustomerCategoryController::class, 'update'])->name('update');
    Route::delete('/customer-category/delete', [CustomerCategoryController::class, 'delete'])->name('delete');

    Route::get('customer-category/validate', [CustomerCategoryController::class, 'validatename'])->name('validatename');
    Route::get('customer-category/Edit/validate', [CustomerCategoryController::class, 'validateeditname'])->name('validateeditname');

});

Route::prefix('admin')->name('color.')->middleware('auth')->group(function () {
    Route::get('/color/index', [ColorController::class, 'index'])->name('index');
    Route::get('/color/create', [ColorController::class, 'create'])->name('create');
    Route::post('/color/store', [ColorController::class, 'store'])->name('store');
    Route::get('/color/edit/{id?}', [ColorController::class, 'editview'])->name('edit');
    Route::post('/color/update/{id?}', [ColorController::class, 'update'])->name('update');
    Route::delete('/color/delete', [ColorController::class, 'delete'])->name('delete');

    Route::get('color/validate', [ColorController::class, 'validatename'])->name('validatename');
    Route::get('color/Edit/validate', [ColorController::class, 'validateeditname'])->name('validateeditname');

});

Route::prefix('admin')->name('purity.')->middleware('auth')->group(function () {
    Route::get('/purity/index', [PurityController::class, 'index'])->name('index');
    Route::get('/purity/create', [PurityController::class, 'create'])->name('create');
    Route::post('/purity/store', [PurityController::class, 'store'])->name('store');
    Route::get('/purity/edit/{id?}', [PurityController::class, 'editview'])->name('edit');
    Route::post('/purity/update/{id?}', [PurityController::class, 'update'])->name('update');
    Route::delete('/purity/delete', [PurityController::class, 'delete'])->name('delete');

    Route::get('purity/validate', [PurityController::class, 'validatename'])->name('validatename');
    Route::get('purity/Edit/validate', [PurityController::class, 'validateeditname'])->name('validateeditname');
});

Route::prefix('admin')->name('orderStatus.')->middleware('auth')->group(function () {
    Route::get('/order-status/index', [OrderStatusController::class, 'index'])->name('index');
    Route::get('/order-status/create', [OrderStatusController::class, 'create'])->name('create');
    Route::post('/order-status/store', [OrderStatusController::class, 'store'])->name('store');
    Route::get('/order-status/edit/{id?}', [OrderStatusController::class, 'editview'])->name('edit');
    Route::post('/order-status/update/{id?}', [OrderStatusController::class, 'update'])->name('update');
    Route::delete('/order-status/delete', [OrderStatusController::class, 'delete'])->name('delete');

    Route::get('order-status/validate', [OrderStatusController::class, 'validatename'])->name('validatename');
    Route::get('order-status/Edit/validate', [OrderStatusController::class, 'validateeditname'])->name('validateeditname');
});

Route::prefix('admin')->name('closeReason.')->middleware('auth')->group(function () {
    Route::get('/closeReason/index', [CloseReasonController::class, 'index'])->name('index');
    Route::get('/closeReason/create', [CloseReasonController::class, 'create'])->name('create');
    Route::post('/closeReason/store', [CloseReasonController::class, 'store'])->name('store');
    Route::get('/closeReason/edit/{id?}', [CloseReasonController::class, 'editview'])->name('edit');
    Route::post('/closeReason/update/{id?}', [CloseReasonController::class, 'update'])->name('update');
    Route::delete('/closeReason/delete', [CloseReasonController::class, 'delete'])->name('delete');

    Route::get('closeReason/validate', [CloseReasonController::class, 'validatename'])->name('validatename');
    Route::get('closeReason/Edit/validate', [CloseReasonController::class, 'validateeditname'])->name('validateeditname');

});


//cast master
Route::resource('admin/castMaster', App\Http\Controllers\Admin\CastMasterController::class);

Route::prefix('admin')->name('castMaster.')->middleware('auth')->group(function () {
    Route::get('/customer-cast/index', [CastMasterController::class, 'index'])->name('index');
    Route::get('/customer-cast/create', [CastMasterController::class, 'create'])->name('create');
    Route::post('/customer-cast/store', [CastMasterController::class, 'store'])->name('store');
    Route::get('/customer-cast/edit/{id?}', [CastMasterController::class, 'editview'])->name('edit');
    Route::post('/customer-cast/update/{id?}', [CastMasterController::class, 'update'])->name('update');
    Route::delete('/customer-cast/delete', [CastMasterController::class, 'delete'])->name('delete');

    Route::get('customer-cast/validate', [CastMasterController::class, 'validatename'])->name('validatename');
    Route::get('customer-cast/Edit/validate', [CastMasterController::class, 'validateeditname'])->name('validateeditname');

});


//Product category Master
Route::prefix('admin')->name('productCategory.')->middleware('auth')->group(function () {
    Route::get('/product-category/index', [ProductCategoryController::class, 'index'])->name('index');
    Route::get('/product-category/create', [ProductCategoryController::class, 'create'])->name('create');
    Route::post('/product-category/store', [ProductCategoryController::class, 'store'])->name('store');
    Route::get('/product-category/edit/{id?}', [ProductCategoryController::class, 'editview'])->name('edit');
    Route::post('/product-category/update/{id?}', [ProductCategoryController::class, 'update'])->name('update');
    Route::delete('/product-category/delete', [ProductCategoryController::class, 'delete'])->name('delete');

    Route::get('product-category/validate', [ProductCategoryController::class, 'validatename'])->name('validatename');
    Route::get('product-category/Edit/validate', [ProductCategoryController::class, 'validateeditname'])->name('validateeditname');

});


//Product  Master
Route::prefix('admin')->name('product.')->middleware('auth')->group(function () {
    Route::get('/product/index', [ProductController::class, 'index'])->name('index');
    Route::post('/product/store', [ProductController::class, 'store'])->name('store');
    Route::get('/product/edit/{id?}', [ProductController::class, 'editview'])->name('edit');
    Route::post('/product/update/{id?}', [ProductController::class, 'update'])->name('update');
    Route::delete('/product/delete', [ProductController::class, 'delete'])->name('delete');

    Route::get('product/validate', [ProductController::class, 'validatename'])->name('validatename');
    Route::get('product/Edit/validate', [ProductController::class, 'validateeditname'])->name('validateeditname');

    Route::get('product/validateTag', [ProductController::class, 'validatetag'])->name('validatetag');
    Route::get('product/Edit/validateTag', [ProductController::class, 'validateedittag'])->name('validateedittag');

});


//branch master
Route::prefix('admin')->name('branch.')->middleware('auth')->group(function () {

    Route::get('branch', [BranchMasterController::class,'index'])->name('index');
    Route::get('branch/create/', [BranchMasterController::class, 'create'])->name('create');
    Route::post('branch/store', [BranchMasterController::class, 'store'])->name('store');
    Route::get('branch/edit/{id?}', [BranchMasterController::class, 'edit'])->name('edit');
    Route::post('branch/update/{id?}', [BranchMasterController::class, 'update'])->name('update');
    Route::delete('branch/delete', [BranchMasterController::class, 'delete'])->name('destroy');
});


//Setting
Route::prefix('admin')->name('setting.')->middleware('auth')->group(function () {
    Route::get('/setting/index', [SettingController::class, 'index'])->name('index');
    Route::post('/setting/store', [SettingController::class, 'create'])->name('store');
    Route::get('/setting/edit/{id?}', [SettingController::class, 'editview'])->name('edit');
    Route::post('/setting/update', [SettingController::class, 'update'])->name('update');
    Route::delete('/setting/delete', [SettingController::class, 'delete'])->name('delete');
});

Route::prefix('admin')->name('setting.')->middleware('auth')->group(function () {
    Route::get('/setting/index', [SettingController::class, 'index'])->name('index');
    Route::post('/setting/store', [SettingController::class, 'create'])->name('store');
    Route::get('/setting/edit/{id?}', [SettingController::class, 'editview'])->name('edit');
    Route::post('/setting/update', [SettingController::class, 'update'])->name('update');
    Route::delete('/setting/delete', [SettingController::class, 'delete'])->name('delete');
});

Route::resource('admin/empMaster', EmployeeMasterController::class);
Route::post('admin/empMaster/delete', [EmployeeMasterController::class, 'destroy'])->name('empMaster.destroy');
Route::get('admin/changepassword/{id?}', [EmployeeMasterController::class, 'changepassword'])->name('empMaster.changepassword');
Route::post('admin/updatepassword/{id?}', [EmployeeMasterController::class, 'updatepassword'])->name('empMaster.updatepassword');

Route::resource('admin/vendorMaster', VendorMasterController::class);
Route::post('admin/vendorMaster/delete', [VendorMasterController::class, 'destroy'])->name('vendorMaster.destroy');


//Customer master

Route::resource('admin/customer', App\Http\Controllers\Admin\CustomerMasterController::class);
Route::match(['get', 'post'], 'admin/customers', [CustomerMasterController::class, 'index'])->name('customer.index');
Route::post('admin/customers/delete', [CustomerMasterController::class, 'destroy'])->name('customer.destroy');
Route::get('admin/customers/validateData', [CustomerMasterController::class, 'validateCustomer'])->name('customer.validateCustomer');
Route::get('admin/customers/Edit/validateData', [CustomerMasterController::class, 'validateEditCustomer'])->name('customer.validateEditCustomer');
Route::get('admin/customers/history/{id?}', [CustomerMasterController::class, 'history'])->name('customer.history');

//customer newVisite    
Route::prefix('admin')->name('newVisite.')->group(function () {
    Route::any('/new-visite/{id}', [CustomerVisiteController::class, 'index'])->name('index');
    Route::any('/new-visite/create/{id}', [CustomerVisiteController::class, 'create'])->name('create');
    Route::any('/new-visite/product/{id}', [CustomerVisiteController::class, 'product'])->name('product');
    Route::get('/previous-visit/{id?}', [CustomerVisiteController::class, 'previous_visit'])->name('previous_visit');
    Route::any('/previous-visit-view/{id?}', [CustomerVisiteController::class, 'previous_visit_view'])->name('previous_visit_view');
    Route::get('/visit_view/{id}', [CustomerVisiteController::class, 'view_visit'])->name('view_visit');
    Route::get('/todayFollowup/{branch_id?}', [CustomerVisiteController::class, 'todayFollowup'])->name('todayFollowup');
    Route::get('/overDue/{branch_id?}', [CustomerVisiteController::class, 'overDue'])->name('overDue');
});

Route::prefix('admin')->name('reports.')->group(function () {
    Route::any('/reports', [ReportController::class, 'index'])->name('index');
    
    Route::any('/reports/staff_analysis', [ReportController::class, 'staff_analysis'])->name('staff_analysis');
    Route::get('/reports/export_staff_analysis/{search?}', [ReportController::class, 'export_staff_analysis'])->name('export_staff_analysis');

    Route::any('/reports/stock_analysis', [ReportController::class, 'stock_analysis'])->name('stock_analysis');
    Route::get('/reports/export_stock_analysis/{search?}', [ReportController::class, 'export_stock_analysis'])->name('export_stock_analysis');

    Route::any('/reports/cancel_reason_report', [ReportController::class, 'cancel_reason_report'])->name('cancel_reason_report');
    Route::get('/reports/export_cancel_reason_report', [ReportController::class, 'export_cancel_reason_report'])->name('export_cancel_reason_report');

    Route::any('/reports/monthly_conversion', [ReportController::class, 'showMonthlyConversionReport'])->name('monthly_conversion');
    Route::get('/reports/export_monthly_conversion/{month?}/{year?}', [ReportController::class, 'export_monthly_conversion'])->name('export_monthly_conversion');

    Route::any('/reports/visit_report', [ReportController::class, 'customer_visit_report'])->name('visit_report');
    Route::get('/reports/export_visit_report/{fromDate?}/{toDate?}/{empId?}', [ReportController::class, 'export_customer_visit_report'])->name('export_visit_report');

    Route::any('/reports/collection_report', [ReportController::class, 'salesstaff_collection_report'])->name('collection_report');
    Route::get('/reports/export_collection_report/{empId?}', [ReportController::class, 'export_salesstaff_collection_report'])->name('export_collection_report');

    Route::any('/reports/order_report', [ReportController::class, 'order_report'])->name('order_report');
    Route::get('/reports/export_order_reports/{fromDate?}/{toDate?}/{empId?}', [ReportController::class, 'export_order_report'])->name('export_order_reports');

});



// Place these first!
Route::post('admin/customer-product/changeStatus', [CustomerProductController::class, 'changeStatus'])->name('custProduct.changeStatus');
Route::post('admin/customer-product/delete/{id?}', [CustomerProductController::class, 'destroy'])->name('custProduct.destroy');
Route::get('admin/customer-product/create/{id}', [CustomerProductController::class, 'create'])->name('custProduct.createWithId');
Route::match(['get', 'post'], 'admin/customer-product/{id}', [CustomerProductController::class, 'index'])->name('custProduct.index');

// Keep this last
Route::resource('admin/custProduct', App\Http\Controllers\Admin\CustomerProductController::class);


//customer followup master
Route::resource('admin/custFollowup', App\Http\Controllers\Admin\CustomerFollowupController::class);
Route::match(['get', 'post'], 'admin/customer-followup/{id}', [CustomerFollowupController::class, 'index'])->name('custFollowup.index');
Route::post('admin/customer-followup/delete/{id?}', [CustomerFollowupController::class, 'destroy'])->name('custFollowup.destroy');
Route::get('admin/customer-followup/create/{id}', [CustomerFollowupController::class, 'create'])->name('custFollowup.createWithId');
Route::get('/custFollowup/branch/{branch_id}', [CustomerFollowupController::class, 'viewByBranch'])->name('custFollowup.byBranch');

//customer order master
Route::resource('admin/custOrder', App\Http\Controllers\Admin\CustomerOrderController::class);
Route::match(['get', 'post'], 'admin/customer-order/{date?}', [CustomerOrderController::class, 'index'])
    ->where('date', '\d{4}-\d{2}-\d{2}')
    ->name('custOrder.index');

//Route::match(['get', 'post'], 'admin/customer-order/', [CustomerOrderController::class, 'index'])->name('custOrder.index');
Route::post('admin/customer-order/delete/{id?}', [CustomerOrderController::class, 'destroy'])->name('custOrder.destroy');
Route::get('admin/customer-order/create', [CustomerOrderController::class, 'create'])->name('custOrder.create');
Route::post('admin/customer-order/orderProduct', [CustomerOrderController::class, 'orderProduct'])->name('custOrder.orderProduct');
Route::get('admin/customer-order/orderDetail/{id?}', [CustomerOrderController::class, 'detail'])->name('custOrder.detail');

Route::get('/cust-order-detail/{detailId}/edit', [CustomerOrderDetailController::class, 'edit'])->name('custOrderDetail.edit');
Route::post('/cust-order-detail/update', [CustomerOrderDetailController::class, 'update'])->name('custOrderDetail.update');
Route::put('/cust-order-detail/{detailId}', [CustomerOrderDetailController::class, 'delete'])->name('custOrderDetail.destroy');
Route::get('/get-order-details/{cust_pro_id}', [CustomerOrderDetailController::class, 'getOrderDetails']);

Route::resource('admin/orderPayment', App\Http\Controllers\Admin\OrderPaymentController::class);
Route::get('admin/order-payment/{orderId}', [OrderPaymentController::class, 'index'])->name('orderPayment.index');
Route::any('admin/order-payment/{orderId}/create', [OrderPaymentController::class, 'store'])->name('orderPayment.store');
Route::put('admin/order-payment/{orderId}', [OrderPaymentController::class, 'delete'])->name('orderPayment.destroy');



/*----------------------------------------Admin Route End------------------------------------- */

/*----------------------------------------Employee Route Start------------------------------------- */

Route::get('/', [EmployeeLoginController::class, 'loginform'])->name('user_login');
Route::post('employee/login', [EmployeeLoginController::class, 'login'])->name('userLogin');

//Forgot-Password Page
 Route::post('employee/Forgotpassword', [EmployeeHomeController::class, 'PasswordForgot'])->name('password_forgot');

//New-Password Page
Route::get('employee/home', [EmployeeHomeController::class, 'index'])->name('userhome');
Route::get('employee/logout', [EmployeeLoginController::class, 'logout'])->name('empuserlogout');


Route::prefix('employee')->name('empprofile.')->middleware(['auth:web_employees'])->group(function () {
    Route::get('/userprofile', [EmployeeHomeController::class, 'getProfile'])->name('employee-detail');
    Route::get('/edit', [EmployeeHomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [EmployeeHomeController::class, 'updateProfile'])->name('update');
    Route::any('/changePassword', [EmployeeHomeController::class, 'changePassword'])->name('userchangepassword');
});


//Customer master

Route::resource('employee/EMPcustomer', App\Http\Controllers\Admin\CustomerMasterController::class);
Route::match(['get', 'post'], 'employee/EMPcustomers', [CustomerMasterController::class, 'index'])->name('EMPcustomer.index');
Route::post('employee/EMPcustomers/delete', [CustomerMasterController::class, 'destroy'])->name('EMPcustomer.destroy');
Route::get('employee/EMPcustomers/validateData', [CustomerMasterController::class, 'validateCustomer'])->name('EMPcustomer.validateCustomer');
Route::get('employee/EMPcustomers/Edit/validateData', [CustomerMasterController::class, 'validateEditCustomer'])->name('EMPcustomer.validateEditCustomer');
Route::get('employee/customers/history/{id?}', [CustomerMasterController::class, 'history'])->name('EMPcustomer.history');


Route::resource('employee/EMPcustProduct', App\Http\Controllers\Admin\CustomerProductController::class);
Route::match(['get', 'post'], 'employee/emp_customer-product/{id}', [CustomerProductController::class, 'index'])->name('EMPcustProduct.index');
Route::post('employee/customer-product/delete/{id?}', [CustomerProductController::class, 'destroy'])->name('EMPcustProduct.destroy');
Route::get('employee/customer-product/create/{id}', [CustomerProductController::class, 'create'])->name('EMPcustProduct.createWithId');


//customer followup master
Route::resource('employee/EMPcustFollowup', App\Http\Controllers\Admin\CustomerFollowupController::class);
Route::match(['get', 'post'], 'employee/customer-followup/{id}', [CustomerFollowupController::class, 'index'])->name('EMPcustFollowup.index');
Route::post('employee/customer-followup/delete/{id?}', [CustomerFollowupController::class, 'destroy'])->name('EMPcustFollowup.destroy');
Route::get('employee/customer-followup/create/{id}', [CustomerFollowupController::class, 'create'])->name('EMPcustFollowup.createWithId');

//customer order master
Route::resource('employee/EMPcustOrder', App\Http\Controllers\Admin\CustomerOrderController::class);
Route::match(['get', 'post'], 'employee/employee/emp-customer-order/{date?}', [CustomerOrderController::class, 'index'])
    ->where('date', '\d{4}-\d{2}-\d{2}')
    ->name('EMPcustOrder.index');

//Route::match(['get', 'post'], 'employee/emp-customer-order/', [CustomerOrderController::class, 'index'])->name('EMPcustOrder.index');
Route::post('employee/customer-order/delete/{id?}', [CustomerOrderController::class, 'destroy'])->name('EMPcustOrder.destroy');
Route::get('employee/customer-order/create/{id}', [CustomerOrderController::class, 'create'])->name('EMPcustOrder.create');
Route::get('employee/customer-order/detail/{id}', [CustomerOrderController::class, 'detail'])->name('EMPcustOrder.detail');

//customer order Detail master
Route::prefix('empcust-order/{orderId}/details')->group(function () {
    Route::get('/', [CustomerOrderDetailController::class, 'index'])->name('EMPcustOrderDetail.index');
    Route::get('/create', [CustomerOrderDetailController::class, 'create'])->name('EMPcustOrderDetail.create');
    Route::post('/', [CustomerOrderDetailController::class, 'store'])->name('EMPcustOrderDetail.store');
});
Route::get('/cust-orderDetail/{detailId}', [CustomerOrderDetailController::class, 'show'])->name('EMPcustOrderDetail.show');
Route::get('/cust-orderDetail/{detailId}/edit', [CustomerOrderDetailController::class, 'edit'])->name('EMPcustOrderDetail.edit');
Route::put('/cust-orderDetail/{detailId}/update', [CustomerOrderDetailController::class, 'update'])->name('EMPcustOrderDetail.update');
Route::put('/cust-orderDetail/{detailId}', [CustomerOrderDetailController::class, 'delete'])->name('EMPcustOrderDetail.destroy');


Route::get('/cust-todayFollowup', [EMPCustomerVisitController::class, 'today'])->name('today');

//customer EMPvisit    
Route::prefix('employee')->name('EMPvisit.')->middleware(['auth:web_employees'])->group(function () {
    Route::any('/cust-visit/today', [EMPCustomerVisitController::class, 'today'])->name('today');
    Route::get('/cust-visit/overdue', [EMPCustomerVisitController::class, 'overdue'])->name('overdue');
    Route::any('/cust-visit/{id}', [EMPCustomerVisitController::class, 'index'])->name('index');


    Route::any('/cust-visit/create/{id}', [EMPCustomerVisitController::class, 'create'])->name('create');
    Route::any('/cust-visit/product/{id}', [EMPCustomerVisitController::class, 'product'])->name('product');
    Route::get('/cust-previous-visit/{id?}', [EMPCustomerVisitController::class, 'previous_visit'])->name('previous_visit');
    Route::any('/cust-previous-visit-view/{id?}', [EMPCustomerVisitController::class, 'previous_visit_view'])->name('previous_visit_view');
    Route::get('/cust-visit_view/{id}', [EMPCustomerVisitController::class, 'view_visit'])->name('view_visit');
    Route::get('/cust-todayFollowup', [EMPCustomerVisitController::class, 'todayFollowup'])->name('todayFollowup');
});


Route::resource('employee/EMPorderPayment', App\Http\Controllers\Admin\OrderPaymentController::class);
Route::get('employee/order-payment/{orderId}', [OrderPaymentController::class, 'index'])->name('EMPorderPayment.index');
Route::any('employee/order-payment/{orderId}/create', [OrderPaymentController::class, 'store'])->name('EMPorderPayment.store');
Route::put('employee/order-payment/{orderId}', [OrderPaymentController::class, 'delete'])->name('EMPorderPayment.destroy');


/*----------------------------------------Employee Route End------------------------------------- */
