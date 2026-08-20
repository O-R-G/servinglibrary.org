<?
    /*
        paypal lookup product and print buttons
        reqd for views/buy views/shop views/donate views/subscribe
    */ 

    // isset($_GET['currency']) ? $_GET['currency'] : 'usd';
    function getProductInfo($currency, $child){
        global $acceptedCurrenciesSymbols;

        $priceField = trim($child['notes']);
        $output = array();
        $paypal_item_pattern = '/\[paypal\-item\]\(((?:\[.*?\]\(.*?\))*)\)/';
        $currencyLowercase = strtolower($currency);
        preg_match_all($paypal_item_pattern, trim($priceField), $items_temp);
    	if(!empty($items_temp) && !empty($items_temp[1])){
            $tag_pattern = '/\[(.*?)\]\((.*?)\)/';
            foreach($items_temp[1] as $info_string)
            {
                $item = array();
                preg_match_all($tag_pattern, $info_string, $info_arr);
                if( !empty($info_arr) && !empty($info_arr[0]))
                {
                    $price_arr = array();
                    foreach($info_arr[0] as $key => $info)
                    {
                        $item[$info_arr[1][$key]] = $info_arr[2][$key];
                    }

                    $item['price'] = $item[$currencyLowercase];
                    if($item['price'] == 'sold out')
                        $item['type'] = 'sold out';
                    if(!isset($item['name']))
                        $item['name'] = $child['name1'];
                }
                $output[] = $item;
            }
    	} else {
    		if(strpos(trim($priceField), '[donation]') !== false) {
    			$item['type'] = 'donation';
    			$item['price'] = 'donation';
                $output[] = $item;
    		} 
    	}
    	return $output;
    }

    function printPayPalButtons($currency, $productsInfo){
    	global $acceptedCurrenciesSymbols;
        // var_dump($productInfo);
        $currencyLowercase = strtolower($currency);
        $output = '<div class="buy-section-container">'; 
        foreach($productsInfo as $productInfo)
        {
            $itemName = $productInfo['name'];
            $key = slug($itemName);
            // $output = '';
            $price = $productInfo['price'];
            $usd = $productInfo['usd'];
            $eur = $productInfo['eur'];
            $gbp = $productInfo['gbp'];
            $type = $productInfo['type'];

            $prefix = $key . '-' . $currencyLowercase;
            $id = 'button-area_' . $prefix;
            
            if( is_numeric($price) ) {
                $output .= '<section id="buy' . $key . '" class="buy-section">';
                $output .= '<div id="' . $id . '" class="button-area button-area-' . $currencyLowercase . '">';
                $output .= '<div id="paypal-cart-button-container-' . $prefix . '" price="'. $price . '" usd="'. $usd . '" eur="'. $eur . '" gbp="'. $gbp . '" class="payment-option paypal-button-container paypal-cart-button-container"><button id="paypal-cart-button-' . $prefix . '" class="button paypal-cart-button" price="'. $price . '" usd="'. $usd . '" eur="'. $eur . '" gbp="'. $gbp . '" itemName="'.$itemName.'" slug="'.$key.'" type="'.$type.'" onclick="addToCartByClick(event, \''.$currencyLowercase.'\')">ADD TO CART</button></div>';
                $output .= '<div id="buy-button-container' . $prefix . '" class="buy-button-container">';
                $output .= '<button id="cost' . $prefix . '" class="button" onclick="togglePaypalButton(\''.$id.'\')">' . $acceptedCurrenciesSymbols[$currencyLowercase] . $price . '</button>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</section>';
            } else if($type == 'donation') {
                $output .= '<div id="donate-buy-section" class="buy-section"><div id="paypal-donate-button-container"></div><button id="donate-btn" class="button">DONATE</button></div>';
            } else if($price == 'sold out') {
                $output .= '<section id="buy' . $key . '" class="buy-section">';
                $output .= '<div id="' . $id . '" class="button-area"><div class="pseudo-button sold-out">SOLD OUT</div></div>';
                $output .= '</section>';
            }
        }        
        $output .= '</div>';
       	return $output;
    }

    $acceptedCurrencies = array('usd','gbp','eur');
    $acceptedCurrenciesSymbols = array(
    	'usd' => '$',
    	'gbp' => '£',
    	'eur' => '€'
    );
    $cookie_name = 'serving-library-shop-currency';
    if(isset($_GET['currency']) && in_array(strtolower($_GET['currency']), $acceptedCurrencies))
    {
        $currency = strtolower($_GET['currency']);
        setcookie($cookie_name, $currency);
    }
    else if(isset($_COOKIE[$cookie_name]))
    {
        $currency = strtolower($_COOKIE[$cookie_name]);
    }
    else
        $currency = 'usd';
?>
