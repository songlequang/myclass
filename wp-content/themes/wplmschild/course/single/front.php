<?php

/**
 * The template for displaying Course font
 *
 * Override this template by copying it to yourtheme/course/single/front.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     1.8.1
 */


global $post, $id_post;
$id= get_the_ID();
$id_post = $post->ID;
$post_author_id = get_post_field( 'post_author', $id );
$author_data = get_userdata($post_author_id);

do_action('wplms_course_before_front_main');

if(have_posts()):
    while(have_posts()):the_post();
        ?>
        <!-- Stat : khải -->
        <div class="course_title">
            <?php vibe_breadcrumbs(); ?>
            <br />
            <br />
            <div class="row">
                <?php
                $author_id = get_post_field('post_author',get_the_ID());
                $user_info = get_userdata($author_id);
                $arg = array(
                    'field'   => 'Học vị',
                    'user_id' => $author_id
                );
                ?>
                <div class="col-sm-2 col-md-2 thongtingianvienphiatren">
                    <a href="<?php echo bp_core_get_user_domain($author_id); ?>">
                        <!--                    --><?php //echo get_avatar( $author_id, 100 ) ; ?>
                        <!--                    <br />-->
                        <!--                    --><?php //echo $user_info->nickname; ?>
                        <?php echo bp_course_instructor(); ?>
                        <!--                    <br />-->
                        <!--                    --><?php //echo bp_get_profile_field_data($arg); ?>
                    </a>
                </div>
                <div class="col-sm-10 col-md-10">
                    <h1><?php the_title(); ?></h1><br/>
<!--                    <h6>--><?php //the_excerpt(); ?><!--</h6>-->

                    <div id="item-meta">
                        <?php bp_course_meta_khoa_hoc(); ?>
                        <?php do_action( 'bp_course_header_actions' ); ?>

                        <?php do_action( 'bp_course_header_meta' ); ?>
                        <?php
                        $students_undertaking=array();
                        $students_undertaking = bp_course_get_students_undertaking();
                        $students=get_post_meta(get_the_ID(),'vibe_students',true);
                        $course_curriculum=vibe_sanitize(get_post_meta(get_the_ID(),'vibe_course_curriculum',false));
                        $units=array();
                        if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
                            foreach($course_curriculum as $key=>$curriculum){
                                if(is_numeric($curriculum)){
                                    $units[]=$curriculum;
                                }
                            }
                        }
                        ?>
                        <br />
                        <div class="thongtinchungkhoahoc row col-md-10">
                            <div class="col-md-4">
                                <div>
                <span>
                    <i class="iconthongtinchungkhoahoc icon-users"></i>
                </span>
                                </div>
                                <div>
                                    <?php echo __('Học viên: ').$students; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div>
            <span>
                <i class="iconthongtinchungkhoahoc icon-clock"></i>
            </span>
                                </div>
                                <div>
                                    <?php echo __('Thời gian: ').tongthoigianvideokhoahoc(get_the_ID()); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div>
            <span>
                <i class="iconthongtinchungkhoahoc icon-text-document"></i>
            </span>
                                </div>
                                <div>
                                    <?php echo __('Bài học: ').count($units); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <br />

            <!-- Edit -->
            <!--<h3><a href="<?php /*bp_course_permalink(); */?>" title="<?php /*bp_course_name(); */?>" itemprop="name"><?php /*bp_course_name(); */?></a></h3>
-->
            <?php do_action( 'bp_before_course_header_meta' ); ?>

