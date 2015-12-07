
<?php
/**
 * Template Name: Start Course Page
 */

// COURSE STATUS :
// 0 : NOT STARTED
// 1: STARTED
// 2 : SUBMITTED
// > 2 : EVALUATED

// VERSION 1.8.4 NEW COURSE STATUSES
// 1 : START COURSE
// 2 : CONTINUE COURSE
// 3 : FINISH COURSE : COURSE UNDER EVALUATION
// 4 : COURSE EVALUATED
add_action( 'wp_enqueue_scripts', 'load_wp_media_files' );
function load_wp_media_files() {
    wp_enqueue_media();
}
do_action('wplms_before_start_course');

get_header('buddypress');

do_action('wplms_start_course');

$user_id = get_current_user_id();

if(isset($_POST['course_id'])){
    $course_id=$_POST['course_id'];
    $coursetaken=get_user_meta($user_id,$course_id,true);
}else if(isset($_COOKIE['course'])){
    $course_id=$_COOKIE['course'];
    $coursetaken=1;
}
if(!isset($course_id) || !is_numeric($course_id))
    wp_die(__('INCORRECT COURSE VALUE. CONTACT ADMIN','vibe'));

$course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
$unit_id = wplms_get_course_unfinished_unit_sau($course_id);

$unit_comments = vibe_get_option('unit_comments');


if ( have_posts() ) : while ( have_posts() ) : the_post();

?>

<section id="content">
<!--Khải edit-->
<div class="menu-unit">
    <div class="row">
        <div class="col-md-6 name-course">
            <?php echo get_the_title($course_id) ?>
        </div>
        <div class="col-md-6">
            <div class="author">
                <?php
                //if(isset($unit_id)){
                //    the_unit_tags($unit_id);
                //    if(is_numeric($unit_id))
                //        the_unit_instructor($unit_id);
                //    else
                        the_unit_instructor($course_id);
                //}
                ?>

            </div>
            <div class="setting-unit-1">
                <span style="cursor: pointer;font-size: 15pt;position: relative;bottom: 8px" title="Hỗ trợ liên hệ Itclass" class="icon-question link_question link_icon"></span>
                <span style="cursor: pointer;padding-left:15px;font-size: 10pt;position: relative;bottom: 10px" title="Thông tin khóa học" class="icon-info link_icon"></span>
                <span style="cursor: pointer;padding-left:15px;font-size: 10pt;position: relative;bottom: 10px" title="Cài đặt khóa học" class="icon-settings link_icon"></span>
                <span style="cursor: pointer;padding-left:15px;font-size: 10pt;position: relative;bottom: 10px" title="Chia sẻ" class="icon-share link_icon"></span>
                <span style="cursor: pointer;padding-left:15px;font-size: 10pt;position: relative;bottom: 10px" title="Nhận xét" class="icon-star link_icon">Viết một nhận xét</span>
            </div>
        </div>
    </div>
</div>

<div id="thongtinkhoahoc" class="anpopupthongtinkhoahoc">
    <div class="thongtinkhoahocx">
        <i class="icon-x danhgia"></i>
    </div>
    <h1>Thông tin khóa học</h1>
    <div class="noidungthongtinkhoahoc">
        <div class="motakhoahoc">
            <?php
            $content_post = get_post($course_id);
            $content=$content_post->post_content;
            $vitricuoinoidung = strripos($content,"</iframe>");
            $content = substr($content,$vitricuoinoidung+25);
            echo $content;
            ?>
        </div>
        <ul class="category-khoahoc">
            <li>
                <h5><?php echo __('Danh Mục:','vibe');?></h5>
                <div><?php
                    $terms = get_the_terms( $course_id, 'course-cat' );
                    foreach ( $terms as $term ) {
                        $category_name=$term->name;
                    }
                    echo $category_name;
                    ?></div>
            </li>
        </ul>
        <h4 class="bordertopttkh"><?php echo __('Yêu cầu khóa học ?','vibe'); ?></h4>
        <ol>
            <?php
            $muctieu1 = get_post_meta($course_id,"muctieu1",true);
            $mt1 = explode("[)",$muctieu1); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
            for($i=0;$i<count($mt1)-1;$i++)
            {
                echo '<li>
                            '.$mt1[$i].'
                            </li>';
            }
            ?>
        </ol>
        <h4 class="bordertopttkh"><?php echo __('Học xong khóa học này bạn có thể ?','vibe'); ?></h4>
        <ol>
            <?php
            $muctieu2=get_post_meta($course_id,"muctieu2",true);
            $mt2 = explode("[)",$muctieu2); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
            for($i=0;$i<count($mt2)-1;$i++)
            {
                echo '<li>
                            '.$mt2[$i].'
                            </li>';
            }
            ?>
        </ol>
        <h4 class="bordertopttkh"><?php echo __('Ai có thể học khóa học này ?','vibe'); ?></h4>
        <ol>
            <?php
            $muctieu3=get_post_meta($course_id,"muctieu3",true);
            $mt3 = explode("[)",$muctieu3); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
            for($i=0;$i<count($mt3)-1;$i++)
            {
                echo '<li>
                            '.$mt3[$i].'
                          </li>';
            }
            ?>
        </ol>
    </div>
