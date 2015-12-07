<?php
/*
Template Name: đây là template
*/

get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

<section id="content">

    <div class="container">
        <?php
        the_content();
        endwhile;
        endif;
        ?>
    </div>

</section>

<section id="content">
    <div class="container">
       <div class="course-cat col-md-3 col-sm-3">
           <?php
                do_action('getdanhmuckhoahoc');
           ?>
       </div>

        <div class="content_mycourse row content-course-cat col-md-9 col-sm-9">
            <div class="content_course">
                <?php do_action('hienthikhoahocmacdinhbandau')?>
            </div>

        </div>

    </div>
</section>


<?php
get_footer();
?>

