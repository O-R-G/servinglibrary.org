<?
// get all objects

$objects = $oo->children($uu->id);
$n = count($objects);

// ajax (turned off for now)
// $n = 10;
// require($root."views/home-ajax.php");
// $n--;
// require($root."views/home-ajax.php");

// randomise the array
shuffle($objects);

foreach($objects as $o) {

	// style caption
	$title = strtoupper($o['name1']);
	$caption = $title;

	// multiple images per object	
	$media = $oo->media($o['id']);

	foreach ($media as $m) {

		// get cover image
		$j = 0;
		
		// ignore if .pdf
		while($m['type'] == "pdf")
			continue;

		// build object url
		$o_url = $uu->url."/".$o['url'];

		// media url
		$m_url = m_url($m);
	
		// display object and caption
		if (substr($o['name1'], 0, 1) != ".") {
			?><div class="thumbsContainer object">
				<a href="<? echo $o_url; ?>">
					<div class="coverContainer"><?
						?><img src="<? echo $m_url; ?>"><?
					?></div>
				</a>
				<div class="captionContainer caption"><? 
					echo $caption; 
				?></div>
			</div><?
		}
	}
}
?>
