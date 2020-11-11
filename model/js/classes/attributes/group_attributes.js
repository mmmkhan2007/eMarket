/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
 |    GNU GENERAL PUBLIC LICENSE v.3.0    |
 |  https://github.com/musicman3/eMarket  |
 =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
/**
 * Группа атрибутов
 *
 * @package Group Attributes
 * @author eMarket
 * 
 */
class GroupAttributes {
    /**
     * Конструктор
     *
     * @param lang {Json} (Языковые переменные)
     */
    constructor(lang) {
        this.modal(lang);
        this.click(lang);
    }

    /**
     * Инициализация для модалов
     *
     *@param lang {Array} (Языковые переменные)
     */
    modal(lang) {

        // Если открыли главный модал
        $('#index').on('show.bs.modal', function (event) {

            var jsdata = new JsData();
            var data_id = 'false';
            if (sessionStorage.getItem('attributes') === null){
                sessionStorage.setItem('attributes', '[]');
            }
            var parse_attributes = jsdata.selectParentUids(data_id, $.parseJSON(sessionStorage.getItem('attributes')));

            GroupAttributes.add(lang, parse_attributes);
            // Загружаем удаление группы атрибутов
            GroupAttributes.deleteValue(lang);
        });

        // Если закрыли главный модал
        $('#index').on('hidden.bs.modal', function (event) {
            $('.group-attributes').empty();
            GroupAttributes.clearAttributes();
        });

        // Если закрыли добавление группы атрибутов
        $('#add_group_attributes').on('hidden.bs.modal', function (event) {
            // Загружаем удаление группы атрибутов
            $('.input-add-group-attributes').val('');
            GroupAttributes.deleteValue(lang);
        });
    }

    /**
     * Инициализация для кликов
     *
     *@param lang {Array} (Языковые переменные)
     */
    click(lang) {
        // Если открыли модал списка значений группы атрибута
        $(document).on('click', '.values-group-attribute', function () {
            var jsdata = new JsData();
            var data_id = $(this).closest('tr').attr('id').split('_')[1];
            var parse_attributes = jsdata.selectParentUids('false', $.parseJSON(sessionStorage.getItem('attributes')));
            sessionStorage.setItem('level_1', data_id);

            $('#attribute').modal('show');
            $('#title_attribute').html(jsdata.selectUid(data_id, parse_attributes)[0]['value']);

        });

        // Добавляем значение группы атрибутов
        $(document).on('click', '.add-group-attributes', function () {
            $('#add_group_attributes').modal('show');
            sessionStorage.setItem('action', 'add');
        });

        // Редактируем значение группы атрибутов
        $(document).on('click', '.edit-group-attribute', function () {
            $('#add_group_attributes').modal('show');
            var processing = new AttributesProcessing();
            processing.clickEdit($(this).closest('tr').attr('id').split('_')[1], 'false', 'level_1');
        });

        // Сохраняем значение группы атрибутов
        $(document).on('click', '#save_group_attributes_button', function () {
            $('#add_group_attributes').modal('hide');

            var attributes_bank = $('#group_attributes_add_form').serializeArray();
            var data_id = 'false';
            var jsdata = new JsData();
            var parse_attributes = $.parseJSON(sessionStorage.getItem('attributes'));

            if (sessionStorage.getItem('action') === 'add') {
                var parse_attributes_add = jsdata.add(attributes_bank, parse_attributes, data_id);

                sessionStorage.setItem('attributes', JSON.stringify(parse_attributes_add));
                var parse_attributes_view = jsdata.selectParentUids(data_id, $.parseJSON(sessionStorage.getItem('attributes')));
                GroupAttributes.add(lang, parse_attributes_view);
            }

            if (sessionStorage.getItem('action') === 'edit') {
                var id = sessionStorage.getItem('level_1');
                var parse_attributes_edit = jsdata.editUid(id, parse_attributes, attributes_bank);
                sessionStorage.setItem('attributes', JSON.stringify(parse_attributes_edit));
                var parse_attributes_view = jsdata.selectParentUids(data_id, $.parseJSON(sessionStorage.getItem('attributes')));
                GroupAttributes.add(lang, parse_attributes_view);
            }

            $('.input-add-group-attributes').val('');
        });
    }

