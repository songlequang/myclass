<?php do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() ) : ?>

	<?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

				<h4><?php bp_the_profile_group_name(); ?></h4>

				<table class="profile-fields">

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

						<?php if ( bp_field_has_data() ) : ?>

							<tr<?php bp_field_css_class(); ?>>

								<td class="label"><?php bp_the_profile_field_name(); ?></td>

								<td class="data"><?php bp_the_profile_field_value(); ?></td>

							</tr>

						<?php endif; ?>

						<?php do_action( 'bp_profile_field_item' ); ?>

					<?php endwhile; ?>

				</table>
			</div>

			<?php do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_profile_loop_content' ); ?>


<!-- thêm -->
<?php
    $arg = array(
    'post_type' => 'course',
    'post_status' => 'publish',
    //        'showposts'=> -1,
    'orderby' => 'title',
    'order' => "ASC",
    'post_author' => bp_displayed_user_id()
    );

global $wp_query;
//$curauth = $wp_query->get_queried_object();

$wp_query = new WP_Query($arg);

?>
<section id="memberstitle">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php _e('Những khóa học của giảng viên ','vibe'); echo $curauth->display_name; ?> </h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div id="buddypress">
        <div class="container">

            <div class="padder">

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <?php
                        if ( have_posts() ) : while ( have_posts() ) : the_post();
                            global $post;
                            $style=apply_filters('wplms_instructor_courses_style','course2');
                            echo '<div class="col-md-4 col-sm-4">'.thumbnail_generator($post,$style,'3','0',true,true).'</div>';
                        endwhile;
                            pagination();
                        endif;
                        ?>
                    </div>

                <?php do_action( 'bp_after_directory_course' ); ?>

            </div><!-- .padder -->

            <?php do_action( 'bp_after_directory_course_page' ); ?>
        </div><!-- #content -->
    </div>
</section>

<!--an mycred -->
<script>
    document.getElementsByClassName('mycred')[0].innerHTML="";
</script>

