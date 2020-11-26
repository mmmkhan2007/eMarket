<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

// 
// Если нажали на кнопку Добавить
if (\eMarket\Valid::inPOST('add')) {

    // Если есть установка по-умолчанию
    if (\eMarket\Valid::inPOST('default_stikers')) {
        $default_stikers = 1;
    } else {
        $default_stikers = 0;
    }

    // Получаем последний id и увеличиваем его на 1
    $id_max = \eMarket\Pdo::selectPrepare("SELECT id FROM " . TABLE_STIKERS . " WHERE language=? ORDER BY id DESC", [lang('#lang_all')[0]]);
    $id = intval($id_max) + 1;

    // Оставляем один экземпляр значения по-умолчанию
    if ($id > 1 && $default_stikers != 0) {
        \eMarket\Pdo::inPrepare("UPDATE " . TABLE_STIKERS . " SET default_stikers=?", [0]);
    }

    // Получаем последний sort и увеличиваем его на 1
    $id_max_sort = \eMarket\Pdo::selectPrepare("SELECT sort FROM " . TABLE_STIKERS . " WHERE language=? ORDER BY sort DESC", [lang('#lang_all')[0]]);
    $id_sort = intval($id_max_sort) + 1;

    // добавляем запись для всех вкладок
    for ($x = 0; $x < $LANG_COUNT; $x++) {
        \eMarket\Pdo::inPrepare("INSERT INTO " . TABLE_STIKERS . " SET id=?, name=?, language=?, default_stikers=?, sort=?", [$id, \eMarket\Valid::inPOST('name_stikers_' . $x), lang('#lang_all')[$x], $default_stikers, $id_sort]);
    }

    // Выводим сообщение об успехе
    $_SESSION['message'] = ['success', lang('action_completed_successfully')];
}

// Если нажали на кнопку Редактировать
if (\eMarket\Valid::inPOST('edit')) {

    // Если есть установка по-умолчанию
    if (\eMarket\Valid::inPOST('default_stikers')) {
        $default_stikers = 1;
    } else {
        $default_stikers = 0;
    }
    // Оставляем один экземпляр значения по-умолчанию
    if ($default_stikers != 0) {
        \eMarket\Pdo::inPrepare("UPDATE " . TABLE_STIKERS . " SET default_stikers=?", [0]);
    }

    for ($x = 0; $x < $LANG_COUNT; $x++) {
        // обновляем запись
        \eMarket\Pdo::inPrepare("UPDATE " . TABLE_STIKERS . " SET name=?, default_stikers=? WHERE id=? AND language=?", [\eMarket\Valid::inPOST('name_stikers_' . $x), $default_stikers, \eMarket\Valid::inPOST('edit'), lang('#lang_all')[$x]]);
    }

    // Выводим сообщение об успехе
    $_SESSION['message'] = ['success', lang('action_completed_successfully')];
}

// Если нажали на кнопку Удалить
if (\eMarket\Valid::inPOST('delete')) {

    // Удаляем
    \eMarket\Pdo::inPrepare("DELETE FROM " . TABLE_STIKERS . " WHERE id=?", [\eMarket\Valid::inPOST('delete')]);
    // Выводим сообщение об успехе
    $_SESSION['message'] = ['success', lang('action_completed_successfully')];
}

// если сортируем мышкой
if (\eMarket\Valid::inPOST('ids')) {
    $sort_array_id_ajax = explode(',', \eMarket\Valid::inPOST('ids')); // Массив со списком id под сортировку
    // Если в массиве пустое значение, то собираем новый массив без этого значения со сбросом ключей
    $sort_array_id = \eMarket\Func::deleteEmptyInArray($sort_array_id_ajax);

    $sort_array_stikers = []; // Массив со списком sort под сортировку

    foreach ($sort_array_id as $val) {
        $sort_stikers = \eMarket\Pdo::selectPrepare("SELECT sort FROM " . TABLE_STIKERS . " WHERE id=? AND language=? ORDER BY id ASC", [$val, lang('#lang_all')[0]]);
        array_push($sort_array_stikers, $sort_stikers); // Добавляем данные в массив sort
        arsort($sort_array_stikers); // Сортируем массив со списком sort
    }
    // Создаем финальный массив из двух массивов
    $sort_array_final = array_combine($sort_array_id, $sort_array_stikers);

    foreach ($sort_array_id as $val) {

        \eMarket\Pdo::inPrepare("UPDATE " . TABLE_STIKERS . " SET sort=? WHERE id=?", [(int) $sort_array_final[$val], (int) $val]);
    }
}

//КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ
$lines = \eMarket\Pdo::getColRow("SELECT id, name, default_stikers, sort FROM " . TABLE_STIKERS . " WHERE language=? ORDER BY sort DESC", [lang('#lang_all')[0]]);
$lines_on_page = \eMarket\Set::linesOnPage();
$count_lines = count($lines);
$navigate = \eMarket\Navigation::getLink($count_lines, $lines_on_page);
$start = $navigate[0];
$finish = $navigate[1];

// Модальное окно
require_once('modal/index.php');

//Создаем маркер для подгрузки JS/JS.PHP в конце перед </body>
$JS_END = __DIR__;
?>