<?php

$thorGuardRwcUrl = 'http://rwc.sdes.ucf.edu/programs/risk-management/thor-guard';

$output = null;
$message = null;

$thorguard = "http://ucf.thormobile10.net/rwc/FL0489.xml";
@$xml = simplexml_load_file($thorguard);
$state = $xml ? strtolower($xml->thordata->lightningalert) : 'blank';

switch ($state){
	case 'blank':	
	$color = "default";
	$message .= '<strong>Disconnected</strong>: ThorGuard does not seem to be responding. Please check back later.';
	break;

	case 'allclear':	
	$color = "success";
	$message .= '<strong>All Clear</strong>: ThorGuard reports all-clear for weather in the area.';
	break;

	case 'caution':	
	$color = "info";
	$message .= '<strong>Caution</strong>: ThorGuard reports caution for weather in the area';
	break;

	case 'warning':
	$color = "warning";
	$message .= '<strong>Warning</strong>: ThorGuard reports warning for weather in the area.';
	break;

	case 'redalert':
	$color = "danger";
	$message .= '<strong>Red Alert</strong>: ThorGuard reports Red-Alert for weather in the area.';
	break;

	case 'unknown':
	echo '<div id="loader" class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>';
	return;
	break;

	default:
	return;
}

echo '<div class="alert alert-'.$color.'"><p><strong><a href="'.$thorGuardRwcUrl.'" class="panel-link">Thor Guard Status</a></strong>: '.$message.'</p></div>';
return;

?>

<?php



?>