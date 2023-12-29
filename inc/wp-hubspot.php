<?php
// Función para procesar el formulario y descargar el PDF
function process_form_and_download_pdf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $product_id = absint($_POST['product_id']);
        $email = sanitize_email($_POST['email']);
        $firstname = sanitize_text_field($_POST['first_name']);
        $phone = sanitize_text_field($_POST['phone']);

        // Recupera la URL del PDF almacenada en la opción de WordPress
        $pdf_url = get_option('pdf_url_' . $product_id);

        if (!$pdf_url) {
            return 'No se encontró la URL del PDF.';
        }
        $product = wc_get_product($product_id);
        $product_name = $product->get_name();

        $api_data = array(
            'fields' => array(
                array(
                    'objectTypeId' => '0-1',
                    'name' => 'email',
                    'value' => $email,
                ),
                array(
                    'objectTypeId' => '0-1',
                    'name' => 'firstname',
                    'value' => $firstname,
                ),
                array(
                    'objectTypeId' => '0-1',
                    'name' => 'phone',
                    'value' => $phone,
                ),
                array(
                    'objectTypeId' => '0-1',
                    'name' => 'product',
                    'value' => $product_name,
                ),
            ),
        );

        $bearer_token = 'pat-na1-a64d5bba-f8da-4396-a377-607b08c1bcb5';

        // URL de la API de HubSpot
        $api_url = 'https://api.hsforms.com/submissions/v3/integration/submit/44656007/68253f1c-70f2-41a5-b663-84c23f26597b';

        // Realiza la solicitud a la API con el Token Bearer
        $response = wp_remote_post($api_url, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $bearer_token,
            ),
            'body' => wp_json_encode($api_data),
        ));

        if (is_wp_error($response)) {
            return 'Hubo un error al conectar con la API de HubSpot.';
        }

        // Procesa la respuesta JSON
        $data = wp_remote_retrieve_body($response);
        $decoded_data = json_decode($data);

        // Obtén el contenido del PDF desde la URL
        $pdf_content = file_get_contents($pdf_url);

        // Envía el PDF al navegador
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ficha_tecnica.pdf"');
        echo $pdf_content;
        exit;
    }
}

// Función para obtener el ID del producto desde la URL y mostrar el formulario
function get_product_id_from_url_shortcode() {
    if (isset($_GET['product_id'])) {
        $product_id = absint($_GET['product_id']);
        // Obtener detalles del producto
        $product = wc_get_product($product_id);
        $product_name = $product->get_name();
        $product_url = get_permalink($product_id);
        $svg_path = '/inc/back-green.svg';
        $svg_content = file_get_contents( get_stylesheet_directory() . $svg_path );

        // Obtener la ID del archivo directamente del campo 'pdf'
        $pdf_attachment_id = get_post_meta($product_id, 'pdf', true);

        // Obtener la URL del archivo adjunto
        $pdf_url = wp_get_attachment_url($pdf_attachment_id);

        // Almacena la URL del PDF en una opción de WordPress
        update_option('pdf_url_' . $product_id, $pdf_url);

        $form_action_url = home_url($_SERVER['REQUEST_URI']); // Mantén la misma URL actual

        $form_html = '<form id="mi_formulario" action="' . esc_url($form_action_url) . '" method="POST">';
        $form_html .= '<a href="'.$product_url.'">'.$svg_content.'DESCARGAR FICHA</a>';    
        $form_html .= '<h1>' . esc_html($product_name) . '</h1>';
        $form_html .= '<div class="form-widget">';
        $form_html .= '<div class="form-element-2">';
        $form_html .= '<div class="form-element-1-2">';
        $form_html .= '<input type="text" name="first_name" id="first_name" placeholder="Nombre:" required>';
        $form_html .= '</div>';
        $form_html .= '<div class="form-element-1-2">';
        $form_html .= '<input placeholder="Teléfono:" type="tel" name="phone" id="phone">';
        $form_html .= '</div>';
        $form_html .= '</div>';
        $form_html .= '<div class="form-element-1">';
        $form_html .= '<div class="form-element-1-1">';
        $form_html .= '<input type="email" name="email" placeholder="Email:" required>';
        $form_html .= '</div>';
        $form_html .= '</div>';
        $form_html .= '</div>';
        $form_html .= '<input class="download" type="submit" value="Descargar Ficha">';
        $form_html .= '<input type="hidden" name="product_id" value="' . esc_attr($product_id) . '">';
        $form_html .= '</form>';

        return $form_html;
    } else {
        return 'No se proporcionó un ID de producto en la URL-RToken:';
    }
}

// Registra el shortcode y procesa el formulario antes de cargar la página
add_shortcode('product_id_from_url', 'get_product_id_from_url_shortcode');
add_action('template_redirect', 'process_form_and_download_pdf');

