<?php
/* =-=-=-= Copyright © 2018 eMarket =-=-=-=  
  |    GNU GENERAL PUBLIC LICENSE v.3.0    |
  |  https://github.com/musicman3/eMarket  |
  =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

use \eMarket\Admin\Slideshow;
use \eMarket\Core\Settings;

Slideshow::view();

if (Slideshow::$slideshow == true) {
    ?>
    <div class="container-fluid">
        <div id="Carousel" class="carousel slide hidden-xs" 
             data-interval="<?php echo Slideshow::$slide_interval ?>" 
             data-pause="<?php echo Slideshow::$slide_pause ?>" 
             data-ride="<?php echo Slideshow::$autostart ?>" 
             data-wrap="<?php echo Slideshow::$cicles ?>">
                 <?php if (Slideshow::$indicators == 'true') { ?>
                <ol class="carousel-indicators">
                    <li data-target="#Carousel" data-slide-to="0" class="active"></li>
                    <?php for ($x = 1; $x < count(Slideshow::$slideshow_array); $x++) { ?>
                        <li data-target="#Carousel" data-slide-to="<?php echo $x ?>"></li>
                    <?php } ?>
                </ol>
            <?php } ?>
            <div class="carousel-inner">
                <?php
                foreach (Slideshow::$slideshow as $images_data) {
                    foreach (json_decode($images_data['logo'], 1) as $logo) {
                        if ($images_data['status'] == 1 && strtotime($images_data['date_start']) <= Slideshow::$this_time && strtotime($images_data['date_finish']) >= Slideshow::$this_time) {
                            ?>
                            <div class="item <?php echo Settings::activeTab(0, 0) ?>">
                                <a href="<?php echo $images_data['url'] ?>">
                                    <img src="/uploads/images/slideshow/resize_4/<?php echo $logo ?>" class="center-block" >
                                    <?php if ($images_data['animation'] == 1) { ?>
                                        <div class="carousel-caption slide-text-anime" style="color: <?php echo $images_data['color'] ?>;">
                                            <h3><?php echo $images_data['name'] ?></h3>
                                            <p><?php echo $images_data['heading'] ?></p>
                                        </div>
                                    <?php } else { ?>
                                        <div class="carousel-caption" style="color: <?php echo $images_data['color'] ?>;">
                                            <h3><?php echo $images_data['name'] ?></h3>
                                            <p><?php echo $images_data['heading'] ?></p>
                                        </div>
                                    <?php } ?>
                                </a>
                            </div>

                            <?php
                        }
                    }
                }
                ?>
            </div>
            <?php if (Slideshow::$navigation_key == 'true') { ?>
                <a class="carousel-control left" href="#Carousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="carousel-control right" href="#Carousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            <?php } ?>
        </div>
    </div>
    <?php
}