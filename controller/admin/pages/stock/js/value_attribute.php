<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
?>

<!--Значения атрибута -->
<script type="text/javascript">

    function addValueAttribute(id, value) {
        $('.values_attribute').prepend(
                '<tr id="value_attributes_' + id + '">' +
                '<td>' + value + '</td>' +
                '<td class="al-text-w">' +
                '<div class="b-right"><button class="delete-value-attribute btn btn-primary btn-xs" title="<?php echo lang('button_delete') ?>"><span class="glyphicon glyphicon-trash"> </span></button></div>' +
                '<div class="b-left"><button class="edit-value-attribute btn btn-primary btn-xs" title="<?php echo lang('button_edit') ?>"><span class="glyphicon glyphicon-edit"> </span></button></div>' +
                '</td>' +
                '</tr>'
                );
    }

    // Если открыли модал списка значений атрибута
    $(document).on('click', '.values-attribute', function () {
        var id = $(this).closest('tr').attr('id').split('_')[1];
        sessionStorage.setItem('value_attribute_action', 'add');
        sessionStorage.setItem('value_attribute_action_id', id);
        $('#add').modal('hide');

        $('#values_attribute').modal('show');
        var parse_attributes = $.parseJSON(sessionStorage.getItem('attributes'));
        $('#title_values_attribute').html('Атрибут: ' + parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['value']);

        if ('data' in parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0] === true) {
            for (x = 0; x < parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'].length; x++) {
                addValueAttribute(x + 1, parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'][x]);
            }
        }
    });

    // Если закрыли модал списка значений атрибута
    $('#values_attribute').on('hidden.bs.modal', function (event) {
        $('.values_attribute').empty();
        $('.attribute').empty();
        $('#add').modal('show');
    });

    // Если открыли модал добавления значения атрибута
    $(document).on('click', '.add-values-attribute', function () {
        $('#add_values_attribute').modal('show');
    });

    // Если закрыли модал добавления значения атрибута
    $('#add_values_attribute').on('hidden.bs.modal', function (event) {
        $('.input-add-values-attribute').val('');
    });

    // Редактируем значения атрибута
    $(document).on('click', '.edit-value-attribute', function () {
        var id = $(this).closest('tr').attr('id').split('_')[2];

        sessionStorage.setItem('value_attribute_action', 'edit');
        sessionStorage.setItem('edit_value_attribute_id', id);

        $('#add_values_attribute').modal('show');

        var parse_attributes = $.parseJSON(sessionStorage.getItem('attributes'))[sessionStorage.getItem('value_attribute_action_id') - 1];

        for (x = 0; x < parse_attributes.length; x++) {
            $('input[name="add_values_' + parse_attributes[x]['name'] + '"]').val(parse_attributes[x]['data'][id - 1]);
        }

    });

    // Удаляем значение атрибута
    $(document).on('click', '.delete-value-attribute', function () {
        $(this).closest('tr').remove();

        var parse_attributes = $.parseJSON(sessionStorage.getItem('attributes'));

        for (x = 0; x < parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1].length; x++) {
            parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][x]['data'].splice($(this).closest('tr').attr('id').split('_')[2] - 1, 1);
        }
        sessionStorage.setItem('attributes', JSON.stringify(parse_attributes));

        $('.values_attribute').empty();

        for (x = 0; x < parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'].length; x++) {
            addValueAttribute(x + 1, parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'][x]);
        }

    });

    // Сохраняем значение атрибута
    $(document).on('click', '#save_add_values_attribute', function () {

        $('#add_values_attribute').modal('hide');

        var value_attributes_bank = $('#add_values_attribute_form').serializeArray();
        var parse_attributes = $.parseJSON(sessionStorage.getItem('attributes'));

        //Если атрибут добавляется
        if (sessionStorage.getItem('value_attribute_action') === 'add') {

            for (x = 0; x < parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1].length; x++) {
                if ('data' in parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][x] !== true) {
                    parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][x]['data'] = [value_attributes_bank[x]['value']];
                    sessionStorage.setItem('attributes', JSON.stringify(parse_attributes));
                } else {
                    parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][x]['data'].push(value_attributes_bank[x]['value']);
                    sessionStorage.setItem('attributes', JSON.stringify(parse_attributes));
                }
            }

            $('.values_attribute').empty();
            for (x = 0; x < parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'].length; x++) {
                addValueAttribute(x + 1, parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'][x]);
            }
        }

        //Если атрибут редактируется
        if (sessionStorage.getItem('value_attribute_action') === 'edit') {

            for (x = 0; x < parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1].length; x++) {

                parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][x]['data'][sessionStorage.getItem('edit_value_attribute_id') - 1] = value_attributes_bank[x]['value'];
                sessionStorage.setItem('attributes', JSON.stringify(parse_attributes));

            }

            $('.values_attribute').empty();
            for (x = 0; x < parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'].length; x++) {
                addValueAttribute(x + 1, parse_attributes[sessionStorage.getItem('value_attribute_action_id') - 1][0]['data'][x]);
            }
        }
    });
</script>