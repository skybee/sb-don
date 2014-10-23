<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');





function get_short_txt( $text, $length = 100 ){
        $text = strip_tags($text);
        return close_tags( mb_substr($text, 0, $length) ).'...';
    }
       
function close_tags($content){
        $position = 0;
        $open_tags = array();
        //теги для игнорирования
        $ignored_tags = array('br', 'hr', 'img');

        while (($position = strpos($content, '<', $position)) !== FALSE)
        {
            //забираем все теги из контента
            if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match))
            {
                $tag = strtolower($match[2]);
                //игнорируем все одиночные теги
                if (in_array($tag, $ignored_tags) == FALSE)
                {
                    //тег открыт
                    if (isset($match[1]) AND $match[1] == '')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]++;
                        else
                            $open_tags[$tag] = 1;
                    }
                    //тег закрыт
                    if (isset($match[1]) AND $match[1] == '/')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]--;
                    }
                }
                $position += strlen($match[0]);
            }
            else
                $position++;
        }
        //закрываем все теги
        foreach ($open_tags as $tag => $count_not_closed)
        {
            if( $count_not_closed < 0 ) $count_not_closed = 0;
            $content .= str_repeat("</{$tag}>", $count_not_closed);
        }

        return $content;
    }  
    
function get_rnd_block( $close_open, $int ){
        mt_srand( abs(crc32($_SERVER['HTTP_HOST']))*$int );
        $cnt_block  = mt_rand(2,8);
        mt_srand();
        
        $class_name     = str_ireplace('.', '_', $_SERVER['HTTP_HOST']);
        $html_str       = '';
        $html_tab       = '';
        
        if( $close_open == 'open' ){
            for($i=0; $i<$cnt_block; $i++){
                $html_tab   .= "\t";
                $html_str   .= $html_tab.'<div class="'.$class_name.'_'.$int.'_'.$i.'">'."\n";
            }
        }
        elseif( $close_open == 'close' ){
            for($i=0; $i<$cnt_block; $i++){
                $html_tab   .= "\t";
                $html_str   .= $html_tab.'</div>'."\n";
            }
        }
        
        return $html_str;
    }
    
function get_rnd_cssclass($classStr){
    $newClass = base64_encode( $classStr.$_SERVER['HTTP_HOST'] );
    $newClass = str_replace('=', '', $newClass);
    $newClass = strtolower($newClass);
    
    return $newClass;
}    
        
function get_cctv_pl_link( $seed_int ){
    
    $host_ar    = array(
        'house-control.org.ua'
    );
    $ancor_ar   = array(
        'Видеонаблюдение',
        'Домофоны',
        'Видеодомофоны',
        'Сигнализация',
        'Видеорегистраторы',
        'Камеры видеонаблюдения',
        'Домофоны и Видеодомофоны',
        'Системы видеонаблюдения',
        'Видеонаблюдение и Сигнализация',
        'Системы контроля и управления доступом',
        'Управление доступом',
        'Охранная сигнализация',
        'Пожарная сигнализация',
        'Установка видеонаблюдения',
        'Установка домофонов',
        'Установка сигнализации',
        'Видеорегистраторы для видеонаблюдения',
        'Интернет магазин видеонаблюдения',
        'Интернет магазин домофонов',
        'Интернет магазин видеодомофонов',
        'Интернет магазин сигнализации',
        'Интернет магазин видеорегистраторов',
        'IP Камеры наблюдения',
        'Магазин систем безопасности',
        'Видеонаблюдение в Украине',
        'Камеры видеонаблюдения в Украине',
        'Домофоны в Украине',
        'Видеодомофоны в Украине',
        'Видеонаблюдение в Киеве',
        'Магазин видеонаблюдения в Украине',
        'Магазин систем видеонаблюдения в Киеве',
        'Магазин видеонаблюдения в Харькове',
        'Магазин систем видеонаблюдения в Днепропетровске',
        'Магазин видеонаблюдения в Одессе',
        'Магазин систем видеонаблюдения в Донецке',
        'Магазин домофонов в Украине',
        'Магазин видеодомофонов в Киеве',
        'Магазин домофонов в Харькове',
        'Магазин видеодомофонов в Днепропетровске',
        'Магазин домофонов в Одессе',
        'Магазин видеодомофонов в Донецке'
        
    );
    
    srand($seed_int);
    
    $url    = 'http://'.$host_ar[rand( 0,count($host_ar)-1 )].'/';
    $ancor  = $ancor_ar[rand( 0,count($ancor_ar)-1 )];
    
    if( rand(0,1) )
        $link = '<a href="'.$url.'" >'.$ancor.'</a>';
    else
        $link = $ancor.' <a href="'.$url.'" >'.$url.'</a>.';
        
    srand();
    
    return $link;
}  

function get_hc_face_link(){
    $url_int = abs( crc32( $_SERVER['HTTP_HOST'] ) );
    
    if( $url_int%10 == 0 )
        return get_cctv_pl_link( $url_int );
    else 
        return ' ';
}

function get_article_url( $id, $host = false ){
    
    if( $host == false ){
        $host = $_SERVER['HTTP_HOST'];
    }
    
    $host = strtolower($host);
    
    $hostInt = abs(crc32($host));
    
    srand( $id );
    $rndInt = rand(1, 30);
    srand();
    
    $rndCat = base64_encode( $hostInt + $rndInt );
    
    $rndCat = str_replace('=', '', $rndCat);
    
    $url = '/'.$rndCat.'/'.$hostInt.$id.'/';
    
    return $url;
}

function get_doc_title($mainTitle, $secondTitle, $cntWord = 6){
    
    $pattern = "#\S{3,}\s#i";
    
    preg_match_all($pattern, $secondTitle, $secTtlAr);
    $secTtlAr   = $secTtlAr[0];
    $cntAllWord = count($secTtlAr);
    
    $secTtlStr = '';
    
    if( $cntAllWord > 0 ){
        for($i=0; $i<$cntWord && $i<$cntAllWord; $i++){
            $secTtlStr .= $secTtlAr[$i];
        }
        
        $mainTitle .= ' - '.$secTtlStr;
    }
    
    return $mainTitle;
    
}