    /**
     * Отображение группы атрибутов
     *
     * @param id {String} (id строки)
     * @param value {String} (значение строки)
     * @param lang {Array} (Языковые переменные)
     */
    static addValue(id, value, lang) {
        $('.group-attributes').prepend(
                '<tr class="groupattributes" id="groupattributes_' + id + '">' +
                '<td class="sortyes-group sortleft-m"><div><span class="glyphicon glyphicon-move"> </span></div></td>' +
                '<td class="sortleft-m"><button type="button" class="values-group-attribute btn btn-primary btn-xs"><span class="glyphicon glyphicon-cog"></span></button></td>' +
                '<td>' + value + '</td>' +
                '<td class="al-text-w">' +
                '<div class="b-right"><button type="button" class="delete-group-attribute btn btn-primary btn-xs" data-placement="left" data-toggle="confirmation" data-singleton="true" data-popout="true" data-btn-ok-label="' + lang[0] + '" data-btn-cancel-label="' + lang[1] + '" title="' + lang[2] + '"><span class="glyphicon glyphicon-trash"> </span></button></div>' +
                '<div class="b-left"><button type="button" class="edit-group-attribute btn btn-primary btn-xs" title="' + lang[3] + '"><span class="glyphicon glyphicon-edit"> </span></button></div>' +
                '</td>' +
                '</tr>'
                );
    }

    /**
     * Удаление группы атрибутов
     * 
     * @param lang {Array} (Языковые переменные)
     *
     */
    static deleteValue(lang) {
        $('.delete-group-attribute').confirmation({
            onConfirm: function (event) {
                $(this).closest('tr').remove();

                var jsdata = new JsData();
                var data_id = 'false';
                var parse_attributes = $.parseJSON(sessionStorage.getItem('attributes'));

                var parse_attributes_delete = jsdata.deleteUid($(this).closest('tr').attr('id').split('_')[1], parse_attributes);
                sessionStorage.setItem('attributes', JSON.stringify(parse_attributes_delete));

                var parse_attributes_add = jsdata.selectParentUids(data_id, $.parseJSON(sessionStorage.getItem('attributes')));
                GroupAttributes.add(lang, parse_attributes_add);
                // Загружаем удаление группы атрибутов
                GroupAttributes.deleteValue(lang);
            }
        });
    }

    /**
     * Сортировка группы атрибутов
     * 
     * @param lang {Array} (Языковые переменные)
     *
     */
    static sort(lang) {
        var sortedIDs = $(".group-attributes").sortable("toArray").reverse();
        var processing = new AttributesProcessing();
        var parse_attributes_add = processing.sorted(sortedIDs, 'false');

        GroupAttributes.add(lang, parse_attributes_add);
        GroupAttributes.deleteValue(lang);
    }

    /**
     * Добавление атрибутов
     * 
     * @param lang {Array} (Языковые переменные)
     * @param parse {Array} (Данные по атрибутам)
     *
     */
    static add(lang, parse) {

        var jsdata = new JsData();
        var parse_attributes_sort = jsdata.sort(parse);

        $('.group-attributes').empty();
        parse.forEach((string, index) => {
            var sort_id = string.length - 1;
            string.forEach((item, i) => {
                if (item.name === 'group_attributes_' + lang[4]) {
                    GroupAttributes.addValue(parse_attributes_sort[index][sort_id].uid, parse_attributes_sort[index][i].value, lang);
                }
            });
        });
    }

    /**
     * Очистка атрибутов
     *
     */
    static clearAttributes() {
        ['attributes',
            'level_1',
            'level_2',
            'level_3',
            'action'
        ].forEach((item) => sessionStorage.removeItem(item));
    }
}