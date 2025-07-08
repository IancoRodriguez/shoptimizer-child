# Shoptimizer Child Theme

Child theme personalizado para Shoptimizer con homepage custom, carousel de productos y funcionalidades específicas para merchandising.

## 🚀 Características

- **Homepage personalizada** con hero banner
- **Carousel de productos** por categorías
- **Diseño responsive** para todos los dispositivos
- **Sección de beneficios** con iconos
- **Colores personalizados** para la marca
- **Sistema de cupones** integrado

## 📁 Estructura de Archivos

```
shoptimizer-child/
├── style.css          # Estilos personalizados
├── page-home.php      # Template homepage personalizado
├── functions.php      # Funciones y shortcodes
├── images/           # Imágenes del tema
└── README.md         # Este archivo
```

## 🛠️ Instalación

1. Subir la carpeta completa a `wp-content/themes/`
2. Activar en WordPress Admin → Apariencia → Temas
3. Asignar el template "Home Page Custom" a tu página de inicio

## 📦 Categorías de Productos

El tema está configurado para mostrar productos de estas categorías:
- **Merchandising** (hasta 8 productos)
- **Ropa** (hasta 8 productos)
- **Uniformes** (hasta 6 productos)
- **Regalos Corporativos** (hasta 6 productos)

## 🎨 Personalización

### Colores
Para cambiar los colores principales, edita las variables CSS en `style.css`:

```css
:root {
    --primary-color: #ff6b35;    /* Naranja principal */
    --secondary-color: #1a1a1a;  /* Negro/gris oscuro */
    --accent-color: #ffd700;     /* Amarillo dorado */
    --text-color: #333;          /* Texto principal */
    --light-bg: #f8f9fa;        /* Fondo claro */
}
```

### Hero Banner
Para cambiar el contenido del hero banner, edita `page-home.php`:
- Modificar el badge y título
- Cambiar el código de descuento
- Actualizar la imagen de fondo

## 🔧 Funcionalidades Técnicas

### Shortcode de Carousel
```php
[productos_carousel categoria="merchandising" limite="8"]
```

### JavaScript
- Carousel automático con navegación
- Responsive design
- Smooth transitions

## 📱 Responsive Design

- **Desktop**: Carousel con 4 productos visibles
- **Tablet**: Carousel con 2-3 productos visibles
- **Mobile**: Carousel con 1 producto visible

## 🎯 Beneficios Incluidos

- 🚚 Envío gratis en compras +$50
- 🎨 Personalización de diseños
- ⚡ Entrega rápida 5-7 días
- 🏆 Calidad premium garantizada

## 🔧 Requisitos

- WordPress 5.0+
- WooCommerce 3.0+
- Tema padre Shoptimizer
- PHP 7.4+

## 📝 Changelog

### v1.0.0
- Implementación inicial del child theme
- Homepage personalizada con hero banner
- Carousel de productos por categorías
- Sección de beneficios
- Diseño responsive completo

## 🤝 Soporte

Para dudas o problemas:
- Revisar la documentación de Shoptimizer
- Verificar que WooCommerce esté configurado correctamente
- Confirmar que las categorías de productos existen

## 📄 Licencia

Este child theme sigue la misma licencia que el tema padre Shoptimizer.

---

**Desarrollado para maximizar conversiones en tiendas de merchandising y productos personalizados.**