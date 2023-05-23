<?php

function agregar_paginas_menu_primario() {
    $menu_name = 'menu-primario';
    $existing_menu = wp_get_nav_menu_object($menu_name);

    if($existing_menu) {
        $menu_id = $existing_menu->term_id;

        // Elimina todos los elementos existentes en el menú primario
        $menu_items = wp_get_nav_menu_items($menu_id);
        if (!empty($menu_items)) {
            foreach ($menu_items as $menu_item) {
                wp_delete_post($menu_item->ID);
            }
        }

        // Añade las páginas al menú
        $pages = array('TIENDA', 'RECETAS', 'TRUCOS DE COCINA');
        
        foreach ($pages as $page_title) {
            $page = get_page_by_title($page_title);
            
            if ($page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $page->post_title,
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
        }
    }
}
