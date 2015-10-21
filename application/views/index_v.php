<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="list_category unique_margin_bottom">
    <div class="heading"><h2>Последние Новости</h2></div>
</div>

<?php 
    foreach ($last_news as $news):
    
    $news_url = get_article_url($news['id']);
    $dateAr = & $news['date_ar'];
    $dateStr = $dateAr['day_str'] . ' &nbsp;' . $dateAr['time'] . ', &nbsp;&nbsp;' . $dateAr['day_nmbr'] . ' ' . $dateAr['month_str'] . ' ' . $dateAr['year_nmbr'];

    if (!empty($news['main_img']))
        $imgUrl = '/sbimg' . $this->unicDomainStr . '/upload/images/medium/' . $news['main_img'];
    else
        $imgUrl = '/sbimg' . $this->unicDomainStr . '/img/default_news.jpg';
?>

    <?=get_rnd_block('open', 55)?>
    <div class="<?=get_rnd_cssclass('list_post')?> list_post" >
        <div class="<?=get_rnd_cssclass('image')?> image">
            <a href="<?= $news_url ?>" >
                <img src="<?= $imgUrl ?>" alt="<?= str_replace('"', " ", $news['title']) ?>" width="211" height="150" class="imgf" onerror="imgError(this);" />
            </a>
        </div>
        <div class="<?=get_rnd_cssclass('information')?> information">
            <h3>
                <a href="<?= $news_url ?>"> <?= $news['title'] ?> </a>
            </h3>
            <span class="date">
                <?= $dateStr ?>
            </span>
            <p>
                <?= $news['text'] ?>[...]
            </p>
        </div>
    </div>
    <?=get_rnd_block('close', 55)?>

<?php endforeach; ?>

<p class="<?=get_rnd_cssclass('index_donor')?> index_donor">
    Источник: <a href="http://odnako.su/<?php if(isset($cat_ar['full_uri'])) echo $cat_ar['full_uri'];?>">Odnako.su</a>
</p>