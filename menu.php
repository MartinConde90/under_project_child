<nav>
<div class="menu">
    <ul class="menu-list">
        <?php
        $pages = array('TIENDA','RECETAS','TRUCOS DE COCINA','NUTRICIÓN','CATÁLOGO','SOSTENIBILIDAD','PLANTAS DECORATIVAS');

        foreach ($pages as $page_title) {
            $page = get_page_by_title($page_title);

            if ($page) {
                $args = array(
                    'title_li' => '',
                    'include' => $page->ID,
                    'link_before' => '<span>', // Agrega un elemento span al enlace
                    'link_after' => '</span>',
                    'depth' => 1, // Limita la profundidad a 1 nivel
                    'echo' => false, // Devuelve el resultado en lugar de imprimirlo
                );

                $menu_items = wp_list_pages($args);

                // Verifica si el enlace actual es el de la página activa
                if (is_page($page->ID)) {
                    // Agrega la clase CSS "current-menu-item" al enlace
                    $menu_items = str_replace('<span>', '<span class="current-menu-item">', $menu_items);
                }

                echo $menu_items;
            }
        }
        ?>
    </ul>
</div>
</nav>