<!--            <div id="item-meta">-->
<!--                --><?php //bp_course_meta_khoa_hoc(); ?>
<!--                --><?php //do_action( 'bp_course_header_actions' ); ?>
<!---->
<!--                --><?php //do_action( 'bp_course_header_meta' ); ?>
<!--                --><?php
//                $students_undertaking=array();
//                $students_undertaking = bp_course_get_students_undertaking();
//                $students=get_post_meta(get_the_ID(),'vibe_students',true);
//                $course_curriculum=vibe_sanitize(get_post_meta(get_the_ID(),'vibe_course_curriculum',false));
//                $units=array();
//                if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
//                    foreach($course_curriculum as $key=>$curriculum){
//                        if(is_numeric($curriculum)){
//                            $units[]=$curriculum;
//                        }
//                    }
//                }
//                ?>
<!--                <br />-->
<!--                <div class="thongtinchungkhoahoc row col-md-10">-->
<!--                    <div class="col-md-4">-->
<!--                        <div>-->
<!--                <span>-->
<!--                    <i class="iconthongtinchungkhoahoc icon-users"></i>-->
<!--                </span>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            --><?php //echo __('Học viên: ').$students; ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div>-->
<!--            <span>-->
<!--                <i class="iconthongtinchungkhoahoc icon-clock"></i>-->
<!--            </span>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            --><?php //echo __('Thời gian: ').tongthoigianvideokhoahoc(get_the_ID()); ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div>-->
<!--            <span>-->
<!--                <i class="iconthongtinchungkhoahoc icon-text-document"></i>-->
<!--            </span>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            --><?php //echo __('Bài học: ').count($units); ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <div class="students_undertaking">
            <?php
            $students_undertaking=array();
            $students_undertaking = bp_course_get_students_undertaking();
            $students=get_post_meta(get_the_ID(),'vibe_students',true);

            echo '<strong>'.$students.__(' STUDENTS ENROLLED','vibe').'</strong>';

            echo '<ul>';
            $i=0;
            foreach($students_undertaking as $student){
                $i++;
                echo '<li>'.get_avatar($student).'</li>';
                if($i>5)
                    break;
            }
            echo '</ul>';
            ?>
        </div>
        <!-- End: khải -->


        <div class="btn-trailer row">
            <div class="course-video col-md-8 col-sm-8">
                <?php
                $noidung= get_the_content();
                $iframedau = strpos($noidung,'<iframe>');
                $iframecuoi = strpos($noidung,'</iframe>');
                $video = substr($noidung,$iframedau+13,$iframecuoi-4);
                echo $video;
                ?>
            </div>

            <div class="col-md-4 col-sm-4">
                <div class="widget pricing">
                    <?php nuthanhtoan(); ?>
                    <?php the_course_details(); ?>
                </div>
                <!-- End Khải -->
                <?php
                $sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="clear" style="border: 1px solid #ccc;margin: 20px 0 50px 0"></div>
        <?php
        do_action('wplms_before_course_description');
        ?>
        <div class="course_description row" itemprop="description">
            <div class="col-md-8 col-sm-8">
                <h4><b><?php _e('Mô tả khóa học','vibe')?></b></h4>
                <div class="small_desc">
                    <?php
                    $more_flag = 1;
                    $content=get_the_content();

                    $vitricuoinoidung = strripos($content,"</iframe>");
                    $content = substr($content,$vitricuoinoidung+25);

                    $middle=strpos( $post->post_content, '<!--more-->' );
                    if($middle){
                        echo apply_filters('the_content',substr($content, 0, $middle));
                    }else{
                        $limit=apply_filters('wplms_course_excerpt_limit',1200);
                        $middle = strrpos(substr($content, 0, $limit), " ");

                        if(strlen($content) < $limit){
                            $more_flag = 0;
                        }
                        $check_vc=strpos( $post->post_content, '[vc_row]' );
                        if ( isset($check_vc) ) {
                            $more_flag=0;
                            echo apply_filters('the_content',$content);
                        }else{
                            echo apply_filters('the_content',substr($content, 0, $middle));
                        }
                    }
                    ?>
                    <?php
                    if($more_flag)
                        echo '<a href="#" id="more_desc" class="link" data-middle="'.$middle.'">'.__('READ MORE','vibe').'</a>';
                    ?>
                </div>
                <?php if($more_flag){ ?>
                    <div class="full_desc">
                        <?php
                        echo apply_filters('the_content',substr($content, $middle,-1));
                        ?>
                        <?php
                        echo '<a href="#" id="less_desc" class="link">'.__('LESS','vibe').'</a>';
                        ?>
                    </div>
                <?php
                }
                ?>
                <h4><b><?php echo __('Yêu cầu khóa học ?','vibe'); ?></b></h4>
                <ol>
                    <?php
                    $muctieu1 = get_post_meta(get_the_ID(),"muctieu1",true);
                    $mt1 = explode("[)",$muctieu1); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
                    for($i=0;$i<count($mt1)-1;$i++)
                    {
                        echo '<li>
                            '.$mt1[$i].'
                            </li>';
                    }
                    ?>
                </ol>
                <h4><b><?php echo __('Học xong khóa học này bạn có thể ?','vibe'); ?></b></h4>
                <ol>
                    <?php
                    $muctieu2=get_post_meta(get_the_ID(),"muctieu2",true);
                    $mt2 = explode("[)",$muctieu2); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
                    for($i=0;$i<count($mt2)-1;$i++)
                    {
                        echo '<li>
                            '.$mt2[$i].'
                            </li>';
                    }
                    ?>
                </ol>
                <h4><b><?php echo __('Ai có thể học khóa học này ?','vibe'); ?></b></h4>
                <ol>
                    <?php
                    $muctieu3=get_post_meta(get_the_ID(),"muctieu3",true);
                    $mt3 = explode("[)",$muctieu3); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
                    for($i=0;$i<count($mt3)-1;$i++)
                    {
                        echo '<li>
                            '.$mt3[$i].'
                          </li>';
                    }
                    ?>
                </ol>
                <br/>
                <?php locate_template( array( 'course/single/curriculum.php'  ), true ); ?>

                <div class="thongtingianvien row">
                    <h4>Thông tin về giảng viên</h4>
                    <?php do_action('getthongtingiangvien')?>
                </div>

            </div>

            <div class="col-md-4 col-sm-4">
                <?php
                echo '<h5><b>';
                echo _e('Các khóa học được học viên quan tâm','vibe');
                echo '</b></h5>';
                echo '<div class="khoahoclienquan">';
                echo '<ul>';
                echo do_action('hienthikhoahoclienquan',get_the_ID());
                echo '</ul>';
                echo '</div>';

                ?>
            </div>

        </div>
        <!--    --><?php
//    do_action('wplms_after_course_description');
//    ?>

        <div class="row">
            <div class="col-md-8 course_reviews">
                <?php
                comments_template('/course-review.php',true);
                ?>
            </div>
        </div>

        <div class="khoahocgiangvien row">
            <h5><?php _e('Những khóa học của giảng viên '.$author_data->display_name,'vibe')?></h5>
            <div class="col-md-8 col-sm-8">
                <span style="display: none"></span>
                <?php do_action('hienthikhoahoccuagiangvien',$post_author_id) ?>
            </div>
        </div>

    <?php
    endwhile;
endif;
?>

<script>
    jQuery(document).ready(function($){
        $('.shadow-login').show();
        $('body').css('overflow', 'hidden');
        $('#ajaxloader').show();
        if($('.widget.pricing input[id="continue_course"]').val()){
            $('.widget.pricing input[id="continue_course"]').parent().submit();
        }else{
            $('#ajaxloader').hide();
            $('.shadow-login').hide();
            $('body').css('overflow-y', 'scroll');
        }
    });
</script>
