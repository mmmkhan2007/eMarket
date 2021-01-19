<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Core;

/**
 * Класс для шаблонизатора
 *
 * @package View
 * @author eMarket
 * 
 */
class View {

    /**
     * Роутинг данных из View
     *
     * @return string $str (роутинг на view)
     */
    public static function routing() {

        $str = str_replace('controller', 'view/' . \eMarket\Core\Settings::template(), getenv('SCRIPT_FILENAME'));

        return $str;
    }

    /**
     * Роутинг данных из View для административной панели
     *
     * @return string $str (роутинг на view)
     */
    public static function routingAdmin() {

        if (\eMarket\Core\Valid::inGET('route_file') != '') {
            $page = \eMarket\Core\Valid::inGET('route_file') . '.php';
        }

        if (!\eMarket\Core\Valid::inGET('route_file') OR \eMarket\Core\Valid::inGET('route_file') == '') {
            $page = 'index.php';
        }

        if (\eMarket\Core\Valid::inGET('route') != '') {
            $str = str_replace('controller', 'view/' . \eMarket\Core\Settings::template(), getenv('DOCUMENT_ROOT') . '/controller/' . \eMarket\Core\Settings::path() . '/pages/' . \eMarket\Core\Valid::inGET('route') . '/' . $page);
        } else {
            $str = str_replace('controller', 'view/' . \eMarket\Core\Settings::template(), getenv('DOCUMENT_ROOT') . '/controller/' . \eMarket\Core\Settings::path() . '/pages/dashboard/index.php');
        }
        if (file_exists($str)) {
            return $str;
        } else {
            return false;
        }
    }

    /**
     * Роутинг данных из View для каталога
     *
     * @return string $str (роутинг на view)
     */
    public static function routingCatalog() {

        if (\eMarket\Core\Valid::inGET('route') != '') {
            $str = str_replace('controller', 'view/' . \eMarket\Core\Settings::template(), getenv('DOCUMENT_ROOT') . '/controller/' . \eMarket\Core\Settings::path() . '/pages/' . \eMarket\Core\Valid::inGET('route') . '/index.php');
        } else {
            $str = str_replace('controller', 'view/' . \eMarket\Core\Settings::template(), getenv('DOCUMENT_ROOT') . '/controller/' . \eMarket\Core\Settings::path() . '/pages/catalog/index.php');
        }
        if (file_exists($str)) {
            return $str;
        } else {
            return false;
        }
    }

    /**
     * Роутинг данных для модулей
     *
     * @param string $path (маркер пути controller/view)
     * @return string $str (роутинг для модулей)
     */
    public static function routingModules($path) {

        if (\eMarket\Core\Valid::inGET('module_path')) {
            return \eMarket\Core\Settings::modulesPath() . '/' . $path . '/' . \eMarket\Core\Settings::path() . '/' . \eMarket\Core\Valid::inGET('module_path');
        } else {
            return \eMarket\Core\Settings::modulesPath() . '/' . $path . '/' . \eMarket\Core\Settings::path();
        }
    }

    /**
     * Template Layers Positioning Controller
     * 
     * @param string $position (позиция)
     * @param string $count (маркер счетчика)
     * @return array|string (массив настроек позиций для конкретного пути)
     */
    public static function tlpc($position, $count = null) {

        $array_pos_value = \eMarket\Core\Pdo::getColRow("SELECT url, value FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND page=? AND template_name=? ORDER BY sort ASC", [\eMarket\Core\Settings::path(), \eMarket\Core\Settings::titleDir(), \eMarket\Core\Settings::template()]);
        if (count($array_pos_value) > 0) {
            $array_out = [];
            foreach ($array_pos_value as $val) {
                if ($val[1] == $position) {
                    $path_view = str_replace('controller', 'view/' . \eMarket\Core\Settings::template(), $val[0]);
                    $array_out[] = $path_view;
                }
            }
            if ($count == 'count') {
                return count($array_out);
            }
            return $array_out;
        } else {
            $array_pos = \eMarket\Core\Pdo::getColRow("SELECT url, page FROM " . TABLE_TEMPLATE_CONSTRUCTOR . " WHERE group_id=? AND value=? AND template_name=? ORDER BY sort ASC", [\eMarket\Core\Settings::path(), $position, \eMarket\Core\Settings::template()]);
            $array_out = [];
            foreach ($array_pos as $val) {
                if ($val[1] == 'all') {
                    $path_view = str_replace('controller', 'view/' . \eMarket\Core\Settings::template(), $val[0]);
                    $array_out[] = $path_view;
                }
            }
            if ($count == 'count') {
                return count($array_out);
            }
            return $array_out;
        }
    }

}

?>