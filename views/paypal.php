<script src="/static/js/shop.js"></script>
<?
function getProductInfo($currency, $priceField){
	$price_pattern = '/\['.$currency.'\]\((.*?)\)/';
	$type_pattern = '/\[type\]\((.*?)\)/';
	preg_match($price_pattern, trim($priceField), $temp);
	$output = array();
	if(!empty($temp)){
		if(!empty($temp[1]) && strtolower($temp[1]) !== 'sold out'){
			preg_match($type_pattern, trim($priceField), $temp_type);
			if(!empty($temp_type))
				$output['type'] = $temp_type[1];
			else
				$output['type'] = '';
			$output['price'] = $temp[1];
		}
		else{
			$output['type'] = 'sold out';
			$output['price'] = 'sold out';
		}
	}
	else
	{
		if(strpos(trim($priceField), '[donation]') !== false)
		{
			$output['type'] = 'donation';
			$output['price'] = 'donation';
		}
		else{
			$output['type'] = 'sold out';
			$output['price'] = 'sold out';
		}
	}
	
	return $output;
}
// function getProductInfo($currency, $priceField){
// 	$product_pattern = '/^\[(.*)\]\((?:\[.*\]\(.*\))*\['.$currency.'\]\((.*)\)(?:\[.*\]\(.*\))*\)/';
// 	preg_match($product_pattern, trim($priceField), $temp);
// 	var_dump($temp);
// 	if( empty($temp) )
// 	{
// 		if(strpos(trim($item['notes']), '[donation]') !== false){
// 			$product_type = 'donation';
// 			$product_price = false;
// 		}
// 		else{
// 			$product_type = false;
// 			$product_price = false;
// 		}
// 	}
// 	else if( empty($product_price) || strtolower($product_price) == 'sold out')
// 	{
// 		$product_type = false;
// 		$product_price = false;
// 	}
// 	else
// 	{
// 		$product_type = $temp[1];
// 		$product_price = $temp[2];
// 	}

// 	$output = array(
// 		'type'  => $product_type,
// 		'price' => $product_price
// 	);

// 	return $output;
// }
function printPayPalButtons($currency, $productInfo, $itemName){
	global $acceptedCurrenciesSymbols;
	$key = empty($key) ? '' : '-' . $key;
	$key = slug($itemName);
	$output = '';
	$price = $productInfo['price'];
	$type = $productInfo['type'];

	// $isSubscription = $type === 'subscription';
	// var_dump($price);
	// var_dump(floatval($price));
	// var_dump(!is_nan('ss'));
	// var_dump(!is_nan($price));
	// echo '<br><br><br>';
	if( is_numeric($price) )
   	{
   		$output = '<section id="buy' . $key . '" class="buy-section">';
		$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area button-area-' . $currency . '">';
		$output .= 		'<div id="paypal-button-container' . $key . '-' . $currency . '" price="'. $price . '" class="payment-option paypal-button-container"></div>';
		$output .= 	'<div id="buy-button-container' . $key . '-' . $currency . '" class="buy-button-container">';
		$output .= 	'<button id="cost' . $key . '-' . $currency . '" class="button" onclick="expandPaypal(\'button-area' . $key . '-' . $currency . '\', \'' . $currency . '\', \''.$itemName.'\', \''.$type.'\')">' . $acceptedCurrenciesSymbols[$currency] . $price . '</button>';
		$output .= '</div></div>';
	   	$output .= '</section>';
   	}
   	else if($type == 'doantion')
   	{
   		$output = '<div id="donate-buy-section" class="buy-section"><div id="paypal-donate-button-container"></div><button id="donate-btn" class="button">DONATE</button></div>';
   	}
   	else if($price == 'sold out')
   	{
   		$output = '<section id="buy' . $key . '" class="buy-section">';
		$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area"><div class="sold-out red">SOLD OUT</div></div>';
		$output .= '</section>';
   	}

   	return $output;
}

if( ($uri[1] == 'shop' && count($uri) == 2) || 
	($uri[1] == 'journal' && count($uri) == 3) )
	$this_page = 'issue';
else if($uri[1] == 'shop' && count($uri) == 3 && $uri[2] == 'subscriptions')
	$this_page = 'subscription';
else if( strpos(trim($item['notes']), '[donation]') !== false )
	$this_page = 'donation';

$acceptedCurrencies = array('usd','gbp','eur');
$currency = isset($_GET['currency']) ? $_GET['currency'] : 'usd';
if(!in_array($currency, $acceptedCurrencies)) $currency = 'usd';
$acceptedCurrenciesSymbols = array(
	'usd' => '$',
	'gbp' => '£',
	'eur' => '€'
);

$isDonation = strpos(trim($item['notes']), '[donation]') !== false;

// only show back button on internal references
// $product_pattern = '/^\[(.*)\]\((?:\[.*\]\(.*\))*\['.$currency.'\]\((.*)\)(?:\[.*\]\(.*\))*\)/';
// $product_pattern = '/^\[(.*)\]\(.*\)/';
// preg_match($product_pattern, trim($item['notes']), $temp);
// var_dump($temp);

