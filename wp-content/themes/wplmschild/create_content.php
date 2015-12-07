<?php
/**
 * Template Name: Create Content
 */
do_action('wplms_before_create_course_header');
get_header('buddypress');

$user_id=get_current_user_id();

$linkage = vibe_get_option('linkage');
//Default settings
$course_settings = array(
    'vibe_duration' => 9999,
    'vibe_course_auto_eval' => 'H',
    'vibe_pre_course' =>'',
    'vibe_course_drip' => 'H',
    'vibe_course_drip_duration' => 1,
    'vibe_course_certificate' =>'S',
    'vibe_course_passing_percentage' => 40,
    'vibe_certificate_template' =>'',
    'vibe_badge' => 'S',
    'vibe_course_badge' => VIBE_URL.'/images/add_image.png',
    'vibe_course_badge_percentage' => 75,
    'vibe_course_badge_title' => '',
    'vibe_max_students' => 9999,
    'vibe_start_date' => date('Y-m-d'),
    'vibe_group' => '',
    'vibe_forum' => '',
    'vibe_course_instructions' => ' <p>'.__('Course specific instructions','vibe').'</p>',
    'vibe_course_message' => ' <p>'.__('Enter a course completion message for students passing this course. This message is shown to students when students submit their course. The message is shown above the Course review form','vibe').'</p>',
    );

$course_pricing =array(
    'vibe_course_free' => 'H',
    'vibe_product' => '',
    'vibe_pmpro_membership' =>'',
    'vibe_subscription' => 'H',
    'vibe_duration' => 0
    );

do_action('wplms_before_create_course_page');

$course_settings=apply_filters('wplms_create_course_settings',$course_settings);
$course_pricing=apply_filters('wplms_frontend_create_course_pricing',$course_pricing);

if(isset($_GET['action']) && is_numeric($_GET['action'])){
    $id = $_GET['action']; // Grant Access to edit the Post | Validates if the user is Course Instructor
    $course_cats =wp_get_post_terms( $id, 'course-cat');
    $course_cat_id = $course_cats[0]->term_id;

    $course_cats_language =wp_get_post_terms( $id, 'language-category');
    $course_cat_language_id = $course_cats_language[0]->term_id;

    if(isset($linkage ) && $linkage ){
        $course_linkage=wp_get_post_terms( $id, 'linkage',array("fields" => "names"));
    }

}

?>
<!--    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>-->
<section id="create_content">
    <div class="container">
        <?php do_action( 'content_notices' ); ?>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div id="course_creation_tabs" class="course-create-steps">
                    <ul <?php echo ((isset($_GET['action']))?'class="islive"':'');?>>
                        <li class="active"><i class="icon-book-open"></i><a><?php  (isset($_GET['action'])?_e('Tạo Khóa Học','vibe'):_e('Tạo Khóa Học','vibe')); ?><span><?php _e('Bắt đầu xây dựng khóa học','vibe'); ?></span></a></li>
                        <!--thêm vào mục tiêu khóa h?c id các li
__________________________________________________************************_____________________________________

-->
                        <li><i class="icon-file-settings"></i><a><?php _e('Mục tiêu','vibe'); ?><span><?php _e('Mục tiêu khóa học','vibe'); ?></span></a></li>
                        <!--end-->
                        <li><i class="icon-settings-1"></i><a><?php _e('Cấu Hình','vibe'); ?><span><?php _e('Cấu Hình Khóa Học','vibe'); ?></span></a></li>
                        <li><i class="icon-file"></i><a><?php _e('Đề Cương','vibe'); ?><span><?php _e('Thêm Bài Học Và Câu Hỏi','vibe'); ?></span></a></li>
                        <?php
                        $enable_pricing = apply_filters('wplms_front_end_pricing',1);
                        if ($enable_pricing) {
                        ?>
                        <li><i class="icon-tag"></i><a><?php _e('Giá','vibe'); ?><span><?php _e('Đặt Giá Cho Khóa Học','vibe'); ?></span></a></li>
                        <?php
                        }
                        ?>
                        <li><i class="icon-glass"></i><a><?php _e('Hoàn Thành','vibe'); ?><span><?php _e('Hoàn Thành Khóa Học !','vibe'); ?></span></a></li>
                    </ul>
                </div>
                <?php
                    if(isset($_GET['action']) && is_numeric($_GET['action'])){
                        echo '<a href="'.admin_url( 'post.php?post='.$_GET['action'].'&action=edit').'" class="link">'.__('Edit in Admin Panel','vibe').'</a>';
                    }
                ?>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="create_course_content content">
                    <div id="create_course" class="active">
                        <article class="live-edit" data-model="article" data-id="1" data-url="/articles">
                            <label><?php _e('Tiêu Đề Khóa Học','vibe'); ?></label>
                            <!--khải edit-->
                            <div id="div-title-course">
                                <h1 id="course-title" data-help-tag="1" data-editable="true" data-max-length="59" maxlength="59" data-name="title"> <?php if(isset($_GET['action'])) echo get_the_title($_GET['action']); else _e('','vibe'); ?></h1>
                                <h6 style="float: right;clear: both" id="lengthchar">60</h6>
                            </div>
                            <!--end-->
                           <hr />
                            <label><?php _e('Loại khóa học','vibe'); ?></label>
                            <ul id="course-category" data-help-tag="2">
                                <li>
                                    <select id="course-category-select" class="chosen">
                                        <option><?php _e('Chọn loại khóa học','vibe'); ?></option>
