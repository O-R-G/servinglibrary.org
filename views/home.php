<?

// id of the 'journal' object
// all issues are attached to this object, 
// and all bulletins are attached to one of those issues
$journal_id = 82553;
$journal_url_base = "/journal";

// treat G-e-s-t-a-l-t differently
$gestalt_id = 77066;

$issues = $oo->children($journal_id);

$i = count($issues)-1;
$issues = array_reverse($issues);



// $n = 10;
// require($root."views/home-ajax.php");
// $n--;
// require($root."views/home-ajax.php");

foreach($issues as $issue)
{
	$id = $issue['id'];
	
	// build the url for this issue
	$issue_url = $journal_url_base."/".$issue['url'];
	
	// get all of the bulletins for this issue
	$bulletins = $oo->children($id);
	
	// randomise the array
	shuffle($bulletins);
	
	foreach($bulletins as $b)
	{
		// style title and author
		$title = strtoupper($b['name1']);
		$author = $b['deck'];
		$caption = $author.": ".$title;
		
		// get cover image
		$all_media = $oo->media($b['id']);
		$j = 0;
		while($all_media[$j]['type'] == "pdf")
			$j++;
		
		// get cover image url
		$m_url = m_url($all_media[$j]);
		
		// build bulletin url
		$b_url = $issue_url."/".$b['url'];

		// staged?
	    	$staged = !(substr($title, 0, 1) != "_" && substr($title, 0, 1) != ".");
		
		if (!$staged) {
			?><div class="thumbsContainer issue<? echo $i; ?>">
				<a href="<? echo $b_url; ?>">
					<div class="coverContainer"><?
						// G-e-s-t-a-l-t exception

						if($b['id'] == $gestalt_id and !$is_mobile)
						{
						?>
						<script src="static/pde/processing-1.4.1.min.js"></script>
						<script>
							var thisW = 220; 
							var thisH = 313;
						</script>
						<canvas datasrc='static/pde/G-e-s-t-a-l-t.pde' width='220' height='313'></canvas><?
						}
						else
						{
						?><img src="<? echo $m_url; ?>"><?
						}
					?></div>
				</a>
				<div class="captionContainer caption"><? 
					echo $caption; 
				?></div>
			</div><?
		}
	}
	
	// divide bulletins
	?><div class="dividerContainer caption">&nbsp;</div><?
	
	$i--;
}

?>
