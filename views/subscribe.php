<section id="subscribe" class="visible"><?
    $subscribe = isset($_POST['subscribe']) ? $_POST['subscribe'] : false;
    $unsubscribe = isset($_POST['unsubscribe']) ? $_POST['unsubscribe'] : false;
    $address = isset($_POST['address']) ? $_POST['address'] : false;
    if (!$subscribe && !$unsubscribe) {	
	?><form enctype='multipart/form-data' action='subscribe' 
method='post'>
            <textarea name='address' cols='30' rows='2'></textarea><br />    
            <input name='subscribe' type='submit' value='Subscribe'>
            <input name='unsubscribe' type='submit' value='Unsubscribe'>
        </form><?
    } else if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
        $to = "serving-library-request@servinglibrary.org";
        $subject = ($subscribe) ? "subscribe" : "unsubscribe";
        $body = "";
        $headers = "From: " . $address;
        mail($to,$subject,$body,$headers);
        ?><p>Thanks.</p><?
    } else {
        ?><p>Please <a href="">enter a valid email address.</a></p><?
    }
?></section>
