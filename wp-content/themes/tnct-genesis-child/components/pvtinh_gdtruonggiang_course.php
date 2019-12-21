<?php global $cpn_assets; ?>
<section class="pvtinh_gdtruonggiang_course">
    <div class="container">
        <h3 class="title-section">Mang thai</h3>
        <div class="content-course d-flex flex-wrap justify-content-space-between">
            <div class="banner">
                <a href="#">
                    <img src="<?= $cpn_assets ?>images/banner.png" alt="">
                </a>
            </div>
            <div class="list-course d-flex flex-wrap justify-content-space-between">
                <?php for ($i = 0; $i < 8; $i++): ?>
                    <div class="course-item">
                        <div class="box-img">
                            <a href="#">
                                <img class="img-cover" src="<?= $cpn_assets ?>images/course.jpg" alt="">
                            </a>
                        </div>
                        <div class="box-info trans-default">
                            <div class="list-star">
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="sum-rating">4.6</span>
                                <span class="view">(1052)</span>
                            </div>
                            <p class="title-course title title-news"><a href="" title="">[VIP] Hướng dẫn chăm sóc thai phụ trước và sau sinh đúng cách</a></p>

                            <ul class="list-price">
                                <del>978.000đ</del>
                                <span>560.000 đ</span>
                            </ul>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>