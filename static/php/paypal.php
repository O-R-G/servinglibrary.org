<?
    /*
        paypal lookup product and print buttons
        reqd for views/buy views/shop views/donate views/subscribe
    */ 

    $acceptedCurrencies = array('usd','gbp','eur');
    $currency = isset($_GET['currency']) ? $_GET['currency'] : 'usd';
    if(!in_array($currency, $acceptedCurrencies)) $currency = 'usd';
    $acceptedCurrenciesSymbols = array(
    	'usd' => '$',
    	'gbp' => '£',
    	'eur' => '€'
    );

    function getProductInfo($currency, $child){
        $priceField = trim($child['notes']);
        $output = array();
        $paypal_item_pattern = '/\[paypal\-item\]\(((?:\[.*?\]\(.*?\))*)\)/';
    	// $price_pattern = '/\['.$currency.'\]\((.*?)\)/';
    	// $type_pattern = '/\[type\]\((.*?)\)/';
    	// preg_match($price_pattern, trim($priceField), $temp);
        preg_match_all($paypal_item_pattern, trim($priceField), $items_temp);
        // foreach($temp[1] as $t)
        // {
        //     var_dump($t);
        //     echo '<br>';
        // }
        // var_dump($temp);
        // die();
    	if(!empty($items_temp[1])){
            // $price_pattern = '/\['.$currency.'\]\((.*?)\)/';
            $tag_pattern = '/\[(.*?)\]\((.*?)\)/';
            
            foreach($items_temp[1] as $info_string)
            {
                $item = array();
                // preg_match($price_pattern, trim($priceField), $price);
                // if(!empty($price[1]))
                //     $product['price'] = 'empty';
                // else
                //     $product['price'] = 'empty';

                // $output[] = $product;
                preg_match_all($tag_pattern, $info_string, $info_arr);
                if( !empty($info_arr[0]) )
                {
                    foreach($info_arr[0] as $key => $info)
                    {
                        $item[$info_arr[1][$key]] = $info_arr[2][$key];
                    }
                    $item['price'] = $item[$currency];
                    if($item['price'] == 'sold out')
                        $item['type'] = 'sold out';
                    if(!isset($item['name']))
                        $item['name'] = $child['name1'];
                }
                $output[] = $item;
            }
    		// if(!empty($temp[1]) && strtolower($temp[1]) !== 'sold out'){
    		// 	preg_match($type_pattern, trim($priceField), $temp_type);
    		// 	if(!empty($temp_type))
    		// 		$output['type'] = $temp_type[1];
    		// 	else
    		// 		$output['type'] = '';
    		// 	$output['price'] = $temp[1];
    		// } else {
    		// 	$output['type'] = 'sold out';
    		// 	$output['price'] = 'sold out';
    		// }
    	} else {
    		if(strpos(trim($priceField), '[donation]') !== false) {
    			$item['type'] = 'donation';
    			$item['price'] = 'donation';
                $output[] = $item;
    		} 
      //       else {
    		// 	$output['type'] = 'sold out';
    		// 	$output['price'] = 'sold out';
    		// }
    	}
    	return $output;
    }

    function printPayPalButtons($currency, $productInfo){
    	global $acceptedCurrenciesSymbols;
        // var_dump($productInfo);
        $itemName = $productInfo['name'];
    	$key = slug($itemName);
    	$output = '';
    	$price = $productInfo['price'];
    	$type = $productInfo['type'];
    	if( is_numeric($price) ) {
       		$output  = '<section id="buy' . $key . '" class="buy-section">';
    		$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area button-area-' . $currency . '">';
            // $output .= '<div id="paypal-button-container-' . $key . '-' . $currency . '" price="'. $price . '" class="payment-option paypal-button-container"></div>';
            $output .= '<div id="paypal-cart-button-container-' . $key . '-' . $currency . '" price="'. $price . '" class="payment-option paypal-button-container paypal-cart-button-container"><button id="paypal-cart-button' . $key . '-' . $currency . '" class="button paypal-cart-button" price="'. $price . '" itemName="'.$itemName.'" slug="'.$key.'" type="'.$type.'" onclick="addToCartByClick(event)">ADD TO CART</button></div>';
    		$output .= '<div id="buy-button-container' . $key . '-' . $currency . '" class="buy-button-container">';
    		$output .= '<button id="cost' . $key . '-' . $currency . '" class="button" onclick="expandPaypal(\'button-area' . $key . '-' . $currency . '\', \'' . $currency . '\', \''.$itemName.'\', \''.$type.'\')">' . $acceptedCurrenciesSymbols[$currency] . $price . '</button>';
    		$output .= '</div>';
    		$output .= '</div>';
    	   	$output .= '</section>';
       	} else if($type == 'donation') {
       		$output = '<div id="donate-buy-section" class="buy-section"><div id="paypal-donate-button-container"></div><button id="donate-btn" class="button">DONATE</button></div>';
       	} else if($price == 'sold out') {
       		$output = '<section id="buy' . $key . '" class="buy-section">';
    		$output .= '<div id="button-area' . $key . '-' . $currency . '" class="button-area"><div class="pseudo-button sold-out">SOLD OUT</div></div>';
    		$output .= '</section>';
       	}
       	return $output;
    }
?>