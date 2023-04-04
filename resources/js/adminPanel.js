/**
 * Отправить запрос на регистрацию бота посредством вебхука
 * @param event
 */
function sendRegisterBotRequest (event) {
    let botUrl = $('input[name="url"]').val();

    // let url = new URL(`/contacts/${mo}`, );

    let params = new URLSearchParams ();
    if (botUrl.length > 0){
        params.append('url', botUrl);
    }

    let url = `/admin/registerBot` + (params.toString().length > 0 ? '?' : '') + params.toString();
    console.log(url);
    $.ajax({
        url: url,
        method: 'GET',
        type: 'GET',
        dataType: 'json',
        success: (function (data, textStatus){
            console.log('Ответ Телеграма: ' + JSON.stringify(data));
        }),
        error: (function (xhr, ajaxOptions, thrownError){
            console.log('Ошибка: ' + xhr.responseText);
        })
    });
}
