<?php
/**
* Template Name: Cards
*/
use SDES\SDES_Static as SDES_Static;
	


 $c = 1;
while ($c <= $GLOBALS['NUMBEROFCARDS']) {
	$contents[] = get_post_meta($post->ID, "service_wysiwyg_".$c, true);
	$images [] = get_post_meta($post->ID, "card_image_".$c, true);
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
				
				<div class="card-deck">
				<?php
					$c=1;					
					foreach ($contents as $key => $content) {					
				?>
					<?php if (!empty($contents[$key]) && !empty($images[$key])) { ?>
					<div class="card">						
					
						<?= misha_image_uploader_field( 'card_image_'.$c, $images[$key], 2 ) ?>
											
						<div class="card-block">							
							<div class="card-text">
								<?= wpautop($contents[$key]) ?>
							</div>																					
						</div>

					</div>

					<?php } //end of if

						if ((($key+1) % 4) == 0){ ?>
							</div>
							<div class="card-deck">
					<?php
					 
						}//end of if
					}//end of foreach
					
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
