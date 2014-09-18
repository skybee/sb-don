<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?=$data['title']?></title>

        <link rel="stylesheet" type="text/css" href="/css/colorpicker.css"  />
        <link rel="stylesheet" type="text/css" href="/css/stylesheet.css"  />
        <link rel="stylesheet" type="text/css" href="/css/style.css"  />
        <link rel="stylesheet" type="text/css" href="/css/firstnavigation.css"  />
        <link rel="stylesheet" type="text/css" href="/css/secondnavigation.css"  />
        <link rel="stylesheet" type="text/css" href="/css/carousel.css"  />
        <link rel="stylesheet" type="text/css" href="/css/tipTip.css"  />
        <link rel="stylesheet" type="text/css" href="/css/tabs.css"  />
        
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


                <ul class="<?=get_rnd_cssclass('firstnav-menu')?> firstnav-menu">

                    <li class="page_item page-item-1733"><a href="#"  title="Archive">Archive</a></li>
                    <li class="page_item page-item-1736"><a href="#"  title="Features">Features</a></li>
                    <li class="page_item page-item-1739"><a href="#"  title="Contact">Contact</a></li>
                    <li class="page_item page-item-1804"><a href="#"  title="Full Width">Full Width</a></li>

                    <li><a href="#">Drop Down</a><ul><li><a href="#">Drop 1</a></li><li><a href="#">Drop 2</a></li> <li><a href="#">Drop 3</a></li></ul></li>


                </ul><!-- #first-menu -->

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

            <div id="categories">
                <ul id="menu-shawn" class="secondnav-menu sf-js-enabled sf-menu"><li id="menu-item-2052" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-2052"><a href="#" >Business</a></li>
                    <li id="menu-item-2053" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-2053"><a href="#" >Health</a></li>
                    <li id="menu-item-2054" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-2054"><a href="#" >Movies</a></li>
                    <li id="menu-item-2055" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-2055"><a href="#" >Opinions</a></li>
                    <li id="menu-item-2057" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-2057"><a href="#" >Sports</a></li>
                    <li id="menu-item-2058" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-2058"><a href="#" >Technology</a></li>
                    <li id="menu-item-2059" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-2059"><a href="#" >World</a></li>
                    <li id="menu-item-2060" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2060"><a href="#">Drop Menu</a>
                        <ul class="sub-menu">
                            <li id="menu-item-2061" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2061"><a href="#">Drop Menu</a></li>
                            <li id="menu-item-2062" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2062"><a href="#">Drop Menu</a></li>
                            <li id="menu-item-2063" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2063"><a href="#">Drop Menu</a></li>
                        </ul>
                    </li>
                </ul>                    </div><!-- #categories -->

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
                    <div class="column_1">
                        <div id="lt_social_icons-3" class="widget"><div class="heading"><h5>Connect with us</h5></div>     
                            <ul id="social_icons">
                                <li><a href="#" ><img src="/img/twitter_social_icon.png"  width="28" height="28" class="socialimg imgf"/><span class="socialtext">Twitter</span></a></li>

                                <li><a href="#" ><img src="/img/facebook_social_icon.png"  width="28" height="28" class="socialimg imgf"/><span class="socialtext">Facebook</span></a></li>

                                <li><a href="#" ><img src="/img/rss_social_icon.png"  width="28" height="28" class="socialimg imgf"/><span class="socialtext">RSS Feed</span></a></li>

                                <li><a href="#" ><img src="/img/vimeo_social_icon.png"  width="28" height="28" class="socialimg imgf"/><span class="socialtext">Vimeo</span></a></li>  
                            </ul>
                        </div><!-- #widget -->
                    </div><!-- #column_1 -->
                    <div class="column_2">
                        <div id="lt_sidebar_news_widget-3" class="widget"><div class="heading"><h5>Latest News</h5></div>           <div class="latest_news">
                                <div class="image">
                                    <a href="#" ><img src="/img/thumb.php-src=http---londonthemes.com-themes-broadcast-wp-content-uploads-2011-07-business-tips.png&w=67&h=53&zc=1&q=100.png"  width="67" height="53" alt="Business tips to help you create one successful business." class="imgf"/></a>
                                </div><!-- #image -->
                                <div class="content"><h3><a href="#" >Business tips to help you create one successful business.</a></h3><span class="date">July 03,  2011</span></div><!-- #content -->
                            </div><!-- #latest_news -->
                            <div class="latest_news">
                                <div class="image">
                                    <a href="#" ><img src="/img/thumb.php-src=http---londonthemes.com-themes-broadcast-wp-content-uploads-2011-07-lakers-.png&w=67&h=53&zc=1&q=100.png"  width="67" height="53" alt="Lakers thinks kobe should see limited minutes to be more effective on the court." class="imgf"/></a>
                                </div><!-- #image -->
                                <div class="content"><h3><a href="#" >Lakers thinks kobe should see limited minutes to be more effective on the court.</a></h3><span class="date">July 03,  2011</span></div><!-- #content -->
                            </div><!-- #latest_news -->
                        </div><!-- #widget -->
                    </div><!-- #column_2 -->
                    <div class="column_3">
                        <div id="text-3" class="widget"><div class="heading"><h5>Text Widget</h5></div>			<div class="textwidget"><p>Maecenas mattis, tortor ut posuere aliquam, quam enim accumsan purus, auctor placerat orci velit vitae massa. Vivamus non iaculis lectus.</p>

                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ac felis quis purus egestas laoreet. Mauris placerat feugiat augue ut viverra.</p></div>
                        </div><!-- #widget -->
                    </div><!-- #column_3 -->

                    <div class="column_4">

                        <div id="pages-3" class="widget"><div class="heading"><h5>Pages</h5></div>		<ul>
                                <li class="page_item page-item-1733"><a href="#"  title="Archive">Archive</a></li>
                                <li class="page_item page-item-1739"><a href="#"  title="Contact">Contact</a></li>
                                <li class="page_item page-item-1736"><a href="#"  title="Features">Features</a></li>
                                <li class="page_item page-item-1804"><a href="#"  title="Full Width">Full Width</a></li>
                            </ul>
                        </div><!-- #widget -->
                    </div><!-- #column_4 -->

                </div><!-- #footer -->

            </div><!-- #footer_holder -->

            <div id="copyright_holder">

                <div class="copyright">

                    <div class="left">&copy; 2011 Broadcast. All Rights Reserved.</div><!-- #left -->

                    <div class="right">Powered by Wordpress. Designed by<a href="#" > Skyali</a></div><!-- #right -->

                </div><!-- #copyright -->

            </div><!-- #copyright_holder -->

    </body>
</html>
