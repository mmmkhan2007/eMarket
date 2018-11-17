<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |    
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

?>
<?php if (isset($_SESSION['login']) && isset($_SESSION['pass'])) { // Выводим если авторизованы     ?>

    <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php for ($i = 0; $i < count($level); $i++) { ?>
                        <li>
                            <?php echo $level[$i]; ?>
                            <ul class="dropdown-menu">
                                <?php
                                if (!isset($menu[$i])) {
                                    $menu[$i] = array();
                                }
                                for ($x = 0; $x < count($menu[$i]); $x++) {

                                    ?>
                                    <li>
                                        <?php echo $menu[$i][$x]; ?>
                                        <ul class="dropdown-menu link">
                                            <?php
                                            if (!isset($submenu[$i][$x])) {
                                                $submenu[$i][$x] = array();
                                            }
                                            for ($y = 0; $y < count($submenu[$i][$x]); $y++) {

                                                ?>
                                                <li>
                                                    <?php echo $submenu[$i][$x][$y]; ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

<?php } ?>