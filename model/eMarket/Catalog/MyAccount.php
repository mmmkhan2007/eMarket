<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Catalog;

/**
 * My Account
 *
 * @package Catalog
 * @author eMarket
 * 
 */
class MyAccount {

    /**
     * Constructor
     *
     */
    function __construct() {
        $this->autorize();
        $this->edit();
    }

    /**
     * Autorize
     *
     */
    public function autorize() {
        if (\eMarket\Autorize::$CUSTOMER == FALSE) {
            header('Location: ?route=login');
            exit;
        }
    }

    /**
     * Edit
     *
     */
    public function edit() {
        if (\eMarket\Valid::inPOST('edit')) {
            if (\eMarket\Valid::inPOST('password') && \eMarket\Valid::inPOST('confirm_password') && \eMarket\Valid::inPOST('password') == \eMarket\Valid::inPOST('confirm_password')) {
                $password_hash = \eMarket\Autorize::passwordHash(\eMarket\Valid::inPOST('password'));
                \eMarket\Pdo::action("UPDATE " . TABLE_CUSTOMERS . " SET firstname=?, lastname=?, middle_name=?, telephone=?, password=? WHERE email=?", [\eMarket\Valid::inPOST('firstname'), \eMarket\Valid::inPOST('lastname'), \eMarket\Valid::inPOST('middle_name'), \eMarket\Valid::inPOST('telephone'), $password_hash, \eMarket\Autorize::$CUSTOMER['email']]);
            } else {
                \eMarket\Pdo::action("UPDATE " . TABLE_CUSTOMERS . " SET firstname=?, lastname=?, middle_name=?, telephone=? WHERE email=?", [\eMarket\Valid::inPOST('firstname'), \eMarket\Valid::inPOST('lastname'), \eMarket\Valid::inPOST('middle_name'), \eMarket\Valid::inPOST('telephone'), \eMarket\Autorize::$CUSTOMER['email']]);
            }

            \eMarket\Messages::alert('success', lang('action_completed_successfully'));
        }
    }

}
