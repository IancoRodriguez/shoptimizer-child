<?php
// SHORTCODE CORREGIDO PARA PRODUCTOS CON BOTÓN AGREGAR AL CARRITO
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
    
    // Guardar el post global actual
    global $post;
    $original_post = $post;
    
    // Empezar captura de salida para usar hooks de WooCommerce
    ob_start();
    ?>
    
    <div class="woocommerce">
        <ul class="products columns-4">
            <?php foreach ($products as $product) : ?>
                <?php
                // Asegurar que tenemos un objeto producto válido
                if (!is_object($product)) {
                    continue;
                }
                
                // Establecer el producto global para que funcionen los hooks
                global $woocommerce_loop;
                $woocommerce_loop['is_shortcode'] = true;
                $woocommerce_loop['columns'] = 4;
                ?>
                
                <li class="product type-product">
                    <?php
                    // Configurar el post global correctamente
                    $post = get_post($product->get_id());
                    setup_postdata($post);
                    
                    // Establecer el producto global
                    $GLOBALS['product'] = $product;
                    
                    // Usar el template completo de WooCommerce
                    wc_get_template_part('content', 'product');
                    ?>
                </li>
                
            <?php endforeach; ?>
        </ul>
    </div>
    
    <?php
    // Restaurar el post global original
    $post = $original_post;
    wp_reset_postdata();
    
    return ob_get_clean();
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
        
        // JS para fragmentos del carrito
        wc_enqueue_js("
            jQuery(document).ready(function($) {
                // Actualizar fragmentos del carrito cuando se agregue un producto
                $('body').on('added_to_cart', function(event, fragments, cart_hash) {
                    // Código para actualizar el carrito visualmente
                    if (fragments && fragments['.cart-contents']) {
                        $('.cart-contents').html(fragments['.cart-contents']);
                    }
                });
            });
        ");
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