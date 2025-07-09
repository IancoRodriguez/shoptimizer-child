<?php
// SHORTCODE CORREGIDO PARA PRODUCTOS CON BOTÓN DE CARRITO
function productos_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'categoria' => '',
        'limite' => 8
    ), $atts);
    
    // Verificar que WooCommerce esté activo
    if (!function_exists('wc_get_products')) {
        return '<div style="padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px;">WooCommerce no está activo o no se puede acceder a los productos.</div>';
    }
    
    $products = array();
    
    // Si se especifica una categoría
    if (!empty($atts['categoria'])) {
        // Método 1: Buscar por slug de categoría
        $term = get_term_by('slug', $atts['categoria'], 'product_cat');
        
        if ($term) {
            $products = wc_get_products(array(
                'limit' => intval($atts['limite']),
                'status' => 'publish',
                'category' => array($term->slug),
                'stock_status' => 'instock'
            ));
        }
        
        // Método 2: Si no encuentra por slug, buscar por nombre
        if (empty($products)) {
            $term = get_term_by('name', $atts['categoria'], 'product_cat');
            if ($term) {
                $products = wc_get_products(array(
                    'limit' => intval($atts['limite']),
                    'status' => 'publish',
                    'category' => array($term->slug),
                    'stock_status' => 'instock'
                ));
            }
        }
        
        // Método 3: Buscar usando tax_query (más específico)
        if (empty($products)) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => intval($atts['limite']),
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    )
                ),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $atts['categoria']
                    )
                )
            );
            
            $query = new WP_Query($args);
            $products = array();
            
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $products[] = wc_get_product(get_the_ID());
                }
                wp_reset_postdata();
            }
        }
    } else {
        // Si no se especifica categoría, mostrar productos recientes
        $products = wc_get_products(array(
            'limit' => intval($atts['limite']),
            'status' => 'publish',
            'stock_status' => 'instock',
            'orderby' => 'date',
            'order' => 'DESC'
        ));
    }
    
    // Debug: Mostrar información de categorías disponibles si no hay productos
    if (empty($products)) {
        $debug_info = '<div style="padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; border-radius: 5px;">';
        $debug_info .= '<strong>Debug Info:</strong><br>';
        $debug_info .= 'Categoría buscada: ' . esc_html($atts['categoria']) . '<br>';
        
        // Mostrar todas las categorías disponibles
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false
        ));
        
        if (!empty($categories)) {
            $debug_info .= '<strong>Categorías disponibles:</strong><br>';
            foreach ($categories as $category) {
                $debug_info .= '- ' . $category->name . ' (slug: ' . $category->slug . ')<br>';
            }
        }
        
        $debug_info .= '</div>';
        return $debug_info;
    }
    
    // NUEVA IMPLEMENTACIÓN: Usar plantilla estándar de WooCommerce
    ob_start();
    
    // Configurar query global para WooCommerce
    global $woocommerce_loop;
    $woocommerce_loop['columns'] = 4;
    $woocommerce_loop['is_shortcode'] = true;
    
    echo '<div class="woocommerce">';
    echo '<div class="products-carousel-homepage">';
    echo '<ul class="products columns-4">';
    
    foreach ($products as $product) {
        if (!is_object($product)) {
            continue;
        }
        
        // Configurar post data para el producto actual
        $GLOBALS['post'] = get_post($product->get_id());
        setup_postdata($GLOBALS['post']);
        
        // Usar la plantilla estándar de WooCommerce
        wc_get_template_part('content', 'product');
    }
    
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('productos_carousel', 'productos_carousel_shortcode');

// ALTERNATIVA: Shortcode usando el shortcode nativo de WooCommerce
function productos_wc_shortcode($atts) {
    $atts = shortcode_atts(array(
        'categoria' => '',
        'limite' => 8,
        'columnas' => 4
    ), $atts);
    
    // Construir argumentos para el shortcode nativo
    $shortcode_args = array(
        'limit' => intval($atts['limite']),
        'columns' => intval($atts['columnas']),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    // Si hay categoría, añadirla
    if (!empty($atts['categoria'])) {
        $shortcode_args['category'] = $atts['categoria'];
    }
    
    // Construir string de atributos
    $shortcode_string = '[products';
    foreach ($shortcode_args as $key => $value) {
        $shortcode_string .= ' ' . $key . '="' . $value . '"';
    }
    $shortcode_string .= ']';
    
    // Envolver en contenedor personalizado
    $output = '<div class="productos-homepage-wrapper">';
    $output .= do_shortcode($shortcode_string);
    $output .= '</div>';
    
    return $output;
}
add_shortcode('productos_wc', 'productos_wc_shortcode');

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
?>