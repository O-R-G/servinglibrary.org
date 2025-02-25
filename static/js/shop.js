/* 
    buy now and shopping cart via paypal api
*/
if(isSandbox)
{
	// sandbox
	var paypal_client_id = 'AZOWN6t-ioLBjw9HiXfGexBtH5WsFqAy92SU5CTHYeX8PwBSk8j-C5LYZL0aY-f1dRRF138bGmC4KoOs';
	var paypal_client_id_eu = 'AXn_nFsUAS9wwsD7ArbuKnwPPmgsMKqxLyEIHT7d-oIEVbU-x36TMkKV7v-biQA8O3fZcycLEYvWQtBG';
}
else
{
	// live
	var paypal_client_id = 'Afwppna4LpZ2tpCOVh4kfISR2Q-VgcwX6nihNbf7hm3ATsDMvDY4TRaTQ47IUxAjSaou9QQYB4ccXxqt';
	var paypal_client_id_eu = 'AZq6zNkJKOzSqFyhO67YyWPxQEqQ10aS1zlMSsnd-QPzCyOZhSUTvhPwMP_r7Dh3ybEhgtZbhJA12Ro_';
}

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
	'default':{
		USD: [
	    {
	    	id: "SHIP_US",
	        label: "DOMESTIC",
	        type: "SHIPPING",
	        selected: true,
	        amount: {
	            value: 0,
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
	                value: 0,
	                currency_code: "EUR"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: 0,
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
	                value: 0,
	                currency_code: "GBP"
	            }
	        },
	        {
	        	id: "SHIP_WORLD",
	            label: "REST OF THE WORLD",
	            type: "SHIPPING",
	            selected: false,
	            amount: {
	                value: 0,
	                currency_code: "GBP"
	            }
	        }
	    ]
	},
	'subscription': {
		USD: [
		    {
		    	id: "SHIP_US",
		        label: "DOMESTIC",
		        type: "SHIPPING",
		        selected: true,
		        amount: {
		            value: 0,
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
	                value: 0,
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
	                value: 0,
	                currency_code: "GBP"
	            }
	        }
	    ]
	}
};
var shippingFeeByItem_arr = {
	'USD': {
		"SHIP_US": {
			'issue': 7.00,
			'annual': 10.00,
			'archive': 10.00,
			'edition': 10.00,
			'subscription-2': 14.00,
			'subscription-12': 84.00
		}
	},
	'EUR': {
		"SHIP_EU": {
			'issue': 6.00,
			'annual': 12.00,
			'archive': 35.00,
			'edition': 45.00,
			'subscription-2': 12.00,
			'subscription-12': 72.00
		},
		"SHIP_WORLD": {
			'issue': 12.00,
			'annual': 25.00,
			'archive': 55.00,
			'edition': 65.00
		}
	},
	'GBP': {
		"SHIP_UK": {
			'issue': 5.00,
			'annual': 12.00,
			'archive': 30.00,
			'edition': 40.00,
			'subscription-2': 10.00,
			'subscription-12': 60.00
		},
		"SHIP_WORLD": {
			'issue': 10.00,
			'annual': 20.00,
			'archive': 45.00,
			'edition': 55.00
		}
	}
};
// var shippingFeeByAmount_arr = {
// 	'USD': {
// 		'10.00': {
// 			'1': '10.00',
// 			'2': '9.00',
// 			'3': '8.00',
// 			'4': '7.00',
// 			'5': '6.00'
// 		},
// 		'20.00': {
// 			'1': '20.00',
// 			'2': '18.00',
// 			'3': '16.00',
// 			'4': '14.00',
// 			'5': '12.00'
// 		},
// 	},
// 	'EUR': {
// 		'8.00': {
// 			'1': '8.00',
// 			'2': '7.00',
// 			'3': '6.00',
// 			'4': '5.00',
// 			'5': '4.00'
// 		},
// 		'10.00': {
// 			'1': '10.00',
// 			'2': '9.00',
// 			'3': '8.00',
// 			'4': '7.00',
// 			'5': '6.00'
// 		},
// 		'20.00': {
// 			'1': '20.00',
// 			'2': '18.00',
// 			'3': '16.00',
// 			'4': '14.00',
// 			'5': '12.00'
// 		},
// 		'40.00': {
// 			'1': '40.00',
// 			'2': '37.00',
// 			'3': '34.00',
// 			'4': '31.00',
// 			'5': '29.00'
// 		},
// 	},
// 	'GBP': {
// 		'5.00': {
// 			'1': '5.00',
// 			'2': '4.50',
// 			'3': '3.50'

