<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function get_city_link_ar( $rand_str, $file = 'city_link.txt', $cnt_link = 1 ){
    $link_ar = file( $file );
    $return_ar = array();
    mt_srand( abs( crc32($rand_str) ) );
    for($i=0; $i<$cnt_link; $i++)
    {
        $return_ar[] = $link_ar[mt_rand(0, count($link_ar)-1)];
    }
    mt_srand();
    
    return $return_ar;
}


function addLinkToNewsList($newsListAr, $linkAr, $delimiter = 3, $afterNews = 2){
    $cntLink    = count($linkAr);
    $cntNews    = count($newsListAr);
    
    for($i=0,$ii=0; $i<$cntNews; $i++)
    {
        if($i<$afterNews){ continue; }
        
        if($i%$delimiter == 0 && $ii<$cntLink)
        {
            $newsListAr[$i]['text'] .= $linkAr[$ii];
            $ii++;
        }
        
        if($ii>$cntLink) { break;}
    }
    
    return $newsListAr;
}