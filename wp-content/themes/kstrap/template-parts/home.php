<?php
/**
 * @package KMA
 * @subpackage kstrap
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="section-wrapper full-bg" >

            <?php
                $slider = new Slider();
                echo $slider->getSlider('home-page-slider');
            ?>

            <a class="clickdown icon center" href="#bot"><i class="fa fa-angle-down" aria-hidden="true"></i></a>

        </div>
    </article><!-- #post-## -->
</div>
<!--<modal v-if="isVisible" @close="isVisible = false"><div class="box" >Welcome!</div></modal>-->