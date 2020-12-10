<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket;

/**
 * Класс для работы с БД через PDO
 *
 * @package Pdo
 * @author eMarket
 * 
 */
final class Pdo {

    // Счетчик запросов к БД
    public static $query_count = 0;

    /**
     * Функция для соединения с БД
     * @param string $a (маркер)
     * @return object $connect (объект БД)
     */
    public static function connect($a = null) {
        static $connect = null;
        
        self::$query_count++; //Считаем запросы к БД

        if (isset($a) && $a == 'end') {
            return $connect;
        }

        if (!isset($connect) && defined('DB_TYPE') && defined('DB_SERVER') && defined('DB_NAME') && defined('DB_USERNAME') && defined('DB_PASSWORD')) {

            try {
                $connect = new \PDO(DB_TYPE . ':host=' . DB_SERVER . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING, \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]);
            } catch (PDOException $error) {
                // Если ошибка соединения с БД в инсталляторе, то переадресуем на страницу ошибки
                if (\eMarket\Set::path() == 'install') {
                    header('Location: /controller/install/error.php?server_db_error=true&error_message=' . $error->getMessage());
                } else {
                    //Выводим на экран, если не в инсталляторе
                    print_r($error->getMessage());
                }
            }
        }

        return $connect;
    }

    /**
     * Инсталляция файла БД
     *
     * @param string $path (путь к файлу БД)
     */
    public static function dbInstall($path) {

        // Подключаем файл БД
        $file_name = $path . DB_TYPE . '.sql';

        // Устанавливаем префикс БД
        $buffer = str_replace('emkt_', DB_PREFIX, implode(file($file_name)));

        //Устанавливаем семейство БД
        if (DB_FAMILY == 'myisam') {
            $buffer = str_ireplace('ENGINE=InnoDB', 'ENGINE=MyISAM', $buffer);
        }

        self::getExec($buffer);
    }

    /**
     * getQuery вместо self::connect()->query()
     *
     * @param string $sql (запрос из БД MYSQL)
     * @return $result (запрос MYSQL)
     */
    public static function getQuery($sql) {

        $result = self::connect()->query($sql);
        return $result;
    }

    /**
     * getExec вместо self::connect()->exec()
     *
     * @param string $sql (запрос из БД MYSQL)
     * @return $result (запрос MYSQL)
     */
    public static function getExec($sql) {

        $result = self::connect()->exec($sql);
        return $result;
    }

    /**
     * getCell для запроса точного значения ячейки.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если несколько вариантов, удовлетворяющих условию, то выдает только верхний.
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Запрос вида: \eMarket\Pdo::getCell("SELECT language FROM table WHERE id=?", ['1']);
     * выдаст конкретное значение russian - НЕ МАССИВ!
     * 
     * Запрос вида: \eMarket\Pdo::getCell("SELECT * FROM table WHERE language=?", ['russian']);
     * выдаст значение первого поля, т.е. номер id.
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|string|array $result (возвращает массив, строку или FALSE)
     */
    public static function getCell($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $value = $exec->fetch(\PDO :: FETCH_NUM)
                AND $result = $value[0]) {
            
        }
        return \eMarket\Func::escape_sign($result);
    }

    /**
     * getColRow для запроса колонок, строк и участков из строк и колонок.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Если применять в запросе \eMarket\Pdo::getColRow("SELECT id FROM table WHERE language=?", ['russian']) то выдаст все значения колонки id в виде массива,
     * удовлетворяющие условию language='russian'.
     *
     * Array
     * (
     * [0] => Array
     * (
     * [0] => 2
     * )
     *
     * [1] => Array
     * (
     * [0] => 3
     * )
     * )
     *
     * Если применить в запросе \eMarket\Pdo::getColRow("SELECT * FROM table WHERE language=?", ['russian']), то выдаст полностью данные всех ячеек строк(и),
     * содержащих это условие (в массиве). Т.е. получится строковая выборка по условию:
     *
     * Array
     * (
     * [0] => Array
     * (
     * [0] => 1
     * [1] => russian
     * [2] => mail@mail.ru
     * [3] => b0baee9d279d34fa1dfd71aadb908c3f
     * [4] => admin
     * )
     *
     * [1] => Array
     * (
     * [0] => 3
     * [1] => russian
     * [2] => m@mail.ru
     * [3] => b0baee9d279d34fa1dfd71aadb908c3f
     * [4] => user
     * )
     * )
     *
     * Если применить в запросе \eMarket\Pdo::getColRow("SELECT id, language FROM table", array()), то выдаст таблицу из указанных колонок в массиве.
     *
     * Если применить в запросе \eMarket\Pdo::getColRow("SELECT id, language FROM table WHERE language=?", ['russian']), то выдаст таблицу из id
     * и language, удовлетворяющих требованию language='russian'.
     *
     * Если применить в запросе \eMarket\Pdo::getColRow("SELECT * FROM table", array()), то выдаст всю таблицу в массиве.
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|array $result (возвращает массив или FALSE)
     */
    public static function getColRow($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->fetchAll(\PDO :: FETCH_NUM)) {
            
        }
        return  \eMarket\Func::escape_sign($result);
    }

    /**
     * getCol для запроса колонки в виде одномерного массива.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Если применять в запросе \eMarket\Pdo::getColRow("SELECT name FROM table WHERE language=?", ['russian']) то выдаст все значения колонки id в виде одномерного массива,
     * удовлетворяющие условию language='russian'.
     *
     * Array
     * (
     * [0] => Name1
     * [1] => Name2
     * )
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|array $result (возвращает массив или FALSE)
     */
    public static function getCol($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->fetchAll(\PDO :: FETCH_COLUMN)) {
            
        }
        return  \eMarket\Func::escape_sign($result);
    }

    /**
     * getRow для запроса данных из строки в виде одномерного массива.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Если применять в запросе \eMarket\Pdo::getRow("SELECT name, age FROM table WHERE language=?", ['russian']) то выдаст все значения колонки id в виде одномерного массива,
     * удовлетворяющие условию language='russian'.
     *
     * Array
     * (
     * [0] => John
     * [1] => Age 18
     * )
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|array $result (возвращает массив или FALSE)
     */
    public static function getRow($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->fetchAll(\PDO :: FETCH_NUM)) {
            
        }
        return  \eMarket\Func::escape_sign($result)[0];
    }

    /**
     * getCellFalse выдает значение ячейки. Если ячейка не найдена то возвращает FALSE.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Использовать так: $a = \eMarket\Pdo::getCellFalse("SELECT permission FROM users WHERE login=? AND password=?", [$_SESSION['login'],$_SESSION['password']]);
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|array $result (возвращает массив или FALSE)
     */
    public static function getCellFalse($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->fetchColumn()) {
            
        }
        return \eMarket\Func::escape_sign($result);
    }

    /**
     * getColCount показывает количество столбцов в запросе. Результат выдается простым числовым значением.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Использовать так: $a = \eMarket\Pdo::getColCount("SELECT permission FROM users WHERE login=? AND password=?", [$_SESSION['login'],$_SESSION['password']]);
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|int|array $result (возвращает массив, целое число или FALSE)
     */
    public static function getColCount($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->ColumnCount()) {
            
        }
        return $result;
    }

    /**
     * getRowCount показывает количество строк в запросе. Результат выдается простым числовым значением.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Использовать так: $a = \eMarket\Pdo::getRowCount("SELECT permission FROM users WHERE login=? AND password=?", [$_SESSION['login'],$_SESSION['password']]);
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|int|array $result (возвращает массив, целое число или FALSE)
     */
    public static function getRowCount($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->RowCount()) {
            
        }
        return $result;
    }

    /**
     * selectPrepare показывает значение ячейки (не массив).
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значение не найдено, то выдает пустой массив: Array()
     * 
     * Использовать так: $a = \eMarket\Pdo::selectPrepare("SELECT permission FROM users WHERE login=? AND password=?", [$_SESSION['login'],$_SESSION['password']]);
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|string|array $result (возвращает массив, строку или FALSE)
     */
    public static function selectPrepare($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $value = $exec->fetchAll()
                AND $result = $value[0][0]) {
            
        }
        return $result;
    }

    /**
     * inPrepare служит для INSERT INTO, DELETE и UPDATE. 
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Если значения нет, то передает пустой массив: Array()
     * Использовать так:
     * 
     * \eMarket\Pdo::inPrepare("INSERT INTO emkt_table SET login=?, password=?", [$_SESSION['login'], $_SESSION['password']]); - создает новую строку
     * 
     * \eMarket\Pdo::inPrepare("UPDATE emkt_table SET login=?, password=? WHERE id=?", [$_SESSION['login'], $_SESSION['password'], $id]); - обновляет строку с конкретным id
     * 
     * \eMarket\Pdo::inPrepare("DELETE FROM emkt_table WHERE id=?", [$id]); - удаляет строку с конкретным id
     * 
     * Также можно применять для SELECT.
     * 
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|array $result (возвращает данные или FALSE)
     */
    public static function inPrepare($sql, $a = null) {

        $result = FALSE;
        if ($result = self::connect()->prepare($sql)
                AND $result->execute($a)) {
            
        }
        return $result;
    }

    /**
     * getColAssoc для запроса колонки в виде одномерного массива.
     * Применяется для случаев защиты от SQL-инъекций и при множественных одинаковых запросах.
     * 
     * ПРИМЕР
     *
     * Использовать так: $a = \eMarket\Pdo::getColAssoc("SELECT value, name FROM categories", []);
     * 
      Возвращаем следующую строку в виде массива, индексированного именами столбцов
      Array
      (
      [name] => apple
      [colour] => red
      )
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|array $result (возвращает данные или FALSE)
     */
    public static function getColAssoc($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->fetchAll(\PDO :: FETCH_ASSOC)) {
        }
        return \eMarket\Func::escape_sign($result);
    }

    /**
     * getObj для запроса в виде объекта.
     * 
     * @param string $sql (запрос из БД MYSQL)
     * @param array $a (параметр для execute($a))
     * @return bool|array $result (возвращает данные или FALSE)
     */
    public static function getObj($sql, $a) {

        $result = FALSE;
        if ($exec = self::connect()->prepare($sql)
                AND $exec->execute($a)
                AND $result = $exec->fetchAll(\PDO :: FETCH_OBJ)) {
            
        }
        return $result;
    }

    /**
     * lastInsertId для запроса последнего вставленного ID
     * 
     * @return string $result (возвращает данные)
     */
    public static function lastInsertId() {

        $result = self::connect()->lastInsertId();
        return $result;
    }

}

?>
