<?php

if ( !defined( 'VIBE_URL' ) )
    define('VIBE_URL',get_template_directory_uri());

add_action('wp_enqueue_scripts', 'vibe_wplms_child_js');
function vibe_wplms_child_js(){
    wp_enqueue_script( 'child-custom-js', get_stylesheet_directory_uri().'/custom.js',array('jquery'));
//    wp_enqueue_script('child-nicEdit',get_stylesheet_directory_uri().'/nicEdit.js',array('jquery'));
    wp_enqueue_script( 'child-nganluong-js', get_stylesheet_directory_uri().'/includes/nganluong.apps.mcflow.js',array('jquery'));
    wp_dequeue_script('wplms-front-end-js');
    wp_dequeue_script('bp-html2canvas-js');
}

?>

<?php
// đăng ký một vị trí menu trong admin
function dangkymenudoc(){
    register_nav_menu('menu_doc',__('Menu Dọc'));
}
add_action('init','dangkymenudoc');

//Cập nhật lại tình user khi đăng ký thành công
function disable_validation( $user_id ) {
    global $wpdb;
    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->users SET user_status = 0 WHERE ID = %d", $user_id ) );
}
add_action( 'bp_core_signup_user', 'disable_validation' );
function fix_signup_form_validation_text() {
    return false;
}
add_filter( 'bp_registration_needs_activation', 'fix_signup_form_validation_text' );

//tự động đăng nhập khi đăng ký
function auto_login_new_user( $user_id ) {
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    // You can change home_url() to the specific URL,such as
    //wp_redirect( 'http://www.wpcoke.com' );
    wp_redirect( home_url().'/danh-sach-khoa-hoc' );
    exit;
}
add_action( 'user_register', 'auto_login_new_user' );

// cho phép cho phép chuyển trang dang submit
add_action('init', 'do_output_buffer');
function do_output_buffer() {
    ob_start();
}
?>



<?php

add_filter('wp_enqueue_scripts','child_wplms_front_end_enqueue_scripts');
function child_wplms_front_end_enqueue_scripts(){
    if(function_exists('vibe_get_option')){
        $edit_course = vibe_get_option('create_course');
        if(is_numeric($edit_course) && is_page($edit_course))
            wp_enqueue_media($edit_course);
    }

//    wp_enqueue_style( 'liveedit-css', plugins_url( 'wplms-front-end/css/jquery-liveedit.css'));
    wp_enqueue_style( 'wplms-front-end-css', plugins_url( 'wplms-front-end/css/wplms_front_end.css'),array(),'1.9.6');
    wp_enqueue_script( 'liveedit-js', plugins_url( 'wplms-front-end/js/jquery-liveedit.js'));
    wp_enqueue_script( 'child-wplms-front-end-js', get_stylesheet_directory_uri().'/wplms_front_end.js', array( 'jquery-ui-core','jquery-ui-sortable','jquery-ui-slider','jquery-ui-datepicker' ) );
    $translation_array = array(
        'course_title' => __( 'Please change the course title','wplms-front-end' ),
        'create_course_confrim' => __( 'This will create a new course in the site, do you want to continue ?','wplms-front-end' ),
        'create_course_confrim_button' => __('Yes, create a new course','wplms-front-end'),
        'save_course_confrim' => __( 'This will overwrite the previous course settings, do you want to continue ?','wplms-front-end' ),
        'save_course_confrim_button' => __('Save course','wplms-front-end'),
        'create_unit_confrim' => __( 'This will create a new unit in the site, do you want to continue ?','wplms-front-end' ),
        'create_unit_confrim_button' => __('Yes, create a new unit','wplms-front-end'),
        'save_unit_confrim' => __( 'This will overwrite the existing unit settings, do you want to continue ?','wplms-front-end' ),
        'saveunit_confrim_button' => __('Yes, save unit settings','wplms-front-end'),
        'create_question_confrim' => __( 'This will create a new question in the site, do you want to continue ?','wplms-front-end' ),
        'create_question_confrim_button' => __('Yes, create a new question','wplms-front-end'),
        'create_quiz_confrim' => __( 'This will create a new quiz in the site, do you want to continue ?','wplms-front-end' ),
        'create_quiz_confrim_button' => __('Yes, create a new quiz','wplms-front-end'),
        'save_quiz_confrim' => __( 'This will overwrite the existing quiz settings, do you want to continue ?','wplms-front-end' ),
        'save_quiz_confrim_button' => __('Yes, save quiz settings','wplms-front-end'),
        'delete_confrim' => __( 'This will delete the unit/quiz from your site, do you want to continue ?','wplms-front-end' ),
        'delete_confrim_button' => __('Continue','wplms-front-end'),
        'save_confrim' => __( 'This will overwrite the previous settings, do you want to continue ?','wplms-front-end' ),
        'save_confrim_button' => __('Save','wplms-front-end'),
        'create_assignment_confrim' => __( 'This will create a new assignment in the site, do you want to continue ?','wplms-front-end' ),
        'create_assignment_confrim_button' => __('Yes, create a new assignment','wplms-front-end')
    );
    wp_localize_script( 'child-wplms-front-end-js', 'wplms_front_end_messages', $translation_array );
}
?>

<?php
remove_action('wp_footer', 'bp_course_add_js');
add_filter('wp_footer','child_course_add_js');
function child_course_add_js(){
    global $bp;
    if ( ! function_exists( 'vibe_logo_url' ) ) return; // Checks if WPLMS is active in current site in WP Multisite
//        wp_enqueue_style( 'bp-course-graph', plugins_url( '/vibe-course-module/includes/css/graph.css' ) );

    //wp_enqueue_script( 'bp-confirm-js', plugins_url( '/vibe-course-module/includes/js/jquery.confirm.min.js' ) );
    //wp_enqueue_script( 'bp-html2canvas-js', plugins_url( '/vibe-course-module/includes/js/html2canvas.js' ) );
    wp_enqueue_script( 'bp-html2canvas-js', get_stylesheet_directory_uri().'/course-module-js.min.js' );

    wp_enqueue_style( 'bp-course-css', plugins_url( '/vibe-course-module/includes/css/course_template.css' ) );
    wp_enqueue_script( 'bp-course-js', plugins_url( '/vibe-course-module/includes/js/course.js' ),array('jquery','wp-mediaelement','jquery-ui-core','jquery-ui-sortable','jquery-ui-droppable'));
    $color=bp_wplms_get_theme_color();
    $single_dark_color=bp_wplms_get_theme_single_dark_color();
    $translation_array = array(
        'too_fast_answer' => __( 'Too Fast or Answer not marked.','vibe' ),
        'answer_saved' => __( 'Answer Saved.','vibe' ),
        'processing' => __( 'Processing...','vibe' ),
        'saving_answer' => __( 'Saving Answer...please wait','vibe' ),
        'remove_user_text' => __( 'This step is irreversible. Are you sure you want to remove the User from the course ?','vibe' ),
        'remove_user_button' => __( 'Confirm, Remove User from Course','vibe' ),
        'cancel' => __( 'Cancel','vibe' ),
        'reset_user_text' => __( 'This step is irreversible. All Units, Quiz results would be reset for this user. Are you sure you want to Reset the Course for this User?','vibe' ),
        'reset_user_button' => __( 'Confirm, Reset Course for this User','vibe' ),
        'quiz_reset' => __( 'This step is irreversible. All Questions answers would be reset for this user. Are you sure you want to Reset the Quiz for this User? ','vibe' ),
        'quiz_reset_button' => __( 'Confirm, Reset Quiz for this User','vibe' ),
        'marks_saved' => __( 'Marks Saved','vibe' ),
        'quiz_marks_saved' => __( 'Quiz Marks Saved','vibe' ),
        'submit_quiz' => __( 'Submit Quiz','vibe' ),
        'sending_messages' => __( 'Sending Messages ...','vibe' ),
        'adding_students' => __( 'Adding Students to Course ...','vibe' ),
        'successfuly_added_students' => __( 'Students successfully added to Course','vibe' ),
        'unable_add_students' => __( 'Unable to Add students to Course','vibe' ),
        'select_fields' => __( 'Please select fields to download','vibe' ),
        'download' => __( 'Download','vibe' ),
        'theme_color' => $color,
        'single_dark_color' => $single_dark_color,
        'for_course' => __( 'for Course','vibe' ),
    );
    wp_localize_script( 'bp-course-js', 'vibe_course_module_strings', $translation_array );
}
?>

<?php
//remove_action('wp_ajax_create_unit','create_unit');
//remove_action('wp_ajax_create_quiz',array(WPLMS_Front_End::instance(),'create_unit'));
add_action('wp_ajax_child_create_unit','child_create_unit');
function child_create_unit(){

    $user_id= get_current_user_id();
    $course_id =$_POST['course_id'];
    $unit_title = stripslashes($_POST['unit_title']);
    wp_enqueue_script('content-min-css',includes_url('/js/tinymce/skins/lightgray/content.min.css'));
    wp_enqueue_script('content-min-css',includes_url('/css/dashicons.min.css?ver=4.0.1'));
    wp_enqueue_script('content-min-css',includes_url('/js/tinymce/skins/wordpress/wp-content.css?ver=4.0.1'));
    if(!isset($unit_title) || count($unit_title) < 2 && $unit_title == ''){
        _e('Can not have a blank Unit ','wplms-front-end');
        die();
    }

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'create_course'.$user_id)  || !current_user_can('edit_posts')){
        _e('Security check Failed. Contact Administrator.','wplms-front-end');
        die();
    }

    if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
        _e('Invalid Course id, please edit a course','wplms-front-end');
        die();
    }

    $the_post = get_post($course_id);
    if($the_post->post_author != $user_id && !current_user_can('manage_options')){
        _e('Invalid Course Instructor','wplms-front-end');
        die();
    }

    $unit_settings = array(
        'post_title' => $unit_title,
        'post_content' => $unit_title,
        'post_status' => 'publish',
        'post_type' => 'unit',
    );
    $unit_settings=apply_filters('wplms_front_end_unit_vars',$unit_settings);
    $unit_id = wp_insert_post($unit_settings);

    $unit_settings1 = array(
        'vibe_type' => 'text-document',
        'vibe_free' => 'H',
        'vibe_duration' => 2,
        'vibe_assignment' => array(),
        'vibe_forum' => ''
    );
    $unit_settings1=apply_filters('wplms_front_end_unit_settings',$unit_settings1);

//        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>

    echo ' <div class="set_backgroud_unit" style="position: block; height: 45px">
                <h3 class="title" data-id="'.$unit_id.'"><i class="icon-file"></i> '.$unit_title.'</h3>
                </div>
                <div class="btn-group" style="z-index:9;margin-top:-40px">
                      <span style="font-size:7pt; margin: 5px 35px 0px -30px;" data-tooltip="Thêm nội dung cho bài học" data-id="'.$unit_id.'" class="edit_content btn btn-success">'.__('Thêm nội dung','wplms-front-end').'</span>
                      <span style="font-size:7pt; margin: 5px 35px 0px -30px;" data-tooltip="Cấu hình bài học" data-id="'.$unit_id.'" class="setting_content btn btn-success">'.__('Cấu hình','wplms-front-end').'</span>

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
                                    <div class="text" id="'.$unit_id.'" name="'.$unit_id.'"> </div>
                                    <span class="save_unit_post btn btn-success"> Lưu</span>
                                     <span class="insert-my-media btn btn-success" data-id="'.$unit_id.'">Thêm hình ảnh</span>
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
                                        <a style="width: 90%" id="save_unit_settings" class="course_button button full" data-id="'.$unit_id.'" data-course="'. $course_id.'">'. __('LƯU CẤU HÌNH UNIT','wplms-front-end').'</a>
                                    </li>

                                 </ul>
                            </div>

                        </div>


                    </div>

                </div>
            ';



    //Linkage
    $linkage = vibe_get_option('linkage');
    if(isset($linkage) && $linkage){
        $course_linkage=wp_get_post_terms( $course_id, 'linkage',array("fields" => "names"));
        if(isset($course_linkage) && is_array($course_linkage))
            wp_set_post_terms( $unit_id, $course_linkage, 'linkage' );
    }
    die();

}

?>



<?php


// tao nút custom thanh toán
function nuthanhtoan(){
    global $post;
    if(isset($id) && $id)
        $course_id=$id;
    else
        $course_id=get_the_ID();

    // Free Course
    $free_course= get_post_meta($course_id,'vibe_course_free',true);

    if(!is_user_logged_in() && vibe_validate($free_course)){
        echo apply_filters('wplms_course_non_loggedin_user','<a href="#" class="unlogin course_button button full">'.__('TAKE THIS COURSE','vibe').'</a>');
        return;
    }

    $take_course_page_id=vibe_get_option('take_course_page');

    if(function_exists('icl_object_id'))
        $take_course_page_id = icl_object_id($take_course_page_id, 'page', true);

    $take_course_page=get_permalink($take_course_page_id);
    $user_id = get_current_user_id();

    do_action('wplms_the_course_button',$course_id,$user_id);

    $coursetaken=get_user_meta($user_id,$course_id,true);
    if(isset($free_course) && $free_course && $free_course !='H' && is_user_logged_in() && (!isset($coursetaken) || !is_numeric($coursetaken))){

        $duration=get_post_meta($course_id,'vibe_duration',true);
        $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);

        $new_duration = time()+$course_duration_parameter*$duration; //parameter 86400

        $new_duration = apply_filters('wplms_free_course_check',$new_duration);
        update_user_meta($user_id,$course_id,$new_duration);
        bp_course_update_user_course_status($user_id,$course_id,0);
        $group_id=get_post_meta($course_id,'vibe_group',true);
        if(isset($group_id) && $group_id !=''){
            groups_join_group($group_id, $user_id );
        }

        $coursetaken = $new_duration;
    }

    if(isset($coursetaken) && $coursetaken && is_user_logged_in()){   // COURSE IS TAKEN & USER IS LOGGED IN


        if($coursetaken){  // COURSE ACTIVE

            $course_user= bp_course_get_user_course_status($user_id,$course_id); // Validates that a user has taken this course

            $new_course_user = get_user_meta($user_id,'course_status'.$course_id,true); // Remove this line in 1.8.5

            if((isset($course_user) && is_numeric($course_user)) || (isset($free_course) && $free_course && $free_course !='H' && is_user_logged_in())){ // COURSE PURCHASED SECONDARY VALIDATION
                echo '<form action="'.apply_filters('wplms_take_course_page',$take_course_page,$course_id).'" method="post">';

                if(isset($new_course_user) && is_numeric($new_course_user) && $new_course_user){ // For Older versions
                    switch($course_user){
                        case 1:
                            echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('START COURSE','vibe').'">';
                            wp_nonce_field('start_course'.$user_id,'start_course');
                            break;
                        case 2:
                            echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('HỌC TIẾP','vibe').'">';
                            wp_nonce_field('continue_course'.$user_id,'continue_course');
                            break;
                        case 3:
                            echo '<a href="#" class="full button">'.__('COURSE UNDER EVALUATION','vibe').'</a>';
                            break;
                        case 4:
                            $finished_course_access = vibe_get_option('finished_course_access');
                            if(isset($finished_course_access) && $finished_course_access){
                                echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('HOÀN THÀNH','vibe').'">';
                                wp_nonce_field('continue_course'.$user_id,'continue_course');
                            }else{
                                echo '<a href="#" class="full button">'.__('HOÀN THÀNH','vibe').'</a>';
                            }
                            break;
                        default:
                            $course_button_html = '<a class="course_button button">'.__('COURSE ENABLED','vibe').'<span>'.__('CONTACT ADMIN TO ENABLE','vibe').'</span></a>';
                            echo apply_filters('wplms_default_course_button',$course_button_html,$user_id,$course_id,$course_user);
                            break;
                    }
                }else{
                    switch($course_user){
                        case 0:
                            echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('START COURSE','vibe').'">';
                            wp_nonce_field('start_course'.$user_id,'start_course');
                            break;
                        case 1:
                            echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('HỌC TIẾP','vibe').'">';
                            wp_nonce_field('continue_course'.$user_id,'continue_course');
                            break;
                        case 2:
                            echo '<a href="#" class="full button">'.__('COURSE UNDER EVALUATION','vibe').'</a>';
                            break;
                        default:
                            $finished_course_access = vibe_get_option('finished_course_access');
                            if(isset($finished_course_access) && $finished_course_access){
                                echo '<input type="submit" class="'.((isset($id) && $id )?'':'course_button full ').'button" value="'.__('HOÀN THÀNH','vibe').'">';
                                wp_nonce_field('continue_course'.$user_id,'continue_course');
                            }else{
                                echo '<a href="#" class="full button">'.__('COURSE FINISHED','vibe').'</a>';
                            }
                            break;
                    }
                }


                echo  '<input type="hidden" name="course_id" value="'.$course_id.'" />';

                echo  '</form>';
            }else{
                $pid=get_post_meta($course_id,'vibe_product',true); // SOME ISSUE IN PROCESS BUT STILL DISPLAYING THIS FOR NO REASON.
                echo '<a href="'.get_permalink($pid).'" class="'.((isset($id) && $id )?'':'course_button full ').'button">'.__('COURSE ENABLED','vibe').'<span>'.__('CONTACT ADMIN TO ENABLE','vibe').'</span></a>';
            }
        }else{
            $pid=get_post_meta($course_id,'vibe_product',true);
            $pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); // $id checks for Single Course page or Course page in the my courses section
            if(is_numeric($pid))
                $pid=get_permalink($pid);

            echo '<a href="'.$pid.'" class="'.((isset($id) && $id )?'':'course_button full ').'button">'.__('Course Expired','vibe').'&nbsp;<span>'.__('Click to renew','vibe').'</span></a>';
        }

    }else{
        $pid=get_post_meta($course_id,'vibe_product',true);
        $pid=apply_filters('wplms_course_product_id',$pid,$course_id,0);
        if(is_numeric($pid)){
            $pid=get_permalink($pid);
            $check=vibe_get_option('direct_checkout');
            $check =intval($check);
            if(isset($check) &&  $check){
                $pid .= '?redirect';
            }
        }

        $extra ='';
        if(isset($pid) && $pid){
            if(is_user_logged_in()){
		if(get_post_meta($course_id,'vibe_coming_soon',true)=='S'){
                    echo '<a href="#" class="full button hienthikhoahoccomingsoon">'.__('COMING SOON','vibe').'</a></br>';
                    echo '<div class="khoahoccomingsoon anpopupthongtinkhoahoc">';
                    echo '<span>Khóa học đang trong thời gian hoàn thiện, mong các bạn thông cảm</span>';
                    echo '</br>';
                    echo '<span  class="button dongkhoahoccomingsoon">'.__('ĐÓNG','vibe').'</span>';
                    echo '</div>';
                }else{
                    echo '<a href="#" class="full button hienxacnhandangkykhoahoc">'.__('TAKE THIS COURSE','vibe').'</a></br>';
                }

                //echo '<a href="#" class="full button hienxacnhandangkykhoahoc">'.__('TAKE THIS COURSE','vibe').'</a></br>';
                echo '<div class="xacnhandangkykhoahoc anpopupthongtinkhoahoc">';
                echo '<span>Nhấn vào nút "đồng ý" để ghi danh khóa học : '.get_the_title($course_id).'</span>';
                echo '</br>';
                echo '<span>Giá tiền khóa học là: '.bp_course_get_course_credits('course_id='.$course_id).'</span></br>';
                echo '<div class="sapxepnutdangkykhoahoc">';
                echo '<a href="'.get_home_url().'/thanh-toan-khoa-hoc/?course_id='.$course_id.'" class="'.((isset($id) && $id )?'':'course_button full ').'button">'.__('Đồng ý','vibe').apply_filters('wplms_course_button_extra',$extra,$course_id).'</a>';
                echo '<span  class="full button huydangkykhoahoc">'.__('Hủy bỏ','vibe').'</span>';
                echo '</div>';
                echo '<form action="'.apply_filters('wplms_take_course_page',$take_course_page,$course_id).'" method="post" style="display: none" id="vaotranghockhoahoc">';
                wp_nonce_field('start_course'.$user_id,'start_course');
                echo  '<input type="hidden" name="course_id" value="'.$course_id.'" />';
                echo  '</form>';
                echo '</div>';
		
            }else{
                //echo '<a href="#" class="unlogin '.((isset($id) && $id )?'':'course_button full ').'button">'.__('TAKE THIS COURSE','vibe').'</a>';
		if(get_post_meta($course_id,'vibe_coming_soon',true)=='S'){
                    echo '<a href="#" class="full button hienthikhoahoccomingsoon">'.__('COMING SOON','vibe').'</a></br>';
                    echo '<div class="khoahoccomingsoon anpopupthongtinkhoahoc">';
                    echo '<span>Khóa học đang trong thời gian hoàn thiện, mong các bạn thông cảm</span>';
                    echo '</br>';
                    echo '<span  class="button dongkhoahoccomingsoon">'.__('ĐÓNG','vibe').'</span>';
                    echo '</div>';
                }else {
                    echo '<a href="#" class="unlogin ' . ((isset($id) && $id) ? '' : 'course_button full ') . 'button">' . __('TAKE THIS COURSE', 'vibe') . '</a>';
                }
            }
        }else{
            echo '<a href="'.apply_filters('wplms_private_course_button','#').'" class="'.((isset($id) && $id )?'':'course_button full ').'button">'. apply_filters('wplms_private_course_button_label',__('PRIVATE COURSE','vibe')).'</a>';
        }
    }
}
?>


<?php


// Update lại nội dung Unit
add_action('wp_ajax_update_unit_content','update_unit_content');
function update_unit_content(){
    $unit_id = $_POST['unit_id'];
    $unit_title = $_POST['unit_title'];
    $unit_content = $_POST['unit_content'];

    $value = array('ID' => $unit_id, 'post_title' => $unit_title ,'post_content' => $unit_content );
    wp_update_post($value);
}



?>

<?php
//    Load category trang chủ để sử dụng ajax
add_action('getdanhmuckhoahoc','laydanhmuckhoahoc');
function laydanhmuckhoahoc(){
    $args = array(
        'orderby'           => 'name',
        'order'             => 'ASC',
        'hide_empty'        => false,
        'exclude'           => array(),
        'exclude_tree'      => array(),
        'include'           => array(),
        'number'            => '',
        'fields'            => 'all',
        'slug'              => '',
        'parent'            => '',
        'hierarchical'      => true,
        'child_of'          => 0,
        'get'               => '',
        'name__like'        => '',
        'description__like' => '',
        'pad_counts'        => true,
        'offset'            => '',
        'search'            => '',
        'cache_domain'      => 'core'
    );
    $terms = get_terms( 'course-cat',$args );
    echo '<ul id="menu-v" class="menu_danhmuckhoahoc">';
    echo '<h5><div style="float: left;padding-left: 15px;padding-top: 3px;"><i class="icon-list-1"></i></div>Danh mục khóa học</h5>';
    echo '<li class="tatcakhoahoc active_menu" data-id="0"><a><label>Tất cả</label></a></li>';
    foreach ( $terms as $term ) {
        if($term->parent ==0){
            $arg = array(
                'post_type'=>'course',
                'posts_per_page'=>6,
                'tax_query' => array(
                    array(
                        'taxonomy'=>'course-cat',
                        'terms'=>$term->term_id,
                        'field'=>'term_id'
                    ),
                    'orderby'=>'title',
                    'order'=>'ASC'
                )
            );

            $post_ids = get_posts($arg);
            if($post_ids){
                //                echo '<li class="danhmuckhoahoc"><h3><span data-id="'.$term->term_id.'">' . $term->name.'</span></h3>';
                echo '<li  class="danhmuckhoahoc cha"><a><label class="danhmuccha" data-id="'.$term->term_id.'">' . $term->name.'</label></a>';
//            $child_term = get_term_children($term->term_id,'course-cat');
//            echo '<ul class="sub">';
//            foreach($child_term as $item){
//                $child = get_term_by( 'id', $item, 'course-cat' );
//                echo '<li  class="danhmuckhoahoc"><a><span data-id="'.$child->term_id.'">'.$child->name .'</span></a></li>';
//            }
//            echo '</li>';
//            echo '</ul>';
                echo '</li>';
            }

        }
    }

    $taxomony = "level";
    $termslevel = get_terms( $taxomony,$args );


    echo '<h5><div style="float: left;padding-left: 15px;padding-top: 3px;"><i class="icon-list-1"></i></div>Cấp độ</h5>';
    echo '<li class="menu_level" data-id="0"><a><input type="radio" name="level" id="level" value="0" checked /><label for="level">Tất cả</label></a></li>';
    foreach ( $termslevel as $term ) {

        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'post_status' =>'publish',
            'tax_query' => array(
                array(
                    'taxonomy'=>'level',
                    'terms'=>$term->term_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'title',
                'order'=>'ASC'
            )
        );

        $post_ids = get_posts($arg);
        if($post_ids)
            echo '<li class="'.$term->slug.' menu_level anmenu"><a><input type="radio" id="'.$term->term_id.'" name="level" value="'.$term->term_id.'" /><label for="'.$term->term_id.'" data-id="'.$term->term_id.'">'.$term->name .'</label></a></li>';



    }

    $taxomony = "language-category";
    $termslanguage = get_terms( $taxomony,$args );

    echo '<h5><div style="float: left;padding-left: 15px;padding-top: 3px;"><i class="icon-list-1"></i></div>Ngôn Ngữ</h5>';
    echo '<li class="menu_ngonngu" data-id="0"><a><input type="radio" name="language" id="language" value="0" checked /><label for="language">Tất cả</label></a></li>';
    foreach ( $termslanguage as $term ) {

        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'post_status' =>'publish',
            'tax_query' => array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$term->term_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'title',
                'order'=>'ASC'
            )
        );

        $post_ids = get_posts($arg);
        if($post_ids){
            echo '<li class="'.$term->slug.' menu_ngonngu anmenu"><a><input type="radio" id="'.$term->term_id.'" name="language" value="'.$term->term_id.'" /><label for="'.$term->term_id.'" data-id="'.$term->term_id.'">'.$term->name .'</label></a></li>';
        }

    }

    echo '</ul>';
}
?>

<?php
function tao_menu_category_ngonngu(){
    add_submenu_page(
        'lms',
        __('Language Category','vibe'),
        __('Language Category','vibe'),
        'edit_posts',
        'edit-tags.php?taxonomy=language-category'
    );
}


add_action( 'admin_menu', 'tao_menu_category_ngonngu', 999 );

function tao_category_ngonngu(){
    $labels = array(
        'name' => 'Language Category',
        'singular' => 'Language Category',
        'menu_name' => 'Language Category',
        'add_new_item' => __('Add New Language Category','vibe-customtypes'),
        'all_items' => __('All Language Categories','vibe-customtypes')
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_menu' => 'lms',
        'query_var' => 'language-category',
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite' => array( 'slug' => WPLMS_COURSE_CATEGORY_SLUG, 'hierarchical' => true, 'with_front' => false ),
    );
    register_taxonomy('language-category', array( 'course'), $args);
}
add_action( 'init', 'tao_category_ngonngu', 0 );
?>

