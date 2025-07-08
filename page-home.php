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
                    <span class="hero-badge">OFERTA ESPECIAL</span>
                    <h1 class="hero-title">Merchandising<br>Personalizado</h1>
                    <p class="hero-subtitle">Hasta -25% con el código: <strong>PROMO2024</strong></p>
                    <a href="#productos" class="hero-btn">Ver Productos</a>
                </div>
                <div class="hero-image">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hero-merchandising.jpg" alt="Merchandising Personalizado">
                </div>
            </div>
        </section>

        <!-- Productos por Categoría -->
        <section class="products-section" id="productos">
            
            <!-- Merchandising -->
            <div class="category-section">
                <div class="category-header">
                    <h2>Merchandising</h2>
                    <a href="<?php echo get_category_link(get_cat_ID('merchandising')); ?>" class="view-all">
                        Ver Todos los Productos >
                    </a>
                </div>
                
                <div class="products-carousel" id="merchandising-carousel">
                    <?php echo do_shortcode('[productos_carousel categoria="merchandising" limite="8"]'); ?>
                </div>
            </div>

            <!-- Ropa -->
            <div class="category-section">
                <div class="category-header">
                    <h2>Ropa</h2>
                    <a href="<?php echo get_category_link(get_cat_ID('ropa')); ?>" class="view-all">
                        Ver Todos los Productos >
                    </a>
                </div>
                
                <div class="products-carousel" id="ropa-carousel">
                    <?php echo do_shortcode('[productos_carousel categoria="ropa" limite="8"]'); ?>
                </div>
            </div>

            <!-- Uniformes -->
            <div class="category-section">
                <div class="category-header">
                    <h2>Uniformes</h2>
                    <a href="<?php echo get_category_link(get_cat_ID('uniformes')); ?>" class="view-all">
                        Ver Todos los Productos >
                    </a>
                </div>
                
                <div class="products-carousel" id="uniformes-carousel">
                    <?php echo do_shortcode('[productos_carousel categoria="uniformes" limite="6"]'); ?>
                </div>
            </div>

            <!-- Regalos Corporativos -->
            <div class="category-section">
                <div class="category-header">
                    <h2>Regalos Corporativos</h2>
                    <a href="<?php echo get_category_link(get_cat_ID('regalos-corporativos')); ?>" class="view-all">
                        Ver Todos los Productos >
                    </a>
                </div>
                
                <div class="products-carousel" id="regalos-carousel">
                    <?php echo do_shortcode('[productos_carousel categoria="regalos-corporativos" limite="6"]'); ?>
                </div>
            </div>

        </section>

        <!-- Sección de Beneficios -->
        <section class="benefits-section">
            <div class="benefits-container">
                <div class="benefit-item">
                    <div class="benefit-icon">🚚</div>
                    <h3>Envío Gratis</h3>
                    <p>En compras mayores a $50</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🎨</div>
                    <h3>Personalización</h3>
                    <p>Diseños únicos para tu marca</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">⚡</div>
                    <h3>Entrega Rápida</h3>
                    <p>5-7 días hábiles</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🏆</div>
                    <h3>Calidad Premium</h3>
                    <p>Productos de alta calidad</p>
                </div>
            </div>
        </section>

    </main>
</div>

<?php get_footer(); ?>