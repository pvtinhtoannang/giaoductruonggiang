<?php global $cpn_assets ?>
<section class="pvtinh_gdtruonggiang_header">
    <header>
        <div class="container">
            <div class="header-content d-flex justify-content-space-between flex-wrap">
                <div class="logo-header">
                    <a href="#">
                        <img src="<?= $cpn_assets ?>images/logo.png" alt="">
                    </a>
                </div>
                <div class="search-box">
                    <form role="search" method="get" class="search-form" action="<?php //echo home_url( '/' ); ?>">
                        <input type="search" class="search-field"
                               placeholder="<?php //echo esc_attr_x( 'Search …', 'placeholder' ) ?>"
                               value="<?php //echo get_search_query() ?>" name="s"
                               title="<?php //echo esc_attr_x( 'Search for:', 'label' ) ?>"/>

                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="list-action hidden-xs hidden-sm">
                    <div class="box-icons">
                        <span class="count">1</span>
                    </div>
                    <div class="box-icons">
                        <span class="count"></span>
                    </div>
                    <div class="box-icons">
                        <span class="count">5</span>
                    </div>
                </div>

                <div class="list-account  hidden-xs hidden-sm">
                    <ul class="d-flex ">
                        <li><a class="text-center" href="#">Đăng nhập</a></li>
                        <li><a class="text-center" href="#">Đăng ký</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="menu-header">
        <div class="container">
            <nav class="main-menu hidden-xs hidden-sm">
                <ul>
                    <li><a href="#">Mang thai</a></li>
                    <li><a href="">Thai Giáo</a></li>
                    <li>
                        <a href="">Menu Item</a>
                        <ul>
                            <li><a href="#">Mang thai</a></li>
                            <li><a href="">Thai Giáo</a></li>
                            <li><a href="">Menu Item</a></li>
                            <li><a href="">Menu Item</a></li>
                            <li><a href="">Menu Item</a></li>
                            <li><a href="">Menu Item</a></li>
                            <li><a href="">Menu Item</a></li>
                            <li><a href="">Menu Item</a></li>
                        </ul>
                    </li>
                    <li><a href="">Menu Item</a></li>
                    <li><a href="">Menu Item</a></li>
                    <li><a href="">Menu Item</a></li>
                    <li><a href="">Menu Item</a></li>
                    <li><a href="">Menu Item</a></li>
                </ul>
            </nav>

            <div class="menu-mobile d-flex justify-content-space-between align-item-center hidden-md hidden-lg">
                <div class="button-menu">
                    <a href="#nav-mob" class="text-white" id="">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                <div class="right-menu">
                    <div class="list-action">
                        <div class="box-icons">
                            <span class="count">1</span>
                        </div>
                        <div class="box-icons">
                            <span class="count"></span>
                        </div>
                        <div class="box-icons">
                            <span class="count">5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>