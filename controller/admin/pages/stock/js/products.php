<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
?>

<!-- Модальное окно "Добавить товар" -->
<script type="text/javascript">
    function callProduct() {
        var msg = $('#form_add_product').serialize();
        // Установка синхронного запроса для jQuery.ajax
        jQuery.ajaxSetup({async: false});
        jQuery.ajax({
            type: 'POST',
            url: window.location.href,
            data: msg,
            beforeSend: function (data) {
                $('#index_product').modal('hide');
                $('#alert_block').html('<div id="alert" class="alert text-danger fade in"><span class="glyphicon glyphicon-alert"></span> <?php echo lang('alert_wait') ?></div>');
            }
        });
        // Отправка запроса для обновления страницы
        jQuery.get(window.location.href,
                {parent_down: <?php echo $parent_id ?>,
                    message: 'ok'},
                AjaxSuccess);
        // Обновление страницы
        function AjaxSuccess(data) {
            document.location.href = window.location.href;
        }
    }

    // Если закрыли главный модал
    $('#index_product').on('hidden.bs.modal', function (event) {
        $('.product-attribute').empty();
    });
</script>