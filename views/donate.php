<?

?>
<div class="mainContainer">
    <div class="wordsContainer body">
        <?= $item['body']; ?>
        <br>
        <div id="donate-section" class="buy-section">
            <button id="pseudo-donate-button" class="button">Donate</button>
            <div id="donate-button-container">
                <div id="donate-button"></div>
                <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8" data-uid-auto="90737bb25e_mty6mjq6mzq"></script>
                <script>
                PayPal.Donation.Button({
                env:'production',
                hosted_button_id:'LQBSV7PPT5REG',
                image: {
                src:'https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif',
                alt:'Donate with PayPal button',
                title:'PayPal - The safer, easier way to pay online!',
                }
                }).render('#donate-button');
                </script>
            </div>
        </div>
        <br><br><a href="/">Go back</a>
    <div>
</div>

<style>
    #donate-section
    {
        width: 200px;
        position: relative;
    }
    #donate-button-container
    {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
</style>