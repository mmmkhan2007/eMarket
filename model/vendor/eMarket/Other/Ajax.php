<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Other;

class Ajax {

    /**
     * Ajax обработка для Добавить, Редактировать, Удалить
     *
     * @param строка $url
     * @return javascript
     */
    public function action($url) {
        $VALID = new \eMarket\Core\Valid;

        ?>
        <!-- Модальное окно "Добавить" -->
        <script type="text/javascript">
            function callAdd() {
                var msg = $('#form_add').serialize();
                // Установка синхронного запроса для jQuery.ajax
                jQuery.ajaxSetup({async: false});
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo $url ?>',
                    data: msg,
                    beforeSend: function () {
                        $('#add').modal('hide');
                    }
                });
                // Отправка запроса для обновления страницы
                jQuery.get('<?php echo $url ?>', // отправка данных GET
                        {modify: 'update_ok'},
                        AjaxSuccess);
                // Обновление страницы
                function AjaxSuccess(data) {
                    setTimeout(function () {
                        document.location.href = '<?php echo $VALID->inSERVER('REQUEST_URI') ?>';
                    }, 100);
                }
            }
        </script>

        <!-- Модальное окно "Редактировать" -->
        <script type="text/javascript">
            function callEdit() {
                var msg = $('#form_edit').serialize();
                // Установка синхронного запроса для jQuery.ajax
                jQuery.ajaxSetup({async: false});
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo $url ?>',
                    data: msg,
                    beforeSend: function () {
                        $('#edit').modal('hide');
                    }
                });
                // Отправка запроса для обновления страницы
                jQuery.get('<?php echo $url ?>', // отправка данных GET
                        {modify: 'update_ok'},
                        AjaxSuccess);
                // Обновление страницы
                function AjaxSuccess(data) {
                    setTimeout(function () {
                        document.location.href = '<?php echo $VALID->inSERVER('REQUEST_URI') ?>';
                    }, 100);
                }
            }
        </script>

        <!-- Функция "Удалить" -->
        <script type="text/javascript">
            function callDelete(id) {
                var msg = $('#form_delete' + id).serialize();
                // Установка синхронного запроса для jQuery.ajax
                jQuery.ajaxSetup({async: false});
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo $url ?>',
                    data: msg,
                    success: function () {
                        // Пустой запрос
                    }
                });
                // Отправка запроса для обновления страницы
                jQuery.get('<?php echo $url ?>', // отправка данных GET
                        {modify: 'update_ok'},
                        AjaxSuccess);
                // Обновление страницы
                function AjaxSuccess(data) {
                    setTimeout(function () {
                        document.location.href = '<?php echo $VALID->inSERVER('REQUEST_URI') ?>';
                    }, 100);
                }
            }
        </script>

        <?php
    }

    /**
     * jQuery File Upload
     *
     * @param строка $url
     * @param строка $dir
     * @param массив $resize_param
     * @return javascript
     */
    public function fileUpload($url, $dir, $resize_param) {

        $FILES = new \eMarket\Other\Files;

        $resize_max = $FILES->imgResizeMax($resize_param);

        ?>
        <!--Подгружаем jQuery File Upload -->
        <script src = "/ext/jquery_file_upload/js/vendor/jquery.ui.widget.js"></script>
        <script src="/ext/jquery_file_upload/js/jquery.iframe-transport.js"></script>
        <script src="/ext/jquery_file_upload/js/jquery.fileupload.js"></script>
        <script src="/ext/fastmd5/md5.min.js"></script>
        <script type="text/javascript">
            // Загрузка новых изображений в модальное окно "Редактировать и Добавить"
            $(function () {
                'use strict';
                var url = '/uploads/temp/';

                $('#fileupload-add, #fileupload-edit').fileupload({
                    url: url,
                    dataType: 'json',
                    submit: function (e, data) {
                        $('#alert_messages_add').empty();
                        $('#alert_messages_edit').empty();
                    },
                    done: function (e, data) {

                        $.each(data.result.files, function (index, file) {
                            var hash_name = md5(file.name); // Хэшируем

                            jQuery.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: '<?php echo $url ?>',
                                data: {image_data: file.name},
                                success: function (image_size) {
                                    // Вычисляем размеры изображения
                                    var this_width = image_size[0]; // Ширина оригинала
                                    var this_height = image_size[1]; // Высота оригинала
                                    var quality_width = <?php echo $resize_max[0] ?>; // Минимальный размер ширины для качественного ресайза
                                    var quality_height = <?php echo $resize_max[1] ?>; // Минимальный размер высоты для качественного ресайза

                                    if (this_height < quality_height && this_width < quality_width) {
                                        // Если изображение не соответствует минимальным размерам то выводим сообщение
                                        if ($('#add').hasClass('in') === true) {
                                            $('#alert_messages_add').html('<div class="alert alert-danger"><?php echo lang('image_resize_error') ?> ' + quality_width + 'x' + quality_height + '</div>');
                                        }
                                        if ($('#edit').hasClass('in') === true) {
                                            $('#alert_messages_edit').html('<div class="alert alert-danger"><?php echo lang('image_resize_error') ?> ' + quality_width + 'x' + quality_height + '</div>');
                                        }
                                    } else {
                                        // Если все ок, то выводим изображение
                                        if ($('#add').hasClass('in') === true) {
                                            $('<span class="file-upload" id="image_add_new_' + hash_name + '"/>').html('<div class="holder"><img src="/uploads/temp/thumbnail/' + file.name + '" class="thumbnail" id="general_' + hash_name + '" /><div class="block"><button class="btn btn-primary btn-xs" type="button" name="deleteImageAddNew_' + hash_name + '" onclick="deleteImageAddNew(\'' + file.name + '\', \'' + hash_name + '\')"><span class="glyphicon glyphicon-trash"></span></button> <button class="btn btn-primary btn-xs" type="button" name="imageGeneralAddNew_' + hash_name + '" onclick="imageGeneralAddNew(\'' + file.name + '\', \'' + hash_name + '\')"><span class="glyphicon glyphicon-star"></span></button></div></div>').appendTo('#logo-add'); // Вставляем лого
                                        }
                                        if ($('#edit').hasClass('in') === true) {
                                            $('<span class="file-upload" id="image_edit_new_' + hash_name + '"/>').html('<div class="holder"><img src="/uploads/temp/thumbnail/' + file.name + '" class="thumbnail" id="general_edit_' + hash_name + '" /><div class="block"><button class="btn btn-primary btn-xs" type="button" name="deleteImageEditNew_' + hash_name + '" onclick="deleteImageEditNew(\'' + file.name + '\', \'' + hash_name + '\')"><span class="glyphicon glyphicon-trash"></span></button> <button class="btn btn-primary btn-xs" type="button" name="imageGeneralEditNew_' + hash_name + '" onclick="imageGeneralEditNew(\'' + file.name + '\', \'' + hash_name + '\')"><span class="glyphicon glyphicon-star"></span></button></div></div>').appendTo('#logo-edit'); // Вставляем лого
                                        }
                                    }
                                }
                            });

                        });
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('.progress-bar').css(
                                'width',
                                progress + '%'
                                );
                        // Разные стили для прогресс-бара и надпись об успехе загрузки
                        $('.progress-bar').empty();
                        $('.progress-bar').removeClass('progress-bar progress-bar-success').addClass('progress-bar progress-bar-warning progress-bar-striped active');
                        if (progress === 100) {
                            setTimeout(function () {
                                $('.progress-bar').html('<?php echo lang('download_complete') ?>');
                                $('.progress-bar').removeClass('progress-bar progress-bar-warning progress-bar-striped active').addClass('progress-bar progress-bar-success');
                            }, 1000);
                        }
                    }
                }).prop('disabled', !$.support.fileInput)
                        .parent().addClass($.support.fileInput ? undefined : 'disabled');
            });

            //Если открыли модальное окно
            $(this).on('show.bs.modal', function (event) {
                // Отправка запроса для очистки временных файлов
                jQuery.post('<?php echo $url ?>',
                        {file_upload: 'empty'});
            });

            // Очищаем модальное окно и hidden input при закрытии
            $(this).on('hidden.bs.modal', function (event) {
                $('.progress-bar').css('width', 0 + '%');
                $('.file-upload').detach();
                $('#delete_image').val('');
                $('#general_image_edit').val('');
                $('#general_image_add').val('');
                $('#alert_messages_add').empty();
                $('#alert_messages_edit').empty();
                //$(this).find('form').trigger('reset'); // Очищаем формы
            });

            // Загрузка изображений в модальное окно "Редактировать"
            function getImageToEdit(logo_general_edit, logo_edit, modal_id) {
                // Добавляем данные
                for (x = 0; x < logo_edit[modal_id].length; x++) {
                    var image = logo_edit[modal_id][x];

                    $('<span class="file-upload" id="image_edit_' + x + '"/>').html('<div class="holder"><img src="/uploads/images/<?php echo $dir ?>/resize_0/' + image + '" class="thumbnail" id="general_' + x + '" /><div class="block"><button class="btn btn-primary btn-xs" type="button" name="delete_image_' + x + '" onclick="deleteImageEdit(\'' + image + '\', \'' + x + '\')"><span class="glyphicon glyphicon-trash"></span></button> <button class="btn btn-primary btn-xs" type="button" name="image_general_edit' + x + '" onclick="imageGeneralEdit(\'' + image + '\', \'' + x + '\')"><span class="glyphicon glyphicon-star"></span></button></div></div>').appendTo('#logo-edit'); // Вставляем лого
                    // Если это главное изображение, то выделяем его
                    if (logo_general_edit[modal_id] === image) {
                        $('#general_' + x).addClass('img-active');
                    }
                }
            }

            // Выборочное удаление изображений в модальном окне "Редактировать"
            function deleteImageEdit(image, num) {
                // Удаляем изображение
                $('#image_edit_' + num).detach();
                // Меняем значение в hidden input
                $('#delete_image').val($('#delete_image').val() + image + ',');
            }

            // Выборочное удаление новых не сохранненных изображений в модальном окне "Добавить"
            function deleteImageAddNew(image, num) {
                jQuery.post('<?php echo $url ?>', // отправка данных POST
                        {delete_image: image,
                            delete_new_image: 'ok'},
                        AjaxSuccess);
                function AjaxSuccess(data) {
                    // Удаляем изображение
                    $('#image_add_new_' + num).detach();
                }
            }

            // Выборочное удаление новых не сохранненных изображений в модальном окне "Редактировать"
            function deleteImageEditNew(image, num) {
                jQuery.post('<?php echo $url ?>', // отправка данных POST
                        {delete_image: image,
                            delete_new_image: 'ok'},
                        AjaxSuccess);
                function AjaxSuccess(data) {
                    // Удаляем изображение
                    $('#image_edit_new_' + num).detach();
                }
            }

            // Главное изображение в модальном окне "Редактировать"
            function imageGeneralEdit(image, num) {
                $('img').removeClass('img-active');
                $('#general_' + num).addClass('img-active');
                // Меняем значение в hidden input
                $('#general_image_edit').val(image);
            }

            // Главное изображение в модальном окне "Добавить"
            function imageGeneralAddNew(image, num) {
                $('img').removeClass('img-active');
                $('#general_' + num).addClass('img-active');
                // Меняем значение в hidden input
                $('#general_image_add').val(image);
            }

            // Главное изображение для нового не сохраненного изображения в модальном окне "Редактировать"
            function imageGeneralEditNew(image, num) {
                $('img').removeClass('img-active');
                $('#general_edit_' + num).addClass('img-active');
                // Меняем значение в hidden input
                $('#general_image_edit_new').val(image);
            }

        </script>

        <?php
    }

}

?>
