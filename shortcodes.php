<?php
/**
 * Add and configure Shortcodes for this theme.
 * Relies on the implementation in ShortcodeBase.
 */

namespace SDES\BaseTheme\Shortcodes;

use \StdClass;
use \Exception;
use \SimpleXMLElement;

require_once( get_stylesheet_directory().'/functions/class-shortcodebase.php' );
use SDES\Shortcodes\ShortcodeBase;

require_once( 'functions/class-sdes-static.php' );
use SDES\SDES_Static as SDES_Static;

require_once( get_stylesheet_directory().'/vendor/autoload.php' );
use Underscore\Types\Arrays;

/**
 * [menuPanel] - Return an in-line menu panel (DIV.panel) using a user-configured Menu.
 * Available attributes:
 * name      => The "Menu Name" of the menu under Appearance>Menus, e.g., Pages
 * heading   => Display an alternate heading instead of the menu Id.
 *
 * Example:
 * [menuPanel name="Other Resources" heading="An Alternate heading"]
 */
class sc_menuPanel extends ShortcodeBase {
	public
	$name = 'Menu Panel',
	$command = 'menuPanel',
	$description = 'Show panelled menu, usually in sidecolumns.',
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true,
	$params      = array(
		array(
			'name'      => 'Menu Name',
			'id'        => 'name',
			'help_text' => 'The menu to display.',
			'type'      => 'text',
			),
		array(
			'name'      => 'Heading',
			'id'        => 'heading',
			'help_text' => 'A heading to display (optional).',
			'type'      => 'text',
			),
	); // The parameters used by the shortcode.

	function __construct() {
		$menus = wp_get_nav_menus();
		$choices = array();
		foreach ( $menus as $menu ) {
			if ( ! is_wp_error( $menu ) && ! array_key_exists( 'invalid_taxonomy', $menu ) && ! empty( $menu ) ) {
				$choices[] = array( 'value' => $menu->slug, 'name' => $menu->name );
			}
		}
		$new_name_param = Arrays::from( $this->params )
		->find( function( $x ) { return 'name' === $x['id']; } )
		->set( 'type', 'dropdown' )
		->set( 'choices', $choices )
		->obtain();
		$other_params = Arrays::from( $this->params )
		->filter( function( $x ) { return 'name' !== $x['id']; } )
		->obtain();
		$this->params = array_merge( array( $new_name_param ), $other_params );
	}

	public static function callback( $attrs, $content = null ) {
		$attrs = shortcode_atts(
			array(
				'name' => 'Pages',
				'heading' => $attrs['name'],
				'style' => 'max-width: 697px;',
				), $attrs
			);
		// Check for errors
		if ( ! is_nav_menu( $attrs['name'] ) ) {
			$error = sprintf( 'Could not find a nav menu named "%1$s"', $attrs['name'] );
			// Output as HTML comment when not logged in or can't edit.
			$format_error =
			( SDES_Static::Is_UserLoggedIn_Can( 'edit_posts' ) )
			? '<p class="bg-danger text-danger">Admin Alert: %1$s</p>'
			: '<!-- %1$s -->';
			$error = sprintf( $format_error, $error );
			return $error;
		}
		// Sanitize input and set context for view.
		$context['heading'] = esc_html( $attrs['heading'] );
		$context['menu_items'] = wp_get_nav_menu_items( esc_attr( $attrs['name'] ) );
		$context['style'] = esc_attr( $attrs['style'] );
		return static::render( $context );
	}

