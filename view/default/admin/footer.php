<?php
/****** Copyright © 2018 eMarket ******* 
*   GNU GENERAL PUBLIC LICENSE v.3.0   *    
* https://github.com/musicman3/eMarket *
***************************************/
?>

    <?php 	if(isset($_SESSION['login']) && isset($_SESSION['pass'])){ // Выводим если авторизованы ?>

    <div id="footerwrap">
        <footer class="clearfix"></footer>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p><img src="/view/default/admin/images/emarket.png" width="80" alt="" class="img-responsive center-block"></p>

                    <p>Copyright (c) 2018-<?php echo date('Y') ?>, <a href="#">eMarket Team</a>. All rights reserved.</p>
                </div>
            </div><!-- /row -->
        </div><!-- /container -->
    </div><!-- /footerwrap -->
    
<?php } ?>

<!-- /сортировка мышкой -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#sort-list").sortable({
            items: 'tr',
            handle: 'td',
            stop: function(event,ui){ sortList(); }
        });
    });

    function sortList(){
        var ids = [];
        $("#sort-list tr").each(function(){ ids[ids.length] = $(this).attr('unitid'); });
        $.ajax({
            method: 'POST',
            dataType: 'text',
            url: '/controller/admin/pages/categories/categories.php',
            data: ({ ids: ids.join() })
        });
    }
</script>