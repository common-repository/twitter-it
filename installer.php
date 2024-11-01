<?php
//***** Installer *****
require_once(ABSPATH . 'wp-admin/upgrade.php');
//***Installer variables***
global $wpdb;
$table_name = $wpdb->prefix . twitter_it::TABLE_NAME;

switch($action) {
  case 'install':
    //***Installer***
	  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . " (
          post_url varchar(255) NOT NULL,
          tiny_url varchar(40) NOT NULL,
          PRIMARY KEY  (post_url)
        );";
        dbDelta($sql);
       }
  break;
  
  case 'uninstall':
  break;
  
  case 'upgrade':
  break;  
  }
//***** End Installer *****
?>