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
$paypal_client_id = getenv('PAYPAL_CLIENT_ID');
?>
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD&disable-funding=credit,card"></script>
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
				// $price_pattern = '/\[price\]\((.*?\))\)/';
				// var_dump($child['notes']);
				// preg_match($price_pattern, $child['notes'], $temp);
				$prices_pattern = '/\[(USD|EU|GBP)\]\((.*?)\)/';
				preg_match_all($prices_pattern, $child['notes'], $temp);
				$prices = array();
				foreach($temp[2] as $c => $t)
					$prices[$temp[1][$c]] = $t;
				// var_dump($prices);
				// die();
				
				?><div class="thumbsContainer journalContainer">
					<? if(isset($cover)){
						?><div class="issue-img-container"><img class="issue-img" src="<?= $cover; ?>"></div><?
					} ?>
					<div class="issue-title"><?= $child['name1']; ?></div>
					<br>
					<div class="issue-body"><?= $child['body']; ?></div>
					<section id="buy-<?= $key; ?>">
				            <div id="button-area-<?= $key; ?>">
				                
				                <?
				                	foreach($prices as $c => $p)
				                	{
				                		?>
				                		<div id="paypal-button-container-<?= $key . '-' . $c; ?>" class="payment-option paypal-button-container"></div>
				                		<div id="buy-button-container-<?= $key . '-' . $c; ?>" class="buy-button-container">
						                    <button id="cost-<?= $key . '-' . $c; ?>" class="button">$<?= $c . ' $' . $p; ?></button>
						              </div>
				                		<?
				                	}
				                ?>
				                
				            </div>
				        
				            <script>
				           
				                var host = <?= json_encode($host); ?>;
				                // var download_url = host + '/thx?name=' + filename;
				                <?
				                foreach($prices as $c => $p)
			                	{
			                	?>
			                	var buy_button_container = document.getElementById('buy-button-container-<?= $key . '-' . $c; ?>');
				                // var download_code_container = document.getElementById('download-code-container');
				                // var download_code = document.getElementById('download-code');
				                buy_button_container.addEventListener('click', function(){
				                    document.body.classList.toggle('viewing-payment-options');
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
					                                // description: filename
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
			                	<? } ?>
				            </script>
				        </section>
				</div><?
			}   
		} ?>
	</div>
</div>