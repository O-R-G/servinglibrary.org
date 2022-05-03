<script src="/static/js/shop.js"></script>
<?
function getPrices($currency, $priceField){
	$prices_pattern = '/\[('.$currency.')\]\((.*?)\)/';
	preg_match_all($prices_pattern, trim($priceField), $temp);
	$output = array();
	if(!empty($temp)){
		foreach($temp[2] as $c => $t)
			$output[$temp[1][$c]] = $t;
	}
	return $output;
}
function printPayPalButtons($currency, $prices, $acceptedCurrenciesSymbols = array(), $key = ''){
	$key = empty($key) ? '' : '-' . $key;
	$output = '';
	if(!empty($prices))
   	{
   		if(is_array($prices))
   		{
   			$output = '<section id="buy' . $key . '" class="buy-section">';
   			$p = $prices[$currency];
   			$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area button-area-' . $currency . '">';
   			$output .= 		'<div id="paypal-button-container' . $key . '-' . $currency . '" price="'. $p . '" class="payment-option paypal-button-container"></div>';
   			$output .= 	'<div id="buy-button-container' . $key . '-' . $currency . '" class="buy-button-container">';
   			$output .= 	'<button id="cost' . $key . '-' . $currency . '" class="button" onclick="expandPaypal(\'button-area' . $key . '-' . $currency . '\', \'' . $currency . '\')">' . $acceptedCurrenciesSymbols[$currency] . $p . '</button>';
   			$output .= '</div></div>';
	  	   	$output .= '</section>';
   		}
   		else if($prices == 'donation')
   		{
   			$output = '<div id="donate-buy-section" class="buy-section"><div id="paypal-donate-button-container"></div><button id="donate-btn" class="button">DONATE</button></div>';
   		}	
   	}
   	else
   	{
   		$output = '<section id="buy' . $key . '" class="buy-section">';
   		$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area"><div class="sold-out red">SOLD OUT</div></div>';
   		$output .= '</section>';
   	}
   	return $output;
}

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
$notes = 

$temp = $oo->urls_to_ids(array('shop', 'issues'));
$journal_children = $oo->children(end($temp));
$base_url = '/journal/';

$prices_pattern = '/\[('.$currency.')\]\((.*?)\)/';

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
<? if($isShop){
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
				$prices = getPrices($currency, trim($child['notes']));
				if(empty($prices) && $isDonation)
					$prices = 'donation';
				$url = $base_url . $child['url'];
				if($currency !== 'usd') $url .= '?currency=' . $currency;

				?><div class="thumbsContainer journalContainer"><?
					if(isset($cover)){
						?><a class="shopItemLink" href="<?= $url; ?>">
							<div class="issue-img-container"><img class="issue-img" src="<?= $cover; ?>"></div>
						</a><?
					}
					echo printPayPalButtons($currency, $prices, $acceptedCurrenciesSymbols, $key);
					if($isDonation) printPayPalButtons($currency, 'donation', $acceptedCurrenciesSymbols, $key);
					?>
				</div><?
			}
		}
	?></div>
</div><?
} 
else
{
	$isDonation = strpos(trim($item['notes']), '[donation]') !== false;
	$prices = getPrices($currency, trim($item['notes']));
	if(empty($prices) && $isDonation)
		$prices = 'donation';
	echo printPayPalButtons($currency, $prices, $acceptedCurrenciesSymbols);
}

if($isDonation){
	?><script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js?client-id=AarUvt7o6QoGOIcQTz9lMSf7UEtUGPJL8iX5mLmTFtIES07o31Pdn_pYSERT_IuhPuIVueizce3yXCzX" charset="UTF-8"></script><?
}?>
<script>
	var isDonation = <?= json_encode($isDonation); ?>;
	var currency = '<?= $currency; ?>';
	paypal_url += '&currency='+currency.toUpperCase();
	if(!isDonation) {
		var paypal_script = loadScript(paypal_url);
	}
	

	document.body.classList.add('viewing-'+currency);

	if(isDonation)
	{
		console.log('isDonation');
		PayPal.Donation.Button({
	       env: 'sandbox',
	       hosted_button_id: 'TKKY8UTYFQ754',
	       // business: 'YOUR_EMAIL_OR_PAYERID',
	       image: {
	           src: '/media/00001.jpg',
	           title: 'PayPal - The safer, easier way to pay online!',
	           alt: 'Donate with PayPal button'
	       },
	       onComplete: function (params) {
	       	console.log('oncomplete');
	           // Your onComplete handler
	       },
		}).render('#paypal-donate-button-container');
	}
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
	#paypal-donate-button-container
	{
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: hidden;
		opacity: 0;
	}
	#donate-btn
	{
		pointer-events: none;
	}
</style>