<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\TelegramUtilityController;
use App\Http\Controllers\View\ChatController;
use App\Http\Middleware\FinishRequest;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Auth\User;
use App\View\Components\Index;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

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



/**
 * Именованный маршрут для входа
 */
Route::get('/login', [Auth\AuthController::class, 'showLogin'])->name('login')->withoutMiddleware('auth');
Route::post('/login', [Auth\AuthController::class, 'login'])->withoutMiddleware('auth');
Route::match(['get', 'post'], '/logout', [Auth\AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return ((new Index())->render());
});
Route::get('/admin', [TelegramUtilityController::class, 'adminPanel']);
Route::get('/admin/registerBot', [TelegramUtilityController::class, 'registerBot']);

Route::get('/supportChat/chat', [ChatController::class, 'getChatView']);
Route::post('/supportChat/chat/{chat}/complete', [\App\Http\Controllers\ChatController::class, 'completeDialog']);  //Завершить диалог
Route::post('/supportChat/chat/{chat}/accept', [\App\Http\Controllers\ChatController::class, 'acceptDialog']);  //Принять диалог
Route::post('/supportChat/chat/{chat}/reject', [\App\Http\Controllers\ChatController::class, 'rejectDialog']);  //Отклонить диалог
Route::post('/supportChat/chat/{chat}/sendMessage', [\App\Http\Controllers\ChatController::class, 'sendMessage']);  //Отправить сообщение
Route::post('/supportChat/chat/{chat}/readMessages', [\App\Http\Controllers\ChatController::class, 'readMessages']);  //Прочитать сообщения этого чата

//Работа с API Телеграма
Route::match(['get', 'post'], '/' . env('TELEGRAM_BOT_URL_PREFIX'), [TelegramUtilityController::class, 'handle'])
    ->middleware(FinishRequest::class)
    ->withoutMiddleware(VerifyCsrfToken::class)
    ->withoutMiddleware('auth');
