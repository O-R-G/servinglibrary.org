<?
$isSandbox = isset($_GET['isSandbox']);
?>
<script>
    var isSandbox = <?= json_encode($isSandbox); ?>;
</script>
<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?

    /*currency
        a view for paypal multi-item shop w/ cart
    */    

    require_once('static/php/paypal.php');
    $shop = ($uri[1] == 'shop' && count($uri) == 2);
    if ($shop) {
        $temp = $oo->urls_to_ids(array('shop'));
        $shop_children = $oo->children(end($temp));
        $base_url = '/journal/';
    	?><div class="mainContainer body">
        	<div id="shopContainer" class="floatContainer"><? 
                foreach($shop_children as $key => $child){
        			if( substr($child['name1'], 0, 1) != '.') {
        				$media = $oo->media($child['id']);
        				if(count($media) > 0)
        					$cover = m_url($media[0]);
        				$isDonation = strpos(trim($child['notes']), '[donation]') !== false;
        				$paypal_products = getProductInfo($currency, $child);
                        if(is_numeric($child['url']))
                            $url = '/journal/' . $child['url'];
                        else
        				    $url = '/shop/' . $child['url'];
                        $query = array();
                        if($currency !== 'usd') $query[] = 'currency=' . $currency;
                        if($isSandbox) $query[] = 'isSandbox';
                        if(!empty($query))
                        {
                            $query_string = '?' . implode('&', $query);
                            $url .=  $query_string;
                        }
        				?><div class="thumbsContainer shop"><?
        					if(isset($cover)){
        						?><a class="shopItemLink" href="<?= $url; ?>">
        							<div class="issue-img-container"><img class="issue-img" src="<?= $cover; ?>"></div>
        						</a><?
        					}
                            echo printPayPalButtons($currency, $paypal_products);
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
    				?><button id="currencyOption-<?= $option; ?>" class="button currencyOption" onclick="location.href='?currency=<?= $isSandbox ? $option . '&isSandbox' : $option; ?>'"><?= $acceptedCurrenciesSymbols[$option]; ?></button><?
    			}
    		}
    	?></div><?
    } else {
        $paypal_products = getProductInfo($currency, $item);
        if(!empty($paypal_products))
        {
            // foreach($paypal_products as $product){
            //     echo printPayPalButtons($currency, $product);
            // }
            echo printPayPalButtons($currency, $paypal_products);
            ?><div id="currencySwitchWrapper" class="currency"><? 
                foreach($acceptedCurrencies as $option){ 
                    if($option == $currency) { ?>
                        <button id="currencyOption-<?= $option; ?>" class="button currencyOption active"><?= $acceptedCurrenciesSymbols[$option]; ?></button><? 
                    } else {
                        ?><button id="currencyOption-<?= $option; ?>" class="button currencyOption" onclick="location.href='?currency=<?= $option; ?>'"><?= $acceptedCurrenciesSymbols[$option]; ?></button><?
                    }
                }
            ?></div><?
        }
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
</div>
<div id = "buy-section-container-cart" class="buy-section-container">
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
    var acceptedCurrencies = <?= json_encode($acceptedCurrencies, true); ?>;
    if(currency.toUpperCase() == 'USD')
        paypal_url = 'https://www.paypal.com/sdk/js?client-id='+paypal_client_id+'&disable-funding=credit,card';
    else
        paypal_url = 'https://www.paypal.com/sdk/js?client-id='+paypal_client_id_eu+'&disable-funding=credit,card';
    paypal_url += '&currency='+currency.toUpperCase();
    var paypal_script = loadScript(paypal_url);
    document.body.classList.add('viewing-'+currency);
    var cart_cookie = readCookie('serving-library-shop-cart');
    if(cart_cookie){
        let temp = 0;
        cart_cookie = JSON.parse(cart_cookie);
        cart_cookie.forEach(function(el, i){
            addToCartFromJson(el);
            temp += parseInt(el.quantity);
        });
        if (temp > 0)
            if (cart_symbol = document.getElementById('cart-symbol'))
                document.body.classList.add('viewing-cart-symbol');
        document.getElementById('item-count').innerText = temp;
    }
</script>
