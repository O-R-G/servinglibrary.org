<?
// if (!isset($db)) {
    // only when called via ajax
    $config = $_SERVER["DOCUMENT_ROOT"];
    // $config = $config."/open-records-generator/config/config.php";
    $config .= "/static/php/config_download.php";
    require_once($config);
    $db = db_connect_download("guest");

    function renderRow($r, $filter = ''){
    	$download_file = $title = strtoupper($r['name1']);
		// $source_file = $r['notes'];
		$author = $r['deck'];
		$output = '<div class="time-row flex-container"><div class="download-info">' . $author . ': ' . $title;
    	if(empty($filter))
    	{
    		$timestamp = strtoupper(date("Y M d g:i A", strtotime($r['created'])));
			$ip = $r['body'];
			$output .= ' by '.$ip.' at '.$timestamp . '</div>';
    	}
    	else if($filter == 'totals')
    	{
    		$output .= '</div><div class="download-count">' . $r['count'] . ' downloads</div>';
    	}
    	$output .= '</div>';
    	return $output;
    }
// } 
$page = isset($_GET['page']) ? $_GET['page'] : 0;
$posts_per_page = 100;
$offset = ($page != 0) ? $posts_per_page*$page : 0;
$filter = '';
if(isset($_GET['totals'])){
	$filter = 'totals';
	$sql = "SELECT name1, deck, COUNT(*) as count FROM downloads GROUP BY name1, deck ORDER BY count DESC LIMIT " . $posts_per_page . " OFFSET " . $offset;
}
else{
	$sql = "SELECT * FROM downloads ORDER BY created DESC LIMIT " . $posts_per_page . " OFFSET " . $offset;
	
}
$res = $db->query($sql);		
while($r = mysqli_fetch_assoc($res)) {
	echo renderRow($r, $filter);
}
?>
