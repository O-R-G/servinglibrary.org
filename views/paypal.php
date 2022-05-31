<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?
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

function printPayPalButtons($currency, $productInfo, $itemName){
	global $acceptedCurrenciesSymbols;
	// $key = empty($key) ? '' : '-' . $key;
	$key = slug($itemName);
	$output = '';
	$price = $productInfo['price'];
	$type = $productInfo['type'];
	if( is_numeric($price) )
   	{
   		$output = '<section id="buy' . $key . '" class="buy-section">';
		$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area button-area-' . $currency . '">';
		$output .= 		'<div id="paypal-button-container' . $key . '-' . $currency . '" price="'. $price . '" class="payment-option paypal-button-container"></div>';
		$output .= 		'<div id="paypal-cart-button-container' . $key . '-' . $currency . '" price="'. $price . '" class="payment-option paypal-button-container paypal-cart-button-container"><button id="paypal-cart-button' . $key . '-' . $currency . '" class="button paypal-cart-button" price="'. $price . '" itemName="'.$itemName.'" slug="'.$key.'" type="'.$type.'" onclick="addToCartByClick(event)">ADD TO CART</button></div>';
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
		$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area"><div class="pseudo-button sold-out">SOLD OUT</div></div>';
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
if($isShop){
	$temp = $oo->urls_to_ids(array('shop', 'issues'));
	$journal_children = $oo->children(end($temp));
	$base_url = '/journal/';
	$body = trim($item['body']);
	?><div class="mainContainer body">
	<div id="shopContainer" class="floatContainer">
		<!-- <div class="thumbsContainer shop"><?= $body; ?></div> -->
		<? foreach($journal_children as $key => $child){
			if( substr($child['name1'], 0, 1) != '.')
			{
				$media = $oo->media($child['id']);
				if(count($media) > 0)
					$cover = m_url($media[0]);
				$isDonation = strpos(trim($child['notes']), '[donation]') !== false;
				$productInfo = getProductInfo($currency, trim($child['notes']));
				$url = $base_url . $child['url'];
				if($currency !== 'usd') $url .= '?currency=' . $currency;
				$itemName = $child['name1'];

				?><div class="thumbsContainer shop"><?
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
} else if($this_page == 'subscription') {
	$children = $oo->children($item['id']);
	foreach($children as $key => $child)
	{
		if( substr($child['name1'], 0, 1) != '.')
		{
			$itemName = 'Subscription - ' . $child['name1'];
			$productInfo = getProductInfo($currency, trim($child['notes']));
			echo printPayPalButtons($currency, $productInfo, $itemName);
		}
	}
}
else {
	$productInfo = getProductInfo($currency, trim($item['notes']));
	echo printPayPalButtons($currency, $productInfo, '');
}

if ($this_page !== 'donation') {
	?><div id="currencySwitchWrapper" class="currency"><? 
	if(!$isDonation){
		foreach($acceptedCurrencies as $option){ 
			if($option == $currency) { ?>
				<button id="currencyOption-<?= $option; ?>" class="button currencyOption active"><?= $acceptedCurrenciesSymbols[$option]; ?></button>
			<? } else { ?>
				<button id="currencyOption-<?= $option; ?>" class="button currencyOption" onclick="location.href='?currency=<?= $option; ?>'"><?= $acceptedCurrenciesSymbols[$option]; ?></button>
			<? }
		}
	 }
	?></div><?
} else {
	?>
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
	</div><?
}
?><script>
	var isDonation = '<?= $this_page === 'donation'; ?>';
	var currency = '<?= $currency; ?>';
	var acceptedCurrenciesSymbols = <?= json_encode($acceptedCurrenciesSymbols, true); ?>;
	paypal_url += '&currency='+currency.toUpperCase();
	if(!isDonation) {
		var paypal_script = loadScript(paypal_url);
	}
	document.body.classList.add('viewing-'+currency);
</script><?
	require_once('views/shopping-cart.php');
?><style>
	/*
		tmp for dev only
		should be cleaned up
	*/

	.thumbsContainer.shop {
		width: 250px;
		position: relative;
		padding: 20px;
	}

	.buy-section {
		position: fixed;
		width: 200px;
		left: 20px;
		bottom: 20px;
	}

	.thumbsContainer .buy-section {
		position: absolute;
        margin: initial;
		left: 30px;
		bottom: 30px;
	}

	.buy-section + .buy-section {
		left: 230px;
	}

	/* obvo all of this is an ugly hack to be fixed */

	.buy-button-container {
		display: inline-block;
		width: 100%;
	}

	.issue-img {
		display: block;
	}

	.viewing-paypal .button-area {
	    background-color: #FFF;
	}

	.viewing-usd .button-area-eur,
	.viewing-usd .button-area-gbp,
	.viewing-eur .button-area-usd,
	.viewing-eur .button-area-gbp,
	.viewing-gbp .button-area-usd,
	.viewing-gbp .button-area-eur {
		display: none;
	}

	.paypal-button-container,
	body.loading .viewing-paypal .paypal-button-container {
	    display: none;
	    height: 35px;
	}

	.cart-button-container {
		display: block;
		width: 200px;
		height: 35px;
	}

	.cart-button-container .button {
		background-color: #0C0;
        color: #FFF;
	}

	.cart-button-container .button:hover {
		background-color: #0F0;
	}

	.paypal-button-container: hover {
	    /* opacity: 1.0; */
	}

	.paypal-button-container > div {
		display: block;
	}

	.viewing-paypal .download-code-container,
	.viewing-paypal .paypal-button-container {
	    /* display: block; */
	    /*pointer-events: initial;*/
	    /*height: initial;*/
	    /*margin-top: 11px;*/
	}

	.viewing-paypal .buy-button-container .button {
	    background-color: #0E0;
	    border-color: #0E0;
	    position: relative;
        color: #FFF;
	}

	body.loading .viewing-paypal:before {
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

	.shopItemLink {
		display: block;
	}

	#currencySwitchWrapper {
		position: fixed;
		bottom: 20px;
		right: 20px;
		z-index: 90;
	}

    .currency {
        color: #000;
    }
    
	.currencyOption {
		display: inline-block;
        width: 40px;
		padding: 2px 5px;
		cursor: pointer;
    	/* border-radius: 4px; */
        text-align: center;
    }

	a.currencyOption.active,
	a.currencyOption:hover,
	.currencyOption.active,
	.currencyOption:hover {
		background-color: #0C0;
		color: #fff;
	}

	.pseudo-button {
		width: 100%;
	    height: 35px;
	    border-radius: 4px;
	    box-sizing: border-box;
	    font-size: 18px;
	    text-align: center;
	    vertical-align: top;
	    padding-top: 7px;
	    font-family: 'Arial', sans-serif;
	}

    .pseudo-button.sold-out {
	    color: #FFF;
	    background-color: none;
	    border: 1px solid #FFF;    
    }

	#donate-btn {
		pointer-events: none;
	}

	#donate-buy-section form {
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
	.payment-option	{
		margin-bottom: 5px;
	}
	.paypal-cart-button {
		/*
        background-color: #0C0;
		border-color: #0C0;
        */
	}

	.paypal-cart-button:hover {
		/*
        background-color: #a9a9a9;
		border-color: #a9a9a9;
        */
	}

	.paypal-cart-button-container,
	.viewing-paypal .paypal-cart-button-container {
		display: none;
	}

	.testCart .viewing-paypal .paypal-cart-button-container {
		display: block;
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

        #cart-symbol {
                position: fixed;
                left: 20px;
                bottom: -35px;
                z-index: 1000;
                cursor: pointer;
        }

        #cart-symbol:hover,
        .viewing-cart #cart-symbol {
                background-color: #0C0;
                color: #fff;
        }

        .viewing-cart-symbol#cart-symbol {
            bottom: 20px;
            transition: bottom 0.25s;
        }

        #cart-container {
                width: 100vw;
                max-width: 100%;
                position: fixed;
                bottom: 0;
                left: 0;
                transform: translate(0, 100%);
                z-index: 1000;
                padding: 20px;
                padding-right: 60px;
                padding-bottom: 75px;
                background-color: #fff;
                min-height: 25vh;
                max-height: 50vh;
                overflow: scroll;
                box-sizing: border-box;
        }

        .viewing-cart #cart-container {
                /* transition: transform .5s; */
                transform: translate(0, 0%);
        }

        #btn-close-cart {
                position: absolute;
                right: 10px;
                top: 15px;
                cursor: pointer;
                padding: 5px 10px;
        }
        #buy-section-cart {
                position: absolute;
                left: 20px;
        }
        .item-row,
        .item-row-default
        {
                display: flex;
        }
        .item-row
        {
                margin-top: 10px;
        }

        .item-column
        {
                display: inline-block;
                flex-basis: 50px;
                padding: 0 15px;
                text-align: right;
        }
        .item-name.item-column
        {
                flex: 1;
                text-align: left;
        }
        .item-remove
        {
                flex-basis: 80px;
                cursor: pointer;
        }
        .item-row-default .item-remove
        {
                cursor: default;
        }
        .item-quantity-container
        {
                position: relative;
        }
        .item-quantity
        {
                text-align: center;
        }
        .item-quantity-minus,
        .item-quantity-plus
        {
                position: absolute;
                top: 0;
                padding: 0 5px;
                cursor: pointer;
        }
        .item-quantity-minus
        {
                left: 10px;
        }
        .item-quantity-plus
        {
                right: 10px;
        }
</style>
