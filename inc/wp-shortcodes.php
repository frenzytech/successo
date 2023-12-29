<?php
// Agrega el shortcode [instructivos_slider] al sistema
function instructivos_slider_shortcode($atts) {
    ob_start();
    $args = array(
        'post_type' => 'instructivo', // Reemplaza 'instructivo' con el nombre de tu post type
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        ?>
        <div class="instructivos-slider">
            <div class="slider-container">
                <?php
                $count = 0;
                while ($query->have_posts()) : $query->the_post();
                    ?>
                    <div class="slide">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    </div>
                    <?php
                    $count++;
                endwhile;
                ?>
            </div>
            <div class="slider-controls">
                <button class="prev">
                    <img src="/wp-content/uploads/2023/12/prev-icon.svg" alt="Prev Icon">
                </button>
                <button class="next">
                    <img src="/wp-content/uploads/2023/12/next-icon.svg" alt="Next Icon">
                </button>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                var container = $('.slider-container');
                var slides = container.find('.slide');
                var totalSlides = slides.length;
                var currentIndex = 0;
                var prevButton = $('.prev');
                var nextButton = $('.next');

                function showSlide() {
                    slides.hide();
                    slides.slice(currentIndex, currentIndex + getSlidesToShow()).show();

                    // Oculta o muestra los botones según la posición actual
                    prevButton.toggle(currentIndex > 0);
                    nextButton.toggle(currentIndex + getSlidesToShow() < totalSlides);
                }

                function getSlidesToShow() {
                    return window.innerWidth <= 767 ? 1 : 3;
                }

                $('.next').on('click', function () {
                    if (currentIndex + getSlidesToShow() < totalSlides) {
                        currentIndex++;
                        showSlide();
                    }
                });

                $('.prev').on('click', function () {
                    if (currentIndex > 0) {
                        currentIndex--;
                        showSlide();
                    }
                });

                showSlide();

                // Asegúrate de actualizar el número de slides al cambiar el tamaño de la pantalla
                $(window).on('resize', function () {
                    showSlide();
                });
            });
        </script>
        <?php
    endif;
    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('instructivos_slider', 'instructivos_slider_shortcode');


// Agrega el shortcode [lista_posts_type_post] al sistema
function lista_posts_type_post_shortcode($atts) {
    // Establece los parámetros por defecto
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 5, // Número de posts a mostrar
        ),
        $atts,
        'lista_posts_type_post'
    );

    // Configura el argumento de la consulta
    $args = array(
        'posts_per_page' => $atts['posts_per_page'],
        'post_type' => 'post',
        'date_query' => array(
            array(
                'year' => date('Y'),
                'month' => date('n'),
            ),
        ),
    );

    // Obtiene los posts
    $query = new WP_Query($args);

    // Inicia la salida del buffer
    ob_start();

    // Muestra la lista de posts
    if ($query->have_posts()) {
        echo '<div class="post-list-sidebar">';
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="post-list-element">
                <div class="image-post-list">
                <?php
                if (has_post_thumbnail()) {
                    // Muestra la imagen destacada si está presente
                    echo '<a href="' . esc_url(get_permalink()) . '">';
                    the_post_thumbnail('thumbnail');
                    echo '</a>';
                }
                ?>
                </div>
                <div class="post-list-meta">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p><svg xmlns="http://www.w3.org/2000/svg" width="14" height="3" viewBox="0 0 25 2" fill="none">
<path d="M0 1L25 1" stroke="#819D44" stroke-width="2"/>
</svg><?php echo get_the_date(); ?></p>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo 'No hay posts disponibles.';
    }

    // Restablece las consultas de WordPress
    wp_reset_postdata();

    // Devuelve el contenido del buffer y lo limpia
    return ob_get_clean();
}

// Registra el shortcode
add_shortcode('lista_posts_type_post', 'lista_posts_type_post_shortcode');