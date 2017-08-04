<?php
/**
 * @package KMA
 * @subpackage kstrap
 * @since 1.0
 * @version 1.2
 */
$socialLinks = new SocialSettingsPage();
?>
    <div id="sticky-footer" class="unstuck">
        <!--<div id="bot">
                <div class="container">
                    <div id="botnav" class="row no-gutters justify-content-center ">
                        <nav class="navbar navbar-toggleable-md">
                            <?php wp_nav_menu(
                                array(
                                    'theme_location'  => 'menu-1',
                                    'container_class' => '',
                                    'container_id'    => 'navbar-footer',
                                    'menu_class'      => 'nav justify-content-center',
                                    'fallback_cb'     => '',
                                    'menu_id'         => 'menu-1',
                                    'walker'          => new WP_Bootstrap_Navwalker(),
                                )
                            ); ?>
                        </nav>
                    </div>
                </div>
            </div>-->
        <div id="bot-bot">
            <div class="container-fluid">
                <div class="row no-gutters justify-content-center justify-content-lg-start align-items-middle">
                    <div class="col-md-3 my-auto text-center text-md-left">
                        <div class="social">
                            <?php
                            $socialIcons = $socialLinks->getSocialLinks('svg','circle');
                            if(is_array($socialIcons)) {
                                foreach ( $socialIcons as $socialId => $socialLink ) {
                                    echo '<a class="' . $socialId . '" href="' . $socialLink[0] . '" target="_blank" >' . $socialLink[1] . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6 my-auto mx-auto justify-content-center text-center">
                        <p class="copyright">&copy;<?php echo date('Y'); ?> Kerigan Marketing Associates. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-3 my-auto justify-content-center justify-content-sm-end text-center text-sm-right">
                        <p class="siteby"><svg version="1.1" id="kma" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="14" width="20" viewBox="0 0 12.5 8.7" style="enable-background:new 0 0 12.5 8.7;" xml:space="preserve">
                                <defs><style>.kma{fill:#b4be35;}</style></defs>
                                <path class="kma" d="M6.4,0.1c0,0,0.1,0.3,0.2,0.9c1,3,3,5.6,5.7,7.2l-0.1,0.5c0,0-0.4-0.2-1-0.4C7.7,7,3.7,7,0.2,8.5L0.1,8.1
                                c2.8-1.5,4.8-4.2,5.7-7.2C6,0.4,6.1,0.1,6.1,0.1H6.4L6.4,0.1z"/>
                            </svg> <a href="https://keriganmarketing.com">Site by KMA</a>.</p>
                    </div>
                </div>
            </div><!-- .container -->
        </div>
    </div>
</div>
<?php wp_footer(); ?>

</body>
</html>