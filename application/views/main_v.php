<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?=$data['title']?></title>

        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>colorpicker.css"  />
        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>stylesheet.css"  />
        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>style.css"  />
        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>firstnavigation.css"  />
        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>secondnavigation.css"  />
        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>carousel.css"  />
        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>tipTip.css"  />
        <link rel="stylesheet" type="text/css" href="/css/<?=$_SERVER['HTTP_HOST'].".stl."?>tabs.css"  />
        
        <link rel="stylesheet" type="text/css" href="/css/jquery.bxslider.css"  />
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="/js/jquery.bxslider.min.js" ></script>
        <script type="text/javascript" src="/js/sb.js" ></script>

        <!--        <link rel="stylesheet" type="text/css" href="/css/featured_long_slider.css"  />
                <link rel="stylesheet" type="text/css" href="/css/prettyPhoto.css"  />-->

    </head>

    <body>
        
        <?=get_rnd_block('open', 1)?>
        <?=get_rnd_block('close', 1)?>

        <div id="header_navigation" class="<?=get_rnd_cssclass('header_navigation')?>">

            <div id="top_navigation" class="<?=get_rnd_cssclass('top_navigation')?>">


                <?php if(0):?>
                <ul class="<?=get_rnd_cssclass('firstnav-menu')?> firstnav-menu">
                    <?php foreach ($main_menu_list as $main_link): ?>
                        <li class="page_item page-item-372" catname="<?= $main_link['url_name']?>">
                            <a href="/<?= $main_link['url_name'] ?>/"><?= $main_link['name'] ?></a>
                            <div class="firstnav-menu-arrow"></div>
                        </li>
                    <?php endforeach; ?>
                </ul><!-- #first-menu -->
                <?php endif; ?>

                <div class="header_right_side">

                    <p>Wednesday, Aug. 20, 2014</p>

                    <ul><li><a href="#" ><img src="/img/header-rss-icon.png"  class="rss_icon" alt="Follow our feed" />Subscribe to rss</a></li></ul>

                </div><!--Right Side -->

            </div><!-- #top_navigation -->

        </div><!-- #header_navigation -->

        <div id="header_container" class="<?=get_rnd_cssclass('header_container')?>" >

            <div id="header" class="<?=get_rnd_cssclass('header')?>">

                <a href="#" ><img src="/img/logo.png"  width="262" height="66" class="logo" alt="Broadcast"/></a>
                <div class="top_ad "><img src="/img/468-ad.png"  alt="468 X 60" /></div><!-- #ad 468x60 closer -->
            </div><!-- #header -->

        </div><!-- #header_container -->

        <div id="categories_container" class="<?=get_rnd_cssclass('categories_container')?>">

            <div id="categories" class="<?=get_rnd_cssclass('categories-id')?>">
                <ul id="menu-shawn" class="secondnav-menu <?=get_rnd_cssclass('secondnav-menu')?>">
                    <?php foreach($catlist as $cat): ?>
                    <!-- <li class="<?php#=get_rnd_cssclass('categories')?>"><a href="/cat/<?php#=$cat['url_name']?>/" ><?php#=$cat['name']?></a></li> -->
                    <li class="<?=get_rnd_cssclass('categories')?>"><a href="#" ><?=$cat['name']?></a></li>
                    <?php endforeach; ?>
                </ul>                    
            </div><!-- #categories -->

        </div><!-- categories container -->

        
        <div id="center" class="<?=get_rnd_cssclass('center')?>">
            <?=get_rnd_block('open', 2)?>
            <div id="container" class="<?=get_rnd_cssclass('container')?>">

                <div id="content">
                    <?=$top_slider?>

                    <div id="content_bg" class="<?=get_rnd_cssclass('content_bg')?>">


                        <div id="left" >
                            <?=get_rnd_block('open', 4)?>
                            <?=$content?>
                            <?=get_rnd_block('close', 4)?>
                        </div><!-- #left -->

                        <div id="right">

                            <div id="search-2" class="widget-container widget_search rightwidget">
                                <form method="get" id="searchform" action="">
                                    <input type="text" class="search"  name="search" id="s" value="" />
                                    <input type="submit" class="searchb" value="" />
                                </form></div><!-- #right_widget -->
                                
                            <?=get_rnd_block('open', 3)?>    
                            <div id="londontabs" class="<?=get_rnd_cssclass('widget')?> widget">
                                <div id="populartab" class="<?=get_rnd_cssclass('tabdiv')?> tabdiv ui-tabs-panel" style="display: block; "><!-- #popular -->
                                    <?=$right_news?>
                                </div><!-- #commentstab -->
                            </div><!-- #tabs -->
                            <?=get_rnd_block('close', 3)?>


                        </div><!-- #content_bg -->
                    </div><!-- #content -->
                </div><!-- #container -->
            </div><!-- #center -->
            <?=get_rnd_block('close', 2)?>
            
            <div id="footer_holder" class="<?=get_rnd_cssclass('footer_holder')?>" >
                <div id="footer" class="<?=get_rnd_cssclass('footer')?>" >

                </div><!-- #footer -->

            </div><!-- #footer_holder -->

            <div id="copyright_holder">

                <div class="copyright">

                    <div class="left">&copy; <?=date("Y")?> SB News. All Rights Reserved.</div><!-- #left -->

                    <div class="right">Powered by Wordpress.</div><!-- #right -->

                </div><!-- #copyright -->

            </div><!-- #copyright_holder -->

    </body>
</html>
