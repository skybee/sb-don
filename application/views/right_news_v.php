<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<? 
    foreach ($last_news as $news):
    
    $news_url = get_article_url($news['id']);
    $dateAr = & $news['date_ar'];
    $dateStr = $dateAr['day_str'] . ' &nbsp;' . $dateAr['time'] . ', &nbsp;&nbsp;' . $dateAr['day_nmbr'] . ' ' . $dateAr['month_str'] . ' ' . $dateAr['year_nmbr'];

    if (!empty($news['main_img']))
        $imgUrl = '/sbimg' . $this->unicDomainStr . '/upload/images/small/' . $news['main_img'];
    else
        $imgUrl = '/sbimg' . $this->unicDomainStr . '/img/default_news.jpg';
?>

<div class="<?=get_rnd_cssclass('tab_inside')?> tab_inside">
    <a href="<?=$news_url?>" >
        <img src="<?=$imgUrl?>"  alt="<?= str_replace('"', " ", $news['title']) ?>" onerror="imgError(this);" />
    </a>
    <div class="<?=get_rnd_cssclass('content')?> content">
        <h6><a href="<?=$news_url?>" ><?=$news['title']?></a></h6> 
        <!--<span class="date">January 03,  2011</span>-->
    </div>
</div>

<? endforeach; ?>