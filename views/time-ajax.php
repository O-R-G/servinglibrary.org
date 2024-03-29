<?
// if (!isset($db)) {
    // only when called via ajax
    $config = $_SERVER["DOCUMENT_ROOT"];
    // $config = $config."/open-records-generator/config/config.php";
    $config .= "/static/php/config_download.php";
    require_once($config);
    $db = db_connect_download("guest");

    function renderRow($r, $filter = ''){
		global $filter_items;
    	$output = '<div class="time-row flex-container">';
		if($filter == 'total') {
    		$output .= '<div class="download-info">TOTAL</div>';
			$output .= '<div class="download-all">' . $r['val'] . ' '.$filter_items[$filter]['unit'].'</div>';
    	} else {
    		$download_file = $title = strtoupper($r['name1']);
			// $source_file = $r['notes'];
			$author = strip_tags($r['deck']);
			$output .= '<div class="download-info">' . $author . ': ' . $title;
	    	if(empty($filter))
	    	{
	    		$timestamp = strtoupper(date("Y M d g:i A", strtotime($r['created'])));
				$ip = $r['body'];
				$output .= ' by '.$ip.' at '.$timestamp . '</div>';
	    	}
	    	else if($filter == 'by-item')
	    	{
	    		$output .= '</div><div class="download-count">' . $r['val'] . ' '.$filter_items[$filter]['unit'].'</div>';
	    	}
	    	else if($filter == 'by-item-daily')
	    	{
	    		$output .= '</div><div class="download-daily">' . $r['val'] . ' '.$filter_items[$filter]['unit'].'</div>';
	    	}
    	}
    	$output .= '</div>';
    	return $output;
    }
$page = isset($_GET['page']) ? $_GET['page'] : 0;
$posts_per_page = 100;
$offset = ($page != 0) ? $posts_per_page*$page : 0;
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
if($filter) {
	if($filter == 'total') {
		$sql = "SELECT COUNT(*) as val FROM downloads";
	} else if($filter == 'by-item') {
		$sql = "SELECT name1, deck, COUNT(*) as val FROM downloads GROUP BY name1, deck ORDER BY val DESC LIMIT " . $posts_per_page . " OFFSET " . $offset;
	} else if($filter == 'by-item-daily') {
		$sql = "SELECT name1, deck, (COUNT(*) / DATEDIFF(CURDATE(), MIN(created))) as val FROM downloads GROUP BY name1, deck ORDER BY val DESC LIMIT " . $posts_per_page . " OFFSET " . $offset;
	}
}else {
	$sql = "SELECT * FROM downloads ORDER BY created DESC LIMIT " . $posts_per_page . " OFFSET " . $offset;
}
// if(isset($_GET['totals'])){
// 	$filter = 'totals';
	
// }
// else if(isset($_GET['daily'])){
// 	$filter = 'daily';
	
// }
// else if(isset($_GET['all'])){
// 	$filter = 'all';
	
// }
// else {
	
// }
$res = $db->query($sql);
while($r = mysqli_fetch_assoc($res)) {
	echo renderRow($r, $filter);
}
?>
