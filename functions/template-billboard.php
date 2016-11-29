<?php

	function billboard_meta_box_markup($object)
	{
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

	    ?>
	        <div class="inside">
	          <p>
	            <strong>Billboard Tag</strong>
	          </p>
	          <p>
	            <label class="screen-reader-text" for="billboard-meta-box-text">Billboard Tag</label>
	            <input name="billboard-meta-box-text" type="text" value="<?php echo get_post_meta($object->ID, "billboard-meta-box-text", true); ?>">
	          </p>
	          <p>
	            <em>Place the tag that you want to appear in this billboard.</em>
	          </p>
	        </div>
	    <?php  
	}

	function add_custom_meta_box()
	{
	    add_meta_box("billboard-meta-box", "Billboard", "billboard_meta_box_markup", "page", "side", "high", null);
	}

	add_action("add_meta_boxes", "add_custom_meta_box");

	function save_custom_meta_box($post_id, $post, $update)
	{
	    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
	        return $post_id;

	    if(!current_user_can("edit_post", $post_id))
	        return $post_id;

	    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	        return $post_id;

	    $meta_box_text_value = "";

	    if(isset($_POST["billboard-meta-box-text"]))
	    {
	        $meta_box_text_value = $_POST["billboard-meta-box-text"];
	    }   
	    update_post_meta($post_id, "billboard-meta-box-text", $meta_box_text_value);
	   
	}

	add_action("save_post", "save_custom_meta_box", 10, 3);

?>