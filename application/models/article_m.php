<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class article_m extends CI_Model{
    
    function __construct() {
        parent::__construct();
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
                                            `donor`.`name` AS 'd_name', `donor`.`host` AS 'd_host'
                                    FROM 
                                        `article`, `category`, `donor`
                                    WHERE 
                                        `article`.`id`  = {$id}
                                        AND
                                        `category`.`id` = `article`.`cat_id`
                                        AND
                                        `article`.`donor_id` = `donor`.`id`
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
        $randInt = mt_rand(1, 1000);
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
        
        return $url;
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
}