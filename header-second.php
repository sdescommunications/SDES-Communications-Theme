<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>

		<?php 
			echo wp_title( '&bull;', true, 'right' ); 
			echo str_replace('&lt;br&gt;', ' ', get_bloginfo('name')); 
		?> 
		&bull; UCF

	</title>

	

	<link rel="shortcut icon" href="//it-dev.sdes.ucf.edu/testing/vp/images/favicon_black.png" />
	<link rel="apple-touch-icon" href="//it-dev.sdes.ucf.edu/testing/vp/images/apple-touch-icon.png" />
	<link rel="stylesheet" href="//it-dev.sdes.ucf.edu/testing/vp/scss/bootstrap.css" />
	<link rel="stylesheet" href="//it-dev.sdes.ucf.edu/testing/vp/css/cards.css" media="screen" />

	<!--[if lt IE 10]>	
	<link rel="stylesheet" href="css/why.css" media="screen" />
	<![endif]-->	

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" id="ucfhb-script" src="//universityheader.ucf.edu/bar/js/university-header.js"></script>

	<script src="//use.fontawesome.com/48342ef48c.js"></script>
	
	<script type="text/javascript" src="//it-dev.sdes.ucf.edu/testing/vp/js/tether.min.js"></script>
	<script type="text/javascript" src="//it-dev.sdes.ucf.edu/testing/vp/js/bootstrap.min.js"></script>	


	<!--[if lt IE 10]>
	<script type="text/javascript" src="http://it-dev.sdes.ucf.edu/testing/vp/js/modernizr-custom.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
	<![endif]-->

</head>
<body>
	<?php

		$google_id = get_option('google_id', '');
		if(!empty($google_id)){
      
	?>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', '<?= get_option("google_id") ?>', 'auto');
		  ga('send', 'pageview');

		</script>
	<?php
		}
	?>
	<script>
		jQuery(function($) {
			$('.navbar .dropdown').hover(function() {
				$(this).find('.dropdown-menu').first().stop().fadeIn("fast");

			}, function() {
				$(this).find('.dropdown-menu').first().stop().fadeOut("fast");

			});

			$('.navbar .dropdown > a').click(function(){
				location.href = this.href;
			});

		});

		$(document).ready(function() {
			$("body").tooltip({ selector: '[data-toggle=tooltip]' });
		});			
	</script>
	<!-- header -->
	<header>
		<div class="skip text-center hidden-xs-up" id="skpToContent">
			<a href="#content"><i class="fa fa-lg fa-chevron-down"><span class="sr-only">Skip to Content</span></i></a>
		</div>
		<div class="header-content">
			<div class="container">
				<section class="site-title">			
					<article>
						<a href="<?= site_url() ?>" class="float-left mb-3">
							<?= html_entity_decode(get_bloginfo('name')) ?>
						</a>
						<span class="site-subtitle float-left hidden-md-down">
							<a href="//www.sdes.ucf.edu">
								<?= html_entity_decode(get_bloginfo('description')) ?>
							</a>
						</span>				
					</article>
					<aside class="text-lg-right">
						<a class="translate" href="#" data-toggle="tooltip" data-placement="right" title="Translate This Page!"><i class="fa fa-globe"></i></a>
					</aside>			
				</section>
			</div>

			<!-- navigation -->
			<nav class="navbar navbar-dept navbar-toggleable-md">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fa falg fa-bars"></i>
				</button>
				<?= wp_nav_menu(array(
											'theme_location' => 'main-menu', 
											'menu_class' => 'navbar-nav',
											'container_class' => 'collapse navbar-collapse',
											'container_id' => 'navbarSupportedContent',
											'walker' => new Nav_Menu(),
										)) ?>			
			</nav>
		</div>				

		<div class="header-break hidden-md-down">
			<?php
				$breaker = (!empty(get_option('breaker_id', ''))) ? get_option('breaker_id', '') : get_stylesheet_directory_uri() . '/images/breaker.jpg';
			?>

			<img src="<?= $breaker ?>" class="img-fluid" />
		</div>

		<?= do_shortcode( '[alert-list show_all="true"]' ); ?>
	</header>