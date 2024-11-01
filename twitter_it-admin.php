<?php
twitter_it_admin::init();

class twitter_it_admin {

	function init()
	{
		add_action('admin_menu', array('twitter_it_admin', 'admin_menu'));
	}

	function update_options()	{
    $old_options = twitter_it::get_options();
    
		$new_options = $_POST['twitter_it'];
		$new_options[button] = isset($new_options[button]);
		$new_options[nofollow] = isset($new_options[nofollow]);
		$new_options[use_filter] = isset($new_options[use_filter]);
		
		if($old_options['service'] != $new_options['service'] || isset($new_options['clear_cache'])) {
      twitter_it_admin::clear_cache();
      unset($new_options['clear_cache']);
		}
		
		update_option('twitter_it_params', $new_options);
	}
	
	function clear_cache() {
    global $wpdb;
    $sql = "DELETE FROM " . $wpdb->prefix . twitter_it::TABLE_NAME;
    $results = $wpdb->query($sql);
	}

	function admin_menu()	{
		add_options_page(
				__('Twitter It!'),
				__('Twitter It!'),
				'manage_options',
				__FILE__,
				array('twitter_it_admin', 'admin_page')
				);
	}
	
	function admin_page()	{
		if ( isset($_POST['update_twitter_it_options'])
			&& $_POST['update_twitter_it_options']
			)
		{
			twitter_it_admin::update_options();

			echo "<div class=\"updated\">\n"
				. "<p>"
					. "<strong>"
					. __('Settings saved.')
					. "</strong>"
				. "</p>\n"
				. "</div>\n";
		}

		$options = get_option('twitter_it_params');
		
    echo twitter_it_admin::site_template();
    
/*
 * Sidebar begin 
 */
    echo twitter_it_admin::sidebar_template();
    
    echo twitter_it_admin::box_start(__('About'));
    echo '<ul>
            <li><a href="http://roman-allenstein.de/wordpress">Twitter It! Homepage</a></li>
            <li><br /> Author: Roman Allenstein</li>
            <li><img src="../wp-content/plugins/twitter-it/images/icon_twitter_1_16x16.png" /><a href="http://www.twitter.com/romanbloggt">Follow me</a></li>
          </ul>';
    echo twitter_it_admin::box_end();
    
    echo twitter_it_admin::box_start(__('Credits'));    
    echo '<ul>
            <li>Thanks to <a href="http://icontexto.blogspot.com/2008/09/icontexto-webdev-social-bookmark-bonus.html">IconTexto</a> for the Icons</li>       
          </ul>';    
    echo twitter_it_admin::box_end();
    
    echo twitter_it_admin::sidebar_template(true);
    
/*
 * Sidebar end
 */        
    
/*
 * Content begins here
 */    
    if ( function_exists('wp_nonce_field') ) wp_nonce_field('twitter_it');
         
    echo twitter_it_admin::box_start('General Settings');
    echo '<ul>';
    echo '<input type="hidden" name="update_twitter_it_options" value="1">';
    echo '<li>'
       . '<input type="text"'
       . ' name="twitter_it[label]" id="twitter_it[label]" value="'.$options['label'].'"'
       . ' /> '
       . '<label for="twitter_it[label]">'
       . __('Label for the Twitter It! Button')
       . '</label>'       
       . '</li>'
////////////////   
       . '<li>'
       . '<input type="text"'
       . ' name="twitter_it[prefix]" id="twitter_it[prefix]" value="'.$options['prefix'].'"'
       . ' /> '
       . '<label for="twitter_it[prefix]">'
       . __('Prefix for the twitter-message e.g. "Reading"')
       . '</label>'       
       . '</li>'
////////////////       
;
    echo '</ul>';       
    echo twitter_it_admin::box_end();              

    echo twitter_it_admin::box_start(__('Integration'));
    echo '<ul>';
    echo '  <li><input type="checkbox" name="twitter_it[use_filter]" id="twitter_it[use_filter]" ' . ( $options['use_filter'] ? ' checked="checked"' : '' ) . ' /> <label for="twitter_it[use_filter]"> ' . __('Place twitter-button after the content of a post?') . '</label></li>';
    echo '  <li><input type="radio" id="filter_before" name="twitter_it[filter_append]" value="before" ' . ( $options['filter_append'] == 'before' ? ' checked="checked"' : '' ) . '/> <label for="filter_before">Add the link <b>before</b> the content starts.</label>';
    echo '      <input type="radio" id="filter_append" name="twitter_it[filter_append]" value="append" ' . ( $options['filter_append'] == 'append' ? ' checked="checked"' : '' ) . '/> <label for="filter_append">Append the link <b>after</b> the content.</label>';
    echo '  <li><input type="checkbox" name="twitter_it[button]" id="twitter_it[button]" ' . ( $options['button'] ? ' checked="checked"' : '' ) . ' /> <label for="twitter_it[button]"> ' . __('Show twitter-image next to text?') . '</label></li>';
    echo '  <li><input type="radio" id="button_1" name="twitter_it[button_image]" value="1" ' . ( $options['button_image'] == '1' ? ' checked="checked"' : '' ) . '/> <label for="button_1"><img src="../wp-content/plugins/twitter-it/images/icon_twitter_1_16x16.png" /></label>';
    echo '      <input type="radio" id="button_2" name="twitter_it[button_image]" value="2" ' . ( $options['button_image'] == '2' ? ' checked="checked"' : '' ) . '/> <label for="button_2"><img src="../wp-content/plugins/twitter-it/images/icon_twitter_2_16x16.png" /></label>';
    echo '      <input type="radio" id="button_3" name="twitter_it[button_image]" value="3" ' . ( $options['button_image'] == '3' ? ' checked="checked"' : '' ) . '/> <label for="button_2"><img src="../wp-content/plugins/twitter-it/images/icon_twitter_3_16x16.png" /></label>';
    echo '      <input type="radio" id="button_4" name="twitter_it[button_image]" value="4" ' . ( $options['button_image'] == '4' ? ' checked="checked"' : '' ) . '/> <label for="button_3"><img src="../wp-content/plugins/twitter-it/images/icon_twitter_4_16x16.png" /></label></li>';
    echo '<li>'
       . '<input type="text"'
       . ' name="twitter_it[style]" id="twitter_it[style]" value="'.$options['style'].'"'
       . ' /> '
       . '<label for="twitter_it[label]">'
       . __('Add custom CSS style?')
       . '</label>'       
       . '</li>';    
    echo '</ul>';
    echo twitter_it_admin::box_end();                     
          
    echo twitter_it_admin::box_start('SEO Settings');
    echo '<ul>';
    echo '<li><img src="images/icon_twitter_16x16.png" /></li>';
    echo '<li><input type="checkbox" name="twitter_it[nofollow]" id="twitter_it[nofollow]" ' . ( $options['nofollow'] ? ' checked="checked"' : '' ) . ' /> <label for="twitter_it[nofollow]"> ' . __('Mark the twitter-link as nofollow? (Better for search-engines)') . '</label></li>';
    echo '</ul>';
    echo twitter_it_admin::box_end();                        
                
    echo twitter_it_admin::box_start('Short URL Settings');          
        echo '<ul>'
             .'<li>' . __('Please select the service you like to use.')
                     . ' '
                     . __('Some services require your user-account, so please fill them in.')
             .'</li>'
             .'<li><input type="radio"  name="twitter_it[service]"          value="tinyurl.com" ' . ($options['service'] == 'tinyurl.com' ? 'checked' : '') . ' /><a href="http://tiny-url.com" target="_blank">Tiny URL</a> ' . __('(Free, no user-account required)') . '</li>'
             .'<li><input type="radio"  name="twitter_it[service]"          value="bit.ly"      ' . ($options['service'] == 'bit.ly' ? 'checked' : '') . ' /><a href="http://bit.ly" target="_blank">Bit.ly</a> ' . __('(Provides statistics about clicks, but requires user and API-key, <a href="http://bit.ly/account/" target="_blank">see here</a> on the right side)') . '</li>'
             .'<li><input type="text"   name="twitter_it[service_user]"     value="'.$options['service_user'].'" /> <label>' . __('User') . '</label></li>'             
             .'<li><input type="text"   name="twitter_it[service_password]" value="'.$options['service_password'].'" /> <label>' . __('Password or API-Key') . '</label></li>';
        echo ' <li><input type="checkbox" name="twitter_it[clear_cache]" id="twitter_it[clear_cache]" ' . ( $options['clear_cache'] ? ' checked="checked"' : '' ) . ' /> <label for="twitter_it[clear_cache]"> ' . __('Clear cache? This refreshs all short-urls.') . '</label></li>';             
        echo '</ul>';
    echo twitter_it_admin::box_end();                  
          
          echo twitter_it_admin::site_template(true);

	}
	
