<?

/*
 * collect all bulletin cover images, write to ./out
 * must make directory first to use this
 * servinglibrary.org/collect
*/

?>

<!-- <script type="text/javascript" src="/static/js/home-ajax.js"></script> -->
<!-- 
<script>
	var thisW = 220; 
	var thisH = 313;
</script>
 --><?

// id of the 'journal' object
// all issues are attached to this object, 
// and all bulletins are attached to one of those issues
$journal_id = 82553;
$journal_url_base = "/journal";

// treat G-e-s-t-a-l-t differently
$gestalt_id = 77066;

$issues = $oo->children($journal_id);

$i = count($issues)-1;
// $issues = array_reverse($issues);

foreach($issues as $issue)
{
	$id = $issue['id'];
	
	// build the url for this issue
	$issue_url = $journal_url_base."/".$issue['url'];
	
	// get all of the bulletins for this issue
	$bulletins = $oo->children($id);
	
	// randomise the array
	// shuffle($bulletins);
	

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
		
		// display bulletin and caption
		?><div class="thumbsContainer issue<? echo $i; ?>">
			<a href="<? echo $b_url; ?>">
				<div class="coverContainer"><?
					// G-e-s-t-a-l-t exception
					if($b['id'] == $gestalt_id and !$is_mobile)
					{
					?><script>
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

				// collect bulletin cover images in order

				$counterpad = sprintf("%04d", $counter);
				$destination_url = './out/' . $counterpad;

				echo $counterpad . '</br>';
				echo $m_url . '</br>';
				echo $destination_url . '</br>';

				if(!@copy($m_url, $destination_url))
				{
				    $errors= error_get_last();
				    echo "COPY ERROR: ".$errors['type'];
				    echo "<br />\n".$errors['message'];
				} else {
				    // echo "</br>File copied from remote!";
				}

				$counter++;	

				echo $caption; 
			?></div>
		</div><?
	}
	
	// divide bulletins
	?><div class="dividerContainer caption">&nbsp;</div><?
	
	$i--;
}

?>