<!--                                        <option value="new">--><?php //_e('Add new category','vibe'); ?><!--</option>-->
                                        <?php
                                        $terms = get_terms('course-cat',array('hide_empty' => false));
                                        if(isset($terms) && is_array($terms))
                                        foreach($terms as $term){
                                            $parenttermname='';
                                            if($term->parent){
                                                $parentterm=get_term_by('id', $term->parent, 'course-cat', 'ARRAY_A');
                                                $parenttermname = $parentterm['name'].' &rsaquo; ';
                                            }
                                            echo '<option value="'.$term->term_id.'" '.(isset($_GET['action'])?selected($course_cat_id,$term->term_id):'').'>'.$parenttermname.$term->name.'</option>';
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li><p id="new_course_category" data-editable="true" data-name="content" data-max-length="1000" data-text-options="true"><?php _e('Enter a new Course Category','vibe'); ?></p></li>
                            </ul>

<!--                            Thên caterogy khóa học-->
                            <?php
                                if(isset($_GET['action'])=='true'){
                            ?>
                                    <div class="course-category-language-css" style="display: block">
                            <?php }else{ ?>
                                        <div class="course-category-language-css">
                            <?php } ?>
                                <label><?php _e('Ngôn ngữ','vibe'); ?></label>
                                <ul id="course-category">
                                    <li>

                                        <select id="course-category-select-language" class="chosen">
                                            <option><?php _e('Chọn ngôn ngữ khóa học','vibe'); ?></option>
                                            <?php
                                            $terms = get_terms('language-category',array('hide_empty' => false));
                                            if(isset($terms) && is_array($terms))
                                                foreach($terms as $term){
                                                    $parenttermname='';
                                                    if($term->parent){
                                                        $parentterm=get_term_by('id', $term->parent, 'language-category', 'ARRAY_A');
                                                        $parenttermname = $parentterm['name'].' &rsaquo; ';
                                                    }
                                                    echo '<option value="'.$term->term_id.'" '.(isset($_GET['action'])?selected($course_cat_language_id,$term->term_id):'').'>'.$parenttermname.$term->name.'</option>';
                                                }
                                            ?>
                                        </select>
                                    </li>
                                </ul>
                            </div>

                            <hr class="clear" style="margin-top: 9%" />
                            <div  id="featured_image" data-help-tag="3">
                                <label><?php _e('Hình ảnh','vibe'); ?></label>
                                <div id="course_image" class="upload_image_button" data-input-name="course-image" data-uploader-title="Upload a Course Image" data-uploader-button-text="Set as Course Image" data-uploader-allow-multiple="false">
                                    <?php
                                    if(isset($_GET['action']) && has_post_thumbnail( $_GET['action'] )){
                                        echo get_the_post_thumbnail($_GET['action'],'thumbnail');
                                    }else{
                                    ?>
                                    <img src="<?php echo VIBE_URL.'/images/add_image.png'; ?>" alt="course image" class="default" />
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <label><?php _e('Mô tả khóa học','vibe'); ?></label>
                            <div id="course_short_description" data-help-tag="4" data-editable="true" data-name="content" data-max-length="250" data-text-options="true">
                            <?php

                            if(isset($_GET['action'])){
                                $the_post = get_post($_GET['action']);
                                $mota = $the_post->post_excerpt;
//                                $vitriiframemota = strpos($mota,'[/iframevideo]');
//                                $motacatvideo = substr($mota,$vitriiframemota+14);
                                echo $mota;
                            }else{
                            ?>
                                <p><?php _e('Đây là nơi dùng để viết mô tả khóa học. Mô tả phải ít hơn 30 ký tự.','vibe'); ?></p>
                            <?php
                            }
                            ?>
                            </div><p></p>
                            <label><?php _e('Video trailer','vibe'); ?></label>
                               <?php if(!isset($_GET['action'])){ ?>
                                  <div class="video_trailer" id="course_video" data-help-tag="4" data-editable="true" data-name="content" data-max-length="1000" data-text-options="true">
                                  <p><?php _e('Chèn link video Youtube hoặc Facebook vào đây.','vibe'); ?></p>
                                <?php

                                }else{ ?>
                                    <div class="video_trailer hasVideo" id="course_video" data-help-tag="4" data-editable="true" data-name="content" data-max-length="1000" data-text-options="true">
                                <?php
                                    $motacontent = $the_post->post_content;
                                    $vitridausrcvideo = strpos($motacontent,'src');
                                    $vitricuoisrcvideo = strpos($motacontent,'width');
                                    $srcvideo = substr($motacontent,$vitridausrcvideo+5,$vitricuoisrcvideo-28);
                                    echo $srcvideo;
                                 } ?>
                            </div>
                            <br class="clear" />
                            <hr />
                            <?php
//                                if(isset($linkage) && $linkage){
                            ?>
<!--                            <label>--><?php //_e('COURSE LINKAGE','vibe'); ?><!--</label>-->
<!--                            <ul id="course-linkage" data-help-tag="5">-->
<!--                                <li>-->
<!--                                    <select id="course-linkage-select" class="chosen">-->
<!--                                        <option>--><?php //_e('Select Linkage term','vibe'); ?><!--</option>-->
<!--                                        --><?php
//                                        $terms = get_terms('linkage',array('hide_empty' => false,'fields' => 'names'));
//
//                                        if(isset($terms) && is_array($terms))
//                                        foreach($terms as $term){
//                                            echo '<option value="'.$term.'" '.(isset($_GET['action'])?(in_array($term,$course_linkage)?'selected':''):'').'>'.$term.'</option>';
//                                        }
//                                        ?>
<!--                                        <option value="add_new">--><?php //_e('Add new Linkage term','vibe'); ?><!--</option>-->
<!--                                    </select>-->
<!--                                </li>-->
<!--                                <li><p id="new_course_linkage" data-editable="true" data-name="content" data-max-length="250" data-text-options="true">--><?php //_e('Enter a new Linkage','vibe'); ?><!--</p></li>-->
<!--                            </ul>-->
<!--                            <hr class="clear" />-->
                            <?php

//                            }
                                if(isset($_GET['action'])){
                            ?>
                            <div class="clear"></div>
                            <h3 class="course_status" data-help-tag="6"><?php _e('Trạng thái','vibe'); ?><span>
                                    <div class="switch">
                                        <input type="radio" class="switch-input vibe_course_status" name="post_status" value="publish" id="online" <?php checked($the_post->post_status,'publish'); ?>>
                                        <label for="online" class="switch-label switch-label-off"><?php _e('Online','vibe');?></label>
                                        <input type="radio" class="switch-input vibe_course_status" name="post_status" value="draft" id="offline" <?php checked($the_post->post_status,'draft'); ?>>
                                        <label for="offline" class="switch-label switch-label-on"><?php _e('Offline','vibe');?></label>
                                        <span class="switch-selection online"></span>
                                      </div>
                                    </span>
                                </h3>
                            <?php
                            }
                            ?>
                            <label><?php _e('Trailer','vibe'); ?></label>
                            <div class="xemtrailer">

                            </div>
                        </article>
                        <?php

                        if(isset($_GET['action'])){
                        ?>
                            <a id="save_course_action" class="button hero"><?php _e('Lưu khóa học','vibe'); ?></a>
                        <?php
                        }else{
                        ?>
                            <a id="create_course_action" class="button hero"><?php _e('Tạo khóa học','vibe'); ?></a>
                        <?php
                        }
                        ?>
                    </div>
                    <!-- Start: Khải
                  Thêm vào div mục tiêu

                  ______________________________**********************________________________________________
                   -->

                    <div id="goal-course">
                        <h3 class="heading"><?php _e('Mục tiêu học tập','vibe'); ?></h3>
                        <?php

                        if(isset($_GET['action']) && is_numeric($_GET['action']))
                        {
                            $courseid=$_GET['action'];
                            $muctieu1 = get_post_meta($courseid,"muctieu1",true);
                            $muctieu2=get_post_meta($courseid,"muctieu2",true);
                            $muctieu3=get_post_meta($courseid,"muctieu3",true);

                        }

                        ?>
                        <article class="live-edit" data-model="article" data-id="1" data-url="/articles">
                            <div class="minititle" ><?php _e('Học viên sẽ học được gì sau khi học khóa học của bạn?','vibe'); ?></div>
                            <br />
                            <div
                            <div class="titlesnotes" ><?php _e('Học xong khóa học học viên sẽ có thể?','vibe'); ?><br /></div>
                            <br />

                            <div class="muctieukhoahoc1">
                                <?php

                                $mt1 = explode("[)",$muctieu1); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
                                for($i=0;$i<count($mt1)-1;$i++)
                                {
                                    echo "<div class='nodeparent'><span class='form-control node1'>".$mt1[$i]."</span><br /><div class='delete' style='cursor: pointer'>Xóa</div></div> ";

                                }

                                ?>

                            </div>
                            <i class="icon-circle-24" style="position: relative;right: 79.5%;font-size: 0.6vw;bottom: 0;bottom: -6px;color: #999999;"></i><input id="muctieu1" class="form-control mt1" type="text" name="txtMucTieu1" value="" placeholder="Nhập vào mục tiêu ...">
                            <input style="float: right;clear: both" id="btnMucTieu1" type="button" value="Thêm mới" class="btn btn-success">
                            <br />
                            <br />

                            <div class="titlesnotes" ><?php _e('Ai nên học khóa học này? Ai không nên?','vibe'); ?></div>
                            <br />
                            <div class="muctieukhoahoc2">
                                <?php

                                $mt2 = explode("[)",$muctieu2); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
                                for($i=0;$i<count($mt2)-1;$i++)
                                {
                                    echo "<div class='nodeparent'><span class='form-control node2'>".$mt2[$i]."</span><br /><div class='delete' style='cursor: pointer'>Xóa</div></div> ";

                                }
                                ?>
                            </div>
                            <i class="icon-circle-24" style="position: relative;right: 79.5%;font-size: 0.6vw;bottom: 0;bottom: -6px;color: #999999;"></i><input id="muctieu2" class="form-control mt2" type="text" name="txtMucTieu2" value="" placeholder="Nhập vào mục tiêu ...">
                            <input style="float:right;clear: both" id="btnMucTieu2" type="button" value="Thêm mới" class="btn btn-success">
                            <br>
                            <div class="titlesnotes" ><?php _e('Những yêu cầu trước khi học khóa học này','vibe'); ?></div>
                            <br />
                            <div class="muctieukhoahoc3">
                                <?php

                                $mt3 = explode("[)",$muctieu3); // Tách chuỗi gốc thành nhiều chuỗi con dựa vào ký tự @
                                for($i=0;$i<count($mt3)-1;$i++)
                                {
                                    echo "<div class='nodeparent'><span class='form-control node3'>".$mt3[$i]."</span><br /><div class='delete' style='cursor: pointer'>Xóa</div></div> ";

                                }

                                ?>
                            </div>
                            <i class="icon-circle-24" style="position: relative;right: 79.5%;font-size: 0.6vw;bottom: 0;bottom: -6px;color: #999999;"></i><input id="muctieu3" class="form-control mt3" type="text" name="txtMucTieu3" value="" placeholder="Nhập vào mục tiêu ...">
                            <input style="float: right;clear: both" id="btnMucTieu3" type="button" value="Thêm mới" class="btn btn-success">
                        </article>
                        <?php
                        if($muctieu1==null||$muctieu1=="")
                        {
                            ?>
                            <a id="save_goal_course" class="button hero"><?php _e('Lưu Mục Tiêu Khóa Học','vibe'); ?></a>
                            <a id="save_goal_editcourse" class="button hero disappearbtn"><?php _e('Cập Nhật Mục Tiêu Khóa Học','vibe'); ?></a>
                        <?php
                        }
                        else
                        {
                            ?>
                            <a id="save_goal_editcourse" class="button hero"><?php _e('Cập Nhật Mục Tiêu Khóa Học','vibe'); ?></a>

                        <?php
                        }
                        ?>
                    </div>
                    <!-- End:Khải-->


                    <div id="course_settings">
                        <h3 class="heading"><?php _e('Cấu hình khóa học','vibe'); ?></h3>
                        <article class="live-edit" data-model="article" data-id="1" data-url="/articles">
                        <ul class="course_setting">
<!--                            <li data-help-tag="7"><label>--><?php //_e('Thời gian khóa học','vibe'); ?><!--</label>-->
<!--                                <h3>--><?php //_e('Maximum Course Duration','vibe'); ?><!--<span><input type="number" id="vibe_duration" class="small_box" value="--><?php //echo $course_settings['vibe_duration']; ?><!--" />--><?php //$course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400); echo calculate_duration_time($course_duration_parameter); ?><!--</span></h3></li>-->
<!--                            <li data-help-tag="8"><label>--><?php //_e('Course Evaluation','vibe'); ?><!--</label>-->
<!--                                <h3>--><?php //_e('Course Evaluation Mode','vibe'); ?><!--<span>-->
<!--                                    <div class="switch">-->
<!--                                        <input type="radio" class="switch-input vibe_course_auto_eval" name="evaluation" value="H" id="manual" --><?php //checked($course_settings['vibe_course_auto_eval'],'H'); ?><!-->
<!--                                        <label for="manual" class="switch-label switch-label-off">--><?php //_e('Manual','vibe');?><!--</label>-->
<!--                                        <input type="radio" class="switch-input vibe_course_auto_eval" name="evaluation" value="S" id="automatic" --><?php //checked($course_settings['vibe_course_auto_eval'],'S'); ?><!-->
<!--                                        <label for="automatic" class="switch-label switch-label-on">--><?php //_e('Automatic','vibe');?><!--</label>-->
<!--                                        <span class="switch-selection online"></span>-->
<!--                                      </div>-->
<!--                                    </span>-->
<!--                                </h3>-->
<!--                            </li>-->
                            <li data-help-tag="9"><label><?php _e('Khóa học bắt buộc','vibe'); ?></label>
                                <h3><?php _e('Đặt khóa học bắt buộc','vibe'); ?>
                                <span><select id="vibe_pre_course" class="chosen">
                                    <option value=""><?php _e('None','vibe'); ?></option>
                                    <?php
                                        $args= array(
                                        'post_type'=> 'course',
                                        'numberposts'=> -1
                                        );
                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
                                        $kposts=get_posts($args);
                                        foreach ( $kposts as $kpost ){
                                            echo '<option value="' . $kpost->ID . '" '.selected($course_settings['vibe_pre_course'],$kpost->ID).'>' . $kpost->post_title . '</option>';
                                        }
                                    ?>
                                </select></span>
                                </h3>
                            </li>
<!--                            <li data-help-tag="10"><label>--><?php //_e('Drip Feed','vibe'); ?><!--</label>-->
<!--                                <h3>--><?php //_e('Drip Feed','vibe'); ?><!--<span>-->
<!--                                    <div class="switch">-->
<!---->
<!--                                        <input type="radio" class="switch-input vibe_course_drip" name="vibe_course_drip" value="H" id="disable" --><?php //checked($course_settings['vibe_course_drip'],'H'); ?><!-->
<!--                                        <label for="disable" class="switch-label switch-label-off">--><?php //_e('Disable','vibe');?><!--</label>-->
<!---->
<!--                                        <input type="radio" class="switch-input vibe_course_drip" name="vibe_course_drip" value="S" id="enable" --><?php //checked($course_settings['vibe_course_drip'],'S'); ?><!-->
<!--                                        <label for="enable" class="switch-label switch-label-on">--><?php //_e('Enable','vibe');?><!--</label>-->
<!--                                        <span class="switch-selection online"></span>-->
<!--                                      </div>-->
<!--                                    </span>-->
<!--                                </h3>-->
<!--                                <ul>-->
<!--                                    <li><h5>--><?php //_e('Set Drip Feed Duration','vibe'); ?><!--<span><input type="number" id="vibe_course_drip_duration" class="small_box" value="--><?php //echo $course_settings['vibe_course_drip_duration']?><!--" />--><?php //$drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400); echo calculate_duration_time($drip_duration_parameter); ?><!--</span></h5></li>-->
<!--                                </ul>-->
<!--                            </li>-->
                            <li data-help-tag="11"><label><?php _e('Chứng chỉ','vibe'); ?></label>
                            <h3><?php _e('Chứng chỉ','vibe'); ?><span>
                                    <div class="switch">
                                        <input type="radio" class="switch-input vibe_course_certificate" name="vibe_course_certificate" value="H" id="disable1" <?php checked($course_settings['vibe_course_certificate'],'H'); ?>>
                                        <label for="disable1" class="switch-label switch-label-off"><?php _e('Ẩn','vibe');?></label>

                                        <input type="radio" class="switch-input vibe_course_certificate" name="vibe_course_certificate" value="S" id="enable1" <?php checked($course_settings['vibe_course_certificate'],'S'); ?>>
                                        <label for="enable1" class="switch-label switch-label-on"><?php _e('Hiện','vibe');?></label>
                                        <span class="switch-selection online"></span>
                                      </div>
                                    </span>
                                </h3>
                                <ul <?php if($course_settings['vibe_course_certificate'] == 'S'){echo 'style="display:block;"';} ?>>
                                    <li><h5><?php _e('Phần trăm nhận chứng chỉ','vibe'); ?><span><input type="number" id="vibe_course_passing_percentage" class="small_box" value="<?php echo $course_settings['vibe_course_passing_percentage']; ?>"/><?php _e('out of 100','vibe'); ?></span></h5></li>
                                    <li><h5><?php _e('Giao diện chứng chỉ','vibe'); ?><span>
                                    <select id="vibe_certificate_template" class="chosen">
                                    <option value=""><?php _e('Giao diện mặc định','vibe'); ?></option>
                                    <?php
                                        $args= array(
                                            'post_type'=> 'certificate',
                                            'numberposts'=> -1
                                        );
                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
                                        $kposts=get_posts($args);
                                        foreach ( $kposts as $kpost ){
                                            echo '<option value="' . $kpost->ID . '" '.selected($course_settings['vibe_certificate_template'],$kpost->ID).'>' . $kpost->post_title . '</option>';
                                        }
                                    ?>
                                    </select></span></h5></li>
                                </ul>
                            </li>
                            <li data-help-tag="12"><label><?php _e('Course Badge','vibe'); ?></label>
                            <h3><?php _e('Huy hiệu khóa học','vibe'); ?><span>
                                    <div class="switch show-below">
                                        <input type="radio" class="switch-input vibe_badge" name="vibe_badge" value="H" id="disable2" <?php checked($course_settings['vibe_badge'],'H'); ?>>
                                        <label for="disable2" class="switch-label switch-label-off"><?php _e('Ẩn','vibe');?></label>
                                        <input type="radio" class="switch-input vibe_badge" name="vibe_badge" value="S" id="enable2" <?php checked($course_settings['vibe_badge'],'S'); ?>>
                                        <label for="enable2" class="switch-label switch-label-on"><?php _e('Hiện','vibe');?></label>
                                        <span class="switch-selection online"></span>
                                      </div>
                                    </span>
                                </h3>
                                <ul <?php if($course_settings['vibe_badge'] == 'S'){echo 'style="display:block;"';} ?>>
                                    <li><h5><?php _e('Phần trăm nhận huy hiệu','vibe'); ?><span><input type="number" id="vibe_course_badge_percentage" class="small_box" value="<?php echo $course_settings['vibe_course_badge_percentage']; ?>" /><?php _e(' out of 100','vibe'); ?></span></h5></li>
                                    <li><h5><?php _e('Tiêu đề huy hiệu','vibe'); ?><span><input type="text" id="vibe_course_badge_title" class="mid_box"  value="<?php echo $course_settings['vibe_course_badge_title']; ?>" /></span></h5></li>
                                    <li><h5><?php _e('Upload hình huy hiệu','vibe'); ?><span>
                                         <div id="badge_image" class="upload_badge_button" data-input-name="vibe_course_badge" data-uploader-title="Upload a Badge Image" data-uploader-button-text="Set as Badge" data-uploader-allow-multiple="false">
                                            <?php
                                            if(is_numeric($course_settings['vibe_course_badge'])){
                                                $img=wp_get_attachment_image_src($course_settings['vibe_course_badge']);
                                                $img=$img[0];
                                            }else{
                                                $img = VIBE_URL.'/images/add_image.png';
                                            }
                                            ?>
                                            <img src="<?php echo $img; ?>" />
                                            <input id="vibe_course_badge" type="hidden" value="<?php echo $course_settings['vibe_course_badge']; ?>" data-default="<?php echo VIBE_URL.'/images/add_image.png'; ?>"/>
                                        </div>
                                    </span></h5></li>
                                </ul>
                            </li>
<!--                            <li data-help-tag="13"><label>--><?php //_e('NUMBER OF STUDENT SEATS','vibe'); ?><!--</label>-->
<!--                                <h3>--><?php //_e('Number of seats in course','vibe'); ?><!--<span>-->
<!--                                    <input id="vibe_max_students" type="number" class="small_box" value="--><?php //echo $course_settings['vibe_max_students']; ?><!--" />-->
<!--                                    </span>-->
<!--                                </h3>-->
<!--                            </li>-->
<!--                            <li data-help-tag="14"><label>--><?php //_e('Course Start Date','vibe'); ?><!--</label>-->
<!--                                <h3>--><?php //_e('Set Course Start Date','vibe'); ?><!--<span>-->
<!--                                    <input id="vibe_start_date" type="text" class="mid_box date_box" value="--><?php //echo $course_settings['vibe_start_date']; ?><!--" />-->
<!--                                    </span>-->
<!--                                </h3>-->
<!--                            </li>-->
                            <?php
                            if(bp_is_active('groups')){
                            ?>
<!--                            <li data-help-tag="15"><label>--><?php //_e('Course Group','vibe'); ?><!--</label>-->
<!--                                <h3>--><?php //_e('Connect a Course Group','vibe'); ?><!--<span>-->
<!--                                    <select id="vibe_group" class="chosen">-->
<!--                                    <option value="">--><?php //_e('None','vibe'); ?><!--</option>-->
<!--                                    <option value="add_new">--><?php //_e('Add new Group','vibe'); ?><!--</option>-->
<!--                                    --><?php
//                                    if(class_exists('BP_Groups_Group')){
//                                        $vgroups =  BP_Groups_Group::get(array(
//                                        'type'=>'alphabetical',
//                                        'per_page'=>999
//                                        ));
//
//                                        foreach($vgroups['groups'] as $vgroup){
//                                            echo '<option value="'.$vgroup->id.'" '.selected($vgroup->id,$course_settings['vibe_group']).'>'.$vgroup->name.'</option>';
//                                        }
//                                    }
//                                    ?>
<!--                                    </select>-->
<!--                                    </span>-->
<!--                                </h3>-->
<!--                            </li>-->
                            <?php
                            }
                            if(post_type_exists('forum')){
                            ?>
<!--                            <li data-help-tag="16"><label>--><?php //_e('Course Forum','vibe'); ?><!--</label>-->
<!--                                <h3>--><?php //_e('Connect a Course Forum','vibe'); ?><!--<span>-->
<!--                                    <select id="vibe_forum" class="chosen">-->
<!--                                    <option value="">--><?php //_e('None','vibe'); ?><!--</option>-->
<!--                                    <option value="add_group_forum">--><?php //_e('Connect the Group forum','vibe'); ?><!--</option>-->
<!--                                    <option value="add_new">--><?php //_e('Add new forum','vibe'); ?><!--</option>-->
<!--                                    --><?php
//                                        $args= array(
//                                        'post_type'=> 'forum',
//                                        'numberposts'=> -1
//                                        );
//                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
//                                        $kposts=get_posts($args);
//                                        foreach ( $kposts as $kpost ){
//                                            echo '<option value="' . $kpost->ID . '" '.selected($course_settings['vibe_forum'],$kpost->ID).'>' . $kpost->post_title . '</option>';
//                                        }
//                                    ?>
<!--                                    </select>-->
<!--                                    </span>-->
<!--                                </h3>-->
<!--                            </li>-->
                            <?php
                            }
                            ?>
                            <li data-help-tag="17"><label><?php _e('Hướng dẫn khóa học','vibe'); ?></label>
                            <div id="vibe_course_instructions" data-editable="true" data-name="content" data-max-length="1000" data-text-options="true">
                            <?php
                                echo  $course_settings['vibe_course_instructions'];
                            ?>
                            </div>
                            </li>
                            <li data-help-tag="18"><label><?php _e('Thông báo khi hoàn thành khóa học','vibe'); ?></label>
                            <div id="vibe_course_message" data-editable="true" data-name="content" data-max-length="1000" data-text-options="true">
                            <?php
                                echo  "Nhập vào thông báo hoàn thành khóa học cho học sinh ở đây";
                            ?>
                            </div>
                            </li>
                            <?php
                            $course_level = vibe_get_option('level');
                            if(isset($course_level) && $course_level){
                            ?>
                            <li data-help-tag="19"><label><?php _e('Trình độ khóa học','vibe'); ?></label>
                            <h3><?php _e('Chọn trình độ khóa học','vibe'); ?><span>
                            <select id="course-level-select" class="chosen">
                                <option><?php _e('Chọn trình độ khóa học','vibe'); ?></option>
                                <?php
                                if(isset($_GET['action']) && is_numeric($_GET['action'])){
                                    $id = $_GET['action'];
                                    $course_levels =wp_get_post_terms( $id, 'level');
                                    $course_level_id = $course_levels[0]->term_id;
                                }
                                $terms = get_terms('level',array('hide_empty' => false));
                                if(isset($terms) && is_array($terms)){
                                foreach($terms as $term){
                                    echo '<option value="'.$term->term_id.'" '.(isset($_GET['action'])?selected($course_level_id,$term->term_id):'').'>'.$term->name.'</option>';
                                }}
                                ?>
                            </select>
                            </span></h3>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                        </article>
                        <a id="save_course_settings" class="button hero"><?php _e('Lưu Cấu Hình','vibe'); ?></a>
                    </div>
                    <div style="display: none"><?php wp_editor('','content'); ?></div>
                    <div id="course_curriculum">
                        <h3 class="heading"><?php _e('ĐỀ CƯƠNG KHÓA HỌC','vibe'); ?></h3>
<!--                        <a id="add_course_section" data-help-tag="14" class="button primary small">--><?php //_e('ADD SECTION','vibe'); ?><!--</a>-->
<!--                        <a id="add_course_unit" data-help-tag="15" class="button primary small">--><?php //_e('ADD UNIT','vibe'); ?><!--</a>-->
<!--                        <a id="add_course_quiz" data-help-tag="15" class="button primary small">--><?php //_e('ADD QUIZ','vibe'); ?><!--</a>-->
                        <ul class="curriculum">

                        <?php
                        if(isset($_GET['action'])){
                            $curriculum = vibe_sanitize(get_post_meta($_GET['action'],'vibe_course_curriculum',false));

                            if(isset($curriculum) && is_array($curriculum)){
                                foreach($curriculum as $kid){
                                    if(is_numeric($kid)){
                                        if(get_post_type($kid) == 'unit'){

                                            // Chỉnh sửa nội dung Unit khi edit
                                            echo '<li class="old_unit"> <div class="set_backgroud_unit" style="position: block; height: 45px">

                                                <h3 class="title" data-id="'.$kid.'"><i class="icon-file"></i> '.get_the_title($kid).'</h3>
                                                </div>
                                                <div class="btn-group" style="z-index:9;margin-top:-40px">
                                                      <span style="font-size:7pt; margin: 5px 35px 0px -30px;" data-tooltip="Thêm nội dung cho bài học" data-id="'.$kid.'" class="edit_content btn btn-success">'.__('Thêm Nội Dung','wplms-front-end').'</span>
                                                     <span style="font-size:7pt; margin: 5px 35px 0px -30px;" data-tooltip="Cấu hình bài học" data-id="'.$kid.'" class="setting_content btn btn-success">'.__('Cấu hình','wplms-front-end').'</span>

                                                      <a  class="menu_delete "><i class="icon-x"></i></a>


                                                       <div class="header_content_unit">
                                                            Chọn nội dung

                                                            <a class="close-btn icon-close-off-2"></a>
                                                       </div>
                                                </div>

                                                 <div class="hidden_button_description" style="position: relative; width:100%; height: 100%;margin: 10px;">


                                                    <div class="box_shadow_content_unit">
                                                        <div class="add_content_unit ">
                                                            <div class="content_editor">
                                                                    <textarea class="text wp-editor-area" id="wisSW_Editor'.$kid.'" name="mytest">'.get_post_field('post_content',$kid).' </textarea>
                                                                    <span class="save_unit_post btn btn-success"> Save</span>
                                                                     <span class="insert-my-media btn btn-success" data-id="'.$kid.'">Add my media</span>
                                                            </div>

                                                             <div class="content_settings">
                                                                 <ul>
                                                                    <li>
                                                                          <span style="float: left; padding: 6px">Miễn phí : </span>
                                                                          <div class="onoffswitch">
                                                                              <input type="checkbox" name="onoffswitch" value="H" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                                                                              <label class="onoffswitch-label" for="myonoffswitch">
                                                                              <span class="onoffswitch-inner"></span>
                                                                              <span class="onoffswitch-switch"></span>
                                                                              </label>
                                                                          </div></br>
                                                                    </li>

                                                                    <li>
                                                                        <span>Loại bài học : </span>
                                                                        <select id="vibe_type">
                                                                            <option value="play" selected>'.__('Video','wplms-front-end').'</option>
                                                                            <option value="music-file-1">'.__('Audio','wplms-front-end').'</option>
                                                                            <option value="podcast">'.__('Podcast','wplms-front-end').'</option>
                                                                            <option value="text-document">'.__('General','wplms-front-end').'</option>
                                                                         </select></br>
                                                                    </li>

                                                                    <li>
                                                                        <span>'. __('Tổng thời gian bài học : ','wplms-front-end').'</span>
                                                                        <input type="number" class="small_box" id="vibe_duration" value="'. $unit_settings1['vibe_duration'].'" /> '. $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60); echo calculate_duration_time($unit_duration_parameter).'</br><p></p>
                                                                    </li>

                                                                    <li>
                                                                        <a style="width: 90%" id="save_unit_settings" class="course_button button full" data-id="'.$kid.'" data-course="'. $_GET['action'].'">'. __('LƯU CẤU HÌNH UNIT','wplms-front-end').'</a>
                                                                    </li>

                                                                 </ul>
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                                </li>
                                            ';

//                                            echo '<li><h3 class="title" data-id="'.$kid.'"><i class="icon-file"></i> '.get_the_title($kid).'</h3>
//                                                    <div class="btn-group">
//                                                     <a class="btn btn-success" style="font-size:7pt" href="'.get_permalink($kid).'edit/?id='.$_GET['action'].'" target="_blank" class="edit_unit">'.__('Edit Unit','vibe').'</a>
//                                                    <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
//                                                    <ul class="dropdown-menu" role="menu">
//                                                        <li><a class="remove">'.__('Remove','vibe').'</a></li>
//                                                        <li><a class="delete">'.__('Delete','vibe').'</a></li>
//                                                    </ul>
//                                                    </div>
//                                                </li>';
                                        }else if(get_post_type($kid)=='quiz'){
                                            // nội dung quiz
                                            echo '<li class="new_quiz"> <div class="set_backgroud_unit" style="position: block; height: 45px">
                                                    <h3 class="title" data-id="'.$kid.'"><i class="icon-file"></i> '.get_the_title($kid).'</h3>
                                                </div>
                                                <div class="btn-group" style="z-index:9;margin-top:-40px">
                                                      <span style="font-size:7pt; margin: 5px 35px;" data-id="'.$kid.'" class="add-question btn btn-success">'.__('Tạo Câu Hỏi','wplms-front-end').'</span>
                                                      <a  class="menu_delete "><i class="icon-x"></i></a>


                                                       <div class="header_content_unit header_content_quiz">
                                                            Chọn loại câu hỏi

                                                            <a class="icon-close-off-2 lecture_icon_quiz"></a>
                                                       </div>
                                                </div>

                                                 <div class="hidden_button_description" style="position: relative; width:100%; height: 100%;margin: 10px;">
                                                    <div class="box_shadow_content_unit box_shadow_content_quiz">
                                                        <div class="add_content_unit add_content_quiz">
                                                         <a class="lecture_icon icon-file multiple-choice "><span>Multiple choice</span> </a>
                                                         <a class="lecture_icon icon-file multiple-correct "><span>Multiple correct</span> </a>
                                                         <a class="lecture_icon icon-file true-false "><span>True/False</span> </a>
                                                         <a class="lecture_icon icon-file match-answer"><span>Match answer</span> </a>
                                                        </div>
                                                        </ hr>
                                                        <div class="titleQuestions">Danh sách các câu trắc nghiệm</div>
                                                         <ul class="NoiDung">';
                                            $quiz_question = vibe_sanitize(get_post_meta($kid,'vibe_quiz_questions',false));

                                            if(isset($quiz_question) && is_array($quiz_question)){
                                                foreach($quiz_question as $qs)
                                                {
                                                    $j=1;
                                                    foreach($qs as $kqs)
                                                    {
                                                        if(get_post_meta($kqs,'vibe_question_type',true)=='single')
                                                        {
                                                            echo '
                                                                    <li class="li-content"><div class="subtitleTN"><div class="question-title"><div class="nametitle" style="float:left"><b class="stt">'.$j.'.'.'</b><span class="name_title"> '.get_post_field('post_title',$kqs).'</span></div><div class="question-type">  multiple-choice</div><div class="question-edit"><span class="Sua">Sửa </span><span class="Xoa">Xóa</span></div></div><div style="clear:both"></div></div><div class="TNMultiple questions" style="display: none;"><input type="text" class="form-control txttl titleTN" placeholder="Tiêu đề trắc nghiệm" value="'.get_post_field('post_title',$kqs).'"><div style="height:50px"></div><textarea class="wp-editor-area editor" data-editable="true" rows="5" cols="40" name="content" placeholder="Nội dung câu hỏi trắc nghiệm">'.get_post_field('post_content',$kqs).'</textarea><form><div class="CauTraLoi">
                                                                                <div class="titleAnswer">Thêm câu trả lời</div>
                                                                                <ul class="DapAn">';
                                                            $question_option = vibe_sanitize(get_post_meta($kqs,'vibe_question_options',false));
                                                            $i=1;
                                                            if(isset($quiz_question) && is_array($quiz_question)){
                                                                foreach($question_option as $kop)
                                                                {
                                                                    echo '<li class="NoiDungDapAn">';
                                                                    if(get_post_meta($kqs,'vibe_question_answer',true)==$i)
                                                                    {
                                                                        echo '<div><input class="rdb" type="radio" name="rdb" checked="checked"><input type="text" class="form-control traloi txttl" placeholder="Nhập vào câu trả lời" value="'.$kop.'">
                                                                                        </div>';
                                                                        echo '<div>
                                                                                            <input type="text" class="form-control giaithich txt" placeholder="Giải thích câu trả lời" value="'.get_post_meta($kqs,'vibe_question_explaination',true).'">
                                                                                        </div>
                                                                                          <div class="XoaTraLoi">Xóa</div>';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '<div><input class="rdb" type="radio" name="rdb" ><input type="text" class="form-control traloi txttl" placeholder="Nhập vào câu trả lời" value="'.$kop.'">
                                                                                        </div>';
                                                                        echo '<div>
                                                                                            <input type="text" class="form-control giaithich txt" placeholder="Giải thích câu trả lời" >
                                                                                        </div>
                                                                                          <div class="XoaTraLoi">Xóa</div>';

                                                                    }
                                                                    $i++;
                                                                }
                                                                echo '</li>';
                                                                echo'</ul><div>
                                                                                  <input type="button" class="LuuCauHoi button btn-success btn-waring disappearbtn" value="Tạo câu hỏi">
                                                                                  <input type="button" class="CapNhapCauHoi button btn-success id-question" value="Lưu thay đổi" data-id="'.$kqs.'">
                                                                                  <input type="button" class="HuyCauHoi button btn-success" value="Hủy">
                                                                               </div>

                                                                    </div></form></div></li>';
                                                            }

                                                        }
                                                        else if(get_post_meta($kqs,'vibe_question_type',true)=='truefalse')
                                                        {
                                                            echo '<li class="li-content"><div class="subtitleTN" style="display: block;"><div class="question-title"><div class="nametitle" style="float:left"><b class="stt">'.$j.'.'.'</b><span class="name_title"> '.get_post_field('post_title',$kqs).'</span></div><div class="question-type"> True-False</div><div class="question-edit"><span class="Sua">Sửa </span><span class="Xoa">Xóa</span></div></div><div style="clear:both"></div></div><div class="TNTrueFalse questions" style="display: none;"><input type="text" class="form-control txttl titleTN" placeholder="Tiêu đề trắc nghiệm" value="'.get_post_field('post_title',$kqs).'"><div style="height:50px"></div><textarea class="wp-editor-area editor" data-editable="true" rows="5" cols="40" name="content" placeholder="Nội dung câu hỏi trắc nghiệm">'.get_post_field('post_content',$kqs).'</textarea><form><div class="CauTraLoi">
                                                                        <div class="titleAnswer">Chọn đáp án cho câu hỏi</div>
                                                                        <ul class="DapAn">';
                                                            if(get_post_meta($kqs,'vibe_question_answer',true)=='1')
                                                            {
                                                                echo'<li>
                                                                                        <input class="rdbTrueFalse" type="radio" name="rdbTrueFalse" checked="checked">
                                                                                        <span class="span-true-false">Đúng</span>
                                                                                 </li>
                                                                                 <li>
                                                                                        <input class="rdbTrueFalse" type="radio" name="rdbTrueFalse" >
                                                                                        <span class="span-true-false">Sai</span>
                                                                                 </li>';
                                                            }
                                                            else
                                                            {
                                                                echo'<li>
                                                                                        <input class="rdbTrueFalse" type="radio" name="rdbTrueFalse" >
                                                                                        <span class="span-true-false">Đúng</span>
                                                                                 </li>
                                                                                 <li>
                                                                                        <input class="rdbTrueFalse" type="radio" name="rdbTrueFalse" checked="checked">
                                                                                        <span class="span-true-false">Sai</span>
                                                                                 </li>';
                                                            }

                                                            echo'</ul>
                                                                        <div>
                                                                          <input type="button" class="LuuTrueFalse button btn-success btn-waring disappearbtn" value="Tạo câu hỏi">
                                                                          <input type="button" class="CapNhatTrueFalse button btn-success id-question" value="Lưu thay đổi" data-id="'.$kqs.'">
                                                                          <input type="button" class="HuyCauHoi button btn-success" value="Hủy">
                                                                       </div>
                                                                    </div></form></div></li>';

                                                        }
                                                        else if(get_post_meta($kqs,'vibe_question_type',true)=='multiple')
                                                        {
                                                            echo '<li class="li-content"><div class="subtitleTN"><div class="question-title"><div class="nametitle" style="float:left"><b class="stt">'.$j.'.'.'</b><span class="name_title"> '.get_post_field('post_title',$kqs).'</span></div><div class="question-type"> Multiple-correct </div><div class="question-edit"><span class="Sua">Sửa </span><span class="Xoa">Xóa</span></div></div><div style="clear:both"></div></div><div class="TNMultiplecorrect questions" style="display: none;"><input type="text" class="form-control txttl titleTN" placeholder="Tiêu đề trắc nghiệm" value="'.get_post_field('post_title',$kqs).'"><div style="height:50px"></div><textarea class="wp-editor-area editor" data-editable="true" rows="5" cols="40" name="content" placeholder="Nội dung câu hỏi trắc nghiệm">'.get_post_field('post_content',$kqs).'</textarea><form><div class="CauTraLoi">
                                                                        <div class="titleAnswer">Thêm câu trả lời</div>

                                                                        <ul class="DapAn">';
                                                            $question_option = vibe_sanitize(get_post_meta($kqs,'vibe_question_options',false));
                                                            $i=1;
                                                            $dapan=get_post_meta($kqs,'vibe_question_answer',true);

                                                            $dapanitem = explode(",",$dapan);
                                                            if(isset($quiz_question) && is_array($quiz_question)){

                                                                foreach($question_option as $kop)
                                                                {
                                                                    echo '<li class="NoiDungDapAn">';
                                                                    $dem=0;
                                                                    for($k=0;$k<count($dapanitem);$k++)
                                                                    {
                                                                        if($dapanitem[$k]==$i)
                                                                        {
                                                                            $dem++;

                                                                        }

                                                                    }
                                                                    if($dem!=0)
                                                                    {
                                                                        echo '
                                                                                    <div>
                                                                                        <input type="checkbox" name="ckbmultilple" class="ckbmultilple" checked="checked">
                                                                                        <input type="text" class="form-control ckbtraloi txttl" placeholder="Nhập vào câu trả lời" value="'.$kop.'">
                                                                                    </div>
                                                                                      <div class="ckbXoaTraLoi">Xóa</div>
                                                                                ';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '
                                                                                    <div>
                                                                                        <input type="checkbox" name="ckbmultilple" class="ckbmultilple">
                                                                                        <input type="text" class="form-control ckbtraloi txttl" placeholder="Nhập vào câu trả lời" value="'.$kop.'">
                                                                                    </div>
                                                                                      <div class="ckbXoaTraLoi">Xóa</div>
                                                                               ';
                                                                    }

                                                                    $i++;
                                                                    echo '</li>';
                                                                }
                                                                /*  echo '              <div>
                                                                                          <input type="checkbox" name="ckbmultilple" class="ckbmultilple">
                                                                                          <input type="text" class="form-control ckbtraloi txttl" placeholder="Nhập vào câu trả lời" >
                                                                                      </div>
                                                                                        <div class="ckbXoaTraLoi">Xóa</div>
                                                                                 ';
                                                                  */
                                                                echo'</ul><div>';
                                                                echo '         <textarea class="wp-editor-area editor txtGiaiThich" data-editable="true" rows="5" cols="40" name="content" placeholder="Nhập vào nội dung giải thích">'.get_post_meta($kqs,'vibe_question_explaination',true).'</textarea>';
                                                                echo'          <input type="button" class="LuuMultipleCorrect button btn-success btn-waring disappearbtn" value="Tạo câu hỏi">
                                                                   <input type="button" class="CapNhapMultipleCorrect button btn-success id-question" value="Lưu thay đổi" data-id="'.$kqs.'">
                                                                   <input type="button" class="HuyCauHoi button btn-success" value="Hủy">
                                                                   </div>

                                                                    </form></div></li>';
                                                            }


                                                        }

                                                        $j++;
                                                    }

                                                }

                                            }
                                            echo '</ul></div></div></li>';


                                        }

                                    } else{
//                                        echo '<li class="new_section"><h3>'.$kid.'</h3>
//                                                <div class="btn-group">
//                                                <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
//                                                <ul class="dropdown-menu" role="menu">
//                                                        <li><a class="remove">'.__('Remove','vibe').'</a></li>
//                                                </ul>
//                                                </div>
//                                              </li>
                                        echo     '<li class="new_section">
                                                    <span class="show_section"></span>
                                                    <input type="text" style="width: 350px;" class="section " value="'.$kid.'" /><a class="rem"><i class="icon-x"></i></a>
                                                    </li>
                                              '
                                        ;
                                    }
                                }
                            }
                        }
                        ?>
                        </ul>
                        <ul id="hidden_base">
                            <li class="new_section">
                                <span class="show_section"></span>
                                <input type="text" style="width: 350px;" class="section " /><a class="rem"><i class="icon-x"></i></a>
                            </li>
                            <li class="new_unit" data-help-tag="16">
                                <div class="set_backgroud_unit" style="position: block; height: 45px">
<!--                                <select>-->
<!--                                    <option value="">--><?php //_e('SELECT A UNIT','vibe'); ?><!--</option>-->
<!--                                    <option value="add_new">--><?php //_e('ADD NEW UNIT','vibe'); ?><!--</option>-->
<!--                                    --><?php
//                                        $args= array(
//                                            'post_type'=> 'unit',
//                                            'numberposts'=> 999
//                                            );
//
//                                        $args = apply_filters('wplms_backend_cpt_query',$args);
//                                        $posts=get_posts($args);
//                                        foreach ( $posts as $post ){
//                                            echo '<option value="' . $post->ID . '"  data-link="'.get_permalink($post->ID).'?">' . $post->post_title . '</option>';
//                                        }
//                                        wp_reset_postdata();
//                                    ?>
<!--                                </select>-->
                                    <ul class="new_unit_actions">
                                        <li><input style="width:287px" type="text" name="new_unit[]" class="new_unit_title" placeholder="<?php _e('Thêm tiêu đề bài học','vibe'); ?>"/></li>
                                        <li>
                                            <div class="btn-group">
                                                <a class="publish btn btn-success"><?php _e('Đồng Ý','vibe'); ?></a>
                                                <span class="remove_new btn btn-success">remove</span>

                                                <!--                                            <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>-->
                                                <!--                                          <ul class="dropdown-menu" role="menu">-->
                                                <!--                                            <li><a class="publish">--><?php //_e('Publish','vibe'); ?><!--</a></li>-->

                                                <!--                                          </ul>-->
                                            </div>
                                        </li>
                                    </ul>
                                <div class="btn-group unit_actions">
                                    <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="" target="_blank" class="edit_unit"><?php _e('Sửa Nội Dung','vibe'); ?></a></li>
                                        <li><a class="remove"><?php _e('Xóa','vibe') ?></a></li>
                                    </ul>
                                </div>

                                </div>
                            </li>
                            <li class="new_quiz" data-help-tag="18">
<!--                                <select>-->
<!--                                    <option value="">--><?php //_e('SELECT A QUIZ','vibe'); ?><!--</option>-->
<!--                                    <option value="add_new">--><?php //_e('ADD NEW QUIZ','vibe'); ?><!--</option>-->
<!--                                    --><?php
//                                        $args= array(
//                                            'post_type'=> 'quiz',
//                                            'numberposts'=> 999
//                                            );
//
//                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
//                                        $posts=get_posts($args);
//                                        foreach ( $posts as $post ){
//                                            echo '<option value="' . $post->ID . '" data-link="'.get_permalink($post->ID).'?id='.$_GET['action'].'&">' . $post->post_title . '</option>';
//                                        }
//                                        wp_reset_postdata();
//                                    ?>
<!--                                </select>-->
                                <ul class="new_quiz_actions">
                                    <li><input type="text" name="new_quiz[]" class="new_quiz_title" /></li>
                                    <li>
                                        <div class="btn-group">
                                            <div>
                                                <a class="publish btn btn-waring"><?php _e('Đồng Ý','vibe'); ?></a>
                                                <a class="remove_new btn btn-waring"><?php _e('Xóa','vibe'); ?></a>
                                            </div>
                                            <!--                                          <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>-->
                                            <!--                                          <ul class="dropdown-menu" role="menu">-->
                                            <!--                                            <li><a class="publish">--><?php //_e('Publish','vibe'); ?><!--</a></li>-->
                                            <!--                                            <li><a class="remove_new">--><?php //_e('Remove','vibe'); ?><!--</a></li>-->
                                            <!--                                          </ul>-->
                                        </div>
                                    </li>
                                </ul>
                                <div class="btn-group quiz_actions">
                                    <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="" target="_blank" class="edit_quiz"><?php _e('Sửa Câu Hỏi','vibe'); ?></a></li>
                                        <li><a class="remove"><?php _e('Xóa','vibe') ?></a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>

                        <a id="add_course_quiz" data-help-tag="15" class="button primary small"><?php _e('Tạo Câu Hỏi','vibe'); ?></a>
                        <a id="add_course_unit" data-help-tag="15" class="button primary small"><?php _e('Tạo Bài Học','vibe'); ?></a>
                        <a id="add_course_section" data-help-tag="14" class="button primary small"><?php _e('Tạo Chương','vibe'); ?></a>
                        <a id="save_course_curriculum" class="button hero"><?php _e('Lưu Đề Cương','vibe'); ?></a>

                    </div>
                    <div id="course_pricing">
                        <h3 class="heading"><?php _e('Định Giá Khóa Học','vibe'); ?></h3>
                        <ul class="course_pricing">
                            <li><h3><?php _e('Miễn Phí','vibe'); ?><span>
                                        <div class="switch" data-help-tag="19">
                                            <input type="radio" class="switch-input vibe_course_free" name="vibe_course_free" value="H" id="disable_free" <?php checked($course_pricing['vibe_course_free'],'H'); ?>>
                                            <label for="disable_free" class="switch-label switch-label-off"><?php _e('No','vibe');?></label>
                                            <input type="radio" class="switch-input vibe_course_free" name="vibe_course_free" value="S" id="enable_free" checked <?php checked($course_pricing['vibe_course_free'],'S'); ?>>
                                            <label for="enable_free" class="switch-label switch-label-on"><?php _e('Yes','vibe');?></label>
                                            <span class="switch-selection online"></span>
                                          </div>
                                        </span>
                                    </h3>
                            </li>
                            <?php
                             if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) {

                            ?>
                                <li class="course_product" data-help-tag="19">
                                    <h3><?php _e('Đặt giá Khóa Học','vibe'); ?><span>
                                        <select id="vibe_product" class="chosen">
                                            <option value=""><?php _e('Chọn khóa học','vibe'); ?></option>
                                            <option value="none"><?php _e('Không có sản phẩm','vibe'); ?></option>
                                            <option value="add_new"><?php _e('Thêm sản phẩm mới','vibe'); ?></option>
                                            <?php
                                                $args= array(
                                                    'post_type'=> 'product',
                                                    'numberposts'=> 999
                                                    );

                                                $args = apply_filters('wplms_fontend_cpt_query',$args);
                                                $posts=get_posts($args);
                                                foreach ( $posts as $post ){
                                                    echo '<option value="' . $post->ID . '" '.selected($course_pricing['vibe_product'],$post->ID).'>' . $post->post_title . '</option>';
                                                }
                                                wp_reset_postdata();
                                            ?>
                                        </select>
                                    </span>
                                    </h3>
                                </li>
                                <li class="new_product">
                                    <h3><?php _e('Loại sản phẩm','vibe'); ?><span>
                                        <div class="switch switch-subscription">
                                                <input type="radio" class="switch-input vibe_subscription" name="vibe_subscription" value="H" id="disable_sub" <?php checked($course_pricing['vibe_subscription'],'H'); ?>>
                                                <label for="disable_sub" class="switch-label switch-label-off"><?php _e('Full Course','vibe');?></label>
                                                <input type="radio" class="switch-input vibe_subscription" name="vibe_subscription" value="S" id="enable_sub" <?php checked($course_settings['vibe_subscription'],'S'); ?>>
                                                <label for="enable_sub" class="switch-label switch-label-on"><?php _e('Subscription','vibe');?></label>
                                                <span class="switch-selection online"></span>
                                              </div>
                                    </span></h3>
                                </li>
                                <li class="new_product">
                                    <h5><?php _e('Set Product Price','vibe'); ?><span>
                                    <input type="text" id="product_price" class="small_box" />
                                    <?php echo get_woocommerce_currency(); ?></span></h5>
                                    <h5 class="product_duration"><?php _e('Set Subscription duration','vibe')?><span>
                                        <input type="number" id="product_duration" class="small_box" />
                                        <?php $product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400); echo calculate_duration_time($product_duration_parameter); ?>
                                    </span></h5>
                                </li>

                            <?php
                            }
                            ?>
                            <?php
                                if(isset($_GET['action']) && is_numeric($_GET['action']))
                                    $course_id=$_GET['action'];
                                else
                                    $course_id=0;

                                do_action('wplms_front_end_pricing_content',$course_id);
                            ?>
                            <?php
                                if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && function_exists('pmpro_getAllLevels')) {
                                  $levels=pmpro_getAllLevels(); // Get all the PMPro Levels
                                  ?>
                                  <li class="course_membership"><h3><?php _e('Set Course Memberships','vibe'); ?><span>
                                          <select id="vibe_pmpro_membership" class="chosen" multiple>
                                              <?php
                                              if(isset($levels) && is_array($levels)){
                                                  foreach($levels as $level){
                                                      if(!is_Array($course_pricing['vibe_pmpro_membership']))
                                                          $course_pricing['vibe_pmpro_membership'] = array();

                                                  if(is_object($level))
                                                      echo '<option value="'.$level->id.'" '.(in_array($level->id,$course_pricing['vibe_pmpro_membership'])?'selected':'').'>'.$level->name.'</option>';
                                                  }
                                              }
                                              ?>
                                          </select>
                                      </span>
                                      </h3>
                                  </li>
                              <?php
                              }
                            ?>
                            <li>
                                <a id="save_pricing" class="button hero"><?php _e('Lưu Giá Khóa Học','vibe'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div id="course_live">
                        <h3 class="heading"><?php _e('Kích Hoạt'); ?></h3>
                        <?php
                            if(isset($_GET['action'])){
                                $post_status = get_post_status($_GET['action']);
                                echo '<a id="publish_course" class="button big hero">'.__('Kích Hoạt','vibe').'</a>';
                            }else{
                        ?>
                        <?php   $new_course_status = vibe_get_option('new_course_status');
                                if(isset($new_course_status) && $new_course_status == 'publish')
                                    echo '<a id="publish_course" class="button big hero">'.__('Kích Hoạt','vibe').'</a>';
                                else
                                    echo '<a id="publish_course" class="button big hero">'.__('Gữi Phê Duyệt','vibe').'</a>';
                            }
                        ?>
                    </div>
                    <?php wp_nonce_field('create_course'.$user_id,'security'); ?>
                    <?php
                    if(isset($_GET['action'])){
                        echo '<input type="hidden" id="course_id" value="'.$_GET['action'].'" />';
                    }

                    // Input trung gian để lấy giá trị check_createcourse trên trình duyệt
                    if(isset($_GET['check_create'])){
                        echo '<input type="hidden" id="check_create_course" value="'.$_GET['action'].'" />';
                    }
                    ?>
                </div>

            </div>
            <div class="col-md-3 col-sm-3">
                <div class="course-create-help">
                    <ul id="create_course_help" class="active">
                        <li class="active"><span>1</span><?php _e('Click on text to enter a Course title, delete the existing text in the title','vibe'); ?></li>
                        <li><span>2</span> <?php _e('Select a Course Category, or Add a New one','vibe'); ?><br />&nbsp;</li>
                        <li><span>3</span> <?php _e('Select or upload a course image, this image is used as Course avatar.','vibe'); ?></li>
                        <li><span>4</span> <?php _e('Enter a Short description about the course, the full description can be added later on. Start by deleting the existing text in the title','vibe'); ?></li>
                        <li <?php echo ((isset($linkage) && $linkage)?'':'style="display:none;"'); ?>><span>5</span> <?php _e('Linkage greatly reduces the unit/quiz/question lists loaded on the page. Once a Linkage term is selected, the lists will only units/quizzes/questions connected to the same linkage term. If editing a course , save and refresh after selecting a Linkage term.','vibe'); ?></li>
                        <li <?php echo (isset($_GET['action'])?'':'style="display:none;"'); ?>><span><?php echo ((isset($linkage) && $linkage)?'6':'5'); ?></span> <?php _e('A Offline Course is not visible to students in the course directory.','vibe'); ?></li>
                    </ul>

                    <ul id="course_settings_help">
                        <li style="display: none"></li>
                        <li style="display: none"></li>
                        <li class="active"><span>1</span><?php _e('Set a pre-required course. A Pre Course should be completeted before a student can access this course','vibe'); ?></li>
                        <li style="display: none"></li>
                        <li><span>2</span><?php _e('Set a Course Certificate. Set certificate percentage marks which a student should achieve to get the course certificate. Select a certificate template.','vibe'); ?></li>
                        <li><span>3</span><?php _e('Set a course Badge. Set percentage marks which a student should get to get the course badge. Upload the Badge image.','vibe'); ?></li>
                        <li style="display: none"></li>
                        <li style="display: none"></li>
                        <li style="display: none"></li>
                        <li style="display: none"></li>
                        <li><span>4</span><?php _e('Enter Course specific instructions for students to take this course.','vibe'); ?></li>
                        <li><span>5</span><?php _e('Enter message which student see after submitting the course.','vibe'); ?></li>
                        <?php
                        $level = vibe_get_option('level');
                        if(isset($level) & $level){
                        ?>
                        <li><span>6</span><?php _e('Select a level for the course.','vibe'); ?></li>
                        <?php
                        }
                        ?>
                    </ul>
<!--                    <ul id="course_curriculum_help">-->
<!--                        <li class="active"><span>1</span>--><?php //_e('Start building curriculum by adding Units, Quizzes and sections.','vibe'); ?><!--</li>-->
<!--                        <li><span>2</span>--><?php //_e('After adding a new unit or quiz, make sure you publish it','vibe'); ?><!--</li>-->
<!--                        <li><span>3</span>--><?php //_e('Save the curriculum only when the button is green.','vibe'); ?><!--</li>-->
<!--                    </ul>-->
                    <ul id="course_pricing_help">
                        <li class="active"><span>1</span><?php _e('Connect the course with a product. A product defines the pricing of a course.<br /><br />a. Enter the Price of the product to set the price of your course.<br />b. Select the type of product.<br /><br /> If product is set to subscription mode, you need to set the subscription duration for the product. <br />A student purchasing a Product with subscription gets access for the course for the subscription duration.<br /> A student purchasing a product without subscription gets access for the course for full course duration as entered in course settings.','vibe'); ?></li>
                    </ul>
                    <ul id="course_live_help">
                        <li class="active"><span>1</span><?php _e('Go live with your course if "Publish" access is granted by Administrator you course will be live as soon as you click on Go Live button.','vibe'); ?></li>
                    </ul>
                    <!--khải thêm vào mục tiêu-->
                    <ul id="goal_course_help">
                        <li id="li1" class="active"><span>1</span><div class="noted" ><?php _e('Lưu ý: Bắt đầu bằng một động từ. Bao gồm các thông tin chi tiết về các kỹ năng cụ thể học viên sẽ được học và nơi học sinh có thể áp dụng chúng.','vibe'); ?></div>
                            <div class="noted" ><?php _e('Ví dụ:Làm được 1 ứng dụng chạy trên thiết bị di động thay vì "Tìm hiểu ứng dụng trên thiết bị di động. ','vibe'); ?></div>
                        </li>
                        <li id="li2" ><span>2</span>
                            <div class="noted" ><?php _e('Lưu ý: Giảng viên cần liệt kê rỏ ra đối tượng học viên nào thích hợp đề học khóa học này.','vibe'); ?></div>
                            <div class="noted" ><?php _e('Ví dụ: Những học viên đã có sẵn nền tảng cơ bản về lập trình hướng đối tượng mới có thể học khóa học này. ','vibe'); ?></div>
                        </li>
                        <li id="li3"><span>3</span>   <div class="noted" ><?php _e('Lưu ý: Giảng viên cần liệt kê rỏ ra tất cả những yêu cầu để có thể học được khóa học này. Cụ thể nhưng cần cài đặt phần mềm nào hay công cụ nào để học được.','vibe'); ?></div>
                            <div class="noted" ><?php _e('Ví dụ: Để học được khóa học này học viên cần cài đặt chương trình Visual Studio, SQL Server ... . ','vibe'); ?></div>
                        </li>
                    </ul>
                    <!--khải end-->
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
do_action('wplms_after_create_course_page');
get_footer();
?>
