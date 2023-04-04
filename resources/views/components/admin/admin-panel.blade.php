<x-layer :title="$title ?? null" :styles="$styles ?? null">
    <div class="position-relative d-flex flex-column w-100 h-100 align-content-center justify-center">
        <div class="flex-grow-1"></div>
        <div class="d-flex flex-row">
            <div class="flex-grow-1"></div>
            <div>
                <form method="get" action="/admin/registerBot">
                    <span>URL телеграм-бота</span>
                    <input type="text" name="url" size="35" placeholder="https://domain.ru">
                    <a id="applyRegisterBot" class="filter__action button button_violet"><span class="button__text">применить</span></a>
                </form>
            </div>
            <div class="flex-grow-1"></div>
        </div>
        <div class="flex-grow-1"></div>
    </div>
</x-layer>
<script type="text/javascript" src="/js/adminPanel.js"></script>
<script type="text/javascript">
    $(function () {
        $('#applyRegisterBot').click(sendRegisterBotRequest); //Удалить контакт из избранного
    });
</script>
