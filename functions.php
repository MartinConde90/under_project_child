<?php
//REEMPLAZAR EL JQUERY DE WOOCOMERCE POR EL QUE ESTAMOS USANDO, EN ESTE CASO 3.6.0
function replace_woocommerce_jquery() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), '3.6.0', true);
}
add_action('wp_enqueue_scripts', 'replace_woocommerce_jquery', 99);

// Configurar la página "TIENDA" como la página principal de WooCommerce
function custom_woocommerce_shop_page_id() {
    $shop_page = get_page_by_title( 'TIENDA' );

    if ( $shop_page ) {
        update_option( 'woocommerce_shop_page_id', $shop_page->ID );
    }
}
add_action( 'init', 'custom_woocommerce_shop_page_id' );

// Configurar la página "CARRITO" como la página Cart Page
function custom_woocommerce_cart_page_id() {
    $cart_page = get_page_by_title( 'CARRITO' );

    if ( $cart_page ) {
        update_option( 'woocommerce_cart_page_id', $cart_page->ID );
    }
}
add_action( 'init', 'custom_woocommerce_cart_page_id' );

// Configurar la página "CHECKOUT" como la página Cart Page
function custom_woocommerce_checkout_page_id() {
    $checkout_page = get_page_by_title( 'CHECKOUT' );

    if ( $checkout_page ) {
        update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
    }
}
add_action( 'init', 'custom_woocommerce_checkout_page_id' );

//HABILITAR CONTRA REEMBOLSO
function habilitar_contra_reembolso( $gateways ) {
    $gateways[] = 'WC_Gateway_COD'; // WooCommerce contra reembolso

    return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'habilitar_contra_reembolso' );




//CREADOR DE PÁGINAS
function creapaginas(){
    $pages = ['TIENDA','RECETAS','TRUCOS DE COCINA','NUTRICIÓN','CATÁLOGO','SOSTENIBILIDAD','CARRITO','CHECKOUT'];

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
            wp_insert_post($new_page);
        }
    }
}
creapaginas();


//CREAMOS LA PÁGINA PRINCIPAL CON Frontpage.php
function pagina_principal() {
    $page_title = 'MAIN'; // Título de la página
    $page_slug = 'main'; // Slug de la página (URL amigable)

    $existing_page = get_page_by_path($page_slug);

    if (!$existing_page) {
        // Crea la página si no existe
        $page_id = wp_insert_post(array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ));

        if ($page_id) {
            // Asigna la plantilla al nuevo ID de página
            update_post_meta($page_id, '_wp_page_template', 'Frontpage.php');
        }
    }
}

add_action('after_switch_theme', 'pagina_principal');

function establecer_pagina_principal() {
    $pagina_principal = 1228; // Id de la página
    update_option('page_on_front', $pagina_principal);
    update_option('show_on_front', 'page');
}
add_action('after_setup_theme', 'establecer_pagina_principal');



//Registrar un menú de navegación primario
function registrar_menu_primario() {
    register_nav_menu('menu-primario', __('Menú Primario'));
}
add_action('after_setup_theme', 'registrar_menu_primario');


// Agregar el menú al tema
function agregar_menu_primario() {
    wp_nav_menu(array(
        'theme_location' => 'menu-primario',
        'menu_class' => 'menu',
        'container' => false,
    ));
}

//--------------------------
/**
 * Agregar campo Opciones Globales
 */

if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Opciones globales',
        'menu_title'    => 'Opciones globales',
        'menu_slug'     => 'opciones-globales',
        'capability'    => 'edit_posts',
        'parent_slug'   =>  '',
        'position'      => false,
        'icon_url'      => false,
        'redirect'      => false
    ));
}
//Agregar campos imagen y texto para  Opciones Globales
if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'title' => 'Banner Superior',
        'fields' => array(
            array(
                'key' => 'field_imagen_banner',
                'label' => 'Imagen del banner',
                'name' => 'imagen_banner',
                'type' => 'image',
            ),
            array(
                'key' => 'field_texto_banner',
                'label' => 'Texto del banner',
                'name' => 'texto_banner',
                'type' => 'text',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'opciones-globales',
                ),
            ),
        ),
    ));
}


//--------------------------SLIDER

