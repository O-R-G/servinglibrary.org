<?php

// $uri = explode('/', $_SERVER['REQUEST_URI']);
$request = $_SERVER['REQUEST_URI'];
$requestclean = strtok($request,"?");
$uri = explode('/', $requestclean);
$view = "views/";
// debug
// ini_set('display_errors', 1);
require_once("views/head.php");
require_once("views/closed.php");
if($uri[1])
{
	if($uri[1] == "time"){
		require_once('views/time.php');
	}
	else if($uri[1] == "table"){
		require_once('views/table.php');
	}
	else if(strcmp($uri[1], "journal") === 0 && count($uri) == 4)
		require_once('views/read.php');
	else if($uri[1] == "collect")
		require_once('views/collect.php');
	else if($uri[1] == "collection" && !isset($uri[2]))
		require_once('views/collection.php');
	else if($uri[1] == "collection" && isset($uri[2]))
		require_once('views/words.php');
	else if($uri[1] == "contact" || $uri[1] == "subscribe") {
    	$showsubscribe = true;
		require_once('views/words.php');
	}
	// else if($uri[1] == "shop" && count($uri) == 2) 
	// 	require_once('views/shop.php');
	else
		require_once('views/words.php');
}
else
	require_once('views/home.php');

if( ($uri[1] == 'journal' && count($uri) == 3) ||
	($uri[1] == "shop" && count($uri) == 2) ||
	($uri[1] == "shop" && count($uri) == 3) ||
	( count($uri) == 2 && (
		$uri[1] == "programs" ||
		$uri[1] == "introduction" ||
		$uri[1] == "publication" ||
		$uri[1] == "collection-new"
	))
	
)
	require_once('views/paypal.php');

// show the things

// require_once($view);
if($uri[1] && $uri[1] == "time")
	require_once("views/clock.php");
else
	require_once("views/badge.php");
require_once("views/foot.php");
?>
