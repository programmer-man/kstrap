<?php
/**
 * @package KMA
 * @subpackage kstrap
 * @since 1.0
 * @version 1.2
 */
$tax = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$headline = $tax->name;
$subhead = '';
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <section class="hero is-light">
            <div class="hero-body">
                <div class="container">
                    <h1 class="title"><?php echo $headline; ?></h1>
                    <?php echo ($subhead!='' ? '<p class="subtitle">'.$subhead.'</p>' : null); ?>
                </div>
            </div>
        </section>
        <section id="content" class="content section">
            <div class="container">
                <div class="entry-content">
                    <?php


                    ?>
                </div><!-- .entry-content -->
            </div>
        </section>
    </article><!-- #post-## -->
</div>