// $product_type = $temp[1];
// $product_price = $temp[2];
// echo 'type: ' . $product_type . '<br>';
// echo 'price: ' . $product_price . ' ' . $currency . '<br>';
// if( empty($temp) )
// {
// 	if(strpos(trim($item['notes']), '[donation]') !== false){
// 		$product_type = 'donation';
// 		$product_price = false;
// 	}
// 	else
// 		$product_type = 'sold out';
// 		$product_price = false;
// }
// else if( empty($product_price) || strtolower($product_price) == 'sold out')
// {
// 	$product_type = 'sold out';
// 	$product_price = false;
// }
// else
// {
// 	$product_type = $temp[1];
// 	$product_price = $temp[2];
// }

?>
<? if($isShop){
	$temp = $oo->urls_to_ids(array('shop', 'issues'));
	$journal_children = $oo->children(end($temp));
	$base_url = '/journal/';
	$body = trim($item['body']);
	?><div class="mainContainer">
	<div id="shopContainer" class="floatContainer">
		<div class="thumbsContainer journalContainer"><?= $body; ?></div>
		<? foreach($journal_children as $key => $child){
			if( substr($child['name1'], 0, 1) != '.')
			{
				$media = $oo->media($child['id']);
				if(count($media) > 0)
					$cover = m_url($media[0]);
				$isDonation = strpos(trim($child['notes']), '[donation]') !== false;
				$productInfo = getProductInfo($currency, trim($child['notes']));
				// $this_product = getProductInfo($currency, trim($child['notes']));
				
				$url = $base_url . $child['url'];
				if($currency !== 'usd') $url .= '?currency=' . $currency;
				$itemName = $child['name1'];

				?><div class="thumbsContainer journalContainer"><?
					if(isset($cover)){
						?><a class="shopItemLink" href="<?= $url; ?>">
							<div class="issue-img-container"><img class="issue-img" src="<?= $cover; ?>"></div>
						</a><?
					}
					echo printPayPalButtons($currency, $productInfo, $itemName);
					?>
				</div><?
			}
		}
	?></div>
</div><?
} 
else if($this_page == 'subscription')
{
	$children = $oo->children($item['id']);
	foreach($children as $key => $child)
	{
		if( substr($child['name1'], 0, 1) != '.')
		{
			$itemName = 'Subscription - ' . $child['name1'];
			$productInfo = getProductInfo($currency, trim($child['notes']));
			// $this_product = getProductInfo($currency, trim($child['notes']));
			echo printPayPalButtons($currency, $productInfo, $itemName);
		}
	}
}
else
{
	$productInfo = getProductInfo($currency, trim($item['notes']));
	echo printPayPalButtons($currency, $productInfo, '');
}
if($this_page !== 'donation')
{
	?>
	<div id="currencySwitchWrapper" class="time">
	<? if(!$isDonation){
		foreach($acceptedCurrencies as $option){ 
			if($option == $currency) { ?>
				<span id="currencyOption-<?= $option; ?>" class="currencyOption active"><?= strtoupper($option); ?></span>
			<? } else { ?>
				<a id="currencyOption-<?= $option; ?>" class="currencyOption" href="?currency=<?= $option; ?>"><?= strtoupper($option); ?></a>
			<? }
		}
	 } ?>
	</div>
	<?
}else{ ?>
	<!-- <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script> -->
	<div id="donate-buy-section" class="buy-section">
		<form name="Donate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="H4B76UC2KFXCE">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" border="0">
			<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" border="0">
		</form> 
		<button id="donate-btn" class="button">DONATE</button>
		<!-- <form action="https://www.sandbox.paypal.com/donate" method="post" target="_blank">
		<input type="hidden" name="hosted_button_id" value="TKKY8UTYFQ754" />
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
		<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
		</form> -->

		<!-- <div id="paypal-donate-button-container"></div> -->
		<!-- <button id="donate-btn" class="button">DONATE</button> -->
	</div>
<? } ?>
<script>
	var isDonation = '<?= $this_page === 'donation'; ?>';
	var currency = '<?= $currency; ?>';
	paypal_url += '&currency='+currency.toUpperCase();
	if(!isDonation) {
		var paypal_script = loadScript(paypal_url);
	}
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
	.buy-section
	{
		position: fixed;
		width: 200px;
		left: 20px;
		bottom: 20px;
	}
	.thumbsContainer .buy-section
	{
		position: absolute;
		text-align: right;
		left: auto;
		right: 0px;
		bottom: 0px;
		padding: 10px;
		/*width: 200px;*/
	}
	.buy-section + .buy-section
	{
		left: 230px;
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
	#donate-btn
	{
		pointer-events: none;
	}
	#donate-buy-section form
	{
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: 0;
	}
	#donate-buy-section form input[type="image"]
	{
		width: 100%;
		height: 100%;
	}
	
	/*form[name="Donate"]
	{
		margin-top: 21px;
		width: 200px;
		height: 35px;
		position: relative;
		overflow: hidden;
	}
	
	form[name="Donate"]:after
	{
		content: "DONATE";
		position: absolute;
		top: 0;
		left: 0;
		display: block;
		width: 100%;
		height: 100%;
		pointer-events: none;
	}*/
</style>