<?php
// SHORTCODE PARA GRID DE PRODUCTOS CON ESTRUCTURA HTML DE SHOPTIMIZER


function productos_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'categoria' => '',
        'limite' => 8,
        'columns' => 4
    ), $atts);
    
    if (!function_exists('wc_get_products')) {
        return '<div class="error-message">WooCommerce no está activo.</div>';
    }
    
    // Configuración esencial
    add_filter('woocommerce_is_woocommerce', '__return_true');
    add_filter('loop_shop_columns', function() use ($atts) {
        return intval($atts['columns']);
    });
    
    // Construir el shortcode nativo de WooCommerce
    $shortcode = '[products';
    $shortcode .= ' limit="' . intval($atts['limite']) . '"';
    $shortcode .= ' columns="' . intval($atts['columns']) . '"';
    
    if (!empty($atts['categoria'])) {
        $shortcode .= ' category="' . esc_attr($atts['categoria']) . '"';
    }
    
    $shortcode .= ']';
    
    return do_shortcode($shortcode);
}
add_shortcode('productos_carousel', 'productos_carousel_shortcode');








// FUNCIÓN ADICIONAL PARA DEBUGGEAR CATEGORÍAS
function debug_product_categories_shortcode() {
    if (!function_exists('wc_get_products')) {
        return 'WooCommerce no está activo.';
    }
    
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false
    ));
    
    $output = '<div style="padding: 20px; background: #e7f3ff; border: 1px solid #b8daff; border-radius: 5px;">';
    $output .= '<h3>Categorías de Productos Disponibles:</h3>';
    
    if (!empty($categories)) {
        $output .= '<ul>';
        foreach ($categories as $category) {
            $product_count = wc_get_products(array(
                'category' => array($category->slug),
                'return' => 'ids',
                'limit' => -1
            ));
            $count = count($product_count);
            
            $output .= '<li><strong>' . $category->name . '</strong> (slug: <code>' . $category->slug . '</code>) - ' . $count . ' productos</li>';
        }
        $output .= '</ul>';
    } else {
        $output .= '<p>No se encontraron categorías de productos.</p>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('debug_categorias', 'debug_product_categories_shortcode');

// Asegurar que los estilos y scripts de WooCommerce se carguen siempre
function enqueue_woocommerce_assets() {
    // Cargar siempre los estilos de WooCommerce
    if (function_exists('wc_enqueue_js')) {
        wp_enqueue_style('woocommerce-general');
        wp_enqueue_style('woocommerce-layout');
        wp_enqueue_style('woocommerce-smallscreen');
        
        // Scripts esenciales de WooCommerce
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
        wp_enqueue_script('wc-add-to-cart-variation');
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_woocommerce_assets');

// Forzar que WooCommerce reconozca las páginas con shortcodes como páginas de WooCommerce
function force_woocommerce_on_shortcode_pages() {
    global $post;
    
    if (is_object($post) && has_shortcode($post->post_content, 'productos_carousel')) {
        // Forzar que WooCommerce piense que esta es una página de WooCommerce
        add_filter('woocommerce_is_woocommerce', '__return_true');
    }
}
add_action('wp', 'force_woocommerce_on_shortcode_pages');
?>