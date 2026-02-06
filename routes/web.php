<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\{DashboardController, FrontController};
use App\Http\Controllers\Settings\{UserController, RoleController, AccessController, PermissionController, SettingsController};
use App\Http\Controllers\Custom\{ItemController, ItemCategoryController, ItemTypeController, SupplierController, PurchaseOrderController, ReceivingController, ReceiverController, RequisitionController, VehicleController, IssuanceController, ReportsController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();

Route::get('/signup', [FrontController::class, 'signup'])->name('signup');
Route::post('/submit_registration', [FrontController::class, 'submit_registration'])->name('submit-registration');


Route::group(['middleware' => 'admin'], function (){

    Route::get('/', [FrontController::class, 'home'])->name('home');
    Route::get('/privacy-policy/', [FrontController::class, 'privacy_policy'])->name('privacy-policy');
    Route::post('/contact-us', [FrontController::class, 'contact_us'])->name('contact-us');
    Route::get('/search', [FrontController::class, 'search'])->name('search');

    Route::get('/search-result',[FrontController::class, 'seach_result'])->name('search.result');



    // BOOK MANAGENMENT

    Route::group(['prefix' => 'items', 'as' => 'items.'], function () {
        // BOOKS
        Route::resource('/', ItemController::class)->parameters(['' => 'item'])->except(['show']);
        Route::post('/single-delete', [ItemController::class, 'single_delete'])->name('single-delete');
        Route::post('/multiple-delete', [ItemController::class, 'multiple_delete'])->name('multiple-delete');
        Route::post('/single-restore', [ItemController::class, 'single_restore'])->name('single-restore');
        Route::post('/multiple-restore', [ItemController::class, 'multiple_restore'])->name('multiple-restore');
        Route::get('info/{info}', [ItemController::class, 'show'])->name('show');
        Route::get('stock-card/{id}', [ItemController::class, 'stock_card'])->name('stock-card');

        // CATEGORIES
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('categories', ItemCategoryController::class);
        });
        Route::post('categories/single-delete', [ItemCategoryController::class, 'single_delete'])->name('categories.single-delete');
        Route::post('categories/multiple-delete', [ItemCategoryController::class, 'multiple_delete'])->name('categories.multiple-delete');
        Route::post('categories/single-restore', [ItemCategoryController::class, 'single_restore'])->name('categories.single-restore');
        Route::post('categories/multiple-restore', [ItemCategoryController::class, 'multiple_restore'])->name('categories.multiple-restore');

        // TYPES
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('types', ItemTypeController::class);
        });
        Route::post('types/single-delete', [ItemTypeController::class, 'single_delete'])->name('types.single-delete');
        Route::post('types/multiple-delete', [ItemTypeController::class, 'multiple_delete'])->name('types.multiple-delete');
        Route::post('types/single-restore', [ItemTypeController::class, 'single_restore'])->name('types.single-restore');
        Route::post('types/multiple-restore', [ItemTypeController::class, 'multiple_restore'])->name('types.multiple-restore');
    });
    
    

    // RECEIVING

    Route::group(['prefix' => 'receiving', 'as' => 'receiving.'], function () {
        // PURCHASE ORDER
        Route::resource('purchase-orders', PurchaseOrderController::class)->except('show');
        Route::post('purchase-orders/single-delete', [PurchaseOrderController::class, 'single_delete'])->name('purchase-orders.single-delete');
        Route::post('purchase-orders/multiple-delete', [PurchaseOrderController::class, 'multiple_delete'])->name('purchase-orders.multiple-delete');
        Route::post('purchase-orders/single-restore', [PurchaseOrderController::class, 'single_restore'])->name('purchase-orders.single-restore');
        Route::post('purchase-orders/multiple-restore', [PurchaseOrderController::class, 'multiple_restore'])->name('purchase-orders.multiple-restore');
        Route::post('purchase-orders/single-post', [PurchaseOrderController::class, 'single_post'])->name('purchase-orders.single-post');
        Route::get('purchase-orders/search-item', [PurchaseOrderController::class, 'search_item'])->name('purchase-orders.search-item');
        Route::get('purchase-orders/show', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        Route::get('purchase-orders/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');
        
        // TRANSACTIONS
        Route::resource('transactions', ReceivingController::class)->except('show');
        Route::post('transactions/single-delete', [ReceivingController::class, 'single_delete'])->name('transactions.single-delete');
        Route::post('transactions/multiple-delete', [ReceivingController::class, 'multiple_delete'])->name('transactions.multiple-delete');
        Route::post('transactions/single-restore', [ReceivingController::class, 'single_restore'])->name('transactions.single-restore');
        Route::post('transactions/multiple-restore', [ReceivingController::class, 'multiple_restore'])->name('transactions.multiple-restore');
        Route::post('transactions/single-post', [ReceivingController::class, 'single_post'])->name('transactions.single-post');
        Route::get('transactions/search-item', [ReceivingController::class, 'search_item'])->name('transactions.search-item');
        Route::get('transactions/search-po-number', [ReceivingController::class, 'search_po_number'])->name('transactions.search-po-number');
        Route::get('transactions/search-purchased-item', [ReceivingController::class, 'search_purchased_item'])->name('transactions.search-purchased-item');
        Route::get('transactions/show', [ReceivingController::class, 'show'])->name('transactions.show');
        // Route::get('transactions/{transaction}', [ReceivingController::class, 'show'])->name('transactions.show');
        
        // SUPPLIERS
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('suppliers', SupplierController::class);
        });
        Route::post('suppliers/single-delete', [SupplierController::class, 'single_delete'])->name('suppliers.single-delete');
        Route::post('suppliers/multiple-delete', [SupplierController::class, 'multiple_delete'])->name('suppliers.multiple-delete');
        Route::post('suppliers/single-restore', [SupplierController::class, 'single_restore'])->name('suppliers.single-restore');
        Route::post('suppliers/multiple-restore', [SupplierController::class, 'multiple_restore'])->name('suppliers.multiple-restore');
    });
    


    // ISSUANCE

    Route::group(['prefix' => 'issuance', 'as' => 'issuance.'], function () {
        // REQUISITION
        Route::resource('requisitions', RequisitionController::class)->except('show');
        Route::post('requisitions/single-delete', [RequisitionController::class, 'single_delete'])->name('requisitions.single-delete');
        Route::post('requisitions/multiple-delete', [RequisitionController::class, 'multiple_delete'])->name('requisitions.multiple-delete');
        Route::post('requisitions/single-restore', [RequisitionController::class, 'single_restore'])->name('requisitions.single-restore');
        Route::post('requisitions/multiple-restore', [RequisitionController::class, 'multiple_restore'])->name('requisitions.multiple-restore');
        Route::post('requisitions/single-post', [RequisitionController::class, 'single_post'])->name('requisitions.single-post');
        Route::get('requisitions/search-item', [RequisitionController::class, 'search_item'])->name('requisitions.search-item');
        Route::get('requisitions/show', [RequisitionController::class, 'show'])->name('requisitions.show');
        Route::post('requisitions/create-issuance', [RequisitionController::class, 'create_issuance'])->name('requisitions.create-issuance');
        Route::post('requisitions/edit-issuance', [RequisitionController::class, 'edit_issuance'])->name('requisitions.edit-issuance');
        Route::get('requisitions/show-issuance/{id}', [RequisitionController::class, 'show_issuance'])->name('requisitions.show-issuance');
        
        // TRANSACTIONS
        Route::resource('transactions', IssuanceController::class)->except('show');
        Route::post('transactions/single-delete', [IssuanceController::class, 'single_delete'])->name('transactions.single-delete');
        Route::post('transactions/multiple-delete', [IssuanceController::class, 'multiple_delete'])->name('transactions.multiple-delete');
        Route::post('transactions/single-restore', [IssuanceController::class, 'single_restore'])->name('transactions.single-restore');
        Route::post('transactions/multiple-restore', [IssuanceController::class, 'multiple_restore'])->name('transactions.multiple-restore');
        Route::post('transactions/single-post', [IssuanceController::class, 'single_post'])->name('transactions.single-post');
        Route::get('transactions/search-item', [IssuanceController::class, 'search_item'])->name('transactions.search-item');
        Route::get('transactions/search-po-number', [IssuanceController::class, 'search_ris_number'])->name('transactions.search-ris-number');
        Route::get('transactions/search-requested-item', [IssuanceController::class, 'search_requested_item'])->name('transactions.search-requested-item');
        Route::get('transactions/show', [IssuanceController::class, 'show'])->name('transactions.show');
        // Route::get('transactions/{transaction}', [IssuanceController::class, 'show'])->name('transactions.show');
        
        // RECEIVERS
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('receivers', ReceiverController::class);
        });

        Route::post('receivers/single-delete', [ReceiverController::class, 'single_delete'])->name('receivers.single-delete');
        Route::post('receivers/multiple-delete', [ReceiverController::class, 'multiple_delete'])->name('receivers.multiple-delete');
        Route::post('receivers/single-restore', [ReceiverController::class, 'single_restore'])->name('receivers.single-restore');
        Route::post('receivers/multiple-restore', [ReceiverController::class, 'multiple_restore'])->name('receivers.multiple-restore');
        
        // VEHICLES
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('vehicles', VehicleController::class)->except('show');
            Route::get('vehicles/search-vehicle', [VehicleController::class, 'search_vehicle'])->name('vehicles.search-vehicle');
        });

        Route::post('vehicles/single-delete', [VehicleController::class, 'single_delete'])->name('vehicles.single-delete');
        Route::post('vehicles/multiple-delete', [VehicleController::class, 'multiple_delete'])->name('vehicles.multiple-delete');
        Route::post('vehicles/single-restore', [VehicleController::class, 'single_restore'])->name('vehicles.single-restore');
        Route::post('vehicles/multiple-restore', [VehicleController::class, 'multiple_restore'])->name('vehicles.multiple-restore');
    });
    
    

    // ACCOUNTS MANAGENMENT

    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {
        // USERS
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('users', UserController::class);
        });
        Route::post('users/single-delete', [UserController::class, 'single_delete'])->name('users.single-delete');
        Route::post('users/multiple-delete', [UserController::class, 'multiple_delete'])->name('users.multiple-delete');
        Route::post('users/single-restore', [UserController::class, 'single_restore'])->name('users.single-restore');
        Route::post('users/multiple-restore', [UserController::class, 'multiple_restore'])->name('users.multiple-restore');

        Route::get('user/edit-profile', [UserController::class, 'edit_profile'])->name('users.edit-profile');
        Route::post('user/update-profile', [UserController::class, 'update_profile'])->name('users.update-profile');
        Route::post('user/update-email', [UserController::class, 'update_email'])->name('users.update-email');
        Route::post('user/update-password', [UserController::class, 'update_password'])->name('users.update-password');
        Route::post('user/update-avatar', [UserController::class, 'update_avatar'])->name('users.update-avatar');
        
        // ROLES
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('roles', RoleController::class);
        });
        Route::post('roles/single-delete', [RoleController::class, 'single_delete'])->name('roles.single-delete');
        Route::post('roles/multiple-delete', [RoleController::class, 'multiple_delete'])->name('roles.multiple-delete');
        Route::post('roles/single-restore', [RoleController::class, 'single_restore'])->name('roles.single-restore');
        Route::post('roles/multiple-restore', [RoleController::class, 'multiple_restore'])->name('roles.multiple-restore');

        // ACCESS
        Route::resource('/access', AccessController::class);
        Route::post('/roles_and_permissions/update', [AccessController::class, 'update_roles_and_permissions'])->name('role-permission.update');

        // PERMISSION
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('/permissions', PermissionController::class)->except(['destroy']);
        });
        Route::post('permission/update-permissions', [PermissionController::class, 'update_permissions'])->name('permissions.update-permissions');
        Route::post('permission/single-delete', [PermissionController::class, 'single_delete'])->name('permissions.single-delete');
        Route::post('permission/multiple-delete', [PermissionController::class, 'multiple_delete'])->name('permissions.multiple-delete');
        Route::post('permission/single-restore', [PermissionController::class, 'single_restore'])->name('permissions.single-restore');
        Route::post('permission/multiple-restore', [PermissionController::class, 'multiple_restore'])->name('permissions.multiple-restore');
        
        // SETTINGS
        Route::group(['middleware' => function ($request, $next) {
            return auth()->user()->role_id == 1 ? $next($request) : abort(403);
        }], function () {
            Route::resource('/settings', SettingsController::class)->except(['destroy']);
        });
        Route::post('settings/update-settings', [SettingsController::class, 'update_settings'])->name('settings.update-settings');

    });

    

    // REPORTS

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        
        Route::get('issuance', [ReportsController::class, 'issuance'])->name('issuance');
        Route::get('receiving', [ReportsController::class, 'receiving'])->name('receiving');
        Route::get('receivables', [ReportsController::class, 'receivables'])->name('receivables');
        Route::get('stock-card', [ReportsController::class, 'stock_card'])->name('stock-card');
        // Route::get('stock-card/{id}', [ReportsController::class, 'stock_card'])->name('stock-card');
        Route::get('inventory', [ReportsController::class, 'inventory'])->name('inventory');
        Route::get('non-inventory', [ReportsController::class, 'non_inventory'])->name('non-inventory');
        Route::get('users', [ReportsController::class, 'users'])->name('users');
        Route::get('audit-trail', [ReportsController::class, 'audit_trail'])->name('audit-trail');
        Route::get('items', [ReportsController::class, 'items'])->name('items');
        Route::get('deficit-items', [ReportsController::class, 'deficit_items'])->name('deficit-items');
        Route::post('log-export-activity', [ReportsController::class, 'log_export_activity'])->name('log-export-activity');
    });

});