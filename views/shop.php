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


	.paypal-button-container {
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
	    margin-top: 11px;
	}
	.viewing-paypal .buy-button-container .button
	{
	    /*background-color: #ccc;*/
	    /*border-color: #ccc;*/
	    background-color: #0E0;
	    border-color: #0E0;
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
	.currencyOption.active,
	.currencyOption:hover
	{
		background-color: #0C0;
		color: #fff;
	}
</style>

<?
// namespace stuff
use \Michelf\Markdown;

$r = $oo->get($uu->id);
$body = Markdown::defaultTransform($r['body']);
$deck = $r['deck'];

// only show back button on internal references
$internal = isset($_SERVER['HTTP_REFERER']) && (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);
$back_url = "javascript:self.history.back();";

$journal_children = $oo->children($r['id']);

$protocol = isset($_SERVER['HTTPS']) && 
         $_SERVER['HTTPS'] === 'on' ? 
         "https://" : "http://";
$host = $protocol . $_SERVER['HTTP_HOST'];
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
<script id="paypal-library-usd" src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card"></script>
<script>
	const loadDynamicScript = (callback, buttonContainerId, price, currency) => {
		let scriptId = 'paypal-library-'+currency.toLowerCase();
		let scriptUrl = "https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card&currency="+currency;
		const existingScript = document.getElementById(scriptId);

		if (!existingScript) {
			const script = document.createElement('script');
			script.src = scriptUrl; // URL for the third-party library being loaded.
			script.id = scriptId ; // e.g., googleMaps or stripe
			document.body.appendChild(script);
			script.onload = () => {
				if (callback) callback(buttonContainerId, price, currency);
			};
		}

		if (existingScript && callback) callback();
	};

	var shippingOptions = 
	{
        USD: {
        	US: 3.00,
        	EU: 8.00,
        	OTHER: 10.00
        },
        GBP: {
        	US: 2.00,
        	EU: 6.00,
        	OTHER: 8.00
        },
        EUR: {
        	US: 2.50,
        	EU: 6.50,
        	OTHER: 8.50
        }
    };

	function expandPaypal(buttonAreaId, price, currency="USD"){
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
			if(thisPaypalButtonContainer)
				loadDynamicScript(createButton, thisPaypalButtonContainer.id, price, currency);
		}
	}
	function createButton(buttonContainerId, price, currency){
		paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    application_context: {
                        brand_name: 'O-R-G',
                        shipping_method: "United Postal Service"
                        // shipping_preference: 'NO_SHIPPING'
                    },
                    purchase_units: [{
                    	amount: {
                            currency_code: currency,
                            value: price
                    	},
		              	shipping: {
			              	options: [
			              	{
		                        id: "SHIP_US",
		                        label: "US",
		                        type: "SHIPPING",
		                        selected: true,
		                        amount: {
		                            value: shippingOptions[currency]['US'],
		                            currency_code: currency
		                        }
			                },{
		                        id: "SHIP_EU",
		                        label: "Europe",
		                        type: "SHIPPING",
		                        selected: false,
		                        amount: {
		                            value: shippingOptions[currency]['EU'],
		                            currency_code: currency
		                        }
		                    },{
		                        id: "SHIP_OTHER",
		                        label: "Rest of the world",
		                        type: "SHIPPING",
		                        selected: false,
		                        amount: {
		                            value: shippingOptions[currency]['OTHER'],
		                            currency_code: currency
		                        }
		                    }]
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
                    console.log('on approve');
                });
            }
      }).render('#' + buttonContainerId);
	}
	function switchCurrency(evt){
		let thisOption = evt.target;
		if(!thisOption.classList.contains('active'))
		{
			let currentActive = document.querySelector('.currencyOption.active');
			let currentCurrency = currentActive.innerText.toLowerCase();
			currentActive.classList.remove('active');

			thisOption.classList.add('active');
			document.body.classList.remove('viewing-'+currentCurrency);
			document.body.classList.add('viewing-'+thisOption.innerText.toLowerCase());
		}
		
	}
	document.body.classList.add('viewing-usd');
</script>
<div id="currencySwitchWrapper" class="time"><span id="currencyOption-usd" class="currencyOption active" onclick="switchCurrency(event)">USD</span> | <span id="currencyOption-eur" class="currencyOption" onclick="switchCurrency(event)">EUR</span> | <span id="currencyOption-gbp" class="currencyOption" onclick="switchCurrency(event)">GBP</span></div>
<div class="mainContainer">
	<div id="shopContainer" class="floatContainer">
		<div class="thumbsContainer journalContainer"></div>
		<? foreach($journal_children as $key => $child){
			if( substr($child['name1'], 0, 1) != '.')
			{
				$media = $oo->media($child['id']);
				if(count($media) > 0)
					$cover = m_url($media[0]);
				$prices_pattern = '/\[(USD|EUR|GBP)\]\((.*?)\)/';
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
					?><!--
					<div class="issue-title"><?= $child['name1']; ?></div>
					<a class="shopItemLink" href="<?= $base_url . $child['url']; ?>">Read more</a>
					<br>
					-->
					<section id="buy-<?= $key; ?>" class="buy-section">
				       	<?
				       	if(!empty($prices))
				       	{
				       		foreach($prices as $c => $p) { ?>
		                		<div id="button-area-<?= $key . '-' . $c; ?>" class="button-area button-area-<?= strtolower($c); ?>">
		                			<div id="paypal-button-container-<?= $key . '-' . $c; ?>" class="payment-option paypal-button-container"></div>
			                		<div id="buy-button-container-<?= $key . '-' . $c; ?>" class="buy-button-container">
					              		<button id="cost-<?= $key . '-' . $c; ?>" class="button" onclick="expandPaypal('button-area-<?= $key . '-' . $c; ?>', <?= $p; ?>, '<?= $c; ?>')">$<?= $p; ?></button>
					            	</div>
					            	
					            	<script>
					            		createButton('paypal-button-container-<?= $key . '-' . $c; ?>', <?= $p; ?>, '<?= $c; ?>');
					            	</script>
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
