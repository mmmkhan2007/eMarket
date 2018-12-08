<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |    
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

/* >-->-->-->  CONNECT PAGE START  <--<--<--< */
require_once(getenv('DOCUMENT_ROOT') . '/model/start.php');
/* ------------------------------------------ */
// 
// Получаем zones_id
if ($VALID->inPOST('zone_id')) {
    $zones_id = (int) $VALID->inPOST('zone_id');
}

if ($VALID->inGET('zone_id')) {
    $zones_id = (int) $VALID->inGET('zone_id');
}

// Если нажали на кнопку Добавить
if ($VALID->inPOST('add')) {

    // Очищаем страны и регионы из этой зоны
    $PDO->inPrepare("DELETE FROM " . TABLE_ZONES_VALUE . " WHERE zones_id=?", [$zones_id]);

    if (empty($VALID->inPOST('multiselect')) == FALSE) {
        // Создаем многомерный массив из одномерного, разбитого на части разделителем "-"
        $multiselect = $FUNC->array_explode($VALID->inPOST('multiselect'), '-');
        // Добавляем выбранные в мультиселекте данные
        for ($x = 0; $x < count($multiselect); $x++) {
            $PDO->inPrepare("INSERT INTO " . TABLE_ZONES_VALUE . " SET country_id=?, regions_id=?, zones_id=?", [$multiselect[$x][0], $multiselect[$x][1], $zones_id]);
        }
    }
}

// Собираем данные для массива Стран в мультиселекте
$countries_multiselect_temp = $PDO->getColRow("SELECT name, id  FROM " . TABLE_COUNTRIES . " WHERE language=?", [lang('#lang_all')[0]]);
// Собираем одномерный массив id=>Страна
$countries_multiselect = array_column($countries_multiselect_temp, 0, 1);
// Сортируем Страны по возрастанию
asort($countries_multiselect);
// Собираем данные для массива Регионов в мультиселекте
$regions_multiselect = $PDO->getColRow("SELECT id, country_id, name, region_code  FROM " . TABLE_REGIONS . " WHERE language=?", [lang('#lang_all')[0]]);
// Собираем название стран и регионов для вывода в View
$name_country = $PDO->getColRow("SELECT id, name FROM " . TABLE_COUNTRIES . " WHERE language=? ORDER BY id DESC", [lang('#lang_all')[0]]);
$name_regions = $PDO->getColRow("SELECT country_id, name FROM " . TABLE_REGIONS . " WHERE language=? ORDER BY id DESC", [lang('#lang_all')[0]]);
// Собираем данные по сопоставлению Страна->Регионы для конкретной зоны
$regions = $PDO->getColRow("SELECT country_id, regions_id FROM " . TABLE_ZONES_VALUE . " WHERE zones_id=?", [$zones_id]);

//КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ
// Собираем данные для вывода списка стран
$lines_temp = $PDO->getColRow("SELECT country_id FROM " . TABLE_ZONES_VALUE . " WHERE zones_id=?", [$zones_id]);
$lines = array_values(array_unique($lines_temp, SORT_REGULAR)); // Выбираем по 1 экземпляру стран и сбрасываем ключи массива
$lines_on_page = $SET->linesOnPage();
$navigate = $NAVIGATION->getLink(count($lines), $lines_on_page);
$start = $navigate[0];
$finish = $navigate[1];

// Формирование списка для всплывающих подсказок
$text_arr = array();
for ($y = $start; $y < $finish; $y++) {
    $text = '| ';
    for ($x = 0; $x < count($regions); $x++) {
        if (isset($regions[$x][0]) == TRUE && isset($lines[$y][0]) == TRUE && $regions[$x][0] == $lines[$y][0]) { // если регион есть
            $text .= $FUNC->filter_array_to_key($name_regions, 0, $regions[$x][0], 1)[$regions[$x][1]] . ' | '; // то, добавляем название региона
        }
    }
    array_push($text_arr, $text);
}
//Создаем маркер для подгрузки JS/JS.PHP в конце перед </body>
$JS_END = __DIR__;

/* ->-->-->-->  CONNECT PAGE END  <--<--<--<- */
require_once(ROOT . '/model/end.php');
/* ------------------------------------------ */

?>