	/**
	 * Render HTML for a "menuPanel" shortcode with a given context.
	 * Context variables:
	 * heading    => The panel-heading.
	 * menu_items => An array of WP_Post objects representing the items in the menu.
	 * style  => Value for the css attribute "style" on the container div.
	 */
	public static function render( $context ) {
		ob_start();
		?>
		<div class="panel panel-warning menuPanel" style="<?=$context['style']?>">
			<div class="panel-heading"><?=$context['heading']?></div>
			<div class="list-group">
				<?php
				foreach ( (array) $context['menu_items'] as $key => $menu_item ) {
					$title = $menu_item->title;
					$url = SDES_Static::url_ensure_prefix( $menu_item->url );
					$class_names = SDES_Static::Get_ClassNames( $menu_item, 'nav_menu_css_class' );
					?>
					<a href="<?=$url?>" class="list-group-item <?=$class_names?>"><?=$title?></a>
					<?php  } ?>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}
	}

/**
 * [panel] - Wrap HTML in a Boostrap CSS panel.
 */
class sc_panel extends ShortcodeBase {
	public
	$name        = 'Panel',
	$command     = 'panel',
	$description = 'Wraps content in a bootstrap panel.',
	$render      = false,
	$params      = array(
		array(
			'name'      => 'Header',
			'id'        => 'header',
			'help_text' => 'A header for the panel.',
			'type'      => 'text',
			'default'   => '',
			),
		),
	$callback    = 'callback',
	$wysiwyg     = true;

	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'header' => '',
				'footer' => '',
				'class' => 'panel-warning',
				'style' => 'max-width: 697px;',
				), $attr
			);
		ob_start();
		?>
		<div class="panel <?= $attr['class'] ? $attr['class'] : ''; ?>" <?= $attr['style'] ? ' style="' . $attr['style'] . '"' : '';?> >
			<div class="panel-heading">
				<h3 class="panel-title"><?= $attr['header'] ?></h3>
			</div>
			<div class="panel-body">
				<?= apply_filters( 'the_content', $content ); ?>
			</div>
			<?php if ( '' !== $attr['footer'] ) : ?>
				<div class="panel-footer"><?= $attr['footer'] ?></div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}


/**************** SHORTCODE Boilerplate START **********************
 * [myShortcode] - Shortcode description.
 * Available attributes:
 * attr1 => Description of attr1.
 * attr2 => Description of attr2.
 *
 * Example:
 * [myShortcode attr1="SomeValue" attr2="AnotherValue"]
 */
function sc_myShortcode( $attrs, $content = null ) {
	// Default attributes.
	SDES_Static::set_default_keyValue( $attrs, 'attr1', 'SomeValue' );
	SDES_Static::set_default_keyValue( $attrs, 'attr2', 'AnotherValue' );
	// Sanitize input.
	$attrs['attr1'] = esc_attr( $attrs['attr1'] );
	$attrs['attr2'] = esc_attr( $attrs['attr2'] );

	// Shortcode logic.

	// Set context for view.
	$context['disp1'] = $attrs['attr1'];
	$context['disp2'] = $attrs['attr2'];
	// Render HTML.
	return rencer_sc_myShortcode( $context );
}
add_shortcode( 'myShortcode', 'sc_myShortcode' );
/**
 * Render HTML for a "myShortcode" shortcode with a given context.
 * Context variables:
 * disp1 => Description.
 * disp2 => Description.
 */
function render_sc_myShortcode( $context ) {
	ob_start();
	?>
	<div>Some: <?=$context['disp1']?></div>
	<div>Another: <?=$context['disp2']?></div>
	<?php
	return ob_get_clean();
}
/**************** SHORTCODE Boilerplate END   **********************/

require_once( get_stylesheet_directory().'/functions/class-shortcodebase.php' );

/**
 * [row] - Wrap HTML in a Boostrap CSS row.
 *
 * @see https://github.com/UCF/Students-Theme/blob/d56183079c70836adfcfaa2ac7b02cb4c935237d/shortcodes.php#L454-L504
 */
