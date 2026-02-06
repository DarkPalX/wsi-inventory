<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\{DashboardController, FrontController};

use App\Http\Controllers\Custom\{ItemController, ItemCategoryController, AuthorController, PublisherController, AgencyController};

// CMS Controllers
use App\Http\Controllers\Cms\{PageController, AlbumController, FileManagerController, MenuController, MemberController};

use App\Http\Controllers\Settings\{UserController, AccountController, WebController, LogsController, RoleController, AccessController, PermissionController};

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


 // CMS4 Front Pages
 Route::get('/home', function(){
    return redirect(route('home'));
});

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/privacy-policy/', [FrontController::class, 'privacy_policy'])->name('privacy-policy');
Route::post('/contact-us', [FrontController::class, 'contact_us'])->name('contact-us');
Route::get('/search', [FrontController::class, 'search'])->name('search');

Route::get('/search-result',[FrontController::class, 'seach_result'])->name('search.result');

//


Route::group(['prefix' => 'admin-panel'], function (){
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('panel.login');

    Auth::routes();

    Route::group(['middleware' => 'admin'], function (){
        // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users
            Route::resource('users', UserController::class);
            Route::post('users/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
            Route::post('users/activate', [UserController::class, 'activate'])->name('users.activate');
            Route::get('user-search/', [UserController::class, 'search'])->name('user.search');
            Route::get('profile-log-search/', [UserController::class, 'filter'])->name('user.activity.search');
        //

        // Account
            Route::get('account/edit', [AccountController::class, 'edit'])->name('account.edit');
            Route::put('account/update', [AccountController::class, 'update'])->name('account.update');
            Route::put('account/update_email', [AccountController::class, 'update_email'])->name('account.update-email');
            Route::put('account/update_password', [AccountController::class, 'update_password'])->name('account.update-password');
        //

        // Website
            Route::get('website-settings/edit', [WebController::class, 'edit'])->name('website-settings.edit');
            Route::put('website-settings/update', [WebController::class, 'update'])->name('website-settings.update');
            Route::post('website-settings/update_contacts', [WebController::class, 'update_contacts'])->name('website-settings.update-contacts');
            Route::post('website-settings/update-ecommerce', [WebController::class, 'update_ecommerce'])->name('website-settings.update-ecommerce');
            Route::post('website-settings/update-paynamics', [WebController::class, 'update_paynamics'])->name('website-settings.update-paynamics');
            Route::post('website-settings/update_media_accounts', [WebController::class, 'update_media_accounts'])->name('website-settings.update-media-accounts');
            Route::post('website-settings/update_data_privacy', [WebController::class, 'update_data_privacy'])->name('website-settings.update-data-privacy');
            Route::post('website-settings/update_modal_content', [WebController::class, 'update_modal_content'])->name('website-settings.update-modal-content');
            Route::post('website-settings/remove_logo', [WebController::class, 'remove_logo'])->name('website-settings.remove-logo');
            Route::post('website-settings/remove_icon', [WebController::class, 'remove_icon'])->name('website-settings.remove-icon');
            Route::post('website-settings/remove_media', [WebController::class, 'remove_media'])->name('website-settings.remove-media');
        //

        // Audit
            Route::get('audit-logs', [LogsController::class, 'index'])->name('audit-logs.index');
        //

        // Roles
            Route::resource('role', RoleController::class);
            Route::post('role/delete',[RoleController::class, 'destroy'])->name('role.delete');
            Route::get('role/restore/{id}',[RoleController::class, 'restore'])->name('role.restore');
        //

        // Access
            Route::resource('access', AccessController::class);
            Route::post('roles_and_permissions/update', [AccessController::class, 'update_roles_and_permissions'])->name('role-permission.update');

            if (env('APP_DEBUG') == "true") {
                // Permission Routes
                Route::resource('permission', PermissionController::class);
                Route::post('permission/delete', [PermissionController::class, 'delete'])->name('permission.delete');
                Route::get('permission/restore/{id}', [PermissionController::class, 'restore'])->name('permission.restore');
            }
        //

        // Pages
            Route::resource('pages', PageController::class);
            Route::get('pages-advance-search', [PageController::class, 'advance_index'])->name('pages.index.advance-search');
            Route::post('pages/get-slug', [PageController::class, 'get_slug'])->name('pages.get_slug');
            Route::put('pages/{page}/default', [PageController::class, 'update_default'])->name('pages.update-default');
            Route::put('pages/{page}/customize', [PageController::class, 'update_customize'])->name('pages.update-customize');
            Route::put('pages/{page}/contact-us', [PageController::class, 'update_contact_us'])->name('pages.update-contact-us');
            Route::post('pages-change-status', [PageController::class, 'change_status'])->name('pages.change.status');
            Route::post('pages-delete', [PageController::class, 'delete'])->name('pages.delete');
            Route::get('page-restore/{page}', [PageController::class, 'restore'])->name('pages.restore');
        //

        // Albums
            Route::resource('albums', AlbumController::class);
            Route::post('albums/upload', [AlbumController::class, 'upload'])->name('albums.upload');
            Route::delete('many/album', [AlbumController::class, 'destroy_many'])->name('albums.destroy_many');
            Route::put('albums/quick/{album}', [AlbumController::class, 'quick_update'])->name('albums.quick_update');
            Route::post('albums/{album}/restore', [AlbumController::class, 'restore'])->name('albums.restore');
            Route::post('albums/banners/{album}', [AlbumController::class, 'get_album_details'])->name('albums.banners');
        //

        // Menu
            Route::resource('menus', MenuController::class);
            Route::delete('many/menu', [MenuController::class, 'destroy_many'])->name('menus.destroy_many');
            Route::put('menus/quick1/{menu}', [MenuController::class, 'quick_update'])->name('menus.quick_update');
            Route::get('menu-restore/{menu}', [MenuController::class, 'restore'])->name('menus.restore');
        //

        // Users
            Route::resource('members', MemberController::class)->except(['show']);
            Route::post('members/update', [MemberController::class, 'update'])->name('members.update');
            Route::post('members/deactivate', [MemberController::class, 'deactivate'])->name('members.deactivate');
            Route::post('members/activate', [MemberController::class, 'activate'])->name('members.activate');
            // Route::get('members/reset-password/{token}/{email}', [MemberController::class, 'reset_password'])->name('members.reset-password');
            // Route::post('members/create-password', [MemberController::class, 'create_password'])->name('members.create-password');
        //




        // Items
            Route::resource('books', ItemController::class);
            // Route::delete('many/menu', [MenuController::class, 'destroy_many'])->name('menus.destroy_many');
            // Route::put('menus/quick1/{menu}', [MenuController::class, 'quick_update'])->name('menus.quick_update');
            // Route::get('menu-restore/{menu}', [MenuController::class, 'restore'])->name('menus.restore');
        //

    });
});



// Pages Frontend
Route::get('/{any}', [FrontController::class, 'page'])->where('any', '.*');

//Members Transaction
Route::post('/create-password', [MemberController::class, 'create_password'])->name('members.create-password');
Route::post('/reset-password', [MemberController::class, 'reset_password'])->name('members.reset-password');
Route::post('/send-reset-form', [MemberController::class, 'send_reset_form'])->name('members.send-reset-form');
Route::post('/login', [MemberController::class, 'members_login'])->name('members.login');
Route::post('/logout', [MemberController::class, 'members_logout'])->name('members.logout');
