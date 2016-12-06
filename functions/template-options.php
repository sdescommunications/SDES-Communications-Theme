<?php
	add_action('admin_enqueue_scripts', 'my_admin_script');
	function my_admin_script()
	{
	    wp_enqueue_script('my-admin', get_bloginfo('template_url').'/js/template-option.js', array('jquery'));
	}
?>