<?php
	$GLOBALS['NUMBEROFCARDS'] = 6;

	function service_meta_box_markup($object)
	{
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
		
		$url = get_post_meta($object->ID, "service-meta-box-image");
		$header = get_post_meta($object->ID, "service-meta-box-header");
		$footer = get_post_meta($object->ID, "service-meta-box-footer");

		$c = 1;
	    while ($c <= $GLOBALS['NUMBEROFCARDS']) {
			$content[] = get_post_meta($object->ID, "service_wysiwyg_".$c);
			$c++;
		}

		$c = 1;     
		while($c <= $GLOBALS['NUMBEROFCARDS']) {
		?>
			<h1>Item <?= $c ?></h1>	        

			<div class="inside">
				<p>
					<strong>Content</strong>
				</p>
				<p>	          	
					<?= (empty($content)) ? wp_editor( $content[($c-1)][0], 'service_wysiwyg_'. ($c) ) : wp_editor( '', 'service_wysiwyg_'. ($c) ) ?>
				</p>	          
			</div>

			<div class="inside">
				<p>
					<strong>Image</strong>
				</p>
				<p>
					<input name="service-meta-box-image[]" type="text" value="<?= (!empty($url)) ? $url[0][($c-1)] : '' ?>">
				</p>	          
			</div>
			<div class="inside">
				<p>
					<strong>Header</strong>
				</p>
				<p>
					<input name="service-meta-box-header[]" type="text" value="<?= (!empty($header)) ? $header[0][($c-1)] : '' ?>">
				</p>	          
			</div>

			<div class="inside">
				<p>
					<strong>Footer</strong>
				</p>
				<p>
					<input name="service-meta-box-footer[]" type="text" value="<?= (!empty($footer)) ? $footer[0][($c-1)] : '' ?>">
				</p>	          
			</div>
			<br>
			<hr>
			<br>	
			<?php
			$c++;
		}

		?>

	    <?php 

	}

	function add_custom_meta_box_service()
	{
	    add_meta_box("service-meta-box", "Service", "service_meta_box_markup", "page", "normal", "high", null);
	}

	add_action("add_meta_boxes", "add_custom_meta_box_service");

	function save_custom_meta_box_service($post_id, $post, $update)
	{
	    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
	        return $post_id;

	    if(!current_user_can("edit_post", $post_id))
	        return $post_id;

	    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	        return $post_id;

	    //$meta_box_image_value = "";

	    if(isset($_POST["service-meta-box-image"]))
	    {	
	    	
	        $imageurl = $_POST["service-meta-box-image"];
	        $header = $_POST["service-meta-box-header"];
	        $footer = $_POST["service-meta-box-footer"];
	        $c = 1;
	        while ($c <= $GLOBALS['NUMBEROFCARDS']) {
	        	$content[] = $_POST["service_wysiwyg_".$c];
	        	$c++;
	        }
	    }

	    //exit(var_dump($content));    

	    update_post_meta($post_id, "service-meta-box-image", $imageurl);
	    update_post_meta($post_id, "service-meta-box-header", $header);
	    update_post_meta($post_id, "service-meta-box-footer", $footer);
	     $c = 1;
	    while ($c <= $GLOBALS['NUMBEROFCARDS']) {
	    	update_post_meta($post_id, "service_wysiwyg_".$c, $content[($c-1)]);
	    	$c++;
	    }
	   
	}

	add_action("save_post", "save_custom_meta_box_service", 10, 3);

?>