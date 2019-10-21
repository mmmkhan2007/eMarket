<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

$DATABASE = \eMarket\Set::moduleDatabase();

if (\eMarket\Valid::inPOST('add')) {
    // Формат даты после Datepicker
    if (\eMarket\Valid::inPOST('start_date')) {
        $start_date = date('Y-m-d', strtotime(\eMarket\Valid::inPOST('start_date')));
    } else {
        $start_date = NULL;
    }
    // Формат даты после Datepicker
    if (\eMarket\Valid::inPOST('end_date')) {
        $end_date = date('Y-m-d', strtotime(\eMarket\Valid::inPOST('end_date')));
    } else {
        $end_date = NULL;
    }

    // Если есть установка по-умолчанию
    if (\eMarket\Valid::inPOST('default_module')) {
        $default_value = 1;
    } else {
        $default_value = 0;
    }

    // Получаем последний id и увеличиваем его на 1
    $id_max = \eMarket\Pdo::selectPrepare("SELECT id FROM " . $DATABASE . " WHERE language=? ORDER BY id DESC", [lang('#lang_all')[0]]);
    $id = intval($id_max) + 1;

    // добавляем запись для всех вкладок
    for ($x = 0; $x < $LANG_COUNT; $x++) {
        \eMarket\Pdo::inPrepare("INSERT INTO " . $DATABASE . " SET id=?, name=?, language=?, sale_value=?, date_start=?, date_end=?, default_set=?", [$id, \eMarket\Valid::inPOST('name_module_' . $x), lang('#lang_all')[$x], \eMarket\Valid::inPOST('sale_value'), $start_date, $end_date, $default_value]);
    }

    // Выводим сообщение об успехе
    $_SESSION['message'] = ['success', lang('action_completed_successfully')];
}

// Если нажали на кнопку Удалить
if (\eMarket\Valid::inPOST('delete')) {
    // Удаляем
    \eMarket\Pdo::inPrepare("DELETE FROM " . $DATABASE . " WHERE id=?", [\eMarket\Valid::inPOST('delete')]);
    // Выводим сообщение об успехе
    $_SESSION['message'] = ['success', lang('action_completed_successfully')];
}

//КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ
$lines = \eMarket\Pdo::getColRow("SELECT id, name, sale_value, date_start, date_end, default_set FROM " . $DATABASE . " WHERE language=? ORDER BY id DESC", [lang('#lang_all')[0]]);
$lines_on_page = \eMarket\Set::linesOnPage();
$navigate = \eMarket\Navigation::getLink(count($lines), $lines_on_page);
$start = $navigate[0];
$finish = $navigate[1];

//Создаем маркер для подгрузки JS/JS.PHP в конце перед </body>
$JS_MOD_END = __DIR__;
// Загружаем разметку модуля
require_once (__DIR__ . '../../view/admin.php');
?>