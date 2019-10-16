<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket;

/**
 * Класс для навигации и сопутствующих элементов
 *
 * @package Navigation
 * @author eMarket
 * 
 */
class Navigation {

    /**
     * КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ ДЛЯ GET
     *
     * @param string $count_lines (количество строк на странице)
     * @param string $lines_on_page (максимум строк на странице)
     * @param string $transfer (используется трансфер)
     * @return array array($start, $finish)
     */
    public static function getLink($count_lines, $lines_on_page, int $transfer = null) {

        $start = 0; // устанавливаем страницу в ноль при заходе
        $finish = $lines_on_page;

        if ($count_lines <= $lines_on_page) {
            $finish = $count_lines;
        }

        if ($start == 0 && $finish >= $lines_on_page + $transfer && $transfer != null) {
            return array($start, $finish + $transfer);
        }

        if ($start == 0 && $count_lines >= $lines_on_page + $transfer && $transfer != null) {
            $finish = $finish + $transfer;
        }

        // Если нажали на кнопку вперед GET
        if (\eMarket\Valid::inGET('finish')) {
            $finish = \eMarket\Valid::inGET('finish') + $lines_on_page; // пересчитываем количество строк на странице
            $start = \eMarket\Valid::inGET('start') + $lines_on_page; // задаем значение счетчика
            if ($start >= $count_lines) {
                $start = \eMarket\Valid::inGET('start');
            }
            if ($finish >= $count_lines) {
                $finish = $count_lines;
            }
            return array($start, $finish);
        }
        // Если нажали на кнопку назад GET
        if ($count_lines > $lines_on_page && \eMarket\Valid::inGET('finish2')) {
            $finish = \eMarket\Valid::inGET('start2'); // пересчитываем количество строк на странице
            $start = \eMarket\Valid::inGET('start2') - $lines_on_page; // задаем значение счетчика
            if ($start < 0) {
                $start = 0;
            }
            if ($finish < $lines_on_page) {
                $finish = $lines_on_page;
            }
            return array($start, $finish + $transfer);
        }

        return array($start, $finish);
    }

    /**
     * КНОПКИ НАВИГАЦИИ НАЗАД-ВПЕРЕД И ПОСТРОЧНЫЙ ВЫВОД ТАБЛИЦЫ ДЛЯ POST
     *
     * @param string $count_lines (количество строк на странице)
     * @param string $lines_on_page (максимум строк на странице)
     * @param string $transfer (используется трансфер)
     * @return array array($start, $finish)
     */
    public static function postLink($count_lines, $lines_on_page, int $transfer = null) {

        $start = 0; // устанавливаем страницу в ноль при заходе
        $finish = $lines_on_page;

        if ($count_lines <= $lines_on_page) {
            $finish = $count_lines;
        }

        if ($start == 0 && $finish >= $lines_on_page + $transfer && $transfer != null) {
            return array($start, $finish + $transfer);
        }

        if ($start == 0 && $count_lines >= $lines_on_page + $transfer && $transfer != null) {
            $finish = $finish + $transfer;
        }

        // Если нажали на кнопку вперед POST
        if (\eMarket\Valid::inPOST('finish')) {
            $finish = \eMarket\Valid::inPOST('finish') + $lines_on_page; // пересчитываем количество строк на странице
            $start = \eMarket\Valid::inPOST('start') + $lines_on_page; // задаем значение счетчика
            if ($start >= $count_lines) {
                $start = \eMarket\Valid::inPOST('start');
            }
            if ($finish >= $count_lines) {
                $finish = $count_lines;
            }
            return array($start, $finish);
        }
        // Если нажали на кнопку назад POST
        if ($count_lines > $lines_on_page && \eMarket\Valid::inPOST('finish2')) {
            $finish = \eMarket\Valid::inPOST('start2'); // пересчитываем количество строк на странице
            $start = \eMarket\Valid::inPOST('start2') - $lines_on_page; // задаем значение счетчика
            if ($start < 0) {
                $start = 0;
            }
            if ($finish < $lines_on_page) {
                $finish = $lines_on_page;
            }
            return array($start, $finish + $transfer);
        }

        return array($start, $finish);
    }

}

?>