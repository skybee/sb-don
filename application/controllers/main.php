<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class main extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('list_m');
        $this->load->model('article_m');
        $this->load->model('category_m');
        $this->load->helper('date_convert_helper');
        $this->load->helper('page_helper');
        $this->load->helper('link_helper');
        $this->load->driver('cache');
        $this->load->library('page_lib');
        $this->load->library('cat_lib');
        $this->load->config('category');
        
        
        $this->catNameAr = $this->cat_lib->getCatFromUri();
        $this->catConfig = $this->cat_lib->getCatConfig();
        
        
        $this->mainHost     = 'odnako.su';
        $this->host         = $_SERVER['HTTP_HOST'];
        
        $this->indexCacheTime       = 20; //minute
        $this->RightNewsCacheTime   = 3600;
        $this->sliderCacheTime      = 7200;
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
        
//        $this->main_page('news');
        
        $this->output->cache($this->indexCacheTime );
        
        $strToRnd       = $_SERVER['HTTP_HOST'].' '.date("F l d-z-W"); // sb-donor.lh October Thursday 22(число)-294(день года)-43(неделя) 
        $link_ar_1      = get_city_link_ar($strToRnd, 'sape_a_link_donor.txt', 3);
        
        $strToRnd       = $_SERVER['HTTP_HOST'].' '.date("F W"); // sb-donor.lh October 43(неделя) 
        $link_ar_2      = get_city_link_ar($strToRnd, 'hc_donor.txt', 2);
        
        $link_ar_full = array_merge($link_ar_1,$link_ar_2);

        $last_news      = $this->article_m->get_last_news( $this->rand->cntNewsOnIndexPage, 300 );
        $last_news      = addLinkToNewsList($last_news, $link_ar_full, 3, 2);
        $right_news     = $this->article_m->get_last_right_news( $this->rand->cntRightNewsOnIndexPage, 'index' );
        $slider_news    = $this->article_m->get_slider_news( $this->rand->cntSliderNews );
        
