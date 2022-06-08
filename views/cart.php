<div id="cart-symbol" class="cart-button-container" onclick="toggleCart()">
	<button id="" class="button" onclick="">
		CART (<span id="item-count">0</span>)
	</button>
</div>
<div id="cart-container" class="body">
	<div id="btn-close-cart" onClick="toggleCart()"><img src='/media/svg/x-3-k.svg'></div>
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
    // cart cookie
    var cart_cookie = readCookie('cart');
    console.log(cart_cookie);
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
</script>