</div>

<!--khải end edit-->
<div class="container" style="margin-top: 20px">
<div class="row">
<div class="col-md-7">
<!--   phần  flex-wrapper  -->
<div class="iconmediaplayer">
    <i class="icon-play"></i>
    <span class="unithientai kiemtra" data-id="<?php echo $unit_id; ?>"><?php echo get_the_title($unit_id) ?></span>
    <div class="hms">
        <?php

        $minutes=0;
        $mins = get_post_meta($unit_id,'vibe_duration',true);
        $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60);
        if($mins){
            if($mins > $unit_duration_parameter){
                $hours = floor($mins/$unit_duration_parameter);
                $minutes = $mins - $hours*$unit_duration_parameter;
            }else{
                $minutes = $mins;
            }
            if($mins < 9999){
                if($unit_duration_parameter == 1)
                    echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Minutes','vibe'):'').' '.$minutes.__(' seconds','vibe').'</span>';
                else if($unit_duration_parameter == 60)
                    echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.__(' minutes','vibe').'</span>';
                else if($unit_duration_parameter == 3600)
                    echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Days','vibe'):'').' '.$minutes.__(' hours','vibe').'</span>';
            }

        }

        ?>
    </div>
</div>
<div class="unit_wrap <?php if(isset($unit_comments) && is_numeric($unit_comments)){echo 'enable_comments';} ?>">
<div class="TV row">
<div class="col-md-10">
    <!--Tab header-->
    <div id="unit_content" class="unit_content">
        <div class="tabheader" style="width:77.7%;">
            <span class="title_unit"><?php echo get_the_title($unit_id) ?></span></span>
        </div>
        <div id="unit" class="quiz_title" data-unit="<?php if(isset($unit_id)) echo $unit_id; ?>">
            <?php
            if(isset($unit_id)){
                the_unit_tags($unit_id);
                if(is_numeric($unit_id))
                    the_unit_instructor($unit_id);
                else
                    the_unit_instructor($course_id);
            }
            $minutes=0;
            $mins = get_post_meta($unit_id,'vibe_duration',true);
            $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60);
            $unit_duration = $mins*$unit_duration_parameter;
            do_action('wplms_course_unit_meta');
            echo '<span><i class="icon-clock"></i> '.tofriendlytime($unit_duration).'</span>';
            ?>
            <h1><?php
                if(isset($course_id)){
                    echo get_the_title($unit_id);
                }else{
                    the_title();
                }
                ?></h1>
            <?php
            if(isset($course_id)){
                the_sub_title($unit_id);
            }else{
                the_sub_title();
            }
            ?>
        </div>
        <?php

        if(isset($coursetaken) && $coursetaken && $unit_id !=''){
            if(isset($course_curriculum) && is_array($course_curriculum)){
                the_unit($unit_id);
                if(isset($unit_comments) && is_numeric($unit_comments)){
                    echo "<script>jQuery(document).ready(function($){ $('.unit_content').trigger('load_comments'); });</script>";
                }
            }else{
                echo '<h3>';
                _e('Course Curriculum Not Set.','vibe');
                echo '</h3>';
            }
        }else{
            the_content();
            if(isset($course_id) && is_numeric($course_id)){
                $course_instructions = get_post_meta($course_id,'vibe_course_instructions',true);
                echo apply_filters('the_content',$course_instructions);
            }
        }

        endwhile;
        endif;
        ?>
        <?php
        $units=array();
        if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
            foreach($course_curriculum as $key=>$curriculum){
                if(is_numeric($curriculum)){
                    $units[]=$curriculum;
                }
            }
        }else{
            echo '<div class="error"><p>'.__('Course Curriculum Not Set','vibe').'</p></div>';
        }

        if($unit_id ==''){

            echo  '<div class="unit_prevnext"><div class="col-md-3"></div><div class="col-md-6">
                          '.((isset($done_flag) && $done_flag)?'': '<a href="#" data-unit="'.$units[0].'" class="unit unit_button">'.__('Start Course','vibe').'</a>').
                '</div></div>';
        }else{

            $k = array_search($unit_id,$units);

            if(empty($k)) $k = 0;

            $next=$k+1;
            $prev=$k-1;
            $max=count($units)-1;

            $done_flag=get_user_meta($user_id,$unit_id,true);


            echo '<div class="unit_prevnext">sdfsdf';
            echo '<div class="col-md-2"> <span class="backtocourse" data-id="'.$course_id.'"><i class="icon-arrow-1-left"></i> Trở về khóa học </span></div>';
            echo '<div class="col-md-4">';

            if($prev >=0){
                if(get_post_type($units[$prev]) == 'quiz'){
                    $quiz_status = get_user_meta($user_id,$units[$prev],true);
                    if(!empty($quiz_status))
                        echo '<a href="#" data-unit="'.$units[$prev].'" class="btnprev unit unit_button">'.__('Previous Quiz','vibe').'</a>';
                    else
                        echo '<a href="'.get_permalink($units[$prev]).'" class="btnprev unit_button">'.__('Previous Quiz','vibe').'</a>';
                }else
                    echo '<a href="#" id="prev_unit" data-unit="'.$units[$prev].'" class="btnprev unit unit_button">'.__('Previous Unit','vibe').'</a>';
            }
            echo '</div>';

            echo  '<div class="col-md-4">';
            if(!isset($done_flag) || !$done_flag){
                if(get_post_type($units[($k)]) == 'quiz'){
                    $quiz_status = get_user_meta($user_id,$units[($k)],true);
                    if(is_numeric($quiz_status)){
                        echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                    }else{
                        echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button">'.__('Start Quiz','vibe').'</a>';
                    }
                }else{
                    echo apply_filters('wplms_unit_mark_complete','<a href="#" id="mark-complete" data-unit="'.$units[($k)].'" class="unit_button">'.__('Mark this Unit Complete','vibe').'</a>',$unit_id,$course_id);
                }
            }else{
                if(get_post_type($units[($k)]) == 'quiz'){
                    echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                }
                // If unit does not show anything
            }
            echo '</div>';

            echo  '<div class="col-md-2">';

            $nextflag=1;
            if($next <= $max){
                $nextunit_access = vibe_get_option('nextunit_access');
                if(isset($nextunit_access) && $nextunit_access){
                    for($i=0;$i<$next;$i++){
                        $status = get_post_meta($units[$i],$user_id,true);
                        if(!empty($status)){
                            $nextflag=0;
                            break;
                        }
                    }
                }
                if($nextflag){
                    if(get_post_type($units[$next]) == 'quiz'){
                        $quiz_status = get_user_meta($user_id,$units[$next],true);
                        if(!empty($quiz_status))
                            echo '<a href="#" data-unit="'.$units[$next].'" class="unit unit_button">'.__('Next Quiz','vibe').'</a>';
                        else
                            echo '<a href="'.get_permalink($units[$next]).'" class=" unit_button">'.__('Next Quiz','vibe').'</a>';
                    }else{
                        if(get_post_type($units[$next]) == 'unit'){ //Display Next unit link because current unit is a quiz on Page reload
                            echo '<a href="#" id="next_unit" data-unit="'.$units[$next].'" class="unit unit_button">'.__('Next Unit','vibe').'</a>';
                        }else{
                            echo '<a href="#" id="next_unit" data-unit="'.$units[$next].'" class="unit unit_button hide">'.__('Next Unit','vibe').'</a>';
                        }
                    }
                }else{
                    echo '<a href="#" id="next_unit" class="unit unit_button hide">'.__('Next Unit','vibe').'</a>';
                }
            }
            echo '</div></div>';

        } // End the Bug fix on course begining
        ?>
    </div>
