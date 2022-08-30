/* 
    buy now and shopping cart via paypal api
*/

// sandbox
// var paypal_client_id = 'AZOWN6t-ioLBjw9HiXfGexBtH5WsFqAy92SU5CTHYeX8PwBSk8j-C5LYZL0aY-f1dRRF138bGmC4KoOs';
// var paypal_client_id_eu = 'AXn_nFsUAS9wwsD7ArbuKnwPPmgsMKqxLyEIHT7d-oIEVbU-x36TMkKV7v-biQA8O3fZcycLEYvWQtBG';

// live
var paypal_client_id = 'Afwppna4LpZ2tpCOVh4kfISR2Q-VgcwX6nihNbf7hm3ATsDMvDY4TRaTQ47IUxAjSaou9QQYB4ccXxqt';
var paypal_client_id_eu = 'AZq6zNkJKOzSqFyhO67YyWPxQEqQ10aS1zlMSsnd-QPzCyOZhSUTvhPwMP_r7Dh3ybEhgtZbhJA12Ro_';

var paypal_url = 'https://www.paypal.com/sdk/js?client-id='+paypal_client_id+'&disable-funding=credit,card';
document.body.classList.add('loading');


function loadScript(url){
	var script = document.createElement('script');
	script.setAttribute('data-csp-nonce', 'xyz-123');
	script.onload = function(){
		document.body.classList.remove('loading');
	};
	script.setAttribute('src', url);
	document.head.appendChild(script);
	return script;
}

var shippingOptions_arr = {
	'issue' : {
	    USD: [
	    {
	    	id: "SHIP_US",
	        label: "DOMESTIC",
	        type: "SHIPPING",
	        selected: true,
	        amount: {
	            value: "10.00",
	            currency_code: "USD"
	        }
		}
		], 
	    EUR: [
	        {
	        	id: "SHIP_EU",
	            label: "WITHIN EU",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "10.00",
	                currency_code: "EUR"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: "40.00",
	                currency_code: "EUR"
	            }
	        }
	    ],
	    GBP: [
	        {
	        	id: "SHIP_UK",
	            label: "WITHIN UK",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "10.00",
	                currency_code: "GBP"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: "30.00",
	                currency_code: "GBP"
	            }
	        }
	    ]
	},
	'annual': {
	    USD: [
	    {
	    	id: "SHIP_US",
	        label: "DOMESTIC",
	        type: "SHIPPING",
	        selected: true,
	        amount: {
	            value: "10.00",
	            currency_code: "USD"
	        }
		}
		], 
	    EUR: [
	        {
	        	id: "SHIP_EU",
	            label: "WITHIN EU",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "8.00",
	                currency_code: "EUR"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: "40.00",
	                currency_code: "EUR"
	            }
	        }
	    ],
	    GBP: [
	        {
	        	id: "SHIP_UK",
	            label: "WITHIN UK",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "5.00",
	                currency_code: "GBP"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: "30.00",
	                currency_code: "GBP"
	            }
	        }
	    ]
	},
	'archive': {
	    USD: [
	    {
	    	id: "SHIP_US",
	        label: "DOMESTIC",
	        type: "SHIPPING",
	        selected: true,
	        amount: {
	            value: "10.00",
	            currency_code: "USD"
	        }
		}
		], 
	    EUR: [
	        {
	        	id: "SHIP_EU",
	            label: "WITHIN EU",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "8.00",
	                currency_code: "EUR"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: "40.00",
	                currency_code: "EUR"
	            }
	        }
	    ],
	    GBP: [
	        {
	        	id: "SHIP_UK",
	            label: "WITHIN UK",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "5.00",
	                currency_code: "GBP"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: "30.00",
	                currency_code: "GBP"
	            }
	        }
	    ]
	},
	'subscription-2': {
	    USD: [
		    {
		    	id: "SHIP_US",
		        label: "DOMESTIC",
		        type: "SHIPPING",
		        selected: true,
		        amount: {
		            value: "10.00",
		            currency_code: "USD"
		        }
			},
			{
		    	id: "SHIP_WORLD",
		        label: "ELSEWHERE",
		        type: "SHIPPING",
		        selected: false,
		        amount: {
		            value: "35.00",
		            currency_code: "USD"
		        }
			}
		], 
	    EUR: [
	        {
	        	id: "SHIP_EU",
	            label: "WITHIN EU",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "10.00",
	                currency_code: "EUR"
	            }
	        }
	    ],
	    GBP: [
	        {
	        	id: "SHIP_UK",
	            label: "WITHIN UK",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "10.00",
	                currency_code: "GBP"
	            }
	        }
	    ]
	},
	'subscription-12': {
	    USD: [
		    {
		    	id: "SHIP_US",
		        label: "DOMESTIC",
		        type: "SHIPPING",
		        selected: true,
		        amount: {
		            value: "10.00",
		            currency_code: "USD"
		        }
			},
			{
		    	id: "SHIP_WORLD",
		        label: "ELSEWHERE",
		        type: "SHIPPING",
		        selected: false,
		        amount: {
		            value: "220.00",
		            currency_code: "USD"
		        }
			}
		], 
	    EUR: [
	        {
	        	id: "SHIP_EU",
	            label: "WITHIN EU",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "10.00",
	                currency_code: "EUR"
	            }
	        }
	    ],
	    GBP: [
	        {
	        	id: "SHIP_UK",
	            label: "WITHIN UK",
	            type: "SHIPPING",
	            selected: true,
	            amount: {
	                value: "10.00",
	                currency_code: "GBP"
	            }
	        }
	    ]
	}
}

