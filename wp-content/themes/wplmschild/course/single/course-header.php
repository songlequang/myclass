<?php
/**
 * The template for displaying Course Header
 *
 * Override this template by copying it to yourtheme/course/single/header.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     1.8.1
 */

do_action( 'bp_before_course_header' );

?>

	<div id="item-header-avatar" itemscope itemtype="http://data-vocabulary.org/Product">
			<?php bp_course_avatar(); ?>
	</div><!-- #item-header-avatar -->


<div id="item-header-content" style="padding: 0">
    <!--//Edit-->
	<!--<span class="highlight" itemprop="category"><?php /*bp_course_type(); */?></span>-->
 <!-- khải cắt phần bình chọn và phần số lượng member học chuyển sang phần body ở giữa --!>

</div><!-- #item-header-content -->
<div id="item-admins">

<h3><?php _e( 'Instructors', 'vibe' ); ?></h3>
	<?php
	bp_course_instructor();

	do_action( 'bp_after_course_menu_instructors' );
	?>
</div><!-- #item-actions -->

<?php
do_action( 'bp_after_course_header' );
?>