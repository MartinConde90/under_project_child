<?php
get_header();
require_once('creaPaginas.php');
include('menu.php');
?>



<?php
//banner
    $imagen_banner = get_field('imagen_banner', 'option');
    $texto_banner = get_field('texto_banner', 'option');
    
    if ($imagen_banner || $texto_banner) {
        echo '<div class="banner-container">';
    
            if ($imagen_banner) {
                echo '<img src="' . esc_url($imagen_banner['url']) . '" alt="' . esc_attr($imagen_banner['alt']) . '">';
            }

            if ($texto_banner) {
                echo '<div class="texto-banner">' . esc_html($texto_banner) . '</div>';
            }
    
        echo '</div>';
    }
    
?>

<?php //SLIDER ?>

<div id="slider">
    <?php
    // Obtener los sliders
    $sliders = get_posts( array(
        'post_type'      => 'slider',
        'posts_per_page' => -1, // Obtener todos los sliders
    ) );

    // Mostrar el slider si hay sliders disponibles
    if ( $sliders ) {
        foreach ( $sliders as $slider ) {
            // Obtener la URL de la imagen desktop
            $imagen_desktop = get_field( 'imagen_desktop', $slider->ID );

            // Obtener la URL de la imagen mobile con el tamaño personalizado
            $imagen_mobile = get_field( 'imagen_mobile', $slider->ID );
            $imagen_mobile_url = wp_get_attachment_image_url( $imagen_mobile, 'imagen_mobile' );

            // Verificar si el dispositivo es desktop o mobile
            $is_desktop = wp_is_mobile() ? false : true;
            // Mostrar la imagen correspondiente al dispositivo
            $imagen_url = $is_desktop ? $imagen_desktop : $imagen_mobile;

            echo '<div class="slider-item">';
            echo '<img src="' . $imagen_url . '" alt="Slider Image">';
            echo '</div>';
        }
    }
    ?>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#slider').slick({
            dots: false, // Mostrar los puntos de navegación
            arrows: false, // Mostrar las flechas de navegación
            infinite: false, // Habilitar el desplazamiento infinito
            slidesToShow: 1, // Mostrar un slide a la vez
            slidesToScroll: 1 // Desplazarse de a un slide a la vez
        });
    });
</script>

<?php //---------CARROUSEL POST CUSTOM TYPES ?>

<div class="carousel">
    <?php
    $args = array(
        'post_type'      => 'custom_post_type', 
        'posts_per_page' => -1
    );

    $query = new WP_Query( $args );

    while ( $query->have_posts() ) {
        $query->the_post();
        $post_permalink = get_permalink();
        ?>
        <div class="carousel-item">
            <a href="<?php echo esc_url( $post_permalink ); ?>">
                
                <div class="carousel-content">
                    <?php the_content(); ?>
                </div>
                <h2><?php the_title(); ?></h2>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="carousel-thumbnail">
                        <?php the_post_thumbnail( 'thumbnail' ); ?>
                    </div>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }
    wp_reset_postdata();
    ?>
</div>



<script>
    jQuery(document).ready(function ($) {
        $('.carousel').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: true,
            infinite: false,
            speed: 500,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>


<?php //FORMULARIO DE CONTACTO ?>

<?php
// Realizar una consulta para obtener la entrada de contacto
$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'ID'           => '1169'
);

$query = new WP_Query( $args );

// Mostrar la entrada de contacto si se encuentra
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        the_content();
    }
    wp_reset_postdata();
}
?>














