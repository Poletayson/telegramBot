// /**
//  * We'll load the axios HTTP library which allows us to easily issue requests
//  * to our Laravel back-end. This library automatically handles sending the
//  * CSRF token as a header based on the value of the "XSRF" token cookie.
//  */

/**
 * Показать модальное окно с информацией
 * @param id ID модального окна
 * @param code Код ошибки
 * @param title Заголовок
 * @param content Тело сообщения
 */
function showModal(id, code, title, content) {
    if ($(`#${id}`).length) {
        $(`#${id}__title`).html(`${code}. ${title}`);
        $(`#${id}__body`).html(`${content}`);
    }

    let modal = new bootstrap.Modal(`#${id}`);
    modal.show();
}

/**
 * Очистить модальное окно
 * @param id
 */
function clearModal(id) {
    if ($(`#${id}`).length) {
        $(`#${id}__title`).html('');
        $(`#${id}__body`).html('');
    }
}

//Костыли на случай возникновения нескольких фонов
//Удаление лишних фонов
$(document).on('show.bs.modal', '.modal', function () {
    if ($(".modal-backdrop").length > 1) {
        $(".modal-backdrop").not(':first').remove();
    }
});
// Удаление всех фонов при закрытии
$(document).on('hide.bs.modal', '.modal', function () {
    if ($(".modal-backdrop").length > 1) {
        $(".modal-backdrop").remove();
    }
});

/**
 * Обработчик ошибок AJAX
 */
$(document).ajaxError(function (event, jqXHR, ajaxSettings, exception) {
    // $( ".log" ).text( "Triggered ajaxError handler." );
    let code = jqXHR.status;
    //Проверяем есть ли сообщение от сервера
    let xhr = JSON.parse(jqXHR.responseText);
    console.log(jqXHR);
    console.log(xhr);
    let message = xhr.message.length > 0 ? xhr.message : "Ошибка Ajax-запроса";
    let title = 'Ошибка';
    let annotation = '';

    //Сопоставление кодов ошибки
    let statusErrorMap = {
        '400': "Server understood the request, but request content was invalid",
        '401': "Unauthorized access",
        '403': "Forbidden",
        '419': "Authentication Timeout",
        '500': "Internal server error",
        '503': "Service unavailable"
    };
    if (code) {
        title = statusErrorMap[code];
        if (!title) {
            title = "Unknown error";
        }


        switch (code) {
            case 419: {
                annotation = "Ваш CSRF-токен устарел. Необходимо обновить страницу";
                break;
            }
            default: {


                break;
            }
        }
    } else if (exception == 'parsererror') {
        title = "Parsing JSON Request failed";
    } else if (exception == 'timeout') {
        title = "Request Time out";
    } else if (exception == 'abort') {
        title = "Request was aborted by the server";
    } else {
        title = "Unknown Error";
    }

    let content = `<div id="modal-error_message__error_text" class="w-100 text-start">${message}</div>`;
    //Пояснение к ошибке, если есть
    if(annotation.length > 0) {
        content += `<div id="modal-error_message__annotation" class="w-100 text-center text-gray-400 pt-4 pb-2 ps-3 pe3" style="font-size: 0.85rem;">${annotation}</div>`;
    }
    showModal('modal-error_message', code, title, content);
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

let constants = require('./constants');

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY, // constants.PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,    //'5.tcp.eu.ngrok.io',
    wsPort: constants.PUSHER_PORT,
    // enabledTransports: ['ws', 'wss'],
    useTLS: false,
    forceTLS: false,
});
