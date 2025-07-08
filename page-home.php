<?php
/**
 * Template Name: Home Page Custom
 * Template personalizado para la página de inicio
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <!-- Hero Banner -->
        <section class="hero-banner">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="hero-badge">HOT SALE</span>
                    <h1 class="hero-title">Drilling<br>Rotary hammers</h1>
                    <p class="hero-subtitle">Up to -20% with the code: <strong>MORATA-SALE20</strong></p>
                    <a href="#productos" class="hero-btn">Ver Productos</a>
                </div>
                <div class="hero-image">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hero-drill.jpg" alt="Drilling Tools">
                </div>
            </div>
        </section>

        <!-- Productos por Categoría -->
        <section class="products-section" id="productos">
            
            <!-- Drilling Machine Products -->
            <div class="category-section">
                <div class="category-header">
                    <h2>Drilling Machine Products</h2>
                    <a href="<?php echo get_category_link(get_cat_ID('drilling-machines')); ?>" class="view-all">
                        View All Products >
                    </a>
                </div>
                
                <div class="products-carousel" id="drilling-carousel">
                    <?php echo do_shortcode('[productos_carousel categoria="drilling-machines" limite="8"]'); ?>
                </div>
            </div>

            <!-- Más categorías -->
            <div class="category-section">
                <div class="category-header">
                    <h2>Power Tools</h2>
                    <a href="<?php echo get_category_link(get_cat_ID('power-tools')); ?>" class="view-all">
                        View All Products >
                    </a>
                </div>
                
                <div class="products-carousel" id="power-tools-carousel">
                    <?php echo do_shortcode('[productos_carousel categoria="power-tools" limite="8"]'); ?>
                </div>
            </div>

            <!-- Ofertas Especiales -->
            <div class="category-section">
                <div class="category-header">
                    <h2>Special Offers</h2>
                    <a href="/ofertas" class="view-all">View All Offers ></a>
                </div>
                
                <div class="products-carousel" id="ofertas-carousel">
                    <?php echo do_shortcode('[productos_carousel categoria="ofertas" limite="6"]'); ?>
                </div>
            </div>

        </section>

        <!-- Sección de Beneficios -->
        <section class="benefits-section">
            <div class="benefits-container">
                <div class="benefit-item">
                    <div class="benefit-icon">🚚</div>
                    <h3>Free Shipping</h3>
                    <p>On orders over $100</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🔧</div>
                    <h3>Expert Support</h3>
                    <p>Professional advice</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">↩️</div>
                    <h3>Easy Returns</h3>
                    <p>30-day return policy</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🛡️</div>
                    <h3>Warranty</h3>
                    <p>2-year manufacturer warranty</p>
                </div>
            </div>
        </section>

    </main>
</div>

<?php get_footer(); ?>