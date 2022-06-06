<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?

    /*
        a view for paypal multi-item shop w/ cart
    */

    require_once('static/php/paypal.php');
	$children = $oo->children($item['id']);
	foreach($children as $key => $child) {
		if( substr($child['name1'], 0, 1) != '.') {
			$itemName = 'Subscription - ' . $child['name1'];
			$productInfo = getProductInfo($currency, trim($child['notes']));
			echo printPayPalButtons($currency, $productInfo, $itemName);
		}
	}
	?><div id="currencySwitchWrapper" class="currency"><? 
	if(!$isDonation){
		foreach($acceptedCurrencies as $option){ 
			if($option == $currency) { 
                ?><button id="currencyOption-<?= $option; ?>" class="button currencyOption active"><?= $acceptedCurrenciesSymbols[$option]; ?></button><?
			} else {
				?><button id="currencyOption-<?= $option; ?>" class="button currencyOption" onclick="location.href='?currency=<?= $option; ?>'"><?= $acceptedCurrenciesSymbols[$option]; ?></button><?
			}
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