<?php
//hiển thị tất cả khóa học khi click vào menu tất cả
add_action('wp_ajax_hienthikhoahocmacdinh','hienthikhoahocmacdinh');
add_action('wp_ajax_nopriv_hienthikhoahocmacdinh','hienthikhoahocmacdinh');

function hienthikhoahocmacdinh(){
    $page = get_query_var('page');
    $level_id = $_POST['level_id'];
    $language_id = $_POST['language_id'];
    $level_id_text = $_POST['level_id_text'];
    $language_id_text = $_POST['language_id_text'];

    $arg = "";
    if($language_id==0 && $level_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=> 6,
            'post_status' =>'publish',
            'paged' => $page,
//        'showposts'=> -1,
            'orderby'=>'id',
            'order'=>"DESC"
        );
    }else if($level_id!=0 && $language_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else if($level_id==0 && $language_id!=0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else if($level_id!=0 && $language_id!=0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }


    // echo '<ul class="breadcrumb-trangchu"><li><a href="'.get_home_url().'">Tất cả khóa học</a>  </li></ul>';
    // echo '<ul class="breadcrumb-level">';
    // if($level_id_text=="Tất cả" && $language_id_text=="Tất cả"){
    // echo '<li>Cấp độ: <span>Tất cả</span></li>';
    // echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
    // }else if($level_id_text!="Tất cả" && $language_id_text=="Tất cả"){
    // echo '<li>Cấp độ: <span>'.$level_id_text.'</span><i class="icon-x"></i></li>';
    // echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
    // }else if($level_id_text=="Tất cả" && $language_id_text!="Tất cả"){
    // echo '<li>Cấp độ: <span>Tất cả</span></li>';
    // echo '<li>Ngôn ngữ lập trình: <span>'.$language_id_text.'</span><i class="icon-x"></i></li>';
    // }else{
    // echo '<li>Cấp độ: <span>'.$level_id_text.'</span><i class="icon-x"></i></li>';
    // echo '<li>Ngôn ngữ lập trình: <span>'.$language_id_text.'</span><i class="icon-x"></i></li>';
    // }

    echo '<ul class="breadcrumb-trangchu"><li><span class="breadcrumb-trangchu-dskhoahoc">Danh sách khóa học</span></li></ul>';
//    echo '<ul class="breadcrumb-trangchu"><li><a href="'.get_home_url().'">Tất cả khóa học</a>  </li></ul>';
    echo '<ul class="breadcrumb-level">';
//    if(empty($level_id_text) && empty($language_id_text)){
    echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
    echo '<li>Danh mục: <span>Tất cả</span></li>';
    if($level_id==0){
        echo '<li>Cấp độ: <span>Tất cả</span></li>';
    }else{
        echo '<li>Cấp độ: <span>'.$level_id_text.'</span><i class="icon-x" data-id="2"></i></li>';
    }
    if($language_id==0){
        echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
    }else{
        echo '<li>Ngôn ngữ lập trình: <span>'.$language_id_text.'</span><i class="icon-x" data-id="3"></i></li>';
    }

    echo '</ul>';

    $wp_query = new WP_Query($arg);


    echo '<ul class="grid">';
    while($wp_query->have_posts()) : $wp_query->the_post();
        $course_id = get_the_ID();

        $course_curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
        $dem = 0;
        if(isset($course_curriculum)){
            foreach($course_curriculum as $item){
                if(isset($item)){
                    $dem++;
                }
            }
        }
        echo '<li class="clear3 col-md-4">';
        echo '<div class="block courseitem">';
        echo '<div class="block_media">';
        echo bp_course_avatar();
        echo '<div class="avatar-list"><div class="hinhgiangvien">'.bp_course_get_instructor_avatar().'</div><div class="tongbaihoc">'.$dem.' bài học</div>'
            .'<div class="thoiluongvideo">Video '.tongthoigianvideokhoahoc($course_id).'</div></div>';
        echo nuthanhtoan();
        echo '</div>';
        echo '<div class="block_content">';
        echo '<h4 class="block_title">';
        echo bp_course_title();
        echo '</h4>';
        echo '<div class="item-meta">';
        echo bp_course_meta();
        echo '</div>';
//        echo '<div class="item-credits">';
//        echo bp_course_credits();
//        echo '</div>';
        echo '<div class="item-instructor">';
        echo bp_course_instructor();
        echo '</div>';
        echo '<div class="item-action">';
        echo bp_course_action();
        echo '</div>';
        echo '<div class="item_process">';
        echo '</div>';
        echo do_action( 'bp_directory_course_item' );
        echo '<div class="clear"></div>';
        echo '<div class="item-credits" style="width:100%; padding-top:12px; border-top:1px solid #EEE">';
        echo bp_course_credits();
        echo '</div>';
        echo '</div>';


        echo '<div class="clear"></div>';
        echo '</div>';
        echo '</li>';

    endwhile;
    echo '</ul><p></p>';
    wp_reset_postdata();
    echo '<div class="clear"></div>';
// phân trang trang chủ
    $num_page = $wp_query->max_num_pages;

    echo '<div><ul class="sophantrang">';
    if(empty($page)){
        for($i=1; $i<6; $i++){
            if($i <= $num_page){
                if($i==1){
                    echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange"><span>'.$i.'</span></li></a>';
                }else {
                    echo '<a class="sotrang" data-id="' . $i . '"><li><span>' . $i . '</span></li></a>';
                }
            }else{
                return die() ;
            }

        }
        echo '<li><span>...</span></li>';

    }else{
        if($page !=1){
            echo '<a class="sotrang" data-id="1"><li><span> << Trang đầu</span></li></a>';
            echo '<li><span>...</span></li>';
        }
        for($i=$page; $i<$page+5; $i++){
            if($i <= $num_page){
                echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';

            }else{
                return die();
            }

        }
        if($page+5 <= $num_page){
            echo '<li><span>...</span></li>';
        }
    }
    echo '<a class="sotrang" data-id="'.$num_page.'"><li><span>Trang cuối >></span></li></a>';
    echo '</ul></div>';
    die();
}
?>

<?php
add_action('hienthikhoahocmacdinhbandau','hienthikhoahocmacdinhbandau');
function hienthikhoahocmacdinhbandau(){

    $arg=array();

//    if(isset($_GET['khoahoc_tag'])){
//        $arg = array(
//            'post_type'=>'course',
//            'posts_per_page'=>2,
//            'tax_query' => array(
//                array(
//                    'taxonomy'=>'course-cat',
//                    'terms'=>$_GET['khoahoc_tag'],
//                    'field'=>'term_id'
//                ),
//                'orderby'=>'title',
//                'order'=>'ASC'
//            ),
//        );
//    }else {
    $arg = array(
        'post_type' => 'course',
        'posts_per_page' => 6,
        'post_status' => 'publish',
//        'showposts'=> -1,
        'orderby' => 'id',
        'order' => "DESC",
    );
//    }


    echo '<ul class="breadcrumb-trangchu"><li><span class="breadcrumb-trangchu-dskhoahoc">Danh sách khóa học</span></li></ul>';
//    echo '<ul class="breadcrumb-trangchu"><li><a href="'.get_home_url().'">Tất cả khóa học</a>  </li></ul>';
    echo '<ul class="breadcrumb-level">';
//    if(empty($level_id_text) && empty($language_id_text)){
    echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
    echo '<li>Danh mục: <span>Tất cả</span></li>';
    echo '<li>Cấp độ: <span>Tất cả</span></li>';
    echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
//    }else{
//        echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo: </li>';
//        echo '<li>Danh mục: <span>Tất cả</span></li>';
//        echo '<li>Cấp độ: <span>'.$level_id_text.'</span></li>';
//        echo '<li>Ngôn ngữ lập trình: <span>'.$language_id_text.'</span></li>';
//    }

    echo '</ul>';

    $wp_query = new WP_Query($arg);


    echo '<ul class="grid">';
    while($wp_query->have_posts()) : $wp_query->the_post();
        $course_id = get_the_ID();

        $course_curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
        $dem = 0;
        if(isset($course_curriculum)){
            foreach($course_curriculum as $item){
                if(isset($item)){
                    $dem++;
                }
            }
        }
        echo '<li class="clear3 col-md-4">';
        echo '<div class="block courseitem">';
        echo '<div class="block_media">';
        echo bp_course_avatar();
        echo '<div class="avatar-list"><div class="hinhgiangvien">'.bp_course_get_instructor_avatar().'</div><div class="tongbaihoc">'.$dem.' bài học</div>'
            .'<div class="thoiluongvideo">Video '.tongthoigianvideokhoahoc($course_id).'</div></div>';
        echo nuthanhtoan();
        echo '</div>';
        echo '<div class="block_content">';
        echo '<h4 class="block_title">';
        echo bp_course_title();
        echo '</h4>';
        echo '<div class="item-meta">';
        echo bp_course_meta();
        echo '</div>';
//        echo '<div class="item-credits">';
//        echo bp_course_credits();
//        echo '</div>';
        echo '<div class="item-instructor">';
        echo bp_course_instructor();
        echo '</div>';
        echo '<div class="item-action">';
        echo bp_course_action();
        echo '</div>';
        echo '<div class="item_process">';
        echo '</div>';
        echo do_action( 'bp_directory_course_item' );
        echo '<div class="clear"></div>';
        echo '<div class="item-credits" style="width:100%; padding-top:12px; border-top:1px solid #EEE">';
        echo bp_course_credits();
        echo '</div>';
        echo '</div>';


        echo '<div class="clear"></div>';
        echo '</div>';
        echo '</li>';

    endwhile;
    echo '</ul><p></p>';
    wp_reset_postdata();
    echo '<div class="clear"></div>';
// phân trang trang chủ
    $num_page = $wp_query->max_num_pages;

    echo '<div><ul class="sophantrang">';
    if(empty($page)){
        for($i=1; $i<6; $i++){
            if($i <= $num_page){
                if($i==1){
                    echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange"><span>'.$i.'</span></li></a>';
                }else {
                    echo '<a class="sotrang" data-id="' . $i . '"><li><span>' . $i . '</span></li></a>';
                }
            }else{
                return ;
            }

        }
        echo '<li><span>...</span></li>';

    }else{
        if($page !=1){
            echo '<a class="sotrang" data-id="1"><li><span> << Trang đầu</span></li></a>';
            echo '<li><span>...</span></li>';
        }
        for($i=$page; $i<$page+5; $i++){
            if($i <= $num_page){
                echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';

            }else{
                return die();
            }

        }
        if($page+5 <= $num_page){
            echo '<li><span>...</span></li>';
        }
    }
    echo '<a class="sotrang" data-id="'.$num_page.'"><li><span>Trang cuối >></span></li></a>';
    echo '</ul></div>';

}
?>

<?php
//    ajax cho phân trang
add_action('wp_ajax_loadajaxphantrang','loadajaxphantrang');
add_action('wp_ajax_nopriv_loadajaxphantrang','loadajaxphantrang');
function loadajaxphantrang(){
    $page = $_POST['page_ajax'];
    $arg = array(
        'post_type'=>'course',
        'posts_per_page'=> 6,
        'post_status' =>'publish',
        'paged' => $page,
//        'showposts'=> -1,
        'orderby'=>'id',
        'order'=>"DESC"
    );

    $wp_query = new WP_Query($arg);


    echo '<ul class="grid">';
    while($wp_query->have_posts()) : $wp_query->the_post();
        $course_id = get_the_ID();

        $course_curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
        $dem = 0;
        if(isset($course_curriculum)){
            foreach($course_curriculum as $item){
                if(isset($item)){
                    $dem++;
                }
            }
        }
        echo '<li class="clear3 col-md-4">';
        echo '<div class="block courseitem">';
        echo '<div class="block_media">';
        echo bp_course_avatar();
        echo '<div class="avatar-list"><div class="hinhgiangvien">'.bp_course_get_instructor_avatar().'</div><div class="tongbaihoc">'.$dem.' bài học</div>'
            .'<div class="thoiluongvideo">Video '.tongthoigianvideokhoahoc($course_id).'</div></div>';
        echo nuthanhtoan();
        echo '</div>';
        echo '<div class="block_content">';
        echo '<h4 class="block_title">';
        echo bp_course_title();
        echo '</h4>';
        echo '<div class="item-meta">';
        echo bp_course_meta();
        echo '</div>';
//        echo '<div class="item-credits">';
//        echo bp_course_credits();
//        echo '</div>';
        echo '<div class="item-instructor">';
        echo bp_course_instructor();
        echo '</div>';
        echo '<div class="item-action">';
        echo bp_course_action();
        echo '</div>';
        echo '<div class="item_process">';
        echo '</div>';
        echo do_action( 'bp_directory_course_item' );
        echo '<div class="clear"></div>';
        echo '<div class="item-credits" style="width:100%; padding-top:12px; border-top:1px solid #EEE">';
        echo bp_course_credits();
        echo '</div>';
        echo '</div>';


        echo '<div class="clear"></div>';
        echo '</div>';
        echo '</li>';

    endwhile;
    echo '</ul><p></p>';
    wp_reset_postdata();
    echo '<div class="clear"></div>';
// phân trang trang chủ
    $num_page = $wp_query->max_num_pages;

    echo '<div><ul class="sophantrang">';
    if(empty($page)){
        for($i=1; $i<6; $i++){
            if($i <= $num_page){
                echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
            }else{
                return die();
            }

        }
        echo '<li><span>...</span></li>';
    }else{
        if($page % 6 == 0){
            echo '<a class="sotrang" data-id="1"><li><span> << Trang đầu</span></li></a>';
            echo '<li><span>...</span></li>';
            for($i=$page; $i<$page+5; $i++){
                if($i <= $num_page){
                    if($i == $page){
                        echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                    }else{
                        echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                    }


                }else{
                    return  die();
                }

            }
        }else{
            for($i=1; $i<$page+5; $i++){
                if($i <= $num_page){
                    if($i == $page){
                        echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                    }else{
                        echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                    }

                }else{
                    return  die();
                }

            }
        }

        if($page+5 <= $num_page){
            echo '<li><span>...</span></li>';
        }
    }
    echo '<a class="sotrang" data-id="'.$num_page.'"><li><span>Trang cuối >></span></li></a>';
    echo '</ul></div>';
    die();
}
?>

<?php
//    ajax cho phân trang
add_action('wp_ajax_loadajaxphantrang_lan_2','loadajaxphantrang_lan_2');
add_action('wp_ajax_nopriv_loadajaxphantrang_lan_2','loadajaxphantrang_lan_2');
function loadajaxphantrang_lan_2(){
    $page = $_POST['page_ajax'];
    $danhmuc_id = $_POST['danhmuc'];
    $danhmuc_text = $_POST['danhmuc_text'];
    $level_id = $_POST['level_id'];
    $level_text = $_POST['level_id_text'];
    $language_id = $_POST['language_id'];
    $language_text = $_POST['language_id_text'];

    $arg = "";
    if($language_id==0 && $level_id==0 && $danhmuc_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=> 6,
            'post_status' =>'publish',
            'paged' => $page,
//        'showposts'=> -1,
            'orderby'=>'id',
            'order'=>"DESC"
        );
    }else if($level_id!=0 && $language_id==0 && $danhmuc_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else if($level_id!=0 && $language_id!=0 && $danhmuc_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else if($level_id!=0 && $language_id==0 && $danhmuc_id!=0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$danhmuc_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else if($level_id==0 && $language_id!=0 && $danhmuc_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else if($level_id==0 && $language_id!=0 && $danhmuc_id!=0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$danhmuc_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else if($level_id==0 && $language_id==0 && $danhmuc_id!=0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$danhmuc_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }else{
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>1,
            'tax_query' => array(
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$danhmuc_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            ),
            'post_status' =>'publish',
            'paged' => $page,
        );
    }



//    $arg = array(
//        'post_type'=>'course',
//        'posts_per_page'=> 6,
//        'post_status' =>'publish',
//        'paged' => $page,
////        'showposts'=> -1,
//        'orderby'=>'title',
//        'order'=>"DESC"
//    );

    echo '<ul class="breadcrumb-trangchu"><li><span class="breadcrumb-trangchu-dskhoahoc">Danh sách khóa học</span></li></ul>';
//    echo '<ul class="breadcrumb-trangchu"><li><a href="'.get_home_url().'">Tất cả khóa học</a>  </li></ul>';
    echo '<ul class="breadcrumb-level">';
//    if(empty($level_id_text) && empty($language_id_text)){
    echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
    if($danhmuc_id==0){
        echo '<li>Danh mục: <span>Tất cả</span></li>';
    }else{
        echo '<li>Danh mục: <span>'.$danhmuc_text.'</span><i class="icon-x" data-id="1"></i></li>';
    }
    if($level_id==0){
        echo '<li>Cấp độ: <span>Tất cả</span></li>';
    }else{
        echo '<li>Cấp độ: <span>'.$level_text.'</span><i class="icon-x" data-id="2"></i></li>';
    }
    if($language_id==0){
        echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
    }else{
        echo '<li>Ngôn ngữ lập trình: <span>'.$language_text.'</span><i class="icon-x" data-id="3"></i></li>';
    }
//    }else{
//        echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo: </li>';
//        echo '<li>Danh mục: <span>Tất cả</span></li>';
//        echo '<li>Cấp độ: <span>'.$level_id_text.'</span></li>';
//        echo '<li>Ngôn ngữ lập trình: <span>'.$language_id_text.'</span></li>';
//    }

    echo '</ul>';

    $wp_query = new WP_Query($arg);


    echo '<ul class="grid">';
    while($wp_query->have_posts()) : $wp_query->the_post();
        $course_id = get_the_ID();

        $course_curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
        $dem = 0;
        if(isset($course_curriculum)){
            foreach($course_curriculum as $item){
                if(isset($item)){
                    $dem++;
                }
            }
        }
        echo '<li class="clear3 col-md-4">';
        echo '<div class="block courseitem">';
        echo '<div class="block_media">';
        echo bp_course_avatar();
        echo '<div class="avatar-list"><div class="hinhgiangvien">'.bp_course_get_instructor_avatar().'</div><div class="tongbaihoc">'.$dem.' bài học</div>'
            .'<div class="thoiluongvideo">Video '.tongthoigianvideokhoahoc($course_id).'</div></div>';
        echo nuthanhtoan();
        echo '</div>';
        echo '<div class="block_content">';
        echo '<h4 class="block_title">';
        echo bp_course_title();
        echo '</h4>';
        echo '<div class="item-meta">';
        echo bp_course_meta();
        echo '</div>';
//        echo '<div class="item-credits">';
//        echo bp_course_credits();
//        echo '</div>';
        echo '<div class="item-instructor">';
        echo bp_course_instructor();
        echo '</div>';
        echo '<div class="item-action">';
        echo bp_course_action();
        echo '</div>';
        echo '<div class="item_process">';
        echo '</div>';
        echo do_action( 'bp_directory_course_item' );
        echo '<div class="clear"></div>';
        echo '<div class="item-credits" style="width:100%; padding-top:12px; border-top:1px solid #EEE">';
        echo bp_course_credits();
        echo '</div>';
        echo '</div>';


        echo '<div class="clear"></div>';
        echo '</div>';
        echo '</li>';

    endwhile;
    echo '</ul><p></p>';
    wp_reset_postdata();
    echo '<div class="clear"></div>';
// phân trang trang chủ
    $num_page = $wp_query->max_num_pages;

    echo '<div><ul class="sophantrang">';
    if(empty($page)){
        for($i=1; $i<6; $i++){
            if($i <= $num_page){
                if($i==1){
                    echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange"><span>'.$i.'</span></li></a>';
                }else{
                    echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                }

            }else{
                return die();
            }

        }
        echo '<li><span>...</span></li>';
    }else{
        if($page % 6 == 0){
            echo '<a class="sotrang" data-id="1"><li><span> << Trang đầu</span></li></a>';
            echo '<li><span>...</span></li>';
            for($i=$page; $i<$page+5; $i++){
                if($i <= $num_page){
                    if($i == $page){
                        echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                    }else{
                        echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                    }


                }else{
                    return  die();
                }

            }
        }else{
            for($i=1; $i<$page+5; $i++){
                if($i <= $num_page){
                    if($i == $page){
                        echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                    }else{
                        echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                    }

                }else{
                    return  die();
                }

            }
        }

        if($page+5 <= $num_page){
            echo '<li><span>...</span></li>';
        }
    }
    echo '<a class="sotrang" data-id="'.$num_page.'"><li><span>Trang cuối >></span></li></a>';
    echo '</ul></div>';
    die();
}
?>


<?php
//Hiển thị khóa học của giảng viên
add_action('hienthikhoahoccuagiangvien','hienthikhoahoccuagiangvien',10,1);
function hienthikhoahoccuagiangvien($user_id){

    $arg = array(
        'post_type'=>'course',
        'posts_per_page'=> 6,
        'post_status' =>'publish',
        'author'=>$user_id
    );

    $wp_query = new WP_Query($arg);
    echo '<ul class="grid">';


    while ($wp_query->have_posts()) : $wp_query->the_post();
        $course_id = get_the_ID();

        echo '<li class="clear3 col-md-6">';
        echo '<div class="block courseitem">';
        echo '<div class="block_media">';
        echo bp_course_avatar();


        echo '<div class="block_content">';
        echo '<h4 class="block_title">';
        echo bp_course_title();
        echo '</h4>';
        echo '<div class="item-meta">';
        echo bp_course_meta();
        echo '</div>';
//        echo '<div class="item-credits">';
//        echo bp_course_credits();
//        echo '</div>';
        echo '<div class="item-instructor">';
        echo bp_course_instructor();
        echo '</div>';
        echo '<div class="item-action">';
        echo bp_course_action();
        echo '</div>';
        echo '<div class="item_process">';
        echo '</div>';
        echo do_action('bp_directory_course_item');
        echo '<div class="clear"></div>';
        echo '<div class="item-credits" style="width:100%; padding-top:12px; border-top:1px solid #EEE">';
        echo bp_course_credits();
        echo '</div>';
        echo '</div>';


        echo '<div class="clear"></div>';
        echo '</div>';
        echo '</li>';

    endwhile;
    echo '</ul><p></p>';
    wp_reset_postdata();
    echo '<div class="clear"></div>';
}
?>

<?php
//Hiển thị khóa học có liên quan với khóa học mà user đang xem
add_action('hienthikhoahoclienquan','hienthikhoahoclienquan',10,1);
function hienthikhoahoclienquan($postid){
    $wp_term_id = wp_get_object_terms($postid,'course-cat');
    $term_id = 0;
    foreach($wp_term_id as $item){
        $term_id = $item->term_id;
    }
    $arg = array(
        'post_type'=>'course',
        'posts_per_page'=>6,
        'tax_query' => array(
            array(
                'taxonomy'=>'course-cat',
                'terms'=>$term_id,
                'field'=>'term_id'
            ),
            'orderby'=>'id',
            'order'=>'DESC'
        )
    );



    $wp_query = new WP_Query($arg);
    while($wp_query->have_posts()): $wp_query->the_post();
        $post_id = get_the_ID();
        echo '<li data-id="'.$term_id.'"><div class="hinhkhoahoc row">';
        echo '<div class="col-md-6">';
        echo bp_course_avatar();
        echo '</div>';
        echo '<div class="col-md-6">';
        echo '<span>';
        echo the_title();
        echo '</span>';
        echo '</br><span>';
        echo bp_course_credits();
        echo '</span>';
        echo '<span>';
        echo child_bp_course_meta();
        echo '</span>';
        echo '</div>';
        echo '</div></li>';
    endwhile;
}
?>

<?php
//thông tin giảng viên
add_action('getthongtingiangvien','getthongtingiangvien');
function getthongtingiangvien(){
    $author_id = get_post_field('post_author',get_the_ID());
    $user_info = get_userdata($author_id);
    $arg = array(
        'field' => 'Học vị',
//        'field'   => 'Bio',
        'user_id' => $author_id
    );
    $arglylich = array(
        'field'   => 'LÝ LỊCH CÁ NHÂN',
        'user_id' => $author_id
    );
    $argthanhtich = array(
        'field'   => 'MỘT SỐ THÀNH TÍCH NỔI BẬT',
        'user_id' => $author_id
    );
    $argcongtac = array(
        'field'   => 'TIỂU SỬ CÔNG TÁC',
        'user_id' => $author_id
    );
    $argbaocao = array(
        'field'   => 'BÁO CÁO KHOA HỌC',
        'user_id' => $author_id
    );

    echo '<div class="row">';
    echo '<div class="col-md-2 col-sm-2">';
    echo '<a href="'.bp_core_get_user_domain($author_id).'">';
    echo get_avatar( $author_id, 100 ) ;
    echo '</a>';
    echo '</div>';

    echo '<div class="col-md-10 col-sm-10">';
    echo '<a href="'.bp_core_get_user_domain($author_id).'">';
    echo $user_info->nickname;
    echo wpautop(bp_get_profile_field_data($arg));
    echo '</a>';
//    echo '<div class="bio row">';
    echo '<table class="profile-fields">';
    echo '<tbody>';
    echo '<tr class="field_7 field_ly-lich-ca-nhan optional-field visibility-public field_type_textarea">';
    echo '<td class="label">LÝ LỊCH CÁ NHÂN</td>';
    echo '<td class="data">';
    echo wpautop(bp_get_profile_field_data($arglylich));
    echo '</td>';
    echo '</tr>';
    echo '<tr class="field_6 field_mot-so-thanh-tich-noi-bat optional-field visibility-public alt field_type_textarea">';
    echo '<td class="label">MỘT SỐ THÀNH TÍCH NỔI BẬT</td>';
    echo '<td class="data">';
    echo wpautop(bp_get_profile_field_data($argthanhtich));
    echo '</td>';
    echo '</tr>';
    echo '<tr class="field_3 field_tieu-su-cong-tac optional-field visibility-public field_type_textarea">';
    echo '<td class="label">TIỂU SỬ CÔNG TÁC</td>';
    echo '<td class="data">';
    echo wpautop(bp_get_profile_field_data($argcongtac));
    echo '</td>';
    echo '</tr>';
    echo '<tr class="field_8 field_bao-cao-khoa-hoc optional-field visibility-public alt field_type_textarea">';
    echo '<td class="label">BÁO CÁO KHOA HỌC</td>';
    echo '<td class="data">';
    echo wpautop(bp_get_profile_field_data($argbaocao));
    echo '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
}
?>

<?php
function child_bp_course_meta() {
    echo child_bp_course_get_course_meta();
}

function child_bp_course_get_course_meta() {

    $reviews=get_post_meta(get_the_ID(),'average_rating',true);
    $count=get_post_meta(get_the_ID(),'rating_count',true);

    if(!isset($reviews) || $reviews == ''){
        $reviews_array=bp_course_get_course_reviews();
        $reviews = $reviews_array['rating'];
        $count = $reviews_array['count'];
    }


    $meta ='';
    if(isset($reviews)){
        $meta = '<div class="star-rating"  itemprop="review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
		<i class="hide" itemprop="rating">'.$reviews.'</i>';
        for($i=1;$i<=5;$i++){


            if($reviews >= 1){
                $meta .='<span class="fill"></span>';
            }elseif(($reviews < 1 ) && ($reviews >= 0.4 ) ){
                $meta .= '<span class="half"></span>';
            }else{
                $meta .='<span></span>';
            }
            $reviews--;
        }
        $meta .= ' <strong itemprop="count"></strong> </div>';
    }else{
        $meta = '<div class="star-rating">
					<span></span><span></span><span></span><span></span><span></span> ( 0 '.__('REVIEWS','vibe').' )
				</div>';
    }

    $students = get_post_meta(get_the_ID(),'vibe_students',true);

    if(!isset($students) && $students =''){$students=0;update_post_meta(get_the_ID(),'vibe_students',0);} // If students not set

    return apply_filters('wplms_course_meta',$meta);
}

?>

<?php


//    load danh sách khóa học khi click vào category
add_action('wp_ajax_hienthikhoahoctrangchuphantrang','hienthikhoahoctrangchuphantrang');
add_action('wp_ajax_nopriv_hienthikhoahoctrangchuphantrang','hienthikhoahoctrangchuphantrang');
function hienthikhoahoctrangchuphantrang(){
    $term_id = $_POST['term_id'];
    $name_term = $_POST['name_term'];
    $page = $_POST['page_ajax_term'];

    $arg = array(
        'post_type'=>'course',
        'posts_per_page'=> 6,
        'paged' => $page,
//        'post_status' =>'publish',
        'tax_query'=>array(
            array(
                'taxonomy'=>'course-cat',
                'terms'=>$term_id,
                'field'=>'term_id',
            ),
            'orderby'=>'id',
            'order'=> 'DESC'
        )
    );
//    echo '<ul class="breadcrumb-trangchu"><li><span class="breadcrumb-trangchu">Danh sách khóa học</span></li></ul>';
    echo '<ul class="breadcrumb-trangchu"><li><a href="'.get_home_url().'">Tất cả khóa học</a> > <a class="danhmuckhoahoc"><span data-id="'.$term_id.'">'.$name_term.'</span></a></li></ul>';
    $wp_query = new WP_Query($arg);
    $countcourse =  count($wp_query);
    if($countcourse == 0){
        echo "Không tìm thấy khóa học";
    }else{
        echo '<ul class="grid">';


        while($wp_query->have_posts()) : $wp_query->the_post();
            $course_id = get_the_ID();

            $course_curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
            $dem = 0;
            if(isset($course_curriculum)){
                foreach($course_curriculum as $item){
                    if(isset($item)){
                        $dem++;
                    }
                }
            }

            echo '<li class="clear3 col-md-4">';
            echo '<div class="block courseitem">';
            echo '<div class="block_media">';
            echo bp_course_avatar();
            echo '<div class="avatar-list"><div class="hinhgiangvien">'.bp_course_get_instructor_avatar().'</div><div class="tongbaihoc">'.$dem.' bài học</div>'
                .'<div class="thoiluongvideo">Video '.tongthoigianvideokhoahoc($course_id).'</div>'.'</div>';
            echo nuthanhtoan();
            echo '</div>';

            echo '<div class="block_content">';
            echo '<h4 class="block_title">';
            echo bp_course_title();
            echo '</h4>';
            echo '<div class="item-meta">';
            echo bp_course_meta();
            echo '</div>';
//            echo '<div class="item-credits">';
//            echo bp_course_credits();
//            echo '</div>';
            echo '<div class="item-instructor">';
            echo bp_course_instructor();
            echo '</div>';
            echo '<div class="item-action">';
            echo bp_course_action();
            echo '</div>';
            echo '<div class="item_process">';
            echo '</div>';
            echo do_action( 'bp_directory_course_item' );
            echo '<div class="clear"></div>';
            echo '<div class="item-credits" style="width:100%; padding-top:12px; border-top:1px solid #EEE">';
            echo bp_course_credits();
            echo '</div>';
            echo '</div>';


            echo '<div class="clear"></div>';
            echo '</div>';
            echo '</li>';

        endwhile;
        echo '</ul><p></p>';
        wp_reset_postdata();
        echo '<div class="clear"></div>';
// phân trang trang chủ
        $num_page = $wp_query->max_num_pages;

        // echo '<div><ul class="sophantrang">';
        // if(empty($page)){
        // for($i=1; $i<6; $i++){
        // if($i <= $num_page){
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="'.$i.'"><span>'.$i.'</span></a></li>';
        // }else{
        // return  die();
        // }

        // }
        // echo '<li><span>...</span></li>';
        // }else{
        // if($page !=1){
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="1"><span> << Trang đầu</span></a></li>';
        // echo '<li><span>...</span></li>';
        // }
        // for($i=$page; $i<$page+5; $i++){
        // if($i <= $num_page){
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="'.$i.'"><span>'.$i.'</span></a></li>';

        // }else{
        // return  die();
        // }

        // }
        // if($page+5 <= $num_page){
        // echo '<li><span>...</span></li>';
        // }
        // }
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="'.$num_page.'"><span>Trang cuối >></span></a></li>';
        // echo '</ul></div>';
        echo '<div><ul class="sophantrang">';
        if(empty($page)){
            for($i=1; $i<6; $i++){
                if($i <= $num_page){
                    if($i==1){
                        echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange"><span>'.$i.'</span></li></a>';
                    }else{
                        echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                    }

                }else{
                    return die();
                }

            }
            echo '<li><span>...</span></li>';
        }else{
            if($page % 6 == 0){
                echo '<a class="sotrang" data-id="1"><li><span> << Trang đầu</span></li></a>';
                echo '<li><span>...</span></li>';
                for($i=$page; $i<$page+5; $i++){
                    if($i <= $num_page){
                        if($i == $page){
                            echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                        }else{
                            echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                        }


                    }else{
                        return  die();
                    }

                }
            }else{
                for($i=1; $i<$page+5; $i++){
                    if($i <= $num_page){
                        if($i == $page){
                            echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                        }else{
                            echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                        }

                    }else{
                        return  die();
                    }

                }
            }

            if($page+5 <= $num_page){
                echo '<li><span>...</span></li>';
            }
        }
        echo '<a class="sotrang" data-id="'.$num_page.'"><li><span>Trang cuối >></span></li></a>';
        echo '</ul></div>';
    }

    die();
}
?>

<?php
// lấy category level và ngôn ngữ theo khóa học cho menu trang chũ
add_action('wp_ajax_locdanhmuckhoahoc','locdanhmuckhoahoc');
add_action('wp_ajax_nopriv_locdanhmuckhoahoc','locdanhmuckhoahoc');
function locdanhmuckhoahoc(){
    global $wpdb;
    $term_id = $_POST['term_id'];
    $arg = array(
        'post_type'=>'course',
        'posts_per_page'=> 6,
        'post_status' =>'publish',
        'tax_query'=>array(
            array(
                'taxonomy'=>'course-cat',
                'terms'=>$term_id,
                'field'=>'term_id',
            ),
            'orderby'=>'id',
            'order'=>"DESC"
        )
    );

    $get_post = get_posts($arg);
    $dem=0;
    $temp ='';
    foreach($get_post as $value_post){
        $results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'term_relationships'.' WHERE object_id ='.$value_post->ID.'', OBJECT );
        foreach($results as $value_category){
            $results_name = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'terms'.' WHERE term_id ='.$value_category->term_taxonomy_id.'', OBJECT );
            foreach($results_name as $value){

                $valuep = $value->slug;
                if($dem == 0){
                    echo '<li>'.$valuep.'</li>';
                }

                if($dem != 0 && $temp != $valuep){
                    $temp = $valuep;
                    echo '<li>'.$valuep.'</li>';
                }
                $dem++;
            }

        }
    }
    die();
}

//    load danh sách khóa học khi click vào category
add_action('wp_ajax_hienthikhoahoctrangchu','hienthikhoahoctrangchu');
add_action('wp_ajax_nopriv_hienthikhoahoctrangchu','hienthikhoahoctrangchu');
function hienthikhoahoctrangchu(){
    $term_id = $_POST['term_id'];
    $name_term = $_POST['name_term'];
    $language_id = $_POST['name_term_language'];
    $level_id = $_POST['name_term_level'];
    $page = $_POST['page_ajax_term'];
    $name_term_language_text = $_POST['name_term_language_text'];
    $name_term_level_text = $_POST['name_term_level_text'];

    if($language_id==0 && $level_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=> 6,
            'paged' => $page,
            'post_status' =>'publish',
            'tax_query'=>array(
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$term_id,
                    'field'=>'term_id',
                ),
                'orderby'=>'id',
                'order'=>"DESC"
            )
        );
    }else if($level_id!=0 && $language_id==0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$term_id,
                    'field'=>'term_id',
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            )
        );
    }else if($level_id==0 && $language_id!=0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$term_id,
                    'field'=>'term_id',
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            )
        );
    }else if($level_id!=0 && $language_id!=0){
        $arg = array(
            'post_type'=>'course',
            'posts_per_page'=>6,
            'tax_query' => array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$language_id,
                    'field'=>'term_id'
                ),
                array(
                    'taxonomy'=>'course-cat',
                    'terms'=>$term_id,
                    'field'=>'term_id',
                ),
                array(
                    'taxonomy'=>'level',
                    'terms'=>$level_id,
                    'field'=>'term_id'
                ),
                'orderby'=>'id',
                'order'=>'DESC'
            )
        );
    }


    echo '<ul class="breadcrumb-trangchu"><li><span class="breadcrumb-trangchu-dskhoahoc">Danh sách khóa học</span></li></ul>';
