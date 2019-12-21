<?php
// if (!class_exists('pvtinh_gdtruonggiang_footer')) {
//     class pvtinh_gdtruonggiang_footer extends TNCT_Component
//     {
//         public function render()
//         {
global $cpn_assets;
?>
    <section class="pvtinh_gdtruonggiang_footer">
        <footer>
            <div class="container">
                <div class="logo-footer">
                    <a href="#">
                        <img src="<?= $cpn_assets ?>images/logo-ft.png" alt="">
                    </a>
                </div>

                <div class="footer-content d-flex flex-wrap">
                    <div class="footer-item">
                        <h4 class="title-footer">LIÊN HỆ GIẢI ĐÁP YÊU CẦU</h4>

                        <ul class="information">
                            <li class="before">Hotline: 0909 090 999</li>
                            <li class="before">Email: edukid@gmail.com</li>
                        </ul>

                        <div class="bct">
                            <a href="#" target="_blank">
                                <img src="<?= $cpn_assets ?>images/bct.png" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="footer-item">
                        <h4 class="title-footer">ĐIỀU KHOẢN SỬ DỤNG</h4>

                        <ul class="menu-footer">
                            <li><a href="#">Điều khoản sử dụng</a></li>
                            <li><a href="#">Quy chế hoạt động</a></li>
                            <li><a href="#">Chính sách bảo mật</a></li>
                            <li><a href="#">Trung tâm CSKH</a></li>
                            <li><a href="#">Chính sách hoàn tiền</a></li>
                        </ul>
                    </div>
                    <div class="footer-item">
                        <h4 class="title-footer">DANH MỤC</h4>

                        <ul class="menu-footer">
                            <li><a href="#">Mang thai</a></li>
                            <li><a href="#">Thai giáo</a></li>
                            <li><a href="#">Nuôi con</a></li>
                            <li><a href="#">Giáo dục sớm</a></li>
                            <li><a href="#">Sản phẩm</a></li>
                            <li><a href="#">Dịch vụ trông trẻ</a></li>
                            <li><a href="#">Tài liệu</a></li>
                            <li><a href="#">Khoá học</a></li>
                        </ul>

                    </div>

                    <div class="footer-item">
                        <div class="fanpage-fb">
                            <div id="fb-root"></div>
                            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v5.0"></script>
                            <div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="timeline"
                                 data-width="" data-height="180" data-small-header="false" data-adapt-container-width="true"
                                 data-hide-cover="false" data-show-facepile="true">
                                <blockquote cite="https://www.facebook.com/facebook" class="fb-xfbml-parse-ignore"><a
                                            href="https://www.facebook.com/facebook">Facebook</a></blockquote>
                            </div>
                        </div>
                    </div>
                    <div class="footer-item">
                        <div class="videos-iframe">

                        </div>
                    </div>

                </div>
            </div>
        </footer>
        <div class="copyright text-center text-white">
            2019 © GIAO DUC TRUONG GIANG All Rights Reserved. Designed by <a href="https://toannangcantho.com/" class="text-white" target="_blank " title="Toàn Năng Cần Thơ">Toan Nang Can Tho</a>
        </div>
    </section>
<?php
//         }
//     }
// }