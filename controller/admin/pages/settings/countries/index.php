<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

/* >-->-->-->  CONNECT PAGE START  <--<--<--< */
require_once(getenv('DOCUMENT_ROOT') . '/model/start.php');
/* ------------------------------------------ */
// 
//Сохраняем сессию с URL текущей страницы
$_SESSION['country_page'] = $VALID->inSERVER('REQUEST_URI');

// Если нажали на кнопку Добавить
if ($VALID->inGET('add')) {

    // Получаем последний id и увеличиваем его на 1
    $id_max = $PDO->selectPrepare("SELECT id FROM " . TABLE_COUNTRIES . " WHERE language=? ORDER BY id DESC", [$lang_all[0]]);
    $id = intval($id_max) + 1;

    // добавляем запись для всех вкладок
    for ($xl = 0; $xl < count($lang_all); $xl++) {
        $PDO->inPrepare("INSERT INTO " . TABLE_COUNTRIES . " SET id=?, name=?, language=?, alpha_2=?, alpha_3=?, address_format=?", [$id, $VALID->inGET($TITLE_DIR . '_' . $lang_all[$xl]), $lang_all[$xl], $VALID->inGET('alpha_2'), $VALID->inGET('alpha_3'), $VALID->inGET('address_format')]);
    }
}

// Если нажали на кнопку Редактировать
if ($VALID->inGET('id_edit')) {

    for ($xl = 0; $xl < count($lang_all); $xl++) {
        // обновляем запись
        $PDO->inPrepare("UPDATE " . TABLE_COUNTRIES . " SET name=?, alpha_2=?, alpha_3=?, address_format=? WHERE id=? AND language=?", [$VALID->inGET('name_edit_' . $TITLE_DIR . '_' . $lang_all[$xl]), $VALID->inGET('alpha_2_edit'), $VALID->inGET('alpha_3_edit'), $VALID->inGET('address_format'), $VALID->inGET('id_edit'), $lang_all[$xl]]);
    }
}

// Если нажали на кнопку Удалить
if ($VALID->inPOST('delete')) {

    // Удаляем Страну и Регионы
    $PDO->inPrepare("DELETE FROM " . TABLE_COUNTRIES . " WHERE id=?", [$VALID->inPOST('delete')]);
    $PDO->inPrepare("DELETE FROM " . TABLE_REGIONS . " WHERE country_id=?", [$VALID->inPOST('delete')]);
}

//КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ
$lines = $PDO->getColRow("SELECT id, name, alpha_2, alpha_3 FROM " . TABLE_COUNTRIES . " WHERE language=? ORDER BY name", [$lang_all[0]]);
$navigate = $NAVIGATION->getLink(count($lines), $lines_on_page);
$start = $navigate[0];
$finish = $navigate[1];

/* ->-->-->-->  CONNECT PAGE END  <--<--<--<- */
require_once(ROOT . '/model/end.php');
/* ------------------------------------------ */

?>