<?

$acceptedCurrencies = array('usd','gbp','eur');
$currency = isset($_GET['currency']) ? strtolower($_GET['currency']) : 'usd';
if(!in_array($currency, $acceptedCurrencies)) $currency = 'usd';

// only show back button on internal references
$protocol = isset($_SERVER['HTTPS']) && 
         $_SERVER['HTTPS'] === 'on' ? 
         "https://" : "http://";
$host = $protocol . $_SERVER['HTTP_HOST'];

$internal = isset($_SERVER['HTTP_REFERER']) && (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);
$back_url = "javascript:self.history.back();";

$body = $item['body'];
$deck = $item['deck'];

$journal_children = $oo->children($item['id']);

if($uri[1] == 'shop' && $uri[2] == 'issues')
	$base_url = '/journal/';
else
	$base_url = '/' . $uri[1] . '/' . $uri[2] . '/';

// $paypal_client_id = getenv('PAYPAL_CLIENT_ID');
// sandbox
$paypal_client_id = 'AarUvt7o6QoGOIcQTz9lMSf7UEtUGPJL8iX5mLmTFtIES07o31Pdn_pYSERT_IuhPuIVueizce3yXCzX';
// live
// $paypal_client_id = 'ATB90kcor24zrbGtQCBUnIKeqtxMMaz5M1rgUbO4mcEt_ACUHXk52TPiBbyoQJaOiubhDz8jzA3iQepP';
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
		<div class="thumbsContainer journalContainer"></div>
		<? foreach($journal_children as $key => $child){
			if( substr($child['name1'], 0, 1) != '.')
			{
				$media = $oo->media($child['id']);
				if(count($media) > 0)
					$cover = m_url($media[0]);
				$prices_pattern = '/\[('.strtoupper($currency).')\]\((.*?)\)/';
				preg_match_all($prices_pattern, $child['notes'], $temp);
				$prices = array();
				if(!empty($temp)){
					foreach($temp[2] as $c => $t)
						$prices[$temp[1][$c]] = $t;
				}
				else
					$isSoldOut = true;

				?><div class="thumbsContainer journalContainer"><?
					if(isset($cover)){
						?><a class="shopItemLink" href="<?= $base_url . $child['url']; ?>">
							<div class="issue-img-container"><img class="issue-img" src="<?= $cover; ?>"></div>
						</a><?
					}
					?>
					<section id="buy-<?= $key; ?>" class="buy-section">
				       	<?
				       	if(!empty($prices))
				       	{
				       		foreach($prices as $c => $p) { ?>
		                		<div id="button-area-<?= $key . '-' . $c; ?>" class="button-area button-area-<?= strtolower($c); ?>">
		                			<div id="paypal-button-container-<?= $key . '-' . $c; ?>" price="<?= $p; ?>" class="payment-option paypal-button-container"></div>
			                		<div id="buy-button-container-<?= $key . '-' . $c; ?>" class="buy-button-container">
					              		<button id="cost-<?= $key . '-' . $c; ?>" class="button" onclick="expandPaypal('button-area-<?= $key . '-' . $c; ?>')">$<?= $p; ?></button>
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
	document.body.classList.add('loading');
	var currency = '<?= $currency; ?>';
	var sThumbsContainer = document.getElementsByClassName('thumbsContainer');

	var paypal_url = 'https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card&currency=<?= strtoupper($currency); ?>';
	var script = document.createElement('script');

	script.onload = function(){
		document.body.classList.remove('loading');
	};

	script.setAttribute('src', paypal_url);
	document.head.appendChild(script);
	
	var shippingOptions = 
	{
        USD: {
        	id: "SHIP_USD",
            label: "Standard Domestic",
            type: "SHIPPING",
            selected: true,
            amount: {
                value: 5,
                currency_code: "USD"
            }
        },
        EUR: {
        	id: "SHIP_EUR",
            label: "Default",
            type: "SHIPPING",
            selected: true,
            amount: {
                value: 6,
                currency_code: "EUR"
            }
        },
        GBP: {
        	id: "SHIP_GBP",
            label: "Default",
            type: "SHIPPING",
            selected: true,
            amount: {
                value: 2,
                currency_code: "GBP"
            }
        }
    };

	function expandPaypal(buttonAreaId){
		let sButtonArea = document.getElementById(buttonAreaId);
		if( sButtonArea.classList.contains('viewing-paypal') ){
			sButtonArea.classList.remove('viewing-paypal');
		}
		else
		{
			let sViewing_paypal = document.querySelector('.button-area.viewing-paypal');
			if(sViewing_paypal)
				sViewing_paypal.classList.remove('viewing-paypal');
			sButtonArea.classList.add('viewing-paypal');
			let thisPaypalButtonContainer = sButtonArea.querySelector('.paypal-button-container');
		}
	}
	function createButton(buttonContainerId, price){
		console.log('createButton . . .');
		paypal.Buttons({
            createOrder: function(data, actions) {
            	console.log('createOrder . . .');
                return actions.order.create({
                    application_context: {
                        brand_name: 'O-R-G',
                        shipping_method: "United Postal Service"
                        // shipping_preference: 'NO_SHIPPING'
                    },
                    purchase_units: [{
                    	amount: {
                            currency_code: currency.toUpperCase(),
                            value: price
                    	},
		              	shipping: {
			              	options: [
			              		shippingOptions[currency.toUpperCase()]
			              	]
			            }
                    }]
                });
            },
            style: {
                color: 'black'
            },
            onError: function (err) {
				// For example, redirect to a specific error page
				// window.location.href = "/your-error-page-here";
				// window.location.href = "/shop/issues/error";
				console.log(err);
			},
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    /* 
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    var transaction = orderData.purchase_units[0].payments.captures[0];
                    alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');
                    const element = document.getElementById('paypal-button-container');
                    element.innerHTML = 'Thx.';
                    */
                    // let email = orderData.payer.email_address;
                    window.location.href = "/shop/issues/thank-you";
                    // console.log('on approve');
                });
            }
      }).render('#' + buttonContainerId);
	}
	var sBuy_button_container = document.getElementsByClassName('buy-button-container');

	[].forEach.call(sThumbsContainer, function(el, i){
		let thisPayPalButtonContainer = el.querySelector('.paypal-button-container');
		
		
		if(thisPayPalButtonContainer)
		{
			let thisBuy_button_container = el.querySelector('.buy-button-container');
			let hasButton = thisPayPalButtonContainer.querySelector('.paypal-button') !== null;
			if(!hasButton && thisBuy_button_container)
			{
				thisBuy_button_container.addEventListener('click', function(){
					let thisPrice = thisPayPalButtonContainer.getAttribute('price');
					console.log('thisPrice = '+thisPrice);
					createButton(thisPayPalButtonContainer.id, thisPrice);
				});
			}
		}
	});

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
</style>