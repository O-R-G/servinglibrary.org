<?php

$uri = explode('/', $_SERVER['REQUEST_URI']);
$view = "views/";

if($uri[1])
{
	if($uri[1] == "time")
		$view.="time.php";
	else if(strcmp($uri[1], "journal") === 0 && count($uri) == 4)
		$view.= "read.php";
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