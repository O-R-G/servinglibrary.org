<?php

// $uri = explode('/', $_SERVER['REQUEST_URI']);
$request = $_SERVER['REQUEST_URI'];
$requestclean = strtok($request,"?");
$uri = explode('/', $requestclean);
$view = "views/";
// debug
// ini_set('display_errors', 1);

if($uri[1])
{
	if($uri[1] == "time")
		$view.="time.php";
	else if($uri[1] == "table")
		$view.= "table.php";
	else if(strcmp($uri[1], "journal") === 0 && count($uri) == 4)
		$view.= "read.php";
	else if($uri[1] == "collect")
		$view.= "collect.php";
	else if($uri[1] == "collection" && !$uri[2])
		$view.= "collection.php";
	else if($uri[1] == "collection" && $uri[2])
		$view.= "words.php";
	else if($uri[1] == "contact" || $uri[1] == "subscribe") {
        	$showsubscribe = true;
		$view.="words.php";
	}
	else if($uri[1] == "shop" && isset($uri[2]) && $uri[2] == 'issues') 
		$view.="shop.php";
	else
		$view.="words.php";
}
else
	$view.="home.php";

// show the things
require_once("views/head.php");
require_once("views/closed.php");
require_once($view);
if($view == "views/time.php")
	require_once("views/clock.php");
else
	require_once("views/badge.php");
require_once("views/foot.php");
?>
