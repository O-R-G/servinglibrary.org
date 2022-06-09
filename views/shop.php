<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?

    /*
        a view for paypal multi-item shop w/ cart
    */    

    require_once('static/php/paypal.php');
    $body = trim($item['body']);
    $deck = trim($item['deck']);
	$temp = $oo->urls_to_ids(array('shop', 'issues'));
	$journal_children = $oo->children(end($temp));
	$base_url = '/journal/';
    $shop = ($uri[1] == 'shop');
    if ($shop) {
    	?><div class="mainContainer body">
        	<div id="shopContainer" class="floatContainer"><? 
                foreach($journal_children as $key => $child){
        			if( substr($child['name1'], 0, 1) != '.') {
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
        				?></div><?
        			}
        		}
        	?></div>
        </div>
    	<div id="currencySwitchWrapper" class="currency"><? 
    		foreach($acceptedCurrencies as $option){ 
    			if($option == $currency) { ?>
    				<button id="currencyOption-<?= $option; ?>" class="button currencyOption active"><?= $acceptedCurrenciesSymbols[$option]; ?></button><? 
                } else {
    				?><button id="currencyOption-<?= $option; ?>" class="button currencyOption" onclick="location.href='?currency=<?= $option; ?>'"><?= $acceptedCurrenciesSymbols[$option]; ?></button><?
    			}
    		}
    	?></div><?
    } else {
        $productInfo = getProductInfo($currency, trim($item['notes']));
        echo printPayPalButtons($currency, $productInfo, $item['name1']);
    }
?><div id="cart-symbol" class="cart-button-container" onclick="toggleCart()">
	<button id="" class="button" onclick="">
		CART (<span id="item-count">0</span>)
	</button>
</div>
<div id="cart-container" class="body">
	<div id="btn-close-cart" onClick="toggleCart()"><img src='/media/svg/x-3-k.svg'></div>
	<div id="" class="item-row-default">
		<div class="item-column item-name">Item</div><div class="item-column item-price">Price</div>
        <div class="item-column item-quantity-container flex-container">
            <span class="item-quantity">Quantity</span>
        </div>
        <div class="item-column item-amount"></div>
        <div class="item-column item-remove"></div>
	</div>
	<section id="buy-section-cart" class="buy-section">
		<div id="button-area-cart" class="button-area">
			<div id="paypal-button-container-cart" class="payment-option paypal-button-container"></div>
			<button id="btn-checkout-cart" class="button" onclick="expandPaypal('button-area-cart', '', '', '')">Checkout</button>
		</div>
	</section>
</div>
<script>
    var currency = '<?= $currency; ?>';
    var acceptedCurrenciesSymbols = <?= json_encode($acceptedCurrenciesSymbols, true); ?>;
    paypal_url += '&currency='+currency.toUpperCase();
    var paypal_script = loadScript(paypal_url);
    document.body.classList.add('viewing-'+currency);
    var cart_cookie = readCookie('cart');
    console.log(cart_cookie);
    if(cart_cookie){
        let temp = 0;
        cart_cookie = JSON.parse(cart_cookie);
        cart_cookie.forEach(function(el, i){
            console.log(el);
            addToCartFromJson(el);
            temp += parseInt(el.quantity);
        });
        document.getElementById('item-count').innerText = temp;
    }
</script>
