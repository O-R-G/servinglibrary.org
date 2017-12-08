<?
$now = date("Y-m-d H:i:s");
$start = "2017-12-08 13:00:00";
$end = "2017-12-08 16:00:00";

$message = "The publications of The Serving Library are currently 
unavailable, as part of the exhibition The Serving <span 
style='text-decoration: line-through;'>Library</span>.";

if ($now > $start && $now < $end)
    die($message);
?>
