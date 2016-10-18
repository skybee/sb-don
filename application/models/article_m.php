<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_m extends CI_Model{
    
    function __construct() {
        parent::__construct();
        
        $this->donorRel = '';
    }
    
    function get_last_news( $cnt = 1, $txt_length = 100, $img = false ){
        
        if ($img)
            $img_sql = " WHERE `main_img` != '' ";
        else
            $img_sql = "";
        
        $sql = "SELECT * FROM 
                    (SELECT * FROM 
                        (SELECT * FROM `article` {$img_sql} ORDER BY `date` DESC LIMIT 200 ) AS `t1` 
                    ORDER BY RAND({$this->unicDomainInt}) 
                    LIMIT {$cnt} ) AS `t2`
                ORDER BY `date` DESC ";

        $query = $this->db->query($sql);

        $result_ar = array();
        foreach ($query->result_array() as $row) {
            
            $row['date_ar'] = get_date_str_ar($row['date']);
            $row['text']    = $this->get_short_txt( $row['text'], $txt_length );  
            
            $result_ar[] = $row;
        }
        
        return $result_ar;
    }
    
    function get_short_txt( $text, $length = 100 ){
        $text = strip_tags($text);
        $text = mb_substr($text, 0, $length);
        $text = preg_replace("#\s[^\s]*$#i", " \n", $text);
        $text = preg_replace("#\.[^\.]*$#i", ". \n", $text);
        
        return $text;
    }
    
    function get_last_right_news( $cnt, $ident = 'index' ){
        
        $cacheName = 'last_right_news_'.$this->unicDomainStr.'_'.$ident;
        
        if( !$lastNewsCache = $this->cache->file->get($cacheName) ){
            $data = $this->get_last_news( $cnt, 0 );
            $this->cache->file->save($cacheName, $data, $this->RightNewsCacheTime );
        }
        else
            $data = $lastNewsCache;
        
        return $data;    
    }
    
    function get_slider_news( $cnt ){
        
        $cacheName = 'slider_news_'.$this->unicDomainStr;
        
        if( !$lastNewsCache = $this->cache->file->get($cacheName) ){
            $data = $this->get_last_news( $cnt, 0, true );
            $this->cache->file->save($cacheName, $data, $this->sliderCacheTime );
        }
        else
            $data = $lastNewsCache;
        
        return $data;
    }
    
    function get_doc_data( $id ){
        $id = (int) $id;
        $query = $this->db->query(" SELECT  `article`.*, 
                                            `category`.`name` AS 'cat_name', `category`.`full_uri` AS 'cat_full_uri',  
                                            `donor`.`name` AS 'd_name', `donor`.`host` AS 'd_host',
                                            `scan_url`.`url` AS 'scan_url'
                                    FROM 
                                        `article`, `category`, `donor`, `scan_url`
                                    WHERE 
                                        `article`.`id`  = {$id}
                                        AND
                                        `category`.`id` = `article`.`cat_id`
                                        AND
                                        `article`.`donor_id` = `donor`.`id`
                                        AND
                                        `scan_url`.`id` = `article`.`scan_url_id` 
                                  ");
        
        if( $query->num_rows() < 1 ) return FALSE; 
        
        $returnAr = $query->row_array();
        $returnAr['date_ar']    = get_date_str_ar( $returnAr['date'] );
        $returnAr['text']       = str_ireplace('/upload/images/real/', '/sbimg'.$this->unicDomainStr.'/upload/images/real/', $returnAr['text']); 
        
        return $returnAr;
    }
    
    function get_like_articles( $id, $text, $cntNews = 4, $dayPeriod = false, $newsDate = false  ){
        $cleanPattern = "#(['\"\,\.\\\]+|&\w{2,6};)#i";
        $text = preg_replace($cleanPattern, ' ', $text);
        
        if( $dayPeriod && $newsDate ){
            
            $intNewsDate = strtotime( $newsDate );
            
            $dateStart  = date("Y-m-d H:i:s", strtotime(" -{$dayPeriod} day", $intNewsDate ) );
            $dateStop   = date("Y-m-d H:i:s", strtotime(" +{$dayPeriod} day", $intNewsDate ) );
            
            $dateSql = " (`date` > '{$dateStart}' AND `date` < '{$dateStop}') ";
        }
        else{
            $dateSql = '';
        }
        
        $sql = "SELECT * FROM
                    (SELECT * FROM
                        (SELECT `article`.* FROM `article` 
                        WHERE 
                            {$dateSql}  
                        AND 
                            `article`.`id` != '{$id}' 
                        AND    
                            MATCH (`article`.`title`,`article`.`text`) AGAINST ('{$text}')
                        LIMIT 100 ) AS `t1`
                    ORDER BY RAND({$this->unicDomainInt}) 
                    LIMIT {$cntNews} ) AS `t2`    
                ORDER BY `date` DESC";       
                
        $query = $this->db->query( $sql );
        
        if( $query->num_rows() < 1 ){ return NULL; }
        
        $result = array();
        foreach( $query->result_array() as $row ){
            $text_length    = $this->get_text_length_for_like_news( $row['id'] );
            
            $row['text']    = $this->get_short_txt( $row['text'], $text_length );
            $row['date_ar'] = get_date_str_ar( $row['date'] );
            $result[] = $row;
        }
        
        return $result;
    }
    
    private function get_text_length_for_like_news( $int ){
        $seed = $int + $this->unicDomainInt;
        
        srand($seed);
        
        return rand(300, 700);
    }
    
    function get_rand_donor( &$doc_data ){
        $seed = $doc_data['id'] + $this->unicDomainInt;
        
        mt_srand( $seed );
        $randInt    = mt_rand(1, 1000);
        $rndRel     = mt_rand(1, 1000);
        mt_srand();
        
        if( $randInt <= 200 ){
            $url = $this->get_odnako_donor_url( $doc_data );
        }
        elseif( $randInt > 200 && $randInt <= 400 ){
            $url = $this->get_real_donor_url( $doc_data );
        }
        else{
            $url = $this->get_rand_satellite_donor_url( $doc_data );
        }
        
        if( $rndRel <= 300 ){
            $this->donorRel = ' rel="nofollow" ';
        }
        
        return $url;
    }
    
    function get_donor_rel(){
        return $this->donorRel;
    }
    
    private function get_real_donor_url( &$doc_data ){
        $sql = "SELECT `url` FROM `scan_url` WHERE `id` = '{$doc_data['scan_url_id']}' LIMIT 1";
        $query = $this->db->query($sql);
        
        $row = $query->row();
        return $row->url;
    }
    
    private function get_rand_satellite_donor_url( &$doc_data ){
        
        $donor_domain = $this->get_donor_domain();
        
        $seed = round( $doc_data['id'] + $this->unicDomainInt / 3 );

        mt_srand( $seed );
        
        $cntAr  = count($donor_domain) - 1;
        
        $rndInt = round( mt_rand(0, $cntAr*1000 ) / 1000 );
        
        $domain = $donor_domain[ $rndInt ];
        mt_srand();
        
        $host = strtolower($domain['subname'].'.'.$domain['name']);
        
        $url = 'http://'.$host.get_article_url($doc_data['id'], $host);
        
        return $url;
    }

    private function get_odnako_donor_url( &$doc_data ){
        $url = 'http://'.$this->mainHost.'/'.$doc_data['cat_full_uri'].'-'.$doc_data['id'].'-'.$doc_data['url_name'].'/';
        return $url;
    }

    private function get_donor_domain(){
        
        $cacheName = 'donor_domain';
        
        $sql    = "SELECT `donor_subdomain`.`subname`, `donor_domain`.`name`"
                . "FROM `donor_subdomain`, `donor_domain`"
                . " WHERE "
                . "`donor_domain`.`id` = `donor_subdomain`.`donor_domain_id`"
                . "AND"
                . "`donor_domain`.`work` = 'yes' ";
        
        $result = array();
        
        if( !$result = $this->cache->file->get($cacheName) ){
            $query = $this->db->query($sql);
            
            foreach ($query->result_array() as $row)
                $result[] = $row;
            
            if(count($result) > 1)
                $this->cache->file->save($cacheName, $result, $this->donorDomainCacheTime );
        }
        
        return $result;
    }
    
    function get_catlist_from_catid( $id ){
        
        $cacheName = 'catlist_'.$id;
        
        if( !$catListCache = $this->cache->file->get($cacheName) ){
            $sql = "SELECT "
                    . "`category`.`id`, `category`.`url_name`, `category`.`name`, `category`.`parent_id` "
                    . "FROM "
                    . "`category`, (SELECT `parent_id` FROM `category` WHERE `category`.`id` = {$id} LIMIT 1 ) AS `t1` "
                    . "WHERE "
                    . "`category`.`parent_id` = `t1`.`parent_id` "
                    . "ORDER BY `category`.`sort` ";

            $query = $this->db->query( $sql );

            if( $query->num_rows() < 1 ) return NULL;

            $data = array();

            foreach( $query->result_array() as $row ){
                $data[] = $row;
            }
            $this->cache->file->save($cacheName, $data, $this->catListCacheTime );
        }
        else
            $data = $catListCache;
        
        return $data;
    }
    
    
    function get_page_list( $cat_id, $page, $cnt = 15, $text_len = 200 ){
        $stop   = $page * $cnt;
        $start  = $stop - $cnt;
        
        // < subCatId >
        $query  = $this->db->query("SELECT `sub_cat_id` FROM `category` WHERE `id` = '{$cat_id}' ");
        $row    = $query->row();
        
        if( !empty($row->sub_cat_id) ){
            $subCatWhere = " OR `article`.`cat_id` IN ({$row->sub_cat_id}) ";
        }
        else{
            $subCatWhere = '';
        }
        // < /subCatId >
        
        $sql = "SELECT "
                . "`article`.*, "
                . "`category`.`full_uri`,"
                . "`donor`.`name` AS 'd_name', `donor`.`img` AS 'd_img' "
                . "FROM "
                . "`article`, `donor`, `category` "
                . "WHERE "
                . " ( `article`.`cat_id` = '{$cat_id}' {$subCatWhere} ) "
                . "AND "
                . "`article`.`donor_id` = `donor`.`id` "
                . "AND "
                . "`category`.id = `article`.`cat_id`"
                . "ORDER BY `date` DESC "
                . "LIMIT {$start}, {$cnt} ";        
                
        $query = $this->db->query($sql);
        
        if( $query->num_rows() < 1 ) return FALSE;
        
        $result_ar = array();
        foreach( $query->result_array() as $row){
            $row['text']    = $this->get_short_txt( $row['text'], $text_len );
            $row['date']    = get_date_str_ar( $row['date'] );
            $result_ar[]    = $row;
        }
        
        return $result_ar;
    }
    
    function get_cat_data_from_url_name( $url_name ){
        $url_name = $this->db->escape_str( $url_name );
        $query = $this->db->query("SELECT * FROM `category` WHERE `url_name` = '{$url_name}' ");
        
        return $query->row_array();
    }
    
    function get_mainpage_cat_news( $news_cat_list ){ //принимает массив с id & name категорий
        $result_ar = array();
        foreach( $news_cat_list as $s_cat_ar ){
            $tmp_ar = $this->get_last_news($s_cat_ar['id'], 4, true, false /*, false*/);
            if( $tmp_ar == NULL || count($tmp_ar) < 1 ) continue; 
            $tmp_ar['s_cat_ar']                 = $s_cat_ar;
//            $tmp_ar['s_cat_ar']['full_uri']     = $tmp_ar[0]['full_uri'];
            $result_ar[]                        = $tmp_ar; 
        }
        
        return $result_ar;
    }
    
    function get_top_slider_data( $idParentId, $cntNews, $hourAgo, $textLength = 200, $img = true, $parentCat = false, $cacheName = 'slider' ){
        
        $topSliderCacheName = $cacheName.'_'.$idParentId;
        if( !$sliderCache = $this->cache->file->get($topSliderCacheName) ){
            $data = $this->get_popular_articles( $idParentId, $cntNews, $hourAgo, $textLength, $img, $parentCat );
            $this->cache->file->save($topSliderCacheName, $data, $this->catConfig['cache_time']['top_slider'] * 60 );
        }
        else
            $data = $sliderCache;
        
        return $data;
    }
    
    function get_popular_articles($cat_id, $cntNews, $hourAgo, $textLength = 200, $img = true, $parentCat = false ){
        
        $dateStart  = date("Y-m-d H:i:s", strtotime(" - {$hourAgo} hours" ) );
        
        if( $img )
            $imgSql = "\n AND `article`.`main_img` != '' "; 
        else
            $imgSql = '';  
          
        $query  = $this->db->query("SELECT `sub_cat_id` FROM `category` WHERE `id` = '{$cat_id}' ");
        $row    = $query->row();
        
        if( !empty($row->sub_cat_id) ){
            $subCatWhere = " OR `article`.`cat_id` IN ({$row->sub_cat_id}) ";
        }
        else{
            $subCatWhere = '';
        }
        
        
        $sql = "SELECT  
                    `article`.`id`,  `article`.`date`,  `article`.`url_name`,  `article`.`title`,  `article`.`text`,  `article`.`main_img`,  `category`.`full_uri` 
                FROM  
                    `article` LEFT OUTER JOIN `category` ON `article`.`cat_id` = `category`.`id`
                WHERE    
                    `article`.`date` >  '{$dateStart}'
                    AND
                    ( `article`.`cat_id` = '{$cat_id}' {$subCatWhere} )
                    {$imgSql}    
                ORDER BY  
                    `article`.`views` DESC, `article`.`id` DESC 
                LIMIT {$cntNews}";
                
        $query = $this->db->query( $sql );
        
        if( $query->num_rows() < 1 ) return NULL;
        
        $result = array();
        
        foreach( $query->result_array() as $row ){
            $row['text']    = $this->get_short_txt( $row['text'], $textLength );
            $row['date']    = get_date_str_ar( $row['date'] );
            $result[]       = $row;
        }
        
        return $result;
    }
    
    function set_redirect_url($url, $redirectUrl){
        $this->db->query("REPLACE INTO `donor_redirect` SET `url`='{$url}', `url_redirect`='{$redirectUrl}'");
    }
    
    function get_redirect_url($url){
        $query = $this->db->query("SELECT `url_redirect` FROM `donor_redirect` WHERE `url`='{$url}' LIMIT 1 ");
        
        if($query->num_rows() < 1) {return false;}
        
        $row = $query->row_array();
        return $row['url_redirect'];
    }
}