//    echo '<ul class="breadcrumb-trangchu"><li><a href="'.get_home_url().'">Tất cả khóa học</a> > <a class="danhmuckhoahoc"><span data-id="'.$term_id.'">'.$name_term.'</span></a></li></ul>';
    echo '<ul class="breadcrumb-level">';
//    if($name_term_level_text=="Tất cả" && $name_term_language_text=="Tất cả"){
//        echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
////        if($name_term == "Tất cả"){
////            echo '<li>Danh mục: <span>'.$name_term.'</span></li>';
////        }else{
//            echo '<li>Danh mục: <span>'.$name_term.'</span><i class="icon-x"></i></li>';
////        }
//        echo '<li>Cấp độ: <span>Tất cả</span></li>';
//        echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
//    }else if($name_term_level_text!="Tất cả" && $name_term_language_text=="Tất cả"){
//        echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
////        if($name_term == "Tất cả"){
////            echo '<li>Danh mục: <span>'.$name_term.'</span></li>';
////        }else{
//            echo '<li>Danh mục: <span>'.$name_term.'</span><i class="icon-x"></i></li>';
////        }
//        echo '<li>Cấp độ: <span>'.$name_term_level_text.'</span><i class="icon-x"></i></li>';
//        echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
//    }else if($name_term_level_text=="Tất cả" && $name_term_language_text!="Tất cả"){
//        echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
////        if($name_term == "Tất cả"){
////            echo '<li>Danh mục: <span>'.$name_term.'</span></li>';
////        }else{
//            echo '<li>Danh mục: <span>'.$name_term.'</span><i class="icon-x"></i></li>';
////        }
//        echo '<li>Cấp độ: <span>Tất cả</span></li>';
//        echo '<li>Ngôn ngữ lập trình: <span>'.$name_term_language_text.'</span><i class="icon-x"></i></li>';
//    }else{
//        echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
////        if($name_term == "Tất cả"){
////            echo '<li>Danh mục: <span>'.$name_term.'</span></li>';
////        }else{
//            echo '<li>Danh mục: <span>'.$name_term.'</span><i class="icon-x"></i></li>';
////        }
//        echo '<li>Cấp độ: <span>'.$name_term_level_text.'</span><i class="icon-x"></i></li>';
//        echo '<li>Ngôn ngữ lập trình: <span>'.$name_term_language_text.'</span><i class="icon-x"></i></li>';
//    }

    echo '<li class="breadcrumb-trangchu-boloc">Đang lọc theo:</li>';
    echo '<li>Danh mục: <span>'.$name_term.'</span><i class="icon-x" data-id="1"></i></li>';
    if($level_id==0){
        echo '<li>Cấp độ: <span>Tất cả</span></li>';
    }else{
        echo '<li>Cấp độ: <span>'.$name_term_level_text.'</span><i class="icon-x" data-id="2"></i></li>';
    }
    if($language_id==0){
        echo '<li>Ngôn ngữ lập trình: <span>Tất cả</span></li>';
    }else{
        echo '<li>Ngôn ngữ lập trình: <span>'.$name_term_language_text.'</span><i class="icon-x" data-id="3"></i></li>';
    }

    echo '</ul>';
    $wp_query = new WP_Query($arg);
    $countcourse =  count($wp_query);
    if($countcourse == 0){
        echo "Không tìm thấy khóa học";
    }else{
        echo '<ul class="grid">';


        while($wp_query->have_posts()) : $wp_query->the_post();
            $course_id = get_the_ID();

            $course_curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
            $dem = 0;
            if(isset($course_curriculum)){
                foreach($course_curriculum as $item){
                    if(isset($item)){
                        $dem++;
                    }
                }
            }

            echo '<li class="clear3 col-md-4">';
            echo '<div class="block courseitem">';
            echo '<div class="block_media">';
            echo bp_course_avatar();
            echo '<div class="avatar-list"><div class="hinhgiangvien">'.bp_course_get_instructor_avatar().'</div><div class="tongbaihoc">'.$dem.' bài học</div>'
                .'<div class="thoiluongvideo">Video '.tongthoigianvideokhoahoc($course_id).'</div>'.'</div>';
            echo nuthanhtoan();
            echo '</div>';

            echo '<div class="block_content">';
            echo '<h4 class="block_title">';
            echo bp_course_title();
            echo '</h4>';
            echo '<div class="item-meta">';
            echo bp_course_meta();
            echo '</div>';
//            echo '<div class="item-credits">';
//            echo bp_course_credits();
//            echo '</div>';
            echo '<div class="item-instructor">';
            echo bp_course_instructor();
            echo '</div>';
            echo '<div class="item-action">';
            echo bp_course_action();
            echo '</div>';
            echo '<div class="item_process">';
            echo '</div>';
            echo do_action( 'bp_directory_course_item' );
            echo '<div class="clear"></div>';
            echo '<div class="item-credits" style="width:100%; padding-top:12px; border-top:1px solid #EEE">';
            echo bp_course_credits();
            echo '</div>';
            echo '</div>';


            echo '<div class="clear"></div>';
            echo '</div>';
            echo '</li>';

        endwhile;
        echo '</ul><p></p>';
        wp_reset_postdata();
        echo '<div class="clear"></div>';
// phân trang trang chủ
        $num_page = $wp_query->max_num_pages;

        // echo '<div><ul class="sophantrang">';
        // if(empty($page)){
        // for($i=1; $i<6; $i++){
        // if($i <= $num_page){
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="'.$i.'"><span>'.$i.'</span></a></li>';
        // }else{
        // return  die();
        // }

        // }
        // echo '<li><span>...</span></li>';
        // }else{
        // if($page !=1){
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="1"><span> << Trang đầu</span></a></li>';
        // echo '<li><span>...</span></li>';
        // }
        // for($i=$page; $i<$page+5; $i++){
        // if($i <= $num_page){
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="'.$i.'"><span>'.$i.'</span></a></li>';

        // }else{
        // return  die();
        // }

        // }
        // if($page+5 <= $num_page){
        // echo '<li><span>...</span></li>';
        // }
        // }
        // echo '<li><a class="sotrangajax" data-name="'.$name_term.'" data-term="'.$term_id.'" data-id="'.$num_page.'"><span>Trang cuối >></span></a></li>';
        // echo '</ul></div>';

        echo '<div><ul class="sophantrang">';
        if(empty($page)){
            for($i=1; $i<6; $i++){
                if($i <= $num_page){
                    if($i==1){
                        echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange"><span>'.$i.'</span></li></a>';
                    }else{
                        echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                    }

                }else{
                    return die();
                }

            }
            echo '<li><span>...</span></li>';
        }else{
            if($page % 6 == 0){
                echo '<a class="sotrang" data-id="1"><li><span> << Trang đầu</span></li></a>';
                echo '<li><span>...</span></li>';
                for($i=$page; $i<$page+5; $i++){
                    if($i <= $num_page){
                        if($i == $page){
                            echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                        }else{
                            echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                        }


                    }else{
                        return  die();
                    }

                }
            }else{
                for($i=1; $i<$page+5; $i++){
                    if($i <= $num_page){
                        if($i == $page){
                            echo '<a class="sotrang" data-id="'.$i.'"><li style="background-color: orange" ><span>'.$i.'</span></li></a>';
                        }else{
                            echo '<a class="sotrang" data-id="'.$i.'"><li><span>'.$i.'</span></li></a>';
                        }

                    }else{
                        return  die();
                    }

                }
            }

            if($page+5 <= $num_page){
                echo '<li><span>...</span></li>';
            }
        }
        echo '<a class="sotrang" data-id="'.$num_page.'"><li><span>Trang cuối >></span></li></a>';
        echo '</ul></div>';

    }

    die();
}
?>

<?php
//  xử lý lưu khóa học khi giảng viên edit
add_action('wp_ajax_chinhsuakhoahoc','chinhsuakhoahoc');
function chinhsuakhoahoc(){
    $user_id= get_current_user_id();
    $course_id = $_POST['ID'];
    $title = $_POST['title'];
    $status = $_POST['status'];
    $category = $_POST['category'];
    $newcategory = $_POST['newcategory'];
    $thumbnail_id = $_POST['thumbnail'];
    $description = $_POST['description'];
    $courselinkage = $_POST['courselinkage'];
    $newcourselinkage = $_POST['newcourselinkage'];
    $descriptionex = $_POST['descriptionex'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'create_course'.$user_id)  || !current_user_can('edit_posts')){
        _e('Security check Failed. Contact Administrator.','wplms-front-end');
        die();
    }


    if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
        _e('Invalid Course id, please edit a course','wplms-front-end');
        die();
    }

    $the_post = get_post($course_id);
    if($the_post->post_author != $user_id && !current_user_can('manage_options')){ // Instructor and Admin check
        _e('Invalid Course Instructor','wplms-front-end');
        die();
    }

    $course_post = array(
        'ID' => $course_id,
        'post_status' => $status,
        'post_title' => $title,
        'post_excerpt' => $descriptionex,
        'post_content' => $description,
    );

    $post_id = wp_update_post($course_post);
    echo $post_id;

    if(is_numeric($category)){
        wp_set_post_terms( $course_id, $category, 'course-cat');
    }else if($category == 'new'){
        $term = term_exists($newcategory, 'course-cat');
        if ($term !== 0 && $term !== null) {
            wp_set_post_terms( $course_id, $term['term_id'], 'course-cat');
        }else{
            $new_term = wp_insert_term($newcategory,'course-cat');
            if (is_array($new_term)) {
                wp_set_post_terms( $course_id, $new_term['term_id'], 'course-cat');
            }else{
                _e('Unable to create a new Course Category. Contact Admin !','wplms-front-end');
                die();
            }
        }
    }



    if(is_numeric($post_id) && $post_id){
        if(isset($thumbnail_id) && is_numeric($thumbnail_id))
            set_post_thumbnail($post_id,$thumbnail_id);

        //Linkage
        if(isset($courselinkage) && $courselinkage){
            $course_linkage = array($courselinkage);
            wp_set_post_terms( $post_id, $course_linkage, 'linkage' );
        }

        if($courselinkage == 'add_new'){
            $new_term = wp_insert_term($newcourselinkage,'linkage');
            if (is_array($new_term)) {
                $course_linkage = array($newcourselinkage);
                wp_set_post_terms( $post_id, $course_linkage, 'linkage' );
            }
        }

        echo $post_id;
    }else{
        _e('Unable to create course, contact admin !','wplms-front-end');
    }

    die();
}

?>

<?php
// xử lý tạo khóa học của giảng viên
add_action('wp_ajax_taokhoahoc','taokhoahoc');
function taokhoahoc(){
    $user_id= get_current_user_id();
    $title = $_POST['title'];
    $category = $_POST['category'];
    $newcategory = $_POST['newcategory'];
    $thumbnail_id = $_POST['thumbnail'];
    $description = $_POST['description'];
    $courselinkage = $_POST['courselinkage'];
    $newcourselinkage = $_POST['newcourselinkage'];
    $descriptionex = $_POST['descriptionex'];
    $category_language=$_POST['category_language'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'create_course'.$user_id) || !current_user_can('edit_posts')){
        _e('Security check Failed. Contact Administrator.','wplms-front-end');
        die();
    }

    $course_post = array(
        'post_status' => 'draft',
        'post_type'  => 'course',
        'post_title' => $title,
        'post_excerpt' => $descriptionex,
        'post_content' => $description,
        'comment_status' => 'open'
    );

    if(is_numeric($category)){
        $course_post['tax_input'] = array('course-cat' => $category, 'language-category'=>$category_language);
    }else if($category == 'new'){
        $term = term_exists($newcategory, 'course-cat');
        if ($term !== 0 && $term !== null) {
            $course_post['tax_input'] = array('course-cat' => $term['term_id']);
        }else{
            $new_term = wp_insert_term($newcategory,'course-cat');
            if (is_array($new_term)) {
                $course_post['tax_input'] = array('course-cat' => $new_term['term_id']);
            }else{
                _e('Unable to create a new Course Category. Contact Admin !','wplms-front-end');
                die();
            }
        }
    }


    $post_id = wp_insert_post($course_post);

    if(is_numeric($post_id)){
        if(isset($thumbnail_id) && is_numeric($thumbnail_id))
            set_post_thumbnail($post_id,$thumbnail_id);

        //Linkage
        if(isset($courselinkage) && $courselinkage){
            $course_linkage = array($courselinakge);
            wp_set_post_terms( $post_id, $course_linkage, 'linkage' );
        }

        if($courselinkage == 'add_new'){
            $new_term = wp_insert_term($newcourselinkage,'linkage');
            if (is_array($new_term)) {
                $course_linkage = array($newcourselinkage);
                $check = wp_set_post_terms( $post_id, $course_linkage, 'linkage' );
            }
        }

        echo $post_id;
    }else{
        _e('Unable to create course, contact admin !','wplms-front-end');
    }

    die();
}
?>

<?php
//Lưu nội dung bài post
add_action('wp_ajax_luuthutubaipost','luuthutubaipost');
function luuthutubaipost(){
    $user_id= get_current_user_id();
    $course_id =$_POST['course_id'];

//    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'create_course'.$user_id)  || !current_user_can('edit_posts')){
//        _e('Security check Failed. Contact Administrator.','wplms-front-end');
//        die();
//    }

    if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
        _e('Invalid Course id, please edit a course','wplms-front-end');
        die();
    }

    $the_post = get_post($course_id);
    if($the_post->post_author != $user_id && !current_user_can('manage_options')){
        _e('Invalid Course Instructor','wplms-front-end');
        die();
    }

    $objcurriculum = json_decode(stripslashes($_POST['curriculum']));
    if(is_array($objcurriculum) && isset($objcurriculum))
        foreach($objcurriculum as $c){
            $curriculum[]=$c->id;
        }

    // $curriculum=array(serialize($curriculum)); // Backend Compatiblity
    if(update_post_meta($course_id,'vibe_course_curriculum',$curriculum)){
        echo $course_id;
    }
//        else{
//            _e('Unable to save curriculum, please contact site admin','wplms-front-end');
//        }
    die();
}

?>

<?php
//Start: khải
//Thêm mục tiêu khóa học
add_action('wp_ajax_themmuctieu','themmuctieu');
//    add_action('wp_ajax_nopriv_test','test');

function themmuctieu(){
    $muctieu1=$_POST["goal1"];
    $muctieu2=$_POST["goal2"];
    $muctieu3=$_POST["goal3"];
    $postid=$_POST["idcourse"];
    update_post_meta($postid, 'muctieu1',$muctieu1 );
    update_post_meta($postid, 'muctieu2',$muctieu2 );
    update_post_meta($postid, 'muctieu3',$muctieu3 );
    die();
}
//Cập nhật mục tiêu khóa học
add_action('wp_ajax_capnhatmuctieu','capnhat');
//    add_action('wp_ajax_nopriv_test','test');
function capnhat(){
    $muctieu1=$_POST["goal1"];
    $muctieu2=$_POST["goal2"];
    $muctieu3=$_POST["goal3"];
    $postid=$_POST["idcourse"];
    update_post_meta($postid, 'muctieu1',$muctieu1 );
    update_post_meta($postid, 'muctieu2',$muctieu2 );
    update_post_meta($postid, 'muctieu3',$muctieu3 );
    die();
}


?>
<?php
//Remove  add_action('wp_ajax_create_quiz',array($this,'create_quiz'));
//remove_action('wp_ajax_create_quiz',array(WPLMS_Front_End::instance(),'create_quiz'));
add_action('wp_ajax_child_create_quiz','child_create_quiz');
function child_create_quiz(){
    $user_id= get_current_user_id();
    $course_id =$_POST['course_id'];
    $quiz_title = stripslashes($_POST['quiz_title']);

    if(!isset($quiz_title) || count($quiz_title) < 2 && $quiz_title == ''){
        _e('Can not have a Blank Quiz','wplms-front-end');
        die();
    }

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'create_course'.$user_id)  || !current_user_can('edit_posts')){
        _e('Security check Failed. Contact Administrator.','wplms-front-end');
        die();
    }

    if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
        _e('Invalid Course id, please edit a course','wplms-front-end');
        die();
    }

    $the_post = get_post($course_id);
    if($the_post->post_author != $user_id && !current_user_can('manage_options')){
        _e('Invalid Course Instructor','wplms-front-end');
        die();
    }

    $quiz_settings = array(
        'post_title' => $quiz_title,
        'post_content' => $quiz_title,
        'post_status' => 'publish',
        'post_type' => 'quiz',
    );
    $quiz_settings=apply_filters('wplms_front_end_quiz_vars',$quiz_settings);
    $quiz_id = wp_insert_post($quiz_settings);

    echo ' <div class="set_backgroud_unit" style="position: block; height: 45px">
                <h3 class="title" data-id="'.$quiz_id.'"><i class="icon-file"></i> '.$quiz_title.'</h3>
                </div>
                <div class="btn-group" style="z-index:9;margin-top:-40px">
                      <span style="font-size:7pt; margin: 5px 35px;" data-id="'.$quiz_id.'" class="add-question btn btn-success">'.__('Add Questions','wplms-front-end').'</span>
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
                         <a class="lecture_icon icon-file true-false"><span>True/False</span> </a>
                         <a class="lecture_icon icon-file match-answer"><span>Match answer</span> </a>
                        </div>
                        </ hr>
                        <div class="titleQuestions">Danh sách các câu trắc nghiệm</div>
                         <ul class="NoiDung"></ul>
                    </div>

                </div>
            ';

    //Linkage
    $linkage = vibe_get_option('linkage');
    if(isset($linkage) && $linkage){
        $course_linkage=wp_get_post_terms( $course_id, 'linkage',array("fields" => "names"));
        if(isset($course_linkage) && is_array($course_linkage))
            wp_set_post_terms($quiz_id, $course_linkage, 'linkage' );
    }
    die();
}
?>

