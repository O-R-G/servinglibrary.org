<?
	require_once('static/php/config_download.php');
	
?>
<script type="text/javascript" src="/static/js/ajax.js<?= empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']; ?>"></script>
<div id="served-container" class="body">
	<div id="served-head">* Recently Served *</div>
	<div id="time-filter-container">
		<?php foreach($filter_items as $filter_item): ?>
			<div class="filter-item-wrapper"><a href="<?php echo implode('/', $uri) . ($filter_item['slug'] ? '?filter=' . $filter_item['slug'] : ''); ?>"><?php echo $filter_item['display']; ?></a></div>
		<?php endforeach; ?>
	</div>
	<br>
	<div class="detail" id="served"><?
	require_once("time-ajax.php");
	?></div>
	<? if(!isset($_GET['filter']) || $_GET['filter'] !== 'total'){ ?>
	<div id="ellipsis">
		<span>.</span> <span>.</span> <span>.</span>
	</div>
	<? } ?>
</div>