class sc_row extends ShortcodeBase {
	public
	$name        = 'Row',
	$command     = 'row',
	$description = 'Wraps content in a bootstrap row.',
	$render      = false,
	$params      = array(
		array(
			'name'      => 'Add Container',
			'id'        => 'container',
			'help_text' => 'Wrap the row in a container div',
			'type'      => 'checkbox',
			),
		array(
			'name'      => 'Additional Classes',
			'id'        => 'class',
			'help_text' => 'Additional css classes',
			'type'      => 'text',
			),
		array(
			'name'      => 'Inline Styles',
			'id'        => 'style',
			'help_text' => 'Inline css styles',
			'type'      => 'text',
			),
		),
	$callback    = 'callback',
	$wysiwyg     = true;

	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'container' => 'false',
				'class'     => '',
				'style'    => '',
				), $attr
			);

		ob_start();
		?>
		<?php if ( 'true' === $attr['container'] ) : ?>
			<div class="container">
			<?php endif; ?>
			<div class="row <?php echo $attr['class'] ? $attr['class'] : ''; ?>"<?php echo $attr['style'] ? ' style="' . $attr['style'] . '"' : '';?>>
				<?php echo apply_filters( 'the_content', $content ); ?>
			</div>
			<?php if ( 'true' === $attr['container'] ) : ?>
			</div>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}
}

/**
 * [column] - Wrap HTML in a Boostrap CSS column.
 *
 * @see https://github.com/UCF/Students-Theme/blob/d56183079c70836adfcfaa2ac7b02cb4c935237d/shortcodes.php#L506-L650
 */
class sc_column extends ShortcodeBase {
	public
	$name        = 'Column',
	$command     = 'column',
	$description = 'Wraps content in a bootstrap column',
	$render      = 'render',
	$params      = array(
		array(
			'name'      => 'Large Size',
			'id'        => 'lg',
			'help_text' => 'The size of the column when the screen is > 1200px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Medium Size',
			'id'        => 'md',
			'help_text' => 'The size of the column when the screen is between 992px and 1199px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Small Size',
			'id'        => 'sm',
			'help_text' => 'The size of the column when the screen is between 768px and 991px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Extra Small Size',
			'id'        => 'xs',
			'help_text' => 'The size of the column when the screen is < 767px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Large Offset',
			'id'        => 'lg_offset',
			'help_text' => 'The offset of the column when the screen is > 1200px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Medium Offset',
			'id'        => 'md_offset',
			'help_text' => 'The offset of the column when the screen is between 992px and 1199px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Small Offset',
			'id'        => 'sm_offset',
			'help_text' => 'The offset of the column when the screen is between 768px and 991px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Extra Small Offset',
			'id'        => 'xs_offset',
			'help_text' => 'The offset of the column when the screen is < 767px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Large Push',
			'id'        => 'lg_push',
			'help_text' => 'Pushes the column the specified number of column widths when the screen is > 1200px (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Medium Push',
			'id'        => 'md_push',
			'help_text' => 'Pushes the column the specified number of column widths when the screen is between 992px and 1199px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Small Push',
			'id'        => 'sm_push',
			'help_text' => 'Pushes the column the specified number of column widths when the screen is between 768px and 991px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Extra Small Push',
			'id'        => 'xs_push',
			'help_text' => 'Pushes the column the specified number of column widths when the screen is < 767px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Large Pull',
			'id'        => 'lg_pull',
			'help_text' => 'Pulls the column the specified number of column widths when the screen is > 1200px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Medium Offset Size',
			'id'        => 'md_pull',
			'help_text' => 'Pulls the column the specified number of column widths when the screen is between 992px and 1199px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Small Offset Size',
			'id'        => 'sm_pull',
			'help_text' => 'Pulls the column the specified number of column widths when the screen is between 768px and 991px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Extra Small Offset Size',
			'id'        => 'xs_pull',
			'help_text' => 'Pulls the column the specified number of column widths when the screen is < 767px wide (1-12)',
			'type'      => 'text',
			),
		array(
			'name'      => 'Additional Classes',
			'id'        => 'class',
			'help_text' => 'Any additional classes for the column',
			'type'      => 'text',
			),
		array(
			'style'     => 'Inline Styles',
			'id'        => 'style',
			'help_text' => 'Any additional inline styles for the column',
			'type'      => 'text',
			),
		),
	$callback    = 'callback',
	$wysiwig     = true;