<?php

//khải Create editor
//mutilple choice
add_action('wp_ajax_create_multiple_choice','create_multiple_choice');
function create_multiple_choice()
{
    //Create quiz
    echo '<li class="li-content">';
    echo '<div class="subtitleTN disappearbtn"></div>';
    echo '<div class="TNMultiple questions">';
    echo '<input type="text" class="form-control txttl titleTN" placeholder="Tiêu đề trắc nghiệm"  >';
    echo '<div style="height:50px" ></div>';
    echo '<textarea class="wp-editor-area editor" data-editable="true" rows="5" cols="40" name="content" placeholder="Nội dung câu hỏi trắc nghiệm"></textarea>';
    echo '<form><div class="CauTraLoi">
            <div class="titleAnswer">Thêm câu trả lời</div>
            <ul class="DapAn">
                <li class="NoiDungDapAn" >
                    <div>
                        <input class="rdb" type="radio" name="rdb" checked="checked">
                        <input type=\'text\' class="form-control traloi txttl" placeholder="Nhập vào câu trả lời">
                    </div>
                    <div >
                        <input type=\'text\' class="form-control giaithich txt" placeholder="Giải thích câu trả lời">
                    </div>
                      <div class="XoaTraLoi">Xóa</div>
                </li>
            </ul>
            <div>
              <input type="button" class="LuuCauHoi button btn-success btn-waring" value="Tạo câu hỏi">
              <input type="button" class="CapNhapCauHoi button btn-success disappearbtn id-question"  data-id="" value="Lưu thay đổi">
              <input type="button" class="HuyCauHoi button btn-success" value="Hủy">
           </div>
        </div></form></div></li>';
    die();
};
//true false
add_action('wp_ajax_create_true_false','create_true_false');
function create_true_false()
{
    //Create quiz
    echo '<li class="li-content">';
    echo '<div class="subtitleTN disappearbtn"></div>';
    echo '<div class="TNTrueFalse questions">';
    echo '<input type="text" class="form-control txttl titleTN" placeholder="Tiêu đề trắc nghiệm"  >';
    echo '<div style="height:50px" ></div>';
    echo '<textarea class="wp-editor-area editor" data-editable="true" rows="5" cols="40" name="content" placeholder="Nội dung câu hỏi trắc nghiệm"></textarea>';
    echo '<form><div class="CauTraLoi">
            <div class="titleAnswer">Chọn đáp án cho câu hỏi</div>
            <ul class="DapAn">
                 <li >

                        <input class="rdbTrueFalse" type="radio" name="rdbTrueFalse" checked="checked">
                        <span class="span-true-false">Đúng</span>
                 </li>
                 <li>
                        <input class="rdbTrueFalse" type="radio" name="rdbTrueFalse" checked="checked">
                        <span class="span-true-false">Sai</span>
                 </li>

            </ul>
            <div>
              <input type="button" class="LuuTrueFalse button btn-success btn-waring" value="Tạo câu hỏi">
              <input type="button" class="CapNhatTrueFalse button btn-success disappearbtn id-question"  data-id="" value="Lưu thay đổi">
              <input type="button" class="HuyCauHoi button btn-success" value="Hủy">
           </div>
        </div></form></div></li>';
    die();

};


add_action('wp_ajax_create_fill_in_the_blanks','create_fill_in_the_blanks');
function create_fill_in_the_blanks()
{
    echo '<li>';
    echo '<div class="TNFillInBlank">';
    wp_editor( '', 'editsometxt', array('textarea_name'=>'edit_txt','textarea_rows'=>10,'wpautop'=>false));
    echo '<form><div class="CauTraLoi">
            <input type="button" value="Lưu" class="btn btn-success">
        </div></form></div></li>';
    die();
};
?>

<?php
//Lưu trắc nghiệm multiple
add_action('wp_ajax_create_questions','create_questions');
function create_questions(){
    $quizid=$_POST['id'];
    $question_title=$_POST['qtitle'];
    $question_content=$_POST['qcontent'];
    //title question
    $question_settings = array(
        'post_title' => $question_title,
        'post_content' => sprintf(__('%s','wplms-front-end'),$question_content),
        'post_status' => 'publish',
        'post_type' => 'question',
    );
    $question_settings=apply_filters('wplms_front_end_question_vars',$question_settings);
    //Lưu question ,lấy được question id
    $qid = wp_insert_post($question_settings);

    $question_settings = array(
        'vibe_question_type' => 'single',
        'vibe_question_options' => '',
        'vibe_question_answer' => 0,
        'vibe_question_hint' => '',
        'vibe_question_explaination' => ''
    );
    $question_settings = apply_filters('wplms_front_end_question_settings',$question_settings);
    $vibe_question_options = array();
    $user_id = get_current_user_id();
    if(!isset($qid) || !is_numeric($qid) && $qid == ''){
        _e('Unable to save, incorrect question','wplms-front-end');

        die();
    }
    foreach($question_settings as $key => $value){
        if($value != $_POST[$key] && $_POST[$key]){
            if($key != 'vibe_question_options')
                update_post_meta($qid,$key,$_POST[$key]);
        }
    }
    $objcurriculum = json_decode(stripslashes($_POST['vibe_question_options']));
    if(is_array($objcurriculum) && isset($objcurriculum))
        foreach($objcurriculum as $c){
            $vibe_question_options[]=$c->option;

            update_post_meta($qid,'vibe_question_options',$vibe_question_options);
        }
    echo '<input type="hidden" class="questionid" value="'.$qid.'">';
    die();
};

//update trắc nghiệm multiple
add_action('wp_ajax_update_questions','update_questions');
function update_questions(){
    $qid=$_POST['id'];
    //Lưu title và content
    $questiontitle=$_POST['qtitle'];
    $questioncontent=$_POST['qcontent'];
    $question_settings = array(
        'ID'=>$qid,
        'post_title' => $questiontitle,
        'post_content' =>$questioncontent,
        'post_type'=>'question',
        'post_status' => 'publish'
    );
    wp_update_post($question_settings);
    $objcurriculum = json_decode(stripslashes($_POST['vibe_question_options']));
    if(is_array($objcurriculum) && isset($objcurriculum))
        foreach($objcurriculum as $c){
            $vibe_question_options[]=$c->option;
        }
    $answer=$_POST['vibe_question_answer'];
    $explaination=$_POST['vibe_question_explaination'];
    update_post_meta($qid,'vibe_question_answer',$answer);
    update_post_meta($qid,'vibe_question_options',$vibe_question_options);
    update_post_meta($qid,'vibe_question_explaination',$explaination);
    die();
};
//Xóa question
add_action('wp_ajax_delete_questions','delete_questions');
function delete_questions(){
    $qid=$_POST['id'];
    wp_delete_post($qid,true);


    die();
};
//Lưu các question vào quiz
//update trắc nghiệm
add_action('wp_ajax_saves_quiz_settings','saves_quiz_settings');
function saves_quiz_settings(){
    $quiz_id=$_POST['quiz_id'];
    $objquestions = json_decode(stripslashes($_POST['questions']));
    $questions = array();
    if(is_array($objquestions) && isset($objquestions))
        foreach($objquestions as $c){
            $questions['ques'][]= $c->ques;
            /*$questions['marks'][]= $c->marks;*/
        };
    update_post_meta($quiz_id,'vibe_quiz_questions',$questions);
    die();
};
?>


<?php
//Lưu trắc nghiệm true false
add_action('wp_ajax_create_questions_truefalse','create_questions_truefalse');
function create_questions_truefalse(){

    $question_title=$_POST['qtitle'];
    $question_content=$_POST['qcontent'];
    //title question
    $question_settings = array(
        'post_title' => $question_title,
        'post_content' => sprintf(__('%s','wplms-front-end'),$question_content),
        'post_status' => 'publish',
        'post_type' => 'question',
    );
    $question_settings=apply_filters('wplms_front_end_question_vars',$question_settings);
    //Lưu question ,lấy được question id
    $qid = wp_insert_post($question_settings);
    $answer=$_POST['vibe_question_answer'];
    $qtype=$_POST['vibe_question_type'];
    add_post_meta($qid,'vibe_question_answer',$answer);
    add_post_meta($qid,'vibe_question_type',$qtype);
    echo '<input type="hidden" class="questionid" value="'.$qid.'">';
    die();
};
//Create multiple correct
add_action('wp_ajax_create_multiple_correct','create_multiple_correct');
function create_multiple_correct()
{
    echo '<li class="li-content">';
    echo '<div class="subtitleTN disappearbtn"></div>';
    echo '<div class="TNMultiplecorrect questions">';
    echo '<input type="text" class="form-control txttl titleTN" placeholder="Tiêu đề trắc nghiệm"  >';
    echo '<div style="height:50px" ></div>';
    echo '<textarea class="wp-editor-area editor" data-editable="true" rows="5" cols="40" name="content" placeholder="Nội dung câu hỏi trắc nghiệm"></textarea>';
    echo '<form><div class="CauTraLoi">
            <div class="titleAnswer">Thêm câu trả lời</div>
            <ul class="DapAn">
                <li class="NoiDungDapAn" >
                    <div>
                        <input type="checkbox" name="ckbmultilple" class="ckbmultilple">
                        <input type=\'text\' class="form-control ckbtraloi txttl" placeholder="Nhập vào câu trả lời">
                    </div>

                      <div class="ckbXoaTraLoi">Xóa</div>
                </li>
            </ul>
              <textarea class="wp-editor-area editor txtGiaiThich" data-editable="true" rows="5" cols="40" name="content" placeholder="Nhập vào nội dung giải thích"></textarea>
              <input type="button" class="LuuMultipleCorrect button btn-success btn-waring" value="Tạo câu hỏi">
              <input type="button" class="CapNhapMultipleCorrect button btn-success disappearbtn id-question" data-id="" value="Lưu thay đổi">
              <input type="button" class="HuyCauHoi button btn-success" value="Hủy">
           </div>
        </div></form></div></li>';
    die();
}
//Create question multiple correct
//Lưu trắc nghiệm multiple
add_action('wp_ajax_create_questions_multiplecorrect','create_questions_multiplecorrect');
function create_questions_multiplecorrect(){
    $quizid=$_POST['id'];
    $question_title=$_POST['qtitle'];
    $question_content=$_POST['qcontent'];
    //title question
    $question_settings = array(
        'post_title' => $question_title,
        'post_content' => sprintf(__('%s','wplms-front-end'),$question_content),
        'post_status' => 'publish',
        'post_type' => 'question',
    );
    $question_settings=apply_filters('wplms_front_end_question_vars',$question_settings);
    //Lưu question ,lấy được question id
    $qid = wp_insert_post($question_settings);

    $question_settings = array(
        'vibe_question_type' => 'multiple',
        'vibe_question_options' => '',
        'vibe_question_answer' => 0,
        'vibe_question_hint' => '',
        'vibe_question_explaination' => ''
    );
    $question_settings = apply_filters('wplms_front_end_question_settings',$question_settings);
    $vibe_question_options = array();
    $user_id = get_current_user_id();
    if(!isset($qid) || !is_numeric($qid) && $qid == ''){
        _e('Unable to save, incorrect question','wplms-front-end');

        die();
    }
    foreach($question_settings as $key => $value){
        if($value != $_POST[$key] && $_POST[$key]){
            if($key != 'vibe_question_options')
                update_post_meta($qid,$key,$_POST[$key]);
        }
    }
    $objcurriculum = json_decode(stripslashes($_POST['vibe_question_options']));
    if(is_array($objcurriculum) && isset($objcurriculum))
        foreach($objcurriculum as $c){
            $vibe_question_options[]=$c->option;

            update_post_meta($qid,'vibe_question_options',$vibe_question_options);
        }
    echo '<input type="hidden" class="questionid" value="'.$qid.'">';
    die();
};

//Create match anwser
add_action('wp_ajax_create_question_match_answer','create_question_match_answer');
function create_question_match_answer(){
    echo '<li class="li-content">';
    echo '<div class="subtitleTN disappearbtn"></div>';
    echo '<div class="TNmatchanswer questions">';
    echo '<input type="text" class="form-control txttl titleTN" placeholder="Tiêu đề trắc nghiệm"  >';
    echo '<div style="height:50px" ></div>';
    echo '<div class="content_editor">';
    echo '</div>';
    echo '<form><div class="CauTraLoi">
            <div class="titleAnswer">Thêm câu trả lời</div>
            <ul class="DapAn">
                <li class="NoiDungDapAn">
                    <div>
                        <input type="number" min="1" name="ckbmultilple" class="txtThuTu form-control" value="1">
                        <input type=\'text\' class="form-control txtmatch-anwser txttl " placeholder="Nhập vào câu trả lời">
                    </div>
                      <div class="XoaTraLoi XoaMatchAnwser">Xóa</div>
                </li>
            </ul>
              <textarea class="wp-editor-area editor txtGiaiThich" data-editable="true" rows="5" cols="40" name="content" placeholder="Nhập vào nội dung giải thích" ></textarea>
              <input type="button" class="LuuMultipleCorrect button btn-success btn-waring" value="Tạo câu hỏi">
              <input type="button" class="CapNhapMultipleCorrect button btn-success disappearbtn id-question" data-id="" value="Lưu thay đổi">
              <input type="button" class="HuyCauHoi button btn-success" value="Hủy">
           </div>
        </div></form></div></li>';
    die();
}
//phần backtocourse
remove_action('wp_ajax_unit_traverse', 'unit_traverse');
remove_action( 'wp_ajax_nopriv_unit_traverse', 'unit_traverse' );
add_action('wp_ajax_unit_traverse', 'unit_traverse1');
add_action( 'wp_ajax_nopriv_unit_traverse', 'unit_traverse1' );
function unit_traverse1(){
    $unit_id= $_POST['id'];
    $course_id = $_POST['course_id'];
    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') ){
        _e('Security check Failed. Contact Administrator.','vibe');
        die();
    }
// Check if user has taken the course
    $user_id = get_current_user_id();
    $coursetaken=get_user_meta($user_id,$course_id,true);
//if(!isset($_COOKIE['course'])) {
//   code cũ if($coursetaken>time()){
    if($coursetaken){
        setcookie('course',$course_id,$expire,'/');
        $_COOKIE['course'] = $course_id;
    }else{
        $pid=get_post_meta($course_id,'vibe_product',true);
        $pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); // $id checks for Single Course page or Course page in the my courses section
        if(is_numeric($pid))
            $pid=get_permalink($pid);
        echo '<div class="message"><p>'.__('Course Expired.','vibe').'<a href="'.$pid.'" class="link alignright">'.__('Click to renew','vibe').'</a></p></div>';
        die();
    }
//}
    if(isset($coursetaken) && $coursetaken){
        $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
        $units=array();
        foreach($course_curriculum as $key=>$curriculum){
            if(is_numeric($curriculum)){
                $units[]=$curriculum;
            }
        }
// Drip Feed Check
        $drip_enable=get_post_meta($course_id,'vibe_course_drip',true);
        if(vibe_validate($drip_enable)){
            $drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);
            $unitkey = array_search($unit_id,$units);
            if($unitkey == 0){
                $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);
                if(!isset($pre_unit_time) || $pre_unit_time ==''){
                    add_post_meta($units[$unitkey],$user_id,time());
                }
            }else{
                $pre_unit_time=get_post_meta($units[($unitkey-1)],$user_id,true);
                if(isset($pre_unit_time) && $pre_unit_time){
                    $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);
                    $value = $pre_unit_time + $drip_duration*$drip_duration_parameter;
                    $value = apply_filters('wplms_drip_value',$value,$units[($unitkey-1)],$course_id);
                    if($value > time()){
                        echo '<div class="message"><p>'.__('Unit will be available in ','vibe').tofriendlytime(($pre_unit_time + ($drip_duration)*$drip_duration_parameter)-time()).'</p></div>';
                        die();
                    }else{
                        $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);
                        if(!isset($pre_unit_time) || $pre_unit_time ==''){
                            add_post_meta($units[$unitkey],$user_id,time());
                            bp_course_record_activity(array(
                                'action' => __('Student started a unit','vibe'),
                                'content' => __('Student started the unit ','vibe').get_the_title($unit_id).__(' in course ','vibe').get_the_title($course_id),
                                'type' => 'unit',
                                'primary_link' => get_permalink($unit_id),
                                'item_id' => $unit_id,
                                'secondary_item_id' => $user_id
                            ));
                        }
                    }
                }else{
                    echo '<div class="message"><p>'.__('Unit can not be accessed.','vibe').'</p></div>';
                    die();
                }
            }
        }
// END Drip Feed Check
        echo '<div class="tabheader" style="width:77.7%;">
<span class="title_unit" >'.get_the_title($unit_id).'</span>
</div>';
        $typequiz = get_post_type($unit_id);
        if($typequiz == "quiz"){
            do_action('wplms_unit_header',$unit_id,$course_id);
        }

        echo '<div id="unit" class="quiz_title" data-unit="'.$unit_id.'">';

        the_unit_tags($unit_id);
        the_unit_instructor($unit_id);
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
            do_action('wplms_course_unit_meta');
            if($mins < 9999){
                if($unit_duration_parameter == 1)
                    echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Minutes','vibe'):'').' '.$minutes.__(' seconds','vibe').'</span>';
                else if($unit_duration_parameter == 60)
                    echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.__(' minutes','vibe').'</span>';
                else if($unit_duration_parameter == 3600)
                    echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Days','vibe'):'').' '.$minutes.__(' hours','vibe').'</span>';
            }
        }
        echo '<div class="clear"></div>';
        echo '<h1>'.get_the_title($unit_id).'</h1>';
        echo '<h3>';
        the_sub_title($unit_id);
        echo '</h3></div>';
        the_unit($unit_id);
        $unit_class='unit_button';
        $hide_unit=0;
        $nextunit_access = vibe_get_option('nextunit_access');
        $k=array_search($unit_id,$units);
        $done_flag=get_user_meta($user_id,$unit_id,true);
        $next=$k+1;
        $prev=$k-1;
        $max=count($units)-1;
        echo '<div class="unit_prevnext">';
        echo '<div class="col-md-2"> <span class="backtocourse" data-id="'.$course_id.'"><i class="icon-arrow-1-left"></i> Trở về khóa học </span></div>';
        echo '<div class="col-md-2">';
        if($prev >=0){
            if(get_post_type($units[$prev]) == 'quiz'){
                $quiz_status = get_user_meta($user_id,$units[$prev],true);
                if(!empty($quiz_status))
                    echo '<a href="#" data-unit="'.$units[$prev].'" class="'.$unit_class.'">'.__('Back to Quiz','vibe').'</a>';
                else
                    echo '<a href="'.get_permalink($units[$prev]).'" class="unit_button">'.__('Back to Quiz','vibe').'</a>';
            }else
                echo '<a href="#" id="prev_unit" data-unit="'.$units[$prev].'" class="unit unit_button">'.__('Previous Unit','vibe').'</a>';
        }
        echo '</div>';

        echo '<div class="col-md-2">';
        if($next <= $max){
            if(isset($nextunit_access) && $nextunit_access){
                $hide_unit=1;
                if(isset($done_flag) && $done_flag){
                    $unit_class .=' ';
                    $hide_unit=0;
                }else{
                    $unit_class .=' hide';
                    $hide_unit=1;
                }
            }
            if(get_post_type($units[$next]) == 'quiz'){
                $quiz_status = get_user_meta($user_id,$units[$next],true);
                if(!empty($quiz_status))
                    echo '<a href="#" data-unit="'.$units[$next].'" class="unit '.$unit_class.'">'.__('Proceed to Quiz','vibe').'</a>';
                else
//                    echo '<a href="'.get_permalink($units[$next]).'" class=" unit_button">'.__('Proceed to Quiz','vibe').'</a>';
                    echo '<a href="#" data-unit="'.$units[$next].'" class="unit '.$unit_class.'">'.__('Proceed to Quiz','vibe').'</a>';
            }else
                echo '<a href="#" id="next_unit" '.(($hide_unit)?'':'data-unit="'.$units[$next].'"').' class="unit '.$unit_class.'">'.__('Next Unit','vibe').'</a>';
        }
        echo '</div>';

        echo '<div class="col-md-4" style="text-align: center">';
        if(get_post_type($units[($k)]) == 'quiz'){
            $quiz_status = get_user_meta($user_id,$units[($k)],true);
//            if(!empty($quiz_status)){
//                echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
//            }else{
//                echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button start_quiz">'.__('Start Quiz','vibe').'</a>';
//            }
            if(is_numeric($quiz_status)){
                if($quiz_status < time()){
                    echo '<script>document.getElementsByClassName("quiz_meta")[0].style.display = "none"</script>';
                    echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                }else{
                    $quiz_class = apply_filters('wplms_in_course_quiz','');
                    echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.' continue">'.__('Continue Quiz','vibe').'</a>';
                }
            }else{
                $quiz_class = apply_filters('wplms_in_course_quiz','');
                echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.'">'.__('Start Quiz','vibe').'</a>';
            }
        }else
            echo ((isset($done_flag) && $done_flag)?'': apply_filters('wplms_unit_mark_complete','<a href="#" id="mark-complete" data-unit="'.$units[($k)].'" class="unit_button">'.__('Mark this Unit Complete','vibe').'</a>',$unit_id,$course_id));
        echo '</div>';

        echo '<div class="col-md-2">';
        echo    '<span class="auto_complete" data-id="false"> </span>';
        echo '</div>';

        echo '</div></div>';
    }
    die();
}

// Thêm thảo luận cho từng unit
add_action('wp_ajax_create_unit_discussion','create_unit_discussion');
function create_unit_discussion(){

    $userid=$_POST["user_id"];
    $noidung=$_POST["cdcontent"];
    $tieude=$_POST["cdtitle"];
    $unitid=$_POST["cdunitid"];
    $courseid=$_POST["cdcourseid"];
    $current_user = get_current_user_id();

    $listauthor = wp_get_current_user();
    $author = $listauthor->display_name;
    $mail_author=$listauthor->user_mail;
    $data = array(
        'comment_post_ID' => $unitid,
        'comment_author' => $author,
        'comment_content' => $noidung,
        'user_id' => $current_user,
        'comment_author_mail'=>$mail_author
    );
    $getid = wp_insert_comment($data);
    update_comment_meta($getid,'review_title',$tieude);
    update_comment_meta($getid,'title_discussion',$tieude);

//    Thêm hành động bình luận
    $author_id = get_post_field('post_author',$courseid);

    $datacomment = array(
        'action' => 'Học viên đã bình luận tại khóa học '.get_the_title($courseid),
        'user_id' => $current_user, // user thực hiện hành động, user gữi hành động
        'component' => 'comments_unit',
        'type' => 'activity_update',
        'content' => $noidung,
        'primary_link' => get_permalink($courseid),
        'item_id' => $courseid, //dùng để lưu id của khóa học
        'secondary_item_id' => $current_user, //id của user sẽ nhận được hành động hoạt thông báo
    );

    $activity_id = bp_activity_add($datacomment);

//    Thêm thông báo
    $datanotify = array(
        'user_id' => $author_id,
        'item_id' => $getid,
        'secondary_item_id' => get_current_user_id(),
        'component' => 'comments',
        'component_name' => 'messages',
        'is_new' => 1,
        'component_action' => 'new_message'

    );
    bp_notifications_add_notification($datanotify);

//    Lấy id củ của bảng messages và cộng thêm 1 để tạo thành Thread_id
    global $wpdb;
    $old_id = 0;
    $old_id_message = $wpdb->get_results('SELECT id FROM itclass_songle_bp_messages_messages ORDER BY id DESC LIMIT 1 ');
    foreach($old_id_message as $item){
        $old_id= $item->id + 1;
    }
//    Thêm dữ liệu vào bảng messages
    $user = get_userdata(get_current_user_id());//$user->user_nicename
    $time_messages = current_time( 'mysql' );
    $messsage_title = $user->user_nicename.' đã bình luận tại khóa học <a href=\"'.get_permalink($courseid).'\"><b>'.get_the_title($courseid).'</b></a>';
    $wpdb->get_results('INSERT INTO itclass_songle_bp_messages_messages (thread_id,sender_id,subject,message,date_sent) VALUES ("'.$old_id.'","'.get_current_user_id().'","'.$messsage_title.'","'.$noidung.'","'.$time_messages.'")');


    $wpdb->get_results('INSERT INTO itclass_songle_bp_messages_recipients (user_id,thread_id,unread_count,sender_only,is_deleted) VALUES ("'.$author_id.'","'.$old_id.'",1,0,0)');

    $noidungsau = get_comment_text($getid);
    echo "<div class='result'>";
    echo '<div class="item-discustion">';
    echo ' <div class="cmtauthor row">';
    echo '<div class="HieuChinh-ds">';
    echo '<div class="Xoads"><i class="icon-x"></i> </div>';
    echo '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
    echo '<input class="id-comment-ds" type="hidden" value="'.$getid.'">';
    echo '</div>';
    echo '<div class="col-md-1">';
    echo get_avatar( get_current_user_id(), 32 ) ;
    echo '</div>';
    echo '<div class="col-md-10" >';
    echo '<span class="authorname">'. $author . '</span>'.'<span style="font-style:italic"> vừa xong </span>';                            ;
    echo '</div></div><br> ';
    echo '<div class="NoiDungCMTUser row">';
    echo '<div class="col-md-1"></div>';
    echo '<div class="col-md-10">';
    echo '<div class="comment-title-user" data-id="'.get_current_user_id().'">'.get_comment_meta($getid,'title_discussion',true) .' </div>';
    echo '<div class="comment-content-user">'.$noidungsau.'</div>';
    echo '<div class="list-comment be-frist"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-'.$getid.'">Hãy là người đầu tiên trả lời bình luận này</a></li></ul></div>';
    echo '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$getid.'">Ẩn đi</a></li></ul></div>';
    echo '<div class="child_comment">';
    echo '<div class="content_child_comment_start"></div>';
    echo '<div class="content_child_comment"></div> ';
    echo '</div>';
    echo '</div></div>';
    echo '<div class="edit_content_editor"></div>';
    echo '<hr/>';
    echo '</div>';
    die();
}

//Load thảo luận view more bình luận khóa học
add_action('wp_ajax_viewmorediscusstion','viewmorediscusstion');
function viewmorediscusstion(){

    $sotrangviewmore = $_POST['sotrangviewmore'];
    $timkiem=$_POST['timkiem'];
    $id = $_POST['id'];
    $course_curriculum=vibe_sanitize(get_post_meta($id,'vibe_course_curriculum',false));
    $unit_id = wplms_get_course_unfinished_unit($id);

    $unit_comments = vibe_get_option('unit_comments');
    $units=array();
    if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
        foreach($course_curriculum as $key=>$curriculum){
            if(is_numeric($curriculum)){
                $units[]=$curriculum;
            }
        }
    }