// 		},
// 		'10.00': {
// 			'1': '10.00',
// 			'2': '9.00',
// 			'3': '8.00',
// 			'4': '7.00',
// 			'5': '6.00'
// 		},
// 		'20.00': {
// 			'1': '20.00',
// 			'2': '18.00',
// 			'3': '16.00',
// 			'4': '14.00',
// 			'5': '12.00'
// 		},
// 		'30.00': {
// 			'1': '30.00',
// 			'2': '28.00',
// 			'3': '26.00',
// 			'4': '24.00',
// 			'5': '22.00'
// 		},
// 	}
// }
function getFeeByAmount(basic_fee, amount){
	if(typeof basic_fee === 'string') basic_fee = parseFloat(basic_fee);
	if(typeof amount === 'string') amount = parseInt(amount);
	let output = 0;
	let r = 1;
	for(let i = 0; i < amount; i++) {
		if(i === 1) r = 2;
		else if(i === 2) r = 3;
		output += basic_fee / r;
	}
	return output;
}
function expandPaypal(buttonAreaId, currency, itemName, type = ''){
	let sButtonArea = document.getElementById(buttonAreaId);
	if( sButtonArea.classList.contains('viewing-paypal') ){
		sButtonArea.classList.remove('viewing-paypal');
	} else {
		let sViewing_paypal = document.querySelector('.button-area.viewing-paypal');
		if(sViewing_paypal)
			sViewing_paypal.classList.remove('viewing-paypal');
		sButtonArea.classList.add('viewing-paypal');
	}
	let hasButton = sButtonArea.querySelector('.paypal-buttons') !== null;
	if(!hasButton){
		var thisPaypalButtonContainer = sButtonArea.querySelector('.paypal-button-container');
		var thisPrice = thisPaypalButtonContainer.getAttribute('price');
		if(thisPaypalButtonContainer.id == 'paypal-button-container-cart')
			createCartButton();
	}
}
/*
function createButton(buttonContainerId, price, currency, itemName, type){
	var currencyUppercase = currency.toUpperCase();
	var options = shippingOptions_arr[type];
	if(options == undefined)
		options = shippingOptions_arr['default'];
	options = options[currencyUppercase];
	for (const [key, value] of Object.entries(options)) {
		options[key].amount.value = shippingFeeByItem_arr[currencyUppercase][value.id][type];
	}
	var shippingFee = shippingFeeByItem_arr[currencyUppercase][0][type];
	var baseAmount = parseFloat(price, 10);
	var totalValue = baseAmount + parseFloat(shippingFee, 10);
	

	let items = [];
	let thisItem = {
		name: itemName, 
		unit_amount: {
			currency_code: currencyUppercase,
			value: price
		},
		quantity: 1
	};
	items.push(thisItem);
	
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
						name: itemName,
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
			console.log('onshippingchange');
			console.log(data);
			console.log(data.amount.currency_code, data.shipping_address.country_code);
			if(data.amount.currency_code == 'USD' && data.shipping_address.country_code != 'US'){
				return actions.reject();
			}
			else
			{
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
			}
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
				let email = orderData.payer.email_address;
				return_url += '?email=' + encodeURIComponent(email)+'&currency='+encodeURIComponent(currencyUppercase);
				[].forEach.call(items, function(el){
					return_url += '&items[]='+ encodeURIComponent(el['quantity'] + ' × '+ el['name']);
				});
				actions.redirect(return_url);
            });
        }

  	}).render('#' + buttonContainerId);
}
*/
function getTotalShippingFee(elements, option, totalAmount, currency){
	var output = 0;
	const items_by_type = {};
	
	[].forEach.call(elements, function(el, i){
		// console.log(el);
		let thisItemQuantity = el.querySelector('.item-quantity').innerText;
		let thisType = el.getAttribute('type');
		if(thisType == '')
			thisType = 'issue';
		let thisSubType = el.getAttribute('subtype');
		if(thisSubType != undefined)
			thisType = thisType + '-' + thisSubType;
		var thisBasicShippingFee = shippingFeeByItem_arr[currency][option.id][thisType].toFixed(2);
		// var thisFinalShippingFee = shippingFeeByAmount_arr[currency][thisBasicShippingFee][totalAmount];
		// var thisFinalShippingFee = getFeeByAmount(thisBasicShippingFee, thisItemQuantity);
		// if totalAmount is larger than the defined shipping fee array, use the last item in the array.
		// if(thisFinalShippingFee == undefined)
		// 	thisFinalShippingFee = Object.values(shippingFeeByAmount_arr[currency][thisBasicShippingFee]).pop();
		if(typeof items_by_type[thisType] === 'undefined') {
			items_by_type[thisType] = {
				'quantity': 0,
				'basic_fee': thisBasicShippingFee
			}
		}
		items_by_type[thisType]['quantity'] += parseInt(thisItemQuantity);
		// output += thisFinalShippingFee * parseInt(thisItemQuantity);
	});
	for(const type in items_by_type) {
		let this_data = items_by_type[type];
		output += getFeeByAmount(this_data['basic_fee'], this_data['quantity']);
	}
	output = parseFloat(output.toFixed(2));
	console.log('total', output);
	return output;
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
		var totalShippingFee = 0;
		var totalItemQuantity = 0;
		var hasSubscription = false;
		// const items_by_type = {};
		[].forEach.call(sItem_row, function(el, i){
			let thisItemName = el.querySelector('.item-name').innerText;
			let thisItemPrice = el.querySelector('.item-price').innerText;
			let thisItemQuantity = el.querySelector('.item-quantity').innerText;
			var thisType = el.getAttribute('type');
			if(thisType == '')
				thisType = 'issue';
			let thisItem = {
				name: thisItemName, 
				unit_amount: {
					currency_code: currencyUppercase,
					value: thisItemPrice
				},
				quantity: thisItemQuantity
			};
			items.push(thisItem);
			
			if(thisType == 'subscription')
				hasSubscription = true;
			baseAmount += parseFloat(thisItemPrice, 10) * parseInt(thisItemQuantity);
			totalItemQuantity += parseInt(thisItemQuantity);
		});
		if(hasSubscription)
			var options = shippingOptions_arr['subscription'];
		else
			var options = shippingOptions_arr['default'];
		options = options[currencyUppercase];
		options.forEach(function(el, i){
			el['amount']['value'] = getTotalShippingFee(sItem_row, el, totalItemQuantity, currencyUppercase);
		});
		// console.log(options);
		// return;
		let totalValue = baseAmount + parseFloat(options[0].amount.value, 10);
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
									value: options[0].amount.value
								}
							}
	                	},
		              	shipping: {
			              	options: options
			            },
			            items: items
	                }]
	                
	            });
	        },
	        onShippingChange: function (data, actions) {
				if(data.amount.currency_code == 'USD' && data.shipping_address.country_code != 'US'){
					return actions.reject();
				}
				else
				{
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
				}
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
                    eraseCookie('serving-library-shop-cart');
                    var return_url = location.protocol + '//' + location.host + "/shop/thx";
					let email = orderData.payer.email_address;
					return_url += '?email=' + encodeURIComponent(email)+'&currency='+encodeURIComponent(currencyUppercase);
					[].forEach.call(items, function(el){
						return_url += '&items[]='+ encodeURIComponent(el['quantity'] + ' × '+ el['name']);
					});
					actions.redirect(return_url);
	            });
	        }
	  	}).render('#' + buttonContainerId);
	} else console.log('cart is empty');
}

