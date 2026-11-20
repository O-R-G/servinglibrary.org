<?php
    require_once(__DIR__ . '/../static/php/paypal.php');
    /*
        a view to send paypal confirmation email
    */
    function getBBCList($shippingMethod, $currency) {
        global $debug_email_us;
        global $debug_email_eu;
        global $acceptedCurrencies;

        if($shippingMethod !== 'domestic') {
            return $debug_email_us . ', ' . $debug_email_eu;
        } elseif (!$currency || !in_array($currency, $acceptedCurrencies)) {
            return $debug_email_us . ', ' . $debug_email_eu;
        } elseif ($currency === 'usd') {
            return $debug_email_us;
        } else {
            return $debug_email_eu;
        }
    }
    // need actual email get in query string
    // think it uses get or maybe post in teiger
    $items = $_GET['items'];
    $currency = isset($_GET['currency']) && in_array(strtolower($_GET['currency']), $acceptedCurrencies) ? strtolower($_GET['currency']) : '';
	require_once('static/php/mail.php');

    $shippingMethod = $_GET['shippingMethod'] ?? '';

    // $debug_email_us = 'info@servinglibrary.org';
    // $debug_email_eu = 'stuart@servinglibrary.org, francesca@servinglibrary.org';
    $debug_email_us = 'wei@o-r-g.com';
    $debug_email_eu = 'drxk41012@gmail.com';

    $bbc_list = getBBCList($shippingMethod, $currency);

	$email = isset($_GET['email']) ? $_GET['email'] : $debug_email_us;
    
    if(!empty($email)) {
        $domain = 'mail.servinglibrary.org';
        $msg['from'] = 'shop@servinglibrary.org';
        $msg['to'] = $email;
        $msg['bcc'] = $bbc_list;
        $msg['subject'] = 'The Serving Library says thanks for your order';
        $msg['text'] = "*\n\nThank you for purchasing the following item(s):\n";
        foreach($items as $it)
            $msg['text'] .= "\n" . $it;

        $msg['text'] .= "\n\nWe appreciate the order -- this really keeps our publishing engine running. Shipping takes approximately five days from ";
        $msg['text'] .= $currency == 'usd' ? 'US.' : 'EU.';
        $msg['text'] .= "\n\nAny questions or concerns, please email info@servinglibrary.org.";
        $msg['text'] .= "\n\n*\n\nEnjoy, tell your friends, and so forth.\n\n*\n\nhttps://www.servinglibrary.org";

        mailgun($domain, $msg);
	}

?>