// var shippingOptions = shippingOptions_arr[type];

function expandPaypal(buttonAreaId, currency, itemName, type = ''){
	let sButtonArea = document.getElementById(buttonAreaId);
	if( sButtonArea.classList.contains('viewing-paypal') ){
		sButtonArea.classList.remove('viewing-paypal');
	} else {
		let sViewing_paypal = document.querySelector('.button-area.viewing-paypal');
		if(sViewing_paypal)
			sViewing_paypal.classList.remove('viewing-paypal');
		sButtonArea.classList.add('viewing-paypal');
		// let thisPaypalButtonContainer = sButtonArea.querySelector('.paypal-button-container');
	}
	let hasButton = sButtonArea.querySelector('.paypal-buttons') !== null;
	// console.log('hasButton = '+hasButton);
	if(!hasButton){
		var thisPaypalButtonContainer = sButtonArea.querySelector('.paypal-button-container');
		var thisPrice = thisPaypalButtonContainer.getAttribute('price');
		// console.log(thisPaypalButtonContainer.id);
		if(thisPaypalButtonContainer.id == 'paypal-button-container-cart')
			createCartButton();
		// else
		// 	createButton(thisPaypalButtonContainer.id, thisPrice, currency, itemName, type);
	}
}

function createButton(buttonContainerId, price, currency, itemName, type){
	// console.log('createButton . . .');
	// console.log(type);
	if(type == 'subscription') {
		if(itemName.indexOf('two years') !== -1){
			// console.log(itemName.indexOf('two years'));
			var options = shippingOptions_arr[type + '-2'];
		}
		else if(itemName.indexOf('twelve years') !== -1)
			var options = shippingOptions_arr[type + '-12'];
	} else
		var options = shippingOptions_arr[type];	
	// console.log(options);
	var baseAmount = parseFloat(price, 10);
	var totalValue = baseAmount + parseFloat(options[currency.toUpperCase()][0].amount.value, 10);
	var currencyUppercase = currency.toUpperCase();
	
	paypal.Buttons({
        createOrder: function(data, actions) {
        	// console.log('createOrder . . .');
            return actions.order.create({
                purchase_units: [{
                	amount: {
                        currency_code: currencyUppercase,
                        value: totalValue,
                        breakdown: {
							item_total: { 
								currency_code: currencyUppercase,
								value: baseAmount
							},
							shipping: {
								currency_code: currencyUppercase,
								value: options[currencyUppercase][0].amount.value
							}
						}
                	},
	              	shipping: {
		              	options: options[currencyUppercase]
		            },
		            items: [{
						name: itemName, /* Shows within upper-right dropdown during payment approval */
						unit_amount: {
							currency_code: currencyUppercase,
							value: baseAmount
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
				value: { 
					value: data.amount.value, 
					currency_code: data.amount.currency_code,
					breakdown: {
						item_total: { 
							currency_code: currencyUppercase,
							value: baseAmount
						},
						shipping: {
							currency_code: currencyUppercase,
							value: parseFloat(data.selected_shipping_option.amount.value, 10)
						}
					}
				}
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
            	if(type == 'subscription')
            		var return_url = location.protocol + '//' + location.host + "/shop/subscriptions/thank-you";
            	else
            		var return_url = location.protocol + '//' + location.host + "/shop/issues/thank-you";
            	// console.log(return_url);
                /*
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                var transaction = orderData.purchase_units[0].payments.captures[0];
                alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');
                const element = document.getElementById('paypal-button-container');
                element.innerHTML = 'Thx.';
                */
		let email = orderData.payer.email_address;
		return_url += '?email=' + email;
		actions.redirect(return_url);
                // console.log('on approve');
            });
        }

  	}).render('#' + buttonContainerId);
}

function createCartButton(){
	let sItem_row = document.getElementsByClassName('item-row');

	if(sItem_row.length > 0)
	{
		let currencyUppercase = currency.toUpperCase();
		let buttonContainerId = 'paypal-button-container-cart';
		let items = [];
		let type = '';
		let baseAmount = 0.0;
		let shipping_option_priority = {
			'subscription-12': 40,
			'subscription-2': 30,
			'issue': 20,
			'archive': 15,
			'annual': 10
		};
		[].forEach.call(sItem_row, function(el, i){
			let thisItemName = el.querySelector('.item-name').innerText;
			let thisItemPrice = el.querySelector('.item-price').innerText;
			let thisItemQuantity = el.querySelector('.item-quantity').innerText;
			let thisItem = {
				name: thisItemName, 
				unit_amount: {
					currency_code: currencyUppercase,
					value: thisItemPrice
				},
				quantity: thisItemQuantity
			};
			items.push(thisItem);

			let thisType = el.getAttribute('type');
			if( shipping_option_priority[thisType] === undefined )
				thisType = 'issue'; // set shipping plan of issue as fallback
			if(type == '' || shipping_option_priority[thisType] > shipping_option_priority[type])
				type = thisType;

			baseAmount += parseFloat(thisItemPrice, 10) * parseInt(thisItemQuantity);

		});
		let options = shippingOptions_arr[type];
		let totalValue = baseAmount + parseFloat(options[currency.toUpperCase()][0].amount.value, 10);
		paypal.Buttons({
	        createOrder: function(data, actions) {
	        	// console.log('createOrder . . .');
	            return actions.order.create({
	                purchase_units: [{
	                	amount: {
	                        currency_code: currencyUppercase,
	                        value: totalValue,
	                        breakdown: {
								item_total: { 
									currency_code: currencyUppercase,
									value: baseAmount
								},
								shipping: {
									currency_code: currencyUppercase,
									value: options[currencyUppercase][0].amount.value
								}
							}
	                	},
		              	shipping: {
			              	options: options[currencyUppercase]
			            },
			            items: items
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
					value: { 
						value: data.amount.value, 
						currency_code: data.amount.currency_code,
						breakdown: {
							item_total: { 
								currency_code: currencyUppercase,
								value: baseAmount
							},
							shipping: {
								currency_code: currencyUppercase,
								value: parseFloat(data.selected_shipping_option.amount.value, 10)
							}
						}
					}
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
                    eraseCookie('cart');
                    var return_url = location.protocol + '//' + location.host + "/shop/thx";
	            	// console.log(return_url);
	                // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    	/*
	                var transaction = orderData.purchase_units[0].payments.captures[0];
	                alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');
	                // const element = document.getElementById('paypal-button-container');
	                // element.innerHTML = 'Thx.';
	                let email = orderData.payer.email_address;
	                console.log(email);
                    	*/
					let email = orderData.payer.email_address;
					return_url += '?email=' + encodeURIComponent(email)+'&currency='+encodeURIComponent(currencyUppercase);
					[].forEach.call(items, function(el){
						return_url += '&items[]='+ encodeURIComponent(el['quantity'] + ' × '+ el['name']);
					});
					actions.redirect(return_url);
	                // console.log('on approve');
	            });
	        }
	  	}).render('#' + buttonContainerId);
	} else console.log('cart is empty');
}

function addToCartByClick(event, quantityToAdd = 1){
	// console.log('addToCartByClick()');
	let thisElement = event.target;
    // hide add to cart button
    thisElement.parentNode.parentNode.classList.remove('viewing-paypal');
	let sCart_container = document.getElementById('cart-container');
	if (cart_symbol = document.getElementById('cart-symbol'))
        document.body.classList.add('viewing-cart-symbol');
	let price = thisElement.getAttribute('price');
	// check if this item exists in the cart
	// let slug = ;
	let rowId = 'item-row-'+thisElement.getAttribute('slug');
	let thisRow = sCart_container.querySelector('#'+rowId);
	let thisQuantity, thisAmount;
	let quantity = 0;
	if(!thisRow){
		let itemName = thisElement.getAttribute('itemName');
		let type = thisElement.getAttribute('type');
		printToCart(rowId, itemName, type, price, 0);
		thisRow = sCart_container.querySelector('#'+rowId);
		// console.log('has been added to the cart');
	}
	thisQuantity = thisRow.querySelector('.item-quantity');
	thisAmount = thisRow.querySelector('.item-amount');
	quantity = parseFloat(thisQuantity.innerText);
	let sItem_count = document.getElementById('item-count');
	sItem_count.innerHTML = parseInt(sItem_count.innerHTML) + quantityToAdd;
	quantity += quantityToAdd;
	thisAmount.innerText = quantity * price;
	thisQuantity.innerHTML = quantity;
	updateRowToCookie();
}

function addToCartFromJson(obj){
	// console.log('addToCartFromJson()');
	printToCart(obj.id, obj.itemName, obj.type, obj.price, obj.quantity);
}

function printToCart(rowId, itemName, type, price, quantity){
	// console.log('printToCart()');
	// let itemName = thisElement.getAttribute('itemName');
	// let type = thisElement.getAttribute('type');
	thisRow = document.createElement('DIV');
	thisRow.id = rowId;
	thisRow.className = 'item-row';
	thisRow.setAttribute('type', type);
	let thisName = document.createElement('DIV');
	thisName.className = 'item-column item-name';
	thisName.innerHTML = itemName;
	let thisPrice_container = document.createElement('DIV');
	thisPrice_container.className = 'item-column item-price-container';
	let thisPrice = document.createElement('SPAN');
	thisPrice.className = 'item-price';
	thisPrice.innerHTML = price;
	let thisPrice_symbol = document.createElement('SPAN');
	thisPrice_symbol.innerHTML = acceptedCurrenciesSymbols[currency];
	thisPrice_container.appendChild(thisPrice_symbol);
	thisPrice_container.appendChild(thisPrice);
	let thisQuantity_container = document.createElement('DIV');
	thisQuantity_container.className = 'item-column item-quantity-container flex-container';
	let thisQuantity_plus = document.createElement('SPAN');
	thisQuantity_plus.innerText = '+';
	thisQuantity_plus.className = 'item-quantity-plus';
	thisQuantity_plus.onclick = function(event){
		let newQuantity = parseInt(event.target.parentNode.querySelector('.item-quantity').innerText) + 1;
		event.target.parentNode.querySelector('.item-quantity').innerText = newQuantity;
		thisAmount.innerText = parseFloat(price, 10) * newQuantity;
		updateRowToCookie();
	}
	let thisQuantity_minus = document.createElement('SPAN');
	thisQuantity_minus.innerText = '-';
	thisQuantity_minus.className = 'item-quantity-minus';
	thisQuantity_minus.onclick = function(event){
		let newQuantity = parseInt(event.target.parentNode.querySelector('.item-quantity').innerText) - 1;
		if(newQuantity != 0){
			event.target.parentNode.querySelector('.item-quantity').innerText = newQuantity; 
			thisAmount.innerText = parseFloat(price, 10) * newQuantity;
			updateRowToCookie();
		}
		// else
		// 	thisRow.parentNode.removeChild(thisRow);
	}
	thisQuantity = document.createElement('DIV');
	thisQuantity.className = 'item-quantity';
	thisQuantity.innerText = quantity;
	thisQuantity_container.appendChild(thisQuantity_minus);
	thisQuantity_container.appendChild(thisQuantity);
	thisQuantity_container.appendChild(thisQuantity_plus);
	let thisAmount_container = document.createElement('DIV');
	thisAmount_container.className = 'item-column item-amount-container';
	thisAmount = document.createElement('SPAN');
	thisAmount.className = 'item-amount';
	thisAmount.innerText = quantity * price;
	let thisAmount_symbol = document.createElement('SPAN');
	thisAmount_symbol.innerHTML = acceptedCurrenciesSymbols[currency];
	thisAmount_container.appendChild(thisAmount_symbol);
	thisAmount_container.appendChild(thisAmount);
	let thisRemove = document.createElement('DIV');
	thisRemove.className = 'item-column item-remove';
	thisRemove.innerText = 'remove';
	thisRemove.onclick = function(){
		let temp = document.getElementById(rowId);
		let sItem_count = document.getElementById('item-count');
		sItem_count.innerHTML = parseInt(sItem_count.innerHTML) - parseInt(temp.querySelector('.item-quantity').innerText);
		if (parseInt(sItem_count.innerHTML) <= 0) {
            sItem_count.innerHTML = '0';
            if (cart_symbol = document.getElementById('cart-symbol'))
                document.body.classList.remove('viewing-cart-symbol');
        }
		temp.parentNode.removeChild(temp);
		updateRowToCookie();
	};
	thisRow.appendChild(thisName);
	thisRow.appendChild(thisPrice_container);
	thisRow.appendChild(thisQuantity_container);
	thisRow.appendChild(thisAmount_container);
	thisRow.appendChild(thisRemove);
	let sCart_container = document.getElementById('cart-container');
	if(sCart_container) sCart_container.appendChild(thisRow);
}

function toggleCart(){
	document.body.classList.toggle('viewing-cart');
}

function updateRowToCookie(){
	let sRows = document.getElementsByClassName('item-row');
	let json = [];
	if(sRows.length > 0){
		[].forEach.call(sRows, function(el, i){
			let this_obj = {};
			this_obj.id = el.id;
			this_obj.itemName = el.querySelector('.item-name').innerText;
			this_obj.type = el.getAttribute('type');
			this_obj.price = el.querySelector('.item-price').innerText;
			this_obj.quantity = el.querySelector('.item-quantity').innerText;
			json.push(this_obj);
		});
	}
	// console.log(json);
	createCookie( 'cart', JSON.stringify(json), '' );
}
