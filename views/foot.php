		</div>
        <div id="Menu" class="TSLContainer body"><?
            // menu
            echo ($uu->url != 'introduction') ? "<a href='/introduction'>Introduction</a> — " : "Introduction — ";
            echo ($uu->url != 'publication') ? "<a href='/publication'>Publication</a> / " : "Publication / ";
            echo ($uu->url != 'programs') ? "<a href='/programs'>Programs</a> / " : "Programs / ";
            echo ($uu->url != 'collection-new') ? "<a href='/collection-new'>Collection</a>" : "Collection";
        ?>
</div>

	</body>
</html><?
$db->close();
?>
