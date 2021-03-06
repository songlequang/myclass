<?php
/*
FILE : class.init.php
DESC : Initilize MyCred Add on and hooks
*/

if ( !defined( 'ABSPATH' ) ) exit;

class wplms_points_init {

    public $version;
    public $subscription_duration_parameter = 86400;

    function __construct(){

        //add_filter( 'mycred_label', 'mycred_pro_relable_mycred' );
        add_filter('mycred_setup_addons',array($this,'wplms_mycred_setup_addons'));
        add_action('init',array($this,'wplms_mycred_custom_metabox'));
        add_filter('wplms_course_credits_array',array($this,'wplms_course_credits_array'),10,2);
        add_action('wplms_header_top_login',array($this,'wplms_mycred_show_points'));
        //
        add_filter('get_point_user',array($this,'get_point_user'));
        add_filter('get_point_course',array($this,'get_point_course'));

        add_filter('wplms_course_product_id',array($this,'wplms_mycred_take_this_course_label'));
        add_action('wplms_course_before_front_main',array($this,'wplms_error_message_handle'));
        add_action('wp_ajax_use_mycred_points',array($this,'use_mycred_points'));
        add_action('wp_print_styles',array($this,'add_styles'));
        add_action('wplms_front_end_pricing_content',array($this,'wplms_front_end_pricing'),10,1);
    }

