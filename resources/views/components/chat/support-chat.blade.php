<x-layer :title="$title ?? null" :styles="$styles ?? null">
    <div class="d-flex flex-column w-100 h-100">
        @csrf
        <div class="main__headers flex-grow-0">
            <h1 class="main__title title_h1">Чат контакт-центра</h1>
        </div>
        <nav class="flex-grow-0">
            <div id="nav-tab" class="nav nav-tabs" role="tablist">
            @foreach($chats as $chat)
                    <div id="nav-chat-{{$chat->id}}-tab"
                        class="nav-link position-relative @if($loop->first) active @endif"
                            data-bs-toggle="tab"
                            data-bs-target="#nav-chat-{{$chat->id}}"
                            data-chat-id="{{$chat->id}}"
                            type="button" role="tab"
                            aria-controls="nav-chat-{{$chat->id}}"
                            aria-selected= @if($loop->first) "true" @else "false" @endif >
                        <div class="row align-items-center">
                            <div class="col">#{{$chat->id}}</div>
                                <div class="col pe-1 ">
                                    <button id="chat-close-{{$chat->id}}" class="button button_icon__sm button_icon-cross @if($chat->active) invisible @endif" style="cursor: default;" data-chat-id="{{$chat->id}}"></button>
                                </div>
                        </div>
                        <div id="chat_notification-{{$chat->id}}" class="chat_notification position-absolute d-flex invisible">
                            <div id="chat_notification-{{$chat->id}}-value" data-unreaded_messages=0>0</div>
                        </div>
                    </div>

        {{--            <li class="nav-item">--}}
        {{--                <a class="nav-link @if($loop->first) active @endif" aria-current="page" href="#">#{{$chat->id}}</a>--}}
        {{--            </li>--}}
            @endforeach
            @foreach($requestedChats as $requestedChat)
                <div id="new_chat-{{$requestedChat->id}}-tab" class="nav-link new_chat-request">
                    <div class="row align-items-center">
                        <div class="col text-nowrap pe-1">
                            <span>НОВЫЙ ДИАЛОГ #{{$requestedChat->id}}</span>
                        </div>
                        <div class="col pe-0">
                            <a id="new_chat-accept-{{$requestedChat->id}}" class="button button_violet" data-chat-id="{{$requestedChat->id}}"><span class="button__text">Принять</span></a>
                        </div>
                        <div class="col ps-0 pe-1">
                            <a id="new_chat-reject-{{$requestedChat->id}}" class="button button_white" data-chat-id="{{$requestedChat->id}}"><span class="button__text">Отклонить</span></a>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </nav>
        <div id="nav-tabContent" class="tab-content flex-grow-1 overflow-hidden" >
            @foreach($chats as $chat)
                <div class="tab-pane fade @if($loop->first) show active @endif w-100 h-100" id="nav-chat-{{$chat->id}}" role="tabpanel" aria-labelledby="nav-chat-{{$chat->id}}-tab">
                    <x-chat.Chat :chat="$chat"></x-chat.Chat>
                </div>
            @endforeach
        </div>
    </div>
</x-layer>

<script type="text/javascript" src="/js/app.js" defer></script>
<script type="text/javascript" src="/js/supportChat.js"></script>
<script type="text/javascript">

    $(function () {
        //Устанавливаем CSRF-токен всем заголовкам ajax-запросов
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("[id^='chat_messages-']").scrollBottom();

        $("[id^='chat-close-']").click(closeChat); //Закрыть чат
        $("[id^='complete-dialog-']").click(completeDialog); //Завершить диалог
        $("[id^='button-send_message-']").click(sendMessage); //Отправить сообщение
        $("[data-bs-toggle='tab']").on('shown.bs.tab', onMessagesViewed);   //При открытии вкладки сообщения этого чата прочитываются

        $(`[id^='new_chat-accept-']`).on('click', acceptDialog); //Кнопка "Принять диалог" у запрошенных чатов
        $(`[id^='new_chat-reject-']`).on('click', rejectDialog);    //Кнопка "Отклонить диалог" у запрошенных чатов

        //Для каждого чата нужно проверить сколько непрочитанных сообщений имеется
        $("[id^='nav-chat-'][id$='-tab']").each(function (index, element) {
            unreadMessagesRefresh ($(this).attr('data-chat-id'));   //Считаем сколько у чата непрочитанных сообщений и обновляем уведомления если нужно

            //Для активного чата нужно вызвать функцию
            if ($(this).hasClass('active')) {
                readMessages ($(this).attr('data-chat-id'));
            }
        });

        // $("[id^='chat_messages-']").each(function (index) {
        //     $(this).scrollTop($(this).scrollHeight);
        // });

        //Подписываемся на события канала пользователя
        Echo.private(`user.${$('#user_id').val()}`)
            .listen('NewDialogStarted', (e) => {
                console.log (`NewDialogStarted`);
                onNewDialogRequested (e.chat);
            })
            .listen('NewDialogAccepted', (e) => {
                console.log (`NewDialogAccepted`);
                onNewDialogAccepted (e.chat, e.renderedChat);
            })
            .listen('NewDialogRejected', (e) => {
                console.log (`NewDialogRejected`);
                onNewDialogRejected (e.chat);
            })
            .listen('MessageReceived', (e) => {
                console.log (`MessageReceived`);
                onMessageReceived (e.message, e.renderedMessage);
                // console.log(e.chat);
            })
            .listen('DialogComplete', (e) => {
                console.log (`DialogComplete`);
                onDialogComplete (e.chat);
                // console.log(e.chat);
            })
            .listen('ErrorOccurred', (e) => {
                console.log (`ErrorOccurred`);
                onErrorOccurred (e);
            });

            // .listen('DialogComplete2', (e) => {
            //     console.log (`DialogComplete2`);
            //     // console.log(e.chat);
            // });


        // console.log (`user.${$('#user_id').val()}`);
        //
        // // //Подписываемся на канал пользователя
        // Echo.private(`user.${$('#user_id').val()}`)
        //     .listen('DialogComplete', (e) => {
        //         console.log (`DialogComplete`);
        //         console.log(e.chat);
        //     });
    });
</script>