</div>
<div class="col-md-2" style="left: -5%;">
    <div class="curriculum_content">
        <ul class="gray-nav nav-tab">
            <li class="c chon hascontent" title="Danh sách bài học" data-placement="bottom">
                <label for="tab3">
                    <i class="icon-list-1"></i>
                </label>
            </li>

            <li class="d" title="Download tài liệu bài học" data-placement="bottom">
                <label for="tab3">
                    <i class="icon-download-3"></i>
                </label>
            </li>

            <li class="e" title="Thảo luận bài học" data-placement="bottom">
                <label for="tab3">
                    <i class="icon-comments"></i>
                </label>
            </li>

            <li class="v">
                <label for="tab3">
                    <i class="icon-file"></i>
                </label>
            </li>
        </ul>

        <div class="curriculum_content_right">

            <?php echo the_course_timeline($course_id,$unit_id);?>

        </div>
    </div>


</div>
</div>

<?php
wp_nonce_field('security','hash');
echo '<input type="hidden" id="course_id" name="course" value="'.$course_id.'" />';
?>
<div id="ajaxloader" class="disabled"></div>
<div class="side_comments"><a id="all_comments_link" data-href="<?php if(isset($unit_comments) && is_numeric($unit_comments)){echo get_permalink($unit_comments);} ?>"><?php _e('SEE ALL','vibe'); ?></a>
    <ul class="main_comments">
        <li class="hide">
            <div class="note">
                <?php
                $author_id = get_current_user_id();
                echo get_avatar($author_id).' <a href="'.bp_core_get_user_domain($author_id).'" class="unit_comment_author"> '.bp_core_get_user_displayname( $author_id) .'</a>';

                $link = vibe_get_option('unit_comments');
                if(isset($link) && is_numeric($link))
                    $link = get_permalink($link);
                else
                    $link = '#';
                ?>
                <div class="unit_comment_content"></div>
                <ul class="actions">
                    <li><a class="tip edit_unit_comment" title="<?php _e('Edit','vibe'); ?>"><i class="icon-pen-alt2"></i></a></li>
                    <li><a class="tip public_unit_comment" title="<?php _e('Make Public','vibe'); ?>"><i class="icon-fontawesome-webfont-3"></i></a></li>
                    <li><a class="tip private_unit_comment" title="<?php _e('Make Private','vibe'); ?>"><i class="icon-fontawesome-webfont-4"></i></a></li>
                    <li><a class="tip reply_unit_comment" title="<?php _e('Reply','vibe'); ?>"><i class="icon-curved-arrow"></i></a></li>
                    <li><a class="tip instructor_reply_unit_comment" title="<?php _e('Request Instructor reply','vibe'); ?>"><i class="icon-forward-2"></i></a></li>
                    <li><a data-href="<?php echo $link; ?>" class="popup_unit_comment" title="<?php _e('Open in Popup','vibe'); ?>" target="_blank"><i class="icon-windows-2"></i></a></li>
                    <li><a class="tip remove_unit_comment" title="<?php _e('Remove','vibe'); ?>"><i class="icon-cross"></i></a></li>
                </ul>
            </div>
        </li>
    </ul>

    <a class="add-comment"><?php _e('Add a Note','vibe');?></a>
    <div class="comment-form">
        <?php
        echo get_avatar($author_id); echo ' <span>'.__('YOU','vibe').'</span>';
        ?>
        <article class="live-edit" data-model="article" data-id="1" data-url="/articles">
            <div class="new_side_comment" data-editable="true" data-name="content" data-text-options="true">
                <?php _e('Add your Comment','vibe'); ?>
            </div>
        </article>
        <ul class="actions">
            <li><a class="post_unit_comment tip" title="<?php _e('Post','vibe'); ?>"><i class="icon-fontawesome-webfont-4"></i></a></li>
            <li><a class="remove_side_comment tip" title="<?php _e('Remove','vibe'); ?>"><i class="icon-cross"></i></a></li>
        </ul>
    </div>