    function mycred_pro_relable_mycred() {
        return __('Points','wplms-mycred');
    }
    function add_styles(){
        wp_enqueue_style('wplms_mycred',VIBE_PLUGIN_URL.'/wplms-mycred-addon-master/assets/wplms-mycred-addon.css',true);
        wp_enqueue_script('wplms_mycred',VIBE_PLUGIN_URL.'/wplms-mycred-addon-master/assets/wplms-mycred-addon.js',true);
    }
    function wplms_mycred_take_this_course_label($x){
        global $post;
        $points_required = get_post_meta($post->ID,'vibe_mycred_points',true);
        if(isset($points_required) && is_numeric($points_required)){
            $user_id = get_current_user_id();
            $mycred = mycred();
            $balance = $mycred->get_users_cred( $user_id );
            if($points_required <= $balance){
                echo '<script>jQuery(document).ready(function($){
					$(".course_button").addClass("hasmycredpoints");
					$(".hasmycredpoints").click(function(event){
					    event.stopPropagation();
						event.preventDefault();
						$(".chemanhinh").css("display","block");
                        $("#ajaxloader").removeClass("disabled");
						$(this).addClass("loader");
						$.ajax({
		                    type: "POST",
		                    url: ajaxurl,
		                    data: { action: "use_mycred_points", 
		                            security: $("#security").val(),
		                            id: '.$post->ID.'
		                          },
		                    cache: false,
		                    success: function (html) {
		                    	console.log(html);
		                        $(this).removeClass("loader");
		                        $(this).html(html);
		                        setTimeout(function(){window.location.assign(document.URL);}, 2000);
		                    }
		            });
						return document.getElementById("vaotranghockhoahoc").submit();
					});
				});</script>
				'.wp_nonce_field('security'.$user_id,'security').'
				';
                return '#';
            }else{
                if(is_numeric($x)){
                    return $x;
                }else{
                    return '?error=insufficient';
                }
            }
        }else{
            return $x;
        }
    }
    function wplms_error_message_handle(){
        global $post;
        if(isset($_REQUEST['error'])){
            switch($_REQUEST['error']){
                case 'insufficient':
                    echo '<div id="message" class="notice"><p>'.__('Purchase points to take this course','vibe').' : <a href="'.$this->wplms_get_mycred_purchase_points().'">'.__('Add Points','wplms-mycred').'</a></p></div>';
                    break;
            }
        }
    }
    function wplms_mycred_setup_addons($installed){
        if ( isset( $_GET['addon_action'] ) && isset( $_GET['addon_id'] ) && $_GET['addon_id'] == 'wplms' && $_GET['addon_action'] == 'activate'){
            $mycred_addons=get_option('mycred_pref_addons');

            if(!isset($mycred_addons['installed']['wplms']))
                delete_option('mycred_pref_addons');
        }
        // Transfer Add-on
        $installed['wplms'] = array(
            'name'        => 'WPLMS',
            'description' => __( 'MyCred points options for WPLMS Learning Management', 'wplms-mycred' ),
            'addon_url'   => 'http://github.com/vibethemes/wplms-mycred-addon',
            'version'     => '1.0',
            'author'      => 'VibeThemes',
            'author_url'  => 'http://www.vibethemes.com',
            'path'        => realpath(dirname(__FILE__)). 'myCRED-addon-wplms.php'
        );

        return $installed;
    }


    function wplms_mycred_custom_metabox(){
        $prefix = 'vibe_';
        if(function_exists('calculate_duration_time')){
            $parameter = calculate_duration_time($this->subscription_duration_parameter);
        }else
            $parameter = 'DAYS';

        $mycred_metabox = array(
            array( // Text Input
                'label'	=> __('MyCred Points','vibe-customtypes'), // <label>
                'desc'	=> __('MyCred Points required to take this course.','vibe-customtypes'),
                'id'	=> $prefix.'mycred_points', // field id and name
                'type'	=> 'number' // type of field
            ),
            array( // Text Input
                'label'	=> __('Subscription ','vibe-customtypes'), // <label>
                'desc'	=> __('Enable subscription mode for this Course','vibe-customtypes'), // description
                'id'	=> $prefix.'mycred_subscription', // field id and name
                'type'	=> 'showhide', // type of field
                'options' => array(
                    array('value' => 'H',
                        'label' =>'Hide'),
                    array('value' => 'S',
                        'label' =>'Show'),
                ),
                'std'   => 'H'
            ),
            array( // Text Input
                'label'	=> __('Subscription Duration','vibe-customtypes'), // <label>
                'desc'	=> __('Duration for Subscription Products (in ','vibe-customtypes').$parameter.')', // description
                'id'	=> $prefix.'mycred_duration', // field id and name
                'type'	=> 'number' // type of field
            ),
        );

        $mycred_metabox = apply_filters('wplms_mycred_metabox',$mycred_metabox);

        if(class_exists('custom_add_meta_box')){
            $mycred_box = new custom_add_meta_box( 'mycred-settings', __('MyCred Points','vibe-customtypes'), $mycred_metabox, 'course', true );
        }
    }

    function get_point_course($course_id){
        $point=get_post_meta($course_id,'vibe_mycred_points',true);
        return $point;

    }
    function wplms_course_credits_array($price_html,$course_id){

        $points=get_post_meta($course_id,'vibe_mycred_points',true);
        if(isset($points) && is_numeric($points)){
            $mycred = mycred();
            $points_html='<strong>'.$mycred->format_creds($points);
            $subscription = get_post_meta($course_id,'vibe_mycred_subscription',true);
            if(isset($subscription) && $subscription && $subscription !='H'){
                $duration = get_post_meta($course_id,'vibe_mycred_duration',true);
                $duration = $duration*$this->subscription_duration_parameter;

                if(function_exists('tofriendlytime'))
                    $points_html .= ' <span class="subs"> '.__('per','vibe').' '.tofriendlytime($duration).'</span>';
            }
            $points_html .='.000 VNĐ</strong>';
            $price_html[]=$points_html;
        }
        return $price_html;
    }

    //lấy số tiền người dùng
    function get_point_user(){
        return $this->get_wplms_mycred_points();
    }

    function wplms_mycred_show_points(){
        echo '<li><a href="'.$this->wplms_get_mycred_link().'"><strong>'.$this->get_wplms_mycred_points().'</strong></a></li>';
    }

    function wplms_get_mycred_link(){
        $mycred = get_option('mycred_pref_core');

        if(isset($mycred['buddypress']) && isset($mycred['buddypress']['history_url']) && isset($mycred['buddypress']['history_location']) && $mycred['buddypress']['history_location']){
            $link=bp_get_loggedin_user_link().$mycred['buddypress']['history_url'];
        }else{
            $link='#';
        }
        return $link;
    }
    function wplms_get_mycred_purchase_points(){
        $link='#';
        return $link;
    }
    function get_wplms_mycred_points() {
        if ( is_user_logged_in() && class_exists( 'myCRED_Core' ) ) {
            $mycred = mycred();
            $balance = $mycred->get_users_cred( get_current_user_id() );
            return $mycred->format_creds( $balance );
        }else {
            return $mycred->format_creds(0);
        }
    }

    function use_mycred_points(){
        $user_id=get_current_user_id();
        $course_id = $_POST['id'];
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security'.$user_id) ){
            _e('Security check Failed.','wplms-mycred');
            die();
        }

        if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Incorrect Course','wplms-mycred');
            die();
        }

        $points = get_post_meta($course_id,'vibe_mycred_points',true);
        $mycred = mycred();
        $balance = $mycred->get_users_cred( $user_id );

        if($balance < $points){
            _e('Not enough balance','wplms-mycred');
            die();
        }
        $deduct = -1*$points;

        $start_date = get_post_meta($course,'vibe_start_date',true);
        $time=0;
        if(isset($start_date) && $start_date){
            $time=strtotime($start_date);
        }
        if($time<time())
            $time=time();

        $subscription = get_post_meta($course_id,'vibe_mycred_subscription',true);
        if(isset($subscription) && $subscription && $subscription !='H'){
            $duration = get_post_meta($course_id,'vibe_mycred_duration',true);
            if(!isset($duration) || !$duration){
                _e('Please set subscription duration or disable subscription','wplms-mycred');
                die();
            }
            $duration_parameter=$this->subscription_duration_parameter;
            $expiry = $time+$duration*$duration_parameter;
            update_user_meta($user_id,$course_id,$expiry);
            update_post_meta($course_id,$user_id,0);
        }else{
            $duration = get_post_meta($course_id,'vibe_duration',true);
            $duration_parameter = apply_filters('vibe_course_duration_parameter',86400);

            $expiry = $time+$duration*$duration_parameter;
            update_user_meta($user_id,$course_id,$expiry);
            update_post_meta($course_id,$user_id,0);
        }

        $mycred->update_users_balance( $user_id, $deduct);
        $mycred->add_to_log('take_course',
            $user_id,
            $deduct,
            __('Student subscibed for course','wplms-mycred'),
            $course_id,
            __('Student Subscribed to course , ends on ','wplms-mycred').date("jS F, Y",$expiry));


        $durationtime = $duration.' '.calculate_duration_time($duration_parameter);

        bp_course_record_activity(array(
            'action' => __('Student subscribed for course ','vibe').get_the_title($course_id),
            'content' => __('Student ','vibe').bp_core_get_userlink( $user_id ).__(' subscribed for course ','vibe').get_the_title($course_id).__(' for ','vibe').$durationtime,
            'type' => 'subscribe_course',
            'item_id' => $course_id,
            'primary_link'=>get_permalink($course_id),
            'secondary_item_id'=>$user_id
        ));
        $instructors[$course]=apply_filters('wplms_course_instructors',get_post_field('post_author',$course_id),$course_id);

        // Commission calculation

        if(function_exists('vibe_get_option'))
            $instructor_commission = vibe_get_option('instructor_commission');
        if($instructor_commission == 0)
            return;

        if(!isset($instructor_commission) || !$instructor_commission)
            $instructor_commission = 70;

        $instructors[$course_id]=apply_filters('wplms_course_instructors',get_post_field('post_author',$course_id),$course_id);

        $commissions = get_option('instructor_commissions');
        if(isset($commissions) && is_array($commissions)){
            if(is_array($instructors)){
                $instructors = array_unique($instructors);
                foreach($instructors as $instructor){
                    if(isset($commissions[$course_id]) && isset($commissions[$course_id][$instructor])){
                        $calculated_commission_base = round(($points*$commissions[$course_id][$instructor]/100),2);
                    }else{
                        $instructor_commission = $instructor_commission/count($instructors);
                        $calculated_commission_base = round(($points*$instructor_commission/100),2);
                    }
                    $mycred->add_to_log('instructor_commission',
                        $instructor,
                        $calculated_commission_base,
                        __('Instructor earned commission','wplms-mycred'),
                        $course_id,
                        __('Instructor earned commission for student purchasing the course via points ','wplms-mycred')
                    );
                }
            }else{
                if(isset($commissions[$course_id][$instructors])){
                    $calculated_commission_base = round(($points*$commissions[$course_id][$instructors]/100),2);
                }else{
                    $calculated_commission_base = round(($points*$instructor_commission/100),2);
                }
                $mycred->add_to_log('instructor_commission',
                    $instructor,
                    $calculated_commission_base,
                    __('Instructor earned commission','wplms-mycred'),
                    $course_id,
                    __('Instructor earned commission for student purchasing the course via points ','wplms-mycred')
                );
            }
        } // End Commissions_array

        do_action('wplms_course_mycred_points_puchased',$course_id,$user_id,$points);
        die();
    }

    function wplms_front_end_pricing($course_id){

        if(isset($course_id) && $course_id){
            $vibe_mycred_points = get_post_meta($course_id,'vibe_mycred_points',true);
            $vibe_mycred_subscription = get_post_meta($course_id,'vibe_mycred_subscription',true);
            $vibe_mycred_duration = get_post_meta($course_id,'vibe_mycred_duration',true);
        }else{
            $vibe_mycred_points=0;
            $vibe_mycred_subscription = 'H';
            $vibe_mycred_duration = 0;
        }



        echo '<li class="course_product" data-help-tag="19">
                <h3>'.__('Set Course Points','vibe').'<span>
                 <input type="text" id="vibe_mycred_points" class="small_box right" value="'.$vibe_mycred_points.'" /></span></h3>
            </li>
            <li class="course_product" >
                <h3>'.__('Subscription Type','vibe').'<span>
                    <div class="switch mycred-subscription">
                            <input type="radio" class="switch-input vibe_mycred_subscription" name="vibe_mycred_subscription" value="H" id="disable_cred_sub" '; checked($vibe_mycred_subscription,'H'); echo '>
                            <label for="disable_cred_sub" class="switch-label switch-label-off">'.__('Full Course','vibe').'</label>
                            <input type="radio" class="switch-input vibe_mycred_subscription" name="vibe_mycred_subscription" value="S" id="enable_cred_sub" '; checked($vibe_mycred_subscription,'S'); echo '>
                            <label for="enable_cred_sub" class="switch-label switch-label-on">'.__('Subscription','vibe').'</label>
                            <span class="switch-selection"></span>
                          </div>
                </span></h3>
            </li>
            <li class="credsubscription course_product" '.(($vibe_mycred_subscription == 'S')?'style="display:block;"':'style="display:none;"').'>
                <h3>'.__('Set Subscription','vibe').'<span>
                <input type="text" id="vibe_mycred_duration" class="small_box" value="'.$vibe_mycred_duration.'" /> '.calculate_duration_time($this->subscription_duration_parameter).'</span></h3>
            </li>
            ';
    }
}

new wplms_points_init();