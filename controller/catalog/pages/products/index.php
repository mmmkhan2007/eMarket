<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

$products = \eMarket\Products::productData(\eMarket\Valid::inGET('id'));

if ($products['manufacturer'] != NULL) {
    $manufacturer = \eMarket\Pdo::getCellFalse("SELECT name FROM " . TABLE_MANUFACTURERS . " WHERE language=? AND id=?", [lang('#lang_all')[0], $products['manufacturer']]);
} else {
    $manufacturer = NULL;
}

if ($products['vendor_code'] != NULL) {
    $vendor_code = \eMarket\Pdo::getCellFalse("SELECT name FROM " . TABLE_VENDOR_CODES . " WHERE language=? AND id=?", [lang('#lang_all')[0], $products['vendor_code']]);
} else {
    $vendor_code = NULL;
}

if ($vendor_code != NULL && $vendor_code != FALSE) {
    $vendor_code_value = $products['vendor_code_value'];
}

if ($products['weight'] != NULL) {
    $weight = \eMarket\Pdo::getCellFalse("SELECT code FROM " . TABLE_WEIGHT . " WHERE language=? AND id=?", [lang('#lang_all')[0], $products['weight']]);
} else {
    $weight = NULL;
}

if ($weight != NULL && $weight != FALSE) {
    $weight_value = $products['weight_value'];
}

if ($products['date_available'] != NULL && $products['date_available'] != FALSE && strtotime($products['date_available']) > strtotime(date('Y-m-d'))) {
    $date_available_marker = 'false';
    $date_available_text = lang('product_in_stock_from') . ' ' . \eMarket\Set::dateLocale($products['date_available']);
} else {
    $date_available_marker = 'true';
    $date_available_text = lang('product_in_stock');
}



$images = \eMarket\Func::deleteValInArray(explode(',', $products['logo'], -1), [$products['logo_general']]);
$product_category = \eMarket\Products::productCategories($products['parent_id']);
$categories_name = \eMarket\Pdo::getCell("SELECT name FROM " . TABLE_CATEGORIES . " WHERE language=? AND id=?", [lang('#lang_all')[0], \eMarket\Valid::inGET('category_id')]);
$category_parent_id = \eMarket\Pdo::getCell("SELECT parent_id FROM " . TABLE_CATEGORIES . " WHERE language=? AND id=?", [lang('#lang_all')[0], \eMarket\Valid::inGET('category_id')]);

//Создаем маркер для подгрузки JS/JS.PHP в конце перед </body>
$JS_END = __DIR__;
?>