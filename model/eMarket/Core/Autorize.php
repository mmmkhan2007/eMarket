<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Core;

use \eMarket\Core\{
    Cart,
    Pdo,
    Settings,
    Valid
};

/**
 * Class for user authorization
 *
 * @package Autorize
 * @author eMarket
 * 
 */
class Autorize {

    public static $customer;

    /**
     * Sessions init
     *
     */
    public static function init() {

        if (Settings::path() == 'admin' && Valid::inGET('route') != 'login') {
            self::sessionAdmin();
        }

        if (Settings::path() == 'catalog') {
            self::sessionCatalog();
            Cart::init();
        }
    }

    /**
     * Session authorization for Admin Panel
     *
     * @return string TRUE
     */
    public static function sessionAdmin() {

        if (Settings::path() == 'admin' && Settings::titleDir() != 'login') {

            session_start();

            if (isset($_SESSION['session_start']) && (time() - $_SESSION['session_start']) / 60 > Settings::sessionExprTime()) {
                unset($_SESSION['login']);
                unset($_SESSION['pass']);
                unset($_SESSION['session_start']);
                $_SESSION['session_page'] = Valid::inSERVER('REQUEST_URI');
                header('Location: ?route=login');
                exit;
            }
            $_SESSION['session_start'] = time();

            if (!isset($_SESSION['login'])) {
                unset($_SESSION['login']);
                unset($_SESSION['pass']);
                $_SESSION['session_page'] = Valid::inSERVER('REQUEST_URI');
                header('Location: ?route=login');
                exit;
            } elseif (isset($_SESSION['login']) && isset($_SESSION['pass'])) {
                $_SESSION['DEFAULT_LANGUAGE'] = Pdo::selectPrepare("SELECT language FROM " . TABLE_ADMINISTRATORS . " WHERE login=? AND password=?", [
                            $_SESSION['login'], $_SESSION['pass']
                ]);
                return TRUE;
            } else {
                $_SESSION['DEFAULT_LANGUAGE'] = Settings::basicSettings('primary_language');
                return TRUE;
            }
        }
    }

    /**
     * Session authorization for Catalog
     *
     */
    public static function sessionCatalog() {

        if (Settings::path() == 'catalog') {

            session_start();
            if (isset($_SESSION['email_customer'])) {
                $customer_data = Pdo::getColAssoc("SELECT * FROM " . TABLE_CUSTOMERS . " WHERE email=?", [$_SESSION['email_customer']])[0];
            } else {
                $customer_data['status'] = 0;
            }

            if (isset($_SESSION['customer_session_start']) && (time() - $_SESSION['customer_session_start']) / 60 > Settings::sessionExprTime() OR $customer_data['status'] == 0) {
                unset($_SESSION['password_customer']);
                unset($_SESSION['email_customer']);
                unset($_SESSION['customer_session_start']);
                return FALSE;
            }
            $_SESSION['customer_session_start'] = time();

            if (!isset($_SESSION['email_customer'])) {
                self::$customer = FALSE;
            } else {
                self::$customer = $customer_data;
            }
        }
    }

    /**
     * Password hashing
     *
     * @param string Password
     * @return string $password Hash
     */
    public static function passwordHash($password) {

        if (HASH_METHOD == 'PASSWORD_DEFAULT') {
            $options = ['cost' => 10];
            $METHOD = PASSWORD_DEFAULT;
        }
        if (HASH_METHOD == 'PASSWORD_BCRYPT') {
            $options = ['cost' => 10];
            $METHOD = PASSWORD_BCRYPT;
        }
        if (HASH_METHOD == 'PASSWORD_ARGON2I') {
            $options = ['time_cost' => 2];
            $METHOD = PASSWORD_ARGON2I;
        }
        $password_hash = password_hash($password, $METHOD, $options);
        return $password_hash;
    }

}
