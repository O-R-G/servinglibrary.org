<?
// path to config file
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/open-records-generator/config/config.php";
require_once($config);

// specific to this 'app'
$config_dir = $root."/config/";
require_once($config_dir."url.php");
// require_once($config_dir."request.php");

$db = db_connect("guest");

$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();

$title = "The Serving Library";

$is_mobile = false;
?>
<!DOCTYPE html>
<html>
	<head>
		<title><? echo $title; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/global.css">
		<link rel="stylesheet" type="text/css" media="all" href="/static/css/mtdbt2f.css">
		<link rel="shortcut icon" href="/media/png/icon.png">
		<script type="text/javascript" src="/static/js/global.js"></script>
		<script src="/static/pde/processing-1.4.1.min.js"></script>
	</head>
	<body>
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
			