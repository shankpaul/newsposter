<?php
/*
Plugin Name: News Poster
Plugin URI: http://www.nuevalgo.com
Description: For easily creating and manage news for your websites.
Author: Shan K Paul
Version: 1.0
Author URI: 
*/
define('PLUGIN_URL',  get_option('siteurl').'/wp-content/plugins/news_poster/');
global $wpdb;

/*
 * Insatll News Poster pugin 
 * Code Works when Plugin Deactivated
 */
function install_news_poster()
{
    /*
     * install tables to batabase
     */
   
        include('db_config.php');
        add_option("NewsPosterStatus",1);
        add_option("NewsPosterVersion",'1.0');
        
        //Default Settings
          add_option("UploadImage",1);
          
}

/*
 * Uninstall News Poster
 * Code Works when Plugin Deactivated
 */
function uninstall_news_poster()
{
   delete_option("NewsPosterStatus");
   delete_option("NewsPosterVersion");
        //Default Settings
   delete_option("UploadImage");  
}

function news_poster_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('views/news-poster-edit.php');
			break;
		case 'add':
			include('views/news-poster-add.php');
			break;
                    case 'settings':
			include('views/news-poster-settings.php');
			break;
                 default:
			include('views/news-poster.php');
			break;
	}
}

function news_poster_add_to_menu() 
{
        /*
         * call back function : news_poster_admin_options();
         */
	add_menu_page('News Poster', 'News Poster', 'manage_options', 'news-poster', 'news_poster_admin_options',PLUGIN_URL.'np_images/icon.png',61.12);
}

				
          
function getNews($np_config="")
{
   date_default_timezone_set('Asia/Kolkata');
    /*
     * Function returns newsid, newst tile, news content,category name, expiry date, postdate , url,post time,and image path
     * Post time must be in timespan format 
     * 
     * returns array format 
     */
    
    /*
     * Configuration settings
     * 
     */
  /*$limit='infinity';
  $offset=0;
  if($limit!="infinity")
  {
    $limit_sql=$limit;
  }
  if($offset!="offset")
  {
    $limit_sql=$limit;
  }
  */
  
    
    
    /*
     * global $wpdb
     * Inter facing with wordpress
     */
    global $wpdb;
    $sql="SELECT a.`news_id` AS id,
	a.`title` AS title,
	a.`content` AS content,
	b.`category_name` AS category,
	a.`post_date` AS postdate,
	a.`url` AS url,
	a.`expiry_date` AS expirydate,
	a.`post_time` AS posttime 
        FROM np_news a,np_category b WHERE a.`category`=b.`cat_id` AND a.disabled=0  AND (a.`expiry_date`>= CURDATE() OR a.`expiry_date` IS NULL ) order by a.news_id desc";
    
   
    
    $np_box=array();
    $results=$wpdb->get_results($sql);
    if(count($results)>0)
    {    
            foreach ($results as $obj)
            {
                $np_box_temp=array();
                $np_box_temp['id']=$obj->id;
                $np_box_temp['title']=stripslashes_deep($obj->title);
                $np_box_temp['content']=stripslashes_deep($obj->content);
                $np_box_temp['category']=$obj->category;
                $np_box_temp['postdate']=$obj->postdate;
                $url=$obj->url;
                echo strstr($url,'http');
                if($url!="")
                {
                    if(strstr($url,'http')=="")
                    {
                        $url='http://'.$url;
                    }
                }
                $np_box_temp['url']=preg_replace('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_/]+!', "<a target=\"_new\" href=\"\\0\">\\0</a>",$url);
                $np_box_temp['expirydate']=$obj->expirydate;
                $np_box_temp['posttime']=$obj->posttime;                
                
                /*
                 * Collect Images of each news
                 */
                $img_box=array();
				$img_url_box=array();
                $imagesql="select image_path as path from np_newsimage where news_id=$obj->id and disabled=0";
                $img_results=$wpdb->get_results($imagesql);
                if(count($img_results)>0)
                {    
                        foreach ($img_results as $img_obj)
                        {
                            array_push($img_box,'<img src="'.$img_obj->path.'"');
							 array_push($img_url_box,$img_obj->path);
                        }
                }
                
                
                /*
                 * Store collected images to single array that contais all the data related to news
                 */
                
               $np_box_temp['image']=$img_box; 
			   $np_box_temp['imageurl']=$img_url_box; 
               
               array_push($np_box,$np_box_temp);
            
                
            }
            return $np_box;
    }
    else
    {
         return $np_box;
    }
}


if (is_admin()) 
{
	add_action('admin_menu', 'news_poster_add_to_menu');
	if(!class_exists('pagination'))
	include_once ('pagination.class.php');
}
register_activation_hook(__FILE__, 'install_news_poster');
register_deactivation_hook( __FILE__, 'uninstall_news_poster' );

?>
