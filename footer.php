<?php
/**
 * Footer area for the theme, as called by get_footer().
 */

namespace SDES\BaseTheme;
?>
	<div class="blank"></div>
</div>

	<!-- repeated navigation, social media -->
	<div class="site-content-end">
		<div class="container">
			<nav class="navbar navbar-light bg-faded site-nav-repeated">				
				<ul class="nav navbar-nav">
					<?php
						wp_nav_menu( array( 'theme_location' => 'main-menu', 'depth' => 1, 'container' => '', 'items_wrap' => '%3$s', 'fallback_cb' => 'SDES\\BaseTheme\\SDES_Helper::fallback_navbar_list_pages' ) );
					?> 
					<li class="nav-item float-xs-right acrobat"><a class="nav-link" title="Get Acrobat Reader" href="#"><span class="fa fa-file-pdf-o"></span></a></li>
				</ul>
			</div>
		</nav>
	</div>

<?php
	use SDES\SDES_Static as SDES_Static;
	require_once( 'functions/class-sdes-helper.php' );
		use SDES\BaseTheme\SDES_Helper;
	require_once( 'functions/class-render-template.php' );
		use SDES\BaseTheme\Render_Template;

	/**
	 * Logic for displaying footer elements.
	 */
class Footer {
	
	public static function get_header( $position = 'center', $default_header = 'UCF Today News', $template_args = null ) {
		$ctx_header['header'] = SDES_Static::get_theme_mod_defaultIfEmpty( "sdes_rev_2015-footer_header-{$position}", $default_header );
		return Render_Template::footer_header( $ctx_header, $template_args );
	}

	public static function get_feed_links( $position = 'center', $ctx_links = null, $template_args = null ) {
		/*
		 * (libraries like C#'s memorycache, not servers like memcached, redis).
		 * Maybe desarrolla2/cache, doctrine/cache, or something under cache/cache on Packagist.org
		 */
		$rss_url = SDES_Static::get_theme_mod_defaultIfEmpty( "sdes_rev_2015-footer_feed-{$position}", 'http://today.ucf.edu/feed/json' );
		$rss_url = SDES_Static::url_ensure_prefix( $rss_url );
		$default_anchors = SDES_Static::get_rss_links_and_titles( $rss_url );
		SDES_Static::set_default_keyValue( $ctx_links, 'anchors', $default_anchors );
		return Render_Template::footer_links( $ctx_links, $template_args );
	}

	public static function get_nav_menu( $position = 'left' ) {
		return
			wp_nav_menu( array(
				'theme_location' => "footer-{$position}-menu",
				'container' => '',
				'depth' => 1,
				'items_wrap' => '<ol>%3$s</ol>',
				'fallback_cb' => 'SDES\\BaseTheme\\SDES_Helper::fallback_navbar_list_pages',
				'links_cb' => array(
				'Footer::get_feed_links',
				array( $position, array( 'echo' => false ) ),
				),
			) );
	}

	public static function get_static_content( $position = 'left' ) {
		SDES_Static::set_default_keyValue( $args, 'echo', true );

		$output = wp_kses(
			get_option( "sdes_rev_2015-footer_content-{$position}", '' ),
			wp_kses_allowed_html( 'post' ),
		null);

		if ( $args['echo'] ) { echo $output;
		} else { return $output; }
	}

	public static function should_show_static_content( $position = 'center' ) {
		$content = get_option( "sdes_rev_2015-footer_content-{$position}", '' );
		return '' !== $content && ! ctype_space( $content );
	}

	public static function should_show_nav_menu( $position = 'center' ) {
		return SDES_Static::get_theme_mod_defaultIfEmpty( "sdes_rev_2015-footer_showLinks-{$position}", false );
	}