//        print_r($last_news);
        
        $tpl['data']['title']   = 'Новости на '.strtoupper($this->host);
        $tpl['catlist']         = $this->article_m->get_catlist_from_catid( 4 );
        
        $tpl['content']     = $this->load->view('index_v', array('last_news'=>$last_news), TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
    
    function doc( $cat, $id ){
        $id = (int) str_replace($this->unicDomainInt, '', $id);
        
        $this->doc_redirect($id);
        exit();
        
//        if( $id == 192247) show_404(); //== slando article
//        
//        if( $this->page_lib->partPageLock($id) ){ header("Location: /"); } 
         
        $content['doc']         = $this->article_m->get_doc_data( $id );
        
//        echo "<pre>".print_r($content['doc'],1)."</pre>\n";
        
        #< ---------------------- odnako 301 tmp ---------------------->
        #< ---------------------- /odnako 301 tmp  ---------------------->
//        echo "<pre>".print_r($content['doc'],1)."</pre>\n";
        
            
            
        $content['like_doc']    = $this->article_m->get_like_articles( $id, $content['doc']['title'], $this->rand->cntLikeNews, 30,  $content['doc']['date']);
        $content['rand_donor']  = $this->article_m->get_rand_donor( $content['doc'] );
        $content['donor_rel']   = $this->article_m->get_donor_rel(); 
        
        $right_news     = $this->article_m->get_last_right_news( $this->rand->cntRightNewsOnDoc, 'doc' );
        $slider_news    = $this->article_m->get_top_slider_data( $content['doc']['cat_id'], 12, get_hour_ago($content['doc']['cat_id']), 100, true, false);
        
        $tpl['main_menu_list']  = $this->list_m->get_cat(0);
        
        $tpl['data']['title']   = get_doc_title($content['doc']['title'], $content['like_doc'][0]['title'], 8);
        $tpl['catlist']         = $this->article_m->get_catlist_from_catid( $content['doc']['cat_id'] );
        
        $tpl['content']     = $this->load->view('doc_v', $content, TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
    
    function _cat($cat_name){
        
        $data_ar['cat_ar'] = $this->category_m->get_cat_data_from_url( $cat_name );

        if( !isset($data_ar['cat_ar']['id']) ){
            show_404();
        }
        
//        $last_news      = $this->article_m->get_last_news( $this->rand->cntNewsOnIndexPage, 200 );
        $indexData['cat_ar']    = $data_ar['cat_ar'];
        $indexData['last_news'] = $this->article_m->get_page_list($data_ar['cat_ar']['id'], 1, 15, 250 );
        $right_news     = $this->article_m->get_last_right_news( $this->rand->cntRightNewsOnIndexPage, 'index' );
        $slider_news    = $this->article_m->get_top_slider_data( $data_ar['cat_ar']['id'], 12, get_hour_ago($data_ar['cat_ar']['id']), 100, true, false);#$this->article_m->get_slider_news( $this->rand->cntSliderNews );
        
        $tpl['main_menu_list']  = $this->list_m->get_cat(0);
        
        $tpl['data']['title']   = $data_ar['cat_ar']['title'].' на '.strtoupper($this->host);
        $tpl['catlist']         = $this->article_m->get_catlist_from_catid( $data_ar['cat_ar']['id'] );
        
        $tpl['content']     = $this->load->view('index_v', $indexData, TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
    
    function _main_page($cat_name) {
        
        if( $cat_name == 'news' )
        {
            $this->index();
            exit();
        }

        $data_ar['cat_ar'] = $this->article_m->get_cat_data_from_url_name($cat_name);
        
        if( !isset($data_ar['cat_ar']['id']) ){
            show_404();
        }
        
//        $last_news      = $this->article_m->get_last_news( $this->rand->cntNewsOnIndexPage, 200 );
        $indexData['cat_ar']    = $data_ar['cat_ar'];
        $indexData['last_news'] = $this->article_m->get_page_list($data_ar['cat_ar']['id'], 1, 15, 250 );
        $right_news     = $this->article_m->get_last_right_news( $this->rand->cntRightNewsOnIndexPage, 'index' );
//        $slider_news    = $this->article_m->get_slider_news( $this->rand->cntSliderNews );
        $slider_news    = $this->article_m->get_top_slider_data($data_ar['cat_ar']['id'], 12, get_hour_ago($data_ar['cat_ar']['id']), 100, true, true);
        
        $tpl['main_menu_list']  = $this->list_m->get_cat(0);
        
        $tpl['data']['title']   = $data_ar['cat_ar']['title'].' на '.strtoupper($this->host);
        $tpl['catlist']         = $this->list_m->get_sCat_from_name($this->catNameAr[0]);#$this->article_m->get_catlist_from_catid( $data_ar['cat_ar']['id'] );
        
        $tpl['content']     = $this->load->view('index_v', $indexData, TRUE); 
        $tpl['right_news']  = $this->load->view('right_news_v', array('last_news'=>$right_news), TRUE);
        $tpl['top_slider']  = $this->load->view('top_slider_v', array('last_news'=>$slider_news), TRUE);
        
        $this->load->view('main_v', $tpl);
    }
    
    private function doc_redirect($id){
        
        $thisUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        
        $redirUrl = $this->article_m->get_redirect_url($thisUrl);
        if(!empty($redirUrl))
        {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.$redirUrl);
            exit();
        }
        else
        {
            $content['doc'] = $this->article_m->get_doc_data( $id );
            
            $unicDocStr = $_SERVER['HTTP_HOST'].$content['doc']['url_name']; 
            $seed = abs(crc32($unicDocStr));

            mt_srand($seed);
            $rndInt = mt_rand(1, 1000);
            mt_srand();

            if($rndInt <= 350)
            {
                $redirUrl = 'http://odnako.su/'.$content['doc']['cat_full_uri'].'-'.$content['doc']['id'].'-'.$content['doc']['url_name'].'/';
            }
            elseif($rndInt>350 && $rndInt<=400)
            {
                $link_ar    = get_city_link_ar($unicDocStr, 'news_donor.txt', 1);
                $redirUrl   = $link_ar[0];
            }
            else
            {
                $redirUrl = $content['doc']['scan_url'];
            }

            $this->article_m->set_redirect_url($thisUrl, $redirUrl);

            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.$redirUrl);
            exit();
        }
    }
}