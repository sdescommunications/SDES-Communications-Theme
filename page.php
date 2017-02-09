<?php
/**
* Template Name: Full Width
*/
use SDES\SDES_Static as SDES_Static;

get_header();
?>
<!-- content area -->
<div class="container site-content" id="content">
	<?= get_template_part( 'includes/template', 'alert' ); ?>


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?= get_template_part( 'includes/template', 'heading' ); ?>
	<div class="row">
		<div class="col-sm-12">
			<?php the_content(); ?>
		</div>
	</div>
<?php endwhile;
else: 
	SDES_Static::Get_No_Posts_Message();
endif; ?>

</div> <!-- /DIV.container.site-content -->
<?php
get_footer();
