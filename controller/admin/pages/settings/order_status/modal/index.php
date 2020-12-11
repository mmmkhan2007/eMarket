<?php

/* =-=-=-= Copyright © 2018 eMarket =-=-=-= 
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

// собираем данные для отображения в Редактировании
$json_data = json_encode([]);
for ($i = $start; $i < $finish; $i++) {
    if (isset($lines[$i]['id']) == TRUE) {

        $modal_id = $lines[$i]['id']; // ID
        
        foreach ($sql_data as $sql_modal) {
            //Языковые
            if ($sql_modal['id'] == $modal_id) {
                $name[array_search($sql_modal['language'], lang('#lang_all'))][$modal_id] = $sql_modal['name'];
            }
            if ($sql_modal['language'] == lang('#lang_all')[0] && $sql_modal['id'] == $modal_id) {
                $default_order_status[$modal_id] = $sql_modal['default_order_status'];
            }
        }
        //Сортируем языковые
        ksort($name);

        $json_data = json_encode([
            'name' => $name,
            'default_order_status' => $default_order_status
        ]);
    }
}
?>
