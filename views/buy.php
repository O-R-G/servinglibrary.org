<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?

    /*
        a view for paypal buy button  
    */

    require_once('static/php/paypal.php');
	$productInfo = getProductInfo($currency, trim($item['notes']));
	// echo printPayPalButtons($currency, $productInfo, '');
	echo printPayPalButtons($currency, $productInfo, $item['name1']);
	?><div id="currencySwitchWrapper" class="currency"><? 
		foreach($acceptedCurrencies as $option){ 
			if($option == $currency) { 
				?><button id="currencyOption-<?= $option; ?>" class="button currencyOption active"><?= $acceptedCurrenciesSymbols[$option]; ?></button><? 
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
