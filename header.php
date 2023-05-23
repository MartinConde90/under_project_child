<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Carga de jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Carga de Slick Slider -->
    <link rel="stylesheet" href="wp-content/plugins/slick-slider/bower_components/slick-carousel/slick/slick.css">
    <link rel="stylesheet" href="wp-content/plugins/slick-slider/bower_components/slick-carousel/slick/slick-theme.css">
    <script src="wp-content/plugins/slick-slider/bower_components/slick-carousel/slick/slick.min.js"></script>



    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <header id="masthead" class="site-header" role="banner">
      <div class="site-branding">
        <?php if ( is_front_page() && is_home() ) : ?>
          <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
        <?php else : ?>
          <p class="site-title"><?php bloginfo( 'name' ); ?></p>
        <?php endif; ?>
        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
      </div><!-- .site-branding -->
    </header>

    <?php /*
      if (is_front_page()) {
        get_template_part('Frontpage');
      }
      */
    ?>

    
    <!-- Resto del contenido de tu página -->
    
    <?php wp_footer(); ?>
  </body>
</html>

          


        



