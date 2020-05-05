<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

// Подгружаем Ajax Добавить, Редактировать, Удалить
\eMarket\Ajax::action('');
?>
<link rel="stylesheet" href="/ext/bootstrap-switch/css/bootstrap-switch.min.css" type="text/css"/>
<script type="text/javascript" src="/ext/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script type="text/javascript">
    $('#default').bootstrapSwitch();
    $('#default_edit').bootstrapSwitch();
</script>

<!-- Загрузка данных в модальное окно Добавить -->
<script type = "text/javascript">
    $('#add').on('show.bs.modal', function (event) {
        // Получаем массивы данных
        var countries = $('div#ajax_data').data('countries');

        $("#countries").empty();

        //Устанавливаем Страну
        for (x = 0; x < countries.length; x++) {
            $("#countries").append($('<option value="' + countries[x]['id'] + '">' + countries[x]['name'] + '</option>'));
        }

        // Устанавливаем Регион
        jQuery.post('<?php echo \eMarket\Valid::inSERVER('REQUEST_URI') ?>',
                {countries_select: countries[0]['id']},
                AjaxSuccess);
        // Обновление страницы
        function AjaxSuccess(data) {
            var regions = $.parseJSON(data);
            $("#regions").empty();

            for (x = 0; x < regions.length; x++) {
                $("#regions").append($('<option value="' + regions[x]['id'] + '">' + regions[x]['name'] + '</option>'));
            }
        }
        // Если выбрали страну, то загружаем новые регионы
        $('#countries').change(function (event) {
            jQuery.post('<?php echo \eMarket\Valid::inSERVER('REQUEST_URI') ?>',
                    {countries_select: $("#countries").val()},
                    AjaxSuccess);
            // Обновление страницы
            function AjaxSuccess(data) {
                var regions = $.parseJSON(data);
                $("#regions").empty();

                for (x = 0; x < regions.length; x++) {
                    $("#regions").append($('<option value="' + regions[x]['id'] + '">' + regions[x]['name'] + '</option>'));
                }
            }
        });
    });
</script>

<!-- Загрузка данных в модальное окно Редактировать -->
<script type="text/javascript">
    $('#edit').on('show.bs.modal', function (event) {
        $('#default_edit').bootstrapSwitch('destroy', true);
        var button = $(event.relatedTarget);
        var modal_id = button.data('edit') - 1; // Получаем ID из data-edit при клике на кнопку редактирования

        // Получаем массивы данных
        var edit_data = $('div#ajax_data').data('json');
        var countries = $('div#ajax_data').data('countries');

        $("#countries_edit").empty();

        //Устанавливаем Страну
        for (x = 0; x < countries.length; x++) {
            if (countries[x]['id'] === edit_data[modal_id]['countries_id']) {
                $("#countries_edit").append($('<option selected value="' + countries[x]['id'] + '">' + countries[x]['name'] + '</option>'));
            } else {
                $("#countries_edit").append($('<option value="' + countries[x]['id'] + '">' + countries[x]['name'] + '</option>'));
            }
        }
        // Устанавливаем Регион
        jQuery.post('<?php echo \eMarket\Valid::inSERVER('REQUEST_URI') ?>',
                {countries_select: edit_data[modal_id]['countries_id']},
                AjaxSuccess);
        // Обновление страницы
        function AjaxSuccess(data) {
            var regions = $.parseJSON(data);
            $("#regions_edit").empty();

            for (x = 0; x < regions.length; x++) {
                if (regions[x]['id'] === edit_data[modal_id]['regions_id']) {
                    $("#regions_edit").append($('<option selected value="' + regions[x]['id'] + '">' + regions[x]['name'] + '</option>'));
                } else {
                    $("#regions_edit").append($('<option value="' + regions[x]['id'] + '">' + regions[x]['name'] + '</option>'));
                }
            }
        }

        // Если выбрали страну, то загружаем новые регионы
        $('#countries_edit').change(function (event) {
            jQuery.post('<?php echo \eMarket\Valid::inSERVER('REQUEST_URI') ?>',
                    {countries_select: $("#countries_edit").val()},
                    AjaxSuccess);
            // Обновление страницы
            function AjaxSuccess(data) {
                var regions = $.parseJSON(data);
                $("#regions_edit").empty();

                for (x = 0; x < regions.length; x++) {
                    if (regions[x]['id'] === edit_data[modal_id]['regions_id']) {
                        $("#regions_edit").append($('<option selected value="' + regions[x]['id'] + '">' + regions[x]['name'] + '</option>'));
                    } else {
                        $("#regions_edit").append($('<option value="' + regions[x]['id'] + '">' + regions[x]['name'] + '</option>'));
                    }
                }
            }
        });

        //Устанавливаем данные в поля
        $('#city_edit').val(edit_data[modal_id]['city']);
        $('#zip_edit').val(edit_data[modal_id]['zip']);
        $('#address_edit').val(edit_data[modal_id]['address']);
        $('#js_edit').val(modal_id + 1);

        // Меняем значение чекбокса
        $('#default_edit').prop('checked', edit_data[modal_id]['default']);
        $('#default_edit').bootstrapSwitch();
    });
</script>