//    $argsComment = array();
//    // lấy danh sách comment của unit và add vào một mảng
//    foreach($units as $unit_id_comment){
//        $args = array(
//            'post_id' => $unit_id_comment,
//            'parent' => 0
//
//        );
//
//        $comments = get_comments($args);
//        $argsComment = array_merge_recursive($argsComment,$comments);
//    }
//
//    // lấy danh sách comment của khóa học và add vào mảng comment của unit
//    $args = array(
//        'post_id' => $id,
//        'parent' => 0
//    );
//    $comments = get_comments($args);
//    $argsComment = array_merge_recursive($argsComment,$comments);
//
//
//    // sắp xếp mãng object của bình luận tăng dần
//    function cmp($a, $b)
//    {
//        return strcmp($a->comment_ID, $b->comment_ID);
//    }
//
//    usort($argsComment, "cmp");

    global $wpdb;
    $bien='';
    for($i=0;$i<count($units);$i++){
        if($i==count($units)-1){
            $bien.=$wpdb->comments.".comment_post_ID=".$units[$i];
        }else{
            $bien.=$wpdb->comments.".comment_post_ID=".$units[$i]." OR ";
        }

    }

    $query = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->commentmeta.".meta_key = 'title_discussion' AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$timkiem."%' OR ".$wpdb->comments.".comment_content like '%".$timkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC LIMIT ".$sotrangviewmore.",10";
//    $query = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$timkiem."%' OR ".$wpdb->comments.".comment_content like '%".$timkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC LIMIT ".$sotrangviewmore.",10";
//    $querytong = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$timkiem."%' OR ".$wpdb->comments.".comment_content like '%".$timkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC";
    $querytong = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->commentmeta.".meta_key = 'title_discussion' AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$timkiem."%' OR ".$wpdb->comments.".comment_content like '%".$timkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC";
    $argsComment = $wpdb->get_results($query);
    $argsCommenttong = $wpdb->get_results($querytong);
    $thaoluantaiunit = 0;
    $tongsothaoluan = count($argsCommenttong);

    $dulieu='';
    foreach($argsComment as $comment) {
        if($comment->comment_parent == 0){
            $checkCommentMeta = get_comment_meta($comment->comment_ID,'review_rating',true);
            if(empty($checkCommentMeta)){

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

                $dulieu.= '<div class="item-discustion">';
                $dulieu.= '<div class="cmtauthor row">';
                $dulieu.= '<div class="HieuChinh-ds">';
                if ($comment->user_id == get_current_user_id()) {
                    $dulieu.= '<div class="Xoads"><i class="icon-x"></i> </div>';
                    $dulieu.= '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
                }
                $dulieu.= '<input class="id-comment-ds" type="hidden" value="' . $comment->comment_ID . '">';
                $dulieu.= '</div>';
                $dulieu.= '<div class="col-md-1">';
                $dulieu.= get_avatar($comment->user_id, 32);
                $dulieu.= '</div>';
                $dulieu.= '<div class="col-md-10" >';

                foreach ($units as $unit_id_comment) {
                    if ($comment->comment_post_ID == $unit_id_comment) {
                        $thaoluantaiunit = $comment->comment_post_ID;
                    }
                }

                if ($thaoluantaiunit != 0) {
                    $dulieu.= '<span class="authorname">' . $comment->comment_author . '</span>' . '<span style="font-style:italic"> đã gửi 1 thảo luận tại bài <span class="unit_line"> <a class="unit" data-unit="' . $thaoluantaiunit . '" ><b>' . get_the_title($thaoluantaiunit) . '</b></a> </span> cách đây ' . human_time_diff( strtotime($comment->comment_date), strtotime(current_time( 'mysql' ))  ) . '</span>';;
                } else {
                    $dulieu.= '<span class="authorname">' . $comment->comment_author . '</span>' . '<span style="font-style:italic"> đã gửi 1 thảo luận cách đây ' . human_time_diff( strtotime($comment->comment_date), strtotime(current_time( 'mysql' ))  ) . '</span>';;

                }


                $dulieu.= '</div></div><br>';
                if($thaoluantaiunit != 0){
                    $dulieu.= '<div data-id="'.$comment->comment_ID.'" data-course-id="'.$thaoluantaiunit.'" class="NoiDungCMTUser row">';
                }else{
                    $dulieu.= '<div data-id="'.$comment->comment_ID.'" data-course-id="'.$id.'" class="NoiDungCMTUser row">';
                }
//                $dulieu.= '<div data-id="' . $comment->comment_ID . '" class="NoiDungCMTUser row">';
                $dulieu.= '<div class="col-md-1"></div>';
                $dulieu.= '<div class="col-md-10">';
                $dulieu.= '<div class="comment-title-user">' . get_comment_meta($comment->comment_ID, 'title_discussion', true) . ' </div>';
                $dulieu.= '<div class="comment-content-user">' . $comment->comment_content . '</div>';

                if ($number_comments_child != 0) {
                    $dulieu.= '<div class="list-comment"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-' . $comment->comment_ID . '">Hiện ' . $number_comments_child . ' trả lời</a></li></ul></div>';
                    $dulieu.= '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-' . $comment->comment_ID . '">Ẩn ' . $number_comments_child . ' trả lời</a></li></ul></div>';
                } else {
                    $dulieu.= '<div class="list-comment be-frist"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-' . $comment->comment_ID . '">Hãy là người đầu tiên trả lời bình luận này</a></li></ul></div>';
                    $dulieu.= '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$comment->comment_ID.'">Ẩn đi</a></li></ul></div>';
                }

//                            echo '<div class="content_child_comment">
//
//                                    </div>';

                $dulieu.= '<div class="child_comment">';
                $dulieu.= '<div class="content_child_comment_start">';
                foreach ($comments_child as $value) {
                    $dulieu.= '<li>';
                    $dulieu.= '<div class="item-discustion child">';
                    $dulieu.= '<div class="cmtauthor child row">';
                    $dulieu.= '<div class="HieuChinh-ds child">';
                    if ($value->user_id == get_current_user_id()) {
                        $dulieu.= '<div class="Xoads"><i class="icon-x"></i> </div>';
                        $dulieu.= '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
                    }
                    $dulieu.= '<input class="child id-comment-ds" type="hidden" value="' . $value->comment_ID . '">';
                    $dulieu.= '</div>';
                    $dulieu.= '<div class="col-md-1">';
                    $dulieu.= get_avatar($value->user_id, 32);
                    $dulieu.= '</div>';
                    $dulieu.= '<div class="col-md-10" >';

                    $dulieu.= '<span class="authorname">' . $value->comment_author . '</span>' . '<span style="font-style:italic"> đã gửi 1 thảo luận cách đây ' . human_time_diff( strtotime($value->comment_date), strtotime(current_time( 'mysql' ))  ) . '</span>';;

                    $dulieu.= '</div></div><br>';
                    $dulieu.= '<div data-id="' . $value->comment_ID . '" data-course-id="'.$thaoluantaiunit.'" class="child NoiDungCMTUser row">';
                    $dulieu.= '<div class="col-md-1"></div>';
                    $dulieu.= '<div class="col-md-10">';
                    $dulieu.= '<div class="comment-title-user">' . get_comment_meta($value->comment_ID, 'title_discussion', true) . ' </div>';
                    $dulieu.= '<div class="comment-content-user">' . $value->comment_content . '</div>';
                    $dulieu.= '</div></div>';
                    $dulieu.= '<div class="edit_content_editor_child"></div>';
                    $dulieu.= '</li>';
                }
                $dulieu.='</div>';
                $dulieu.= '<div class="content_child_comment"></div>';
                $dulieu.= '</div>';

                $dulieu.= '</div></div>';

                $dulieu.= '<div class="edit_content_editor "></div><hr>';
                $dulieu.= '</div>';

            }

        }

    }
    $tong = $sotrangviewmore+count($argsComment);
    if($tongsothaoluan > $tong){
        $dulieu.= ' <div data-course-id="'.$id.'" tong="'.$tongsothaoluan.'" data-page="'.$tong.'" class="xemthembinhluan"><span class="btn btn-primary"><i style="display: none" class="noidungthongbaoloading icon-refresh glyphicon-refresh-animate"></i> Xem thêm...</span></div>';
    }
    $dulieu.='</div>';

    echo $dulieu;

    die();
}

//
// In course Quiz

add_action('wp_ajax_child_in_start_quiz', 'child_incourse_start_quiz');

function child_incourse_start_quiz(){
    $quiz_id= $_POST['quiz_id'];
    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($quiz_id)){
        _e('Security check Failed. Contact Administrator.','vibe');
        die();
    }

    $user_id = get_current_user_id();

    do_action('wplms_before_quiz_begining',$quiz_id);

    $get_questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));

    if(!isset($get_questions) || !is_array($get_questions)) // Fallback for Older versions
        $get_questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));


    if(!is_array($get_questions) || !is_array($get_questions['ques']) || !is_array($get_questions['marks'])){
        echo $quiz_id;
        die();
    }


    $questions=$get_questions['ques'];
    $marks=$get_questions['marks'];
    $posts_per_page = apply_filters('wplms_incourse_quiz_per_page',10);
    $page = $_POST['page'];


    if(!isset($page) || !is_numeric($page) || !$page){
        $page = 1;
        // Add user to quiz : Quiz attempted by user
        update_post_meta($quiz_id,$user_id,0);
        $quiz_duration_parameter = apply_filters('vibe_quiz_duration_parameter',60);
        $quiz_duration = get_post_meta($quiz_id,'vibe_duration',true) * $quiz_duration_parameter; // Quiz duration in seconds
        $expire=time()+$quiz_duration;
        update_user_meta($user_id,$quiz_id,$expire);
        do_action('wplms_start_quiz',$quiz_id,$user_id);
        // Start Quiz Notifications
    }

    $args = apply_filters('wplms_in_course_quiz_args',array('post__in' => $questions,'post_type'=>'question','posts_per_page'=>$posts_per_page,'paged'=>$page,'orderby'=>'post__in'));

    $the_query = new WP_Query($args);

    $quiz_questions = array();

    if ( $the_query->have_posts() ) {
        echo '<script>var all_questions_json = '.json_encode($questions).'</script>';
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            global $post;
            $loaded_questions[]=get_the_ID();
            $key = array_search(get_the_ID(),$questions);
            $hint = get_post_meta(get_the_ID(),'vibe_question_hint',true);
            $type = get_post_meta(get_the_ID(),'vibe_question_type',true);

            echo '<div class="in_question " data-ques="'.$post->ID.'">';
            echo '<i class="marks">'.(isset($marks[$key])?'<i class="icon-check-5"></i>'.$marks[$key]:'').'</i>';
            echo '<div class="question '.$type.'">';
            the_content();
            if(isset($hint) && strlen($hint)>5){
                echo '<a class="show_hint tip" tip="'.__('SHOW HINT','vibe').'"><span></span></a>';
                echo '<p class="hint"><i>'.__('HINT','vibe').' : '.$hint.'</i></p>';
            }
            echo '</div>';
            switch($type){
                case 'truefalse':
                case 'single':
                case 'multiple':
                case 'sort':
                case 'match':
                    $options = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_question_options',false));

                    if($type == 'truefalse')
                        $options = array( 0 => __('FALSE','vibe'),1 =>__('TRUE','vibe'));

                    if(isset($options) || $options){

                        $answers=get_comments(array(
                            'post_id' => $post->ID,
                            'status' => 'approve',
                            'user_id' => $user_id
                        ));


                        if(isset($answers) && is_array($answers) && count($answers)){
                            $answer = reset($answers);
                            $content = explode(',',$answer->comment_content);
                        }else{
                            $content=array();
                        }

                        echo '<ul class="question_options '.$type.'">';
                        if($type=='single'){
                            foreach($options as $key=>$value){

                                echo '<li>
                            <input type="radio" id="'.$post->post_name.$key.'" class="ques'.$post->ID.'" name="'.$post->ID.'" value="'.($key+1).'" '.(in_array(($key+1),$content)?'checked':'').'/>
                            <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                        </li>';
                            }
                        }else if($type == 'sort'){
                            foreach($options as $key=>$value){
                                echo '<li id="'.($key+1).'" class="ques'.$post->ID.' sort_option">
                              <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                          </li>';
                            }
                        }else if($type == 'match'){
                            foreach($options as $key=>$value){
                                echo '<li id="'.($key+1).'" class="ques'.$post->ID.' match_option">
                              <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                          </li>';
                            }
                        }else if($type == 'truefalse'){
                            foreach($options as $key=>$value){
                                echo '<li>
                            <input type="radio" id="'.$post->post_name.$key.'" class="ques'.$post->ID.'" name="'.$post->ID.'" value="'.$key.'" '.(in_array($key,$content)?'checked':'').'/>
                            <label for="'.$post->post_name.$key.'"><span></span> '.$value.'</label>
                        </li>';
                            }
                        }else{
                            foreach($options as $key=>$value){
                                echo '<li>
                            <input type="checkbox" class="ques'.$post->ID.'" id="'.$post->post_name.$key.'" name="'.$post->ID.$key.'" value="'.($key+1).'" '.(in_array(($key+1),$content)?'checked':'').'/>
                            <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                        </li>';
                            }
                        }
                        echo '</ul>';
                    }
                    break; // End Options
                case 'fillblank':
                    break;
                case 'select':
                    break;
                case 'smalltext':
                    echo '<input type="text" name="'.$k.'" class="ques'.$k.' form_field" value="'.($content?$content:'').'" placeholder="'.__('Type Answer','vibe').'" />';
                    break;
                case 'largetext':
                    echo '<textarea name="'.$k.'" class="ques'.$k.' form_field" placeholder="'.__('Type Answer','vibe').'">'.($content?$content:'').'</textarea>';
                    break;
            }
            echo '</div>';
        }
        $count = count($questions);
        if($posts_per_page < $count){
            echo '<div class="pagination"><label>'.__('PAGES','vibe').'</label>
          <ul>';
            $max =  $count/$posts_per_page;
            if(($count%$posts_per_page)){
                $max++;
            }
            for($i=1;$i<=$max;$i++){
                if($page == $i){
                    echo '<li><span>'.$i.'</span></li>';
                }else{
                    echo '<li><a class="quiz_page">'.$i.'</a><li>';
                }
            }
            echo '</ul>
          </div>';
        }
        echo '<script>var questions_json = '.json_encode($loaded_questions).'</script>';
    }
    wp_reset_postdata();
    die();
}

//Lấy danh sách sinh viên đã học khóa học
function lay_danh_sach_hoc_vien_khoa_hoc($course_id=NULL, $page=0){ // Modified function, counts total number of students
    global $wpdb,$post;
    if(!isset($course_id))
        $course_id=get_the_ID();

    $course_members = array();

    $cquery=$wpdb->prepare("SELECT DISTINCT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s ORDER BY meta_value ASC ",'course_status'.$course_id);

    $course_meta = $wpdb->get_results( $cquery, ARRAY_A);
    foreach($course_meta as $meta){
        if(is_numeric($meta['user_id']))  // META KEY is NUMERIC ONLY FOR USERIDS
            $course_members[] = $meta['user_id'];
    }


    return $course_members;
}

//Thêm thảo luận unit
//Start: khải
//Thêm mục tiêu khóa học
add_action('wp_ajax_create_discussion','create_discussion');

function create_discussion(){
    $kt=$_POST['kt'];
    $user_receiver=$_POST["user_receiver"];
    $noidung=$_POST["cdcontent"];
    $tieude=$_POST["cdtitle"];
    $courseid=$_POST["cdcourseid"];
    $listauthor = wp_get_current_user();
    $author = $listauthor->display_name;
    $mail_author=$listauthor->user_mail;
    $parent_comment = $_POST["parent_comment"];
    $student_id_array = lay_danh_sach_hoc_vien_khoa_hoc($courseid);

    $data = array(
        'comment_post_ID' => $courseid,
        'comment_author' => $author,
        'comment_content' => $noidung,
        'user_id' => get_current_user_id(),
        'comment_author_mail'=>$mail_author,
        'comment_parent' => $parent_comment
    );
    $getid = wp_insert_comment($data);
    update_comment_meta($getid,'review_title',$tieude);
    update_comment_meta($getid,'title_discussion',$tieude);

    if(!empty($student_id_array)){
        foreach($student_id_array as $student_id){
            if($student_id != get_current_user_id()){
                //    Thêm hành động bình luận

                $datacomment = array(
                    'action' => 'Học viên đã bình luận tại khóa học '.get_the_title($courseid),
                    'user_id' => get_current_user_id(), // user thực hiện hành động, user gữi hành động
                    'component' => 'comments_unit',
                    'type' => 'activity_update',
                    'content' => $noidung,
                    'primary_link' => get_permalink($courseid),
                    'item_id' => $courseid, //dùng để lưu id của khóa học
                    'secondary_item_id' => $student_id, //id của user sẽ nhận được hành động hoạt thông báo
                );

                $activity_id = bp_activity_add($datacomment);

//    Thêm thông báo
                $datanotify = array(
                    'user_id' => $student_id,
                    'item_id' => $getid,
                    'secondary_item_id' => get_current_user_id(),
                    'component' => 'comments',
                    'component_name' => 'messages',
                    'is_new' => 1,
                    'component_action' => 'new_message'

                );
                bp_notifications_add_notification($datanotify);

//    Lấy id củ của bảng messages và cộng thêm 1 để tạo thành Thread_id
                global $wpdb;
                $old_id = 0;
                $old_id_message = $wpdb->get_results('SELECT id FROM itclass_songle_bp_messages_messages ORDER BY id DESC LIMIT 1 ');
                foreach($old_id_message as $item){
                    $old_id= $item->id + 1;
                }
//    Thêm dữ liệu vào bảng messages
                $user = get_userdata(get_current_user_id());//$user->user_nicename
                $time_messages = current_time( 'mysql' );
                $messsage_title = $user->user_nicename.' đã bình luận tại khóa học <a href=\"'.get_permalink($courseid).'\"><b>'.get_the_title($courseid).'</b></a>';
                $wpdb->get_results('INSERT INTO itclass_songle_bp_messages_messages (thread_id,sender_id,subject,message,date_sent) VALUES ("'.$old_id.'","'.get_current_user_id().'","'.$messsage_title.'","'.$noidung.'","'.$time_messages.'")');


                $wpdb->get_results('INSERT INTO itclass_songle_bp_messages_recipients (user_id,thread_id,unread_count,sender_only,is_deleted) VALUES ("'.$student_id.'","'.$old_id.'",1,0,0)');

            }
        }
    }




//    echo "<div class='result'>";

    $noidungsau = get_comment_text($getid);

    echo '<div class="result">';
    echo '<div class="item-discustion">';
    echo '<div class="cmtauthor row">';
    echo '<div class="HieuChinh-ds">';
    echo '<div class="Xoads"><i class="icon-x"></i> </div>';
    echo '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
    echo '<input class="id-comment-ds" type="hidden" value="'.$getid.'">';
    echo '</div>';
    echo '<div class="col-md-1">';
    echo get_avatar( get_current_user_id(), 32 ) ;
    echo '</div>';
    echo '<div class="col-md-10">';
    echo '<span class="authorname">'. $author . '</span>'.'<span style="font-style:italic"> vừa xong </span>';                            ;
    echo '</div></div><br> ';
    echo '<div data-id="'.$getid.'" data-course-id="'.$courseid.'" class="NoiDungCMTUser row">';
    echo '<div class="col-md-1"></div>';
    echo '<div class="col-md-10">';
    echo '<div class="comment-title-user">'.get_comment_meta($getid,'title_discussion',true) .' </div>';
    echo '<div class="comment-content-user">'.$noidungsau.'</div>';
    echo '<div class="list-comment be-frist"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-'.$getid.'">Hãy là người đầu tiên trả lời bình luận này</a></li></ul></div>';
    echo '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$getid.'">Ẩn đi</a></li></ul></div>';
    echo '<div class="child_comment">';
    echo '<div class="content_child_comment_start"></div>';
    echo '<div class="content_child_comment"></div> ';
    echo '</div>';
    echo '</div></div>';
    echo '<div class="edit_content_editor"></div>';
    echo '<hr/>';
    echo '</div>';
    die();
}

// tao discussion child
add_action('wp_ajax_create_discussion_child','create_discussion_child');

function create_discussion_child(){
    $kt=$_POST['kt'];
    $user_receiver=$_POST["user_receiver"];
    $noidung=$_POST["cdcontent"];
    $tieude=$_POST["cdtitle"];
    $courseid=$_POST["cdcourseid"];
    $listauthor = wp_get_current_user();
    $author = $listauthor->display_name;
    $mail_author=$listauthor->user_mail;
    $parent_comment = $_POST["parent_comment"];
    $student_id_array = lay_danh_sach_hoc_vien_khoa_hoc($courseid);

    $data = array(
        'comment_post_ID' => $courseid,
        'comment_author' => $author,
        'comment_content' => $noidung,
        'user_id' => get_current_user_id(),
        'comment_author_mail'=>$mail_author,
        'comment_parent' => $parent_comment
    );
    $getid = wp_insert_comment($data);
    update_comment_meta($getid,'review_title',$tieude);
    update_comment_meta($getid,'title_discussion',$tieude);

    //    Lấy id củ của bảng messages và cộng thêm 1 để tạo thành Thread_id
    global $wpdb;
    $old_id = 0;
    $old_id_message = $wpdb->get_results('SELECT id FROM itclass_songle_bp_messages_messages ORDER BY id DESC LIMIT 1 ');
    foreach($old_id_message as $item){
        $old_id= $item->id + 1;
    }
//    Thêm dữ liệu vào bảng messages
    $user = get_userdata(get_current_user_id());//$user->user_nicename
    $time_messages = current_time( 'mysql' );
    $messsage_title = $user->user_nicename.' đã bình luận tại khóa học <a href=\"'.get_permalink($courseid).'\"><b>'.get_the_title($courseid).'</b></a>';
    $wpdb->get_results('INSERT INTO itclass_songle_bp_messages_messages (thread_id,sender_id,subject,message,date_sent) VALUES ("'.$old_id.'","'.get_current_user_id().'","'.$messsage_title.'","'.$noidung.'","'.$time_messages.'")');


    if(!empty($student_id_array)){
        foreach($student_id_array as $student_id){
            if($student_id != get_current_user_id()){
                //    Thêm hành động bình luận

                $datacomment = array(
                    'action' => 'Học viên đã bình luận tại khóa học '.get_the_title($courseid),
                    'user_id' => get_current_user_id(), // user thực hiện hành động, user gữi hành động
                    'component' => 'comments_unit',
                    'type' => 'activity_update',
                    'content' => $noidung,
                    'primary_link' => get_permalink($courseid),
                    'item_id' => $courseid, //dùng để lưu id của khóa học
                    'secondary_item_id' => $student_id, //id của user sẽ nhận được hành động hoạt thông báo
                );

                $activity_id = bp_activity_add($datacomment);

//    Thêm thông báo
                $datanotify = array(
                    'user_id' => $student_id,
                    'item_id' => $getid,
                    'secondary_item_id' => get_current_user_id(),
                    'component' => 'comments',
                    'component_name' => 'messages',
                    'is_new' => 1,
                    'component_action' => 'new_message'

                );
                bp_notifications_add_notification($datanotify);



                $wpdb->get_results('INSERT INTO itclass_songle_bp_messages_recipients (user_id,thread_id,unread_count,sender_only,is_deleted) VALUES ("'.$student_id.'","'.$old_id.'",1,0,0)');

            }
        }
    }

//    echo "<div class='result'>";

    $noidungsau = get_comment_text($getid);

    echo '<div class="result">';
    echo '<li>';
    echo '<div class="item-discustion child">';
    echo '<div class="cmtauthor child row">';
    echo '<div class="HieuChinh-ds child">';
    echo '<div class="Xoads"><i class="icon-x"></i> </div>';
    echo '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
    echo '<input class="id-comment-ds" type="hidden" value="'.$getid.'">';
    echo '</div>';
    echo '<div class="col-md-1">';
    echo get_avatar( get_current_user_id(), 32 ) ;
    echo '</div>';
    echo '<div class="col-md-10">';
    echo '<span class="authorname">'. $author . '</span>'.'<span style="font-style:italic"> vừa xong </span>';                            ;
    echo '</div></div><br> ';
    echo '<div data-id="'.$getid.'" data-course-id="'.$courseid.'" class="NoiDungCMTUser row">';
    echo '<div class="col-md-1"></div>';
    echo '<div class="col-md-10">';
    echo '<div class="comment-title-user">'.get_comment_meta($getid,'title_discussion',true) .' </div>';
    echo '<div class="comment-content-user">'.$noidungsau.'</div>';
    echo '</div></div>';
    echo '<div class="edit_content_editor_child"></div>';

    echo '</div>';
    echo '</li>';
    die();
}

//lấy thảo luận trong bảng notifications
add_action('wpmls_get_notification_for_user','get_notification_for_user');
function get_notification_for_user(){
    global $wpdb;
    $notification = $wpdb->get_results('SELECT DISTINCT n.user_id, m.thread_id, m.subject, m.message, n.secondary_item_id,mr.unread_count FROM itclass_songle_bp_messages_messages m JOIN itclass_songle_bp_notifications n ON m.sender_id=n.secondary_item_id JOIN itclass_songle_bp_messages_recipients mr ON n.user_id = mr.user_id WHERE n.user_id='.get_current_user_id().' GROUP BY n.secondary_item_id,n.user_id, n.item_id, n.component_name, n.component_action, n.date_notified,m.thread_id ORDER BY m.thread_id DESC LIMIT 20');
    echo '<ul>';
    foreach($notification as $notification){
//        $activity = $wpdb->get_results('SELECT * FROM itclass_songle_bp_activity WHERE user_id='.get_current_user_id());
//        foreach($activity as $activity){
        if($notification->unread_count == 1){
            echo '<li style="background-color:#ccc" data-id="'.$notification->thread_id.'">';
        }else{
            echo '<li data-id="'.$notification->thread_id.'">';
        }

//            echo '<a href="'.$activity->primary_link.'">';
        echo  get_avatar( $notification->secondary_item_id, 32 ) ;
        echo '<div class="chitietthongbao">';
        $user = get_userdata($notification->secondary_item_id);
        echo '<span>'.$notification->subject.'</span></br>';
        echo '<span>'.$notification->message.'</span>';
        echo '</div>';
//            echo '</a>';
        echo '</li>';
    }
//    }
    echo '</ul>';
    die();
}

