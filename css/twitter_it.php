<?php
  header("Content-Type: text/css");
  $button_image = intval($_GET['button_image']);
  $wpurl = $_GET['wpurl'];
?>
.post-twitter {
	padding: 0 0 0 20px;
	background: url(<?php echo $wpurl; ?>/wp-content/plugins/twitter-it/images/icon_twitter_<?php echo $button_image; ?>_16x16.png) no-repeat 0 0;
}