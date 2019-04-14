<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">

    <div class="main-menu-header">
        <input type="text" placeholder="Search" class="menu-search form-control round"/>
    </div>

    <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            <?php
                function getHtmlMenu($menus) {
                    $html = "";
                    foreach ($menus as $key => $menu) {
                        if($menu->count_permission > 0) {
                            $html .= "<li class='nav-item'>";
                                $html .= "<a href='{$menu->url}'>";
                                    $html .= "<i class='icon-{$menu->icon_class}'></i>";
                                    $html .= "<span class='menu-title'>{$menu->name}</span>";
                                $html .= "</a>";
                                if(count($menu->children) > 0) {
                                    $html .= "<ul class='menu-content'>";
                                        $html .= getHtmlMenu($menu->children);
                                    $html .= "</ul>";
                                }
                            $html .= "<li>";
                        }
                    }
                    return $html;
                }

                echo getHtmlMenu($menus);
            ?>
        </ul>
    </div>
</div>