//lấy thảo luận trong bảng notification khi click vào chuông báo khi click thông báo mới
add_action('wp_ajax_get_notification_for_user_use_ajax','get_notification_for_user_use_ajax');
function get_notification_for_user_use_ajax(){
    global $wpdb;
    $notification = $wpdb->get_results('SELECT DISTINCT n.user_id, m.thread_id, m.subject, m.message, n.secondary_item_id,mr.unread_count FROM itclass_songle_bp_messages_messages m JOIN itclass_songle_bp_notifications n ON m.sender_id=n.secondary_item_id JOIN itclass_songle_bp_messages_recipients mr ON n.user_id = mr.user_id WHERE n.user_id='.get_current_user_id().' GROUP BY n.secondary_item_id,n.user_id, n.item_id, n.component_name, n.component_action, n.date_notified,m.thread_id ORDER BY m.thread_id DESC LIMIT 20');
    
    $count = count($notification);
    if($count > 0){
        echo '<ul>';
        foreach($notification as $notification){
//        $activity = $wpdb->get_results('SELECT * FROM itclass_songle_bp_activity WHERE user_id='.get_current_user_id());
//        foreach($activity as $activity){
            if($notification->unread_count == 1){
                echo '<li style="background-color:#ccc" data-id="'.$notification->thread_id.'">';
            }else{
                echo '<li data-id="'.$notification->thread_id.'">';
            }

//            echo '<a href="'.$activity->primary_link.'">';
            echo  get_avatar( $notification->secondary_item_id, 32 ) ;
            echo '<div class="chitietthongbao">';
            $user = get_userdata($notification->secondary_item_id);
            echo '<span>'.$notification->subject.'</span></br>';
            echo '<span>'.$notification->message.'</span>';
            echo '</div>';
//            echo '</a>';
            echo '</li>';
        }
//    }
        echo '</ul>';
    }else{
        echo '<ul>';
            echo '<li>Không có thông báo !</li>';
        echo '</ul>';
    }
    die();
}

//cập nhật trạng thái notification khi click vào chuông thông báo
add_action('wp_ajax_update_status_notification','update_status_notification');
function update_status_notification(){
    $thread_id = $_POST['notify_id'];
    global $wpdb;
    $wpdb->get_results('UPDATE itclass_songle_bp_notifications SET is_new=0 WHERE user_id = '.get_current_user_id().' AND is_new=1');
    $wpdb->get_results('UPDATE itclass_songle_bp_messages_recipients SET unread_count=0 WHERE user_id = '.get_current_user_id().' AND thread_id='.$thread_id.'');
    die();
}

//lấy ra thảo luận mới nhất trong bảng Notification để xem có thảo luận mới hay không
//add_action('wp_ajax_get_new_notification_id','get_new_notification_id');
//function get_new_notification_id(){
//    global $wpdb;
//    $notification = $wpdb->get_results('SELECT * FROM it_bp_notifications WHERE secondary_item_id = '.get_current_user_id().' AND is_new=1 ORDER BY item_id DESC LIMIT 1');
//    $notification_id = null;
//    foreach($notification as $notification){
//        $notification_id = $notification->id;
//    }
//    echo $notification_id;
//    die();
//}
add_action('wp_ajax_get_new_ajax_count_notification_for_user','get_new_ajax_count_notification_for_user');
function get_new_ajax_count_notification_for_user(){
    global $wpdb;
    $notification = $wpdb->get_results('SELECT * FROM itclass_songle_bp_notifications WHERE user_id = '.get_current_user_id().' AND is_new=1 ORDER BY item_id DESC');
    echo count($notification);
    die();
}


//Lấy số thông báo mới của user
add_action('get_new_count_notification_for_user','get_new_count_notification_for_user');
function get_new_count_notification_for_user(){
    global $wpdb;
    $notification = $wpdb->get_results('SELECT * FROM itclass_songle_bp_notifications WHERE user_id = '.get_current_user_id().' AND is_new=1 ORDER BY item_id DESC');
    echo count($notification);
}

add_action('wp_ajax_getdiscusstionforunit','getdiscusstionforunit');
//lấy thảo luận dựa vào post_id
function getdiscusstionforunit(){
    $unit_id =  $_POST['post_id'];
    $args = array(
        'post_id' => $unit_id,
    );

    $comments = get_comments($args);
    foreach($comments as $comment)
    {
        if($comment->comment_parent == 0){
            // lấy nội dung bình luận con
            $args = array(
                'post_id' => $unit_id,
                'parent' => $comment->comment_ID,
                'order' => 'ASC',
            );
            $comments_child = get_comments($args);

            //đếm số comment con
            $args = array(
                'post_id' => $unit_id,
                'parent' => $comment->comment_ID,
                'count' => true
            );
            $number_comments_child = get_comments($args);

            echo '<div class="item-discustion">';
            echo '<div class="cmtauthor row">';
            echo '<div class="col-md-1">';
            echo get_avatar( $comment->user_id, 32 ) ;
            echo '</div>';
            echo '<div class="col-md-10" >';
            echo '<span class="authorname">'. $comment->comment_author . '</span>'.'<span style="font-style:italic"> đã gửi 1 thảo luận cách đây '. human_time_diff( strtotime($comment->comment_date), strtotime(current_time( 'mysql' ))  ).'</span>';                            ;
            echo '<div class="HieuChinh-ds">';
            if($comment->user_id==get_current_user_id())
            {
                echo '<div class="Xoads"><i class="icon-x"></i> </div>';
                echo '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
            }
            echo '<input class="id-comment-ds" type="hidden" value="'.$comment->comment_ID.'">';
            echo '</div>';
            echo '</div></div><br>';
            echo '<div data-id="'.$comment->comment_ID.'" data-course-id="'.$unit_id.'" class="NoiDungCMTUser row">';
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
                echo '<div data-id="'.$value->comment_ID.'" data-course-id="'.$unit_id.'" class="child NoiDungCMTUser row">';
                echo '<div class="col-md-1"></div>';
                echo '<div class="col-md-10">';
                echo '<div class="comment-title-user">'.get_comment_meta($value->comment_ID,'title_discussion',true) .' </div>';
                echo '<div class="comment-content-user">'. $value->comment_content.'</div>';
                echo '</div></div>';
                echo '<div class="edit_content_editor_child"></div>';
                echo '</li>';
            }
            echo '</div>';
            echo '<div class="content_child_comment"></div>';
            echo '</div>';

            echo '</div></div>';

            echo '<div class="edit_content_editor"></div><hr>';
            echo '</div>';
        }

    }
    die();
}


add_action('wp_ajax_update_discussion','update_discussion');
//    add_action('wp_ajax_nopriv_test','test');
function update_discussion(){
    $kt = $_POST['kt'];
    $noidung=$_POST["content"];
    $tieude=$_POST["title"];
    $cmt_id=$_POST["cmt_id"];
    $id_course=$_POST['id_course'];
    $noidung = wp_unslash( $noidung );

    global $wpdb;
    $wpdb->update(
        $wpdb->comments,
        array(
            'comment_content' => $noidung,	// string
        ),
        array( 'comment_ID' => $cmt_id ),
        array(
            '%s',	// value1
        ),
        array( '%d' )
    );
  
    update_comment_meta($cmt_id,'title_discussion',$tieude);
    $noidungsau = get_comment_text($cmt_id);
    $args = array(
        'comment_ID' => $cmt_id,
    );
    $dulieu='<div class="result">';
    $dulieu.='<div class="col-md-1"></div>';
    $dulieu.='<div class="col-md-10">';
    $dulieu.='<div class="comment-title-user">'.$tieude.' </div>';
    $dulieu.='<div class="comment-content-user">'.$noidungsau.'</div>';


    if($kt==0) {
        $args = array(
            'post_id' => $id_course,
            'parent' => $cmt_id,
        );
        $comments_child = get_comments($args);

        $args = array(
            'post_id' => $id_course,
            'parent' => $cmt_id,
            'count' => true
        );
        $number_comments_child = get_comments($args);

        if ($number_comments_child != 0) {
            $dulieu .= '<div class="list-comment"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-' . $cmt_id . '">Hiện ' . $number_comments_child . ' trả lời</a></li></ul></div>';
            $dulieu .= '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-' . $cmt_id . '">Ẩn ' . $number_comments_child . ' trả lời</a></li></ul></div>';
        } else {
            $dulieu .= '<div class="list-comment be-frist"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-' . $cmt_id . '">Hãy là người đầu tiên trả lời bình luận này</a></li></ul></div>';
            $dulieu .= '<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$cmt_id.'">Ẩn đi</a></li></ul></div>';
        }

        $dulieu .= '<div class="child_comment">';
        $dulieu .= '<div class="content_child_comment_start">';
        foreach ($comments_child as $value) {
            $dulieu .= '<li>';
            $dulieu .= '<div class="item-discustion child">';
            $dulieu .= '<div class="cmtauthor child row">';
            $dulieu .= '<div class="HieuChinh-ds child">';
            if ($value->user_id == get_current_user_id()) {
                $dulieu .= '<div class="Xoads"><i class="icon-x"></i> </div>';
                $dulieu .= '<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
            }
            $dulieu .= '<input class="child id-comment-ds" type="hidden" value="' . $value->comment_ID . '">';
            $dulieu .= '</div>';
            $dulieu .= '<div class="col-md-1">';
            $dulieu .= get_avatar($value->user_id, 32);
            $dulieu .= '</div>';
            $dulieu .= '<div class="col-md-10" >';

            $dulieu .= '<span class="authorname">' . $value->comment_author . '</span>' . '<span style="font-style:italic"> đã gửi 1 thảo luận cách đây ' . human_time_diff(strtotime($value->comment_date), strtotime(current_time('mysql'))) . '</span>';;

            $dulieu .= '</div></div><br>';
            $dulieu .= '<div data-id="' . $value->comment_ID . '" data-course-id="' . $id_course . '" class="child NoiDungCMTUser row">';
            $dulieu .= '<div class="col-md-1"></div>';
            $dulieu .= '<div class="col-md-10">';
            $dulieu .= '<div class="comment-title-user">' . get_comment_meta($value->comment_ID, 'title_discussion', true) . ' </div>';
            $dulieu .= '<div class="comment-content-user">' . $value->comment_content . '</div>';
            $dulieu .= '</div></div>';
            $dulieu .= '<div class="edit_content_editor_child"></div>';
            $dulieu .= '</li>';
        }
        $dulieu .= '</div>';
        $dulieu .= '<div class="content_child_comment"></div>';
        $dulieu .= '</div>';
        $dulieu .= '</div>';
    }
    $dulieu.='</div>';
    $dulieu.='</div>';

    echo $dulieu;
    die();
}
//Xóa thảo luận
add_action('wp_ajax_delete_comment','delete_comment');
function delete_comment()
{
    $cmt_id = $_POST["cmt_id"];
    wp_delete_comment($cmt_id,true);
    die();
}

//End: khải
?>
<?php
//Nút continue
if(function_exists('wplms_show_course_student_status'))
    remove_filter('wplms_course_credits','wplms_show_course_student_status'); // Remove this line in 1.8.5
