<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\View\Components\chat\SupportChat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChatController extends Controller
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getChatView () {
        //Проверяем авторизацию
        abort_if(($response = Gate::inspect('ChatPolicy-contactCenterChat'))->denied(), $response->code(), $response->message());

        $user = UserController::getAuthentifiedUser();
        $chats = Chat::where('user_id', $user->getUserId())
            ->where('active', true)
            ->get();

        //Получаем все активные чаты с неподтверждённым оператором
        $noConfirmedChats = Chat::where('active', true)
            ->whereNull('user_id')
            ->get();

        $requestedChats = new Collection();
        foreach ($noConfirmedChats as $chat) {
            if (Redis::get("ChatsData:{$chat->id}:SelectedOperator"))
            {
                $requestedChats->push($chat);
            }; //Получаем данные всех чатов, которые ещё  операторов, которые отказались от этого чата
        }

        return (new SupportChat($chats, $requestedChats))->render();
    }
}
