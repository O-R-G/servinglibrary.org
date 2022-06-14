<?
// path to config file
// $config = $_SERVER["DOCUMENT_ROOT"];
// $config = $config."/open-records-generator/config/config.php";
// require_once($config);
require_once('open-records-generator/config/config.php');
require_once('open-records-generator/config/url.php');

// specific to this 'app'
# $config_dir = $root."/config/";
// require_once($config_dir."url.php");
// require_once($config_dir."request.php");

$db = db_connect("guest");

$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();

if($uu->id){
	$item = $oo->get($uu->id);
}
else if(!empty($_GET)) {
	try {
		$uri_temp = $uri;
		array_shift($uri_temp);
		$temp = $oo->urls_to_ids($uri_temp);
		$id = end($temp);
		$item = $oo->get($id);
	} catch(Exception $err) {
		$item = $oo->get(0);
	}
}
else if( $uri[1] == 'join' && count($uri) == 2 )
{
	try{
		$temp = $oo->urls_to_ids(array('contact', 'join'));
		$id = end($temp);
		$item = $oo->get($id);
	} catch(Exception $err) {
		$item = $oo->get(0);
	}
}

$title = "The Serving Library";
$is_mobile = false;

// $isShop = ($uri[1] == 'shop' && count($uri) == 2);
// $isSubscriptions = ($uri[1] == 'shop' && count($uri) == 3 && $uri[2] == 'subscriptions');

$bodyClass = '';
if($uri[1] == 'shop')
	$bodyClass .= 'shop testCart ';


?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $title; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/main.css">
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/mtdbt2f.css">
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/shop.css">
		<link rel="apple-touch-icon" href="/media/png/icon.png" />
		<script type="text/javascript" src="/static/js/global.js"></script>
		<script src="/static/js/analytics.js"></script>
	</head>
	<body class="<?= $bodyClass; ?>" <?= $paypal_layout ? 'paypal_layout="'.$paypal_layout.'"' : ''; ?>> 
		<div id='timeWrapper' class='timeContainerWrapper'>
			<div id='time' class='timeContainer time'>
				<script type="text/javascript">
					// Init clock		
					var currenttime = '<?php echo date("F d, Y H:i:s", time());?>';
					var montharray = new Array("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
					var serverdate = new Date(currenttime);
					window.onload = function()
					{
						displayTime();
						setInterval(displayTime, 1000);
					};
				</script>
				<a href='/time' id='serverTime'></a>
			</div>
		</div>
		<div id="main-container" class="mainContainer">			
