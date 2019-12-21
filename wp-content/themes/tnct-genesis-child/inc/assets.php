<?php
/**
 * User: Minh Nhut
 * Date: 2019-13-12
 */
if (!function_exists('tnct_enqueue_scripts')):
    function tnct_enqueue_scripts()
    {
        //add style
        wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/component-assets/lib/bootstrap/css/bootstrap.min.css', array(), 'v3.3.7', 'all');
        wp_enqueue_style('fontawesome', get_stylesheet_directory_uri() . '/component-assets/lib/font-awesome/css/font-awesome.min.css', array(), '4.7.0', 'all');
        wp_enqueue_style('mmenu', get_stylesheet_directory_uri() . '/component-assets/lib/mmenu/jquery.mmenu.all.css', array(), '8.4.0', 'all');
        wp_enqueue_style('slick', get_stylesheet_directory_uri() . '/component-assets/lib/slick/slick.css', array(), wp_get_theme()->get('Version'), 'all');
        wp_enqueue_style('slick-theme', get_stylesheet_directory_uri() . '/component-assets/lib/slick/slick-theme.css', array(), wp_get_theme()->get('Version'), 'all');
        wp_enqueue_style('reset', get_stylesheet_directory_uri() . '/component-assets/css/reset.min.css', array(), wp_get_theme()->get('Version'), 'all');
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/component-assets/css/style.css', array(), wp_get_theme()->get('Version'), 'all');

        //add script
        wp_enqueue_script('bootstrap-js', get_stylesheet_directory_uri() . '/component-assets/lib/bootstrap/js/bootstrap.min.js', array(), 'v3.3.7', true);
        wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/component-assets/lib/slick/slick.js', array(), wp_get_theme()->get('Version'), true);
        wp_enqueue_script('mmenu-js', get_stylesheet_directory_uri() . '/component-assets/lib/mmenu/jquery.mmenu.all.min.js', array(), wp_get_theme()->get('Version'), true);
        wp_enqueue_script('script-js', get_stylesheet_directory_uri() . '/component-assets/js/script.js', array('jquery'), wp_get_theme()->get('Version'), true);

    }

    add_action('wp_enqueue_scripts', 'tnct_enqueue_scripts', 1);
endif;
