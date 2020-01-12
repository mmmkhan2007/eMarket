<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket;

/**
 * Класс для работы с модулями
 *
 * @package Modules
 * @author eMarket
 * 
 */
final class Modules {

    /**
     * Инсталляция модуля
     *
     * @param array $module (входящие данные)
     */
    public static function install($module) {

        \eMarket\Pdo::inPrepare("INSERT INTO " . TABLE_MODULES . " SET name=?, type=?, active=?", [$module[1], $module[0], 1]);
        //Загружаем БД из файла
        \eMarket\Pdo::dbInstall(ROOT . '/modules/' . $module[0] . '/' . $module[1] . '/install/');
    }
    
    /**
     * Удаление модуля
     *
     * @param array $module (входящие данные)
     */
    public static function uninstall($module) {
        // Удаляем
        \eMarket\Pdo::inPrepare("DELETE FROM " . TABLE_MODULES . " WHERE name=? AND type=?", [$module[1], $module[0]]);
        \eMarket\Pdo::inPrepare("DROP TABLE " . DB_PREFIX . 'modules_' . $module[0] . '_' . $module[1], []);
    }    

}

?>