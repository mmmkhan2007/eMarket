<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Other;

class Eac {

    /**
     * Движок EAC (Engine Ajax Catalog) v.1.0
     * @param строка $TABLE_CATEGORIES (название таблицы категорий)
     * @return массив
     */
    public function start($TABLE_CATEGORIES) {

        $PDO = new \eMarket\Core\Pdo;
        $VALID = new \eMarket\Core\Valid;

        // Устанавливаем parent_id родительской категории
        $parent_id = self::parentIdStart($TABLE_CATEGORIES);

        // Если нажали на кнопку Добавить
        self::addCategory($TABLE_CATEGORIES, $parent_id);

        // Если нажали на кнопку Редактировать
        self::editCategory($TABLE_CATEGORIES);

        $idsx_real_parent_id = $parent_id; //для отправки в JS
        //
        // ГРУППОВЫЕ ДЕЙСТВИЯ: Если нажали на кнопки: Отображать, Скрыть, Удалить, Вырезать, Вставить + выделение
        if ($VALID->inGET('idsx_cut_marker') == 'cut') { // очищаем буфер обмена, если он был заполнен, при нажатии Вырезать
            unset($_SESSION['buffer']);
        }

        if (($VALID->inGET('idsx_paste_key') == 'paste')
                or ( $VALID->inGET('idsx_statusOn_key') == 'statusOn')
                or ( $VALID->inGET('idsx_statusOff_key') == 'statusOff')) {
            $parent_id_real = (int) $VALID->inGET('idsx_real_parent_id'); // получить значение из JS
        }

        if (($VALID->inGET('idsx_statusOn_key') == 'statusOn')
                or ( $VALID->inGET('idsx_statusOff_key') == 'statusOff')
                or ( $VALID->inGET('idsx_cut_key') == 'cut')
                or ( $VALID->inGET('idsx_delete_key') == 'delete')) {

            if ($VALID->inGET('idsx_statusOn_key') == 'statusOn') {
                $idx = $VALID->inGET('idsx_statusOn_id');
                $status = 1;
            }

            if ($VALID->inGET('idsx_statusOff_key') == 'statusOff') {
                $idx = $VALID->inGET('idsx_statusOff_id');
                $status = 0;
            }

            if ($VALID->inGET('idsx_cut_key') == 'cut') {
                $idx = $VALID->inGET('idsx_cut_id');
                $parent_id_real = (int) $VALID->inGET('idsx_real_parent_id'); // получить значение из JS
            }

            if ($VALID->inGET('idsx_delete_key') == 'delete') {
                $idx = $VALID->inGET('idsx_delete_id');
            }

            // Устанавливаем родительскую категорию
            $parent_id = $PDO->selectPrepare("SELECT parent_id FROM " . $TABLE_CATEGORIES . " WHERE id=?", [$idx]);
            // Устанавливаем родительскую категорию родительской категории
            $parent_id_up = $PDO->selectPrepare("SELECT parent_id FROM " . $TABLE_CATEGORIES . " WHERE id=?", [$parent_id]);
            // считаем одинаковые parent_id
            $parent_id_num = $PDO->getColRow("SELECT id FROM " . $TABLE_CATEGORIES . " WHERE parent_id=?", [$parent_id]);
            // если меньше 2-х значений, то устанавливаем parent_id как родительский родительского
            if (count($parent_id_num) < 2) {
                $parent_id = $parent_id_up;
            }

            //Выбираем данные из БД
            $data_cat = $PDO->inPrepare("SELECT id, parent_id FROM " . $TABLE_CATEGORIES);

            $category = $idx; // id родителя
            $categories = array();
            $keys = array(); // массив ключей
            $keys[] = $category; // добавляем первый ключ в массив
            // В цикле формируем ассоциативный массив разделов
            while ($category = $data_cat->fetch(\PDO::FETCH_ASSOC)) {
                // Проверяем наличие id категории в массиве ключей
                if (in_array($category['parent_id'], $keys)) {
                    $categories[$category['parent_id']][] = $category['id'];
                    $keys[] = $category['id']; // расширяем массив
                }
            }

            for ($x = 0; $x < count($keys); $x++) {

                //Обновляем статус подкатегорий
                if (($VALID->inGET('idsx_statusOn_key') == 'statusOn')
                        or ( $VALID->inGET('idsx_statusOff_key') == 'statusOff')) {
                    $PDO->inPrepare("UPDATE " . $TABLE_CATEGORIES . " SET status=? WHERE id=?", [$status, $keys[$x]]);
                    if ($parent_id_real > 0) {
                        $parent_id = $parent_id_real; // Возвращаемся в свою директорию после "Вырезать"
                    }
                }

                //Удаляем подкатегории
                if ($VALID->inGET('idsx_delete_key') == 'delete') {
                    $PDO->inPrepare("DELETE FROM " . $TABLE_CATEGORIES . " WHERE id=?", [$keys[$x]]);
                }
            }

            //Обновляем статус основной категории
            if (($VALID->inGET('idsx_statusOn_key') == 'statusOn')
                    or ( $VALID->inGET('idsx_statusOff_key') == 'statusOff')) {
                $PDO->inPrepare("UPDATE " . $TABLE_CATEGORIES . " SET status=? WHERE id=?", [$status, $idx]);
            }

            //Вырезаем основную родительскую категорию    
            if ($VALID->inGET('idsx_cut_key') == 'cut') {
                if (!isset($_SESSION['buffer'])) {
                    $_SESSION['buffer'] = array();
                }
                array_push($_SESSION['buffer'], $idx);
                if ($parent_id_real > 0) {
                    $parent_id = $parent_id_real; // Возвращаемся в свою директорию после обновления
                }
            }

            //Удаляем основную категорию    
            if ($VALID->inGET('idsx_delete_key') == 'delete') {
                $PDO->inPrepare("DELETE FROM " . $TABLE_CATEGORIES . " WHERE id=?", [$idx]);
            }
        }

        //Вставляем вырезанные категории    
        if ($VALID->inGET('idsx_paste_key') == 'paste' && isset($_SESSION['buffer']) == TRUE) {
            for ($buf = 0; $buf < count($_SESSION['buffer']); $buf++) {
                $PDO->inPrepare("UPDATE " . $TABLE_CATEGORIES . " SET parent_id=? WHERE id=?", [$parent_id_real, $_SESSION['buffer'][$buf]]);
            }
            unset($_SESSION['buffer']); // очищаем буфер обмена
            if ($parent_id_real > 0) {
                $parent_id = $parent_id_real; // Возвращаемся в свою директорию после вставки
            }
        }
        // Если parrent_id является массивом, то
        if (is_array($parent_id) == TRUE) {
            $parent_id = 0;
        }

        return array($idsx_real_parent_id, $parent_id);
    }

