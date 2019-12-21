<?php
    global $cpn_assets;
    $banner_slider = get_field('banner_slider', 'option');
?>
<section class="pvtinh_gdtruonggiang_slider">
    <div class="container">
        <div class="slider-content flex-wrap justify-content-space-between d-flex">
            <div class="main-slider">
                <?php echo do_shortcode('[metaslider id="26"]'); ?>
            </div>
            <div class="right-ads d-flex">
                <div class="ads-item">
                    <a href="<?= $banner_slider['lien_ket_1'] ?>">
                        <img src="<?= $banner_slider['banner_1']['sizes']['medium'] ?>" alt="<?= $banner_slider['lien_ket_1'] ?>">
                    </a>
                </div>
                <div class="ads-item">
                    <a href="<?= $banner_slider['lien_ket_2'] ?>">
                        <img src="<?= $banner_slider['banner_2']['sizes']['medium'] ?>" alt="<?= $banner_slider['lien_ket_2'] ?>">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>