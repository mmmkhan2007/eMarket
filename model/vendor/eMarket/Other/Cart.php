<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Other;

/**
 * Класс для работы с корзиной
 *
 * @package Cart
 * @author eMarket
 * 
 */
class Cart {

    /**
     * Добавить товар в корзину
     *
     * @param string $id (ID товара)
     * @param string $quantity (количество добавляемых товаров)
     */
    public function add($id, $quantity = null) {
        $FUNC = new \eMarket\Other\Func;

        $count = 0;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [['id' => $id, 'quantity' => $quantity]];
        } else {
            // Если не было такого id, то добавляем в массив для подсчета
            $id_count = $FUNC->filterArrayToKey($_SESSION['cart'], 'id', $id, 'id');
            foreach ($_SESSION['cart'] as $value) {
                if ($value['id'] == $id) {
                    $_SESSION['cart'][$count]['quantity'] = $_SESSION['cart'][$count]['quantity'] + $quantity;
                }
                if ($value['id'] != $id && count($id_count) == 0) {
                    array_push($_SESSION['cart'], ['id' => $id, 'quantity' => $quantity]);
                }
                $count++;
            }
        }
    }

    /**
     * Подсчет количества товара в корзине
     *
     * @return string $total_quantity (количества товара)
     */
    public function totalQuantity() {

        $total_quantity = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $value) {
                $total_quantity = $total_quantity + $value['quantity'];
            }
        }
        return $total_quantity;
    }

    /**
     * Подсчет количества товара в корзине
     *
     * @return string $total_quantity (количества товара)
     */
    public function totalPrice() {
        $PDO = new \eMarket\Core\Pdo;

        $total_price = 0;
        foreach ($_SESSION['cart'] as $value) {
            $price = $PDO->getCell("SELECT price FROM " . TABLE_PRODUCTS . " WHERE id=? AND language=?", [$value['id'], lang('#lang_all')[0]]);
            $total_price = $total_price + $price * $value['quantity'];
        }
        return $total_price;
    }

    /**
     * Инициализация корзины на страницы
     *
     */
    public function init() {
        $VALID = new \eMarket\Core\Valid;
        $SET = new \eMarket\Core\Set;

        if ($SET->path() == 'catalog' && $VALID->inGET('add_to_cart')) {
            if (!$VALID->inGET('add_quantity')) {
                $add_quantity = 1;
            } else {
                $add_quantity = $VALID->inGET('add_quantity');
            }
            self::add($VALID->inGET('add_to_cart'), $add_quantity);
        }
    }

    /**
     * Подсчет количества товара в корзине
     *
     * @return array $cart (информация о товарах в корзине)
     */
    public function info() {
        $PDO = new \eMarket\Core\Pdo;

        $cart_info = [];
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $value) {
                $product = $PDO->getColAssoc("SELECT id, name, logo_general, price FROM " . TABLE_PRODUCTS . " WHERE language=? AND id=?", [lang('#lang_all')[0], $value['id']]);
                array_push($cart_info, $product[0]);
            }
        }
        return $cart_info;
    }

    /**
     * Подсчет количества товара в корзине
     * @param string $id (ID товара в корзине)
     * @return string $total_quantity (количества товара)
     */
    public function cartProductQuantity($id) {

        $product_quantity = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $value) {
                if ($value['id'] == $id) {
                    $product_quantity = $value['quantity'];
                }
            }
        }
        return $product_quantity;
    }

}

?>