	public static function is_feed_set( $position = 'left' ) {
		return '' !== SDES_Static::get_theme_mod_defaultIfEmpty( "sdes_rev_2015-footer_feed-{$position}", '' );
	}
}
?>
	<!-- footers -->
	<footer class="site-footer-container">

		<!-- main footer -->
		<div class="site-footer">
			<div class="container"> 
				<div class="row">
					<div class="col-md-4">
						<?php
						if ( Footer::should_show_static_content( 'left' ) ) {
							Footer::get_static_content( 'left' );
						} else {
							Footer::get_header( 'left', 'Site Hosted by SDES' );
							if ( Footer::should_show_nav_menu( 'left' ) ) {
								Footer::get_nav_menu( 'left' );
							} elseif ( Footer::is_feed_set( 'left' ) ) {
								Footer::get_feed_links( 'left' );
							} else {
								?>
							<ul>
								<li><a href="//www.sdes.ucf.edu/">SDES Home</a></li>
								<li><a href="//www.sdes.ucf.edu/about">What is SDES? / About</a></li>
								<li><a href="//www.sdes.ucf.edu/departments">SDES Departments</a></li>
								<li><a href="//www.sdes.ucf.edu/events">Division Calendar</a></li>
								<li><a href="//www.sdes.ucf.edu/contact">Contact Us</a></li>
								<li><a href="//www.sdes.ucf.edu/staff">SDES Leadership Team</a></li>
								<li><a href="//creed.sdes.ucf.edu/">The UCF Creed</a></li>
								<li><a href="//it.sdes.ucf.edu/">SDES Information Technology</a></li>
							</ul>
								<?php
							}
						}
							?>
					</div>
					<div class="col-md-4">
						<?php
						if ( Footer::should_show_static_content( 'center' ) ) {
								Footer::get_static_content( 'center' );
						} else {
							Footer::get_header( 'center' );
							if ( Footer::should_show_nav_menu( 'center' ) ) {
								Footer::get_nav_menu( 'center' );
							} else {
								Footer::get_feed_links( 'center' );
							}
						}
						?>
					</div>
					<div class="col-md-4">
						<?php
						if ( Footer::should_show_static_content( 'right' ) ) {
							Footer::get_static_content( 'right' );
						} else {
							
						?>
						
						<?php

							//Gets main contact info and desplays it
							$id = get_posts(array(
								'post_type' => 'contact',
								'post_status' => 'publish',
								'posts_per_page' => -1,
								), 'OBJECT');
							if(!empty($id)){
								foreach($id as $item){			
									if(strtolower($item->post_title) == "main"){	
										$info = $item->ID;
									}	//end of if		
								}	//end of for
							}

							$data = get_post_meta($info);						

						?>
							<h2>Contact</h2>
							<p>
								<?= bloginfo( 'name' ) ?><br>
								<?php if(!empty($data)){ ?>

									<?php if(!empty($data['contact_phone'][0])) { ?>
										Phone: <a href="tel:<?= $data['contact_phone'][0] ?>"><?= $data['contact_phone'][0] ?></a> 
									<?php } ?>

									<?php if(!empty($data['contact_fax'][0])) { ?>
										&#8226; Fax: <?= $data['contact_fax'][0] ?>
									<?php } ?>

									<?php if(!empty($data['contact_email'][0])) { ?>
										&#8226; Email: <a href="mailto:<?= $data['contact_email'][0] ?>"> <?= $data['contact_email'][0] ?></a>
									<?php } ?>

									<?php if(!empty($data['contact_room'][0]) && !empty($data['contact_building'][0]) && !empty($data['contact_room'][0])) { ?>
										&#8226; Location: <a href="//map.ucf.edu/?show=<?= $data['contact_map_id'][0] ?>" class="external"><?=	$data['contact_building'][0] ?>, Room <?= $data['contact_room'][0]?></a>
									<?php } ?>	

								<?php } else{ ?>
									<a href="//www.ucf.edu/phonebook/">UCF Phonebook</a> &#8226; 
									<a href="//events.ucf.edu/">UCF Events</a> &#8226; 
									<a href="//map.ucf.edu/">UCF Map</a> &#8226; 
									<a href="//ucf.custhelp.com/">Ask UCF</a>
								<?php	} ?>

								
							</p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<!-- sub footer -->
		<div class="site-sub-footer">
			<div class="container">
				<div class="row">
					<div class="col-sm-11">
						<div id="accessibility"><a href="//www.sdes.ucf.edu/accessibility"><i class="fa fa-universal-access" aria-hidden="true"></i> Accessibility Statement</a></div>
						<div id="copyright">Copyright &copy; <?= date('Y'); ?> <a href="//www.sdes.ucf.edu/">Student Development and Enrollment Services</a></div>
					</div>
					<div id="sdes_promo" class="col-sm-1"><div id="sdes_promo" class="col-sm-1"><a href="//undergrad.ucf.edu/whatsnext"><img src="//assets.sdes.ucf.edu/images/qep-logo-dark.png" alt="what's next"></a></div></div>
				</div>
			</div>
		</div>
	</footer>
	
	<?php wp_footer(); ?>

</body>
</html>
