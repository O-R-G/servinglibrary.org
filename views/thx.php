<?
    /*
        a view to send paypal confirmation email
    */

    // need actual email get in query string
    // think it uses get or maybe post in teiger

    if ($valid_key) {
        if(!empty($email)) {
            $domain = 'mail.o-r-g.com';
            $msg['from'] = 'store@o-r-g.com';
            $msg['to'] = $email;
            $msg['bcc'] = $debug_email;
            $msg['subject'] = 'O-R-G small software purchase';
            $msg['text'] = "*\n\nThank you very much. Here is a link to your downloaded software:\n";
            $msg['text'] .= "\n" . $host . $download;
            $msg['text'] .= "\n\nEnjoy, tell your friends, and so forth.\n\n*\n\nhttps://www.o-r-g.com";
            mailgun($domain, $msg);
    }
?>