</div>
</div>
<!--    Phần đề cương khóa học-->
<div class="DeCuongKH">
    <div class="course_time">
        <?php
        $sobaidahoc=0;
        for($i=0;$i<count($units);$i++){
            $kiemtradone=get_user_meta(get_current_user_id(),$units[$i],true);
            if(!empty($kiemtradone)){
                $sobaidahoc++;
            }
        }
        echo '<strong>SỐ BÀI ĐÃ HỌC : <span>'.$sobaidahoc.'/'.count($units).'</span></strong>';
        ?>
    </div>
    <?php
    do_action('wplms_course_start_after_time',$course_id,$unit_id);
    echo the_course_timeline($course_id,$unit_id);
    do_action('wplms_course_start_after_timeline',$course_id,$unit_id);

    if(isset($course_curriculum) && is_array($course_curriculum)){
        ?>
        <!-- <div class="more_course">
                            <a href="<?php /*echo get_permalink($course_id); */?>" class="unit_button full button"><?php /*_e('BACK TO COURSE','vibe'); */?></a>
                            <form action="<?php /*echo get_permalink($course_id); */?>" method="post">
                                <?php
        /*                                $finishbit=get_post_meta($course_id,$user_id,true);
                                        if(isset($finishbit) && $finishbit!=''){
                                            if($finishbit>0 && $finishbit < 3){
                                                echo '<input type="submit" name="review_course" class="review_course unit_button full button" value="'. __('REVIEW COURSE ','vibe').'" />';
                                                echo '<input type="submit" name="submit_course" class="review_course unit_button full button" value="'. __('FINISH COURSE ','vibe').'" />';
                                            }
                                        }
                                        */?>
                                <?php /*wp_nonce_field($course_id,'review'); */?>
                            </form>
                        </div>-->
    <?php
    }
    ?>
