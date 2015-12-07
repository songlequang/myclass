<?php
/*
Template Name: JSON Course API
*/

$show_post = $_GET['show_post'];
$comment_count = $_GET['comment_count'];
if(isset($_GET['comment_count'])){
    $arg = "";
    $arg = array(
        'post_type'=>'course',
        'post_status' =>'publish',
        'showposts'=> 4,
	'meta_key'=> 'vibe_students',
        'orderby'=>'meta_value_num',
        'order'=>"DESC"
    );
}else{
    $arg = "";
    $arg = array(
        'post_type'=>'course',
        'post_status' =>'publish',
        'showposts'=> $show_post,
        'orderby'=>'id',
        'order'=>"DESC"
    );
}


$wp_query = new WP_Query($arg);
$arrayJSON = array();
$dem = 1;
echo '{ "content":[';
while($wp_query->have_posts()) : $wp_query->the_post();
    $course_id = get_the_ID();
    $course_avatar = wp_get_attachment_url( get_post_thumbnail_id($course_id) );
    $course_title = get_the_title();

    $course_curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
    $dem1 = 0;
    if(isset($course_curriculum)){
        foreach($course_curriculum as $item){
            if(isset($item)){
                $dem1++;
            }
        }
    }

    //lấy số lượng học viên
    $amount_student = get_post_meta($course_id,"vibe_students",true);
    $instructor_id = get_the_author_meta( 'ID' );

    //lấy tên giảng viên
    $instructor_name = bp_core_get_user_displayname($instructor_id);

    //lấy giá khóa học
    $price_course = get_post_meta($course_id,"vibe_mycred_points",true).".000 VNĐ";

    //lấy chức vụ giảng viên
    $field = vibe_get_option('instructor_field');
    $special = bp_get_profile_field_data('field='.$field.'&user_id='.$instructor_id);

    //lấy đánh giá
    $reviews=get_post_meta(get_the_ID(),'average_rating',true);
    $count=get_post_meta(get_the_ID(),'rating_count',true);

    //lấy hình giảng viên
    $avatar_instructor = get_avatar($instructor_id);
    $regex = '/(^.*src="|" w.*$)/';
    $avatar_instructor = preg_replace($regex, '', $avatar_instructor);
    $avatar_link = bp_core_get_user_domain($instructor_id);

    //lấy link khóa học
    $link_course = get_permalink($course_id);

    //lấy số lượng video khóa học
    $total_time = tongthoigianvideokhoahoc($course_id);

    $content = array("title"=>$course_title,"img" => $course_avatar,
        "amount_students"=>$amount_student , "name_instructor"=>$instructor_name, "price_course"=>$price_course,
        "special"=>$special, "reviews"=>$reviews , "reviews_count"=>$count,"avatar"=>$avatar_instructor,
        "link_course"=>$link_course, "total_time"=>"$total_time", "total_video"=>"$dem1", "link_user"=>$avatar_link);
    $JSon = json_encode($content);

    if($wp_query->post_count == $dem){
        echo $JSon;
    }else{
        echo $JSon.',';
    }
    $dem++;


endwhile;
echo ']}';

wp_reset_postdata();


?>

