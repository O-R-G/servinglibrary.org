<?
if (!isset($db)) {
    // only when called via ajax
    $config = $_SERVER["DOCUMENT_ROOT"];
    $config = $config."/open-records-generator/config/config.php";
    require_once($config);
    $db = db_connect("guest");
} 
$page = isset($_GET['page']) ? $_GET['page'] : 0;
$posts_per_page = 100;
$offset = ($page != 0) ? $posts_per_page*$page : 0;
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
while($r = mysqli_fetch_assoc($res)) {
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