	public static function callback( $attr, $content = '' ) {
		$attr = array_merge(
			$attr,
			array(
				'class' => '',
				'style' => '',
				)
			);

		// Size classes.
		$classes = array( $attr['class'] ? $attr['class'] : '' );

		$prefixes = array( 'xs', 'sm', 'md', 'lg' );
		$suffixes = array( '', '_offset', '_pull', '_push' );

		foreach ( $prefixes as $prefix ) {
			foreach ( $suffixes as $suffix ) {
				if ( array_key_exists( $prefix.$suffix, $attr ) ) {
					$suf = str_replace( '_', '-', $suffix );
					$classes[] = 'col-' . $prefix . $suf . '-' . $attr[ $prefix . $suffix ];
				}
			}
		}

		$ctxt['cls_str'] = esc_attr( implode( ' ', $classes ) );
		$ctxt['style'] = esc_attr( $attr['style'] );
		$ctxt['content'] = apply_filters( 'the_content', $content );
		return static::render( $ctxt );
	}

	public static function render( $ctxt ) {
		ob_start();
		?>
		<div class="<?= $ctxt['cls_str'] ?>" style="<?= $ctxt['style'] ?>">
			<?= $ctxt['content'] ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

/**
 * [events] - Show an events calendar from events.ucf.edu
 */
class sc_events extends ShortcodeBase {
	public
	$name = 'Events', // The name of the shortcode.
	$command = 'events', // The command used to call the shortcode.
	$description = 'Show events calendar from a feed', // The description of the shortcode.
	$callback    = 'callback',
	$render      = false,
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(
			'name'      => 'Event Id',
			'id'        => 'id',
			'help_text' => 'The calendar_id of the events.ucf.edu calendar.',
			'type'      => 'text',
			),
		array(
			'name'      => 'Header',
			'id'        => 'header',
			'help_text' => 'A header for this events calendar.',
			'type'      => 'text',
			'default'   => 'Upcoming Events',
			),
		array(
			'name'      => 'Limit',
			'id'        => 'limit',
			'help_text' => 'Only show this many items.',
			'type'      => 'number',
			'default'   => 6,
			),
	); // The parameters used by the shortcode.

	/**
	 * @see https://github.com/ucf-sdes-it/it-php-template/blob/e88a085401523f78b812ea8b4d9557ba30e40c9f/template_functions_generic.php#L241-L326
	 */
	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'id' => 41, // SDES Events calendar.
				'limit' => 6,
				'header'    => 'Upcoming Events',
				'timezone' => 'America/New_York',
				), $attr
			);
		if ( null === $attr['id'] ) { return true; }

		// Open cURL instance for the UCF Event Calendar RSS feed.
		$ch = curl_init( "http://events.ucf.edu/?calendar_id={$attr['id']}&upcoming=upcoming&format=rss" );

		// Set cURL options.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		$rss = curl_exec( $ch );
		curl_close( $ch );
		$rss = @utf8_encode( $rss );
		// Disable libxml errors and allow user to fetch error information as needed.
		libxml_use_internal_errors( true );
		try {
			$xml = new SimpleXMLElement( $rss, LIBXML_NOCDATA );
		} catch ( Exception $e ) { }
		// If there are errors.
		if ( libxml_get_errors() ) {
			ob_start();
			?>
			<li>Failed loading XML</li>
			<?php foreach ( libxml_get_errors() as $error ) : ?>
				<li><?= htmlentities( $error->message ) ?></li>
			<?php endforeach;
			return ob_get_clean();
		}

		// Set limit if items returned are smaller than limit.
		$count = ( count( $xml->channel->item ) > $attr['limit'] ) ? $attr['limit'] : count( $xml->channel->item );
		ob_start();
		?>
		<div class="panel panel-warning">
			<div class="panel-heading"><?= $attr['header'] ?></div>
			<ul class="list-group ucf-events">
				<?php
					// Check for items.
				if ( 0 === count( $xml->channel->item ) ) : ?>
				<li class="list-group-item">Sorry, no events could be found.</li>
				<?php
				else :
						// Loop through until limit.
					for ( $i = 0; $i < $count; $i++ ) {
							// Prepare xml output to html.
						$title = htmlentities( $xml->channel->item[ $i ]->title );
						$title = ( strlen( $title ) > 50) ? substr( $title, 0, 45 ) : $title;
						$loc = htmlentities( $xml->channel->item[ $i ]->children( 'ucfevent', true )->location->children( 'ucfevent', true )->name );
						$map = htmlentities( $xml->channel->item[ $i ]->children( 'ucfevent', true )->location->children( 'ucfevent', true )->mapurl );
						$startTime = new \DateTime( $xml->channel->item[ $i ]->children( 'ucfevent', true )->startdate, new \DateTimeZone( $attr['timezone'] ) );
						$context['datetime'] = $startTime->format( DATE_ISO8601 );
						$context['month'] = $startTime->format( 'M' );
						$context['day'] = $startTime->format( 'j' );
						$context['link'] = htmlentities( $xml->channel->item[ $i ]->link );

						?>    
						<li class="list-group-item">
							<div class="date">								
								<span class="month"><?= $context['month'] ?></span>
								<span class="day"><?= $context['day'] ?></span>								
							</div>
							<a class="title" href="<?= $context['link'] ?>"><?= $title ?></a>
							<a class="location" href="<?= $context['link'] ?>"><?= $loc ?></a>
							</a>
							<div class="end"></div>
						</li>
						<?php }
						endif; ?>
					</ul>
					<div class="panel-footer">
						<a href="//events.ucf.edu/?calendar_id=<?= $attr['id'] ?>&amp;upcoming=upcoming">&raquo;More Events</a>
					</div>
				</div>
				<?php
				return ob_get_clean();
			}
		}

