<?php

class page_lib{
    
    function __construct() {
        $this->ci = &get_instance();
    }
    
    function partPageLock( $id ){
         $percent   = (int) $this->ci->lockPagePercent."0";
         $domainInt = $this->ci->unicDomainInt;
         
         if( $id%5 == 0 ){
             $seed = ($domainInt + $id) / 10000;
         }
         elseif( $id%3 == 0 ){
             $seed = $domainInt / $id;
         }
         elseif( $id%2 == 0 ){
             $seed = ($domainInt - $id) / 2;
         }
         else{
             $seed = $domainInt + $id;
         }
         
         $seed = abs( round($seed) );
         
         mt_srand($seed);
         $randInt = mt_rand(1, 1000);
         mt_srand();
         
//         echo $randInt."<br />\n".$percent."<br />\n".$seed;
         
         if( $randInt <= $percent ){
             return true;
         }
         else{
             return false;
         }
    }
    
    
}

