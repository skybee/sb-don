<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class main extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('article_m');
        $this->load->helper('date_convert_helper');
        $this->load->helper('page_helper');
        $this->load->driver('cache');
        
        
        $this->mainHost     = 'odnako.su';
        $this->host         = $_SERVER['HTTP_HOST'];
        
        $this->indexCacheTime       = 20; //minute
        $this->RightNewsCacheTime   = 900;
        $this->sliderCacheTime      = 3600;
        $this->donorDomainCacheTime = 3600*5;
        
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
        
        $tpl['content']     = $this->load->view('index_v', array('last_news'=>$last_news), TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
    
    function doc( $cat, $id ){
        $id = (int) str_replace($this->unicDomainInt, '', $id);
        
        $content['doc']         = $this->article_m->get_doc_data( $id );
        $content['like_doc']    = $this->article_m->get_like_articles( $id, $content['doc']['title'], $this->rand->cntLikeNews, 30,  $content['doc']['date']);
        $content['rand_donor']  = $this->article_m->get_rand_donor( $content['doc'] );
        
        $right_news     = $this->article_m->get_last_right_news( $this->rand->cntRightNewsOnDoc, 'doc' );
        $slider_news    = $this->article_m->get_slider_news( $this->rand->cntSliderNews );
        
        $tpl['data']['title']   = get_doc_title($content['doc']['title'], $content['like_doc'][0]['title'], 8);
        
        $tpl['content']     = $this->load->view('doc_v', $content, TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
}