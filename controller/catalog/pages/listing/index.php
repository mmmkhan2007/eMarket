<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |    
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

/* >-->-->-->  CONNECT PAGE START  <--<--<--< */
require_once(getenv('DOCUMENT_ROOT') . '/model/start.php');
/* ------------------------------------------ */

$products_new = $PRODUCTS->viewNew(10);

//$DEBUG->trace($image);
/* ->-->-->-->  CONNECT PAGE END  <--<--<--<- */
require_once(ROOT . '/model/end.php');
/* ------------------------------------------ */

?>