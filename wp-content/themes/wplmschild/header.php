<?php
//Header File
$user_role = 'student'; // Change user role here
$contributor = get_role($user_role);
$contributor->add_cap('upload_files');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title>
        <?php echo wp_title('|',true,'right'); ?>
    </title>
    <?php

    $layout = vibe_get_option('layout');
    if(!isset($layout) || !$layout)
        $layout = '';

    wp_head();
    ?>
</head>

<body <?php body_class($layout); ?>>
<div class="chemanhinh"></div>
<div id="ajaxloader" class="disabled"></div>
<div id="global" class="global">
<div class="pagesidebar">
    <div class="sidebarcontent">
        <h2 id="sidelogo">
            <a href="<?php echo vibe_site_url(); ?>"><img src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
        </h2>
        <?php
        $args = apply_filters('wplms-mobile-menu',array(
            'theme_location'  => 'mobile-menu',
            'container'       => '',
            'menu_class'      => 'sidemenu',
            'fallback_cb'     => 'vibe_set_menu',
        ));

        wp_nav_menu( $args );
        ?>
    </div>
    <a class="sidebarclose"><span></span></a>
</div>
<div class="pusher">
<?php
$fix=vibe_get_option('header_fix');
?>
<header class="<?php if(isset($fix) && $fix){echo 'fix';} ?>">
    <!--            Thêm khung nội dung search ở slider-->
    <input type="hidden" id="check_home_url" value=<?php echo get_home_url() ?>>
    <input type="hidden" id="check_current_url" value="<?php echo get_the_permalink(); ?>">
    <div class="container">


        <div id="searchdiv" class="active">
            <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                <div><label class="screen-reader-text" for="s">Search for:</label>
                    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Hit enter to search...','vibe'); ?>" />
                    <?php
                    $course_search=vibe_get_option('course_search');
                    if(isset($course_search) && $course_search)
                        echo '<input type="hidden" value="course" name="post_type" />';
                    ?>
                    <input type="submit" id="searchsubmit" value="Search" />
                </div>
                <div id="search"><i class="icon-search-2"></i></div>
            </form>

        </div>
        <div class="row">
            <div class="col-md-9 col-sm-6 col-xs-12">
                <!--                        <div class="danhsachmenu" style="float: left">-->
                <!--                            <a class="brow-menu"><i class="icon-list-2"></i>--><?php //_e('Danh sách khóa học','wplms-front-end') ?><!--</a>-->
                <!--                        </div>-->
                <?php

                if(is_home()){
                    echo '<h1 id="logo">';
                }else{
                    echo '<h2 id="logo">';
                }
                ?>

                <a href="<?php echo vibe_site_url(); ?>"><img src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                <?php
                if(is_home()){
                    echo '</h1>';
                }else{
                    echo '</h2>';
                }

                $args = apply_filters('wplms-main-menu',array(
                    'theme_location'  => 'main-menu',
                    'container'       => 'nav',
                    'menu_class'      => 'menu',
                    'walker'          => new vibe_walker,
                    'fallback_cb'     => 'vibe_set_menu'
                ));

                $argsInstructor = apply_filters('wpmls-main-menu',array(
                    'theme_location' => 'main-menu',
                    'container' => 'nav',
                    'menu_class' => 'menu',
                    'menu' => 'MenuInstructor',
                    'walker' => new vibe_walker,
                    'fallback_cb' => 'vibe_set_menu'
                ));

                $argsUnLogin = apply_filters('wpmls-main-menu',array(
                    'theme_location' => 'main-menu',
                    'container' => 'nav',
                    'menu_class' => 'menu',
                    'menu' => 'MenuUnLogin',
                    'walker' => new vibe_walker,
                    'fallback_cb' => 'vibe_set_menu'
                ));

                $uid = get_current_user_id();
                $level = get_user_meta($uid,'it_user_level',true);
                if(is_user_logged_in()){
                    if($level == 10 || $level == 1){
                        wp_nav_menu($argsInstructor);
                    }else{
                        wp_nav_menu( $args );
                    }
                }else{
                    wp_nav_menu( $argsUnLogin );
                }

                ?>
            </div>
            <div class="col-md-3 col-sm-6">
                <div id="searchicon"><i class="icon-search-2"></i></div>
                <?php
                if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
                    ?>
                    <ul class="topmenu">
