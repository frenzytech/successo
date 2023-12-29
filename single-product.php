<?php
/**
 * The Template for displaying product content within single product page.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header( 'shop' );

while ( have_posts() ) :
	the_post();

	/**
	 * Hook: woocommerce_before_single_product.
	 *
	 * @hooked wc_output_buffered_action - 10
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	?>

	<div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>
        <div class="back">
				<?php
				$terms = get_the_terms( get_the_ID(), 'product_cat' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$category_names = wp_list_pluck( $terms, 'name' );
					$svg_path = '/inc/back-green.svg';
                    $svg_content = file_get_contents( get_stylesheet_directory() . $svg_path );
					echo  '<a href="/soluciones-agropecuarias" class="back" style="background:none">' . $svg_content . implode( ', ', $category_names ) . '</a>';
				}
				?>
        </div>
		
        <div class="product-custom">
            <div class="meta-product-custom">
                <h1><?php the_title(); ?></h1>
				<?php
				// Obtener la URL del campo PDF
				$pdf_attachment_id = get_post_meta(get_the_ID(), 'pdf', true);
				$pdf_url = wp_get_attachment_url($pdf_attachment_id);
				if (!empty($pdf_url)) {
    				echo '<a href="' . esc_url(add_query_arg('product_id', get_the_ID(), '/ficha')) . '" class="product-ficha">Descargar la ficha técnica</a>';
				}
				?>
            	<div class="svg-five-stars">
					<?php
					$svg_path = '/inc/stars.svg';
                    $svg_content = file_get_contents( get_stylesheet_directory() . $svg_path );
					echo '<h4>Valoraciones:   </h4> '.$svg_content;
					?>
				</div>
                <!-- Mostrar atributos del producto -->
			<div class="product-attributes">
				<?php
				$product_attributes = wc_get_product( get_the_ID() )->get_attributes();
				if ( $product_attributes ) {
					foreach ( $product_attributes as $attribute ) {
						$attribute_name = $attribute->get_name();
						$attribute_value = wc_get_product( get_the_ID() )->get_attribute( $attribute_name );
						if ( $attribute_value ) {
							echo '<div class="att-title"><h4>' . esc_html( wc_attribute_label( $attribute_name ) ) . ':</h4></div><div class="att-value"><h4>' . esc_html( $attribute_value ) . '</h4></div>';
						}
					}
				}
				?>
			</div>
            </div>
            <div class="image-product-custom">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="producto-content-custom">
                <?php the_content(); ?>
            </div>
        </div>
			      <?php echo do_shortcode('[products_slider]'); ?>
	</div>

	<?php
	do_action( 'woocommerce_after_single_product' );

endwhile;