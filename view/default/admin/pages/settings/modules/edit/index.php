<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

?>

<div id="settings_modules" class="container-fluid">
    <div class="panel panel-default">

        <div class="panel-heading">
            <!--Выводим уведомление об успешном действии-->
            <?php $MESSAGES->alert(); ?>
            <h3 class="panel-title">
                <div class="pull-left"><a class="btn btn-primary btn-xs" href="?route=settings/modules&active=<?php echo $VALID->inGET('type') ?>"><span class="back glyphicon glyphicon-share-alt"></span></a> <?php echo lang($VALID->inGET('type') . '_' . $VALID->inGET('name') . '_name') ?></div>
                <div class="clearfix"></div>
            </h3>
        </div>
        
        <div class="panel-body">
            <div class="pull-right">
                <input hidden type="checkbox" data-off-color="danger" data-size="mini" data-on-text="ВКЛ." data-off-text="ВЫКЛ." name="switch" checked>
            </div>
            <div class="pull-left">
                <div class="text-left"><?php echo lang('modules_name') ?> <?php echo lang($VALID->inGET('type') . '_' . $VALID->inGET('name') . '_name') ?></div>
                <div class="text-left"><?php echo lang('modules_author') ?> <?php echo lang($VALID->inGET('type') . '_' . $VALID->inGET('name') . '_author') ?></div>
                <div class="text-left"><?php echo lang('modules_version') ?> <?php echo lang($VALID->inGET('type') . '_' . $VALID->inGET('name') . '_version') ?></div>
            </div>
            <div class="clearfix"></div></br>

        <!--Выводим данные из модуля-->
        <?php require_once (ROOT . '/modules/' . $VALID->inGET('type') . '/' . $VALID->inGET('name') . '/controller/admin.php'); ?>
        
        </div>

    </div>
</div>