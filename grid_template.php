<?php
get_header();
include('menu.php');
?>

<!-- Mostrar el formulario de filtro -->
<?php mostrar_formulario_filtro(); ?>

<?php
$args = array(
    'post_type'      => 'planta',
    'posts_per_page' => 4,
    'paged'          => get_query_var('paged') ? get_query_var('paged') : 1
);

$args = filtros($args);

$query = new WP_Query($args);



if ($query->have_posts()) {
    $count = 0;

    echo '<div class="grid-container">'; 

        while ($query->have_posts()) {
            $query->the_post();
            $count++;

            $post_id = get_the_ID();
            $attachments = get_attached_media('image', $post_id);
                $attachment = reset($attachments);
                $thumbnail_url = $attachment->guid;
            $title = get_the_title();
            $post_permalink = get_permalink();
            
            ?>
            <div class="grid-item">
                <a href="<?php echo esc_url( $post_permalink ); ?>">
                    <h2 class="title-overlay"><?php echo $title; ?></h2>
                    <img src="<?php echo $thumbnail_url; ?>" alt="<?php echo $title; ?>">
                </a>
            </div>
            <?php

            // salto de línea después de cada 2 posts
            if ($count % 2 === 0) {
                echo '<div style="clear: both;"></div>';
            }
        }

    echo '</div>'; 

    // Agrega la paginación
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total'   => $query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'prev_next' => true,
        'prev_text' => '&#8249;', 
        'next_text' => '&#8250;', 
        'type'      => 'list', 
        'mid_size'  => 2, 
    ));
    echo '</div>';

    wp_reset_postdata();
} else {
    echo 'No se encontraron posts.';
}

get_footer();


