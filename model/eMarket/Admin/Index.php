<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Admin;

use \eMarket\Core\{
    Valid
};

/**
 * Index
 *
 * @package Admin
 * @author eMarket
 * 
 */
class Index {

    /**
     * Route
     *
     * @return string url
     */
    public function route() {
        if (Valid::inGET('route') != '') {
            return ROOT . '/controller/admin/pages/' . Valid::inGET('route') . '/index.php';
        } else {
            return ROOT . '/controller/admin/pages/dashboard/index.php';
        }
    }

}