	function site_template($footer = false) {
    if(!$footer) {
		$string .= '<div class="wrap">'
        .'  <form method="post" action="">'
        .'  <h2>' . __('Twitter It!') . ' ' . __('Settings') . '</h2>'
        .'    <div id="poststuff" class="metabox-holder">';
    } else {
          $string .= '<p class="submit">'
            . '<input type="submit"'
              . ' value="' . attribute_escape(__('Save Changes')) . '"'
              . ' />'
            . '</p>';

          echo '</form>';    
        $string .= '</div>'; //meta-box-sortabless
        $string .= '</div>'; //has-sidebar-content
        $string .= '</div>'; //has-sidebar   
        $string .= '</div>'; //metabox-holder        
        $string .= '</div>'; //class wrap
    }
    return $string;
	}
	
	function sidebar_template($footer = false) {
    if(!$footer) {
        $string .= '<div class="inner-sidebar">';
        $string .= '<div class="meta-box-sortabless ui-sortable" id="side-sortables">';
    } else {
        $string .= '</div>'; //meta-box-sortabless
        $string .= '</div>'; //inner-sidebar
        $string .= '<div class="has-sidebar sm-padded">';
        $string .= '<div class="has-sidebar-content">';   
        $string .= '<div class="meta-box-sortabless">';        
    }
    return $string;
	}	
	
	function box_start($title){
    $string .= '<div id="dashboard_right_now" class="postbox">';
    $string .= '<div class="handlediv" title="Klicken zum Umschalten"></div>';
    $string .= '<h3 class="hndle">' . $title .'</h3>';
    $string .= '<div class="inside">';
    return $string;
	}
	
	function box_end() 
	{
    $string .= '</div>'; //inside
    $string .= '</div>'; //postbox
    return $string;
	}
}
?>