function addToCartByClick(event, quantityToAdd = 1){
	let thisElement = event.target;
    thisElement.parentNode.parentNode.classList.remove('viewing-paypal');
	let sCart_container = document.getElementById('cart-container');
	if (cart_symbol = document.getElementById('cart-symbol'))
        document.body.classList.add('viewing-cart-symbol');
	let price_all = {
		'usd': thisElement.getAttribute('usd'),
		'eur': thisElement.getAttribute('eur'),
		'gbp': thisElement.getAttribute('gbp')
	};
	// check if this item exists in the cart
	let rowId = 'item-row-'+thisElement.getAttribute('slug');
	let thisRow = sCart_container.querySelector('#'+rowId);
	let thisQuantity, thisAmount;
	let quantity = 0;
	if(!thisRow){
		let itemName = thisElement.getAttribute('itemName');
		let type = thisElement.getAttribute('type');
		let subType = false;
		if(type == 'subscription'){
			if(thisElement.getAttribute('slug') == 'subscription-12-years')
				subType = '12';
			else if(thisElement.getAttribute('slug') == 'subscription-2-years')
				subType = '2';
		}
		printToCart(rowId, itemName, type, price_all, 0, subType);
		thisRow = sCart_container.querySelector('#'+rowId);
	}
	thisQuantity = thisRow.querySelector('.item-quantity');
	thisAmount = thisRow.querySelector('.item-amount');
	quantity = parseFloat(thisQuantity.innerText);
	let sItem_count = document.getElementById('item-count');
	sItem_count.innerHTML = parseInt(sItem_count.innerHTML) + quantityToAdd;
	quantity += quantityToAdd;
	thisAmount.innerText = quantity * price_all[currency];
	thisQuantity.innerHTML = quantity;
	updateRowToCookie();
}

