<?php
/**
 * @package KMA
 * @subpackage kstrap
 * @since 1.0
 * @version 1.2
 */

require('vendor/autoload.php');
require('inc/bootstrap-wp-navwalker.php');
//require ( 'inc/bulma_pagination.php' );
require('inc/cpt.php');
require('inc/loadmodules.php');

include('inc/CustomPostType/CustomPostType.php');

$book = new CustomPostType('Book');
$book->addTaxonomy('category');
$book->addTaxonomy('author');

$book->addMetaBox(
	'Book Info',
	array(
		'Year'        => 'text',
		'Genre'       => 'text',
		'Description' => 'textarea',
        'Featured'    => 'boolean'
	)
);

$book->addMetaBox(
    'Author Info',
    array(
        'Name'        => 'text',
        'Nationality' => 'text',
        'Birthday'    => 'text'
    )
);

if ( ! function_exists('kstrap_setup')) :

    function kstrap_setup()
    {

        load_theme_textdomain('kstrap', get_template_directory() . '/languages');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        register_nav_menus(array(
            'mobile-menu'    => esc_html__('Mobile Menu', 'kstrap'),
            'mini-top-right' => esc_html__('Mini Menu Top Right', 'kstrap'),
            'main-menu'      => esc_html__('Main Navigation', 'kstrap')
        ));

        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ));

        function kstrap_inline()
        { ?>
            <style type="text/css">
                <?php echo file_get_contents(get_template_directory() . '/style.css'); ?>
            </style>
        <?php }

        add_action('wp_head', 'kstrap_inline');

        wp_register_script('scripts', get_template_directory_uri() . '/app.js', array(), '0.0.1', true);

    }
endif;
add_action('after_setup_theme', 'kstrap_setup');

function kstrap_scripts()
{
    wp_enqueue_script('scripts');
    //wp_enqueue_style( 'style', get_stylesheet_uri() );
}

add_action('wp_enqueue_scripts', 'kstrap_scripts');

//Remove WordPress's content filtering so we can make our own tags AND use them.
remove_filter('the_content', 'wpautop');