</div>
<!-- khải end edit-->
</div>
<div class="col-md-5">
<!--Khải edit-->
<div class="course_tab">
    <ul class="tab_tab_settings">
        <li class="discussion litab activeli">
            <i class="icon-comment"> </i>
            <span class="ThaoLuan">Thảo luận</span>
        </li>
        <li class="ThongBao">
            <i class="icon-volume"></i>
            <span>Thông báo</span>
        </li>
        <li class="slhocvien">
            <?php
            $students = get_post_meta($course_id,'vibe_students',true);
            if(!isset($students) && $students =''){$students=0;update_post_meta(get_the_ID(),'vibe_students',0);}
            echo '<i class="icon-male-user-4"></i><span class="slhv">'.$students.'</span>';

            ?>
        </li>
    </ul>
</div>
<br>
<!-- phần nội dung thảo luận-->
<div class="discussion-content">
<div class="buttondcs">
    <input style="width: 35%;float: left;margin-right: 5px" class="form-control timkiembinhluan" data-course-id="<?php echo $course_id ?>" placeholder="Tìm kiếm thảo luận"> hoặc
    <input type="button" class="btnAddDiscussion btn btn-success" value="Thêm thảo luận" data-course="<?php echo $course_id; ?>" data-id="<?php echo $user_id; ?>">
</div>
<div class="contentdcs">
    <i class="btnClose icon-close-off-2 "><br></i>
    <br />
    <input  placeholder="Nhập vào tiêu đề thảo luận" class="title-discussion form-control">
    <br />
    <?php wp_editor( "", "txtThaoLuan", array(
        'teeny'=>false,
        'media_buttons'=>false,
        'quicktags' => false,
        'textarea_rows' => 5,
        'tinymce' => array(
            'toolbar1' => 'bold, italic, underline',
            'toolbar2'=>false,
        ),
    )); ?>
    <a  href="#"  style="font-size: 9pt;margin-top: 5px">Thảo luận chung</a>
    <span class="insert-my-media btn btn-success" data-id="<?php echo $course_id ?>">Thêm hình ảnh</span>
    <a class=" btn btn-success btnThaoLuan">Gửi</a>
    <input type="hidden" class="course_id" value="<?php echo $course_id ?>">
    <input type="hidden" class="user_id" value="<?php echo $user_id ?>">
    <a style="float: right;font-size: 10pt;margin-top: 5px;font-style: italic" href="#">Hỗ trợ liên hệ ITClass</a>
