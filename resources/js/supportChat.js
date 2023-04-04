/**
 * Закрыть чат
 * @param event
 */
function closeChat(event) {
    event.stopPropagation();    //Предотвращаем выполнение обработчиков других элементов
    let chatId = this.getAttribute('data-chat-id');

    $(`#nav-chat-${chatId}`).remove();
    $(`#nav-chat-${chatId}-tab`).remove();

    //Включаем первую попавшуюся вкладку
    if ($('div').is("[id^=nav-chat]")) {
        let bsInstance = bootstrap.Tab.getOrCreateInstance($("[id^=nav-chat]"));
        bsInstance.show();

    }

}

/**
 * Завершить диалог. Он будет отмечен как неактивный и считаться архивным
 * @param event
 */
function completeDialog(event) {
    event.stopPropagation();    //Предотвращаем выполнение обработчиков других элементов
    let chatId = this.getAttribute('data-chat-id');

    // $('#mo_data').attr('data-medicine-organization');


    $.ajax({
        url: `/supportChat/chat/${chatId}/complete`,
        type: 'POST',
        method: 'POST',
        dataType: 'json',
    });
}

/**
 * Принять новый входящий диалог
 * @param event
 */
function acceptDialog(event) {
    event.stopPropagation();    //Предотвращаем выполнение обработчиков других элементов
    let chatId = this.getAttribute('data-chat-id');

    $.ajax({
        url: `/supportChat/chat/${chatId}/accept`,
        type: 'POST',
        method: 'POST',
        dataType: 'json',
    });
}

/**
 * Отклонить новый входящий диалог
 * @param event
 */
function rejectDialog(event) {
    event.stopPropagation();    //Предотвращаем выполнение обработчиков других элементов
    let chatId = this.getAttribute('data-chat-id');

    $.ajax({
        url: `/supportChat/chat/${chatId}/reject`,
        type: 'POST',
        method: 'POST',
        dataType: 'json',
    });
}

/**
 * Отправить сообщение клиенту
 * @param event
 */
function sendMessage(event) {
    event.stopPropagation();    //Предотвращаем выполнение обработчиков других элементов
    let chatId = this.getAttribute('data-chat-id');

    $.ajax({
        url: `/supportChat/chat/${chatId}/sendMessage`,
        type: 'POST',
        method: 'POST',
        dataType: 'json',
        data: {
            text: $(`#textarea-message-${chatId}`).val(),
        },
    });
}


/**
 * Был запрошен новый диалог
 * @param chat
 */
function onNewDialogRequested(chat) {
    $('#nav-tab').append(`<div id="new_chat-${chat.id}-tab" class="nav-link new_chat-request">
                            <div class="row align-items-center">
                                <div class="col text-nowrap pe-1">
                                    <span>НОВЫЙ ДИАЛОГ #${chat.id}</span>
                                </div>
                                <div class="col pe-0">
                                    <a id="new_chat-accept-${chat.id}" class="button button_violet" data-chat-id="${chat.id}"><span class="button__text">Принять</span></a>
                                </div>
                                <div class="col ps-0 pe-1">
                                    <a id="new_chat-reject-${chat.id}" class="button button_white" data-chat-id="${chat.id}"><span class="button__text">Отклонить</span></a>
                                </div>
                            </div>
                        </div>`);

    $(`#new_chat-accept-${chat.id}`).on('click', acceptDialog); //Кнопка "Принять диалог"
    $(`#new_chat-reject-${chat.id}`).on('click', rejectDialog);    //Кнопка "Отклонить диалог"
}

/**
 * Подтверждён новый диалог
 * @param chat
 * @param renderedChat
 */
function onNewDialogAccepted(chat, renderedChat) {
    $(`#new_chat-${chat.id}-tab`).remove(); //Удаляем вкладку для подтверждения

    let isFirst = $('#nav-tab').html() === '';  //Определяем что чат будет первым

    //Вкладка
    $('#nav-tab').append(`<div id="nav-chat-${chat.id}-tab"
                            class="nav-link position-relative ${isFirst ? ' active' : ''}"
                                data-bs-toggle="tab"
                                data-bs-target="#nav-chat-${chat.id}"
                                data-chat-id="${chat.id}"
                                type="button" role="tab"
                                aria-controls="nav-chat-${chat.id}"
                                aria-selected=${isFirst ? 'true' : 'false'}>
                            <div class="row align-items-center">
                                <div class="col">#${chat.id}</div>
                                    <div class="col pe-1 ">
                                        <button id="chat-close-${chat.id}" class="button button_icon__sm button_icon-cross ${chat.active ? 'invisible' : ''}" style="cursor: default;" data-chat-id="${chat.id}"></button>
                                    </div>
                            </div>
                            <div id="chat_notification-${chat.id}" class="chat_notification position-absolute d-flex invisible">
                                <div id="chat_notification-${chat.id}-value" data-unreaded_messages=0>0</div>
                            </div>
                        </div>`);

    //Тело чата
    $('#nav-tabContent').append(`<div class="tab-pane fade ${isFirst ? ' show active' : ''} w-100 h-100" id="nav-chat-${chat.id}" role="tabpanel" aria-labelledby="nav-chat-${chat.id}-tab">
                                    ${renderedChat}
                                </div>`);

    //Навешиваем обработчики на элементы нового чата
    $(`#chat-close-${chat.id}`).click(closeChat); //Закрыть чат
    $(`#complete-dialog-${chat.id}`).click(completeDialog); //Завершить диалог
    $(`#button-send_message-${chat.id}`).click(sendMessage); //Отправить сообщение
    $(`#nav-chat-${chat.id}-tab`).on('shown.bs.tab', onMessagesViewed); //При открытии вкладки сообщения этого чата прочитываются

    unreadMessagesRefresh(chat.id);
}
/**
 * Оператор отказался от нового диалога
 * @param chat
 */