function addToCartFromJson(obj){
	printToCart(obj.id, obj.itemName, obj.type, obj.prices, obj.quantity, obj.subType);
}

function printToCart(rowId, itemName, type, prices, quantity, subType = false){
	thisRow = document.createElement('DIV');
	thisRow.id = rowId;
	thisRow.className = 'item-row';
	thisRow.setAttribute('type', type);
	if(subType)
		thisRow.setAttribute('subType', subType);
	let thisName = document.createElement('DIV');
	thisName.className = 'item-column item-name';
	thisName.innerHTML = itemName;
	let thisPrice_container = document.createElement('DIV');
	thisPrice_container.className = 'item-column item-price-container';
	let thisPrice = document.createElement('SPAN');
	thisPrice.className = 'item-price';
	thisPrice.innerHTML = prices[currency];
	let thisPrice_symbol = document.createElement('SPAN');
	thisPrice_symbol.className = 'item-price-symbol';
	thisPrice_symbol.innerHTML = acceptedCurrenciesSymbols[currency];
	thisPrice_container.appendChild(thisPrice_symbol);
	thisPrice_container.appendChild(thisPrice);
	let thisAmount = document.createElement('SPAN');
	for (const [key, value] of Object.entries(prices)) {
		thisRow.setAttribute(key, value);
	}
	let thisQuantity_container = document.createElement('DIV');
	thisQuantity_container.className = 'item-column item-quantity-container flex-container';
	let thisQuantity_plus = document.createElement('SPAN');
	thisQuantity_plus.innerText = '+';
	thisQuantity_plus.className = 'item-quantity-plus';
	thisQuantity_plus.onclick = function(event){
		let newQuantity = parseInt(event.target.parentNode.querySelector('.item-quantity').innerText) + 1;
		event.target.parentNode.querySelector('.item-quantity').innerText = newQuantity;
		thisAmount.innerText = parseFloat(prices[currency], 10) * newQuantity;
		updateRowToCookie();
	}
	let thisQuantity_minus = document.createElement('SPAN');
	thisQuantity_minus.innerText = '-';
	thisQuantity_minus.className = 'item-quantity-minus';
	thisQuantity_minus.onclick = function(event){
		let newQuantity = parseInt(event.target.parentNode.querySelector('.item-quantity').innerText) - 1;
		if(newQuantity != 0){
			event.target.parentNode.querySelector('.item-quantity').innerText = newQuantity; 
			thisAmount.innerText = parseFloat(prices[currency], 10) * newQuantity;
			updateRowToCookie();
		}
	}
	let thisQuantity = document.createElement('DIV');
	thisQuantity.className = 'item-quantity';
	thisQuantity.innerText = quantity;
	thisQuantity_container.appendChild(thisQuantity_minus);
	thisQuantity_container.appendChild(thisQuantity);
	thisQuantity_container.appendChild(thisQuantity_plus);
	let thisAmount_container = document.createElement('DIV');
	thisAmount_container.className = 'item-column item-amount-container';
	
	thisAmount.className = 'item-amount';
	thisAmount.innerText = quantity * prices[currency];
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
			this_obj.subType = el.getAttribute('subType') ? el.getAttribute('subType') : '';
			let prices = {};
			acceptedCurrencies.forEach(function(c, i){
				prices[c] = el.getAttribute(c);
			});
			this_obj.prices = prices;
			this_obj.quantity = el.querySelector('.item-quantity').innerText;
			json.push(this_obj);
		});
	}
	createCookie( 'serving-library-shop-cart', JSON.stringify(json), '' );
}
