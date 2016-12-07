<?php
	add_action('admin_enqueue_scripts', 'my_admin_script');
	function my_admin_script()
	{
	    wp_enqueue_script('my-admin', get_bloginfo('template_url').'/js/template-option.js', array('jquery'));
	}

	add_filter('admin_init', 'add_google_id');
 
	function add_google_id()
	{
	    register_setting('general', 'google_id', 'esc_attr');
	    add_settings_field('google_id', '<label for="google_id">'.__('Google ID' , 'google_id' ).'</label>' , 'google_id_setting_html', 'general');
	}
	 
	function google_id_setting_html()
	{
	    $value = get_option( 'google_id', '' );
	    echo '<input type="text" id="google_id" name="google_id" value="' . $value . '" />';
	}
?>