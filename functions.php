<?php
// Funciones del child theme
function shoptimizer_child_enqueue_styles() {
    wp_enqueue_style( 'shoptimizer-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'shoptimizer-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('shoptimizer-style'),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'shoptimizer_child_enqueue_styles' );

// Shortcode para carousel de productos - CORREGIDO
function productos_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'categoria' => '',
        'limite' => 8
    ), $atts);
    
    // Verificar que WooCommerce esté activo
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce no está activo.</p>';
    }
    
    // Obtener productos de la categoría - QUERY CORREGIDA
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limite'],
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '='
            )
        )
    );
    
    // Si hay categoría específica, filtrar por ella
    if (!empty($atts['categoria'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $atts['categoria']
            )
        );
    }
    
    $products = new WP_Query($args);
    
    if (!$products->have_posts()) {
        return '<p>No hay productos disponibles en esta categoría: ' . esc_html($atts['categoria']) . '</p>';
    }
    
    // ESTRUCTURA CORREGIDA DEL CAROUSEL
    $output = '<div class="carousel-wrapper">';
    $output .= '<div class="carousel-container">';
    
    while ($products->have_posts()) {
        $products->the_post();
        global $product;
        
        $product_id = get_the_ID();
        $product_title = get_the_title();
        $product_link = get_permalink();
        $product_image = get_the_post_thumbnail($product_id, 'woocommerce_thumbnail');
        $product_price = $product->get_price_html();
        $product_rating = wc_get_rating_html($product->get_average_rating());
        
        // Si no hay imagen, usar placeholder
        if (!$product_image) {
            $product_image = '<img src="' . wc_placeholder_img_src() . '" alt="' . esc_attr($product_title) . '">';
        }
        
        // Obtener colores del producto (usando atributos)
        $colors = '';
        $attributes = $product->get_attributes();
        if (isset($attributes['pa_color']) || isset($attributes['color'])) {
            $color_terms = wp_get_post_terms($product_id, 'pa_color');
            if (!empty($color_terms)) {
                $colors = '<div class="product-colors">';
                foreach ($color_terms as $color) {
                    $colors .= '<span class="color-option ' . esc_attr($color->slug) . '"></span>';
                }
                $colors .= '</div>';
            }
        }
        
        $output .= '<div class="product-card">';
        $output .= '<div class="product-image">';
        $output .= '<a href="' . esc_url($product_link) . '">' . $product_image . '</a>';
        
        // Badge de descuento
        if ($product->is_on_sale()) {
            $output .= '<span class="product-badge">OFERTA</span>';
        }
        
        $output .= '</div>';
        $output .= '<div class="product-info">';
        $output .= '<h3 class="product-title"><a href="' . esc_url($product_link) . '">' . esc_html($product_title) . '</a></h3>';
        $output .= $colors;
        $output .= '<div class="product-price">' . $product_price . '</div>';
        
        if ($product_rating) {
            $output .= '<div class="product-rating">' . $product_rating . '</div>';
        }
        
        $output .= '<button class="product-button" onclick="location.href=\'' . esc_url($product_link) . '\'">Ver Producto</button>';
        $output .= '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div>'; // Cierra carousel-container
    
    // Botones de navegación del carousel
    $output .= '<button class="carousel-nav carousel-prev" onclick="moveCarousel(this, -1)">‹</button>';
    $output .= '<button class="carousel-nav carousel-next" onclick="moveCarousel(this, 1)">›</button>';
    $output .= '</div>'; // Cierra carousel-wrapper
    
    wp_reset_postdata();
    
    return $output;
}
add_shortcode('productos_carousel', 'productos_carousel_shortcode');

// JavaScript para el carousel - CORREGIDO
function add_carousel_script() {
    ?>
    <script>
    function moveCarousel(button, direction) {
        const wrapper = button.parentElement;
        const container = wrapper.querySelector('.carousel-container');
        const cards = container.querySelectorAll('.product-card');
        
        if (cards.length === 0) return;
        
        const cardWidth = cards[0].offsetWidth + 20; // 20px gap
        const currentTransform = container.style.transform || 'translateX(0px)';
        const currentX = parseInt(currentTransform.replace('translateX(', '').replace('px)', '')) || 0;
        const newX = currentX + (direction * cardWidth * -1);
        
        // Limitar el desplazamiento
        const maxScroll = -(container.scrollWidth - wrapper.offsetWidth);
        const finalX = Math.max(Math.min(newX, 0), maxScroll);
        
        container.style.transform = `translateX(${finalX}px)`;
    }
    </script>
    <?php
}
add_action('wp_footer', 'add_carousel_script');
?>