<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
?>
<!-- Модальное окно "Настройки" -->
<?php require_once('modal/settings.php') ?>
<!-- КОНЕЦ Модальное окно "Добавить" -->

<!-- Модальное окно "Добавить" -->
<?php require_once('modal/index.php') ?>
<!-- КОНЕЦ Модальное окно "Добавить" -->

<div id="slideshow">
    <div class="panel panel-default">
        <div class="panel-heading">
            <!--Выводим уведомление об успешном действии-->
            <div id="alert_block"><?php \eMarket\Messages::alert(); ?></div>
            <h3 class="panel-title">
                <?php echo \eMarket\Set::titlePageGenerator() ?>
            </h3>
        </div>
        <div class="panel-body">
            <!--Скрытый div для передачи данных-->
            <div id="ajax_data" class='hidden' 
                 data-jsonsettings='<?php echo $settings ?>'
                 data-jsondata='<?php echo $json_data ?>'></div>

            <div class="pull-right slide-sett"><a href="#settings" class="btn btn-primary btn-xs" data-toggle="modal"><span class="glyphicon glyphicon-cog"></span></a></div>

            <!-- Языковые панели -->
            <ul class="nav nav-tabs">
                <li class="<?php echo \eMarket\Set::activeTab($set_language, lang('#lang_all')[0]) ?>"><a data-toggle="tab" href="#<?php echo lang('#lang_all')[0] ?>"><img src="/view/<?php echo \eMarket\Set::template() ?>/admin/images/langflags/<?php echo lang('#lang_all')[0] ?>.png" alt="<?php echo lang('#lang_all')[0] ?>" title="<?php echo lang('#lang_all')[0] ?>" width="16" height="10" /> <?php echo lang('language_name', lang('#lang_all')[0]) ?></a></li>

                <?php
                if ($LANG_COUNT > 1) {
                    for ($x = 1; $x < $LANG_COUNT; $x++) {
                        ?>

                        <li class="<?php echo \eMarket\Set::activeTab($set_language, lang('#lang_all')[$x]) ?>"><a data-toggle="tab" href="#<?php echo lang('#lang_all')[$x] ?>"><img src="/view/<?php echo \eMarket\Set::template() ?>/admin/images/langflags/<?php echo lang('#lang_all')[$x] ?>.png" alt="<?php echo lang('#lang_all')[$x] ?>" title="<?php echo lang('#lang_all')[$x] ?>" width="16" height="10" /> <?php echo lang('language_name', lang('#lang_all')[$x]) ?></a></li>

                        <?php
                    }
                }
                ?>

            </ul>

            <div class="ajax-tab tab-content">
                <div id="<?php echo lang('#lang_all')[0] ?>" class="tab-pane fade in active">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th colspan="5">
                                        <?php if ($lines == TRUE) { ?>
                                            <?php echo lang('with') ?> <?php echo $start + 1 ?> <?php echo lang('to') ?> <?php echo $finish ?> ( <?php echo lang('of') ?> <?php echo $count_lines; ?> )
                                            <?php
                                        } else {
                                            ?>
                                            <?php echo lang('no_listing') ?>
                                        <?php } ?>
                                    </th>

                                    <th>
                                        <div class="flexbox">
                                            <div class="b-left"><a href="#index" class="btn btn-primary btn-xs" data-toggle="modal"><span class="glyphicon glyphicon-plus"></span></a></div>
                                            <form>
                                                <input hidden name="route" value="<?php echo \eMarket\Valid::inGET('route') ?>">
                                                <input hidden name="slide_lang" value="<?php echo \eMarket\Valid::inGET('slide_lang') ?>">
                                                <input hidden name="backstart" value="<?php echo $start ?>">
                                                <input hidden name="backfinish" value="<?php echo $finish ?>">
                                                <div class="b-left">
                                                    <?php if ($start > 0) { ?>
                                                        <button type="submit" class="btn btn-primary btn-xs" formmethod="get"><span class="glyphicon glyphicon-chevron-left"></span></button>
                                                    <?php } else { ?>
                                                        <a type="submit" class="btn btn-primary btn-xs disabled"><span class="glyphicon glyphicon-chevron-left"></span></a>
                                                    <?php } ?>
                                                </div>
                                            </form>
                                            <form>
                                                <input hidden name="route" value="<?php echo \eMarket\Valid::inGET('route') ?>">
                                                <input hidden name="slide_lang" value="<?php echo \eMarket\Valid::inGET('slide_lang') ?>">
                                                <input hidden name="start" value="<?php echo $start ?>">
                                                <input hidden name="finish" value="<?php echo $finish ?>">
                                                <div>
                                                    <?php if ($finish != $count_lines) { ?>
                                                        <button type="submit" class="btn btn-primary btn-xs" formmethod="get"><span class="glyphicon glyphicon-chevron-right"></span></button>
                                                    <?php } else { ?>
                                                        <a type="submit" class="btn btn-primary btn-xs disabled"><span class="glyphicon glyphicon-chevron-right"></span></a>
                                                    <?php } ?>
                                                </div>
                                            </form>
                                        </div>
                                    </th>
                                </tr>

                                <tr class="border">
                                    <th><?php echo lang('slides_image') ?></th>
                                    <th class="text-center"><?php echo lang('slides_quantity') ?></th>
                                    <th class="text-center"><?php echo lang('slides_name') ?></th>
                                    <th class="text-center"><?php echo lang('slides_show_start') ?></th>
                                    <th class="text-center"><?php echo lang('slides_show_end') ?></th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php for ($start; $start < $finish; $start++) { ?>
                                    <tr class="<?php echo \eMarket\Set::statusSwitchClass($lines[$start][9], [$this_time, strtotime($lines[$start][7])], [strtotime($lines[$start][8]), $this_time]) ?>">
                                        <td><img src="/uploads/images/slideshow/resize_0/<?php echo $lines[$start][3] ?>" /></td>
                                         <td class="text-center"><?php echo count(json_decode($lines[$start][2])) ?></td>
                                        <td class="text-center"><?php echo $lines[$start][5] ?></td>
                                        <td class="text-center"><?php echo \eMarket\Set::dateLocale($lines[$start][7]); ?></td>
                                        <td class="text-center"><?php echo \eMarket\Set::dateLocale($lines[$start][8]); ?></td>
                                        <td>
                                            <div class="flexbox">
                                                <!--Вызов модального окна для редактирования-->
                                                <div class="b-left">
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#index" data-edit="<?php echo $lines[$start][0] ?>"><span class="glyphicon glyphicon-edit"></span></button>
                                                </div>
                                                <form id="form_delete<?php echo $lines[$start][0] ?>" name="form_delete" action="javascript:void(null);" onsubmit="Ajax.callDelete('<?php echo $lines[$start][0] ?>')" enctype="multipart/form-data">
                                                    <input hidden name="delete" value="<?php echo $lines[$start][0] ?>">
                                                    <div>
                                                        <button type="submit" name="delete_but" class="btn btn-primary btn-xs" data-placement="left" data-toggle="confirmation" data-singleton="true" data-popout="true" data-btn-ok-label="<?php echo lang('confirm-yes') ?>" data-btn-cancel-label="<?php echo lang('confirm-no') ?>" title="<?php echo lang('confirm-del') ?>"><span class="glyphicon glyphicon-trash"> </span></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php
                if ($LANG_COUNT > 1) {
                    for ($x = 1; $x < $LANG_COUNT; $x++) {
                        $start = $navigate[0];
                        $finish = $navigate[1];
                        ?>

                        <div id="<?php echo lang('#lang_all')[$x] ?>" class="tab-pane fade">
                            <div class="table-responsive">
                                <table class="table table-hover">

                                    <thead>
                                        <tr>
                                            <th colspan="4">
                                                <?php if ($lines == TRUE) { ?>
                                                    <?php echo lang('with') ?> <?php echo $start + 1 ?> <?php echo lang('to') ?> <?php echo $finish ?> ( <?php echo lang('of') ?> <?php echo $count_lines; ?> )
                                                    <?php
                                                } else {
                                                    ?>
                                                    <?php echo lang('no_listing') ?>
                                                <?php } ?>
                                            </th>

                                            <th>
                                                <div class="flexbox">
                                                    <div class="b-left"><a href="#index" class="btn btn-primary btn-xs" data-toggle="modal"><span class="glyphicon glyphicon-plus"></span></a></div>
                                                    <form>
                                                        <input hidden name="route" value="<?php echo \eMarket\Valid::inGET('route') ?>">
                                                        <input hidden name="slide_lang" value="<?php echo \eMarket\Valid::inGET('slide_lang') ?>">
                                                        <input hidden name="backstart" value="<?php echo $start ?>">
                                                        <input hidden name="backfinish" value="<?php echo $finish ?>">
                                                        <div class="b-left">
                                                            <?php if ($start > 0) { ?>
                                                                <button type="submit" class="btn btn-primary btn-xs" formmethod="get"><span class="glyphicon glyphicon-chevron-left"></span></button>
                                                            <?php } else { ?>
                                                                <a type="submit" class="btn btn-primary btn-xs disabled"><span class="glyphicon glyphicon-chevron-left"></span></a>
                                                            <?php } ?>
                                                        </div>
                                                    </form>
                                                    <form>
                                                        <input hidden name="route" value="<?php echo \eMarket\Valid::inGET('route') ?>">
                                                        <input hidden name="slide_lang" value="<?php echo \eMarket\Valid::inGET('slide_lang') ?>">
                                                        <input hidden name="start" value="<?php echo $start ?>">
                                                        <input hidden name="finish" value="<?php echo $finish ?>">
                                                        <div>
                                                            <?php if ($finish != $count_lines) { ?>
                                                                <button type="submit" class="btn btn-primary btn-xs" formmethod="get"><span class="glyphicon glyphicon-chevron-right"></span></button>
                                                            <?php } else { ?>
                                                                <a type="submit" class="btn btn-primary btn-xs disabled"><span class="glyphicon glyphicon-chevron-right"></span></a>
                                                            <?php } ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </th>
                                        </tr>

                                        <tr class="border">
                                            <th><?php echo lang('slides_image') ?></th>
                                            <th class="text-center"><?php echo lang('slides_quantity') ?></th>
                                            <th class="text-center"><?php echo lang('slides_name') ?></th>
                                            <th class="text-center"><?php echo lang('slides_show_start') ?></th>
                                            <th class="text-center"><?php echo lang('slides_show_end') ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php for ($start; $start < $finish; $start++) { ?>
                                        <tr class="<?php echo \eMarket\Set::statusSwitchClass($lines[$start][9], [$this_time, strtotime($lines[$start][7])], [strtotime($lines[$start][8]), $this_time]) ?>">
                                                <td><img src="/uploads/images/slideshow/resize_0/<?php echo $lines[$start][3] ?>" /></td>
                                                <td class="text-center"><?php echo count(json_decode($lines[$start][2])) ?></td>
                                                <td class="text-center"><?php echo $lines[$start][5] ?></td>
                                                <td class="text-center"><?php echo \eMarket\Set::dateLocale($lines[$start][7]); ?></td>
                                                <td class="text-center"><?php echo \eMarket\Set::dateLocale($lines[$start][8]); ?></td>
                                                <td>
                                                    <div class="flexbox">
                                                        <!--Вызов модального окна для редактирования-->
                                                        <div class="b-left">
                                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#index" data-edit="<?php echo $lines[$start][0] ?>"><span class="glyphicon glyphicon-edit"></span></button>
                                                        </div>
                                                        <form id="form_delete<?php echo $lines[$start][0] ?>" name="form_delete" action="javascript:void(null);" onsubmit="Ajax.callDelete('<?php echo $lines[$start][0] ?>')" enctype="multipart/form-data">
                                                            <input hidden name="delete" value="<?php echo $lines[$start][0] ?>">
                                                            <div>
                                                                <button type="submit" name="delete_but" class="btn btn-primary btn-xs" data-placement="left" data-toggle="confirmation" data-singleton="true" data-popout="true" data-btn-ok-label="<?php echo lang('confirm-yes') ?>" data-btn-cancel-label="<?php echo lang('confirm-no') ?>" title="<?php echo lang('confirm-del') ?>"><span class="glyphicon glyphicon-trash"> </span></button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>