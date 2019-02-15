<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

/* >-->-->-->  CONNECT PAGE START  <--<--<--< */
require_once(getenv('DOCUMENT_ROOT') . '/model/start.php');
/* ------------------------------------------ */

$layout_pages = scandir(ROOT . '/controller/catalog/pages/');
$name_template = scandir(ROOT . '/view/');

$layout_header = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'header', 'default']);
$layout_content = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'content', 'default']);
$layout_boxes_left = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'boxes-left', 'default']);
$layout_boxes_right = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'boxes-right', 'default']);
$layout_footer = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'footer', 'default']);

$layout_header_basket = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'header-basket', 'default']);
$layout_content_basket = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'content-basket', 'default']);
$layout_boxes_basket = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'boxes-basket', 'default']);
$layout_footer_basket = $PDO->getCol("SELECT url FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", ['catalog', 'footer-basket', 'default']);

// ОБРАБАТЫВАЕМ HEADER
if ($VALID->inPOST('layout_header') OR $VALID->inPOST('layout_header_basket')) {
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'header', 'default']);
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'header-basket', 'default']);

    for ($x = 0; $x < count($VALID->inPOST('layout_header')); $x++) {
        if ($VALID->inPOST('layout_header')[$x] == 'header') {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/' . $VALID->inPOST('layout_header')[$x] . '.php', 'catalog', 'header', 'all', $x, 'default']);
        } else {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_header')[$x] . '.php', 'catalog', 'header', 'all', $x, 'default']);
        }
    }

    for ($x = 0; $x < count($VALID->inPOST('layout_header_basket')); $x++) {
        if ($VALID->inPOST('layout_header_basket')[$x] == 'header') {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/' . $VALID->inPOST('layout_header_basket')[$x] . '.php', 'catalog', 'header-basket', 'all', $x, 'default']);
        } else {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_header_basket')[$x] . '.php', 'catalog', 'header-basket', 'all', $x, 'default']);
        }
    }
}
// ОБРАБАТЫВАЕМ CONTENT
if ($VALID->inPOST('layout_content') OR $VALID->inPOST('layout_content_basket')) {
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'content', 'default']);
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'content-basket', 'default']);

    for ($x = 0; $x < count($VALID->inPOST('layout_content')); $x++) {
        $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_content')[$x] . '.php', 'catalog', 'content', 'all', $x, 'default']);
    }

    for ($x = 0; $x < count($VALID->inPOST('layout_content_basket')); $x++) {
        $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_content_basket')[$x] . '.php', 'catalog', 'content-basket', 'all', $x, 'default']);
    }
}

// ОБРАБАТЫВАЕМ BOXES
if ($VALID->inPOST('layout_boxes_left') OR $VALID->inPOST('layout_boxes_right') OR $VALID->inPOST('layout_boxes_basket')) {
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'boxes-left', 'default']);
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'boxes-right', 'default']);
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'boxes-basket', 'default']);

    for ($x = 0; $x < count($VALID->inPOST('layout_boxes_left')); $x++) {
        $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_boxes_left')[$x] . '.php', 'catalog', 'boxes-left', 'all', $x, 'default']);
    }
    
    for ($x = 0; $x < count($VALID->inPOST('layout_boxes_right')); $x++) {
        $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_boxes_right')[$x] . '.php', 'catalog', 'boxes-right', 'all', $x, 'default']);
    }

    for ($x = 0; $x < count($VALID->inPOST('layout_boxes_basket')); $x++) {
        $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_boxes_basket')[$x] . '.php', 'catalog', 'boxes-basket', 'all', $x, 'default']);
    }
}

// ОБРАБАТЫВАЕМ FOOTER
if ($VALID->inPOST('layout_footer') OR $VALID->inPOST('layout_footer_basket')) {
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'footer', 'default']);
    $PDO->inPrepare("DELETE FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=?", ['catalog', 'footer-basket', 'default']);

    for ($x = 0; $x < count($VALID->inPOST('layout_footer')); $x++) {
        if ($VALID->inPOST('layout_footer')[$x] == 'footer') {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/' . $VALID->inPOST('layout_footer')[$x] . '.php', 'catalog', 'footer', 'all', $x, 'default']);
        } else {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_footer')[$x] . '.php', 'catalog', 'footer', 'all', $x, 'default']);
        }
    }

    for ($x = 0; $x < count($VALID->inPOST('layout_footer_basket')); $x++) {
        if ($VALID->inPOST('layout_footer_basket')[$x] == 'footer') {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/' . $VALID->inPOST('layout_footer_basket')[$x] . '.php', 'catalog', 'footer-basket', 'all', $x, 'default']);
        } else {
            $PDO->inPrepare("INSERT INTO " . TABLE_TEMPLATE_CONSTRUCTOR . " SET url=?, group_id=?, value=?, page=?, sort=?, template_name=?", ['/controller/catalog/layouts/' . $VALID->inPOST('layout_footer_basket')[$x] . '.php', 'catalog', 'footer-basket', 'all', $x, 'default']);
        }
    }
}

//$DEBUG->trace($layout_pages);
//
//Создаем маркер для подгрузки JS/JS.PHP в конце перед </body>
$JS_END = __DIR__;

/* ->-->-->-->  CONNECT PAGE END  <--<--<--<- */
require_once(ROOT . '/model/end.php');
/* ------------------------------------------ */

?>