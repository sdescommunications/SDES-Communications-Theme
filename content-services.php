<?php
/**
* Template Name: Content Services
*/
use SDES\SDES_Static as SDES_Static;
	
$urls = get_post_meta($post->ID, "service-meta-box-image", true);
$headers = get_post_meta($post->ID, "service-meta-box-header", true);
$footers = get_post_meta($post->ID, "service-meta-box-footer", true);
 $c = 1;
while ($c <= $GLOBALS['NUMBEROFCARDS']) {
	$contents[] = get_post_meta($post->ID, "service_wysiwyg_".$c, true);
	$c++;
}
get_header();
?>
<!-- content area -->
<div class="container site-content" id="content">
	<?= get_template_part( 'includes/template', 'alert' ); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?= get_template_part( 'includes/template', 'heading' ); ?>
		<div class="row">
			<div class="col-sm-12 ">
				<?php the_content(); ?>
				
				<div class="card-columns">
				<?php					
					foreach ($urls as $key => $url) {
					if(!empty($contents[$key]) || !empty($headers[$key]) || !empty($url) ){
				?>
					<div class="card">
						<?php if (!empty($url)) { ?>
							<img class="card-img-top img-fluid" src="<?= $url ?>" alt="...">
						<?php } ?>

						<?php if (!empty($contents[$key]) || !empty($headers[$key])) { ?>						
						<div class="card-block">	
							<h3 class="card-title"><?= $headers[$key] ?></h3>
							<p class="card-text">
								<?= wpautop($contents[$key]) ?>
							</p>														
						</div>
						<?php } ?>

						<?php if (!empty($footers[$key])) { ?>
						<div class="card-footer">
							<?= $footers[$key] ?>
						</div>
						<?php } ?>
					</div>						
				<?php											
					}
				}
				?>
				</div>

							
		</div>
	<?php endwhile;
	else: 
		SDES_Static::Get_No_Posts_Message();
	endif; ?>

</div> <!-- /DIV.container.site-content -->
<?php
get_footer();
