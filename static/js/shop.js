// load paypal library

// sandbox
var paypal_client_id = 'AarUvt7o6QoGOIcQTz9lMSf7UEtUGPJL8iX5mLmTFtIES07o31Pdn_pYSERT_IuhPuIVueizce3yXCzX';
// live
// paypal_client_id = 'ATB90kcor24zrbGtQCBUnIKeqtxMMaz5M1rgUbO4mcEt_ACUHXk52TPiBbyoQJaOiubhDz8jzA3iQepP';

var paypal_url = 'https://www.paypal.com/sdk/js?client-id='+paypal_client_id+'&disable-funding=credit,card';

document.body.classList.add('loading');

function loadScript(url){
	var script = document.createElement('script');
	script.onload = function(){
		document.body.classList.remove('loading');
	};
	script.setAttribute('src', url);
	document.head.appendChild(script);
	return script;
}


var shippingOptions = 
{
    USD: [
    {
    	id: "SHIP_USD",
        label: "DOMESTIC",
        type: "SHIPPING",
        selected: true,
        amount: {
            value: 10,
            currency_code: "USD"
        }
	}
	], 
    EUR: [
        {
        	id: "SHIP_EUR",
            label: "WITHIN EU",
            type: "SHIPPING",
            selected: true,
            amount: {
                value: 10,
                currency_code: "EUR"
            }
        },
        {
        	id: "SHIP_WORLD",
            label: "REST OF THE WORLD",
            type: "SHIPPING",
            selected: false,
            amount: {
                value: 40,
                currency_code: "EUR"
            }
        }
    ],
    GBP: [
        {
        	id: "SHIP_GBP",
            label: "WITHIN UK",
            type: "SHIPPING",
            selected: true,
            amount: {
                value: 10,
                currency_code: "GBP"
            }
        },
        {
        	id: "SHIP_WORLD",
            label: "REST OF THE WORLD",
            type: "SHIPPING",
            selected: false,
            amount: {
                value: 30,
                currency_code: "GBP"
            }
        }
    ]
};

function expandPaypal(buttonAreaId, currency, itemName){
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
		// let thisPaypalButtonContainer = sButtonArea.querySelector('.paypal-button-container');
	}
	let hasButton = sButtonArea.querySelector('.paypal-buttons') !== null;
	if(!hasButton){
		var thisPaypalButtonContainer = sButtonArea.querySelector('.paypal-button-container');
		var thisPrice = thisPaypalButtonContainer.getAttribute('price');
		createButton(thisPaypalButtonContainer.id, thisPrice, currency, itemName);
	}
}
function createButton(buttonContainerId, price, currency, itemName){
	console.log('createButton . . .');
	var baseAmount = parseFloat(price, 10);
	var totalValue = baseAmount + parseFloat(shippingOptions[currency.toUpperCase()][0].amount.value, 10);
	var currencyUppercase = currency.toUpperCase();
	paypal.Buttons({
        createOrder: function(data, actions) {
        	console.log('createOrder . . .');
        	console.log(data);
            return actions.order.create({
                application_context: {
                    brand_name: 'O-R-G',
                    shipping_method: "United Postal Service"
                    // shipping_preference: 'NO_SHIPPING'
                },
                purchase_units: [{
                	amount: {
                        currency_code: currencyUppercase,
                        value: totalValue,
                        breakdown: {
							item_total: {  /* Required when including the `items` array */
								currency_code: currencyUppercase,
								value: totalValue
							}
						}
                	},
	              	shipping: {
		              	options: shippingOptions[currencyUppercase]
		            },
		            items: [{
						name: itemName, /* Shows within upper-right dropdown during payment approval */
						unit_amount: {
							currency_code: currencyUppercase,
							value: price
						},
						quantity: "1"
					}]
                }]
                
            });
        },
        onShippingChange: function (data, actions) {
			// console.log("SELECTED_OPTION", data.selected_shipping_option); // data.selected_shipping_option contains the selected shipping option
			
			data.amount.value = parseFloat(baseAmount, 10) + parseFloat(data.selected_shipping_option.amount.value, 10);
			data.amount.value = data.amount.value + '';
			return actions.order.patch([{
				op: "replace",
				path: "/purchase_units/@reference_id=='default'/amount",
				value: { value: data.amount.value, currency_code: data.amount.currency_code }
			}]);
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

