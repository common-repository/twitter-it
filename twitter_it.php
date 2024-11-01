<?php
/*
Plugin Name: Twitter It!
Plugin URI: http://roman-allenstein.de/wordpress
Description: Let your visitors twitter your posts over the web! Activate the plugin an integrate the following code into your Template. <code>&lt;?php if ( function_exists('twitter_it') ) : twitter_it($post->ID); endif; ?></code>
Version: 5
Author: Roman Allenstein
Author URI: http://roman-allenstein.de
*/
/*  Copyright 2009  Roman Allenstein  (email : wordpress@roman-allenstein.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
function twitter_it($id) {
  twitter_it::the_twitter_tag($id);
}

class twitter_it {
  
  const TABLE_NAME  = "twitter_it_tinyurls";
  const MESSAGE_LENGTH = 140;

	function init()	{
		$options = twitter_it::get_options();
    if($options['button']) {
      add_action('wp_head', array('twitter_it', 'add_css'));
    }
  
    if($options['use_filter']) {
      add_filter('the_content', array('twitter_it', 'twitter_it_filter'), 1200, 1);
    }
  }
  
  function twitter_it_filter($content) { 
    global $post;	
    $options = twitter_it::get_options();
    if($options['filter_append'] == 'append') {
      $string = $content . twitter_it::the_twitter_tag($post->ID, false);
    } else {
      $string = twitter_it::the_twitter_tag($post->ID, false) . $content;
    }
    return $string;
  }  
	
	function get_options()
	{
		if ( ( $o = get_option('twitter_it_params') ) === false )
		{
			$o = array(
				'label' => "Twitter It!",
				'prefix' => "Reading ",
				'button' => false
				);
			
			update_option('twitter_it_params', $o);
		}
		
		return $o;
	}
	
  function the_twitter_tag($id, $echo = true) {
    $options = twitter_it::get_options();
  
    $p = get_post($id);
    
    if($options[button]) {
      $class = 'class="post-twitter" ';
    }
    
    if($options[nofollow]) {
      $rel = 'rel="nofollow"';
    }    
  
    if($options[style]) {
      $style = 'style="'.$options[style].'"';
    }  
  
    $string  = '<span '.$class.''.$style.'>';  
  
    $string .= '<a href="'.twitter_it::generate_twitter_link($p->post_title, $p->guid).'" title="'.$options[label].'" '.$rel.'>'.$options[label].'</a>';
  
    $string  .= '</span>'; 
    if($echo) {
      echo $string;
    } else {
      return $string;
    }
  }

  function generate_twitter_link($title, $post_url) {
    global $wpdb;
    $options = twitter_it::get_options();
    
    if($wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . twitter_it::TABLE_NAME . " WHERE post_url = '" . $post_url . "' AND tiny_url != ''") == 0) {
    
      $short_url = twitter_it::generate_short_url($post_url);
      
      if($wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . twitter_it::TABLE_NAME . " WHERE post_url = '" . $post_url . "' AND tiny_url != '".$short_url."'") != 0) {
        $sql = "DELETE FROM " . $wpdb->prefix . twitter_it::TABLE_NAME . " WHERE post_url = '" . $post_url . "'";
        $results = $wpdb->query($sql);        
      }
      
      $sql = "INSERT INTO " . $wpdb->prefix . twitter_it::TABLE_NAME . "(post_url, tiny_url) VALUES ('".$post_url."', '".$short_url."')";
      $results = $wpdb->query($sql);
    } else {
      $short_url = $wpdb->get_var("SELECT tiny_url FROM " . $wpdb->prefix . twitter_it::TABLE_NAME . " WHERE post_url = '" . $post_url . "'");
    }
    
    $twitterTitle = rawurlencode($options[prefix] . " \"".$title."\" " . $short_url);
    
    $prefix_length = strlen($options[prefix]);
    $suffix_length = strlen($short_url);
    $twitit_length    = twitter_it::MESSAGE_LENGTH - $prefix_length - $suffix_length;
    
    if(strlen($twitterTitle) > $twitit_length) {
      $twitterTitle = rawurlencode($options[prefix] . " \"".trim(substr($title,0,($twitit_length-4-3)))."\" " . $short_url);
    }
    
    
    $twitterLink = "http://twitter.com/home?status=".$twitterTitle;
    return $twitterLink;
  }
  
  function generate_short_url($url) {
    $options = twitter_it::get_options();
    
    switch($options[service]) {
    
      case 'tinyurl.com':
        $TWITTER_API = "http://tinyurl.com/api-create.php?url=";
        $api_url = $TWITTER_API . $url;
        $shortUrl = twitter_it::get_short_url($api_url);
      break;
      
      case 'bit.ly':
        $BITLY_API    = "http://api.bit.ly/shorten?version=2.0.1&history=1&longUrl=";
        $api_url      = $BITLY_API.$url.'&login='.$options[service_user].'&apiKey='.$options[service_password];
        $shortUrl     = twitter_it::get_short_url($api_url);
        $asd          = json_decode($shortUrl);
        $shortUrl     = $asd->results->$url->shortUrl;
      break;
    }
    return $shortUrl;
  }
  
  function get_short_url($url) {
    if(ini_get('allow_url_fopen') == 'On') {
      $shortUrl = file_get_contents($url);
    } elseif(function_exists('curl_init')) {
      $ch = curl_init();
      $timeout = 5; // set to zero for no timeout
      curl_setopt ($ch, CURLOPT_URL, $url);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      $shortUrl = curl_exec($ch);        
      curl_close($ch);          
    }

	return $shortUrl;
  }

  function add_css() {
    $options = twitter_it::get_options();
    echo '<link rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/twitter-it/css/twitter_it.php?wpurl='.get_bloginfo('wpurl').'&button_image='.$options['button_image'].'" type="text/css" media="screen" />';
  }
}
twitter_it::init();

# include admin stuff when relevant
if ( is_admin() )
{
	include dirname(__FILE__) . '/twitter_it-admin.php';
}

# Installer
function twitter_it_install () {
  $action = 'install';
  
  if(ini_get('allow_url_fopen') == 'On' || function_exists('curl_init')) {
    require_once(dirname(__FILE__).'/installer.php');
  } else {
		$message = 'You have to enable <a href="http://de3.php.net/curl">cURL</a> or <a href="http://www.php.net/manual/de/function.fopen.php">allow_url_fopen</a>.';
		if( function_exists('deactivate_plugins') )
          deactivate_plugins(__FILE__);
		else
          $message .= '<p><strong>Please deactivate this plugin immediately.</strong></p>'; 
		die($message);
	}
  
}
register_activation_hook(__FILE__,'twitter_it_install');
?>