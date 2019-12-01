<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
?>
<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

namespace eMarket\Modules\Discount;

/**
 * Класс для валидации данных
 *
 * @package Sale
 * @author eMarket
 * 
 */
class Sale {

    /**
     * Выходные данные для внутреннего интерфейса калькулятора
     *
     * @param array $input (массив с входящими значениями id, price, discount)
     * @return array $output (массив с выодящими значениями id, price, discount)
     */
    public static function interface($input, $CURRENCIES) {

        if ($input[2] != '' && $input[2] != NULL) {
            $discount = \eMarket\Pdo::getCell("SELECT sale_value FROM " . DB_PREFIX . 'modules_discount_sale' . " WHERE language=? AND id=?", [lang('#lang_all')[0], $input[2]]);
            $price = $input[1] / 100 * (100 - $discount);
            $this_time = time();
            $date_start = \eMarket\Pdo::getCell("SELECT UNIX_TIMESTAMP (date_start) FROM " . DB_PREFIX . 'modules_discount_sale' . " WHERE id=?", [$input[2]]);
            $date_end = \eMarket\Pdo::getCell("SELECT UNIX_TIMESTAMP (date_end) FROM " . DB_PREFIX . 'modules_discount_sale' . " WHERE id=?", [$input[2]]);
            
            if ($this_time > $date_start && $this_time < $date_end) {
                return '<del>' . \eMarket\Products::productPrice($input[1], $CURRENCIES, 1) . '</del><br><span class="label label-danger">' . \eMarket\Products::productPrice($price, $CURRENCIES, 1) . '</span>';
            }
            return \eMarket\Products::productPrice($input[1], $CURRENCIES, 1);
        }
        return \eMarket\Products::productPrice($input[1], $CURRENCIES, 1);
    }

}

?>
