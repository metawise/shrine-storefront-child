<?php
include_once "slider.php";
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

// Fully Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action('wp_enqueue_scripts', 'remove_block_css', 100);
function remove_block_css()
{
  wp_dequeue_style('wp-block-library'); // Wordpress core
  wp_dequeue_style('wp-block-library-theme'); // Wordpress core
  wp_dequeue_style('wc-block-style'); // WooCommerce
  wp_dequeue_style('storefront-gutenberg-blocks'); // Storefront theme
}

add_action('init', 'shrine_remove');
function shrine_remove()
{
  /**
   * Header
   * @see  storefront_secondary_navigation()
   */
  remove_action('storefront_header', 'storefront_secondary_navigation', 30);
  /**
   * Homepage
   * @see  storefront_recent_products()
   * @see  storefront_popular_products()
   */
  remove_action('homepage', 'storefront_recent_products', 30);
  remove_action('homepage', 'storefront_popular_products', 50);
  /**
   * Footer
   * @see  storefront_handheld_footer_bar()
   */
  remove_action('storefront_footer', 'storefront_handheld_footer_bar', 999);
}

if (!function_exists('storefront_site_branding')) {
  /**
   * Overwrite storefront_site_branding function defined in storefront/inc/storefront-template-functions.php
   */
  function storefront_site_branding()
  {
?>

    <center>
      <font color="white">Free UPS ground shipping on all US orders over $200 and free priority shipping on all international orders over $600</font>
    </center>
    <p></p>
    <center>
      <font color="yellow">If you are in the LA area we invite you to make an appointment to shop at the Shrine showroom and warehouse in downtown Los Angeles. We take appointments Tuesday - Saturday afternoons. Contact Us <a href="mailto:shrine@shrinestore.com">here</a></font>
      <p></p>
      <div class="shrine-hdr-contact">
        <ul>
          <li class="shrine-hdr-phone-ttl">Phone Orders</li>
          <li class="shrine-hdr-phone-number">
            <a href="tel://1-213-622-9656" class="text-white">(213) 622-9656</a>
          </li>
          <li class="shrine-hdr-hours">9am - 6pm PST | Monday - Friday</li>
        </ul>
      </div>
      <div class="shrine-hdr-logo">
        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><img class="shrine-logo" src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" alt="Shrine Store" width="340" height="179" /></a>
        <div class="shrine-hdr-tagline">Hollywood, California</div>
      </div>
      <div class="shrine-hdr-links">
        <ul id="shrine-hdr-cart">
          <li>
            <a href="https://www.facebook.com/ShrineofHollywood" target="_blank" aria-label="Visit our Facebook page">
              <span class="shrine-facebook"></span></a> &nbsp;
            <a href="https://www.Pinterest.com/shrinestore" target="_blank" aria-label="Visit our Pinterest page"><span class="shrine-pinterest"></span></a> &nbsp;
            <a href="https://twitter.com/shrinestore" target="_blank" aria-label="Visit our Twitter page"><span class="shrine-twitter"></span></a> &nbsp;
            <a href="https://www.instagram.com/shrinehollywood" target="_blank" aria-label="Visit our Instagram page"><span class="shrine-instagram"></span></a>
          </li>
          <li class="shrine-hdr-account">
            <a href="index.php?page_id=7">My Account</a>
          </li>
          <li class="shrine-hdr-cart">
            <a href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'storefront'); ?>">
              <span class="shrine-hdr-amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span class="shrine-hdr-count"><?php echo wp_kses_data(sprintf(_n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'storefront'), WC()->cart->get_cart_contents_count())); ?></span>
            </a>
          </li>
        </ul>
      </div>
    <?php
  }
}

