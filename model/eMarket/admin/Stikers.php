<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Admin;

/**
 * Stikers
 *
 * @package Admin
 * @author eMarket
 * 
 */
class Stikers {

    public static $sql_data = FALSE;
    public static $json_data = FALSE;

    /**
     * Constructor
     *
     */
    function __construct() {
        $this->add();
        $this->edit();
        $this->delete();
        $this->data();
        $this->modal();
    }

    /**
     * Add
     *
     */
    public function add() {
        if (\eMarket\Valid::inPOST('add')) {

            if (\eMarket\Valid::inPOST('default_stikers')) {
                $default_stikers = 1;
            } else {
                $default_stikers = 0;
            }

            $id_max = \eMarket\Pdo::selectPrepare("SELECT id FROM " . TABLE_STIKERS . " WHERE language=? ORDER BY id DESC", [lang('#lang_all')[0]]);
            $id = intval($id_max) + 1;

            if ($id > 1 && $default_stikers != 0) {
                \eMarket\Pdo::action("UPDATE " . TABLE_STIKERS . " SET default_stikers=?", [0]);
            }

            for ($x = 0; $x < \eMarket\Lang::$COUNT; $x++) {
                \eMarket\Pdo::action("INSERT INTO " . TABLE_STIKERS . " SET id=?, name=?, language=?, default_stikers=?", [$id, \eMarket\Valid::inPOST('name_stikers_' . $x), lang('#lang_all')[$x], $default_stikers]);
            }

            \eMarket\Messages::alert('success', lang('action_completed_successfully'));
        }
    }

    /**
     * Edit
     *
     */
    public function edit() {
        if (\eMarket\Valid::inPOST('edit')) {

            if (\eMarket\Valid::inPOST('default_stikers')) {
                $default_stikers = 1;
            } else {
                $default_stikers = 0;
            }

            if ($default_stikers != 0) {
                \eMarket\Pdo::action("UPDATE " . TABLE_STIKERS . " SET default_stikers=?", [0]);
            }

            for ($x = 0; $x < \eMarket\Lang::$COUNT; $x++) {
                \eMarket\Pdo::action("UPDATE " . TABLE_STIKERS . " SET name=?, default_stikers=? WHERE id=? AND language=?", [\eMarket\Valid::inPOST('name_stikers_' . $x), $default_stikers, \eMarket\Valid::inPOST('edit'), lang('#lang_all')[$x]]);
            }

            \eMarket\Messages::alert('success', lang('action_completed_successfully'));
        }
    }

    /**
     * Delete
     *
     */
    public function delete() {
        if (\eMarket\Valid::inPOST('delete')) {
            \eMarket\Pdo::action("DELETE FROM " . TABLE_STIKERS . " WHERE id=?", [\eMarket\Valid::inPOST('delete')]);

            \eMarket\Messages::alert('success', lang('action_completed_successfully'));
        }
    }

    /**
     * Data
     *
     */
    public function data() {
        self::$sql_data = \eMarket\Pdo::getColAssoc("SELECT * FROM " . TABLE_STIKERS . " ORDER BY id DESC", []);
        $lines = \eMarket\Func::filterData(self::$sql_data, 'language', lang('#lang_all')[0]);
        \eMarket\Pages::table($lines);
    }

    /**
     * Modal
     *
     */
    public function modal() {
        self::$json_data = json_encode([]);
        $name = [];
        for ($i = \eMarket\Pages::$start; $i < \eMarket\Pages::$finish; $i++) {
            if (isset(\eMarket\Pages::$table['lines'][$i]['id']) == TRUE) {

                $modal_id = \eMarket\Pages::$table['lines'][$i]['id'];

                foreach (self::$sql_data as $sql_modal) {
                    if ($sql_modal['id'] == $modal_id) {
                        $name[array_search($sql_modal['language'], lang('#lang_all'))][$modal_id] = $sql_modal['name'];
                    }
                    if ($sql_modal['language'] == lang('#lang_all')[0] && $sql_modal['id'] == $modal_id) {
                        $default_stikers[$modal_id] = (int) $sql_modal['default_stikers'];
                    }
                }

                ksort($name);

                self::$json_data = json_encode([
                    'name' => $name,
                    'default_stikers' => $default_stikers
                ]);
            }
        }
    }

}