<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |    
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

?>

<?php if (isset($_SESSION['login']) && isset($_SESSION['pass'])) { // Выводим если авторизованы  ?>
    <div class="container-fluid">
	<div class="welcome text-center">
	    <?php echo lang('index-title') ?>
	    <div class="welcome_logo">eMarket <small>v0.2.0</small></div>
	    <div class="welcome_description"><?php echo lang('index-text') ?></div>
	</div>
    </div>
<?php } ?>