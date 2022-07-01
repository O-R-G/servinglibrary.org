		</div>
        <div id="Menu" class="TSLContainer body"><?
            // menu
            echo ($uri[1] != 'introduction') ? "<a href='/introduction'>Introduction</a> — " : "Introduction — ";
            echo ($uri[1] != 'journal') ? "<a href='/journal'>Journal</a> / " : "Journal / ";
            echo ($uri[1] != 'collection') ? "<a href='/collection/about'>Collection</a> / " : "Collection / ";
            echo ($uri[1] != 'programs') ? "<a href='/programs'>Programs</a> / " : "Programs / ";
            echo ($uri[1] != 'shop') ? "<a href='/shop'>Shop</a>" : "Shop : <a href='/shop/subscriptions'>Subscriptions</a> : <a href='/shop/edition'>Edition</a>";
        ?></div>
    </body>
</html><?
$db->close();
?>
