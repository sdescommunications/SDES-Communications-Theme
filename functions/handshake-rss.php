<?php 

// Open cURL instance for the UCF Event Calendar RSS feed.
$ch = curl_init( $_POST["url"] );

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

$limit = count( $xml->channel->item );
// Set limit if items returned are smaller than limit.
$count = ( count( $xml->channel->item ) > $limit ) ? $limit : count( $xml->channel->item );

ob_start();
?>
<div class="card">
	<h3 class="card-header" style="background: 	black; color: #ffcc00; "><?= $xml->channel->title ?> - <?= $count ?></h3>
	<div style="height: 300px; overflow: auto;">
		<?php
		if ( 0 === count( $xml->channel->item ) ){			
			echo 'Sorry, no events could be found';
		} else {

				// Loop through until limit.
			for ( $i = 0; $i < $count; $i++ ) {
					// Prepare xml output to html.
				$title = htmlentities( $xml->channel->item[ $i ]->title );
				$description = htmlentities( $xml->channel->item[ $i ]->description );
				$link = htmlentities( $xml->channel->item[ $i ]->link );
				$pubdate = htmlentities( $xml->channel->item[ $i ]->pubDate );

				?>
				<div class="card-block">
					<h4 class="card-title"><a href="<?= $link ?>" ><?= $title ?> <i class="fa fa-external-link"></i></a></h4>
					<p class="card-text" style="border-bottom: 1px dashed gray;"><?= substr($description, 0, 125) ?> ...<br><small><em><?= substr($pubdate, 0, 17) ?></em></small></p>				
				</div>
				<?php
			}
		}
		?>
	</div>
</div>		
