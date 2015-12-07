<?php

  if(is_user_logged_in()):

    global $post,$id_post;

    $user_id = get_current_user_id();
    $coursetaken=get_user_meta($user_id,$post->ID,true);

    if(isset($coursetaken) && $coursetaken){


    $answers=get_comments(array(
      'post_id' => $post->ID,
      'status' => 'approve',
      'user_id' => $user_id
      ));
    if(isset($answers) && is_array($answers) && count($answers)){
        $answer = end($answers);
        $content = $answer->comment_content;
    }else{
        $content='';
    }

    $fields =  array(
        'author' => '<p><label class="comment-form-author clearfix">'.__( 'Name','vibe' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input class="form_field" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" /></p>',
        'email'  => '<p><label class="comment-form-email clearfix">'.__( 'Email','vibe' ) .  ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .          '<input id="email" class="form_field" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"/></p>',
        'url'   => '<p><label class="comment-form-url clearfix">'. __( 'Website','vibe' ) . '</label>' . '<input id="url" name="url" type="text" class="form_field" value="' . esc_attr( $commenter['comment_author_url'] ) . '"/></p>',
         );

   $comment_field='<p>' . '<textarea id="comment" name="comment" class="form_field" rows="8" ">'.$content.'</textarea></p>';

   if ( isset($_POST['review']) && wp_verify_nonce($_POST['review'],get_the_ID()) ):

    comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'label_submit' => __('Post Review','vibe'),'title_reply'=> '<span>'.__('Write a Review','vibe').'</span>','logged_in_as'=>'','comment_notes_after'=>'' ));
    echo '<div id="comment-status" data-quesid="'.$post->ID.'"></div><script>jQuery(document).ready(function($){$("#submit").hide();$("#comment").on("keyup",function(){if($("#comment").val().length){$("#submit").show(100);}else{$("#submit").hide(100);}});});</script>';
    endif;
  }
  ?>
<?php
  endif;
?>
<h3 class="review_title"><?php _e('Course Reviews','vibe'); ?></h3>
  <?php
  if (get_comments_number()==0) {
    echo '<div id="message" class="notice"><p>';_e('No Reviews found for this course.','vibe');echo '</p></div>';
  }else{
  ?>
  <ol class="reviewlist commentlist">
      <?php
//              wp_list_comments('type=comment&avatar_size=120&reverse_top_level=false');
      global $id_post;
      $danhsach=array(
          'post_id' => $id_post,
          'meta_key' => 'review_rating',
          'status' => 'approve',
      );
      $danhgiacuauser = get_comments($danhsach);
      wp_list_comments( 'type=comment&callback=mytheme_comment&avatar_size=120&reverse_top_level=false',$danhgiacuauser );
      paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') )
      ?>

  </ol>
<?php
  }
?>
<?php
    function mytheme_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

        $user_info = get_userdata( $comment->user_id );

    if ( 'div' == $args['style'] ) {
    $tag = 'div';
    $add_below = 'comment';
    } else {
    $tag = 'li';
    $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
<?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
<?php endif; ?>
    <div class="comment-author vcard">
        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
        <?php printf( __( '<cite class="fn">'.bp_core_get_userlink($comment->user_id).'</cite> <span class="says">says:</span>' ) ); ?>
    </div>
<?php if ( $comment->comment_approved == '0' ) : ?>
    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
    <br />
<?php endif; ?>

    <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
            <?php
            /* translators: 1: date, 2: time */
            printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '  ', '' );
        ?>

    </div>


<?php //comment_text(); ?>
        <p><strong><?php echo get_comment_meta($comment->comment_id,'review_title',true) ?></strong>
            <br /> <?php echo $comment->comment_content ?>
            </p>
        <?php $sosaodanhgia=get_comment_meta($comment->comment_id,'review_rating',true); ?>
        <div class='omment-rating star-rating'>
            <?php for($i=1;$i<=5;$i++){
                if($i<=$sosaodanhgia){
                    echo "<span class='fill'></span>";
                }else{
                    echo "<span></span>";
            }
            }
            echo "-Cách đây ".human_time_diff( strtotime($comment->comment_date), strtotime(current_time( 'mysql' ))  ); ?>
            </div>

    <div class="reply">
        <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
    </div>
<?php if ( 'div' != $args['style'] ) : ?>
    </div>
<?php endif; ?>
<?php
}

