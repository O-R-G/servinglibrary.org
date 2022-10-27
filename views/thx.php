<?
    /*
        a view to send paypal confirmation email
    */

    // need actual email get in query string
    // think it uses get or maybe post in teiger
    $items = $_GET['items'];
	require_once('static/php/mail.php');
	// $debug_email = 'david@servinglibrary.org';
    $debug_email = 'info@servinglibrary.org';

	$email = isset($_GET['email']) ? $_GET['email'] : $debug_email;
    if(!empty($email)) {
        $domain = 'mail.servinglibrary.org';
        $msg['from'] = 'shop@servinglibrary.org';
        $msg['to'] = $email;
        $msg['bcc'] = $debug_email;
        $msg['subject'] = 'The Serving Library says thanks for your order';
        $msg['text'] = "*\n\nThank you for purchasing the following item(s):\n";
        foreach($items as $it)
            $msg['text'] .= "\n" . $it;

        $msg['text'] .= "\n\nWe appreciate the order -- this really keeps our publishing engine running. Shipping takes approximately five days from ";
        $msg['text'] .= $_GET['currency'] == 'USD' ? 'US.' : 'EU.';
        $msg['text'] .= "\n\nAny questions or concerns, please email info@servinglibrary.org.";
        $msg['text'] .= "\n\n*\n\nEnjoy, tell your friends, and so forth.\n\n*\n\nhttps://www.servinglibrary.org";

        mailgun($domain, $msg);
	}

?>
