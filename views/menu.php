<style>
    .menu-pseudo-item,
    .menu-item.active
    {
        display: none;
    }
    .menu-item.active + .menu-pseudo-item
    {
        display: inline;
    }
    .menu-item-divider:last-child
    {
        display: none;
    }
</style>
<div id="Menu" class="TSLContainer body"><?
    // menu
    $menu_items = $oo->children(0);
    $menu_html = '';
    foreach($menu_items as $menu_item)
    {
        if(substr($menu_item['name1'], 0, 1) !== '.' && substr($menu_item['name1'], 0, 1) !== '_')
        {
            $menu_itemClass = ( $uri[1] == $menu_item['url'] && count($uri) == 2 ) ? 'menu-item active' : 'menu-item';
            $menu_html .= '<a class="'.$menu_itemClass.'" href="/'.$menu_item['url'].'">'.$menu_item['name1'].'</a><span class="menu-pseudo-item" href="/'.$menu_item['url'].'">'.$menu_item['name1'].'</span>';
            if($menu_item['url'] == 'introduction')
                $menu_html .= '<span class="menu-item-divider"> — </span>';
            else
                $menu_html .= '<span class="menu-item-divider"> / </span>';
        }
    }
    echo $menu_html;
?></div>