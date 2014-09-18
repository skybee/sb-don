<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="page_heading blog_single_header no_margin_top">
    <h1><?=$doc['title']?></h1>
</div>

<div class="<?=get_rnd_cssclass('blog_date')?> blog_date">
    <div class="the_date">
        <p>
            Источник:  
            <a href="/" rel="<?=$doc['d_host']?>" class="d_host" ><?=$doc['d_name']?></a>
        </p> 
        <span class="<?=get_rnd_cssclass('seperator')?> seperator">|</span>   
        <p>
            <?= $doc['date_ar']['day_nmbr'].' '.$doc['date_ar']['month_str'].' '.$doc['date_ar']['year_nmbr']?>
        </p> 
        <span class="seperator">|</span>  
    </div><!-- #the_date -->

</div><!-- #date -->


<div class="<?=get_rnd_cssclass('pure_content')?> pure_content">
    <?=get_rnd_block('open', 8)?>
    
    <? if( !empty($doc['main_img']) ):
        $imgUrl = '/sbimg' . $this->unicDomainStr . '/upload/images/medium/' . $doc['main_img'];
    ?>
    <div class="<?=get_rnd_cssclass('thumb_main')?> thumb_main">
        <img src="<?=$imgUrl?>" alt="<?= str_replace('"', " ", $doc['title']) ?>" class="doc_main_img" onerror="imgError(this);"  />
    </div>
    <? endif; ?>
    <span class="copy_url">
    <?=$doc['text']?>
    </span>
    
    <div class="<?=get_rnd_cssclass('list_category')?> list_category like_news_title">    
        <div class="<?=get_rnd_cssclass('heading')?> heading"><h2>Похожие Новости:</h2></div>
    </div>

    <? 
        foreach($like_doc as $l_doc ): 
            
            $news_url = get_article_url($l_doc['id']);
            $dateAr = & $l_doc['date_ar'];
            $dateStr = $dateAr['day_str'] . ' &nbsp;' . $dateAr['time'] . ', &nbsp;&nbsp;' . $dateAr['day_nmbr'] . ' ' . $dateAr['month_str'] . ' ' . $dateAr['year_nmbr'];

            if (!empty($l_doc['main_img']))
                $imgUrl = '/sbimg' . $this->unicDomainStr . '/upload/images/small/' . $l_doc['main_img'];
            else
                $imgUrl = '/sbimg' . $this->unicDomainStr . '/img/default_news.jpg';
        
    ?>
        <div class="<?=get_rnd_cssclass('list_post')?> list_post like_news_list">
            <div class="<?=get_rnd_cssclass('image')?> image">
                <a href="<?= $news_url ?>" >
                    <img src="<?= $imgUrl ?>" alt="<?= str_replace('"', " ", $l_doc['title']) ?>" class="imgf" onerror="imgError(this);" />
                </a>
            </div>
            <div class="<?=get_rnd_cssclass('information')?> information">
                <h3>
                    <a href="<?= $news_url ?>"> <?= $l_doc['title'] ?> </a>
                </h3>
                <span class="date">
                    <?= $dateStr ?>
                </span>
                
            </div>
            <p>
               <?= $l_doc['text'] ?>[...]
            </p>
        </div>
    
    <? endforeach; ?>

    
    <?=get_rnd_block('open', 9)?>
    <p class="<?=get_rnd_cssclass('index_donor')?> index_donor">
        Источник: <a href="<?=$rand_donor?>"><?=$rand_donor?></a>
    </p>
    <?=get_rnd_block('close', 9)?>
    <?=get_rnd_block('close', 8)?>
</div>