if (!function_exists('storefront_primary_navigation')) {
  /**
   * Overwrite the primary navigation function defined in storefront/inc/structure/header.php
   * Display Primary Navigation
   * @since  1.0.0
   * @return void
   */
  function storefront_primary_navigation()
  {
    ?>
      <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e('Primary Navigation', 'storefront'); ?>">
        <button class="menu-toggle" aria-controls="primary-navigation" aria-expanded="false" aria-label="Toggle"></button>
        <?php
        wp_nav_menu(
          array(
            'theme_location'  => 'primary',
            'container_class'  => 'primary-navigation',
          )
        );

        wp_nav_menu(
          array(
            'theme_location'  => 'handheld',
            'container_class'  => 'handheld-navigation',
          )
        );
        ?>
      </nav><!-- #site-navigation -->
    <?php
  }
}

if (!function_exists('storefront_credit')) {
  /**
   * Overwrite the theme credit function defined in storefront/inc/structure/footer.php
   * @return void
   */
  function storefront_credit()
  {
    ?>
      <div class="site-info">
        <a href="index.php"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-mini.png" class="shrine-ftr-logo" alt="Shrine of Hollywood Logo" width="100" height="38" /></a>
        <?php wp_nav_menu(array(
          'container' => false,                       // remove nav container
          'container_class' => '',                    // class for container
          'menu' => __('Footer Menu', 'shrine'),    // nav name
          'menu_class' => 'shrine-ftr-nav',           // custom nav class
          'theme_location' => 'site-info',            // where located in the theme
          'before' => '',                             // before the menu
          'after' => '',                              // after the menu
          'link_before' => '',                        // before each link
          'link_after' => '',                         // after each link
          'depth' => 0,                               // limit the depth of the nav
          'fallback_cb' => ''                         // fallback function (if there is one)
        )); ?>
        <small><?php echo esc_html(apply_filters('storefront_copyright_text', $content = '&copy; ' . date('Y')) . ' Rock Opera Inc. All Rights Reserved.'); ?></small>
      </div><!-- .site-info -->
  <?php
  }
}

if (!function_exists('storefront_product_categories')) {
  /**
   * Display Product Categories
   * Hooked into the `homepage` action in the homepage template
   * @since  1.0.0
   * @return void
   */
  function storefront_product_categories($args)
  {

    if (is_woocommerce_activated()) {

      $args = apply_filters('storefront_product_categories_args', array(
        'limit'             => 12,
        'columns'           => 3,
        'child_categories'   => 0,
        'orderby'           => 'menu_order',
        'title'              => __('Product Categories', 'shrine-storefront-child'),
      ));

      echo '<section class="storefront-product-section storefront-product-categories">';

      do_action('storefront_homepage_before_product_categories');

      echo '<h2 class="section-title">' . wp_kses_post($args['title']) . '</h2>';

      do_action('storefront_homepage_after_product_categories_title');

      echo storefront_do_shortcode(
        'product_categories',
        array(
          'number'   => intval($args['limit']),
          'columns'  => intval($args['columns']),
          'orderby'  => esc_attr($args['orderby']),
          'parent'  => esc_attr($args['child_categories']),
        )
      );

      do_action('storefront_homepage_after_product_categories');

      echo '</section>';
    }
  }
}

add_filter('woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args');

function custom_woocommerce_get_catalog_ordering_args($args)
{
  $orderby_value = isset($_GET['orderby']) ? woocommerce_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

  if ('alphabetical' == $orderby_value) {
    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
  }

  if (!isset($_GET['orderby'])) {

    $args['orderby'] = 'title';
    $args['order'] = 'ASC';
  }


  return $args;
}

add_filter('woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby');

add_filter('woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby');

function custom_woocommerce_catalog_orderby($sortby)
{
  $sortby['alphabetical'] = __('Alphabetical');
  return $sortby;
}

// if (!function_exists('woocommerce_image_dimensions')) {
//     function woocommerce_image_dimensions() {

//         $single = array(
//             'width'     => '500',   // px
//             'height'    => '800',   // px
//             'crop'      => 0       // true
//         );

//         // Image sizes
//         // update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
//         update_option( 'shop_single_image_size', $single );         // Single product image
//         // update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
//     }
// }
// add_action( 'after_switch_theme', 'woocommerce_image_dimensions', 1 );