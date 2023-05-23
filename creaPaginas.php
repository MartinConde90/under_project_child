<?php
$pages = ['TIENDA','RECETAS','TRUCOS DE COCINA','NUTRICIÓN','CATÁLOGO','SOSTENIBILIDAD'];

// Crea nuevas páginas
foreach ($pages as $page_slug){
    $existing_page = get_page_by_title($page_slug); // Comprobamos si la página existe por su título

    if (!$existing_page) {
        $new_page = array(
            'post_title'    => $page_slug, 
            'post_content'  => 'Contenido de la página', 
            'post_status'   => 'publish',
            'post_type'     => 'page'
        );
        // Inserta la nueva página en la base de datos
        $new_page_id = wp_insert_post($new_page);
    }
}
?>

