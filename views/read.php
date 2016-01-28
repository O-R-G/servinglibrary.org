<?

// this is not 100% ok
$issue = $uri[2];
if($issue == "zero")
	$issue = 0;

$r = $oo->get($uu->id);
$title = strtoupper($r['name1']);
$author = $r['deck'];
$body = $r['body'];

$gestalt_id = 77066;

// get media stuff
$fields = array("*");
$tables = array("media");
$where 	= array("object = '".$r['id']."'", 
				"active = '1'");
$order 	= array("type");
$limit 	= 3;
$media_all = $oo->get_all($fields, $tables, $where, $order, $limit);
$media_all = array_reverse($media_all);
$cover_img = m_url($media_all[1]);

$pdf = $media_all[0];
$source_name = m_pad($pdf['id']);

if($pdf['caption'])
	$display_name = $pdf['caption'];
else
	$display_name = $source_name;

$display_name = htmlspecialchars($display_name, ENT_QUOTES);

// video exception for issue 8
$video = false;
if(count($media_all) > 2)
{
	$video = $media_all[2];
	$vid_caption = $video['caption'];
}
?><div class="mainContainer">
	<div class="detailContainer">
		<a class="issue<? echo $issue; ?>"><?
			if($video)
			{
			?><div id="video" class="videoContainer hidden"><?
				echo $vid_caption;
			?></div><?
			}
			?><div id="cover-container"><?
				if($r['id'] == $gestalt_id && !$is_mobile)
				{
				?><script>
					var thisW = 400; 
					var thisH = 570;
				</script>
				<canvas datasrc='/static/pde/G-e-s-t-a-l-t.pde' width='400' height='570'></canvas><?
				}
				else
				{
				?><img src="<? echo $cover_img; ?>"><?
				}
			?></div>	
			<div class="captionContainer detail"><? echo $author.": ".$title; ?></div>
		</a>
	</div>
	<div class="wordsContainer body pad"><?
		echo nl2br($body);
		?>
		<div id="download-button">Download PDF</div>
		<script>
			button = document.getElementById("download-button");
			cover = document.getElementById("cover-container");
			cover.onclick = button.onclick = function()
			{
				document.getElementById("download-form").submit();
			};
		</script><?
		// video exception
		if($video)
		{
		?><div id="video-button">* Watch VIDEO</div>
		<script>
			var video_hidden = true;
			video = document.getElementById("video");
			video_button = document.getElementById("video-button");
			function show_hide()
			{
				if(video_hidden)
				{
					video.className = "videoContainer";
					cover.className+= " hidden";
					video_button.innerHTML = "* Hide VIDEO";
				}
				else
				{
					video.className+= " hidden";
					cover.className = "";
					video_button.innerHTML = "* Watch VIDEO";
				}
				video_hidden = !video_hidden;
			}
			video_button.onclick = function()
			{
				show_hide();
			};
		</script><?
		}
	?></div>
	<form id="download-form" action="/lib/download.php" method="post">
		<input type="hidden" name="source_name" value="<? echo $source_name; ?>">
		<input type="hidden" name="display_name" value="<? echo $display_name; ?>">
		<input type="hidden" name="author" value="<? echo $author; ?>">
		<input type="hidden" name="issue" value="<? echo $issue; ?>">
	</form>
</div>