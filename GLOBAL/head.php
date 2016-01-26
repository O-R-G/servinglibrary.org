<?php 
	require_once("_Library/systemDatabase.php"); 
	require_once("_Library/systemCookie.php");

	$dev = systemCookie("devCookie", $dev, 0);
	// if (!$dev) die('Under construction . . .');

	// Parse $Id

        $sourceFile = $_REQUEST['sourceFile'];          // no register globals
        $downloadFile = $_REQUEST['downloadFile'];      // no register globals
        $author = $_REQUEST['author'];         		// no register globals
        $issue = $_REQUEST['issue'];         		// no register globals

        $id = $_REQUEST['id'];          		// no register globals
	$ids = explode(",", $id);
	$idFull = $id;
	$id = $ids[count($ids) - 1];	

        $pageName = basename($_SERVER['PHP_SELF'], ".html");
	$documentTitle = "The Serving Library";
	$watch = $_REQUEST['watch'];
	
	// detect mobile
	$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows(phone|ce)|blackberry|tablet'.'|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.'|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT']);

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; 
?>



<!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/Dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?php echo $documentTitle; ?></title>
	<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8" />
	<meta http-equiv="Title" content="<?php echo $documentTitle; ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" media="all" href="GLOBAL/global.css" />
	<link rel="apple-touch-icon" href="MEDIA/PNG/00342.png" />
	<script type="text/javascript" src="GLOBAL/global.js"></script>
	<script src="_Processing/processing-1.4.1.min.js"></script>
</head>
<body onload="displayTime(); setInterval('displayTime()', 1000 )">


<?php
if ( $pageName != "pop") {	
?>
		
	<!-- TIME -->
	
	<div id='timeWrapper' class='timeContainerWrapper'>
	<div id='time' class='timeContainer time'>
	
		<script type="text/javascript">
		<!--
		
		// Init clock
		
		var currenttime = '<?php echo date("F d, Y H:i:s", time());?>';
		var montharray = new Array("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
		var serverdate = new Date(currenttime);
	
		//-->		
		</script>
	
		<a href='time.html' id='serverTime'></a>
		
	</div>
	</div>
	
<?php
}
?>	


<?php	

		if (( $pageName != 'words' ) && ( $debug == 1 )) {

			$html  = "<div id='About' class='aboutContainer body'>";

			if ( $pageName == 'index' ) {
			
				$html .= "<a href='words.html?id=96'>?</a>";
			} 
			$html  .= "</div>";
			echo $html;
		}		

?>	
	
	

	
	
	
	
	
