<section id="subscribe" class="visible"><?
    $subscribe = $_POST['subscribe'];
    $unsubscribe = $_POST['unsubscribe'];
    $address = $_POST['address'];
    if (!$subscribe && !$unsubscribe) {	
	?><form enctype='multipart/form-data' action='subscribe' method='post'>
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

<p>*and/or* FOLLOW us, in the usual places . . . 
<a href="https://www.instagram.com/servinglibrary/" target="new"><img src='media/png/instagram.png' style='width:5%; 
padding:2px;'></a>
<a href="https://twitter.com/serving_library" target="new"><img src='media/png/twitter.png' style='width:5%; 
padding:2px;'></a>
