<?php
/**
 * The Template for displaying product content within a single product page.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header('shop');

while (have_posts()) :
    the_post();

    /**
     * Hook: woocommerce_before_single_product.
     *
     * @hooked wc_output_buffered_action - 10
     * @hooked wc_print_notices - 10
     */
    do_action('woocommerce_before_single_product');

    ?>

    <div id="plan-<?php the_ID(); ?>">
        <div class="back">
            <?php
            $svg_path = '/inc/back-green.svg';
            $svg_content = file_get_contents(get_stylesheet_directory() . $svg_path);
            echo '<a href="/planes-de-manejo" class="back" style="background:none">' . $svg_content . 'Planes de Manejo</a>';
            ?>
        </div>

        <div class="plan-custom">
            <div class="meta-plan-custom">
                <h1><?php the_title(); ?></h1>
                <h2 class="uno">¿Quieres más</h2>
                <h2 class="dos">información?</h2>
                <?php
                // Obtener la URL del campo PDF
                $pdf_attachment_id = get_post_meta(get_the_ID(), 'pdf', true);
                // Obtener la URL del archivo adjunto
                $pdf_url = wp_get_attachment_url($pdf_attachment_id);
                
                if (!empty($pdf_url)) {
                    echo '<form action="" method="post" id="hs-form">
                    <input class="email" placeholder="hola@email.com" type="email" name="email" id="email" required>
                </form>';
                }
                ?>
            </div>
            <div class="image-plan-custom">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail();
                } else {
                    echo 'no hay contendido'; // Asumiendo que $svg_content está definido en otro lugar
                }
                ?>
            </div>
            <div class="plan-content-custom">
                <?php
                $content = get_the_content();
                if (!empty($content)) {
                    echo '<p>' . $content . '</p>';
                } else {
                    echo '<p>Lorem ipsum dolor sit amet consectetur adipiscing elit hac cras, porta aenean est lacinia imperdiet proin ultricies augue enim sagittis, tellus sociis euismod duis ante blandit mollis dapibus. Id aliquet nostra dictum odio non malesuada commodo fermentum phasellus, cursus hendrerit augue hac nunc tortor morbi vestibulum mus, eget conubia eu erat magna ante habitasse integer.</p>';
                }
                
                
                ?>
            </div>
        </div>
        <?php echo do_shortcode('[plan_single_slider]'); ?>
    </div>

    <?php
    do_action('woocommerce_after_single_product');

endwhile;
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('hs-form');
        var emailInput = document.getElementById('email');
        var pdfLink = document.getElementById('pdf-link');

        emailInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === 'Tab') {
                event.preventDefault(); // Prevent the default form submission

                // Get the email value
                var email = emailInput.value;

                // Get the title of the post
                var postTitle = '<?php echo esc_js(get_the_title()); ?>';

                // Create the data object with title
                var data = {
                    'fields': [
                        {
                            'objectTypeId': '0-1',
                            'name': 'email',
                            'value': email,
                        },
                        {
                            'objectTypeId': '0-1',
                            'name': 'firstname',
                            'value': email,
                        },
                        {
                            'objectTypeId': '0-1',
                            'name': 'product',
                            'value': postTitle,
                        },
                    ],
                };

                // Send the data to the HubSpot Forms API
                fetch('https://api.hsforms.com/submissions/v3/integration/submit/44656007/68253f1c-70f2-41a5-b663-84c23f26597b', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer pat-na1-a64d5bba-f8da-4396-a377-607b08c1bcb5',
                    },
                    body: JSON.stringify(data),
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        
                        // After successful submission, trigger download of PDF
                        window.location.href = pdfLink.href;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Handle error as needed
                    });
            }
        });
    });
</script>
