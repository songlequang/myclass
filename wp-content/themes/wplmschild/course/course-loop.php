<?php
/**
 * The template for displaying course directory loop.
 *
 * Override this template by copying it to yourtheme/course/course-loop.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     1.8.1
 */
?>
<?php do_action( 'bp_before_course_loop' ); ?>
<?php
if ( bp_course_has_items( bp_ajax_querystring( 'course' ) ) ) : ?>
    <div id="pag-top" class="pagination">

        <div class="pag-DeCuongKHcount" id="course-dir-count-top">

            <?php bp_course_pagination_count(); ?>

        </div>

        <div class="pagination-links" id="course-dir-pag-top">

            <?php bp_course_item_pagination(); ?>

        </div>

    </div>

    <?php do_action( 'bp_before_directory_course_list' ); ?>

    <ul id="course-list" class="item-list" role="main">

        <?php while ( bp_course_has_items() ) : bp_course_the_item(); ?>

            <li itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
                <?php
                global $post;
                $cache_duration = vibe_get_option('cache_duration'); if(!isset($cache_duration)) $cache_duration=86400;
                if($cache_duration){
                    $course_key= 'course_'.$post->ID;
                    if(is_user_logged_in()){
                        $user_id = get_current_user_id();
                        $user_meta = get_user_meta($user_id,$post->ID,true);
                        if(isset($user_meta)){
                            $course_key= 'course_'.$user_id.'_'.get_the_ID();
                        }
                    }
                    $result = wp_cache_get($course_key,'course_loop');
                }else{$result=false;}

                if ( false === $result) {
                    ob_start();
                    ?>
                    <div class="item-avatar" itemprop="photo">
                        <?php bp_course_avatar(); ?>
                    </div>

                    <div class="item">
                        <div class="item-title"  itemprop="itemreviewed"><?php bp_course_title(); if(get_post_status() != 'publish'){echo '<i> ( '.get_post_status().' ) </i>';} ?></div>
                        <div class="item-meta"><?php bp_course_meta(); ?></div>
                        <div class="item-desc"><?php bp_course_desc(); ?></div>
                        <div class="item-credits">
                            <?php bp_course_credits(); ?>
                        </div>

                        <div class="item-instructor">
                            <?php bp_course_instructor(); ?>
                        </div>
                        <div class="item-action"><?php bp_course_action() ?></div>
                        <div class="item_process">
                            <?php
                            $course_id = $post->ID;
                            $unit_id = wplms_get_course_unfinished_unit($post->ID);
                            do_action('child_wplms_course_start_after_time',$course_id,$unit_id);
                            ?>

                        </div>
                        <?php do_action( 'bp_directory_course_item' ); ?>

                    </div>
                    <?php
                    $result = ob_get_clean();
                }
                if($cache_duration)
                    wp_cache_set( $course_key,$result,'course_loop',$cache_duration);
                echo $result;
                ?>
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
        <p><?php _e( 'You have not subscribed to any courses.', 'vibe' ); ?></p>
    </div>

<?php endif;  ?>


<?php do_action( 'bp_after_course_loop' ); ?>