</div>
<div class="discussions_all"> <!--nhiều thảo luận-->
    <?php
    $defaults= array(
        'comment_post_ID'=>$course_id,
    );
    echo get_comment($defaults);
    ?>
</div>
<br >
<br>
<!--                    bat dau load danh gia -->
<div id="ratingreviewkhoahoc" class="anpopupthongtinkhoahoc">
    <div class="thongtinkhoahocx">
        <i class="icon-x danhgia"></i>
    </div>

    <h1>Đánh giá: <?php echo get_the_title($course_id);  ?></h1>

    <div class="noidungthongtinkhoahoc">
        <div class="danhgiacuaban">
            Đánh giá của bạn:
            <?php
            $danhsach=array(
                'post_id' => $course_id,
                'user_id' => $user_id,
                'meta_key' => 'review_rating'
            );
            $danhgiacuauser = get_comments($danhsach);
            foreach($danhgiacuauser as $ds){
                $idrating = $ds->comment_ID;
            }

            if($idrating){
                for( $i=1; $i <= 5; $i++ )
                    if(get_comment_meta( $idrating, 'review_rating', true )==$i){
                        echo '<span class="commentrating">
                            <input type="radio" name="review_rating" id="rating" value="'. $i .'" checked/>'. $i .'
                            </span>';
                    }else{
                        echo '<span class="commentrating">
                            <input type="radio" name="review_rating" id="rating" value="'. $i .'"/>'. $i .'
                            </span>';
                    }
            }
            else{
                for( $i=1; $i <= 5; $i++ )
                    if($i==5){
                        echo '<span class="commentrating">
                            <input type="radio" name="review_rating" id="rating" value="'. $i .'" checked/>'. $i .'
                            </span>';
                    }else{
                        echo '<span class="commentrating">
                            <input type="radio" name="review_rating" id="rating" value="'. $i .'"/>'. $i .'
                            </span>';
                    }
            }
            ?>
        </div>
        <div>
            <span class="tieudehoanthiendanhgia">Vui lòng hoàn thành đánh giá của bạn</span>
            <ul>
                <li>
                    Khóa học này có đáp ứng đầy đủ các nhu cầu của bạn hay không?
                </li>
                <li>
                    Bạn cảm thấy chất lượng khóa học này như thế nào?
                </li>
                <li>
                    Bạn cảm thấy giảng viên như thế nào?
                </li>
            </ul>
            <?php
            if($idrating){
                echo '<input type="text" class="tieudedanhgia" placeholder="Tiêu đề đánh giá" value="'.get_comment_meta($idrating,'review_title',true).'">';
                echo '<textarea rows="4" class="noidungdanhgia" placeholder="Nội dung đánh giá">'.get_comment_text($idrating).'</textarea>';
                echo '<div class="noidungxulydanhgia">';
                echo '<span data-course="'.$course_id.'" data-id="'.$idrating.'" class="btn btn-primary capnhatdanhgia">Cập Nhật Đánh Giá</span>';
                echo '<a href="#" data-course="'.$course_id.'" data-id="'.$idrating.'" class="xoadanhgia">Xóa đánh giá của bạn</a>';
                echo '<p class="loadingcapnhatdanhgia anpopupthongtinkhoahoc"><i class="icon-refresh glyphicon-refresh-animate"></i>Đang cập nhật...</p>';
                echo '</div>';
            }else{
                echo '<input type="text" class="tieudedanhgia" placeholder="Tiêu đề đánh giá">';
                echo '<textarea class="noidungdanhgia" rows="4" placeholder="Nội dung đánh giá"></textarea>';
                echo '<div class="noidungxulydanhgia">';
                echo '<span data-id="'.$course_id.'" class="btn btn-primary danhgiakhoahoc">Đánh Giá</span>';
                echo '<p class="loadingdanhgia anpopupthongtinkhoahoc"><i class="icon-refresh glyphicon-refresh-animate"></i>Đang lưu...</p>';
                echo '</div>';
            }
            ?>

        </div>
        <input type="hidden" class="datacourseid" value="<?php echo $course_id; ?>">
        <h6 class='review_title'>Danh sách nhận xét</h6>
        <div class="loaddanhsachdanhgia">
        </div>
    </div>

