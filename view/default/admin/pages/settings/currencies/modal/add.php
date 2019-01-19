<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

?>
<!-- Модальное окно "Добавить" -->
<div id="add" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><div class="tooltip-right"><a href="#" ><span data-toggle="tooltip" data-placement="left" data-original-title="Сокращенное наименование указывается любыми символами" class="glyphicon glyphicon-question-sign"></span></a>&nbsp;&nbsp;<button class="close" type="button" data-dismiss="modal">×</button></div>
                <h4 class="modal-title"><?php echo lang('title_' . $SET->titleDir() . '_index') ?></h4>
            </div>
            <form id="form_add" name="form_add" action="javascript:void(null);" onsubmit="callAdd()">
                <div class="panel-body">
                    <input type="hidden" name="add" value="ok" />

                    <!-- Языковые панели -->
                    <?php require_once(ROOT . '/view/' . $SET->template() . '/layouts/lang_tabs_add.php') ?>

                    <!-- Содержимое языковых панелей -->
                    <div class="tab-content">
                        <div id="<?php echo lang('#lang_all')[0] ?>" class="tab-pane fade in active">
                            <div class="form-group">
                                <div class="input-group has-error">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
                                    <input class="input-sm form-control" placeholder="<?php echo lang('name_full') ?>" type="text" name="<?php echo $SET->titleDir() . '_' . lang('#lang_all')[0] ?>" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group has-error">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                                    <input class="input-sm form-control" placeholder="<?php echo lang('name_little') ?>" type="text" name="code<?php echo lang('#lang_all')[0] ?>" id="code<?php echo lang('#lang_all')[0] ?>" required />
                                </div>
                            </div>
                        </div>

                        <?php
                        if ($LANG_COUNT > 1) {
                            for ($x = 1; $x < $LANG_COUNT; $x++) {

                                ?>

                                <div id="<?php echo lang('#lang_all')[$x] ?>" class="tab-pane fade">
                                    <div class="form-group">
                                        <div class="input-group has-error">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
                                            <input class="input-sm form-control" placeholder="<?php echo lang('name_full') ?>" type="text" name="<?php echo $SET->titleDir() . '_' . lang('#lang_all')[$x] ?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group has-error">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                                            <input class="input-sm form-control" placeholder="<?php echo lang('name_little') ?>" type="text" name="code<?php echo lang('#lang_all')[$x] ?>" id="code<?php echo lang('#lang_all')[$x] ?>" required />
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        }

                        ?>
                        <div class="form-group">
                            <div class="input-group has-success">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                                <input class="input-sm form-control" placeholder="<?php echo lang('currency_symbol') ?>" type="text" name="symbol" id="symbol" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group has-success">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                                <select name="symbol_position" id="symbol_position" class="input-sm form-control">
                                    <option value="right"><?php echo lang('symbol_right') ?></option>
                                    <option value="left"><?php echo lang('symbol_left') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group has-error">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-order"></span></span>
                                <input class="input-sm form-control" placeholder="<?php echo lang('decimal_places') ?>" type="text" pattern="[0-9]{0,1}" name="decimal_places" id="decimal_places" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group has-error">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-order"></span></span>
                                <input class="input-sm form-control" placeholder="<?php echo lang('value') ?>" type="text" pattern="\d+(\.\d{0,10})?" name="value" id="value" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default_value"><?php echo lang('default_set') ?> </label>
                            <input class="check-box" name="default_value" type="checkbox" checked>
                        </div>
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
<!-- КОНЕЦ Модальное окно "Добавить" -->