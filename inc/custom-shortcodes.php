<?php
// Agrega el shortcode [products_slider] al sistema
function products_slider_shortcode($atts) {
    ob_start();

    // Obtén la categoría actual del producto
    $current_category = get_the_terms(get_the_ID(), 'product_cat');
    $current_category_slug = !empty($current_category) ? $current_category[0]->slug : '';

    $args = array(
        'post_type' => 'product', // Reemplaza 'product' con el nombre de tu post type
        'posts_per_page' => -1,
        'order' => 'DESC', // Ordena los productos por fecha de publicación de forma descendente
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $current_category_slug,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        ?>
        <div class="products-slider">
            <div class="slider-container">
                <?php
                $count = 0;
                while ($query->have_posts()) : $query->the_post();
                    $is_current = (get_the_ID() === get_queried_object_id());
                    ?>
                    <div class="slide <?php echo $is_current ? 'current' : ''; ?>">
                        <h2>
                            <?php if (!$is_current) : ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <?php else : ?>
                                <?php the_title(); ?>
                            <?php endif; ?>
                        </h2>
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

add_shortcode('products_slider', 'products_slider_shortcode');

// Agrega el shortcode [soluciones_slider] al sistema
function soluciones_slider_shortcode($atts) {
    ob_start();

    $args = array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'parent' => 0,
    );

    $categories = get_terms($args);

    if (!empty($categories)) :
        ?>
        <div class="instructivos-slider">
            <div class="slider-container">
                <?php
                foreach ($categories as $category) :
                    // Obtener el primer producto de la categoría
                    $first_product_args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $category->term_id,
                            ),
                        ),
                    );

                    $first_product_query = new WP_Query($first_product_args);

                    if ($first_product_query->have_posts()) :
                        $first_product_query->the_post();
                        ?>
                        <div class="slide">
                            <h2><a href="<?php echo get_permalink(); ?>"><?php echo $category->name; ?></a></h2>
                        </div>
                        <?php
                    endif;

                    wp_reset_postdata();
                endforeach;
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

add_shortcode('soluciones_slider', 'soluciones_slider_shortcode');

