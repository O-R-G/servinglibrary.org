<?
    /*
        mailgun php sdk
        https://github.com/mailgun/mailgun-php
    */

    require 'vendor/autoload.php';
    use Mailgun\Mailgun;

    function mailgun($domain, $msg) {
        $mailgun_client_id = getenv('MAILGUN_CLIENT_ID');
        try {
            $mg = Mailgun::create($mailgun_client_id);
            $mg->messages()->send($domain, [
		'from'	=> $msg['from'],
		'to'	=> $msg['to'],
		'bcc'	=> $msg['bcc'],
    	        'subject' => $msg['subject'],
		'text'	=> $msg['text']
            ]);
        } catch (Exception $e) {
        }
    }
?>