function onNewDialogRejected(chat) {
    $(`#new_chat-${chat.id}-tab`).remove(); //Удаляем вкладку для подтверждения
}

/**
 * Получено новое сообщение
 * @param message
 * @param renderedMessage
 */
function onMessageReceived(message, renderedMessage) {
    let chatMessages = $(`#chat_messages-${message.chat.id}`);
    chatMessages.append(`<div class="message_wrapper d-flex ${message.from_client ? 'justify-content-start' : 'justify-content-end'}">
                                                    ${renderedMessage}
                                                </div>`);
    //Сообщение было от оператора, очищаем область ввода
    if (!message.from_client) {
        $(`#textarea-message-${message.chat.id}`).val('');
    }
    //Если сообщение пришло в активный чат, нужно прокрутить чат вниз и вызвать функцию чтения сообщения
    if ($(`#nav-chat-${message.chat.id}-tab`).hasClass('active')) {
        chatMessages.scrollBottom();
        readMessages (message.chat.id);
    } else {
        //Иначе просто пересчитываем сколько непросмотренных сообщений
        unreadMessagesRefresh(message.chat.id);
    }

    // chatMessages.scrollTop(chatMessages.scrollHeight);  //Прокручиваем вниз
}

/**
 * Диалог был завершён
 * @param chat
 */
function onDialogComplete(chat) {
    //Если есть тело чата, значит чат находился в обработке оператора
    if ($(`#nav-chat-${chat.id}`).length){
        $(`#chat-close-${chat.id}`).removeClass('invisible');    //Показываем кнопку для закрытия чата
        $(`#complete-dialog-${chat.id}`).remove();  //Удаляем кнопку завершения диалога

        //Удаляем элементы для отправки сообщений
        $(`#textarea-${chat.id}`).remove();
        $(`#button-send_message-${chat.id}`).remove();
        $(`#bottom_block-${chat.id}`).html(`<div class="text-center pt-4 pb-5">
                                            <span class="text-dark">Диалог завершён. Чат находится в архиве</span>
                                        </div>`);
    }

    //А может диалог ещё не был принят оператором, а клиент уже успел отменить его? Удаляем запрос на диалог
    if ($(`#new_chat-${chat.id}-tab`).length) {
        console.error(`Будет удалён элемент #new_chat-${chat.id}-tab`);
        $(`#new_chat-${chat.id}-tab`).remove();  //Удаляем кнопку завершения диалога
    }
}

/**
 * Произошла ошибка
 * @param event
 */
function onErrorOccurred(event) {
    console.error('Произошла ошибка: ' + event.text);
}

//При открытии вкладки сообщения этого чата прочитываются
$("[data-bs-toggle='tab']").on('shown.bs.tab', function (event) {
    readMessages ($(this).attr('data-chat-id'));
});

/**
 * Событие "Сообщения просмотрены"
 * @param event
 */
function onMessagesViewed(event) {
    //Определяем нужный чат и прочитываем сообщения
    let chatId = $(this).attr('data-chat-id');
    readMessages(chatId);
}

/**
 * "Прочитать" сообщения выбранного чата
 * @param chatId
 */
function readMessages(chatId) {
    $.ajax({
        url: `/supportChat/chat/${chatId}/readMessages`,
        type: 'POST',
        method: 'POST',
        dataType: 'json',
        success: (function (data, textStatus){

            if (data['result'] === 0) {
                let messages = data['data'];
                // console.log(`Сообщения: ${messages}`);
                //У всех сообщений, соответствующих ID из пришедшего списка, удаляем класс "не прочитано"
                for (let messageId in messages) {
                    $(`#message-${messages[messageId]}`).removeClass('unread');
                }
                unreadMessagesRefresh(chatId);  //Обновляем отметку о непрочитанных сообщениях для выбранного чата

            } else {
                console.error('Ошибка при прочтении сообщений: ' + data['text']);
            }
        })
    });
}

/**
 * Обновить отметку о непрочитанных сообщениях для выбранного чата
 * @param chatId
 */
function unreadMessagesRefresh(chatId) {
    let unreadMessagesCount = 0;
    //Находим все непрочитанные сообщения этого чата
    let unreadMessages = $(`#chat_messages-${chatId}`).find('[id^=message-].unread');
    unreadMessagesCount = unreadMessages.length;
    console.log(`unreadMessagesRefresh. Чат: ${chatId}, сообщений: ${unreadMessagesCount}`);
    if (unreadMessagesCount > 0) {
        $(`#chat_notification-${chatId}`).removeClass('invisible');
        $(`#chat_notification-${chatId}-value`).html(unreadMessagesCount).attr('data-unreaded_messages', unreadMessagesCount);
        //$(`#chat_notification-${chatId}-value`).attr('data-unreaded_messages', unreadMessagesCount);
    } else {
        $(`#chat_notification-${chatId}`).addClass('invisible');
        $(`#chat_notification-${chatId}-value`).html(0).attr('data-unreaded_messages', 0);
        // $(`#chat_notification-${chatId}-value`).attr('data-unreaded_messages', 0);
    }
}