    /**
     * Сортировка мышкой в EAC
     * @param строка $TABLE_CATEGORIES (название таблицы категорий)
     * @param строка $TOKEN (токен)
     * @param строка $TOKEN
     */
    public function sortMouse($TABLE_CATEGORIES, $TOKEN) {

        $PDO = new \eMarket\Core\Pdo;
        $VALID = new \eMarket\Core\Valid;

        // если сортируем категории мышкой
        if ($VALID->inGET('token_ajax') == $TOKEN && $VALID->inGET('ids')) {
            $j2 = $VALID->inGET('j');
            $sort_ajax = explode(',', $VALID->inGET('ids'));
            for ($ajax_i = 0; $ajax_i < count($sort_ajax); $ajax_i++) {
                $PDO->inPrepare("UPDATE " . $TABLE_CATEGORIES . " SET sort_category=? WHERE id=?", [$ajax_i + $j2, $sort_ajax[$ajax_i]]);
            }
        }
    }

    /**
     * Добавить категорию в EAC
     * @param строка $TABLE_CATEGORIES (название таблицы категорий)
     * @param строка $parent_id
     */
    private function addCategory($TABLE_CATEGORIES, $parent_id) {

        $PDO = new \eMarket\Core\Pdo;
        $VALID = new \eMarket\Core\Valid;

        if ($VALID->inGET(lang('#lang_all')[0])) {

            if ($VALID->inGET('view_cat')) {
                $view_cat = 1;
            } else {
                $view_cat = 0;
            }

            $sort_category = 0;

            // Получаем последний id и увеличиваем его на 1
            $id_max = $PDO->selectPrepare("SELECT id FROM " . $TABLE_CATEGORIES . " WHERE language=? ORDER BY id DESC", [lang('#lang_all')[0]]);
            $id = intval($id_max) + 1;

            // добавляем запись для всех вкладок
            for ($xl = 0; $xl < count(lang('#lang_all')); $xl++) {
                $PDO->inPrepare("INSERT INTO " . $TABLE_CATEGORIES . " SET id=?, name=?, sort_category=?, language=?, parent_id=?, date_added=?, status=?", [$id, $VALID->inGET(lang('#lang_all')[$xl]), $sort_category, lang('#lang_all')[$xl], $parent_id, date("Y-m-d H:i:s"), $view_cat]);
            }
        }
    }

    /**
     * Редактировать категорию в EAC
     * @param строка $TABLE_CATEGORIES (название таблицы категорий)
     */
    private function editCategory($TABLE_CATEGORIES) {

        $PDO = new \eMarket\Core\Pdo;
        $VALID = new \eMarket\Core\Valid;

        if ($VALID->inGET('cat_edit')) {

            if ($VALID->inGET('view_cat')) {
                $view_cat = 1;
            } else {
                $view_cat = 0;
            }

            for ($xl = 0; $xl < count(lang('#lang_all')); $xl++) {
                // обновляем запись
                $PDO->inPrepare("UPDATE " . $TABLE_CATEGORIES . " SET name=?, last_modified=?, status=? WHERE id=? AND language=?", [$VALID->inGET('name_edit' . lang('#lang_all')[$xl]), date("Y-m-d H:i:s"), $view_cat, $VALID->inGET('cat_edit'), lang('#lang_all')[$xl]]);
            }
        }
    }

    /**
     * Установить parent_id родительской категории
     * @param строка $TABLE_CATEGORIES (название таблицы категорий)
     * @return строка $parent_id
     */
    private function parentIdStart($TABLE_CATEGORIES) {

        $PDO = new \eMarket\Core\Pdo;
        $VALID = new \eMarket\Core\Valid;

        // Устанавливаем родительскую категорию
        $parent_id = $VALID->inGET('parent_id');
        if ($parent_id == FALSE) {
            $parent_id = 0;
        }

        // Устанавливаем родительскую категорию при переходе на уровень выше
        if ($VALID->inGET('parent_up')) {
            $parent_id = $PDO->selectPrepare("SELECT parent_id FROM " . $TABLE_CATEGORIES . " WHERE id=?", [$VALID->inGET('parent_up')]);
        }

        // Устанавливаем родительскую категорию при переходе на уровень ниже
        if ($VALID->inGET('parent_down')) {
            $parent_id = $VALID->inGET('parent_down');
        }
        return $parent_id;
    }

}

?>