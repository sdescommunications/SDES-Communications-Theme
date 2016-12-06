<?php
/**
* Template Name: Content Page Billboard
*/
use SDES\SDES_Static as SDES_Static;

get_header();

$hideBillboard = false; // Could add an option for hiding Billboard in Theme Customizer.
if ( $hideBillboard ) {
	// Could add an adminmsg here.
} else {
	/* If using the WP Nivo Plugin, use the following code instead: */
	// if ( function_exists('show_nivo_slider') ) { show_nivo_slider(); } 
	echo do_shortcode( "[billboard-list tags='". get_post_meta($post->ID, "billboard-meta-box-text", true) ."']" );

}


?>
<!-- content area -->
<div class="container site-content" id="content">
	<?= do_shortcode( '[alert-list show_all="true"]' ); ?>


	<div class="row">
		<br>
		<div class="col-sm-8">
			<?php if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					the_content();
				endwhile;			
			endif;
			wp_reset_query();
			?>
		</div>
		<div class="col-sm-4">
			<?php		

			$sidebar = get_post_meta( $post->ID, 'page_sidecolumn', $single=true );
			echo do_shortcode( $sidebar );
			?>
		</div>	
	</div>


</div> <!-- /DIV.container.site-content -->
<?php
get_footer();