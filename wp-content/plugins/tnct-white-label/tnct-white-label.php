<?php
/*
Plugin Name: TNCT White Label
Plugin URI: http://toannangcantho.com/
Description: White Label WordPress CMS
Version: 2.0.1
Author: TN Developer
Author URI: http://toannangcantho.com/
*/

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('TNCT_White_Label')) :
    class TNCT_White_Label
    {

        /**
         * TNCT_White_Label constructor.
         */
        function __construct()
        {
            $this->initialize();
        }

        /**
         * TNCT_White_Label initialize.
         */
        function initialize()
        {
            //vars
            $path = plugin_dir_url(__FILE__);
            $this->define('TNCT_WL_PATH', $path);

            //remove hook
            remove_action('welcome_panel', 'wp_welcome_panel');

            //hook action
            add_action('login_head', array($this, 'tnct_admin_favicon'));
            add_action('admin_head', array($this, 'tnct_admin_favicon'));
            add_action('admin_head', array($this, 'tnct_welcome_remove_help_tabs'));
            add_action('login_enqueue_scripts', array($this, 'tnct_login_enqueue_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'tnct_admin_head_enqueue_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'tnct_custom_dashboard_scripts'));
            add_action('admin_menu', array($this, 'tnct_disable_dashboard_widgets'));
            add_action('welcome_panel', array($this, 'tnct_welcome_panel_custom'));

            //filter
            add_filter('screen_options_show_screen', array($this, 'tnct_welcome_remove_screen_options'));
            add_filter('admin_footer_text', array($this, 'tnct_footer_admin'), 11);
            add_filter('update_footer', array($this, 'tnct_footer_update'), 11);
            add_filter('login_headertext', array($this, 'tnct_login_logo_url_title'));
            add_filter('login_headerurl', array($this, 'tnct_login_logo_url'));
        }

        /**
         * TNCT_White_Label define
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

        function tnct_admin_favicon()
        {
            $favicon = TNCT_WL_PATH . 'images/favicon.png';
            echo '<link rel="shortcut icon" href="' . $favicon . '" />';
        }

        function tnct_login_enqueue_scripts()
        {
            //login css
            wp_enqueue_style('tnct-login-style', TNCT_WL_PATH . 'css/custom-login-page.css');
        }

        function tnct_admin_head_enqueue_scripts()
        {
            wp_enqueue_style('tnct-admin-interface', TNCT_WL_PATH . 'css/admin-interface.css');
        }

        function tnct_custom_dashboard_scripts()
        {
            if (!current_user_can('administrator')) {
                wp_enqueue_style('tnct-dashboard-custom', TNCT_WL_PATH . 'css/custom-dashboard.css');
            }
        }

        function tnct_welcome_remove_help_tabs()
        {
            if (!current_user_can('administrator')) {
                $screen = get_current_screen();
                $screen->remove_help_tabs();
            }
        }

        function tnct_welcome_remove_screen_options()
        {
            $screen = get_current_screen();
            if ($screen->id == 'dashboard') {
                if (!current_user_can('administrator')) {
                    return false;
                }
            }

            return true;
        }

        function tnct_disable_dashboard_widgets()
        {
            if (!current_user_can('administrator')) {
                remove_meta_box('dashboard_right_now', 'dashboard', 'normal');// Remove "At a Glance"
                remove_meta_box('dashboard_activity', 'dashboard', 'normal');// Remove "Activity" which includes "Recent Comments"
                remove_meta_box('dashboard_quick_press', 'dashboard', 'side');// Remove Quick Draft
                remove_meta_box('dashboard_primary', 'dashboard', 'core');// Remove WordPress Events and News
            }
        }

        function tnct_footer_admin()
        {
            echo '<span id="footer-thankyou">Cảm ơn bạn đã sử dụng dịch vụ của <a href="http://toannangcantho.com/" target="_blank">Toàn Năng Cần Thơ</a></span>';
        }

        function tnct_footer_update()
        {
            echo '<span>Phiên bản 3.0.0</span>';
        }

        function tnct_welcome_panel_custom()
        {
            ?>
            <div class="welcome-panel-content">
                <h2>Xin chào! Bạn đã đăng nhập vào khu vực Quản trị của Toàn Năng Cần Thơ!</h2>
                <div class="welcome-panel-column-container">
                    <div class="welcome-panel-column">
                        <h3>Các Bước Tiếp Theo</h3>
                        <ul>
                            <?php if ('page' == get_option('show_on_front') && !get_option('page_for_posts')) : ?>
                                <li><?php printf('<a href="%s" class="welcome-icon welcome-edit-page">' . __('Edit your front page') . '</a>', get_edit_post_link(get_option('page_on_front'))); ?></li>
                                <li><?php printf('<a href="%s" class="welcome-icon welcome-add-page">' . __('Add additional pages') . '</a>', admin_url('post-new.php?post_type=page')); ?></li>
                            <?php elseif ('page' == get_option('show_on_front')) : ?>
                                <li><?php printf('<a href="%s" class="welcome-icon welcome-edit-page">' . __('Edit your front page') . '</a>', get_edit_post_link(get_option('page_on_front'))); ?></li>
                                <li><?php printf('<a href="%s" class="welcome-icon welcome-add-page">' . __('Add additional pages') . '</a>', admin_url('post-new.php?post_type=page')); ?></li>
                                <li><?php printf('<a href="%s" class="welcome-icon welcome-write-blog">' . __('Add a blog post') . '</a>', admin_url('post-new.php')); ?></li>
                            <?php else : ?>
                                <li><?php printf('<a href="%s" class="welcome-icon welcome-write-blog">' . __('Write your first blog post') . '</a>', admin_url('post-new.php')); ?></li>
                                <li><?php printf('<a href="%s" class="welcome-icon welcome-add-page">' . __('Add an About page') . '</a>', admin_url('post-new.php?post_type=page')); ?></li>
                                <li><a href="<?= home_url('/wp-admin/admin.php?page=website-settings'); ?>"
                                       class="welcome-icon welcome-setup-home">Cài đặt trang chủ</a></li>
                            <?php endif; ?>
                            <li><?php printf('<a href="%s" class="welcome-icon welcome-view-site">' . __('View your site') . '</a>', home_url('/')); ?></li>
                        </ul>
                    </div>

                    <div class="welcome-panel-column-img">
                        <img src="<?= TNCT_WL_PATH; ?>images/tnct.png" alt="">
                    </div>
                </div>
            </div>
            <?php
        }

        function tnct_login_logo_url_title()
        {
            return 'Đẳng cấp tạo nên thương hiệu';
        }

        function tnct_login_logo_url()
        {
            return 'https://toannangcantho.com';
        }
    }

    new TNCT_White_Label();
endif;
