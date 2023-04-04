<x-layer :title="$title ?? null" :styles="$styles ?? null">
    <div class="position-relative d-flex flex-column w-100 h-100 align-content-center justify-center">
        <div class="flex-grow-1"></div>
        <div class="d-flex flex-row">
            <div class="flex-grow-1"></div>
            <div>
                <a id="applyRegisterBot" class="filter__action button" href="/supportChat/chat"><span class="button__text">Панель оператора</span></a>
                <a id="applyRegisterBot" class="filter__action button" href="/admin"><span class="button__text">Панель администратора</span></a>
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
