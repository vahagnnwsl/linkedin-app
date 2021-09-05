<?php

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

        Route::get('/', [App\Http\Controllers\Dashboard\AccountController::class, 'index'])->name('accounts.index');

        Route::get('/create', [App\Http\Controllers\Dashboard\AccountController::class, 'create'])->name('accounts.create')->middleware('role:Admin');
        Route::get('/login/{type}', [App\Http\Controllers\Dashboard\AccountController::class, 'login'])->name('accounts.login')->middleware(['role:Admin|Manager']);
        Route::post('/store', [App\Http\Controllers\Dashboard\AccountController::class, 'store'])->name('accounts.store')->middleware('role:Admin');
        Route::get('/edit/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'edit'])->name('accounts.edit')->middleware('role:Admin');
        Route::put('/update/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'update'])->name('accounts.update')->middleware('role:Admin');
        Route::get('/{id}/conversations/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConversations'])->name('accounts.syncConversations')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/connections/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConnections'])->name('accounts.syncConnections')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/requests/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncRequests'])->name('accounts.syncRequests')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/conversations', [App\Http\Controllers\Dashboard\AccountController::class, 'getConversations']);
        Route::get('/{id}/conversations/history', [App\Http\Controllers\Dashboard\AccountController::class, 'conversations'])->name('accounts.conversations')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/conversations/{conversation_id}/messages', [App\Http\Controllers\Dashboard\AccountController::class, 'conversationMessages'])->name('accounts.conversationMessages')->middleware(['role:Admin|Manager']);

    });

    Route::group(['prefix' => 'keys','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\KeyController::class, 'index'])->name('keys.index');
        Route::post('/store', [App\Http\Controllers\Dashboard\KeyController::class, 'store'])->name('keys.store');
        Route::get('/{id}/edit', [App\Http\Controllers\Dashboard\KeyController::class, 'edit'])->name('keys.edit');
        Route::put('/{id}/update', [App\Http\Controllers\Dashboard\KeyController::class, 'update'])->name('keys.update');
    });

    Route::group(['prefix' => 'proxies','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\ProxyController::class, 'index'])->name('proxies.index');
        Route::post('/store', [App\Http\Controllers\Dashboard\ProxyController::class, 'store'])->name('proxies.store');
        Route::get('/{id}/edit', [App\Http\Controllers\Dashboard\ProxyController::class, 'edit'])->name('proxies.edit');
        Route::put('/update/{id}', [App\Http\Controllers\Dashboard\ProxyController::class, 'update'])->name('proxies.update');

    });

    Route::group(['prefix' => 'users','middleware'=>'role:Admin'], function () {

        Route::get('/', [App\Http\Controllers\Dashboard\UserController::class, 'index'])->name('users.index');
        Route::get('/create', [App\Http\Controllers\Dashboard\UserController::class, 'create'])->name('users.create');
        Route::post('/store', [App\Http\Controllers\Dashboard\UserController::class, 'store'])->name('users.store');
        Route::get('/edit/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'edit'])->name('users.edit');
        Route::put('/update/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'update'])->name('users.update');
        Route::get('/{id}/login', [App\Http\Controllers\Dashboard\UserController::class, 'login'])->name('users.login');
        Route::get('/{id}/password', [App\Http\Controllers\Dashboard\UserController::class, 'updatePasswordForm'])->name('users.updatePasswordForm');
        Route::put('/{id}/password', [App\Http\Controllers\Dashboard\UserController::class, 'updatePassword'])->name('users.updatePassword');

    });

    Route::group(['prefix' => 'connections'], function () {

        Route::get('/', [App\Http\Controllers\Dashboard\ConnectionController::class, 'index'])->name('connections.index');
        Route::get('/{id}/getInfo', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getInfo'])->name('connections.getInfo');
        Route::get('/{id}/trackingId', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getTrackingId']);
        Route::post('/{id}/sendInvitation', [App\Http\Controllers\Dashboard\ConnectionController::class, 'sendInvitation']);
        Route::get('/{id}/messages', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getMessages']);
        Route::post('/{id}/createConversation', [App\Http\Controllers\Dashboard\ConnectionController::class, 'createConversation']);
        Route::get('/getSkills', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getSkills'])->name('connections.getSkills');;
        Route::get('/getPositions', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getPositions'])->name('connections.getPositions');;
        Route::get('/{id}/getSkillsAndPositions', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getSkillsAndPositions'])->name('connections.getSkillsAndPositions');;

    });

    Route::group(['prefix' => 'connection-request','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\ConnectionRequestController::class, 'index'])->name('connectionRequest.index');
    });

    Route::group(['prefix' => 'failed-jobs','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\ErrorController::class, 'indexFailedJob'])->name('failed-jobs.index');
    });

    Route::group(['prefix' => 'logs','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\ErrorController::class, 'indexLogs'])->name('logs.index');
    });

    Route::group(['prefix' => 'companies','middleware'=>'role:Admin'], function () {
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

    Route::group(['prefix' => 'countries','middleware'=>'role:Admin'], function () {

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
