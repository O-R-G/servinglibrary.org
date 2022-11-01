<?
$isSandbox = isset($_GET['isSandbox']);
?>
<script>
    var isSandbox = <?= json_encode($isSandbox); ?>;
</script>
<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?

    /*
        a view for donations
    */

    require_once('static/php/paypal.php');

    ?><!-- <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script> -->
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
    <div id="currencySwitchWrapper" class="currency"><? 
        foreach($acceptedCurrencies as $option){ 
            if($option == $currency) { ?>
                <button id="currencyOption-<?= $option; ?>" class="button currencyOption active"><?= $acceptedCurrenciesSymbols[$option]; ?></button><? 
            } else {
                ?><button id="currencyOption-<?= $option; ?>" class="button currencyOption" onclick="location.href='?currency=<?= $option; ?>'"><?= $acceptedCurrenciesSymbols[$option]; ?></button><?
            }
        }
    ?></div>
<script>
	var currency = '<?= $currency; ?>';
	var acceptedCurrenciesSymbols = <?= json_encode($acceptedCurrenciesSymbols, true); ?>;
	paypal_url += '&currency='+currency.toUpperCase();
	document.body.classList.add('viewing-'+currency);
</script><?
	// require_once('views/cart.php');
?>