/**
 * [socialButton] - Show a button for social network, based on the URL set in the Theme Customizer.
 */
class sc_socialButton extends ShortcodeBase {
	public
	$name = 'Social Button', // The name of the shortcode.
	$command = 'socialButton', // The command used to call the shortcode.
	$description = 'Show a button for a social network.', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(
			'name'      => 'Network',
			'id'        => 'network',
			'help_text' => 'The social network to show.',
			'type'      => 'dropdown',
			'choices' => array(
				array( 'value' => 'facebook', 'name' => 'facebook' ),
				array( 'value' => 'twitter', 'name' => 'twitter' ),
				array( 'value' => 'youtube', 'name' => 'youtube' ),
				),
			),
		array(
			'name'      => 'Class',
			'id'        => 'class',
			'help_text' => 'The wrapper classes.',
			'type'      => 'text',
			'default' => 'col-sm-6 text-center',
			),
	); // The parameters used by the shortcode.

	/**
	 * @see hhttps://github.com/ucf-sdes-it/it-php-template/blob/615ecbcfa0eccffd0e8b5f71501b1b7e78cd5cf7/template_data.php#L1723-L1740
	 * @see https://shs.sdes.ucf.edu/home.php
	 */
	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'network' => '',
				'class' => 'col-sm-6 text-center',
				), $attr
			);
		$ctxt['container_classes'] = esc_attr( $attr['class'] );
		switch ( $attr['network'] ) {
			case 'facebook':
			case 'twitter':
			case 'youtube':
			default:
			$ctxt['network'] = $attr['network'];
			$ctxt['url'] = esc_attr(
				SDES_Static::url_ensure_prefix(
					SDES_Static::get_theme_mod_defaultIfEmpty( 'sdes_rev_2015-' . $attr['network'], '' ) ) );
			$ctxt['image'] = esc_attr( get_stylesheet_directory_uri() . "/images/{$attr['network']}.gif" );
			break;
		}
		if ( '' === $ctxt['url'] ) { return ''; }
		return static::render( $ctxt );
	}

	/**
	 * Render HTML for a "socialButton" shortcode with a given context.
	 * Context variables:
	 * container_classes    => List of css classes for the cotainer div..
	 * url  => The URL of the social network being linked.
	 * image  => The button image.
	 */
	public static function render( $ctxt ) {
		ob_start();
		?>
		<div class="<?= $ctxt['container_classes'] ?>">
			<a href="<?= $ctxt['url'] ?>">
				<img src="<?= $ctxt['image'] ?>" class="clean" alt="<?= $ctxt['network'] ?> button">
			</a>
		</div>
		<?php
		return ob_get_clean();
	}
}

