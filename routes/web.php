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
        Route::get('/{id}/show', [App\Http\Controllers\Dashboard\AccountController::class, 'show'])->name('accounts.show');
        Route::get('/{id}/checkLife', [App\Http\Controllers\Dashboard\AccountController::class, 'checkLife'])->name('accounts.checkLife');
        Route::get('/login/{type}', [App\Http\Controllers\Dashboard\AccountController::class, 'login'])->name('accounts.login')->middleware(['role:Admin|Manager']);
        Route::post('/store', [App\Http\Controllers\Dashboard\AccountController::class, 'store'])->name('accounts.store')->middleware('role:Admin');
        Route::get('/edit/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'edit'])->name('accounts.edit')->middleware('role:Admin');
        Route::put('/update/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'update'])->name('accounts.update')->middleware('role:Admin');
        Route::get('/{id}/conversations/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConversations'])->name('accounts.syncConversations')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/connections/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConnections'])->name('accounts.syncConnections')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/requests/sync', [App\Http\Controllers\Dashboard\AccountController::class, 'syncRequests'])->name('accounts.syncRequests')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/requests', [App\Http\Controllers\Dashboard\AccountController::class, 'getRequests'])->name('accounts.requests');
        Route::get('/{id}/conversations', [App\Http\Controllers\Dashboard\AccountController::class, 'getConversations']);
        Route::get('/{id}/conversation-messages', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConversationsMessages'])->name('accounts.syncConversationsMessages');
        Route::get('/{id}/conversation-last-messages', [App\Http\Controllers\Dashboard\AccountController::class, 'syncConversationsLastMessages'])->name('accounts.syncConversationsLastMessages');
        Route::get('/{id}/conversations/history', [App\Http\Controllers\Dashboard\AccountController::class, 'conversations'])->name('accounts.conversations')->middleware(['role:Admin|Manager']);
        Route::get('/{id}/conversations/{conversation_id}/messages', [App\Http\Controllers\Dashboard\AccountController::class, 'conversationMessages'])->name('accounts.conversationMessages')->middleware(['role:Admin|Manager']);
        Route::get('/checkAllLife', [App\Http\Controllers\Dashboard\AccountController::class, 'checkAllLife'])->middleware(['role:Admin|Manager']);
        Route::get('/checkOnline', [App\Http\Controllers\Dashboard\AccountController::class, 'checkOnline'])->middleware(['role:Admin|Manager']);
        Route::get('/{id}/setOnlineParameter', [App\Http\Controllers\Dashboard\AccountController::class, 'setOnlineParameter'])->middleware(['role:Admin|Manager']);
        Route::delete('/{id}', [App\Http\Controllers\Dashboard\AccountController::class, 'destroy'])->name('accounts.destroy');

    });

    Route::group(['prefix' => 'keys','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\KeyController::class, 'index'])->name('keys.index');
        Route::post('/store', [App\Http\Controllers\Dashboard\KeyController::class, 'store'])->name('keys.store');
        Route::get('/{id}/edit', [App\Http\Controllers\Dashboard\KeyController::class, 'edit'])->name('keys.edit');
        Route::get('/{id}/search', [App\Http\Controllers\Dashboard\KeyController::class, 'search'])->name('keys.search');
        Route::get('/{id}/searchByCompanies', [App\Http\Controllers\Dashboard\KeyController::class, 'searchByCompanies'])->name('keys.searchByCompanies');
        Route::put('/{id}/update', [App\Http\Controllers\Dashboard\KeyController::class, 'update'])->name('keys.update');
        Route::delete('/{id}', [App\Http\Controllers\Dashboard\KeyController::class, 'destroy'])->name('keys.destroy');
    });

    Route::group(['prefix' => 'proxies','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\ProxyController::class, 'index'])->name('proxies.index');
        Route::post('/store', [App\Http\Controllers\Dashboard\ProxyController::class, 'store'])->name('proxies.store');
        Route::get('/{id}/edit', [App\Http\Controllers\Dashboard\ProxyController::class, 'edit'])->name('proxies.edit');
        Route::put('/update/{id}', [App\Http\Controllers\Dashboard\ProxyController::class, 'update'])->name('proxies.update');
        Route::get('/{id}/check', [App\Http\Controllers\Dashboard\ProxyController::class, 'checkLife'])->name('proxies.check');
        Route::delete('/{id}', [App\Http\Controllers\Dashboard\ProxyController::class, 'destroy'])->name('proxies.destroy');
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
        Route::delete('/{id}', [App\Http\Controllers\Dashboard\UserController::class, 'destroy'])->name('users.destroy');

    });

    Route::group(['prefix' => 'connections'], function () {

        Route::get('/', [App\Http\Controllers\Dashboard\ConnectionController::class, 'index'])->name('connections.index');
        Route::get('/{id}/getInfo', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getInfo'])->name('connections.getInfo');
        Route::get('/{id}/edit', [App\Http\Controllers\Dashboard\ConnectionController::class, 'edit'])->name('connections.edit');
        Route::post('/{id}/statuses', [App\Http\Controllers\Dashboard\ConnectionController::class, 'addStatus'])->name('connections.addStatus');
        Route::post('/{id}/keys', [App\Http\Controllers\Dashboard\ConnectionController::class, 'addKeys'])->name('connections.addKeys');
        Route::get('/{id}/trackingId', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getTrackingId']);
        Route::post('/{id}/request', [App\Http\Controllers\Dashboard\ConnectionController::class, 'sendRequest']);
        Route::get('/{id}/messages', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getMessages']);
        Route::post('/{id}/createConversation', [App\Http\Controllers\Dashboard\ConnectionController::class, 'createConversation']);
        Route::get('/getSkills', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getSkills'])->name('connections.getSkills');;
        Route::get('/getPositions', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getPositions'])->name('connections.getPositions');;
        Route::get('/calcExperience', [App\Http\Controllers\Dashboard\ConnectionController::class, 'calcExperience'])->name('connections.calcExperience');;
        Route::get('/{id}/getSkillsAndPositions', [App\Http\Controllers\Dashboard\ConnectionController::class, 'getSkillsAndPositions'])->name('connections.getSkillsAndPositions');;
        Route::get('/exportCvs', [App\Http\Controllers\Dashboard\ConnectionController::class, 'exportCvs'])->name('connections.exportCvs');
        Route::get('/carrierInterest', [App\Http\Controllers\Dashboard\ConnectionController::class, 'carrierInterest'])->name('connections.carrierInterest');;
        Route::get('/{id}/fullInfo', [App\Http\Controllers\Dashboard\ConnectionController::class, 'fullInfo'])->name('connections.fullInfo');

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

    Route::group(['prefix' => 'categories','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [App\Http\Controllers\Dashboard\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/store', [App\Http\Controllers\Dashboard\CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/{id}/delete', [App\Http\Controllers\Dashboard\CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/{id}/edit', [App\Http\Controllers\Dashboard\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{id}/update', [App\Http\Controllers\Dashboard\CategoryController::class, 'update'])->name('categories.update');
    });

    Route::group(['prefix' => 'companies','middleware'=>'role:Admin'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\CompanyController::class, 'index'])->name('companies.index');
        Route::get('/sync', [App\Http\Controllers\Dashboard\CompanyController::class, 'sync'])->name('companies.sync');
        Route::get('/{id}/connections', [App\Http\Controllers\Dashboard\CompanyController::class, 'getConnections'])->name('companies.connections');
    });

    Route::group(['prefix' => 'conversations'], function () {

        Route::get('/{id}', [App\Http\Controllers\Dashboard\ConversationController::class, 'show']);
        Route::get('/entityUrn/{entityUrn}', [App\Http\Controllers\Dashboard\ConversationController::class, 'getByEntityUrn']);
        Route::get('/account/{id}', [App\Http\Controllers\Dashboard\ConversationController::class, 'getByAccount']);
        Route::post('/{id}/messages-sync', [App\Http\Controllers\Dashboard\ConversationController::class, 'syncMessages']);
        Route::get('/{entityUrn}/messages', [App\Http\Controllers\Dashboard\ConversationController::class, 'getMessages']);
        Route::post('/{id}/sync-last-messages', [App\Http\Controllers\Dashboard\ConversationController::class, 'synLastMessages']);

    });

    Route::group(['prefix' => 'messages'], function () {

        Route::post('/', [App\Http\Controllers\Dashboard\MessageController::class, 'store']);
        Route::post('/{id}/resend', [App\Http\Controllers\Dashboard\MessageController::class, 'resend']);
        Route::put('/{id}/destroy', [App\Http\Controllers\Dashboard\MessageController::class, 'update']);

    });


    Route::group(['prefix' => 'jobs'], function () {
        Route::get('',[App\Http\Controllers\Dashboard\JobController::class, 'index'])->name('jobs.index');
        Route::post('/delete',[App\Http\Controllers\Dashboard\JobController::class, 'delete']);
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

    Route::group(['prefix' => 'searches'], function () {
        Route::post('/', [App\Http\Controllers\Dashboard\SearchController::class, 'store'])->name('searches.store');
        Route::get('/', [App\Http\Controllers\Dashboard\SearchController::class, 'index'])->name('searches.index');
        Route::delete('/{id}', [App\Http\Controllers\Dashboard\SearchController::class, 'destroy'])->name('searches.destroy');
    });

    Route::group(['prefix' => 'logs'], function () {
        Route::get('/', [App\Http\Controllers\Dashboard\LogController::class, 'index'])->name('logs.index');
    });
});
