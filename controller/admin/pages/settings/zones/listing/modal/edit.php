<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
// собираем данные для отображения в Редактировании
if (isset($lines[$k][0]) == TRUE) {
    $name_edit = array();
    for ($xl = 0; $xl < count($LANG_ALL); $xl++) {
        array_push($name_edit, $PDO->selectPrepare("SELECT name FROM " . TABLE_ZONES . " WHERE id=? and language=?", [$lines[$k][0], $LANG_ALL[$xl]]));
    }
    $value_edit = $PDO->selectPrepare("SELECT note FROM " . TABLE_ZONES . " WHERE id=?", [$lines[$k][0]]);

}

?>
