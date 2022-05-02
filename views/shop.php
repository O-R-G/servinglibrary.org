<script src="/static/js/shop.js"></script>
<?
$acceptedCurrencies = array('usd','gbp','eur');
$currency = isset($_GET['currency']) ? $_GET['currency'] : 'usd';
if(!in_array($currency, $acceptedCurrencies)) $currency = 'usd';
$acceptedCurrenciesSymbols = array(
	'usd' => '$',
	'gbp' => '£',
	'eur' => '€'
);

// only show back button on internal references
$protocol = isset($_SERVER['HTTPS']) && 
         $_SERVER['HTTPS'] === 'on' ? 
         "https://" : "http://";
$host = $protocol . $_SERVER['HTTP_HOST'];

$internal = isset($_SERVER['HTTP_REFERER']) && (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);
$back_url = "javascript:self.history.back();";

$body = trim($item['body']);
$deck = trim($item['deck']);

$temp = $oo->urls_to_ids(array('shop', 'issues'));
$journal_children = $oo->children(end($temp));
$base_url = '/journal/';

?>
<div id="currencySwitchWrapper" class="time">
<? foreach($acceptedCurrencies as $option){ 
	if($option == $currency) { ?>
		<span id="currencyOption-<?= $option; ?>" class="currencyOption active"><?= strtoupper($option); ?></span>
	<? } else { ?>
		<a id="currencyOption-<?= $option; ?>" class="currencyOption" href="?currency=<?= $option; ?>"><?= strtoupper($option); ?></a>
	<? }
 } ?>
</div>
<div class="mainContainer">
	<div id="shopContainer" class="floatContainer">
		<div class="thumbsContainer journalContainer"><?= $body; ?></div>
		<? foreach($journal_children as $key => $child){
			if( substr($child['name1'], 0, 1) != '.')
			{
				$media = $oo->media($child['id']);
				if(count($media) > 0)
					$cover = m_url($media[0]);
				$prices_pattern = '/\[('.$currency.')\]\((.*?)\)/';
				preg_match_all($prices_pattern, $child['notes'], $temp);
				$prices = array();
				if(!empty($temp)){
					foreach($temp[2] as $c => $t)
						$prices[$temp[1][$c]] = $t;
				}
				else
					$isSoldOut = true;
				$url = $base_url . $child['url'];
				if($currency !== 'usd') $url .= '?currency=' . $currency;

				?><div class="thumbsContainer journalContainer"><?
					if(isset($cover)){
						?><a class="shopItemLink" href="<?= $url; ?>">
							<div class="issue-img-container"><img class="issue-img" src="<?= $cover; ?>"></div>
						</a><?
					}
					?>
					<section id="buy-<?= $key; ?>" class="buy-section">
				       	<?
				       	if(!empty($prices))
				       	{
				       		foreach($prices as $c => $p) { 
				       			?>
		                		<div id="button-area-<?= $key . '-' . $c; ?>" class="button-area button-area-<?= $c; ?>">
		                			<div id="paypal-button-container-<?= $key . '-' . $c; ?>" price="<?= $p; ?>" class="payment-option paypal-button-container"></div>
			                		<div id="buy-button-container-<?= $key . '-' . $c; ?>" class="buy-button-container">
					              		<button id="cost-<?= $key . '-' . $c; ?>" class="button" onclick="expandPaypal('button-area-<?= $key . '-' . $c; ?>', '<?= $c; ?>')"><?= $acceptedCurrenciesSymbols[$c] . $p; ?></button>
					            	</div>
				            	</div>
		                	<? }
				       	}
				       	else
				       	{
				       		?><div id="button-area-<?= $key . '-' . $c; ?>" class="button-area"><div class="sold-out red">SOLD OUT</div></div><?
				       	}
				       	?>
			        </section>
				</div><?
			}
		}
	?></div>
</div>
<script>
	var currency = '<?= $currency; ?>';
	paypal_url += '&currency='+currency.toUpperCase();
	var paypal_script = loadScript(paypal_url);

	document.body.classList.add('viewing-'+currency);
</script>
<style>
	/*
		tmp for dev only
		should be cleaned up
	*/

	.thumbsContainer.journalContainer {
		width: 300px;
		margin-bottom: 10px;
		position: relative;
	}
	.buy-section {
		position: absolute;
		text-align: right;
		right: 0px;
		bottom: 0;
		padding: 10px;
		width: 200px;
	}

	/* obvo all of this is an ugly hack to be fixed */

	.buy-button-container {
		display: inline-block;
		width: 100%;
	}
	.issue-img
	{
		display: block;
	}
	.viewing-paypal .button-area
	{
	    background-color: #fff;
	}

	.viewing-usd .button-area-eur,
	.viewing-usd .button-area-gbp,
	.viewing-eur .button-area-usd,
	.viewing-eur .button-area-gbp,
	.viewing-gbp .button-area-usd,
	.viewing-gbp .button-area-eur
	{
		display: none;
	}


	.paypal-button-container,
	body.loading .viewing-paypal .paypal-button-container{
	    /*position: fixed;*/
	    /*left: 18px;*/
	    /*bottom: 90px;*/
	    /*width: 100px;*/
	    display: block;
	    opacity: 0.0;
	    pointer-events: none;
	    height: 0;
	    overflow: hidden;
	}

	.paypal-button-container:hover {
	    /* opacity: 1.0; */
	}

	.viewing-paypal .download-code-container,
	.viewing-paypal .paypal-button-container
	{
	    opacity: 1;
	    pointer-events: initial;
	    height: initial;
	    /*margin-top: 11px;*/
	}
	.viewing-paypal .buy-button-container .button
	{
	    /*background-color: #ccc;*/
	    /*border-color: #ccc;*/
	    background-color: #0E0;
	    border-color: #0E0;
	    position: relative;
	}
	body.loading .viewing-paypal:before
	{
		content: "Loading . . .";
		/*position: absolute;*/
		display: block;
		width: 100%;
		height: 35px;
		/*top: -21px;*/
		/*left: 10px;*/
		border-radius: 4px;
		border: 1px solid #ccc;
		background-color: #ccc;
		color: #000;
		z-index: 100;
		text-align: center;
	    font-size: 18px;
	    padding-top: 6px;
	    box-sizing: border-box;
	    margin-bottom: 5px;
	}
	.shopItemLink
	{
		display: block;
	}
	#currencySwitchWrapper
	{
		position: fixed;
		top: 30px;
		right: 20px;
		z-index: 90;
	}
	.currencyOption
	{
		display: inline-block;
		padding: 2px 5px;
		cursor: pointer;
	}
	a.currencyOption.active,
	a.currencyOption:hover,
	.currencyOption.active,
	.currencyOption:hover
	{
		background-color: #0C0;
		color: #fff;
	}
	.sold-out
	{
		width: 100%;
	    height: 35px;
	    color: #FFF;
	    background-color: #f00;
	    border: 1px solid #f00;
	    border-radius: 4px;
	    box-sizing: border-box;
	    font-size: 18px;
	    text-align: center;
	    vertical-align: top;
	    padding-top: 7px;
	    font-family: 'Arial', sans-serif;
	}
</style>