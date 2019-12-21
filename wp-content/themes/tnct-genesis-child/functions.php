<?php
/**
 * TNCT Child Theme
 *
 *
 * @package TNCT Child Theme
 * @author  TNCT
 */

// Start the engine (do not remove).
require_once get_template_directory() . '/lib/init.php';

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'TNCT' );
define( 'CHILD_THEME_URL', 'https://toannangcantho.com/' );
define( 'CHILD_THEME_VERSION', '3.0.0' );

// Enable responsive viewport.
add_theme_support( 'genesis-responsive-viewport' );

// Enable automatic output of WordPress title tags.
add_theme_support( 'title-tag' );

// Enable HTML5 markup structure.
add_theme_support( 'html5', array(
    'caption',
    'comment-form',
    'comment-list',
    'gallery',
    'search-form',
) );

// Enable WooCommerce support.
add_theme_support( 'woocommerce' );

// Remove primary sidebar.
unregister_sidebar( 'sidebar' );
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

// Remove secondary sidebar.
unregister_sidebar( 'sidebar-alt' );
remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );

// Remove header right widget area.
unregister_sidebar( 'header-right' );
remove_action( 'genesis_header', 'genesis_do_header' );

// Remove loop
remove_action( 'genesis_loop', 'genesis_do_loop' );

// Remove footer
remove_action( 'genesis_footer', 'genesis_do_footer' );

// Set full width content
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Remove genesis admin menu
remove_theme_support( 'genesis-admin-menu' );

// Remove genesis SEO meta box in post
remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

// Remove genesis layouts in post
remove_theme_support( 'genesis-inpost-layouts' );

// Remove genesis scripts box in post
add_action( 'admin_menu', 'remove_genesis_page_post_scripts_box' );
function remove_genesis_page_post_scripts_box() {
    $types = array( 'post', 'page' );
    remove_meta_box( 'genesis_inpost_scripts_box', $types, 'normal' );
}

// Remove genesis menu
remove_theme_support( 'genesis-menus' );

// Load child theme
include_once( get_stylesheet_directory() . '/inc/child-theme.php' );