<!--                        <li>--><?php //echo '<a href="'.get_home_url().'/edit-course'.'" ">'. __( 'Create course', 'wplms-front-end' ).'</a>';
//                            ?><!--</li>-->
                        <!--                                    chuông thông báo-->
                        <li class="thongbao"><i class="icon-bell"><span class="sothongbao"><?php do_action('get_new_count_notification_for_user') ?></span></i></li>

                        <li><a href="<?php bp_loggedin_user_link(); ?>" class="smallimg vbplogin"><?php $n=vbp_current_user_notification_count(); echo ((isset($n) && $n)?'<em></em>':''); bp_loggedin_user_avatar( 'type=full' ); ?><span><?php bp_loggedin_user_fullname(); ?></span></a></li>
                        <?php do_action('wplms_header_top_login'); ?>
                        <?php
                        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) { global $woocommerce;
                            ?>
                            <li><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="smallimg vbpcart"><span><?php echo (($woocommerce->cart->cart_contents_count)?'<em>'.$woocommerce->cart->cart_contents_count.'</em>':''); ?></span></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                    <div class="noidungthongbao">
                        <h5>Thông báo</h5>
                        <div class="mask">
                            <!--                                            --><?php //do_action('wpmls_get_notification_for_user') ?>
                        </div>

                        <div class="xemthem">
                            <i class="noidungthongbaoloading icon-refresh glyphicon-refresh-animate"></i>
                            <a href=<?php echo bp_loggedin_user_domain().bp_get_messages_slug()?>> <span>Xem thêm</span></a>
                        </div>
                    </div>
                <?php
                else :
                    ?>
                    <ul class="topmenu">
                       <?php
//                           if(is_user_logged_in()){
//                               if($level == 10 || $level == 1){
//                                  echo '<li><a href="'.get_home_url().'/edit-course'.'" ">'. __( 'Create course', 'wplms-front-end' ).'</a></li>';
//                               }
//                           }else{
//
//                           }
                       ?>
                        <?php   printf( __( '<li><a href="%s" class="vbpregister" title="'.__('Create an account','vibe').'" tabindex="5" >'.__( 'ĐĂNG KÝ','vibe' ).'</a></li> ', 'vibe' ), site_url( BP_REGISTER_SLUG . '/' ) );
                        ?>
                        <li><a href="#login" class="smallimg vbplogin"><span><?php _e('ĐĂNG NHẬP','vibe'); ?></span></a></li>
                        <?php
                        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) { global $woocommerce;
                            ?>
                            <li><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="smallimg vbpcart"><span><?php echo (($woocommerce->cart->cart_contents_count)?'<em>'.$woocommerce->cart->cart_contents_count.'</em>':''); ?></span></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                <?php
                endif;
                ?>

            </div>
            <a id="trigger">
                <span class="lines"></span>
            </a>
        </div>
    </div>
    <!--            Kiểm tra xem là đang đăng nhập hay la chưa để set css với JS-->
    <?php
    $uni = get_current_user_id();
    ?>
    <div id="vibe_bp_login">

        <input type="hidden" id="check_user" value=<?php echo $uni ?>>
        <div style="  position: absolute;float: right;margin-left: 85%; z-index: 9"><a class="btn-close"><i class="icon-x"></i></a></div>
        <?php
        if ( function_exists('bp_get_signup_allowed')){
            the_widget('child_vibe_bp_login',array(),array()); // load form
        }
        ?>
    </div>
    <div class='shadow-login'> </div>

    <!--            <div id="noidung_menu">-->
    <!--                --><?php
    //                $array_menu_doc = (array(
    //                    'theme_location'=>'menu_doc',
    //                    'container_class'=>'menudoc',
    //                    'menu_class'=>'nav_doc',
    //                    'echo'=> true
    //                ));
    //                wp_nav_menu($array_menu_doc);
    //                ?>
    <!---->
    <!--            </div>-->
</header>

<!--        --><?php //if(is_front_page()){ ?>
<!--            <div class="searchslider col-md-12 col-sm-12 col-xs-12">-->
<!--                <h1><b>--><?php //_e('Học hỏi các kỹ năng mới','wpmls_front_end')?><!--</b></h1>-->
<!--                <h1><b>--><?php //_e('Được giảng dạy bởi các chuyên gia','wpmls_front_end')?><!--</b></h1>-->
<!--                <div id="searchdiv" class="active search_slider">-->
<!--                    <form role="search" method="get" id="searchform" action="--><?php //echo home_url( '/' ); ?><!--">-->
<!--                        <div><label class="screen-reader-text" for="s">Search for:</label>-->
<!--                            <input class="noidungsearch" type="text" value="--><?php //the_search_query(); ?><!--" name="s" id="s" placeholder="--><?php //_e('Hit enter to search...','vibe'); ?><!--" />-->
<!--                            --><?php
//                            $course_search=vibe_get_option('course_search');
//                            if(isset($course_search) && $course_search)
//                                echo '<input type="hidden" value="course" name="post_type" />';
//                            ?>
<!--                            <button type="submit" id="searchsubmit" ><i class="icon-search-2"></i></button>-->
<!--                        </div>-->
<!---->
<!--                    </form>-->
<!---->
<!--                </div>-->
<!---->
<!--                <div class="search_bottom_content">-->
<!--                    <h7>--><?php //_e('Khám phá các khóa học về Công Nghệ Thông Tin','wpmls_front_end')?><!--</h7>-->
<!--                </div>-->
<!---->
<!--                <div class="search_content_icon">-->
<!--                    <div class="search-icon-left">-->
<!--                        <div class="icon-left-search">-->
<!--                            <i class="icon-book-open"></i>-->
<!--                        </div>-->
<!---->
<!--                        <div class="content-right">-->
<!--                            <b>--><?php //_e('+1200','wpmls_front_end') ?><!--</b></br>-->
<!--                            --><?php //_e('khóa học','wpmls_front_end') ?>
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="search-icon-left">-->
<!--                        <div class="icon-left-search">-->
<!--                            <i class="icon-user"></i>-->
<!--                        </div>-->
<!---->
<!--                        <div class="content-right">-->
<!--                            <b>--><?php //_e('+5000','wpmls_front_end') ?><!--</b></br>-->
<!--                            --><?php //_e('sinh viên theo học','wpmls_front_end') ?>
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="search-icon-left">-->
<!--                        <div class="icon-left-search">-->
<!--                            <i class="icon-bank-notes"></i>-->
<!--                        </div>-->
<!---->
<!--                        <div class="content-right">-->
<!--                            <b>--><?php //_e('$1200','wpmls_front_end') ?><!--</b></br>-->
<!--                            --><?php //_e('thu nhập mỗi tháng','wpmls_front_end') ?>
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        --><?php //} ?>
