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

function togglePaypalButton(buttonAreaId, currency, itemName, type = ''){
	let sButtonArea = document.getElementById(buttonAreaId);
	if(!sButtonArea) {
		console.log('wrong button area id: ' + buttonAreaId);
		return;
	}
	const expanded = sButtonArea.dataset.expanded == '1';
	if( expanded ){
		sButtonArea.dataset.expanded = '0';
		// removePaypalButtons();
	} else {
		sButtonArea.dataset.expanded = '1';
	}
	
}
function toggleCheckoutButton(buttonAreaId, currency, itemName, type = ''){
	const sCart_container = document.getElementById('cart-container');
	let sButtonArea = document.getElementById(buttonAreaId);
	if( sButtonArea.classList.contains('viewing-paypal') ){
		sButtonArea.classList.remove('viewing-paypal');
		sCart_container.dataset.locked = "0";
		removePaypalButtons();
	} else {
		let sViewing_paypal = document.querySelector('.button-area.viewing-paypal');
		if(sViewing_paypal)
			sViewing_paypal.classList.remove('viewing-paypal');
		sButtonArea.classList.add('viewing-paypal');
		sCart_container.dataset.locked = "1";
		let hasButton = sButtonArea.querySelector('.paypal-buttons') !== null;
		if(!hasButton){
			var thisPaypalButtonContainer = sButtonArea.querySelector('.paypal-button-container');
			var thisPrice = thisPaypalButtonContainer.getAttribute('price');
			if(thisPaypalButtonContainer.id == 'paypal-button-container-cart')
				createPaypalButton();
		}
	}
}
function createPaypalButton(){
	console.log('createPaypalButton');
	let sItem_row = document.getElementsByClassName('item-row');

	if(sItem_row.length > 0)
	{
		let currencyUppercase = currency.toUpperCase();
		let buttonContainerId = 'paypal-button-container-cart';
		let items = [];
		let type = '';
		let baseAmount = 0.0;
		var totalShippingFee = 0;
		var hasSubscription = false;
		const rederence_id = '781012';
		[].forEach.call(sItem_row, function(el, i){
			let thisItemName = el.querySelector('.item-name').innerText;
			let thisItemPrice = el.querySelector('.item-price').innerText;
			let thisItemQuantity = el.querySelector('.item-quantity').innerText;
			var thisType = el.getAttribute('type');
			if(thisType == '')
				thisType = 'issue';
			let thisSubType = el.getAttribute('subtype');
			if(thisSubType)
				thisType = thisType + '-' + thisSubType;
			let thisItem = {
				name: thisItemName,
				unit_amount: {
					currency_code: currencyUppercase,
					value: thisItemPrice
				},
				quantity: thisItemQuantity,
				type: thisType
			};
			items.push(thisItem);

			if(el.getAttribute('type') == 'subscription')
				hasSubscription = true;
			baseAmount += parseFloat(thisItemPrice, 10) * parseInt(thisItemQuantity);
		});
		// Shipping fees/options are never computed here — create_order.php and
		// patch_order.php both derive them server-side from $shipping_config
		// (see api/api_config.php), so the browser only ever sends the cart.
		paypal.Buttons({
	        createOrder: function(data, actions) {
				// console.log('createOrder');
				// console.log(items);
	        	return fetch('/api/create_order.php', {
	        		method: 'POST',
	        		headers: {
	        			'Content-Type': 'application/json'
	        		},
	        		body: JSON.stringify({
	        			currency: currencyUppercase,
	        			items: items,
	        			hasSubscription: hasSubscription
	        		})
	        	})
	        	.then(response => {
	        		if (!response.ok) {
	        			return response.json().then(err => {
	        				throw new Error(err.error || 'Failed to create order');
	        			});
	        		}
	        		return response.json();
	        	})
	        	.then(orderData => orderData.id)
	        	.catch(error => {
	        		console.error('Order creation error:', error);
	        		throw error;
	        	});
	        },
			onShippingAddressChange: function (data, actions) {
				// console.log('onShippingAddressChange');
				// console.log(data.shippingAddress.countryCode);
				const contryCodeUppercase = data.shippingAddress.countryCode.toUpperCase();
				return fetch('/api/patch_order.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						orderId: data.orderID,
						currency: currencyUppercase,
						items: items,
						countryCode: contryCodeUppercase,
						baseAmount: baseAmount,
						reference_id: rederence_id
					})
				})
				.then(response => {
					if (!response.ok) {
						return response.json().then(err => {
							throw new Error(err.error || 'Failed to update order');
						});
					}
					return response.json();
				})
				.catch((error, details) => {
					console.log(error);
					// console.error('Shipping option change error:', error);
					// throw error;
				});
			},
	        onShippingOptionsChange: function (data, actions) {
				// console.log(data);
				return fetch('/api/patch_order.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						orderId: data.orderID,
						currency: currencyUppercase,
						items: items,
						shippingOptionId: data.selectedShippingOption.id,
						baseAmount: baseAmount,
						reference_id: rederence_id
					})
				})
				.then(response => {
					if (!response.ok) {
						return response.json().then(err => {
							throw new Error(err.error || 'Failed to update order');
						});
					}
					return response.json();
				})
				.catch((error, details) => {
					console.log(error);
					// console.error('Shipping option change error:', error);
					// throw error;
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

function addToCartByClick(event, currency, quantityToAdd = 1){
	console.log('addToCartByClick', currency);
	let thisElement = event.target;
    thisElement.parentNode.parentNode.classList.remove('viewing-paypal');
	
	if (cart_symbol = document.getElementById('cart-symbol'))
        document.body.classList.add('viewing-cart-symbol');
	let price_all = {
		'usd': thisElement.getAttribute('usd'),
		'eur': thisElement.getAttribute('eur'),
		'gbp': thisElement.getAttribute('gbp')
	};
	// check if this item exists in the cart
	let rowId = 'item-row-'+thisElement.getAttribute('slug');
	const sCart_container = document.getElementById('cart-container');
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
		printToCart(rowId, itemName, type, price_all, 0, currency, subType);
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

function addToCartFromJson(obj, currency){
	printToCart(obj.id, obj.itemName, obj.type, obj.prices, obj.quantity, currency, obj.subType);
}

function printToCart(rowId, itemName, type, prices, quantity, currency, subType = false){
	console.log('printToCart', currency);
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
	if(document.body.classList.contains('viewing-cart')) {
		let sButtonArea = document.querySelector('.button-area.viewing-paypal');
		if(sButtonArea)
			sButtonArea.classList.remove('viewing-paypal');
		let cart_container = document.getElementById('cart-container');
		if(cart_container)
			cart_container.dataset.locked = "0";
		removePaypalButtons();
	}
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

function removePaypalButtons(){
	const buttons = document.querySelectorAll('.paypal-buttons');
	// console.log(buttons);
	buttons.forEach(button => {
		button.parentNode.removeChild(button);
	});
}