add_filter('wplms_course_credits','wplms_show_new_course_student_status_1',20,2);
function wplms_show_new_course_student_status_1($credits,$course_id){
    if(is_user_logged_in()){
        $user_id=get_current_user_id();
        $check=get_user_meta($user_id,$course_id,true);
        if(isset($check) && $check){
            if($check < time()){
                return '<strong>'.__('HỌC TIẾP','vibe').'</strong>';
            }
            //Khải insert
            $take_course_page_id=vibe_get_option('take_course_page');
            if(function_exists('icl_object_id'))
                $take_course_page_id = icl_object_id($take_course_page_id, 'page', true);
            $take_course_page=get_permalink($take_course_page_id);

            //Khải end
            $check_course= bp_course_get_user_course_status($user_id,$course_id);
            $new_check_course = get_user_meta($user_id,'course_status'.$course_id,true);
            if(isset($new_check_course) && is_numeric($new_check_course) && $new_check_course){
                switch($check_course){
                    //Khải edit
                    case 1:
                        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('START','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
                        break;
                    case 2:
			$credits = '<strong><span>HỌC TIẾP</span></strong>'; 
                        //$credits ='<form class="frm_course_continue" action="'.apply_filters('wplms_take_course_page',$take_course_page,$course_id).'" method="post">'.
                        //    '<input class="btn_course_continue" type="submit" class="'.((isset($course_id) && $course_id )?'':'course_button full ').'button" value="'.__('HỌC TIẾP','vibe').'">'.
                        //    '<input type="hidden" name="course_id" value="'.$course_id.'" />'.wp_nonce_field('continue_course'.$user_id,'continue_course').'

                        // </form>';




                        /*  $credits ='<a href="'.apply_filters('wplms_take_course_page',$take_course_page,$course_id).'"><strong>'.__('HỌC TIẾP','vibe').'</strong></a>';*/
                        break;
                    case 3:
                        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('UNDER','vibe').'<span class="subs">'.__('EVALUATION','vibe').'</span></strong></a>';
                        break;
                    case 4:
                        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('HOÀN THÀNH','vibe').'</strong></a>';
                        break;
                    default:
                        $credits =apply_filters('wplms_course_status_display','<a href="'.get_permalink($course_id).'"><strong>'.__('COURSE','vibe').'<span class="subs">'.__('ENABLED','vibe').'</span></strong></a>',$course_id);
                        break;
                    //Khải end
                }
            }else{
                //Khải edit
                switch($check_course){
                    case 0:
                        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('START','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
                        break;
                    case 1:
                        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('HỌC TIẾP','vibe').'</strong></a>';
                        break;
                    case 2:
                        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('UNDER','vibe').'<span class="subs">'.__('EVALUATION','vibe').'</span></strong></a>';
                        break;
                    default:
                        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('HOÀN THÀNH','vibe').'</strong></a>';
                        break;
                    //Khải end

                }
            }
        }
    }
    return $credits;
}
?>
<?php
//Lưu cấu hình setting unit
add_action('wp_ajax_child_save_unit_settings','child_save_unit_settings');
function child_save_unit_settings(){
    $user_id = get_current_user_id();
    $course_id = $_POST['course_id'];
    $unit_id = $_POST['unit_id'];
    $vibe_type = $_POST['vibe_type'];
    $vibe_free = $_POST['vibe_free'];
    $vibe_duration = $_POST['vibe_duration'];

    if(isset($_POST['vibe_assignment']))
        $vibe_assignment = $_POST['vibe_assignment'];

    if(isset($_POST['vibe_forum']))
        $vibe_forum = $_POST['vibe_forum'];

//    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'save_unit'.$user_id)  || !current_user_can('edit_posts')){
//        _e('Security check Failed. Contact Administrator.','wplms-front-end');
//        die();
//    }

    if((isset($_POST['vibe_forum']) && $_POST['vibe_forum']) && (!is_numeric($course_id) || get_post_type($course_id) != 'course')){
        _e('Invalid Course id, please edit a course','wplms-front-end');
        die();
    }

    if(!is_numeric($unit_id) || get_post_type($unit_id) != 'unit'){
        _e('Invalid Unit id, please edit a course','wplms-front-end');
        die();
    }

    $unit_post = get_post($unit_id,ARRAY_A);
    if($unit_post['post_author'] != $user_id && !current_user_can('manage_options')){
        _e('Invalid Unit Instructor','wplms-front-end');
        die();
    }

    $flag=1;

    echo '<script>alert("$vibe_type + $vibe_free")</script>';
    update_post_meta($unit_id,'vibe_type',$vibe_type);
    update_post_meta($unit_id,'vibe_free',$vibe_free);
    update_post_meta($unit_id,'vibe_duration',$vibe_duration);

    if(isset($vibe_assignment) && $flag)
        if(is_array($vibe_assignment) && isset($vibe_assignment)){
            update_post_meta($unit_id,'vibe_assignment',$vibe_assignment);
        }

    if(isset($vibe_forum) && $flag)
        if(is_numeric($vibe_forum)){
            update_post_meta($unit_id,'vibe_forum',$vibe_forum);
        }


    if($vibe_forum == 'add_group_child_forum' && $flag){

        $group_id = get_post_meta($course_id,'vibe_group',true);
        if(isset($group_id)){
            $forum_id = groups_get_groupmeta( $group_id, 'forum_id');
            if(is_array($forum_id))
                $forum_id=$forum_id[0];

            $forum_settings = array(
                'post_title' => stripslashes( $unit_post['post_title'] ),
                'post_content' => stripslashes( $unit_post['post_excerpt'] ),
                'post_name' => $unit_post['post_name'],
                'post_parent' => $forum_id,
                'post_status' => 'publish',
                'post_type' => 'forum',
                'comment_status' => 'closed'
            );
            $forum_settings=apply_filters('wplms_front_end_forum_vars',$forum_settings);
            if(isset($forum_id) && is_numeric($forum_id))
                $new_forum_id = wp_insert_post($forum_settings);
            if(!update_post_meta($unit_id,'vibe_forum',$new_forum_id))
                $flag=0;
        }
    }

    if($vibe_forum == 'add_new' && $flag){
        $forum_settings = array(
            'post_title' => stripslashes( $unit_post['post_title'] ),
            'post_content' => stripslashes( $unit_post['post_excerpt'] ),
            'post_name' => $unit_post['post_name'],
            'post_status' => 'publish',
            'post_type' => 'forum',
            'comment_status' => 'closed'
        );
        $forum_settings=apply_filters('wplms_front_end_forum_vars',$forum_settings);
        $new_forum_id = wp_insert_post($forum_settings);
        if(!update_post_meta($unit_post->ID,'vibe_forum',$new_forum_id))
            $flag=0;
    }

    if($flag)
        _e('Settings Saved','wplms-front-end');
    else
        _e('Unable to save settings','wplms-front-end');

    die();
}
?>

<?php
// Xử lý file đính kèm khi thêm unit
add_action('wp_ajax_UpdateAttachmentPost','UpdateAttachmentPost');
function UpdateAttachmentPost(){

    $id_post = $_POST['id_post'];
    $id_attachment = $_POST['id_attachment'];
    $value = array('ID' => $id_attachment,'post_parent' => $id_post);
    get_post_field('post_parent',$id_attachment);
    $check = wp_update_post($value);

    echo $check;


}
?>

<?php
//    xử lý đăng nhập ở trang đăng ký
add_action('wp_ajax_dangnhaptrangdangky','dangnhaptrangdangky');
add_action('wp_ajax_nopriv_dangnhaptrangdangky','dangnhaptrangdangky');
function dangnhaptrangdangky(){
    $credit = array();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $credit['user_login'] = $username;
    $credit['user_password'] = $password;
    $credit['remember'] = true;
    $user = wp_signon($credit,false);
    if(!is_wp_error($user)){
        echo home_url().'/danh-sach-khoa-hoc' ;
    }else{
        echo 1;
    }

    die();
}
?>


<?php
//Remove buddupress ở file củ và sử dụng file ở childtheme
remove_action('wp_footer', 'wplms_enqueue_footer');
add_filter('wp_footer', 'child_wplms_enqueue_footer');
function child_wplms_enqueue_footer(){
    wp_dequeue_script('buddypress-js');
    wp_enqueue_script('child-buddypress-js',get_stylesheet_directory_uri().'/buddypress.js',array('jquery'));
}
?>


<?php
// <!--Thêm thanh processbar khi mới vừa load trang mycourse -->
add_action('child_wplms_course_start_after_time','child_wplms_course_progressbar',1,2);
function child_wplms_course_progressbar($course_id,$unit_id){
    $user_id=get_current_user_id();
    $course_progressbar = vibe_get_option('course_progressbar');
    if(!isset($course_progressbar) || !$course_progressbar)
        return;

    $units = bp_course_get_curriculum_units($course_id);

    $total_units = count($units);

    $key = array_search($unit_id,$units);
    $meta=get_user_meta($user_id,$unit_id,true);

    if(isset($meta) && $meta)
        $key++;

    if(!$total_units)$total_units=1;

    $percentage = round(($key/$total_units)*100); // Indexes are less than the count value
    if($percentage > 100)
        $percentage= 100;

    $unit_increase = round((1/$total_units)*100);
    echo "<script>
          jQuery(document).ready(function($){
            $('.course_progressbar').each(function(){
              var cookie_id = 'course_progress'+".$course_id.";
              if($.cookie(cookie_id)!= null){
                $(this).attr('data-value',$.cookie(cookie_id));
                $(this).find('.bar').css('width',$.cookie(cookie_id)+'%');
                $(this).find('.bar span').text($.cookie(cookie_id)+'%');
              }
            });
          });
          </script>";
    echo '<div class="progress course_progressbar" data-increase-unit="'.$unit_increase.'" data-value="'.$percentage.'">
             <div class="bar animate cssanim stretchRight load" style="width: '.$percentage.'%;"><span>'.$percentage.'%</span></div>
           </div>';

}
?>


<?php
//    <!--Xử lý code ajax của mycourse-->
remove_action('wp_ajax_course_filter','course_filter');
remove_action('wp_ajax_nopriv_course_filter','course_filter');

add_action('wp_ajax_course_filter','child_course_filter');
add_action('wp_ajax_nopriv_course_filter','child_course_filter');
function child_course_filter(){
    global $bp;

    $args=array('post_type' => BP_COURSE_CPT);
    if(isset($_POST['filter'])){
        $filter = $_POST['filter'];
        switch($filter){
            case 'popular':
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = 'vibe_students';
                break;
            case 'newest':
                $args['orderby'] = 'date';
                break;
            case 'rated':
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = 'average_rating';
                break;
            case 'alphabetical':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            default:
                $args['orderby'] = '';
                break;
        }
    }

    if(isset($_POST['search_terms']) && $_POST['search_terms'])
        $args['search_terms'] = $_POST['search_terms'];

    if(isset($_POST['page']))
        $args['paged'] = $_POST['page'];

    if(isset($_POST['scope']) && $_POST['scope'] == 'personal'){
        $uid=get_current_user_id();
        $args['meta_query'] = array(
            array(
                'key' => $uid,
                'compare' => 'EXISTS'
            )
        );
    }

    if(isset($_POST['scope']) && $_POST['scope'] == 'instructor'){
        $uid=get_current_user_id();
        $args['instructor'] = $uid;
    }

    if(isset($_POST['extras'])){

        $extras = json_decode(stripslashes($_POST['extras']));
        $course_categories=array();
        $course_levels=array();
        $type=array();
        if(is_array($extras)){
            foreach($extras as $extra){
                switch($extra->type){
                    case 'course-cat':
                        $course_categories[]=$extra->value;
                        break;
                    case 'free':
                        $type=$extra->value;
                        break;
                    case 'level':
                        $course_levels[]=$extra->value;
                        break;
                }
            }
        }
        $args['tax_query']=array();
        if(count($course_categories)){
            $args['tax_query']['relation'] = 'AND';
            $args['tax_query'][]=array(
                'taxonomy' => 'course-cat',
                'terms'    => $course_categories,
                'field'    => 'slug',
            );
        }
        if($type){
            switch($type){
                case 'free':
                    $args['meta_query']['relation'] = 'AND';
                    $args['meta_query'][]=array(
                        'key' => 'vibe_course_free',
                        'value' => 'S',
                        'compare'=>'='
                    );
                    break;
                case 'paid':
                    $args['meta_query']['relation'] = 'AND';
                    $args['meta_query'][]=array(
                        'key' => 'vibe_course_free',
                        'value' => 'H',
                        'compare'=>'='
                    );
                    break;
            }
        }
        if(count($course_levels)){
            $args['tax_query']['relation'] = 'AND';
            $args['tax_query'][]=array(
                'taxonomy' => 'level',
                'field'    => 'slug',
                'terms'    => $course_levels,
            );
        }
    }

    $loop_number=vibe_get_option('loop_number');
    isset($loop_number)?$loop_number:$loop_number=5;

    $args['per_page'] = $loop_number;

    ?>

    <?php do_action( 'bp_before_course_loop' ); ?>

    <?php
    if ( bp_course_has_items( $args ) ) : ?>

        <div id="pag-top" class="pagination ">

            <div class="pag-count" id="course-dir-count-top">

                <?php bp_course_pagination_count(); ?>

            </div>

            <div class="pagination-links" id="course-dir-pag-top">

                <?php bp_course_item_pagination(); ?>

            </div>

        </div>

        <?php do_action( 'bp_before_directory_course_list' );
        $cookie=urldecode($_POST['cookie']);
        if(stripos($cookie,'course_directory=grid')){
            $class='grid';
        }
        ?>
        <ul id="course-list" class="item-list <?php echo $class; ?>" role="main">

            <?php while ( bp_course_has_items() ) : bp_course_the_item(); ?>

                <li>
                    <div class="item-avatar">
                        <?php bp_course_avatar(); ?>

                    </div>

                    <div class="item">
                        <div class="item-title"><?php bp_course_title(); ?></div>
                        <div class="item-meta"><?php bp_course_meta(); ?></div>
                        <div class="item-desc"><?php bp_course_desc(); ?></div>
                        <div class="item-credits">
                            <?php bp_course_credits(); ?>
                        </div>
                        <div class="item-instructor">
                            <?php bp_course_instructor(); ?>
                        </div>
                        <div class="item-action"><?php bp_course_action(); ?></div>
                        <?php if($_POST['scope'] == "personal") : ?>
                            <div class="item_process">
                                <?php
                                $course_id = get_the_ID();
                                $unit_id = wplms_get_course_unfinished_unit($course_id);
                                do_action('child_wplms_course_start_after_time',$course_id,$unit_id);
                                ?>

                            </div>
                        <?php endif ?>
                        <?php do_action( 'bp_directory_course_item' ); ?>

                    </div>

                    <div class="clear"></div>
                </li>

            <?php endwhile; ?>

        </ul>

        <?php do_action( 'bp_after_directory_course_list' ); ?>

        <div id="pag-bottom" class="pagination">

            <div class="pag-count" id="course-dir-count-bottom">

                <?php bp_course_pagination_count(); ?>

            </div>

            <div class="pagination-links" id="course-dir-pag-bottom">

                <?php bp_course_item_pagination(); ?>

            </div>

        </div>

    <?php else: ?>

        <div id="message" class="info">
            <p><?php _e( 'No Courses found.', 'vibe' ); ?></p>
        </div>

    <?php endif;  ?>


    <?php do_action( 'bp_after_course_loop' ); ?>
    <?php

    die();
}

//Kết thúc
?>

<?php
// Xử lý đăng nhập mặc định
add_action('wp_ajax_dangnhapmacdinh','dangnhapmacdinh');
add_action('wp_ajax_nopriv_dangnhapmacdinh','dangnhapmacdinh');
function dangnhapmacdinh(){
    $credit = array();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $credit['user_login'] = $username;
    $credit['user_password'] = $password;
    $credit['remember'] = true;
    $user = wp_signon($credit,false);
    if(!is_wp_error($user)){
        echo 0;
    }else{
        echo 1;
    }

    die();
}
?>



<?php
//    custom plugin (social login) đăng nhập bằng mạng xã hội
remove_action( 'login_form',          'sc_render_login_form_social_connect', 10 );
remove_action( 'register_form',       'sc_render_login_form_social_connect', 10 );
remove_action( 'after_signup_form',   'sc_render_login_form_social_connect', 10 );
remove_action( 'social_connect_form', 'sc_render_login_form_social_connect', 10 );
remove_action( 'comment_post', 'sc_social_connect_add_comment_meta' );
remove_action( 'get_comment_author_link', 'sc_social_connect_render_comment_meta' );
remove_action( 'comment_form_top', 'sc_render_comment_form_social_connect' );
remove_action( 'comment_form_top', 'sc_render_comment_form_social_connect' );

if (!function_exists('child_sc_render_login_form_social_connect')) :

    function child_sc_render_login_form_social_connect( $args = NULL ) {
        $display_label = false;

        if( $args == NULL )
            $display_label = true;
        elseif ( is_array( $args ) )
            extract( $args );

        if( !isset( $images_url ) )
            $images_url = apply_filters('social_connect_images_url', SOCIAL_CONNECT_PLUGIN_URL . '/media/img/');

        $twitter_enabled = get_option( 'social_connect_twitter_enabled' ) && get_option( 'social_connect_twitter_consumer_key' ) && get_option( 'social_connect_twitter_consumer_secret' );
        $facebook_enabled = get_option( 'social_connect_facebook_enabled', 1 ) && get_option( 'social_connect_facebook_api_key' ) && get_option( 'social_connect_facebook_secret_key' );
        $google_plus_enabled = get_option( 'social_connect_google_plus_enabled', 1 );
        $google_enabled = get_option( 'social_connect_google_enabled', 1 );
        $yahoo_enabled = get_option( 'social_connect_yahoo_enabled', 1 );
        $wordpress_enabled = get_option( 'social_connect_wordpress_enabled', 1 );
        ?>

        <?php if ($twitter_enabled || $facebook_enabled || $google_enabled || $google_plus_enabled|| $yahoo_enabled || $wordpress_enabled) : ?>
            <div class="social_connect_ui <?php if( strpos( $_SERVER['REQUEST_URI'], 'wp-signup.php' ) ) echo 'mu_signup'; ?>">
                <p class="comment-form-social-connect">
                    <?php if( $display_label !== false ) : ?>
                        <label><?php _e( 'Đăng nhập với mạng xã hội', 'social_connect' ); ?></label>
                    <?php endif; ?>
                <div class="social_connect_form">
                    <?php do_action ('social_connect_pre_form'); ?>
                    <?php if( $facebook_enabled ) :
                        echo apply_filters('social_connect_login_facebook','<div class="social-btn"><a href="javascript:void(0);" title="Facebook" class="social_connect_login_facebook"><i class="icon-facebook social-icon"></i><span class="btn-text">Đăng nhập bằng Facebook</span></a></div><p></p>');
                    endif; ?>
                    <?php if( $twitter_enabled ) :
                        echo apply_filters('social_connect_login_twitter','<div class="social-btn tiwtter-button"><a href="javascript:void(0);" title="Twitter" class="social_connect_login_twitter"><i class="icon-twitter social-icon"></i><span class="btn-text">Đăng nhập bằng Tiwtter</span></a></div><p></p>');
                    endif; ?>
                    <?php if( $google_plus_enabled ) :
                        echo apply_filters('social_connect_login_google_plus','<div class="social-btn google-plus-button"><a href="javascript:void(0);" title="Google+" class="social_connect_login_google_plus"><i class="icon-google-plus social-icon"></i><span class="btn-text">Đăng nhập bằng Google+</span></a></div><p></p>');
                    endif; ?>
                    <?php if( $google_enabled ) :
                        echo apply_filters('social_connect_login_google','<div class="social-btn google-button"><a href="javascript:void(0);" title="Google" class="social_connect_login_google"><i class="icon-google social-icon"></i><span class="btn-text">Đăng nhập bằng Google</span></a></div><p></p>');
                    endif; ?>
                    <?php if( $yahoo_enabled ) :
                        echo apply_filters('social_connect_login_yahoo','<div class="social-btn yahoo-button"><a href="javascript:void(0);" title="Yahoo" class="social_connect_login_yahoo"><i class="icon-yahoo social-icon"></i><span class="btn-text">Đăng nhập bằng Yahoo</span></a></div><p></p>');
                    endif; ?>
                    <?php if( $wordpress_enabled ) :
                        echo apply_filters('social_connect_login_wordpress','<div class="social-btn wordpress-button"><a href="javascript:void(0);" title="WordPress.com" class="social_connect_login_wordpress"><img alt="WordPress.com" src="'.$images_url.'wordpress_32.png" />Đăng nhập bằng Wordpress</a></div>');
                    endif; ?>
                    <?php do_action ('social_connect_post_form'); ?>
                </div></p>

                <?php
                $social_connect_provider = isset( $_COOKIE['social_connect_current_provider']) ? $_COOKIE['social_connect_current_provider'] : '';

                do_action ('social_connect_auth'); ?>
                <div id="social_connect_facebook_auth">
                    <input type="hidden" name="client_id" value="<?php echo get_option( 'social_connect_facebook_api_key' ); ?>" />
                    <input type="hidden" name="redirect_uri" value="<?php echo home_url('index.php?social-connect=facebook-callback'); ?>" />
                </div>

                <div id="social_connect_twitter_auth"><input type="hidden" name="redirect_uri" value="<?php echo home_url('index.php?social-connect=twitter'); ?>" /></div>
                <div id="social_connect_google_auth"><input type="hidden" name="redirect_uri" value="<?php echo home_url('index.php?social-connect=google'); ?>" /></div>
                <div id="social_connect_google_plus_auth"><input type="hidden" name="redirect_uri" value="<?php echo home_url('index.php?social-connect=google-plus'); ?>" /></div>
                <div id="social_connect_yahoo_auth"><input type="hidden" name="redirect_uri" value="<?php echo home_url('index.php?social-connect=yahoo'); ?>" /></div>
                <div id="social_connect_wordpress_auth"><input type="hidden" name="redirect_uri" value="<?php echo home_url('index.php?social-connect=wordpress'); ?>" /></div>

                <div class="social_connect_wordpress_form" title="WordPress">
                    <p><?php _e( 'Enter your WordPress.com blog URL', 'social_connect' ); ?></p><br />
                    <p>
                        <span>http://</span><input class="wordpress_blog_url" size="15" value=""/><span>.wordpress.com</span> <br /><br />
                        <a href="javascript:void(0);" class="social_connect_wordpress_proceed"><?php _e( 'Proceed', 'social_connect' ); ?></a>
                    </p>
                </div>
            </div> <!-- End of social_connect_ui div -->
        <?php endif;
    }
endif; // function_exist

//add_action( 'login_form',          'child_sc_render_login_form_social_connect', 10 );
//add_action( 'register_form',       'child_sc_render_login_form_social_connect', 10 );
//add_action( 'after_signup_form',   'child_sc_render_login_form_social_connect', 10 );
//add_action( 'social_connect_form', 'child_sc_render_login_form_social_connect', 10 );


function child_sc_social_connect_add_comment_meta( $comment_id ) {
    $social_connect_comment_via_provider = isset( $_POST['social_connect_comment_via_provider']) ? $_POST['social_connect_comment_via_provider'] : '';
    if( $social_connect_comment_via_provider != '' ) {
        update_comment_meta( $comment_id, 'social_connect_comment_via_provider', $social_connect_comment_via_provider );
    }
}
add_action( 'comment_post', 'child_sc_social_connect_add_comment_meta' );


function child_sc_social_connect_render_comment_meta( $link ) {
    global $comment;
    $images_url = SOCIAL_CONNECT_PLUGIN_URL . '/media/img/';
    $social_connect_comment_via_provider = get_comment_meta( $comment->comment_ID, 'social_connect_comment_via_provider', true );
    if( $social_connect_comment_via_provider && current_user_can( 'manage_options' )) {
        return $link . '&nbsp;<img class="social_connect_comment_via_provider" alt="'.$social_connect_comment_via_provider.'" src="' . $images_url . $social_connect_comment_via_provider . '_16.png"  />';
    } else {
        return $link;
    }
}
add_action( 'get_comment_author_link', 'child_sc_social_connect_render_comment_meta' );


function child_sc_render_comment_form_social_connect() {
    if( comments_open() && !is_user_logged_in()) {
        sc_render_login_form_social_connect();
    }
}
add_action( 'comment_form_top', 'child_sc_render_comment_form_social_connect' );


function child_sc_render_login_page_uri(){
    ?>
    <input type="hidden" id="social_connect_login_form_uri" value="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" />
<?php
}
add_action( 'wp_footer', 'child_sc_render_login_page_uri' );
?>

<?php
remove_action('widgets_init', 'vibe_bp_widgets');
add_action( 'widgets_init', 'child_vibe_bp_widgets' );


function child_vibe_bp_widgets() {
    register_widget('child_vibe_bp_login');
    register_widget('child_vibe_course_categories');
    register_widget('child_vibecertificatecode');
}






/* Creates the widget itself */

if ( !class_exists('child_vibe_bp_login') ) {
    class child_vibe_bp_login extends WP_Widget {

        function child_vibe_bp_login() {
            $widget_ops = array( 'classname' => 'vibe-bp-login', 'description' => __( 'Vibe BuddyPress Login', 'vibe' ) );
            $this->WP_Widget( 'vibe_bp_login', __( 'Vibe BuddyPress Login Widget','vibe' ), $widget_ops);
        }

        function widget( $args, $instance ) {
            extract( $args );

            echo $before_widget;

            if ( is_user_logged_in() ) :
                do_action( 'bp_before_sidebar_me' ); ?>
                <div id="sidebar-me">
                    <div id="bpavatar">
                        <a href="<?php echo bp_loggedin_user_domain() . BP_XPROFILE_SLUG ?>/" title="<?php _e('Thông tin cá nhân','vibe'); ?>"><?php bp_loggedin_user_avatar( 'type=full' ); ?></a>
                    </div>
                    <ul style="width: 145px">
                        <li id="username"><a href="<?php bp_loggedin_user_link(); ?>"><?php bp_loggedin_user_fullname(); ?></a></li>
                        <li><a href="<?php echo bp_loggedin_user_domain() . BP_XPROFILE_SLUG ?>/" title="<?php _e('Thông tin cá nhân','vibe'); ?>"><?php _e('Thông tin cá nhân','vibe'); ?></a></li>
                        <li id="vbplogout"><a href="<?php echo wp_logout_url( get_permalink() ); ?>" id="destroy-sessions" rel="nofollow" class="logout" title="<?php _e( 'Đăng xuất','vibe' ); ?>"><?php _e('Đăng xuất','vibe'); ?></a></li>
                        <li id="admin_panel_icon"><?php if (current_user_can("edit_posts"))
                                echo '<a href="'.vibe_site_url() .'wp-admin/" title="'.__('Access admin panel','vibe').'"><i class="icon-settings-1"></i></a>'; ?>
                        </li>
                    </ul>
                    <ul>
                        <?php
                        function child_wplms_get_mycred_link(){
                            $mycred = get_option('mycred_pref_core');

                            if(isset($mycred['buddypress']) && isset($mycred['buddypress']['history_url']) && isset($mycred['buddypress']['history_location']) && $mycred['buddypress']['history_location']){
                                $link=bp_get_loggedin_user_link().$mycred['buddypress']['history_url'];
                            }else{
                                $link='#';
                            }
                            return $link;
                        }

                        $loggedin_menu = array(
//                            'sodu'=>array(
//                                'icon' => 'icon-book-open-1',
//                                'label' => __('Số dư : '.apply_filters('get_point_user',''),'vibe'),
//                                'link' => "#"
//                            ),
                            'taikhoan'=>array(
                                'icon' => 'icon-book-open-1',
                                'label' => __('Tài khoản: '.apply_filters('get_point_user',''),'vibe'),
                                'link' => child_wplms_get_mycred_link(),
                            ),
                            'naptientaokhoan'=>array(
                                'icon' => 'icon-book-open-1',
                                'label' => __('Nạp tiền tài khoản','vibe'),
                                'link' => get_home_url().'/thanh-toan-khoa-hoc/',
                            ),
                            'courses'=>array(
                                'icon' => 'icon-book-open-1',
                                'label' => __('Khóa học của tôi','vibe'),
                                'link' => bp_loggedin_user_domain().BP_COURSE_SLUG
                            ),
//                            'stats'=>array(
//                                'icon' => 'icon-analytics-chart-graph',
//                                'label' => __('Tình trạng','vibe'),
//                                'link' => bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_STATS_SLUG
//                            )
                        );
                        if ( bp_is_active( 'messages' ) ){
                            $loggedin_menu['messages']=array(
                                'icon' => 'icon-letter-mail-1',
                                'label' => __('Hộp thư đến','vibe').(messages_get_unread_count()?' <span>' . messages_get_unread_count() . '</span>':''),
                                'link' => bp_loggedin_user_domain().BP_MESSAGES_SLUG
                            );
                            $n=vbp_current_user_notification_count();
//                            $loggedin_menu['notifications']=array(
//                                'icon' => 'icon-exclamation',
//                                'label' => __('Thông báo','vibe').(($n)?' <span>'.$n.'</span>':''),
//                                'link' => bp_loggedin_user_domain().BP_NOTIFICATIONS_SLUG
//                            );
                        }
                        if ( bp_is_active( 'groups' ) ){
                            $loggedin_menu['groups']=array(
                                'icon' => 'icon-myspace-alt',
                                'label' => __('Nhóm','vibe'),
                                'link' => bp_loggedin_user_domain().BP_GROUPS_SLUG
                            );
                        }

                        $loggedin_menu = apply_filters('wplms_logged_in_top_menu',$loggedin_menu);
                        foreach($loggedin_menu as $item){
                            echo '<li><a href="'.$item['link'].'"><i class="'.$item['icon'].'"></i>'.$item['label'].'</a></li>';
                        }
                        ?>
                    </ul>

                    <?php
                    do_action( 'bp_sidebar_me' ); ?>
                </div>
                <?php do_action( 'bp_after_sidebar_me' );

            /***** If the user is not logged in, show the log form and account creation link *****/

            else :
                if(!isset($user_login))$user_login='';
                do_action( 'bp_before_sidebar_login_form' ); ?>


                <h2 class="box-heading">Đăng nhập ITClass !</h2>

                <form name="login-form" id="vbp-login-form" class="standard-form" action="<?php echo apply_filters('wplms_login_widget_action',vibe_site_url( 'wp-login.php', 'login-post' )); ?>" method="post">
                    <div class="col-md-6" style="padding: 0px 20px 10px">
                        <label><?php _e( 'Đăng nhập với tài khoản ItClass', 'dangnhapitclass' ); ?></label>
                        <label><?php _e( 'Tên đăng nhập', 'vibe' ); ?><br />
                            <input type="text" name="log" id="side-user-login" class="input" tabindex="1" value="<?php echo esc_attr( stripslashes( $user_login ) ); ?>" /></label>

                        <label><?php _e( 'Mật khẩu', 'vibe' ); ?> <a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" tabindex="5" class="tip" title="<?php _e('Forgot Password','vibe'); ?>"><i class="icon-question"></i></a><br />
                            <input type="password" tabindex="2" name="pwd" id="sidebar-user-pass" class="input" value="" /></label>

                        <p class="quenmatkhau"><label><input name="rememberme" tabindex="3" type="checkbox" id="sidebar-rememberme" value="forever" /><?php _e( 'Ghi nhớ', 'vibe' ); ?>   <a href="<?php echo wp_lostpassword_url()?>"> Quên mật khẩu </a></label>

                        </p>

                        <?php do_action( 'bp_sidebar_login_form' ); ?>
                        <!--                        <input type="submit" name="wp-submit" id="sidebar-wp-submit" tabindex="4" value="--><?php //_e( 'Đăng nhập','vibe' ); ?><!--" tabindex="100" /><p></p>-->
                        <span class="error-login">Tài khoản hoặc mật khẩu không đúng !</span>
                        <span class="btn btn-danger" id="id-dangnhap-it"><i style="display: none" class="noidungthongbaoloading icon-refresh glyphicon-refresh-animate"></i><?php _e( 'Đăng nhập','vibe' ); ?></span><br/>
                        <input type="hidden" name="testcookie" value="1" />
                        <?php if ( bp_get_signup_allowed() ) :
                            _e( 'Bạn chưa có tài khoản ? ','vibe' );
                            printf( __( '<a href="%s" class="vbpregister" title="'.__('Create an account','vibe').'" tabindex="5" >'.__( 'Đăng ký','vibe' ).'</a> ', 'vibe' ), site_url( BP_REGISTER_SLUG . '/' ) );
                        endif; ?>
                    </div>
                    <div class="box-separator"></div>
                    <div class="box-right">
                        <?php //do_action( 'login_form' ); //BruteProtect FIX ?>
			<p class="comment-form-social-connect">
                           <label>Đăng nhập với mạng xã hội</label>
                        </p>
			<a href="http://it.myclass.vn/wp-login.php?loginFacebook=1&redirect=http://it.myclass.vn" onclick="window.location = 'http://it.myclass.vn/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;"><div class="social-btn"><i class="icon-facebook social-icon"></i><span class="btn-text">Đăng nhập bằng Facebook</span></div></a>
                    </div>

                </form>


                <?php do_action( 'bp_after_sidebar_login_form' );
            endif;

            echo $after_widget;
        }

        /* Updates the widget */

        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            return $instance;
        }

        /* Creates the widget options form */

        function form( $instance ) {

        }

    }
}




/*======= Vibe Testimonials ======== */

class child_vibe_course_categories extends WP_Widget {


    /** constructor -- name this the same as the class above */
    function child_vibe_course_categories() {
        $widget_ops = array( 'classname' => 'Course Categories', 'description' => __('Course Categories ', 'vibe') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'vibe_course_categories' );
        $this->WP_Widget( 'vibe_course_categories', __('Course Categories', 'vibe'), $widget_ops, $control_ops );
    }


    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
        extract( $args );

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title'] );
        $exclude_names = (isset($instance['exclude_names'])?esc_attr($instance['exclude_names']):'');
        $sort = esc_attr($instance['sort']);
        $order = esc_attr($instance['order']);
        $exclude_ids = esc_attr($instance['exclude_ids']);

        echo $before_widget;

        // Display the widget title
        if ( $title )
            echo $before_title . $title . $after_title;


        $args = array(
            'orderby'    => $order,
            'order' => $sort
        );
        if (isset($exclude_ids))
            $args['exclude'] = $exclude_ids;

        echo '<ul class="'.$order.'">';
        if($order == 'hierarchial'){
            $catlist='title_li=&taxonomy=course-cat';
            if (isset($exclude_ids))
                $catlist.='&exclude='.$exclude_ids;
            wp_list_categories($catlist);
        }else{
            $terms = get_terms( 'course-cat', $args);
            if ( !empty( $terms ) && !is_wp_error( $terms ) ){

                foreach ( $terms as $term ) {
                    echo '<li><a href="' . get_term_link( $term ) . '" title="' . sprintf(__('View all Courses in %s', 'vibe'), $term->name) . '">' . $term->name . '</a></li>';
                }
            }
        }
        echo '</ul>';
        echo $after_widget;

    }

    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['exclude_ids'] = $new_instance['exclude_ids'];
        $instance['sort'] = $new_instance['sort'];
        $instance['order'] = $new_instance['order'];

        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
        $defaults = array(
            'title'  => __('Course Categories','vibe'),
            'exclude_ids'  => '',
            'sort'  => 'DESC',
            'order' => ''
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $title  = esc_attr($instance['title']);
        $exclude_ids = esc_attr($instance['exclude_ids']);
        $sort = esc_attr($instance['sort']);
        $order = esc_attr($instance['order']);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','vibe'); ?></label>
            <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order by','vibe'); ?></label>
            <select class="select" name="<?php echo $this->get_field_name('order'); ?>">
                <option value="name" <?php selected('name',$order); ?>><?php _e('Name','vibe'); ?></option>
                <option value="slug" <?php selected('slug',$order); ?>><?php _e('Slug','vibe'); ?></option>
                <option value="count" <?php selected('count',$order); ?>><?php _e('Course Count','vibe'); ?></option>
                <option value="hierarchial" <?php selected('hierarchial',$order); ?>><?php _e('Hierarchial','vibe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sort'); ?>"><?php _e('Sort Order ','vibe'); ?></label>
            <select class="select" name="<?php echo $this->get_field_name('sort'); ?>">
                <option value="ASC" <?php selected('ASC',$sort); ?>><?php _e('Ascending','vibe'); ?></option>
                <option value="DESC" <?php selected('DESC',$sort); ?>><?php _e('Descending','vibe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('exclude_ids'); ?>"><?php _e('Exclude Course Category Terms Ids (comma saperated):','vibe'); ?></label>
            <input class="regular_text" id="<?php echo $this->get_field_id('exclude_ids'); ?>" name="<?php echo $this->get_field_name('exclude_ids'); ?>" type="text" value="<?php echo $exclude_ids; ?>" />
        </p>

        <?php
        wp_reset_query();
        wp_reset_postdata();
    }
}


/*======= Vibe Gallery ======== */

class child_vibecertificatecode extends WP_Widget {


    /** constructor -- name this the same as the class above */
    function child_vibecertificatecode() {
        $widget_ops = array( 'classname' => 'vibecertificatecode', 'description' => __('Vibe Certificate Code validator', 'vibe') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'vibecertificatecode' );
        $this->WP_Widget( 'vibecertificatecode', __('Vibe Certificate Code validator', 'vibe'), $widget_ops, $control_ops );
    }


    /** @see WP_Widget::widget -- do not rename this */
    function widget( $args, $instance ) {
        extract( $args );

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title'] );


        echo $before_widget;
        echo '<div class="certificate_code_validator">';
        // Display the widget title
        if ( $title )
            echo $before_title . $title . $after_title;
        $certificate_page = vibe_get_option('certificate_page');
        echo '<form action="'.get_permalink($certificate_page).'" method="get">';
        echo '<input type="text" class="form_field" name="code" placeholder="'.__('Enter Certificate Code','vibe').'" />';
        echo '<input type="submit" class="button primary small" value="'.__('Validate','vibe').'" />';
        echo '</form>
			  </div>';
        echo $after_widget;

    }

    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
        $defaults = array(
            'title'  => 'Certificate Code',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title  = esc_attr($instance['title']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','vibe'); ?></label>
            <input class="regular_text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
    <?php
    }

}

/* chinh breadcrumbs khoa hoc */
function vibe_breadcrumbs() {

    global $post;

    /* === OPTIONS === */
    $text['home']     = __('Home','vibe'); // text for the 'Home' link
    $text['category'] = '%s'; // text for a category page
    $text['search']   = '%s'; // text for a search results page
    $text['tag']      = '%s'; // text for a tag page
    $text['author']   = '%s'; // text for an author page
    $text['404']      = 'Error 404'; // text for the 404 page

    $showCurrent = apply_filters('vibe_breadcrumbs_show_title',1); // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = ''; // delimiter between crumbs
    $before      = '<li class="current"><span itemprop="title">'; // tag before the current crumb
    $after       = '</span></li>'; // tag after the current crumb
    /* === END OF OPTIONS === */

    global $post;
    $homeLink = home_url();
    $linkBefore = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
    $linkAfter = '</li>';
    $linkAttr = ' rel="v:url" property="v:title" itemprop="url"';
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s" ><span itemprop="name">%2$s</span></a>' . $linkAfter;

    if (is_home() || is_front_page()) {

        if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';

    } else {

        echo '<ul class="breadcrumbs">' . sprintf($link, $homeLink, $text['home']) . $delimiter;

        if ( is_category() ) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
            }
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif ( is_search() ) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;

        } elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {

            $post_type_var = get_post_type();

            switch($post_type_var){
                case 'post':
                    $cat = get_the_category();
                    if(isset($cat) && is_array($cat))
                        $cat = $cat[0];


                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    if(isset($cats) && !is_object($cats)){
                        if ($showCurrent == 0)
                            $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);

                        $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);

                        $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                        echo $cats;
                    }
                    global $post;
                    if ($showCurrent == 1) echo $before . $post->post_title. $after;
                    break;
                case 'product':
                    $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                    $post_type = get_post_type_object(get_post_type());
                    printf($link, $homeLink . '/' . basename($shop_page_url) . '/', $post_type->labels->singular_name);
                    global $post;
                    if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after;
                    break;
                case 'course':
                    $post_type =  get_post_type_object(get_post_type());

                    $course_category = get_the_term_list12($post->ID, 'course-cat', '', '', '' );

                    $slug = $post_type->rewrite;
                    if(isset($course_category)){

                        $course_category = str_replace('<a', $linkBefore . '<a' . $linkAttr, $course_category);
                        $course_category = str_replace('rel="tag">','rel="tag"><span itemprop="title">',$course_category);
                        $course_category = str_replace('</a>', '</span></a>' . $linkAfter, $course_category);
                        printf($link, $homeLink, __('Course','vibe'));  //$post_type->labels->singular_name
                        echo apply_filters('wplms_breadcrumbs_course_category',$course_category);

                    }
                    global $post;
                    if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after;
                    break;
                case 'forum':
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    if($slug['slug'] == 'forums/forum')
                        $slug['slug'] = 'forums';
                    printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                    global $post;
                    if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after;
                    break;
                default:
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                    global $post;
                    if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after;
                    break;
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());

            echo $before . $post_type->labels->singular_name . $after;

        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            if(isset($cat[0])){
                $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
            }
            printf($link, get_permalink($parent), __('Attachment','vibe'));
            global $post;
            if ($showCurrent == 1) echo $delimiter . $before . $post->post_title . $after;

        } elseif ( is_page() && !$post->post_parent ) {
            global $post;
            if ($showCurrent == 1) echo $before . $post->post_title . $after;

        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                echo $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) echo $delimiter;
            }
            global $post;
            if ($showCurrent == 1) echo $delimiter . $before .  $post->post_title . $after;

        } elseif ( is_tag() ) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif ( is_404() ) {
            echo $before . $text['404'] . $after;
        }

        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo '<li>'.__('Page','vibe') . ' ' . get_query_var('paged').'</li>';
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }

        echo '</ul>';

    }
} // end vibe_breadcrumbs()


/* custom link category khoa hoc trong breadcrums khoa hoc */
function get_the_term_list12( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {
    $terms = get_the_terms( $id, $taxonomy );

    if ( is_wp_error( $terms ) )
        return $terms;

    if ( empty( $terms ) )
        return false;

    $links = array();
    $linktest=array();

    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        $linktest=$term->term_id;
        if ( is_wp_error( $link ) ) {
            return $link;
        }
        $links[] = '<a href="'.add_query_arg( 'khoahoc_tag', $linktest, get_home_url() ).'" rel="tag">' . $term->name . '</a>';
    }

    /**
     * Filter the term links for a given taxonomy.
     *
     * The dynamic portion of the filter name, `$taxonomy`, refers
     * to the taxonomy slug.
     *
     * @since 2.5.0
     *
     * @param array $links An array of term links.
     */
    $term_links = apply_filters( "term_links-$taxonomy", $links );

    return $before . join( $sep, $term_links ) . $after;
}

//add_filter( 'query_vars', 'addnew_query_vars', 10, 1 );
//function addnew_query_vars($vars)
//{
//    $vars[] = 'khoahoc_tag'; // var1 is the name of variable you want to add
//    return $vars;
//}

/* them chuc nang them user vao khoa hoc */

add_action('admin_enqueue_scripts', 'vibe_wplms_child_js_admin');
function vibe_wplms_child_js_admin()
{
    wp_enqueue_script('child-custom-js', get_stylesheet_directory_uri() . '/custom-admin.js', array('jquery'));
}

add_action( 'admin_menu', 'tao_menu_add_user_to_course', 999 );

function tao_menu_add_user_to_course(){
    add_submenu_page( 'lms', 'Add User', 'Add User', 'manage_options', 'lms-add-user', 'add_user_to_course' );
}

