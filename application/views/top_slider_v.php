<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="<?=get_rnd_cssclass('top_slider')?> top_slider"> 
<?  
    $cntNews = count($last_news);
    
    for($i=1; $i<=$cntNews; $i= $i+4 ):
?>
    
    <div class="<?=get_rnd_cssclass('slider_conteiner')?> slider_conteiner">
        <?
            $ii = 0;
            foreach ($last_news as $key=>$news):
                $news_url   = get_article_url($news['id']);
                $imgUrl     = '/sbimg' . $this->unicDomainStr . '/upload/images/medium/' . $news['main_img'];
        ?>
        <div class="<?=get_rnd_cssclass('slider_news_conteiner')?> slider_news_conteiner">
            <a href="<?=$news_url?>">
                <img src="<?=$imgUrl?>" alt="" onerror="imgError(this);" />
                <div class="<?=get_rnd_cssclass('slider_news_description')?> slider_news_description" >
                    <?=$news['title']?>
                </div>
            </a>
        </div>
        <? 
            unset( $last_news[$key] );
            $ii++;
            if($ii >= 4 ) break;
            endforeach; 
        ?>
    </div>
<? endfor; ?>
</div>