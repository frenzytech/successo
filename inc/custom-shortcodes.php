<?php
// Agrega el shortcode [products_slider] al sistema
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
            <div class="slider-container products-slider-container">
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
            <div class="slider-controls products-slider-controls">
                <button class="prev products-prev">
                    <img src="/wp-content/uploads/2023/12/prev-icon.svg" alt="Prev Icon">
                </button>
                <button class="next products-next">
                    <img src="/wp-content/uploads/2023/12/next-icon.svg" alt="Next Icon">
                </button>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                var container = $('.products-slider-container');
                var slides = container.find('.slide');
                var totalSlides = slides.length;
                var currentIndex = 0;
                var prevButton = $('.products-prev');
                var nextButton = $('.products-next');

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

                $('.products-next').on('click', function () {
                    if (currentIndex + getSlidesToShow() < totalSlides) {
                        currentIndex++;
                        showSlide();
                    }
                });

                $('.products-prev').on('click', function () {
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

// Agrega el shortcode [planes_slider] al sistema
function planes_slider_shortcode($atts) {
    ob_start();
    $args = array(
        'post_type' => 'plan', // Reemplaza 'plan' con el nombre de tu post type
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        ?>
        <div class="instructivos-slider">
            <div class="slider-container planes-slider-container">
                <?php
                $count = 0;
                while ($query->have_posts()) : $query->the_post();
                    ?>
                    <div class="slide planes-slide">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    </div>
                    <?php
                    $count++;
                endwhile;
                ?>
            </div>
            <div class="slider-controls planes-slider-controls">
                <button class="prev planes-prev">
                    <img src="/wp-content/uploads/2023/12/prev-icon.svg" alt="Prev Icon">
                </button>
                <button class="next planes-next">
                    <img src="/wp-content/uploads/2023/12/next-icon.svg" alt="Next Icon">
                </button>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                var container = $('.planes-slider-container');
                var slides = container.find('.planes-slide');
                var totalSlides = slides.length;
                var currentIndex = 0;
                var prevButton = $('.planes-prev');
                var nextButton = $('.planes-next');

                function showSlide() {
                    slides.hide();
                    slides.slice(currentIndex, currentIndex + getSlidesToShow()).show();

                    // Oculta o muestra los botones según la posición actual
                    prevButton.toggle(currentIndex > 0);
                    nextButton.toggle(currentIndex + getSlidesToShow() < totalSlides);
                }

                function getSlidesToShow() {
                    return window.innerWidth <= 767 ? 1 : 5;
                }

                $('.planes-next').on('click', function () {
                    if (currentIndex + getSlidesToShow() < totalSlides) {
                        currentIndex++;
                        showSlide();
                    }
                });

                $('.planes-prev').on('click', function () {
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

add_shortcode('planes_slider', 'planes_slider_shortcode');


// Agrega el shortcode [plan_single_slider] al sistema
function plan_single_slider_shortcode($atts) {
    ob_start();

    $args = array(
        'post_type'      => 'plan', // Reemplaza 'plan' con el nombre de tu post type
        'posts_per_page' => -1,
        'order'          => 'DESC', // Ordena los productos por fecha de publicación de forma descendente
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        ?>
        <div class="products-slider">
            <div class="slider-container plan-single-slider-container">
                <?php
                $count = 0;
                while ($query->have_posts()) : $query->the_post();
                    $is_current = (get_the_ID() === get_queried_object_id());
                    ?>
                    <div class="slide <?php echo $is_current ? 'current' : ''; ?> plan-single-slide">
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
            <div class="slider-controls plan-single-slider-controls">
                <button class="prev plan-single-prev">
                    <img src="/wp-content/uploads/2023/12/prev-icon.svg" alt="Prev Icon">
                </button>
                <button class="next plan-single-next">
                    <img src="/wp-content/uploads/2023/12/next-icon.svg" alt="Next Icon">
                </button>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                var container = $('.plan-single-slider-container');
                var slides = container.find('.plan-single-slide');
                var totalSlides = slides.length;
                var currentIndex = 0;
                var prevButton = $('.plan-single-prev');
                var nextButton = $('.plan-single-next');

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

                $('.plan-single-next').on('click', function () {
                    if (currentIndex + getSlidesToShow() < totalSlides) {
                        currentIndex++;
                        showSlide();
                    }
                });

                $('.plan-single-prev').on('click', function () {
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

add_shortcode('plan_single_slider', 'plan_single_slider_shortcode');

