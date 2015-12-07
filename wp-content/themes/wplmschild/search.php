<?php

if (isset($_GET["post_type"]) && $_GET["post_type"] == 'course'){
    load_template(TEMPLATEPATH . '/search-incourse.php'); 
    exit();
}



	get_header();

    if(isset($_GET['language']) && ! empty($_GET['language']) && isset($_GET['level']) && ! empty($_GET['level'])){
//        global $wp_query;
        $arg = array(
            'post_type'=>'course',
            'post_status' =>'publish',
            'tax_query'=>array(
                array(
                    'taxonomy'=>'language-category',
                    'terms'=>$_GET['language'],
                    'field'=>'term_id',
                ),
                array(
                    'taxonomy'=>'level',
                    'terms'=>$_GET['level'],
                    'field'=>'term_id',
                ),
                'orderby'=>'title',
                'order'=>"ASC"
            ),
            's' => $_GET['s']
        );
        $wp_query=new WP_Query($arg);
    }else{
        if(isset($_GET['language']) && ! empty($_GET['language'])){
            $arg = array(
                'post_type'=>'course',
                'post_status' =>'publish',
                'tax_query'=>array(
                    array(
                        'taxonomy'=>'language-category',
                        'terms'=>$_GET['language'],
                        'field'=>'term_id',
                    ),
                    'orderby'=>'title',
                    'order'=>"ASC"
                ),
                's' => $_GET['s']
            );
            $wp_query=new WP_Query($arg);
        }else {
            if (isset($_GET['level']) && !empty($_GET['level'])) {
                $arg = array(
                    'post_type' => 'course',
                    'post_status' => 'publish',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'level',
                            'terms' => $_GET['level'],
                            'field' => 'term_id',
                        ),
                        'orderby' => 'title',
                        'order' => "ASC"
                    ),
                    's' => $_GET['s']
                );
                $wp_query = new WP_Query($arg);
            } else {
                $arg = array(
                    'post_type' => 'course',
                    'post_status' => 'publish',
                    'tax_query' => array(
                        'orderby' => 'title',
                        'order' => "ASC"
                    ),
                    's' => $_GET['s']
                );
                $wp_query = new WP_Query($arg);
            }
        }
    }

    $total_results = $wp_query->found_posts;
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php _e('Search Results for "', 'vibe'); the_search_query(); ?>"</h1>
                    <h5><?php echo $total_results.__(' results found','vibe');  ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php vibe_breadcrumbs(); ?>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        <div class="col-md-3 col-sm-4">
            <div class="searchfilter">
                <?php
                    $manglanguage_name=array();
                    $manglanguage_id=array();
                    $manglevel_name=array();
                    $manglevel_id=array();
                    $demlanguage = 0;
                    $demlevel=0;
                    global $wpdb;
                    echo '<ul>';
                    if ( have_posts() ) : while ( have_posts() ) : the_post();

                        $terms_language = get_the_terms( get_the_ID(), 'language-category' );
                        foreach($terms_language as $value){
                            $manglanguage_name[$demlanguage]=$value->name;
                            $manglanguage_id[$demlanguage]=$value->term_id;
                            $demlanguage++;
                        }
                        $terms_level = get_the_terms( get_the_ID(), 'level' );
                        foreach($terms_level as $value){
                            $manglevel_name[$demlevel]=$value->name;
                            $manglevel_id[$demlevel]=$value->term_id;
                            $demlevel++;
                        }
                    endwhile;
                    endif;

                    $demlanguage = count($manglanguage_name);
                    $manglanguage_name=array_unique($manglanguage_name);
                    $manglanguage_id=array_unique($manglanguage_id);

                    $demlevel = count($manglevel_name);
                    $manglevel_name=array_unique($manglevel_name);
                    $manglevel_id=array_unique($manglevel_id);

                    echo '<li><h3>Ngôn Ngữ</h3></li>';
                    for($i=0;$i<$demlanguage;$i++){
                        if(!empty($manglanguage_name[$i])){
                            if($manglanguage_id[$i]==$_GET['language']){
                                echo '<li><input class="ngonngu" checked type="checkbox" id="'.$manglanguage_id[$i].'"><label for="'.$manglanguage_id[$i].'">'.$manglanguage_name[$i].'</label></li>';
                            }else {
                                echo '<li><input class="ngonngu" type="checkbox" id="' . $manglanguage_id[$i] . '"><label for="' . $manglanguage_id[$i] . '">' . $manglanguage_name[$i] . '</label></li>';
                            }
                        }
                    }

                    echo '<li><h3>Cấp Độ</h3></li>';
                    for($i=0;$i<$demlevel;$i++){
                        if(!empty($manglevel_name[$i])){
                            if($manglevel_id[$i]==$_GET['level']){
                                echo '<li><input class="capdo" checked type="checkbox" id="'.$manglevel_id[$i].'"><label for="'.$manglevel_id[$i].'" data-language="'.$_GET['language'].'">'.$manglevel_name[$i].'</label></li>';
                            }else {
                                echo '<li><input class="capdo" type="checkbox" id="' . $manglevel_id[$i] . '"><label for="' . $manglevel_id[$i] . '">' . $manglevel_name[$i] . '</label></li>';
                            }
                        }
                    }
                    echo '<input type="hidden" name="capdo" id="capdo" value="'.$_GET['level'].'">';
                    echo '<input type="hidden" name="ngongu" id="ngonngu" value="'.$_GET['language'].'">';
                    echo '<input type="hidden" name="tukhoa" id="tukhoa" value="'.$_GET['s'].'">';
                    echo '<input type="hidden" name="linktrangchu" id="linktrangchu" value="'.get_home_url().'">';
                    echo '</ul>';
                ?>
            </div>
        </div>
        <div class="col-md-9 col-sm-8">
            <div class="content">
                <?php
                    if ( have_posts() ) : while ( have_posts() ) : the_post();

//                    $categories = get_the_category();
//                    $cats='<ul>';
//                    if($categories){
//                        foreach($categories as $category) {
//                            $cats .= '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), 'vibe' ) ) . '">'.$category->cat_name.'</a></li>';
//                        }
//                    }
//                    $cats .='</ul>';
                        
                       echo ' <div class="blogpost">

                            '.(has_post_thumbnail(get_the_ID())?'
                            <div class="featured">
                                <a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'medium').'</a>
                            </div>':'').'
                            <div class="excerpt '.(has_post_thumbnail(get_the_ID())?'thumb':'').'">
                                <h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
                                <div class="cats">
                                    '.$cats.'
                                    <p>| 
                                    <a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_meta( 'display_name' ).'</a>
                                    </p>
                                </div>
                                <p>'.get_the_excerpt().'</p>
                                <a href="'.get_permalink().'" class="link">'.__('Read More','vibe').'</a>
                            </div>
                        </div>';
                    endwhile;
                    else:
                        echo '<h3>'.__('Sorry, No results found.','vibe').'</h3>';
                    endif;
                    pagination();
                ?>
            </div>
        </div>
<!--        <div class="col-md-3 col-sm-4">-->
<!--            <div class="sidebar">-->
<!--                --><?php
//                $sidebar = apply_filters('wplms_sidebar','searchsidebar');
//                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
<!--                --><?php //endif; ?>
<!--            </div>-->
<!--        </div>-->
    </div>
</section>
<?php
get_footer();
?>

<style>
    .blogpost .excerpt.thumb {
        margin-left: 240px;
    }
</style>