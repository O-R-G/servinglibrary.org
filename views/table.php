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
$issues = array_reverse($issues);
$k = 1;
// $n = 10;
// require($root."views/home-ajax.php");
// $n--;
// require($root."views/home-ajax.php");

?><div class="table-container">
	<div class="table">
		<div class="table-tr">
			<div class="table-th">#</div>
			<div class="table-th">BULLETIN</div>
			<div class="table-th">TITLE</div>
			<div class="table-th">AUTHOR(S)</div>
			<div class="table-th">PUBLISHED</div>
		</div><?
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
		
		// display bulletin and caption
		?><div class="table-tr">
			<div class="table-td"><? echo $k++; ?></div>
			<div class="table-td">
				<a href="<? echo $issue_url; ?>"><? echo $i; ?></a>
			</div>
			<div class="table-td">
				<a href="<? echo $b_url; ?>"><? echo $b['name1'] ?></a>
			</div>
			<div class="table-td"><? echo $author; ?></div>
			<div class="table-td"><? echo $b['created']; ?></div>
		</div><?
	}
	$i--;
}
?>