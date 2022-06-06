<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?

    /*
        a view for paypal multi-item shop w/ cart
    */    

    require_once('static/php/paypal.php');
	$temp = $oo->urls_to_ids(array('shop', 'issues'));
	$journal_children = $oo->children(end($temp));
	$base_url = '/journal/';
	$body = trim($item['body']);
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
?><script>
	var currency = '<?= $currency; ?>';
	var acceptedCurrenciesSymbols = <?= json_encode($acceptedCurrenciesSymbols, true); ?>;
	paypal_url += '&currency='+currency.toUpperCase();
    var paypal_script = loadScript(paypal_url);
	document.body.classList.add('viewing-'+currency);
</script><?
	require_once('views/cart.php');
?>
