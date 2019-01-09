<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
// 
//АВТОЗАГРУЗЧИК КЛАССОВ
require_once('vendor/autoload.php');
//
//СОЗДАЕМ ОБЪЕКТЫ CORE
$AUTORIZE = new eMarket\Core\Autorize;
$EAC = new eMarket\Core\Eac;
$LANG = new eMarket\Core\Lang;
$NAVIGATION = new eMarket\Core\Navigation;
$PDO = new eMarket\Core\Pdo;
$SET = new eMarket\Core\Set;
$TREE = new eMarket\Core\Tree;
$VALID = new eMarket\Core\Valid;
$VIEW = new eMarket\Core\View;

//СОЗДАЕМ ОБЪЕКТЫ OTHER
$AJAX = new eMarket\Other\Ajax;
$DEBUG = new eMarket\Other\Debug;
$FILES = new eMarket\Other\Files;
$FUNC = new eMarket\Other\Func;
$MESSAGES = new eMarket\Other\Messages;
//
//АВТОЗАГРУЗЧИК ФУНКЦИЙ
//Получаем список путей к файлам функций
foreach ($TREE->filesTree(getenv('DOCUMENT_ROOT') . '/model/functions/') as $path) {
    require_once($path);
}

?>