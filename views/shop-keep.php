<?
// namespace stuff
use \Michelf\Markdown;

$r = $oo->get($uu->id);
$body = Markdown::defaultTransform($r['body']);
$deck = $r['deck'];

// only show back button on internal references
$internal = (substr($_SERVER['HTTP_REFERER'], 0, strlen($host)) === $host);
$back_url = "javascript:self.history.back();";

$journal_children = $oo->children($r['id']);

$protocol = isset($_SERVER['HTTPS']) && 
         $_SERVER['HTTPS'] === 'on' ? 
         "https://" : "http://";
$host = $protocol . $_SERVER['HTTP_HOST'];
// $paypal_client_id = getenv('PAYPAL_CLIENT_ID');
// sandbox
// $paypal_client_id = 'AarUvt7o6QoGOIcQTz9lMSf7UEtUGPJL8iX5mLmTFtIES07o31Pdn_pYSERT_IuhPuIVueizce3yXCzX';
// live
$paypal_client_id = 'ATB90kcor24zrbGtQCBUnIKeqtxMMaz5M1rgUbO4mcEt_ACUHXk52TPiBbyoQJaOiubhDz8jzA3iQepP';
$key = 0;
$c = 'USD';
$p = 20;
?>
<script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>"></script>
<div class="mainContainer">
	<div class="wordsContainer body"><?
		echo nl2br($deck);
		echo $body;
                if (isset($showsubscribe))
                       require_once("views/subscribe.php");
		if($internal && !isset($showsubscribe))
		{
		?><a href="<? echo $back_url; ?>">Go back</a><?
		}
	?></div>
	<div id="shopContainer" class="floatContainer">
		<? foreach($journal_children as $key => $child){
			if( substr($child['name1'], 0, 1) != '.')
			{
				$media = $oo->media($child['id']);
				if(count($media) > 0)
					$cover = m_url($media[0]);
				$prices_pattern = '/\[(USD|EU|GBP)\]\((.*?)\)/';
				preg_match_all($prices_pattern, $child['notes'], $temp);
				$prices = array();
				foreach($temp[2] as $c2 => $t)
					$prices[$temp[1][$c2]] = $t;
				
				?><div class="thumbsContainer journalContainer">
					<? if(isset($cover)){
						?><div class="issue-img-container"><img class="issue-img" src="<?= $cover; ?>"></div><?
					} ?>
					<div class="issue-title"><?= $child['name1']; ?></div>
					<br>
				</div><?
			}   
		} ?>
	</div>
	<section id="buy-<?= $key; ?>" class="buy-section">
            <div id="button-area-<?= $key; ?>" class="button-area">
            	<div id="paypal-button-container-<?= $key . '-' . $c; ?>" class="payment-option paypal-button-container"></div>
  		<div id="buy-button-container-<?= $key . '-' . $c; ?>" class="buy-button-container">
                    <button id="cost-<?= $key . '-' . $c; ?>" class="button">$<?= $c . ' $' . $p; ?></button>
              </div></div>
        
            <script>

            	var buy_button_container = document.getElementById('buy-button-container-<?= $key . '-' . $c; ?>');
                buy_button_container.addEventListener('click', function(){
                    document.body.classList.toggle('viewing-paypal');
                });
  			paypal.Buttons({
	                    createOrder: function(data, actions) {
	                        return actions.order.create({
	                            application_context: {
	                                brand_name: 'O-R-G',
	                                // shipping_preference: 'NO_SHIPPING'
	                            },
	                            purchase_units: [{
	                            	amount: {
		                                	currency_code: '<?= $c; ?>',
		                                   value: '<?= $p; ?>'
	                            	},
			              	shipping: {
				              	options: [
				              	{
				                            id: "SHIP_US",
				                            label: "US",
				                            type: "SHIPPING",
				                            selected: true,
				                            amount: {
				                                value: "3.00",
				                                currency_code: "USD"
				                            }
				                     },{
				                            id: "SHIP_EU",
				                            label: "Europe",
				                            type: "SHIPPING",
				                            selected: false,
				                            amount: {
				                                value: "8.00",
				                                currency_code: "USD"
				                            }
				                     },{
				                            id: "SHIP_OTHER",
				                            label: "Rest of the world",
				                            type: "SHIPPING",
				                            selected: false,
				                            amount: {
				                                value: "10.00",
				                                currency_code: "USD"
				                            }
				                     }]
				              }
	                            }]
	                        });
	                    },
	                    style: {
	                        color: 'black'
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
	                            console.log('on approve');   
	                        });
	                    }
	              }).render('#paypal-button-container-<?= $key . '-' . $c; ?>');
            </script>
        </section>
</div>