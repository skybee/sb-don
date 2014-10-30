<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class main extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('article_m');
        $this->load->helper('date_convert_helper');
        $this->load->helper('page_helper');
        $this->load->driver('cache');
        $this->load->library('page_lib');
        
        
        $this->mainHost     = 'odnako.su';
        $this->host         = $_SERVER['HTTP_HOST'];
        
        $this->indexCacheTime       = 20; //minute
        $this->RightNewsCacheTime   = 900;
        $this->sliderCacheTime      = 3600;
        $this->donorDomainCacheTime = 3600*5;
        $this->catListCacheTime     = 3600*10;
        
        $this->lockPagePercent      = 30;
        
        $this->unicDomainInt      = abs(crc32($this->host));
        $this->unicDomainStr      = str_replace( '=', '', base64_encode( $this->unicDomainInt ) );
        
        mt_srand( $this->unicDomainInt );
        $this->rand->cntNewsOnIndexPage         = mt_rand(10, 30);
        $this->rand->cntRightNewsOnIndexPage    = mt_rand(15, 30);
        $this->rand->cntRightNewsOnDoc          = mt_rand(10, 30);
        $this->rand->cntLikeNews                = mt_rand(6, 15);
        $cntSliderNews = array(8,12,16);
        $this->rand->cntSliderNews              = $cntSliderNews[ rand(0, count($cntSliderNews)-1) ];
        mt_srand();
    }
    
    function index(){
        $this->output->cache( $this->indexCacheTime );

        $last_news      = $this->article_m->get_last_news( $this->rand->cntNewsOnIndexPage, 200 );
        $right_news     = $this->article_m->get_last_right_news( $this->rand->cntRightNewsOnIndexPage, 'index' );
        $slider_news    = $this->article_m->get_slider_news( $this->rand->cntSliderNews );
        
        $tpl['data']['title']   = 'Новости на '.strtoupper($this->host);
        $tpl['catlist']         = $this->article_m->get_catlist_from_catid( 4 );
        
        $tpl['content']     = $this->load->view('index_v', array('last_news'=>$last_news), TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
    
    function doc( $cat, $id ){
        $id = (int) str_replace($this->unicDomainInt, '', $id);
        
//        if( $id == 192247) show_404(); //== slando article
//        
//        if( $this->page_lib->partPageLock($id) ){ header("Location: /"); } 
         
        $content['doc']         = $this->article_m->get_doc_data( $id );
        
        #<odnako 301 tmp>
            if( $_SERVER['HTTP_HOST'] == 'build02.comeze.com' || $_SERVER['HTTP_HOST'] == 'sbnews.ukrainecityguide.com' || $_SERVER['HTTP_HOST'] == 'build.net76.net' ){
                show_404();
                exit();
            }
        
            $redirUrl = 'http://odnako.su/'.$content['doc']['cat_full_uri'].'-'.$content['doc']['id'].'-'.$content['doc']['url_name'].'/';

            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.$redirUrl);
            exit();
        #</odnako 301 tmp>
//        echo "<pre>".print_r($content['doc'],1)."</pre>\n";
        
        $content['like_doc']    = $this->article_m->get_like_articles( $id, $content['doc']['title'], $this->rand->cntLikeNews, 30,  $content['doc']['date']);
        $content['rand_donor']  = $this->article_m->get_rand_donor( $content['doc'] );
        $content['donor_rel']   = $this->article_m->get_donor_rel(); 
        
        $right_news     = $this->article_m->get_last_right_news( $this->rand->cntRightNewsOnDoc, 'doc' );
        $slider_news    = $this->article_m->get_slider_news( $this->rand->cntSliderNews );
        
        $tpl['data']['title']   = get_doc_title($content['doc']['title'], $content['like_doc'][0]['title'], 8);
        $tpl['catlist']         = $this->article_m->get_catlist_from_catid( $content['doc']['cat_id'] );
        
        $tpl['content']     = $this->load->view('doc_v', $content, TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
}