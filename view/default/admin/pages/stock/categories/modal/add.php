<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

?>
<!-- Модальное окно "Добавить категорию" -->
<div id="add" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><div class="tooltip-right"><a href="#" ><span data-toggle="tooltip" data-placement="left" data-original-title="Заполните карточку категорий" class="glyphicon glyphicon-question-sign"></span></a>&nbsp;&nbsp;<button class="close" type="button" data-dismiss="modal">×</button></div>
                <h4 class="modal-title"><?php echo lang('title_' . $SET->titleDir() . '_index') ?></h4>
            </div>
            <form id="form_add" name="form_add" action="javascript:void(null);" onsubmit="call_add()">
                <div class="panel-body">
                    <input type="hidden" name="parent_id" value="<?php echo $parent_id ?>" />
                    <input type="hidden" name="add" value="ok" />
                    <!-- Языковые панели -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#<?php echo lang('#lang_all')[0] ?>"><img src="/view/<?php echo $SET->template() ?>/admin/images/langflags/<?php echo lang('#lang_all')[0] ?>.png" alt="<?php echo lang('#lang_all')[0] ?>" title="<?php echo lang('#lang_all')[0] ?>" width="16" height="10" /> <?php echo lang('language_name', lang('#lang_all')[0]) ?></a></li>

                        <?php
                        if (count(lang('#lang_all')) > 1) {
                            for ($xl = 1; $xl < count(lang('#lang_all')); $xl++) {

                                ?>

                                <li><a data-toggle="tab" href="#<?php echo lang('#lang_all')[$xl] ?>"><img src="/view/<?php echo $SET->template() ?>/admin/images/langflags/<?php echo lang('#lang_all')[$xl] ?>.png" alt="<?php echo lang('#lang_all')[$xl] ?>" title="<?php echo lang('#lang_all')[$xl] ?>" width="16" height="10" /> <?php echo lang('language_name', lang('#lang_all')[$xl]) ?></a></li>

                                <?php
                            }
                        }

                        ?>

                    </ul>
                    <div class="tab-content">
                        <div id="<?php echo lang('#lang_all')[0] ?>" class="tab-pane fade in active">
                            <div class="form-group">
                                <div class="input-group has-error">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
                                    <input class="input-sm form-control" placeholder="<?php echo lang('name') ?>" type="text" name="<?php echo lang('#lang_all')[0] ?>" />
                                </div>
                            </div>
                        </div>
                        <?php
                        if (count(lang('#lang_all')) > 1) {
                            for ($xl = 1; $xl < count(lang('#lang_all')); $xl++) {

                                ?>

                                <div id="<?php echo lang('#lang_all')[$xl] ?>" class="tab-pane fade">
                                    <div class="form-group">
                                        <div class="input-group has-error">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
                                            <input class="input-sm form-control" placeholder="<?php echo lang('name') ?>" type="text" name="<?php echo lang('#lang_all')[$xl] ?>" />
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        }

                        ?>
                    </div>
                    <div class="form-group">
                        <label for="image"><?php echo lang('images') ?>:</label><br>
                        <input type="file" name="image" id="image" /> <?php echo lang('max') ?>: <?php echo get_cfg_var('upload_max_filesize'); ?>
                    </div>
                    <div class="form-group">
                        <label for="view_category"><?php echo lang('display') ?> </label>
                        <input class="check-box" type="checkbox" name="view_cat" checked>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-xs" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-floppy-remove"></span> <?php echo lang('cancel') ?></button>
                    <button type="submit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-floppy-disk"></span> <?php echo lang('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- КОНЕЦ Модальное окно "Добавить категорию" -->