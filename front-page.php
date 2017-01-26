<?php
/**
 * Display the Front Page of the site, per the WordPress Template Hierarchy.
 */
use SDES\SDES_Static as SDES_Static;

get_header();

?>

<?= do_shortcode( "[billboard-list tags='home']" ) ?>

<!-- content area -->
<div class="container site-content" id="content">
	<?= do_shortcode( '[alert-list show_all="true"]' ); ?>


	<div class="row">
		<br>
		<div class="col-sm-8">
			<?php 
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						the_content();
					endwhile;
				else:
					$qNews = array('post_type' => 'news');
					$loop = new WP_Query($qNews);
					if ( $loop->have_posts() ) : ?>
						<h2 class="page-header">News and Announcements</h2>
						<?php echo do_shortcode("[news-list]"); 
					else:
						SDES_Static::Get_No_Posts_Message();
					endif;
				wp_reset_query();
				endif; 
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