function add_user_to_course() {

    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    echo '<h2>Thêm học sinh vào khóa học</h2>';
    echo '</div>';

    echo '<div class="timkiem">Tìm kiếm theo:   ';
    echo '<input type="radio" name="timkiemtheo" class="timtheoten" value="ten" checked>Tên đăng nhập   ';
    echo '<input type="radio" name="timkiemtheo" class="timtheoemail" value="gmail">Gmail';
    echo '<br /><br />';
    echo '<input type="text" name="timkiem" class="giatri">   ';
    echo '<span class="btn btn-primary timkiemhocsinh">Tìm Kiếm</span>';
    echo '</div>';
    echo '<br />';

    echo '<div class="danhsachhocsinh">Danh sách học sinh';
    echo '<br />';
    echo '<table class="wp-list-table widefat fixed striped users">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col" id="username" class="manage-column column-username sortable" style="">Tên Đăng Nhập</th>';
    echo '<th scope="col" id="email" class="manage-column column-email sortable" style="">E-mail</th>';
    echo '<th scope="col" id="posts" class="manage-column column-add-user num" style="">Thêm Học Sinh</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody id="the-list" data-wp-lists="list:user">
		<tr class="no-items"><td class="colspanchange" colspan="9">No users found.</td></tr>	</tbody>';
    echo '<table>';

}

//xử lý tìm kiếm học sinh trong trang admin
add_action('wp_ajax_tim_kiem_hoc_sinh','tim_kiem_hoc_sinh');

function tim_kiem_hoc_sinh(){
    $kt=$_POST['kt'];
    $dulieu=$_POST['dulieu'];

    if($kt==1){
        $user = get_user_by( 'login', $dulieu );
        if(empty($user->user_login)){
            $kq='<tbody id="the-list" data-wp-lists="list:user">
		<tr class="no-items"><td class="colspanchange" colspan="9">No users found.</td></tr>	</tbody>';
        }else{
            $kq='<tr id="user">';
            $kq.='<td class="username column-username">'.$user->user_login.'</td>';
            $kq.='<td class="email column-email">'.$user->user_email.'</td>';
            $kq.='<td class="adduser column-add-user">';
            $kq.='Mã khóa học: <input type="text" class="id_course"><span class="btn btn-primary themuser" data-id-user="'.$user->ID.'">Thêm</span></td>';
            $kq.='</tr>';
        }
        echo $kq;
    }else{
        $user = get_user_by( 'email', $dulieu );
        if(empty($user->user_login)){
            $kq='<tbody id="the-list" data-wp-lists="list:user">
		<tr class="no-items"><td class="colspanchange" colspan="9">No users found.</td></tr>	</tbody>';
        }else{
            $kq='<tr id="user">';
            $kq.='<td class="username column-username">'.$user->user_login.'</td>';
            $kq.='<td class="email column-email">'.$user->user_email.'</td>';
            $kq.='<td class="adduser column-add-user">';
            $kq.='Mã khóa học: <input type="text" class="id_course"><span class="btn btn-primary themuser" data-id-user="'.$user->ID.'">Thêm</span></td>';
            $kq.='</tr>';
        }
        echo $kq;
    }
    die();
}

add_action('wp_ajax_them_hoc_sinh_vao_khoa_hoc','them_hoc_sinh_vao_khoa_hoc');

function them_hoc_sinh_vao_khoa_hoc(){
    $dulieu=$_POST['dulieu'];
    $id=$_POST['id'];
    $course=get_post( $dulieu );
    if($course->post_type=="course"){
        add_user_meta( $id, $dulieu, '1437619528');
	add_user_meta( $id, 'course_status'.$dulieu, '2');
	update_post_meta($dulieu,$id,0);
        $slhocvien = get_post_meta($dulieu,'vibe_students',true);
        $slhocvien = $slhocvien+1;
        update_post_meta($dulieu,'vibe_students',$slhocvien);
        echo 'ok';
    }else{
        echo '1';
    }
}

//xử lý  thêm đánh giá mới
add_action('wp_ajax_themdanhgiakhoahoc','themdanhgiakhoahoc');
function themdanhgiakhoahoc(){
    $id = $_POST['id'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $danhgia = $_POST['danhgia'];

    $time = current_time('mysql');
    global $current_user;
    get_currentuserinfo();

    $data = array(
        'comment_post_ID' => $id,
        'comment_author' => $current_user->user_login,
        'comment_author_email' => $current_user->user_email,
        'comment_date' => $time,
        'comment_date_gmt' => $time,
        'comment_content' => $noidung,
        'comment_approved' => 1,
        'comment_parent' => 0,
        'user_id' => get_current_user_id(),
    );

    $idnew = wp_insert_comment($data);
    if(is_numeric($idnew)){
        add_comment_meta( $idnew, 'review_title', $tieude );
        add_comment_meta( $idnew, 'review_rating', $danhgia );
//        tiến hành cập nhật đánh giá cho khóa học
        $args = array(
            'status' => 'approve',
            'post_id' => $id
        );
        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query( $args );
        if ( $comments ) {
            $ratings = 0;
            $count = 0;
            $rating = array();
            foreach ($comments as $comment) {
                $rate = get_comment_meta($comment->comment_ID, 'review_rating', true);
                if (isset($rate) && $rate != '')
                    $rating[] = $rate;
            }
            $count = count($rating);

            if (!$count) $count = 1;

            $ratings = round((array_sum($rating) / $count), 1);

            update_post_meta($id, 'average_rating', $ratings);
            update_post_meta($id, 'rating_count', $count);
        }
        echo $idnew;
    }else{
        echo 'Đánh giá thất bại !';
    }
    die();

}


//xử lý cập nhật đánh giá khóa học
add_action('wp_ajax_capnhatdanhgiakhoahoc','capnhatdanhgiakhoahoc');
function capnhatdanhgiakhoahoc(){
    $id = $_POST['id'];
    $course_id = $_POST['course_id'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $danhgia = $_POST['danhgia'];

    $commentarr = array();
    $commentarr['comment_ID'] = $id;
    $commentarr['comment_content'] = $noidung;

    $kiemtraupdate = wp_update_comment( $commentarr );
//    if($kiemtraupdate != 0) {
    update_comment_meta($id, 'review_title', $tieude);
    update_comment_meta($id, 'review_rating', $danhgia);
//        tiến hành cập nhật đánh giá của khóa học
    $args = array(
        'status' => 'approve',
        'post_id' => $course_id
    );
    $comments_query = new WP_Comment_Query;
    $comments = $comments_query->query( $args );
    if ( $comments ) {
        $ratings = 0;
        $count = 0;
        $rating = array();
        foreach ($comments as $comment) {
            $rate = get_comment_meta($comment->comment_ID, 'review_rating', true);
            if (isset($rate) && $rate != '')
                $rating[] = $rate;
        }
        $count = count($rating);

        if (!$count) $count = 1;

        $ratings = round((array_sum($rating) / $count), 1);

        update_post_meta($course_id, 'average_rating', $ratings);
        update_post_meta($course_id, 'rating_count', $count);
    }
    echo '1';
//    }else{
//        echo 'Cập nhật thất bại';
//    }
    die();

}

//xử lý xóa đánh giá khóa học
add_action('wp_ajax_xoadanhgiakhoahoc','xoadanhgiakhoahoc');

function xoadanhgiakhoahoc(){
    $id = $_POST['id'];
    $course_id = $_POST['course_id'];
    $kq=wp_delete_comment($id);
    if($kq==true){
        $args = array(
            'status' => 'approve',
            'post_id' => $course_id
        );
        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query( $args );
        if ( $comments ) {
            $ratings = 0;
            $count = 0;
            $rating = array();
            foreach ($comments as $comment) {
                $rate = get_comment_meta($comment->comment_ID, 'review_rating', true);
                if (isset($rate) && $rate != '')
                    $rating[] = $rate;
            }
            $count = count($rating);

            if (!$count) $count = 1;

            $ratings = round((array_sum($rating) / $count), 1);

            update_post_meta($course_id, 'average_rating', $ratings);
            update_post_meta($course_id, 'rating_count', $count);
        }
        echo '1';
    }else{
        echo 'xóa đánh giá thất bại';
    }
    die();
}

// xử lý load danh sách đánh giá khóa học
add_action('wp_ajax_load_danh_sach_danh_gia_khoa_hoc','load_danh_sach_danh_gia_khoa_hoc');

function load_danh_sach_danh_gia_khoa_hoc(){
    $id = $_POST['id'];
    $danhsachtong=array(
        'post_id' => $id,
        'meta_key' => 'review_rating',
    );
    $danhgiatong = get_comments($danhsachtong);

    $number = 3;
    $danhsach=array(
        'post_id' => $id,
        'meta_key' => 'review_rating',
        'number' => $number,
        'offset' => 0
    );
    $dulieu="<div class='noidungdanhsachdanhgia'>";
    $danhgia = get_comments($danhsach);
    foreach($danhgia as $dg){
        $sosaodanhgia=get_comment_meta($dg->comment_id,'review_rating',true);
        $dulieu.="<li class='comment byuser comment-author-itclass even thread-even depth-1' id='comment-".$dg->comment_id."'>";
        $dulieu.="<div id='div-comment-".$dg->comment_id."' class='comment-body'>";
        $dulieu.="<div id='dinhdangimg' class='comment-author vcard'>";
        $dulieu.=get_avatar($dg->user_id)."<br />";
        $dulieu.="<cite class='fn'><span>".bp_core_get_userlink($dg->user_id)."</span></cite>";
        $dulieu.="</div>";
        $dulieu.="<p>";
        $dulieu.="<strong>".get_comment_meta($dg->comment_id,'review_title',true)."</strong>";
        $dulieu.="<br />".$dg->comment_content;
        $dulieu.="</p>";
        $dulieu.="<div class='omment-rating star-rating'>";
        for($i=1;$i<=5;$i++){
            if($i<=$sosaodanhgia){
                $dulieu.="<span class='fill'></span>";
            }else{
                $dulieu.="<span></span>";
            }
        }
        $dulieu.="-Cách đây ".human_time_diff( strtotime($dg->comment_date), strtotime(current_time( 'mysql' ))  );
        $dulieu.="</div>";

        $dulieu.="</div>";
        $dulieu.="</li>";
    }
    $dulieu.="</div>";
    if(count($danhgia)<count($danhgiatong)){
        $dulieu.="<span class='btn btn-primary xemthemdanhgia' data-page='1' data-course='".$id."' data-number='".$number."' data-total='".count($danhgiatong)."'>XÊM THÊM</span>";
        $dulieu.="<p class='loaddingdanhgia anpopupthongtinkhoahoc'><i class='icon-refresh glyphicon-refresh-animate'></i>Đang tải...</p>";
    }
    echo $dulieu;
    die();

}

//xử lý load xem thêm đánh giá khóa học
add_action('wp_ajax_xem_them_danh_gia_khoa_hoc','xem_them_danh_gia_khoa_hoc');

function xem_them_danh_gia_khoa_hoc(){
    $page = $_POST['page'];
    $id = $_POST['id'];
    $number = $_POST['number'];

    $danhsach=array(
        'post_id' => $id,
        'meta_key' => 'review_rating',
        'number' => $number,
        'offset' => $number*$page
    );
    $dulieu="";
    $danhgia = get_comments($danhsach);
    foreach($danhgia as $dg){
        $sosaodanhgia=get_comment_meta($dg->comment_id,'review_rating',true);
        $dulieu.="<li class='comment byuser comment-author-itclass even thread-even depth-1' id='comment-".$dg->comment_id."'>";
        $dulieu.="<div id='div-comment-".$dg->comment_id."' class='comment-body'>";
        $dulieu.="<div id='dinhdangimg' class='comment-author vcard'>";
        $dulieu.=get_avatar($dg->user_id)."<br />";
        $dulieu.="<cite class='fn'><span>".$dg->comment_author."</span></cite>";
        $dulieu.="</div>";
        $dulieu.="<p>";
        $dulieu.="<strong>".get_comment_meta($dg->comment_id,'review_title',true)."</strong>";
        $dulieu.="<br />".$dg->comment_content;
        $dulieu.="</p>";
        $dulieu.="<div class='omment-rating star-rating'>";
        for($i=1;$i<=5;$i++){
            if($i<=$sosaodanhgia){
                $dulieu.="<span class='fill'></span>";
            }else{
                $dulieu.="<span></span>";
            }
        }
        $dulieu.="-Cách đây ".human_time_diff( strtotime($dg->comment_date), strtotime(current_time( 'mysql' ))  );
        $dulieu.="</div>";

        $dulieu.="</div>";
        $dulieu.="</li>";
    }

    echo $dulieu;
    die();
}

// //xóa ASSIGNMENT
add_action('wp_ajax_xoa_assigment','xoa_assigment');
function xoa_assigment(){
    $comment_id = $_POST['comment_id'];
    $attachmentIds = get_comment_meta($comment_id, 'attachmentId', TRUE);
    foreach($attachmentIds as $attachmentId){
        if(is_numeric($attachmentId) && !empty($attachmentId)){
            wp_delete_attachment($attachmentId);
        }
    }
    wp_delete_comment($comment_id,true);

    die();

}

add_action('wp_ajax_timkiembinhluan','timkiembinhluan');
function timkiembinhluan(){
    global $wpdb;
    $noidungtimkiem = $_POST['tieude'];
    $id = $_POST['id'];
    $course_curriculum=vibe_sanitize(get_post_meta($id,'vibe_course_curriculum',false));
    $unit_id = wplms_get_course_unfinished_unit($id);

    $unit_comments = vibe_get_option('unit_comments');
    $units=array();
    if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
        foreach($course_curriculum as $key=>$curriculum){
            if(is_numeric($curriculum)){
                $units[]=$curriculum;
            }
        }
    }
    $bien='';
    for($i=0;$i<count($units);$i++){
        if($i==count($units)-1){
            $bien.=$wpdb->comments.".comment_post_ID=".$units[$i];
        }else{
            $bien.=$wpdb->comments.".comment_post_ID=".$units[$i]." OR ";
        }

    }
    $query = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->commentmeta.".meta_key = 'title_discussion' AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$noidungtimkiem."%' OR ".$wpdb->comments.".comment_content like '%".$noidungtimkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC LIMIT 0,10";
//    $query = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$noidungtimkiem."%' OR ".$wpdb->comments.".comment_content like '%".$noidungtimkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id LIMIT 0,10";
//    $querytong = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$noidungtimkiem."%' OR ".$wpdb->comments.".comment_content like '%".$noidungtimkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id";
    $querytong = "select * from ".$wpdb->comments." , ".$wpdb->commentmeta." where ".$wpdb->comments.".comment_ID = ".$wpdb->commentmeta.".comment_id AND ".$wpdb->commentmeta.".meta_key = 'title_discussion' AND ".$wpdb->comments.".comment_parent=0 AND (".$wpdb->commentmeta.".meta_value like '%".$noidungtimkiem."%' OR ".$wpdb->comments.".comment_content like '%".$noidungtimkiem."%') AND ( ".$wpdb->comments.".comment_post_id=".$id." OR ".$bien.") GROUP BY ".$wpdb->comments.".comment_id Order by ".$wpdb->comments.".comment_date DESC";
    $result = $wpdb->get_results($query);
    $resulttong = $wpdb->get_results($querytong);
    $dulieu='';
    $dulieu.='<div class="append-content-discussion" >';
    foreach($result as $value){
        $checkCommentMeta = get_comment_meta($value->comment_ID,'review_rating',true);
        if(empty($checkCommentMeta)){
            $args = array(
                'post_id' => $value->comment_post_ID,
                'parent' => $value->comment_ID,
                'order' => 'ASC',
            );
            $comments_child = get_comments($args);

            //đếm số comment con
            $args = array(
                'post_id' => $value->comment_post_ID,
                'parent' => $value->comment_ID,
                'count' => true
            );
            $number_comments_child = get_comments($args);

            $dulieu.='<div class="item-discustion">';
            $dulieu.='<div class="cmtauthor row">';
            $dulieu.='<div class="HieuChinh-ds">';
            if($value->user_id==get_current_user_id())
            {
                $dulieu.='<div class="Xoads"><i class="icon-x"></i> </div>';
                $dulieu.='<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
            }
            $dulieu.='<input class="id-comment-ds" type="hidden" value="'.$value->comment_ID.'">';
            $dulieu.='</div>';
            $dulieu.='<div class="col-md-1">';
            $dulieu.=get_avatar( $value->user_id, 32 ) ;
            $dulieu.='</div>';
            $dulieu.='<div class="col-md-10" >';

            foreach($units as $unit_id_comment){
                if($value->comment_post_ID == $unit_id_comment){
                    $thaoluantaiunit = $value->comment_post_ID;
                }
            }

            if($thaoluantaiunit != 0){
                $dulieu.='<span class="authorname">'. $value->comment_author . '</span>'.'<span style="font-style:italic"> đã gửi 1 thảo luận tại bài <span class="unit_line"> <a class="unit" data-unit="'.$thaoluantaiunit.'" ><b>'.get_the_title($thaoluantaiunit).'</b></a> </span> cách đây '. human_time_diff( strtotime($value->comment_date), strtotime(current_time( 'mysql' ))  ).'</span>';                            ;
            }else{
                $dulieu.='<span class="authorname">'. $value->comment_author . '</span>'.'<span style="font-style:italic"> đã gửi 1 thảo luận cách đây '. human_time_diff( strtotime($value->comment_date), strtotime(current_time( 'mysql' ))  ).'</span>';                            ;

            }


            $dulieu.='</div></div><br>';
            if($thaoluantaiunit != 0){
                $dulieu.= '<div data-id="'.$value->comment_ID.'" data-course-id="'.$thaoluantaiunit.'" class="NoiDungCMTUser row">';
            }else{
                $dulieu.='<div data-id="'.$value->comment_ID.'" data-course-id="'.$id.'" class="NoiDungCMTUser row">';
            }
//            $dulieu.='<div data-id="'.$value->comment_ID.'" data-course-id="'.$id.'" class="NoiDungCMTUser row">';
            $dulieu.='<div class="col-md-1"></div>';
            $dulieu.='<div class="col-md-10">';
            $dulieu.='<div class="comment-title-user">'.get_comment_meta($value->comment_ID,'title_discussion',true) .' </div>';
            $dulieu.='<div class="comment-content-user">'. $value->comment_content.'</div>';

            if($number_comments_child !=0){
                $dulieu.='<div class="list-comment"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-'.$value->comment_ID.'">Hiện '.$number_comments_child.' trả lời</a></li></ul></div>';
                $dulieu.='<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$value->comment_ID.'">Ẩn '.$number_comments_child.' trả lời</a></li></ul></div>';
            }else{
                $dulieu.='<div class="list-comment be-frist"><ul><li><a class="rely_comment" data-commnent-id="comment-child-editor-'.$value->comment_ID.'">Hãy là người đầu tiên trả lời bình luận này</a></li></ul></div>';
                $dulieu.='<div class="hide-list-comment"><ul><li><a class="hide_comment" data-commnent-id="comment-child-editor-'.$value->comment_ID.'">Ẩn đi</a></li></ul></div>';
            }

//                            echo '<div class="content_child_comment">
//
//                                    </div>';

            $dulieu.='<div class="child_comment">';
            $dulieu.='<div class="content_child_comment_start">';
            foreach($comments_child as $value1){
                $dulieu.='<li>';
                $dulieu.='<div class="item-discustion child">';
                $dulieu.='<div class="cmtauthor child row">';
                $dulieu.='<div class="HieuChinh-ds child">';
                if($value1->user_id==get_current_user_id())
                {
                    $dulieu.='<div class="Xoads"><i class="icon-x"></i> </div>';
                    $dulieu.='<div class="Suads"><i class="icon-edit-pen-1"></i> </div>';
                }
                $dulieu.='<input class="child id-comment-ds" type="hidden" value="'.$value1->comment_ID.'">';
                $dulieu.='</div>';
                $dulieu.='<div class="col-md-1">';
                $dulieu.=get_avatar( $value1->user_id, 32 ) ;
                $dulieu.='</div>';
                $dulieu.='<div class="col-md-10" >';

                $dulieu.='<span class="authorname">'. $value1->comment_author . '</span>'.'<span style="font-style:italic"> đã gửi 1 thảo luận cách đây '. human_time_diff( strtotime($value1->comment_date), strtotime(current_time( 'mysql' ))  ).'</span>';                            ;

                $dulieu.='</div></div><br>';
                $dulieu.='<div data-id="'.$value1->comment_ID.'" data-course-id="'.$thaoluantaiunit.'" class="child NoiDungCMTUser row">';
                $dulieu.='<div class="col-md-1"></div>';
                $dulieu.='<div class="col-md-10">';
                $dulieu.='<div class="comment-title-user">'.get_comment_meta($value1->comment_ID,'title_discussion',true) .' </div>';
                $dulieu.='<div class="comment-content-user">'. $value1->comment_content.'</div>';
                $dulieu.='</div></div>';
                $dulieu.='<div class="edit_content_editor_child"></div>';
                $dulieu.='</li>';
            }
            $dulieu.='</div>';
            $dulieu.='<div class="content_child_comment"></div>';
            $dulieu.='</div>';

            $dulieu.= '</div></div>';

            $dulieu.= '<div class="edit_content_editor "></div><hr>';
            $dulieu.= '</div>';
        }
    }
    $tong = count($resulttong);

    if($tong>10){
        $dulieu.='<div data-page="10" data-course-id="'.$id.'" class="xemthembinhluan"><span class="btn btn-primary"><i style="display: none" class="noidungthongbaoloading icon-refresh glyphicon-refresh-animate"></i> Xem thêm...</span></div>';
    }
    echo $dulieu;
    die();
}

//load khoa hoc chua done len dau trang course-status
add_action('wp_ajax_load_phia_tren_khoa_hoc','load_phia_tren_khoa_hoc');
function load_phia_tren_khoa_hoc(){
    $id = $_POST['id'];
    $unit_id = wplms_get_course_unfinished_unit_sau($id);

    $dulieu="";
    $dulieu.='<i class="icon-play"></i>';
    if(empty($unit_id)){
        $dulieu.='<span class="unithientai kiemtra" data-id="'.$unit_id.'">Course Status</span>';
    }else{
        $dulieu.='<span class="unithientai kiemtra" data-id="'.$unit_id.'">'.get_the_title($unit_id).'</span>';
    }

    $dulieu.='<div class="hms">';
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
                $dulieu.='<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Minutes','vibe'):'').' '.$minutes.__(' seconds','vibe').'</span>';
            else if($unit_duration_parameter == 60)
                $dulieu.='<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.__(' minutes','vibe').'</span>';
            else if($unit_duration_parameter == 3600)
                $dulieu.='<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Days','vibe'):'').' '.$minutes.__(' hours','vibe').'</span>';
        }

    }

    $dulieu.='</div>';
    echo $dulieu;
    die();
}

//lay tong thoi gian khoa hoc
function tongthoigianvideokhoahoc($course_id){
    $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
    $thoigiantong=0;
    if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
        foreach($course_curriculum as $key=>$curriculum){
            if(is_numeric($curriculum)){
                $thoigian = $mins = get_post_meta($curriculum,'vibe_duration',true);
                $thoigiantong+=$thoigian;
            }
        }
    }

    if($thoigiantong>60){
        $gio=floor($thoigiantong/60);
        $phut=$thoigiantong-$gio*60;
        if($gio<10){
            $gio = '0'.$gio;
        }
        if($phut<10){
            $phut = '0'.$phut;
        }
        if($phut>0){
            $giatri = $gio." giờ ".$phut. " phút";
            return $giatri;
        }else{
            $giatri = $gio." giờ";
            return $giatri;
        }
    }else{
        if($thoigiantong<10){
            $thoigiantong = '0'.$thoigiantong;
        }
        $giatri = $thoigiantong. " phút";
        return $giatri;
    }

}

add_action('init', 'removeTabs');
function removeTabs() {
    global $bp;
    if(isset($bp->bp_nav['activity'])) {
        unset($bp->bp_nav['activity']);
    }
    if(isset($bp->bp_nav['notifications'])) {
        unset($bp->bp_nav['notifications']);
    }

    // var_dump(bp_displayed_user_id()."--".bp_loggedin_user_id());

    if(bp_displayed_user_id() != bp_loggedin_user_id()){
        if(isset($bp->bp_nav['settings'])) {
            unset($bp->bp_nav['settings']);
        }
        if(isset($bp->bp_nav['mycred-history'])) {
            unset($bp->bp_nav['mycred-history']);
        }

        if(isset($bp->bp_nav['profile'])) {
            unset($bp->bp_nav['profile']);
        }

        // $bp->bp_nav['profile']['name'] = 'Khóa học của';

    }


    bp_core_remove_subnav_item( 'settings', 'delete-account' );
}

//an media library va an hinh anh cua user khac
add_filter( 'posts_where', 'hide_attachments_wpquery_where' );
function hide_attachments_wpquery_where( $where ){
    global $current_user;
    if( !current_user_can( 'manage_options' ) ) {
        if( is_user_logged_in() ){
            if( isset( $_POST['action'] ) ){
                // library query
                if( $_POST['action'] == 'query-attachments' ){
                    $where .= ' AND post_author='.$current_user->data->ID;
                }
            }
        }
    }

    return $where;
}

add_filter( 'media_view_strings', 'custom_media_uploader' );
function custom_media_uploader( $strings ) {
    unset( $strings['mediaLibraryTitle'] ); //Media Library
    return $strings;
}
//end

function wplms_get_course_unfinished_unit_sau($course_id){
    $user_id = get_current_user_id();

    if(isset($_COOKIE['course'])){
        $coursetaken=1;
    }else{
        $coursetaken=get_user_meta($user_id,$course_id,true);
    }

    $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));

    $key=0;
    $uid='';
    if(isset($coursetaken) && $coursetaken){
        if(isset($course_curriculum) && is_array($course_curriculum) && count($course_curriculum)){
            foreach($course_curriculum as $uid){
                if(is_numeric($uid)){
                    $units[$key]=$uid;
                    $unittaken=get_user_meta($user_id,$uid,true);
                    if(!isset($unittaken) || !$unittaken){
                        break;
                    }
                    $key++;
                }
            }
        }else{
            echo '<div class="error"><p>'.__('Course Curriculum Not Set','vibe').'</p></div>';
            return;
        }
    }

    $flag=apply_filters('wplms_next_unit_access',true,$units[($key-1)]);
    $drip_enable=get_post_meta($course_id,'vibe_course_drip',true);
    $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);

    if(vibe_validate($drip_enable)){
        $drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);

        if($key > 0){
            $pre_unit_time=get_post_meta($units[($key-1)],$user_id,true);

            if(isset($pre_unit_time) && $pre_unit_time){
                $value = $pre_unit_time + ($key)*$drip_duration*$drip_duration_parameter;
                $value = apply_filters('wplms_drip_value',$value,$units[($key-1)],$course_id,$units[$unitkey]);
                if( $value > time()){
                    $flag=0;
                }
            }else{
                $flag=0;
            }

        }
    }

    if(isset($uid) && $flag && $key){// Should Always be set
        $unit_id=$uid; // Last un finished unit
    }else{
        if(isset($key) && $key > 0){
            $unit_id=$units[($key-1)] ;
        }else{
            $unit_id = $units[0] ;
        }
    }

    return $unit_id;
}








