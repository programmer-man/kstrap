<?php
/**
 * @package KMA
 * @subpackage kstrap
 * @since 1.0
 * @version 1.2
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport"
            content="width=device-width, initial-scale=1">
    <link rel="profile"
            href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >
<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'kstrap' ); ?></a>
<div id="app">
<header id="top" class="header">
    <div class="collapse justify-content-center hidden-md-up" id="mobilemenu">
        <?php wp_nav_menu( array(
            'theme_location' => 'main-menu',
            'container_class' => 'navbar',
            'container_id'    => 'navbarLeft',
            'menu_class'      => 'navbar-nav mr-auto text-left',
            'fallback_cb'     => '',
            'menu_id'         => 'mobile-menu',
            'walker'          => new WP_Bootstrap_Navwalker(),
        ) ); ?>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-9 col-md-2">
                <a class="navbar-brand" href="/">
                    <img src="<?php echo get_template_directory_uri() . '/img/kstrap-logo.svg'; ?>" alt="KStrap" height="28">
                </a>
            </div>
            <div class="col-3 hidden-md-up">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobilemenu" aria-controls="mobilemenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-box">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </span>
                </button>
            </div>
            <div class="col-12 col-sm-9 hidden-sm-down">
                <?php wp_nav_menu( array(
                        'theme_location' => 'main-menu',
                        'container_class' => 'navbar',
                        'container_id'    => 'navbarMain',
                        'menu_class'      => 'nav justify-content-end',
                        'fallback_cb'     => '',
                        'menu_id'         => 'main-menu',
                        'walker'          => new WP_Bootstrap_Navwalker(),
                    ) ); ?>
            </div>
        </div>
    </div>
</header>
