<?php
/**
 *
 * User: Nhutfs
 * Date: 13/12/2019
 * Time: 08:59
 *
 * @link
 *
 * @package
 * @subpackage
 * @since 1.0.0
 */


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('TNCT_Child_Theme')) :
    class TNCT_Child_Theme
    {

        /**
         * TNCT_Child_Theme constructor.
         */
        function __construct()
        {
            $this->initialize();
        }

        /**
         * TNCT_Child_Theme initialize.
         */
        function initialize()
        {
            $version = wp_get_theme('startpress-framework')->get('Version');
            $path = dirname(__FILE__);
            $assets = get_stylesheet_directory_uri() . '/component-assets';

            //define
            $this->define('TNCT_PATH', $path);
            $this->define('TNCT_ASSETS', $assets);
            $this->define('TNCT_VERSION', $version);

            //includes
            $this->tnct_include('/helpers.php');
            $this->tnct_include('/assets.php');
            $this->tnct_include('/layouts.php');
//            $this->tnct_include('/my-functions.php');
//            $this->tnct_include('/woo-functions.php');

            //hook
            add_action('tnct_init', array($this, 'tnct_setup'));
        }

        /**
         * TNCT_Child_Theme define
         *
         * @param $name
         * @param bool $value
         */
        function define($name, $value = true)
        {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        /**
         * TNCT_Child_Theme get path
         *
         * @param string $path
         *
         * @return string
         */
        function get_path($path = '')
        {
            return TNCT_PATH . $path;
        }

        /**
         * TNCT_Child_Theme include
         *
         * @param $file
         */
        function tnct_include($file)
        {
            $path = $this->get_path($file);
            if (file_exists($path)) {
                include_once($path);
            }
        }

        function tnct_setup()
        {
            register_nav_menus(array(
                'primary-menu' => 'Primary menu'
            ));
        }
    }

    new TNCT_Child_Theme();
    do_action('tnct_init');
    
endif;