// Registrar el post type "slider"
function registrar_slider_post_type() {
    $labels = array(
        'name'               => 'Slider',
        'singular_name'      => 'Slider',
        'menu_name'          => 'Slider',
        'name_admin_bar'     => 'Slider',
        'add_new'            => 'Añadir nuevo',
        'add_new_item'       => 'Añadir nuevo Slider',
        'new_item'           => 'Nuevo Slider',
        'edit_item'          => 'Editar Slider',
        'view_item'          => 'Ver Slider',
        'all_items'          => 'Todos los Sliders',
        'search_items'       => 'Buscar Sliders',
        'parent_item_colon'  => 'Slider padre:',
        'not_found'          => 'No se encontraron Sliders.',
        'not_found_in_trash' => 'No se encontraron Sliders en la papelera.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'publicly_queryable' => true,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => array( 'title' ),
        'rewrite'            => array( 'slug' => 'slider' ),
    );

    register_post_type( 'slider', $args );
}
add_action( 'init', 'registrar_slider_post_type' );

// Registrar los campos personalizados para el post type "slider"
function registrar_campos_personalizados_slider() {
    // Campo "IMAGEN DESKTOP"
    acf_add_local_field_group( array(
        'key'       => 'group_imagen_desktop',
        'title'     => 'Imagen Desktop',
        'fields'    => array(
            array(
                'key'           => 'field_imagen_desktop',
                'label'         => 'Imagen Desktop',
                'name'          => 'imagen_desktop',
                'type'          => 'image',
                'return_format' => 'url',
                'preview_size'  => 'thumbnail',
            ),
        ),
        'location'  => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'slider',
                ),
            ),
        ),
    ) );

    // Campo "IMAGEN MOBILE"
    acf_add_local_field_group( array(
        'key'       => 'group_imagen_mobile',
        'title'     => 'Imagen Mobile',
        'fields'    => array(
            array(
                'key'           => 'field_imagen_mobile',
                'label'         => 'Imagen Mobile',
                'name'          => 'imagen_mobile',
                'type'          => 'image',
                'return_format' => 'url',
                'preview_size'  => 'thumbnail',
            ),
        ),
        'location'  => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'slider',
                ),
            ),
        ),
    ) );
}
add_action( 'acf/init', 'registrar_campos_personalizados_slider' );

//------------------------------CARROUSEL

function registrar_custom_post_type() {
    $labels = array(
        'name'               => 'Custom Post Type',
        'singular_name'      => 'Custom Post Type',
        'menu_name'          => 'Custom Post Types',
        'name_admin_bar'     => 'Custom Post Type',
        'add_new'            => 'Agregar nuevo',
        'add_new_item'       => 'Agregar nuevo Custom Post Type',
        'new_item'           => 'Nuevo Custom Post Type',
        'edit_item'          => 'Editar Custom Post Type',
        'view_item'          => 'Ver Custom Post Type',
        'all_items'          => 'Todos los Custom Post Types',
        'search_items'       => 'Buscar Custom Post Types',
        'parent_item_colon'  => 'Custom Post Types padre:',
        'not_found'          => 'No se encontraron Custom Post Types.',
        'not_found_in_trash' => 'No se encontraron Custom Post Types en la papelera.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'custom-post-type' ), 
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ) 
    );

    register_post_type( 'custom_post_type', $args ); 
}
add_action( 'init', 'registrar_custom_post_type' );


//----------------POST CONTACTO
function crear_entrada_contacto() {
    // Comprobar si la entrada ya existe
    $entrada = get_page_by_title( 'Contacto', OBJECT, 'post' );

    // Si la entrada ya existe, devolver su ID
    if ( $entrada ) {
        return $entrada->ID;
    } else {
        // Crear el contenido de la entrada
        $entrada_content = '[contact-form-7 id="1169" title="Contacto"]';

        // Crear los datos de la entrada
        $entrada_data = array(
            'post_title'   => 'Contacto',
            'post_content' => $entrada_content,
            'post_status'  => 'publish',
            'post_type'    => 'post'
        );

        // Insertar la entrada en la base de datos y obtener el ID
        $entrada_id = wp_insert_post( $entrada_data );

        // Devolver el ID de la entrada creada
        return $entrada_id;
    }
}
add_action( 'after_setup_theme', 'crear_entrada_contacto' );


//REGISTRAMOS EL POST TYPE PLANTA
function registrar_post_type_planta() {
    $labels = array(
        'name'               => 'Plantas',
        'singular_name'      => 'Planta',
        'menu_name'          => 'Plantas',
        'name_admin_bar'     => 'Planta',
        'add_new'            => 'Agregar Nueva',
        'add_new_item'       => 'Agregar Nueva Planta',
        'new_item'           => 'Nueva Planta',
        'edit_item'          => 'Editar Planta',
        'view_item'          => 'Ver Planta',
        'all_items'          => 'Todas las Plantas',
        'search_items'       => 'Buscar Plantas',
        'parent_item_colon'  => 'Planta Padre:',
        'not_found'          => 'No se encontraron plantas.',
        'not_found_in_trash' => 'No se encontraron plantas en la papelera.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'planta' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-leaf',
        'supports'           => array( 'title', 'editor', 'thumbnail' )
    );

    register_post_type( 'planta', $args );
}
add_action( 'init', 'registrar_post_type_planta' );

