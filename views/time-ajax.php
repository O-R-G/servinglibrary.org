<?
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/open-records-generator/config/config.php";
require_once($config);
$db = db_connect("guest");

$page = $_GET['page'];
$posts_per_page = 100;

if($page)
	$offset = $posts_per_page*$page;
else
	$offset = 0;

$sql = "SELECT
			id,
			name1,
			deck,
			body,
			created,
			notes
		FROM
			downloads
		ORDER BY
			created DESC
		LIMIT $posts_per_page
		OFFSET $offset";

$res = $db->query($sql);		

while($r = mysqli_fetch_assoc($res))
{
	$download_file = $title = strtoupper($r['name1']);
	$source_file = $r['notes'];
	$author = $r['deck'];
	$timestamp = strtoupper(date("Y M d g:i A", strtotime($r['created'])));
	$ip = $r['body'];
	$id = $r['id'];
	echo $author.": ".$title." by ".$ip." at ".$timestamp;
	?><br><?
}
?>