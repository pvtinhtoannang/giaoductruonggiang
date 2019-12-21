<?php
global $cpn_assets;
?>
<section class="pvtinh_gdtruonggiang_hotcourse">
    <div class="container">
        <h3 class="title-section text-center">Được quan tâm nhiều nhất</h3>
        <div class="list-hot-course">
            <?php for ($i = 0; $i < 8; $i++): ?>
                <div class="course-item hot before">
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
                        <div class="author d-flex align-item-center">
                            <div class="box-avatar">
                                <img src="<?= $cpn_assets ?>images/author.jpg" alt="">
                            </div>

                            <span>Th.S Ngô Tuấn Hà</span>
                        </div>
                        <ul class="list-price">
                            <del>978.000đ</del>
                            <span>560.000 đ</span>
                        </ul>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
