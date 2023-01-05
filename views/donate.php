<?
$isSandbox = isset($_GET['isSandbox']);
?>
<script>
    var isSandbox = <?= json_encode($isSandbox); ?>;
</script>
<script src="/static/js/shop.js"></script>
<script src="/static/js/cookie.js"></script><?

    /*
        a view for donations
    */

    // require_once('static/php/paypal.php');

    ?><!-- <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script> -->
    <div id="donate-buy-section" class="buy-section">
        <? echo $item['body']; ?>
    </div>