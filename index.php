<?php

$uri = explode('/', $_SERVER['REQUEST_URI']);
$view = "views/";

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
	else if($uri[1] == "objects" && !$uri[2])
		$view.= "objects.php";
	else if($uri[1] == "objects" && $uri[2])
		$view.= "words.php";
	else if($uri[1] == "join") {
        	$showsubscribe = true;
		$view.="words.php";
	}
	else
		$view.="words.php";
}
else
	$view.="home.php";

// show the things
require_once("views/head.php");
if($view == "views/time.php")
	require_once("views/clock.php");
else
	require_once("views/badge.php");
require_once($view);
require_once("views/foot.php");
?>