/**
 * [twitterFeed] - Show a Twitter timeline for a given Twitter username.
 * @see https://dev.twitter.com/web/javascript/loading https://dev.twitter.com/web/javascript/loading
 */
class sc_twitterFeed extends ShortcodeBase {
	public
	$name        = 'Twitter Feed',
	$command     = 'twitterFeed',
	$description = 'Display a Twitter feed.',
	$render      = false,
	$closing_tag = false,
	$params      = array(
		array(
			'name'      => 'Username',
			'id'        => 'username',
			'help_text' => 'The username for your Twitter feed.',
			'type'      => 'text',
			'default'   => '',
			),
		array(
			'name'      => 'Widget ID',
			'id'        => 'widgetId',
			'help_text' => 'The ID for your Twitter account. After logging in, go to: https://twitter.com/settings/widgets/new',
			'type'      => 'text',
			'default'   => '',
			),
		),
	$callback    = 'callback',
	$wysiwyg     = true;

	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'username' => '',
				'widgetId' => '',
				), $attr
			);
		ob_start();
		?>
		<a class="twitter-timeline" href="//twitter.com/<?= $attr['username']; ?>" data-widget-id="<?= $attr['widgetId']; ?>">Tweets by @<?= $attr['username']; ?></a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		<?php
		return ob_get_clean();
	}
}

require_once( get_stylesheet_directory().'/custom-posttypes.php' );
use SDES\BaseTheme\PostTypes\Alert;
/**
 * Use code from the Alert class in a shortcode.
 * Extending Alert to add ContextToHTML, assuming responsiblity for sanitizing inputs.
 */
class AlertWrapper extends Alert {
	public static function ContextToHTML( $alert_context ) {
		return static::render_to_html( $alert_context );
	}
}

/**
 * [alert] - Show a single, ad-hoc alert directly in a page's content.
 */
class sc_alert extends ShortcodeBase {
	public
	$name = 'Alert (Ad hoc)', // The name of the shortcode.
	$command = 'alert', // The command used to call the shortcode.
	$description = 'Show an alert on a single page.', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(
			'name'      => 'Is Unplanned',
			'id'        => 'is_unplanned',
			'help_text' => 'Show the alert as red instead of yellow.',
			'type'      => 'checkbox',
			'default'   => true,
			),
		array(
			'name'      => 'Title',
			'id'        => 'title',
			'help_text' => 'A title for the alert (shown in bold).',
			'type'      => 'text',
			'default'   => 'ALERT',
			),
		array(
			'name'      => 'Message',
			'id'        => 'message',
			'help_text' => 'Message text for the alert.',
			'type'      => 'text',
			'default'   => 'Alert',
			),
		array(
			'name'      => 'URL',
			'id'        => 'url',
			'help_text' => 'Make the alert a link.',
			'type'      => 'text',
			'default'   => '',
			),
	); // The parameters used by the shortcode.


	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'title' => 'ALERT',
				'message' => 'Alert',
				'is_unplanned' => true,
				'url' => null,
				), $attr
			);
		$attr['is_unplanned'] = filter_var( $attr['is_unplanned'], FILTER_VALIDATE_BOOLEAN );

		// Create and sanitize mocks for WP_Post and metadata using the shortcode attributes.
		$alert = new StdClass;
		$alert->post_title = esc_attr( $attr['title'] );
		$alert->post_content = esc_attr( $attr['message'] );
		$metadata_fields = array(
			'alert_is_unplanned' => $attr['is_unplanned'],
			'alert_url' => esc_attr( SDES_Static::url_ensure_prefix( $attr['url'] ) ),
			);
		$ctxt = AlertWrapper::get_render_context( $alert, $metadata_fields );
		return AlertWrapper::ContextToHTML( $ctxt );
	}
}

