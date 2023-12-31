<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/new_register', [App\Http\Controllers\Admin\HomeController::class, 'user_register'])->name('user_register');
Route::get('/login_screen', [App\Http\Controllers\Admin\HomeController::class, 'login_screen'])->name('login_screen');
Route::get('/userlogin', [App\Http\Controllers\Admin\HomeController::class, 'userlogin'])->name('userlogin');
Route::get('/user_logout', [App\Http\Controllers\Admin\HomeController::class, 'user_logout'])->name('user_logout');
Route::post('/save_new_registration', [App\Http\Controllers\Admin\HomeController::class, 'save_new_registration'])->name('save_new_registration');
Route::post('/change_password', [App\Http\Controllers\Admin\HomeController::class, 'change_password'])->name('change_password');
Route::post('/change_password_for_user', [App\Http\Controllers\Admin\HomeController::class, 'change_password_for_user'])->name('change_password_for_user');
Route::post('/UpdatePerDetails', [App\Http\Controllers\Admin\HomeController::class, 'UpdatePerDetails'])->name('UpdatePerDetails');
Route::get('/getPersonalDetails/{id}', [App\Http\Controllers\Admin\HomeController::class, 'getPersonalDetails'])->name('getPersonalDetails');
Route::get('/DashboardDetails', [App\Http\Controllers\Admin\HomeController::class, 'DashboardDetails'])->name('DashboardDetails');
Route::get('/fetchDashboardDetailsForGraph', [App\Http\Controllers\Admin\HomeController::class, 'fetchDashboardDetailsForGraph'])->name('fetchDashboardDetailsForGraph');
Route::get('/getAllSystemUsers', [App\Http\Controllers\Admin\HomeController::class, 'getAllSystemUsers'])->name('getAllSystemUsers');

//deleting user
Route::get('/DeleteUser/{id}', [App\Http\Controllers\Admin\HomeController::class, 'DeleteUser'])->name('DeleteUser');

//Deleting organization from system
Route::get('/OrgDelete/{id}', [App\Http\Controllers\Admin\HomeController::class, 'OrgDelete'])->name('OrgDelete');

//pulling assign task in notification
// Route::get('/getNotification', [App\Http\Controllers\Admin\HomeController::class, 'getNotification'])->name('getNotification');

//Checking email while adding by admin
Route::get('/emailchecking/{email}', [App\Http\Controllers\Admin\HomeController::class, 'emailchecking'])->name('emailchecking');

//adding new user by admin
Route::post('/add_newuser', [App\Http\Controllers\Admin\HomeController::class, 'add_newuser'])->name('add_newuser');

//Adding organization
Route::post('/add_organization', [App\Http\Controllers\Admin\HomeController::class, 'add_organization'])->name('add_organization');

//loading all organization
Route::get('/orgListDetails', [App\Http\Controllers\Admin\HomeController::class, 'orgListDetails'])->name('orgListDetails');

//_invoice routes
Route::post('/create_invoice', [App\Http\Controllers\Admin\HomeController::class, 'create_invoice'])->name('create_invoice');
Route::get('/viewFiles/{path}', [App\Http\Controllers\Admin\HomeController::class, 'viewFiles'])->name('viewFiles');
Route::get('/InvoiceList', [App\Http\Controllers\Admin\HomeController::class, 'InvoiceList'])->name('InvoiceList');
Route::get('/BillList', [App\Http\Controllers\Admin\HomeController::class, 'BillList'])->name('BillList');
Route::get('/InvoiceDetails/{id}', [App\Http\Controllers\Admin\HomeController::class, 'InvoiceDetails'])->name('InvoiceDetails');
Route::get('/InvoiceDelete/{id}', [App\Http\Controllers\Admin\HomeController::class, 'InvoiceDelete'])->name('InvoiceDelete');
Route::get('/BillListByParams/{params}', [App\Http\Controllers\Admin\HomeController::class, 'BillListByParams'])->name('BillListByParams');
Route::get('/Received/{id}', [App\Http\Controllers\Admin\HomeController::class, 'Received'])->name('Received');
Route::post('/uploadBillDoc', [App\Http\Controllers\Admin\HomeController::class, 'uploadBillDoc'])->name('uploadBillDoc');

Route::get('/SearchUserByEmail/{params}', [App\Http\Controllers\Admin\HomeController::class, 'SearchUserByEmail'])->name('SearchUserByEmail');


//Purphase Order routes
Route::post('/create_purposeOrder', [App\Http\Controllers\Admin\HomeController::class, 'create_purposeOrder'])->name('create_purposeOrder');
Route::get('/PurchaseList', [App\Http\Controllers\Admin\HomeController::class, 'PurchaseList'])->name('PurchaseList');
Route::get('/PurchaseDetails/{id}', [App\Http\Controllers\Admin\HomeController::class, 'PurchaseDetails'])->name('PurchaseDetails');
Route::get('/DeletePurchase/{id}', [App\Http\Controllers\Admin\HomeController::class, 'DeletePurchase'])->name('DeletePurchase');
Route::get('/PurchaseOrderList', [App\Http\Controllers\Admin\HomeController::class, 'PurchaseOrderList'])->name('PurchaseOrderList');
Route::post('/uploadPurchaseOrderDoc', [App\Http\Controllers\Admin\HomeController::class, 'uploadPurchaseOrderDoc'])->name('uploadPurchaseOrderDoc');
Route::get('/PurchaseOrderListByParams/{params}', [App\Http\Controllers\Admin\HomeController::class, 'PurchaseOrderListByParams'])->name('PurchaseOrderListByParams');


Route::get('/resetportalpassword/{dob}/{email}', [App\Http\Controllers\Admin\HomeController::class, 'resetportalpassword'])->name('resetportalpassword');
Route::get('/getSessionDetail', [App\Http\Controllers\Admin\HomeController::class, 'getSessionDetail'])->name('getSessionDetail');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('home', function () {
    return redirect('/dashboard');
});
Route::get('/{vue_capture?}', function () {
    return view('home');
});