</div>
<!--                    ket thuc load danh gia -->

<div class="NoiDungThaoLuan">
    <div class="append-content-discussion" >
        <?php
        //                        $argsComment = array();
        //                        // lấy danh sách comment của unit và add vào một mảng
        //                        foreach($units as $unit_id_comment){
        //                            $args = array(
        //                                'post_id' => $unit_id_comment,
        //
        //                            );
        //
        //                            $comments = get_comments($args);
        //                            $argsComment = array_merge_recursive($argsComment,$comments);
        //                        }
        //
        //                        // lấy danh sách comment của khóa học và add vào mảng comment của unit
        //                        $args = array(
        //                            'post_id' => $course_id,
        //                        );
        //
        //                        $comments = get_comments($args);
        //                        $argsComment = array_merge_recursive($argsComment,$comments);
        //
        //
        //                        // sắp xếp mãng object của bình luận tăng dần
        //                        function cmp($a, $b)
        //                        {
        //                            return strcmp($a->comment_ID, $b->comment_ID);
        //                        }
        //
        //                        usort($argsComment, "cmp");
        global $wpdb;
        $bien='';
        for($i=0;$i<count($units);$i++){
            if($i==count($units)-1){
                $bien.=$wpdb->comments.".comment_post_ID=".$units[$i];
            }else{
                $bien.=$wpdb->comments.".comment_post_ID=".$units[$i]." OR ";
            }

        }
        //                        $query = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->comments.".comment_parent=0 AND (".$bien.") GROUP BY ".$wpdb->comments.".comment_id LIMIT 0,5";
        $query = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->commentmeta.".meta_key = 'title_discussion' AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$noidungtimkiem."%' OR ".$wpdb->comments.".comment_content like '%".$noidungtimkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$course_id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC LIMIT 0,10";
        $querytong = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->commentmeta.".meta_key = 'title_discussion' AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$noidungtimkiem."%' OR ".$wpdb->comments.".comment_content like '%".$noidungtimkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$course_id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC";
        $argsComment = $wpdb->get_results($query);
        $argsCommenttong = $wpdb->get_results($querytong);


        $thaoluantaiunit = 0;
        $tongsobinhluan = count($argsCommenttong);
        $dem = 0;


        foreach($argsComment as $comment)
        {
            $checkCommentMeta = get_comment_meta($comment->comment_ID,'review_rating',true);
            if(empty($checkCommentMeta)){
                if($comment->comment_parent == 0){
                    // lấy nội dung bình luận con
                    $args = array(
                        'post_id' => $comment->comment_post_ID,
                        'parent' => $comment->comment_ID,
                        'order' => 'ASC',
                    );
                    $comments_child = get_comments($args);

                    //đếm số comment con
                    $args = array(
                        'post_id' => $comment->comment_post_ID,
                        'parent' => $comment->comment_ID,
                        'count' => true
                    );
                    $number_comments_child = get_comments($args);

                    echo '<div class="item-discustion">';
                    echo '<div class="cmtauthor row">';
                    echo '<div class="HieuChinh-ds">';
                    if($comment->user_id==get_current_user_id())
                    {
                        echo '<div class="Xoads"><i class="icon-x"></i> </div>';
                        echo '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
                    }
                    echo '<input class="id-comment-ds" type="hidden" value="'.$comment->comment_ID.'">';
                    echo '</div>';
                    echo '<div class="col-md-1">';
                    echo get_avatar( $comment->user_id, 32 ) ;
                    echo '</div>';
                    echo '<div class="col-md-10" >';

                    foreach($units as $unit_id_comment){
                        if($comment->comment_post_ID == $unit_id_comment){
                            $thaoluantaiunit = $comment->comment_post_ID;
                        }
                    }

                    if($thaoluantaiunit != 0){
                        echo '<span class="authorname">'. $comment->comment_author . '</span>'.'<span style="font-style:italic"> đã gửi 1 thảo luận tại bài <span class="unit_line"> <a class="unit" data-unit="'.$thaoluantaiunit.'" ><b>'.get_the_title($thaoluantaiunit).'</b></a> </span> cách đây '. human_time_diff( strtotime($comment->comment_date), strtotime(current_time( 'mysql' ))  ).'</span>';                            ;
                    }else{
                        echo '<span class="authorname">'. $comment->comment_author . '</span>'.'<span style="font-style:italic"> đã gửi 1 thảo luận cách đây '. human_time_diff( strtotime($comment->comment_date), strtotime(current_time( 'mysql' ))  ).'</span>';                            ;

                    }


                    echo '</div></div><br>';
                    if($thaoluantaiunit != 0){
                        echo '<div data-id="'.$comment->comment_ID.'" data-course-id="'.$thaoluantaiunit.'" class="NoiDungCMTUser row">';
                    }else{
                        echo '<div data-id="'.$comment->comment_ID.'" data-course-id="'.$course_id.'" class="NoiDungCMTUser row">';
                    }

                    echo '<div class="col-md-1"></div>';
                    echo '<div class="col-md-10">';
                    echo '<div class="comment-title-user">'.get_comment_meta($comment->comment_ID,'title_discussion',true) .' </div>';
                    echo '<div class="comment-content-user">'. $comment->comment_content.'</div>';

                    if($number_comments_child !=0){
                        echo '<div class="list-comment"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-'.$comment->comment_ID.'">Hiện '.$number_comments_child.' trả lời</a></li></ul></div>';
                        echo '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$comment->comment_ID.'">Ẩn '.$number_comments_child.' trả lời</a></li></ul></div>';
                    }else{
                        echo '<div class="list-comment be-frist"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-'.$comment->comment_ID.'">Hãy là người đầu tiên trả lời bình luận này</a></li></ul></div>';
                        echo '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$comment->comment_ID.'">Ẩn đi</a></li></ul></div>';

                    }

//                            echo '<div class="content_child_comment">
//
//                                    </div>';

                    echo '<div class="child_comment">';
                    echo '<div class="content_child_comment_start">';
                    foreach($comments_child as $value){
                        echo '<li>';
                        echo '<div class="item-discustion child">';
                        echo '<div class="cmtauthor child row">';
                        echo '<div class="HieuChinh-ds child">';
                        if($value->user_id==get_current_user_id())
                        {
                            echo '<div class="Xoads"><i class="icon-x"></i> </div>';
                            echo '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
                        }
                        echo '<input class="child id-comment-ds" type="hidden" value="'.$value->comment_ID.'">';
                        echo '</div>';
                        echo '<div class="col-md-1">';
                        echo get_avatar( $value->user_id, 32 ) ;
                        echo '</div>';
                        echo '<div class="col-md-10" >';

                        echo '<span class="authorname">'. $value->comment_author . '</span>'.'<span style="font-style:italic"> đã gửi 1 thảo luận cách đây '. human_time_diff( strtotime($value->comment_date), strtotime(current_time( 'mysql' ))  ).'</span>';                            ;

                        echo '</div></div><br>';
                        echo '<div data-id="'.$value->comment_ID.'" data-course-id="'.$thaoluantaiunit.'" class="child NoiDungCMTUser row">';
                        echo '<div class="col-md-1"></div>';
                        echo '<div class="col-md-10">';
                        echo '<div class="comment-title-user">'.get_comment_meta($value->comment_ID,'title_discussion',true) .' </div>';
                        echo '<div class="comment-content-user">'. $value->comment_content.'</div>';
                        echo '</div></div>';
                        echo '<div class="edit_content_editor_child"></div>';
                        echo '</li>';
                    }
                    echo '</div>';
                    echo '<div class="content_child_comment"></div> ';
                    echo '</div>';

                    echo '</div></div>';

                    echo '<div class="edit_content_editor "></div><hr>';
                    echo '</div>';
                }
            }


        }
        ?>
        <?php
        if($tongsobinhluan > 10){
            ?>
            <div data-page="10" data-tong="<?php echo $tongsobinhluan ?>" data-course-id="<?php echo $course_id ?>" class="xemthembinhluan"><span class="btn btn-primary"><i style="display: none" class="noidungthongbaoloading icon-refresh glyphicon-refresh-animate"></i> Xem thêm...</span></div>
        <?php } ?>
    </div>
</div>
</div>
<!--end edit-->

</div>
</div>

</section>


<?php
get_footer();
?>