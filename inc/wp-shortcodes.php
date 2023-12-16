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
                    slides.slice(currentIndex, currentIndex + 3).show();

                    // Oculta o muestra los botones según la posición actual
                    prevButton.toggle(currentIndex > 0);
                    nextButton.toggle(currentIndex + 3 < totalSlides);
                }

                $('.next').on('click', function () {
                    if (currentIndex + 3 < totalSlides) {
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
            });
        </script>
        <?php
    endif;
    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('instructivos_slider', 'instructivos_slider_shortcode');