<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

?>
<!-- Модальное окно "Добавить" -->
<?php require_once('modal/add.php') ?>
<!-- КОНЕЦ Модальное окно "Добавить" -->

<!-- Модальное окно "Редактировать" -->
<?php require(ROOT . '/view/' . $SET->template() . '/admin/pages/settings/countries/modal/edit.php') ?>
<!-- КОНЕЦ Модальное окно "Редактировать" -->

<div id="ajax">
    <div id="settings" class="container-fluid">
        <div class="row-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <!--Выводим уведомление об успешном действии-->
                    <?php $MESSAGES->alert('success', lang('action_completed_successfully')); ?>
                    <h3 class="panel-title">
                        <div class="pull-left"><a class="btn btn-primary btn-xs" href="../"><span class="back glyphicon glyphicon-share-alt"></span></a> <?php echo lang('title_' . $SET->titleDir() . '_index') ?></div>
                        <div class="clearfix"></div>
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th colspan="5">
                                    <?php if ($lines == TRUE) { ?>
                                        <div class="page"><?php echo lang('s') ?> <?php echo $start + 1 ?> <?php echo lang('po') ?> <?php echo $finish ?> ( <?php echo lang('iz') ?> <?php echo count($lines); ?> )</div>
                                        <?php
                                    } else {

                                        ?>
                                        <div><?php echo lang('no_listing') ?></div>
                                    <?php } ?>
                                </th>

                                <th>
                                    <form>
                                        <?php if (count($lines) > $lines_on_page) { ?>
                                            <input hidden name="start" value="<?php echo $start ?>">
                                            <input hidden name="finish" value="<?php echo $finish ?>">
                                            <div class="right"><button type="submit" class="btn btn-primary btn-xs" action="index.php" formmethod="get"><span class="glyphicon glyphicon-chevron-right"></span></button></div>
                                        <?php } ?>
                                    </form>

                                    <form>
                                        <?php if (count($lines) > $lines_on_page) { ?>
                                            <input hidden name="start2" value="<?php echo $start ?>">
                                            <input hidden name="finish2" value="<?php echo $finish ?>">
                                            <div class="left"><button type="submit" class="btn btn-primary btn-xs" action="index.php" formmethod="get"><span class="glyphicon glyphicon-chevron-left"></span></button></div>
                                        <?php } ?>
                                    </form>

                                    <?php if (count($lines) > $lines_on_page) { ?> <div class="left"> <?php } else { ?> <div class="right"> <?php } ?>
                                            <!--Вызов модального окна для добавления-->
                                            <a href="#add" class="btn btn-primary btn-xs" data-toggle="modal"><span class="glyphicon glyphicon-plus"></span></a></div>
                                </th>
                            </tr>
                            <?php if ($lines == TRUE) { ?>
                                <tr class="border">
                                    <th class="sortleft"></th>
                                    <th><?php echo lang('country') ?></th>
                                    <th class="al-text"><?php echo lang('alpha_2') ?></th>
                                    <th class="al-text"><?php echo lang('alpha_3') ?></th>
                                    <th class="al-text"><?php echo lang('country_flag') ?></th>
                                    <th class="al-text-w"></th>
                                </tr>
                            <?php } ?>
                        </thead>
                        <tbody>
                            <?php for ($start; $start < $finish; $start++) { ?>
                                <tr>
                                    <td class="sortleft">
                                        <form action="regions/index.php">
                                            <input hidden name="country_id" value="<?php echo $lines[$start][0] ?>">
                                            <button type="submit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-cog"></span></button>
                                        </form>
                                    </td>
                                    <td><?php echo $lines[$start][1] ?></td>
                                    <td class="al-text"><?php echo $lines[$start][2] ?></td>
                                    <td class="al-text"><?php echo $lines[$start][3] ?></td>
                                    <td class="al-text"><img src='/view/<?php echo $SET->template() ?>/admin/images/worldflags/<?php echo strtolower($lines[$start][2]) ?>.png'></td>
                                    <td class="al-text-w">
                                        <form id="form_delete" name="form_delete" method="POST" onsubmit="call_delete()" enctype="multipart/form-data">
                                            <input hidden name="delete" value="<?php echo $lines[$start][0] ?>">
                                            <div class="right">
                                                <button type="submit" name="delete_but" class="btn btn-primary btn-xs" data-toggle="confirmation" data-btn-ok-label="<?php echo lang('confirm-yes') ?>" data-btn-cancel-label="<?php echo lang('confirm-no') ?>" title="<?php echo lang('confirm-del') ?>"><span class="glyphicon glyphicon-trash"> </span></button>
                                            </div>
                                        </form>
                                        <!--Вызов модального окна для редактирования-->
                                        <div class="left">
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#edit" data-edit="<?php echo $lines[$start][0] ?>"><span class="glyphicon glyphicon-edit"></span></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
