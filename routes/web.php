<?php

use App\Models\Connection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



//dd(\App\Models\Company::whereIn('id',[1942,1945])->get());
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [App\Http\Controllers\Dashboard\IndexController::class, 'home'])->name('dashboard.index');

    Route::group(['prefix' => 'accounts'], function () {

        Route::get('/', [App\Http\Controllers\Dashboard\AccountController::class, 'index'])->name('accounts.index')->middleware('permission:accounts');

        Route::get('/create', [App\Http\Controllers\Dashboard\AccountController::class, 'create'])->name('accounts.create')->middleware('permission:accounts');
        Route::post('/store', [App\Http\Controllers\Dashboard\AccountController::class, 'store'])->name('accounts.store')->middleware('permission:accounts');
        Route::get('/edit/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'edit'])->name('accounts.edit')->middleware('permission:accounts');
        Route::put('/update/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'update'])->name('accounts.update')->middleware('permission:accounts');
        Route::get('/{id}/conversations/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConversations'])->name('accounts.syncConversations')->middleware('permission:accounts');
        Route::get('/{id}/connections/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConnections'])->name('accounts.syncConnections')->middleware('permission:accounts');
        Route::get('/{id}/conversations', [App\Http\Controllers\Dashboard\AccountController::class, 'getConversations']);
        Route::get('/{id}/conversations/view', [App\Http\Controllers\Dashboard\AccountController::class, 'conversations'])->name('accounts.conversations')->middleware('permission:accounts');
        Route::get('/{id}/conversations/{conversation_id}/messages', [App\Http\Controllers\Dashboard\AccountController::class, 'conversationMessages'])->name('accounts.conversationMessages')->middleware('permission:accounts');

    });
    Route::group(['prefix' => 'keys'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\KeyController::class, 'index'])->name('keys.index')->middleware('permission:keys');
        Route::post('/store', [App\Http\Controllers\Dashboard\KeyController::class, 'store'])->name('keys.store')->middleware('permission:keys');
        Route::get('/search', [App\Http\Controllers\Dashboard\KeyController::class, 'search'])->name('keys.search')->middleware('permission:keys');

    });

    Route::group(['prefix' => 'users'], function () {

        Route::get('/', [App\Http\Controllers\Dashboard\UserController::class, 'index'])->name('users.index')->middleware('permission:users');
        Route::get('/create', [App\Http\Controllers\Dashboard\UserController::class, 'create'])->name('users.create')->middleware('permission:users');
        Route::post('/store', [App\Http\Controllers\Dashboard\UserController::class, 'store'])->name('users.store')->middleware('permission:users');;
        Route::get('/edit/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'edit'])->name('users.edit')->middleware('permission:users');
        Route::put('/update/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'update'])->name('users.update')->middleware('permission:users');
        Route::get('/linkedin/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'linkedin'])->name('users.linkedin');

    });

    Route::group(['prefix' => 'connections'], function () {

        Route::get('/', [App\Http\Controllers\Dashboard\ConnectionController::class, 'index'])->name('connections.index')->middleware('permission:connections');
        Route::get('/{id}/getInfo', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getInfo'])->name('connections.getInfo')->middleware('permission:connections');
        Route::get('/{id}/trackingId', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getTrackingId']);
        Route::post('/{id}/sendInvitation', [App\Http\Controllers\Dashboard\ConnectionController::class, 'sendInvitation']);
        Route::get('/{id}/messages', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getMessages']);

    });

    Route::group(['prefix' => 'connection-request'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\ConnectionRequestController::class, 'index'])->name('connectionRequest.index');
    });

    Route::group(['prefix' => 'jobs'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\JobController::class, 'index'])->name('jobs.index');
    });

    Route::group(['prefix' => 'companies'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\CompanyController::class, 'index'])->name('companies.index');
        Route::get('/sync', [App\Http\Controllers\Dashboard\CompanyController::class, 'sync'])->name('companies.sync');
        Route::get('/{id}/connections', [App\Http\Controllers\Dashboard\CompanyController::class, 'getConnections'])->name('companies.connections');
    });

    Route::group(['prefix' => 'conversations'], function () {

        Route::post('/{id}/messages-sync', [App\Http\Controllers\Dashboard\ConversationController::class, 'syncMessages']);
        Route::get('/{id}/messages', [App\Http\Controllers\Dashboard\ConversationController::class, 'getMessages']);
        Route::post('/{id}/sync-last-messages', [App\Http\Controllers\Dashboard\ConversationController::class, 'synLastMessages']);

    });

    Route::group(['prefix' => 'messages'], function () {

        Route::post('/', [App\Http\Controllers\Dashboard\MessageController::class, 'store']);
        Route::post('/{id}/resend', [App\Http\Controllers\Dashboard\MessageController::class, 'resend']);
        Route::put('/{id}/destroy', [App\Http\Controllers\Dashboard\MessageController::class, 'update']);

    });
    Route::group(['prefix' => 'search'], function () {

        Route::get('',[App\Http\Controllers\Dashboard\SearchController::class, 'index'])->name('search.index');
        Route::post('/linkedin',[App\Http\Controllers\Dashboard\SearchController::class, 'linkedin'])->name('search.linkedin');;
    });

    Route::group(['prefix' => 'countries'], function () {

        Route::get('',[App\Http\Controllers\Dashboard\CountryController::class, 'index'])->name('countries.index');
        Route::post('/store',[App\Http\Controllers\Dashboard\CountryController::class, 'store'])->name('countries.store');
        Route::delete('/{id}}',[App\Http\Controllers\Dashboard\CountryController::class, 'destroy'])->name('countries.destroy');
    });

    Route::group(['prefix' => 'linkedin'], function () {

        Route::get('/chat', [App\Http\Controllers\Dashboard\LinkedinController::class, 'chat'])->name('linkedin.chat');
        Route::get('/search', [App\Http\Controllers\Dashboard\LinkedinController::class, 'search'])->name('linkedin.search');
        Route::get('/connection-search', [App\Http\Controllers\Dashboard\LinkedinController::class, 'searchConnection']);
        Route::get('/send-invitations', [App\Http\Controllers\Dashboard\LinkedinController::class, 'sendInvitations'])->name('linkedin.sendInvitations');
        Route::get('/invitations/sent', [App\Http\Controllers\Dashboard\LinkedinController::class, 'getSentInvitations']);

    });



    Route::get('/permissions', [App\Http\Controllers\Dashboard\PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:permissions');

    Route::get('/roles', [App\Http\Controllers\Dashboard\RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles');
    Route::post('/roles', [App\Http\Controllers\Dashboard\RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles');
    Route::post('/roles/{id}', [App\Http\Controllers\Dashboard\RoleController::class, 'syncPermissions'])->middleware('permission:roles');
    Route::get('/roles/{id}', [App\Http\Controllers\Dashboard\RoleController::class, 'get'])->middleware('permission:roles');


    Route::get('/profile', [App\Http\Controllers\Dashboard\IndexController::class, 'profile'])->name('account.profile');




    Route::group(['prefix' => 'linkedin'], function () {
        Route::post('/message', [App\Http\Controllers\Dashboard\LinkedinController::class, 'storeMessage']);
        Route::get('/conversations/{conversation_id}/user/{user_id}/messages', [App\Http\Controllers\Dashboard\LinkedinController::class, 'getConversationMessages']);
        Route::post('/conversations/{id}/sync', [App\Http\Controllers\Dashboard\LinkedinController::class, 'syncConversationMessages']);
        Route::post('/conversations/sync', [App\Http\Controllers\Dashboard\LinkedinController::class, 'syncConversations']);
        Route::post('/conversations/{conversation_id}/messages/{message_id}/resend', [App\Http\Controllers\Dashboard\LinkedinController::class, 'resendMessage']);
        Route::get('/profiles/search', [App\Http\Controllers\Dashboard\LinkedinController::class, 'searchProfile']);
        Route::post('/profiles/invitations', [App\Http\Controllers\Dashboard\LinkedinController::class, 'sendInvitation']);
        Route::get('/profiles/invitations/received', [App\Http\Controllers\Dashboard\LinkedinController::class, 'getReceivedInvitations']);
        Route::post('/profiles/invitations/{id}/reply', [App\Http\Controllers\Dashboard\LinkedinController::class, 'replyInvitation']);
    });


});
