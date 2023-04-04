<div class="chat d-flex flex-column w-100 h-100 ">
    <div class="d-flex flex-row flex-grow-0">
        <div class="chat_header flex-grow-0 pt-3 pb-3">СНИЛС: @if(!is_null($chat->snils)){{$chat->snils}} @else - @endif</div>
        <div class="flex-grow-1"></div>
        <div class="flex-grow-0 pt-3 pb-3">
            <a id="complete-dialog-{{$chat->id}}" class="filter__action button button_violet @if(!$chat->active) invisible @endif" data-chat-id="{{$chat->id}}"><span class="button__text">Завершить диалог</span></a>
        </div>
    </div>

    <div id="chat_messages-{{$chat->id}}" class="flex-grow-1 overflow-auto pt-2 pb-2">
    @if(!is_null($chat->messages))
        @foreach($chat->messages as $message)
            <div class="message_wrapper d-flex @if($message->from_client) justify-content-start @else justify-content-end @endif ">
                <x-chat.Message :message="$message"></x-chat.Message>
            </div>
        @endforeach
    @endif
    </div>

    <div id="bottom_block-{{$chat->id}}">
        @if($chat->active)
            <div class="d-flex flex-grow-0 align-items-center">
                <div class="flex-grow-1">
                    <textarea id="textarea-message-{{$chat->id}}" class="textarea" placeholder="Сообщение"></textarea>
                </div>
                <div class="flex-grow-0 ps-2">
                    <a id="button-send_message-{{$chat->id}}" class="button_circle" data-chat-id="{{$chat->id}}">
                        <span class="icon icon_plane"></span>
                    </a>
                </div>
            </div>
        @else
            <div class="text-center pt-4 pb-5">
                <span class="text-dark">Диалог завершён. Чат находится в архиве</span>
            </div>
        @endif
    </div>

</div>
