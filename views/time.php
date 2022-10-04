<?
	require_once('static/php/config_download.php');
?>
<script type="text/javascript" src="/static/js/ajax.js"></script>
<div id="served-container" class="body">
	<div id="served-head">* Recently Served *</div>
	<div class="detail" id="served"><?
	require_once("time-ajax.php");
	?></div>
	<div id="ellipsis">
		<span>.</span> <span>.</span> <span>.</span>
	</div>
</div>