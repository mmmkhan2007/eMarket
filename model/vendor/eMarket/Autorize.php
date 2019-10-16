<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket;

/**
 * Класс для авторизации пользователей
 *
 * @package Autorize
 * @author eMarket
 * 
 */
class Autorize {

    /**
     * Авторизация сессиями для Административной панели
     *
     * @return string $str ($TOKEN)
     */
    public static function sessionAdmin() {

        // ЕСЛИ В АДМИНИСТРАТИВНОЙ ПАНЕЛИ
        if (\eMarket\Set::path() == 'admin' && \eMarket\Set::titleDir() != 'login') {

            session_start();

            if (isset($_SESSION['session_start']) && (time() - $_SESSION['session_start']) / 60 > \eMarket\Set::sessionExprTime()) { // Если истекло время сеанса
                unset($_SESSION['login']);    //удаляем текущую сессию
                unset($_SESSION['pass']);
                unset($_SESSION['session_start']);
                header('Location: ?route=login'); // переадресация на LOGIN
            }
            $_SESSION['session_start'] = time();

            if (!isset($_SESSION['login'])) { // Если нет пользователя
                unset($_SESSION['login']);    //удаляем текущую сессию
                unset($_SESSION['pass']);
                header('Location: ?route=login'); // переадресация на LOGIN
            } else {
                $TOKEN = $_SESSION['pass']; // создаем токен для ajax и пр.
                //Язык авторизованного администратора
                $_SESSION['DEFAULT_LANGUAGE'] = \eMarket\Pdo::selectPrepare("SELECT language FROM " . TABLE_ADMINISTRATORS . " WHERE login=? AND password=?", [$_SESSION['login'], $_SESSION['pass']]);

                return $TOKEN;
            }
        }
    }

    /**
     * Авторизация сессиями для Каталога
     *
     * @return string $str ($TOKEN)
     */
    public static function sessionCatalog() {

        if (\eMarket\Set::path() == 'catalog') {

            session_start();
            if (isset($_SESSION['customer_session_start']) && (time() - $_SESSION['customer_session_start']) / 60 > \eMarket\Set::sessionExprTime()) { // Если истекло время сеанса
                unset($_SESSION['password_customer']);
                unset($_SESSION['email_customer']);
                unset($_SESSION['customer_session_start']);
                return FALSE;
            }
            $_SESSION['customer_session_start'] = time();

            if (!isset($_SESSION['email_customer'])) { // Если нет пользователя
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    /**
     * Хэширование пароля
     *
     * @param string $password (входящий пароль)
     * @return string $password_hash (хэшированный пароль)
     */
    public static function passwordHash($password) {

        if (HASH_METHOD == 'PASSWORD_DEFAULT') {
            $options = ['cost' => 10]; // Уровень сложности
            $METHOD = PASSWORD_DEFAULT;
        }
        if (HASH_METHOD == 'PASSWORD_BCRYPT') {
            $options = ['cost' => 10]; // Уровень сложности
            $METHOD = PASSWORD_BCRYPT;
        }
        if (HASH_METHOD == 'PASSWORD_ARGON2I') {
            $options = ['time_cost' => 2]; // Максимум в сек. на вычисление хэша
            $METHOD = PASSWORD_ARGON2I;
        }
        $password_hash = password_hash($password, $METHOD, $options); // Хэшируем пароль
        return $password_hash;
    }

}

?>