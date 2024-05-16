<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FriendRequestController;
use App\Http\Controllers\Api\GroupChatController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Requests\Api\TicketRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/auth/verify' ,[ AuthController::class, 'verify']);
Route::post('user/auth/otp/verify' ,[ AuthController::class, 'otpVerify']);
Route::post('user/auth/register' ,[ AuthController::class, 'register']);
Route::post('user/auth/login' ,[ AuthController::class, 'login']);
Route::post('user/auth/recover/password' ,[ AuthController::class, 'recoverPassword']);
Route::post('user/auth/new/password' ,[ AuthController::class, 'newPassword']);
Route::post('user/auth/social' ,[ AuthController::class, 'social']);
Route::post('user/auth/changePassword' ,[ AuthController::class, 'changePassword']);
Route::post('user/auth/logout' ,[ AuthController::class, 'logout']);
Route::post('user/auth/deleteAccount' ,[ AuthController::class, 'deleteAccount']);
Route::post('user/profile' ,[ UserController::class, 'profile']);
Route::post('user/edit/profile' ,[ UserController::class, 'editProfile']);
Route::post('user/remove/profile/image' ,[ UserController::class, 'removeImage']);
Route::post('user/social/connect' ,[ UserController::class, 'socialConnect']);
Route::post('user/remove/social/connect' ,[ UserController::class, 'removeSocial']);
Route::get('user/social/connect/list/{id}' ,[ UserController::class, 'socialList']);
Route::post('user/block' ,[ UserController::class, 'block']);
Route::get('user/block/list/{id}' ,[ UserController::class, 'blockList']);
Route::post('ticket' ,[ TicketController::class, 'ticket']);
Route::get('ticket/list/{id}/{status}' ,[ TicketController::class, 'list']);
Route::get('ticket/close/{ticket_id}' ,[ TicketController::class, 'closeTicket']);
Route::post('user/message/send' ,[ TicketController::class, 'messageSend']);
Route::get('message/list/{ticket_id}' ,[ TicketController::class, 'messageList']);
Route::get('category/list' ,[ TicketController::class, 'categoryList']);
Route::get('faqs' ,[ TicketController::class, 'faqs']);
Route::post('add/report', [ReportController::class, 'report']);
Route::post('create/post' ,[ PostController::class, 'createPost']);
Route::get('save/post/{post_id}/{user_id}', [PostController::class, 'savePost']);
Route::get('post/delete/{post_id}', [PostController::class, 'delete']);
Route::get('post/detail/{post_id}/{user_id}', [PostController::class, 'postDetail']);
Route::post('create/group/{user_id}', [GroupChatController::class, 'createGroup']);
Route::post('message/send/group', [GroupChatController::class, 'sendMessageGroup']);
Route::get('group/message/list/{group_id}', [GroupChatController::class, 'messageList']);
Route::post('send/request', [FriendRequestController::class, 'sendRequest']);
Route::post('accept/request', [FriendRequestController::class, 'acceptRequest']);
Route::post('cancel/request', [FriendRequestController::class, 'cancelRequest']);
Route::post('remove/friend', [FriendRequestController::class, 'removeFriend']);
Route::get('search/{name}', [UserController::class, 'search']);
