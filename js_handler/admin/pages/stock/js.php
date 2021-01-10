<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
$resize_max = json_encode(\eMarket\Files::imgResizeMax($resize_param));
$resize_max_prod = json_encode(\eMarket\Files::imgResizeMax($resize_param_product));
$lang_js = json_encode([
    'image_resize_error' => lang('image_resize_error'),
    'download_complete' => lang('download_complete')
        ]);

$lang_attributes = json_encode([
    lang('confirm-yes'),
    lang('confirm-no'),
    lang('button_delete'),
    lang('button_edit'),
    lang('#lang_all')[0]
        ]);
?>
<!--Подгружаем jQuery File Upload -->
<script src = "/ext/jquery_file_upload/js/vendor/jquery.ui.widget.js"></script>
<script src="/ext/jquery_file_upload/js/jquery.iframe-transport.js"></script>
<script src="/ext/jquery_file_upload/js/jquery.fileupload.js"></script>
<script type="text/javascript" src="/model/js/classes/images/fileupload.js"></script>
<script type="text/javascript" src="/model/js/classes/images/fileupload_product.js"></script>

<script type="text/javascript">
    var resize_max = JSON.parse('<?php echo $resize_max ?>');
    var resize_max_prod = JSON.parse('<?php echo $resize_max_prod ?>');
    var lang = JSON.parse('<?php echo $lang_js ?>');
    new Fileupload(resize_max, lang);
    new FileuploadProduct(resize_max_prod, lang);
</script>

<script type="text/javascript" src="/model/js/classes/ajax/ajax.js"></script>
<script type="text/javascript">
    new Ajax();
</script>

<!--Подгружаем Атрибуты -->
<?php require_once ('attributes.php') ?>

<!--Подгружаем Summernote -->
<?php require_once ('summernote.php') ?>

<!--Подгружаем Контекстное меню -->
<?php require_once ('context.php') ?>

<!--Подгружаем Действия мышкой -->
<?php require_once ('mouse.php') ?>

<!--Подгружаем Bootstrap Datepicker -->
<?php require_once ('datepicker.php') ?>