// Crear los campos personalizados CARACTERISTICAS PLANTAS
function crear_campos_caracteristicas() {
    if( function_exists('acf_add_local_field_group') ):

        acf_add_local_field_group(array(
            'key' => 'caracteristicas_planta',
            'title' => 'Características de la Planta',
            'fields' => array(
                array(
                    'key' => 'riego_planta',
                    'label' => 'Riego',
                    'name' => 'riego',
                    'type' => 'select',
                    'choices' => array(
                        'Poca agua' => 'Poca agua',
                        'Moderada' => 'Moderada',
                        'Abundante' => 'Abundante',
                    ),
                    'multiple' => 0,
                    'allow_null' => 0,
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'siembra_planta',
                    'label' => 'Siembra',
                    'name' => 'siembra',
                    'type' => 'select',
                    'choices' => array(
                        'Interior' => 'Interior',
                        'Exterior' => 'Exterior',
                    ),
                    'multiple' => 0,
                    'allow_null' => 0,
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'luz_planta',
                    'label' => 'Luz',
                    'name' => 'luz',
                    'type' => 'select',
                    'choices' => array(
                        'Baja' => 'Baja',
                        'Moderada' => 'Moderada',
                        'Alta' => 'Alta',
                    ),
                    'multiple' => 0,
                    'allow_null' => 0,
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'planta', // Es el post type al que se lo añadimos
                    ),
                ),
            ),
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
        
    endif;}
add_action('acf/init', 'crear_campos_caracteristicas');

//CREAMOS LA PÁGINA DEL GRID
function custom_grid_template_page() {
    $page_title = 'PLANTAS DECORATIVAS'; // Título de la página
    $page_slug = 'plantas-decorativas'; // Slug de la página (URL amigable)

    $existing_page = get_page_by_path($page_slug);

    if (!$existing_page) {
        // Crea la página si no existe
        $page_id = wp_insert_post(array(
            'post_title'     => $page_title,
            'post_name'      => $page_slug,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ));

        if ($page_id) {
            // Asigna la plantilla al nuevo ID de página
            update_post_meta($page_id, '_wp_page_template', 'grid_template.php');
        }
    }
}

add_action('after_switch_theme', 'custom_grid_template_page');


//FILTRO PLANTAS
// Función para aplicar el filtro de características a la consulta
function filtros($args){
    // Verificar si se ha enviado el formulario de filtro
    if (isset($_GET['filtro-riego']) && isset($_GET['filtro-siembra']) && isset($_GET['filtro-luz'])) {
        $meta_query = array('relation' => 'AND');

        // Filtrar por características seleccionadas
        if ($_GET['filtro-riego'] !== 'todos') {
            $meta_query[] = array(
                'key'     => 'riego',
                'value'   => $_GET['filtro-riego'],
                'compare' => '=',
            );
        }

        if ($_GET['filtro-siembra'] !== 'todos') {
            $meta_query[] = array(
                'key'     => 'siembra',
                'value'   => $_GET['filtro-siembra'],
                'compare' => '=',
            );
        }

        if ($_GET['filtro-luz'] !== 'todos') {
            $meta_query[] = array(
                'key'     => 'luz',
                'value'   => $_GET['filtro-luz'],
                'compare' => '=',
            );
        }

        // Agregar la meta consulta a $args
        $args['meta_query'] = $meta_query;
    }
    return $args;
}


// Mostrar el formulario de filtro
function mostrar_formulario_filtro() {
    ?>
    <form action="<?php echo esc_url(get_permalink()); ?>" method="GET" class="filter-form">
        <div class="filter-container">
            <label for="filtro-riego">Riego:</label>
            <select name="filtro-riego" id="filtro-riego">
                <option value="todos">Todos</option>
                <option value="Poca agua">Poca agua</option>
                <option value="Moderada">Moderada</option>
                <option value="Abundante">Abundante</option>
            </select>
        </div>

        <div class="filter-container">
            <label for="filtro-siembra">Siembra:</label>
            <select name="filtro-siembra" id="filtro-siembra">
                <option value="todos">Todos</option>
                <option value="Interior">Interior</option>
                <option value="Exterior">Exterior</option>
            </select>
        </div>

        <div class="filter-container">
            <label for="filtro-luz">Luz:</label>
            <select name="filtro-luz" id="filtro-luz">
                <option value="todos">Todos</option>
                <option value="Baja">Baja</option>
                <option value="Moderada">Moderada</option>
                <option value="Alta">Alta</option>
            </select>
        </div>
        <button type="submit">Filtrar</button>
    </form>
    <?php
}
