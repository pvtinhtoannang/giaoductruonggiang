<?php
/**
 * User: Minh Nhut
 * Date: 2019-07-26
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);

//add SKU under title
if (!function_exists('sku_undertitle')):
    function sku_undertitle()
    {
        global $product;
        if (!empty($product->get_sku())) {
            ?>
            <p class="custom-sku"><i class="fa fa-check-square-o" aria-hidden="true"></i> Mã sản phẩm:
                <span><?= $product->get_sku(); ?></span></p>
        <?php } else {
            ?>
            <p class="custom-sku"><i class="fa fa-check-square-o" aria-hidden="true"></i> Không có mã sản phẩm</span>
            </p>
            <?php
        }
    }

    add_action('woocommerce_single_product_summary', 'sku_undertitle', 6);
endif;

if (!function_exists('custom_price')):
    function custom_price()
    {
        global $product;
        $price = $product->get_price();
        $rugular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        if (!empty($price)) {
            if (!empty($sale_price)) {
                ?>
                <p class="custom_price"><?= number_format($price, 0, '.', '.'); ?> <sup>đ</sup>
                    <span class="sale-price"><del><?= number_format($rugular_price, 0, '.', '.'); ?></del> <sup>đ</sup></span>
                </p>
                <?php
            } else {
                ?>
                <p class="custom_price"><?= number_format($price, 0, '.', '.'); ?> <sup>đ</sup></p>
                <?php
            }
        } else {
            ?>
            <p class="custom_price">Giá: Liên hệ</p>
            <?php
        }
    }

    add_action('woocommerce_single_product_summary', 'custom_price', 10);
endif;

if (!function_exists('woo_get_stock_status')):
    function woo_get_stock_status()
    {
        global $product;
        if (trim($product->get_stock_status()) == 'outofstock') {
//            echo "hết hàng";
            ?>
            <p class="stock_status">Tình trạng: <span>Hết hàng</span></p>
            <?php
        } else {
//            echo "còn hàng";
            ?>
            <p class="stock_status">Tình trạng: <span>Còn hàng</span></p>
            <?php
        }
    }

    add_action('woocommerce_single_product_summary', 'woo_get_stock_status', 15);
endif;

if (!function_exists('custom_excerpt')):
    function custom_excerpt()
    {
        $my_excerpt = get_the_excerpt();
        if ('' != $my_excerpt) {
            ?>
            <p class="custom_excerpt">Mô tả: <?= $my_excerpt; ?></p>
            <?php
        } else {
            ?>
            <p class="custom_excerpt">Nội dung đang được quản trị viên cập nhật</p>
            <?php
        }
    }

    add_action('woocommerce_single_product_summary', 'custom_excerpt', 20);
endif;
if (!function_exists('mn_woo_attribute')):
    function mn_woo_attribute()
    {
        global $product;
        $attributes = $product->get_attributes();
        if (!$attributes) {
            echo "Sản phẩm này chưa được cập nhật thuộc tính";
            return;
        }
        $display_result = '';
        foreach ($attributes as $i => $attribute) {
            if ($attribute->get_variation()) {
                continue;
            }
            $name = $attribute->get_name();
            if ($attribute->is_taxonomy()) {
                $terms = wp_get_post_terms($product->get_id(), $name, 'all');
                $cwtax = $terms[0]->taxonomy;
                $cw_object_taxonomy = get_taxonomy($cwtax);
                if (isset ($cw_object_taxonomy->labels->singular_name)) {
                    $tax_label = $cw_object_taxonomy->labels->singular_name;
                } elseif (isset($cw_object_taxonomy->label)) {
                    $tax_label = $cw_object_taxonomy->label;
                    if (0 === strpos($tax_label, 'Product ')) {
                        $tax_label = substr($tax_label, 8);
                    }
                }
                $display_result .= '<li><label class="attribute_title">' . $tax_label . ': </label>';
                $tax_terms = array();
                foreach ($terms as $term) {
                    $single_term = esc_html($term->name);
                    array_push($tax_terms, $single_term);
                }
                $display_result .= implode(', ', $tax_terms) . '</li>';
            } else {
                $display_result .= '<li><span class="attribute_title">' . $name . ': </span>';
                $display_result .= esc_html(implode(', ', $attribute->get_options())) . '</li>';
            }
        }
        echo "<ul class='mn_woo_attribute'>" . $display_result . "</ul>";
    }

//    add_action('woocommerce_single_product_summary', 'mn_woo_attribute', 25);
endif;

if (!function_exists('mn_woo_share_button')):
    function mn_woo_share_button()
    {
        ?>
        <div class="addthis_inline_share_toolbox"></div>
        <?php
    }

    add_action('woocommerce_single_product_summary', 'mn_woo_share_button', 30);
endif;

if (!function_exists('product_rating')):
    function product_rating()
    {
        global $product;
        $max_star = 5;
        $star = $product->get_average_rating();
        ?>
        <div class="product-info">
            <?php
            if ($star > 0) { ?>
                <div class="rating">
                    <?php for ($i = 0; $i < $star; $i++) { ?>
                        <i class="fa fa-star" aria-hidden="true"></i>
                    <?php } ?>
                    <span class="line">|</span>
                </div>
            <?php } ?>
        </div>
        <?php

    }

    add_action('woocommerce_single_product_summary', 'product_rating', 6);
endif;

if (!function_exists('woo_product_thumbnail')):
    function woo_product_thumbnail()
    {
        global $product;
        $attachment_ids = $product->get_gallery_image_ids();
        ?>
        <div class="mn-woo-product-image">
            <div class="mn-image-itemt item_main">
                <a data-fancybox="gallery_gr" class="thumbnail-item"
                   href="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full') ?>">
                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full') ?>"
                         alt="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full') ?>"></a>
            </div>
            <?php
            if ($attachment_ids && $product->get_image_id()) {
                foreach ($attachment_ids as $attachment_id) {
                    $image = wp_get_attachment_image_src($attachment_id);
                    ?>
                    <div class="mn-image-itemt">
                        <a data-fancybox="gallery_gr" class="thumbnail-item" href="<?= $image[0]; ?>"><img
                                src="<?= $image[0]; ?>"></a>
                    </div>

                    <?php
                }
            }
            ?>
        </div>
        <div class="mn-woo-product-thumbnail">
            <div class="mn-thumbnail-itemt item_main">
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?>"
                     alt="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?>">
            </div>
            <?php
            if ($attachment_ids && $product->get_image_id()) {
                foreach ($attachment_ids as $attachment_id) {
                    $image = wp_get_attachment_image_src($attachment_id);
                    ?>
                    <div class="mn-thumbnail-itemt">
                        <img src="<?= $image[0]; ?>">
                    </div>

                    <?php
                }
            }
            ?>
        </div>
        <?php
    }

    add_action('woocommerce_product_thumbnails', 'woo_product_thumbnail', 20);
endif;

if (!function_exists('mn_woo_product_data')):
    function mn_woo_product_data()
    {
        global $product;
        ?>
        <div class="mn-woo-product-data">

            <div class="question-container" id="question-container">

                <div class="question-box">
                    <div class="question-title collapsed" data-toggle="collapse" data-parent="#question-container"
                         href="#chi_tiet_sp">
                        <h3 class="title">Chi tiết sản phẩm</h3>
                        <i class="box-open fa fa-caret-down"></i>
                        <i class="box-close fa fa-caret-up"></i>
                    </div>
                    <div class="question-content collapse in" id="chi_tiet_sp">
                        <div class="content">
                            <?= wpautop(get_the_content($product->get_id())); ?>
                        </div>
                    </div><!-- / content -->
                </div>

                <div class="question-box">
                    <div class="question-title collapsed" data-toggle="collapse" data-parent="#question-container"
                         href="#thuoc_tinh_sp">
                        <h3 class="title">Thuộc tính sản phẩm</h3>
                        <i class="box-open fa fa-caret-down"></i>
                        <i class="box-close fa fa-caret-up"></i>
                    </div>
                    <div class="question-content collapse" id="thuoc_tinh_sp">
                        <div class="content">
                            <?php mn_woo_attribute(); ?>
                        </div>
                    </div><!-- / content -->
                </div>

                <div class="question-box">
                    <div class="question-title collapsed" data-toggle="collapse" data-parent="#question-container"
                         href="#danh_gia">
                        <h3 class="title">Đánh giá</h3>
                        <i class="box-open fa fa-caret-down"></i>
                        <i class="box-close fa fa-caret-up"></i>
                    </div>
                    <div class="question-content collapse" id="danh_gia">
                        <div class="content">
                            <div id="reviews">
                                <div id="comments">
                                        <span><?php
                                            if (get_option('woocommerce_enable_review_rating') === 'yes' && ($count = $product->get_rating_count()))
                                                printf(_n('%s đánh giá cho %s', '%s đánh giá cho %s', $count, 'woocommerce'), $count, get_the_title());
                                            else
                                                _e('Đánh giá', 'woocommerce');
                                            ?></span>
                                    <?php $args = array('post_id' => $product->get_id());
                                    $comments = get_comments($args); ?>
                                    <?php if (!empty($comments)) : ?>

                                        <ol class="commentlist">
                                            <?php wp_list_comments(array('callback' => 'woocommerce_comments'), $comments); ?>
                                        </ol>

                                        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) :
                                            echo '<nav class="woocommerce-pagination">';
                                            paginate_comments_links(apply_filters('woocommerce_comment_pagination_args', array(
                                                'prev_text' => '&larr;',
                                                'next_text' => '&rarr;',
                                                'type' => 'list',
                                            )));
                                            echo '</nav>';
                                        endif; ?>

                                    <?php else : ?>

                                        <p class="woocommerce-noreviews"><?php _e('There are no reviews yet.', 'woocommerce'); ?></p>

                                    <?php endif; ?>
                                </div>

                                <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>

                                    <div id="review_form_wrapper">
                                        <div id="review_form">
                                            <?php
                                            $commenter = wp_get_current_commenter();

                                            $comment_form = array(
                                                'title_reply' => __('Nếu thấy thích, hãy đánh giá cho sản phẩm này', 'woocommerce'),
                                                'title_reply_to' => __('Trả lời', 'woocommerce'),
                                                'comment_notes_before' => '',
                                                'comment_notes_after' => '',
                                                'fields' => array(
                                                    'author' => '<p class="comment-form-author">' . '<label for="author">' . __('Name', 'woocommerce') . ' <span class="required">*</span></label> ' .
                                                        '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" aria-required="true" /></p>',
                                                    'email' => '<p class="comment-form-email"><label for="email">' . __('Email', 'woocommerce') . ' <span class="required">*</span></label> ' .
                                                        '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" aria-required="true" /></p>',
                                                ),
                                                'label_submit' => __('Submit', 'woocommerce'),
                                                'logged_in_as' => '',
                                                'comment_field' => ''
                                            );

                                            if (get_option('woocommerce_enable_review_rating') === 'yes') {
                                                $comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . __('Điểm đánh giá', 'woocommerce') . '</label><select name="rating" id="rating">
                                                            <option value="">' . __('Rate&hellip;', 'woocommerce') . '</option>
                                                            <option value="5">' . __('Perfect', 'woocommerce') . '</option>
                                                            <option value="4">' . __('Good', 'woocommerce') . '</option>
                                                            <option value="3">' . __('Average', 'woocommerce') . '</option>
                                                            <option value="2">' . __('Not that bad', 'woocommerce') . '</option>
                                                            <option value="1">' . __('Very Poor', 'woocommerce') . '</option>
                                                        </select></p>';
                                            }

                                            $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __('Nội dung', 'woocommerce') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';

                                            comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
                                            ?>
                                        </div>
                                    </div>

                                <?php else : ?>

                                    <p class="woocommerce-verification-required"><?php _e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?></p>

                                <?php endif; ?>

                            </div>
                            <h3 class="note">Nếu không có tài khoản bạn có thể bình luận bằng Facebook</h3>
                            <div class="fbcomment">
                                <div id="fb-root"></div>
                                <script async defer
                                        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6"></script>
                                <div class="fb-comments" data-width="100%"
                                     data-href="<?php echo get_permalink($product->get_id()) ?>"
                                     data-numposts="5"></div>
                            </div>
                        </div>
                    </div><!-- / content -->
                </div>

            </div>
            <div class="mn-woo-related-products">
                <?php
                $cats = wp_get_post_terms(get_the_ID(), 'product_cat');
                if ($cats) {
                    $related_posts = get_posts(array(
                        'posts_per_page' => 8,
                        'post_type' => 'product',
                        'exclude' => get_the_ID(),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $cats[0]->term_id
                            )
                        ),
                        'orderby' => 'rand'
                    ));
                }
                ?>
                <?php if (!empty($related_posts)) : ?>
                    <div class="title-container">
                        <p class="data-title">Sản phẩm liên quan</p>
                        <span class="underline"></span>
                    </div>
                <?php endif; ?>
                <div class="related-product-container">
                    <div class="related-products-slick">
                        <?php
                        if (!empty($related_posts)) :?>
                            <?php foreach ($related_posts as $product) :
                                $pro_info = wc_get_product($product->ID);
                                $pro_price = $pro_info->get_price();
                                ?>
                                <div class="product-box">
                                    <div class="box-img">
                                        <a href="<?php echo get_permalink($product->ID) ?>"
                                           title="<?php echo get_the_title($product->ID) ?>">
                                            <img src="<?php echo get_the_post_thumbnail_url($product->ID, 'medium') ?>"
                                                 class="img-responsive" alt="<?php echo get_the_title($product->ID) ?>">
                                        </a>
                                    </div>
                                    <h3 class="product-title"><a
                                            href="<?php echo get_permalink($product->ID) ?>"><?php echo get_the_title($product->ID) ?></a>
                                    </h3>
                                    <?php if (!empty($pro_price)) { ?>
                                        <p class="price"><?= number_format($pro_price, 0, '.', '.'); ?>
                                            <sup>đ</sup></p>
                                    <?php } else { ?>
                                        <p class="price">Liên hệ</p>
                                    <?php } ?>
                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    add_action('woocommerce_after_single_product_summary', 'mn_woo_product_data', 10);
endif;