class sc_contactblock extends ShortcodeBase{
	public
	$name = 'Contact', // The name of the shortcode.
	$command = 'contactblock', // The command used to call the shortcode.
	$description = 'Show the contact information box without custumizer or cms.', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(			
			'name'      => 'Contact Block',
			'id'        => 'contactname',
			'help_text' => 'This is the Title of the Contact block you would like to display.',
			'type'      => 'text',
			),
		);

	public static function callback( $attr, $content = '' ) {
		//get the post_id based on title given by user
		$id = get_posts(array(
			'post_type' => 'contact',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			), 'OBJECT');
		if(!empty($id)){
			foreach($id as $item){			
				if(strtolower($item->post_title) == strtolower($attr['contactname'])){	
					return static::render( $item->ID );
				}	//end of if		
			}	//end of for
		}else{
			return '<div class="alert alert-danger">Verbage to say go to contact and add a contact named main.</div>';
		}	//end of ifelse

		return '<div class="alert alert-danger">No contact block exists with this name.</div>';
		
	}

	public static function render ( $attr ){
		
		$data = get_post_meta($attr);

		ob_start();
		?>	
		
		<table class="table table-condensed table-striped table-bordered">
			<tbody>
				<?php if(!empty($data['contact_Hours'][0])) { ?>
				<tr>
					<th scope="row">Hours</th>
					<td><?= $data['contact_Hours'][0] ?></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_phone'][0])) { ?>
				<tr>
					<th scope="row">Phone</th>
					<td><a href="tel:<?= $data['contact_phone'][0] ?>"><?= $data['contact_phone'][0] ?></a></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_fax'][0])) { ?>
				<tr>
					<th scope="row">Fax</th>
					<td><?= $data['contact_fax'][0] ?></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_email'][0])) { ?>
				<tr>
					<th scope="row">Email</th>
					<td><a href="mailto:<?= $data['contact_email'][0] ?>"> <?= $data['contact_email'][0] ?></a></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_room'][0]) && !empty($data['contact_building'][0]) && !empty($data['contact_room'][0])) { ?>
				<tr>
					<th scope="row">Location</th>
					<td><a href="http://map.ucf.edu/?show=<?= $data['contact_map_id'][0] ?>" class="external"><?=	$data['contact_building'][0] ?>, Room <?= $data['contact_room'][0]?></a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
		<?php
		return ob_get_clean();
	}
}

function register_shortcodes() {
	ShortcodeBase::Register_Shortcodes(array(
		__NAMESPACE__.'\sc_row',
		__NAMESPACE__.'\sc_column',
		__NAMESPACE__.'\sc_alert',
		__NAMESPACE__.'\sc_panel',
		__NAMESPACE__.'\sc_menuPanel',
		__NAMESPACE__.'\sc_events',
		__NAMESPACE__.'\sc_socialButton',
		__NAMESPACE__.'\sc_twitterFeed',
		__NAMESPACE__.'\sc_contactBlock',
		));
}
add_action( 'init', __NAMESPACE__.'\register_shortcodes' );
