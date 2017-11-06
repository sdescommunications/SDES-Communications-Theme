<?php 
	get_header('second'); the_post();

	$image_url 	= has_post_thumbnail( $post->ID ) ?
	wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full', false ) : null;

	if ( $image_url ) {
		$image_url = $image_url[0];
	}

	<?php if (have_posts()) :
	while (have_posts()) : the_post(); 
	global $post;
	$post_object = $post;
		//Copied from News::toHTML.
	$context['Post_ID'] = $post_object->ID;
	$thumbnailUrl = get_stylesheet_directory_uri() . '/images/blank.png';
	$context['thumbnail']
	= has_post_thumbnail($post_object) 
	? get_the_post_thumbnail($post_object, '', array('class' => 'img-fluid'))
	: "<img src='".$thumbnailUrl."' alt='thumb' class='img-fluid'>";
	$news_link = get_post_meta( $post_object->ID, 'news_link', true );
	$context['permalink'] = get_permalink( $post_object );
	$context['title_link'] = ( '' !== $news_link ) ? $news_link : $context['permalink'];
	$context['title'] = get_the_title( $post_object );
	$news_strapline = get_post_meta( $post_object->ID, 'news_strapline', true );
	$context['news_strapline'] =('' !== $news_strapline ) ? '<div class="news-strapline">'.$news_strapline.'</div>' : '';
	$context['month_year_day'] = get_the_date('F j, Y', $post_object);
	$context['time'] = get_the_time('g:i a', $post_object);
	$context['content'] = get_the_content();
	?>
	<h2 class="page-header news-title"><?= the_title(); ?></h2>
	<div class="news">		
		<?= $context['thumbnail'] ?>
		<div class="news-content">
			<?= $context['news_strapline'] ?>
			<div class="datestamp mb-2">
				Posted on <?= $context['month_year_day'] ?> at <?= $context['time'] ?>
			</div>
			<div class="news-summary">
				<?= wpautop($context['content']) ?>
			</div>
			<a class="btn btn-callout float-right mt-3" href="<?= wp_get_referer() ?>"><i class="fa fa-chevron-left"></i> Back</a>
		</article>
	</section>
</div>
<?php get_footer(); ?>