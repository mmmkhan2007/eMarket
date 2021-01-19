<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Core;

/**
 * Debug class
 *
 * @package Debug
 * @author eMarket
 * 
 */
class Debug {

    public static $TIME_START;

    /**
     * Array displaying when debugging
     *
     * @param array Input array
     */
    public static function trace($var) {
        static $int = 0;
        echo '<pre><b>' . $int . '</b> ';
        print_r($var);
        echo '</pre>';
        $int++;
    }

    /**
     * Displaying debug information
     *
     * @param string Start time
     */
    public static function info() {

        $val = \eMarket\Core\Pdo::getCell("SELECT debug FROM " . TABLE_BASIC_SETTINGS . "", []);
        if ($val == 1) {
            $tend = microtime(1);

            $totaltime = round(($tend - self::$TIME_START), 2);

            echo lang('debug_page_generation_time') . " " . $totaltime . " " . lang('debug_sec') . "<br>";
            echo lang('debug_db_queries') . " " . \eMarket\Core\Pdo::$QUERY_COUNT . " " . lang('debug_pcs') . "<br><br>";
        }
    }

}