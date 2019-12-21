<?php

add_action('genesis_before', function () {
});

add_action('genesis_before_header', function () {
});

add_action('genesis_header', function () {
    get_template_part('components/pvtinh_gdtruonggiang_header');
});

add_action('genesis_after_header', function () {

});

add_action('genesis_before_content', function () {
});

add_action('genesis_before_loop', function () {
});

add_action('genesis_loop', function () {
    if (is_home()) {
        get_template_part('components/pvtinh_gdtruonggiang_slider');
    } else if (is_single() && !is_woocommerce()) {
        the_content();
    } else if (is_single() && is_woocommerce()) {
        get_template_part('components/pvtinh_gdtruonggiang_slider');
    } else if (is_page()) {
        echo "page";
    } else if (is_404()) {
        echo "is_404";
    } else if (is_search()) {
        echo "is_search";
    } else if (is_archive() && !is_woocommerce()) {
        echo "archive";
    } else if (is_archive() && is_woocommerce()) {
        echo "archive product";
    }
});

add_action('loop_start', function () {

});

add_action('genesis_before_entry', function () {
});

add_action('genesis_before_sidebar_widget_area', function () {
});

add_action('genesis_after_sidebar_widget_area', function () {
});

add_action('genesis_entry_header', function () {
});

add_action('genesis_entry_content', function () {
});

add_action('genesis_entry_footer', function () {
});

add_action('genesis_after_entry', function () {
});

add_action('loop_end', function () {
});

add_action('genesis_after_endwhile', function () {
});

add_action('genesis_after_loop', function () {
});

add_action('genesis_after_content', function () {
});

add_action('genesis_after_content_sidebar_wrap', function () {
});

add_action('genesis_before_footer', function () {
});

add_action('genesis_footer', function () {
    echo "genesis_footer";
});

add_action('genesis_after_footer', function () {
});

add_action('genesis_after', function () {

});
