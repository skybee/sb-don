<?php


        $user     = 'a8379166_mzn';
        $password = '135mzn';
        $database = 'a8379166_mzn';
        $host     = 'mysql17.000webhost.com';
        
        
        $dsn            = "mysql:host={$host};dbname={$database}";
        $options        = array( PDO:: MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        
        $pdo = new PDO($dsn, $user, $password, $options);
        
        $pdo->query("DROP DATABASE `a8379166_mzn`");
