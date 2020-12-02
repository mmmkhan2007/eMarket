/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
 |    GNU GENERAL PUBLIC LICENSE v.3.0    |
 |  https://github.com/musicman3/eMarket  |
 =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
/**
 * Запросы Ajax
 *
 * @package Ajax
 * @author eMarket
 * 
 */
class Ajax {
    /**
     * Конструктор
     *
     * @param basic_url {String} (Базовый url)
     */
    constructor(basic_url) {
        this.basic_url = basic_url;
    }

    /**
     * Функция Добавить
     *
     * @param name {String} (имя)
     * @param url {String} (url)
     */
    static callAdd(name, url) {
        if (name === undefined || name === null) {
            var msg = $('#form_add').serialize();
        } else {
            var msg = $('#' + name).serialize();
        }
        jQuery.ajaxSetup({async: false});
        jQuery.ajax({
            type: 'POST',
            url: this.basic_url,
            data: msg,
            beforeSend: function () {
                $('.modal').modal('hide');
            }
        });
        jQuery.get(this.basic_url,
                {modify: 'update_ok'},
                AjaxSuccess);
        function AjaxSuccess(data) {
            if (url === undefined || url === null) {
                document.location.href = window.location.href;
            } else {
                document.location.href = url;
            }
        }
    }

    /**
     * Функция "Удалить"
     *
     * @param id {String} (id)
     * @param url {String} (url)
     */
    static callDelete(id, url) {
        var msg = $('#form_delete' + id).serialize();
        jQuery.ajaxSetup({async: false});
        jQuery.ajax({
            type: 'POST',
            url: this.basic_url,
            data: msg,
            success: function () {
                // Пустой запрос
            }
        });
        jQuery.get(this.basic_url,
                {modify: 'update_ok'},
                AjaxSuccess);
        function AjaxSuccess(data) {
            if (url === undefined) {
                document.location.href = window.location.href;
            } else {
                document.location.href = url;
            }
        }
    }
}