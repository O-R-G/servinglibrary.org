<div id="cart-symbol" class="cart-button-container" onclick="toggleCart()">
	<button id="" class="button" onclick="">
		CART (<span id="item-count">0</span>)
	</button>
</div>
<div id="cart-container" class="body">
	<div id="btn-close-cart" onClick="toggleCart()">X</div>
	<div id="" class="item-row-default">
		<div class="item-column item-name">Item</div><div class="item-column item-price">Price</div><div class="item-column item-quantity-container flex-container"><span class="item-quantity">Quantity</span></div><div class="item-column item-amount"></div><div class="item-column item-remove"></div>
	</div>
	<section id="buy-section-cart" class="buy-section">
		<div id="button-area-cart" class="button-area">
			<div id="paypal-button-container-cart" class="payment-option paypal-button-container"></div>
			<button id="btn-checkout-cart" class="button" onclick="expandPaypal('button-area-cart', '', '', '')">Checkout</button>
		</div>
	</section>
</div>

<script>
	function printToCart(rowId, itemName, type, price, quantity){
		console.log('printToCart()');
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
		let thisAmount_symbol = document.createElement('SPAN');
		thisAmount_symbol.innerHTML = acceptedCurrenciesSymbols[currency];
		thisAmount_container.appendChild(thisAmount_symbol);
		thisAmount_container.appendChild(thisAmount);
		let thisRemove = document.createElement('DIV');
		thisRemove.className = 'item-column item-remove';
		thisRemove.innerText = 'remove';
		thisRemove.onclick=function(){
			thisRow.parentNode.removeChild(thisRow);
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

	function addToCartByClick(event, quantityToAdd = 1){
		console.log('addToCartByClick()');
		let thisElement = event.target;
		let sCart_container = document.getElementById('cart-container');

		if (cart_symbol = document.getElementById('cart-symbol'))
            cart_symbol.classList.add('viewing-cart-symbol');

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
		console.log('addToCartFromJson()');
		printToCart(obj.id, obj.itemName, obj.type, obj.price, obj.quantity);
	}

	var cart_cookie = readCookie('cart');
	// console.log(cart_cookie);
	if(cart_cookie){
		let temp = 0;
		cart_cookie = JSON.parse(cart_cookie);
		cart_cookie.forEach(function(el, i){
			console.log(el);
			addToCartFromJson(el);
			temp += parseInt(el.quantity);
		});
		document.getElementById('item-count').innerText = temp;
	}

	function toggleCart(){
		document.body.classList.toggle('viewing-cart');
	}

	function updateRowToCookie(){
		let sRows = document.getElementsByClassName('item-row');
		let json = [];
		if(sRows.length > 0)
		{
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
</script>
