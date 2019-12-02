<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

$resize_param = [];
array_push($resize_param, ['125', '94']); // ширина, высота
//
$resize_param_product = [];
array_push($resize_param_product, ['125', '94']); // ширина, высота
array_push($resize_param_product, ['200', '150']);
array_push($resize_param_product, ['325', '244']);
array_push($resize_param_product, ['525', '394']);
array_push($resize_param_product, ['850', '638']);

// Создаем массив используемых таблиц в EAC
$TABLES = [
    TABLE_CATEGORIES,
    TABLE_PRODUCTS,
    TABLE_TAXES,
    TABLE_UNITS,
    TABLE_MANUFACTURERS,
    TABLE_VENDOR_CODES,
    TABLE_WEIGHT,
    TABLE_LENGTH,
    TABLE_CURRENCIES
];
// Загружаем движок EAC
$EAC_ENGINE = \eMarket\Eac::init($TABLES, $TOKEN, $resize_param, $resize_param_product);
$idsx_real_parent_id = $EAC_ENGINE[0];
$parent_id = $EAC_ENGINE[1];

// Формируем распродажу для выпадающего списка
$installed_active = \eMarket\Pdo::getCell("SELECT id FROM " . TABLE_MODULES . " WHERE name=? AND type=? AND active=?", ['sale', 'discount', 1]);
$sales = '';
$sale_default = '0';
if ($installed_active != '') {
    $sales_all = \eMarket\Pdo::getColAssoc("SELECT id, name FROM " . DB_PREFIX . 'modules_discount_sale' . " WHERE language=?", [lang('#lang_all')[0]]);
}
$sales_flag = 0;

if ($installed_active != '' && isset($sales_all) && count($sales_all) > 0) {
    $sales_flag = 1;
    foreach ($sales_all as $val) {
        $sales .= $val['id'] . ': ' . "'" . $val['name'] . "', ";
    }
    $sale_default = \eMarket\Pdo::getCell("SELECT id FROM " . DB_PREFIX . 'modules_discount_sale' . " WHERE language=? AND default_set=?", [lang('#lang_all')[0], 1]);
}
// Формируем массив Валюта для выпадающего списка
$currencies_all = \eMarket\Pdo::getColRow("SELECT name, default_value, id FROM " . TABLE_CURRENCIES . " WHERE language=?", [lang('#lang_all')[0]]);

// Формируем массив Налог для выпадающего списка
$taxes_all = \eMarket\Pdo::getColRow("SELECT name, id FROM " . TABLE_TAXES . " WHERE language=?", [lang('#lang_all')[0]]);

// Формируем массив Единица измерения для выпадающего списка
$units_all = \eMarket\Pdo::getColRow("SELECT name, default_unit, id FROM " . TABLE_UNITS . " WHERE language=?", [lang('#lang_all')[0]]);

// Формируем массив Размер измерения для выпадающего списка
$length_all = \eMarket\Pdo::getColRow("SELECT name, default_length, id FROM " . TABLE_LENGTH . " WHERE language=?", [lang('#lang_all')[0]]);

// Формируем массив Вес измерения для выпадающего списка
$weight_all = \eMarket\Pdo::getColRow("SELECT name, default_weight, id FROM " . TABLE_WEIGHT . " WHERE language=?", [lang('#lang_all')[0]]);

// Формируем массив Вес измерения для выпадающего списка
$vendor_codes_all = \eMarket\Pdo::getColRow("SELECT name, default_vendor_code, id FROM " . TABLE_VENDOR_CODES . " WHERE language=?", [lang('#lang_all')[0]]);

// Формируем массив Производитель измерения для выпадающего списка
$manufacturers_all = \eMarket\Pdo::getColRow("SELECT name, id FROM " . TABLE_MANUFACTURERS . " WHERE language=?", [lang('#lang_all')[0]]);


// КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ
// 
// Устанавливаем родительскую категорию при навигации назад-вперед
if (\eMarket\Valid::inGET('parent_id_temp')) {
    $parent_id = \eMarket\Valid::inGET('parent_id_temp');
}

$lines_on_page = \eMarket\Set::linesOnPage();
// получаем отсортированное по sort_category содержимое в виде массива для отображения на странице и сортируем в обратном порядке
$lines_cat = \eMarket\Pdo::getColRow("SELECT id, name, parent_id, status FROM " . TABLE_CATEGORIES . " WHERE parent_id=? AND language=? ORDER BY sort_category DESC", [$parent_id, lang('#lang_all')[0]]);
$count_lines_cat = count($lines_cat);  //считаем количество строк

$lines_prod = \eMarket\Pdo::getColAssoc("SELECT * FROM " . TABLE_PRODUCTS . " WHERE parent_id=? AND language=? ORDER BY id DESC", [$parent_id, lang('#lang_all')[0]]);

$lines_prod2 = \eMarket\Pdo::getColRow("SELECT id, name, parent_id, status, discount FROM " . TABLE_PRODUCTS . " WHERE parent_id=? AND language=? ORDER BY id DESC", [$parent_id, lang('#lang_all')[0]]);
$count_lines_prod = count($lines_prod);  //считаем количество строк

$arr_merge = \eMarket\Func::arrayMergeOriginKey('cat', 'prod', $lines_cat, $lines_prod2);
$count_lines_merge = $count_lines_cat + $count_lines_prod; // Считаем общее количество строк в категории

$navigate = \eMarket\Navigation::getLink($count_lines_merge, $lines_on_page, 1);
$start = $navigate[0];
$finish = $navigate[1];

// Параметры для JS
if (!isset($idsx_real_parent_id)) {
    $idsx_real_parent_id = '';
}

if (isset($_SESSION['buffer'])) {
    $ses_verify = count($_SESSION['buffer']);
} else {
    $ses_verify = '0';
}
// КОНЕЦ-> КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ
//Создаем маркер для подгрузки JS/JS.PHP в конце перед </body>
$JS_END = __DIR__;
?>
