<?php
global $cpn_assets;
$cpn_assets = '/wp-content/themes/tnct-genesis-child/component-assets/';
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Cài đặt website',
        'menu_title' => 'Cài đặt website',
        'menu_slug' => 'website-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

//get logo
function tnct_logo()
{
    $web_option = get_field('web_custom', 'option');
    $logo_container = '';
    if (!empty($web_option['main_logo'])) {
        $logo_container .= '<a class="tnct-logo" href="' . get_home_url() . '" title="' . get_bloginfo('name') . '">';
        $logo_container .= '<img src="' . $web_option['main_logo']['url'] . '" alt="' . get_bloginfo('name') . '" />';
        $logo_container .= '</a>';
    } else {
        $logo_container .= '<a class="tnct-logo" href="' . get_home_url() . '" title="' . get_bloginfo('name') . '">';
        $logo_container .= get_bloginfo('name') . '</a>';
    }

    return $logo_container;
}

//get favicon
add_action('wp_head', 'tnct_favicon');
function tnct_favicon()
{
    $web_option = get_field('web_custom', 'option');
    if (!empty($web_option['favicon_img'])) {
        $favicon = $web_option['favicon_img']['url'];
    } else {
        $favicon = get_theme_file_uri() . '/images/favicon.png';
    }
    echo "<link rel=\"shortcut icon\" href=\"" . $favicon . "\" />\n";
}

add_filter('get_the_archive_title', 'tnct_archive_title');
function tnct_archive_title($title)
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }

    return $title;
}
