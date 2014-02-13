<?php
            $set_default='  SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
                            SET time_zone = "+00:00";';
            
     $create_news_master="  CREATE TABLE IF NOT EXISTS `np_news` (
                            `news_id` bigint(20) NOT NULL AUTO_INCREMENT,
                            `title` varchar(200) DEFAULT NULL,
                            `content` text NOT NULL,
                            `category` int(11) DEFAULT NULL,
                            `post_date` date NOT NULL,
                            `post_time` varchar(60) NOT NULL,
                            `url` varchar(250) DEFAULT NULL,
                            `expiry_date` date DEFAULT NULL,
                            `sort_order` bigint(20) DEFAULT NULL,
                            `disabled` smallint(1) DEFAULT '0',
                            PRIMARY KEY (`news_id`)
                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;";
     
     $create_news_slave=" CREATE TABLE IF NOT EXISTS `np_newsimage` (
                          `news_imageid` bigint(20) NOT NULL AUTO_INCREMENT,
                          `news_id` bigint(20) NOT NULL,
                          `image_path` varchar(200) NOT NULL,
                          `disabled` smallint(1) NOT NULL DEFAULT '0',
                          PRIMARY KEY (`news_imageid`),
                          KEY `news_id` (`news_id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;";

     $create_news_category=" CREATE TABLE IF NOT EXISTS `np_category` (
                            `cat_id` bigint(20) NOT NULL AUTO_INCREMENT,
                            `category_name` varchar(150) NOT NULL,
                            `status` smallint(1) NOT NULL DEFAULT '0',
                            PRIMARY KEY (`cat_id`),
                            UNIQUE KEY `category_name` (`category_name`)
                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;
                            ";
       $set_foriegn_key="   ALTER TABLE `np_newsimage`
                            ADD CONSTRAINT `np_newsimage_ibfk_1`
                            FOREIGN KEY (`news_id`)
                            REFERENCES `np_news` (`news_id`) 
                            ON DELETE CASCADE ON UPDATE CASCADE;";
       global $wpdb;
      
       $wpdb->query(  $create_news_master );
       $wpdb->query(  $create_news_slave );
       $wpdb->query(  $set_foriegn_key );
       $wpdb->query(  $create_news_category );
?>
