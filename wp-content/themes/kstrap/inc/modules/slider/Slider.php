<?php
/**
 * Slider Class
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Slider {

    private $dir;
    private $cpt;

    /**
     * Slider constructor.
     */
    function __construct() {

        $this->dir = dirname(__FILE__);

    }

    /**
     * @return null
     */
    public function createPostType() {

        $this->cpt = new Custom_Post_Type( 'Slide Image', array(
            'supports'           => array( 'title', 'revisions' ),
            'menu_icon'          => 'dashicons-images-alt2',
            'rewrite'            => array( 'with_front' => false ),
            'has_archive'        => false,
            'menu_position'      => null,
            'public'             => false,
            'publicly_queryable' => false,
        ) );

        $this->cpt->add_taxonomy( 'Slider' );

        $this->cpt->add_meta_box( 'Slide Details', array(
            'Photo File'         => 'image',
            'Headline'           => 'text',
            'Caption'            => 'text',
            'Alt Tag'            => 'text',
            'Link'               => 'text',
            'Open in New Window' => 'boolean',
        ) );

        $this->cpt->add_meta_box(
            'Photo Description',
            array(
                'HTML' => 'wysiwyg',
            )
        );

        $this->createAdminColumns();

    }

    /**
     * @return null
     */
    public function createAdminColumns() {

        //TODO: make this work...

    }

    /**
     * @param slider ( post type category )
     *
     * @return array
     */
    public function getSlides( $category = '' ) {

        $request = array(
            'posts_per_page' => - 1,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'slide_image',
            'post_status'    => 'publish',
        );

        if ( $category != '' ) {
            $categoryarray        = array(
                array(
                    'taxonomy'         => 'slider',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ),
            );
            $request['tax_query'] = $categoryarray;
        }

        $slidelist = get_posts( $request );

        $slideArray = array();
        foreach ( $slidelist as $slide ) {

            array_push( $slideArray, array(
                'id'          => ( isset( $slide->ID ) ? $slide->ID : null ),
                'name'        => ( isset( $slide->post_title ) ? $slide->post_title : null ),
                'slug'        => ( isset( $slide->post_name ) ? $slide->post_name : null ),
                'photo'       => ( isset( $slide->slide_details_photo_file ) ? $slide->slide_details_photo_file : null ),
                'headline'    => ( isset( $slide->slide_details_headline ) ? $slide->slide_details_headline : null ),
                'caption'     => ( isset( $slide->slide_details_caption ) ? $slide->slide_details_caption : null ),
                'alt'         => ( isset( $slide->slide_details_alt_tag ) ? $slide->slide_details_alt_tag : null ),
                'url'         => ( isset( $slide->slide_details_link ) ? $slide->slide_details_link : null ),
                'target'      => ( isset( $slide->slide_details_open_in_new_window ) ? $slide->slide_details_open_in_new_window : null ),
                'description' => ( isset( $slide->photo_description_html ) ? $slide->photo_description_html : null ),
                'link'        => get_permalink( $slide->ID ),
                'category'    => ( $category != '' ? $category : 'none' ),
            ) );

        }

        return $slideArray;

    }

    private function buildIndicators( $slideArray ) {

        $indicators = '';

        foreach ( $slideArray as $num => $indicator ) {

            $indicators .= file_get_contents( $this->dir . '/IndicatorBuilder.php' );
            $indicators = str_replace( '{indicator-category}', $indicator['category'], $indicators );
            $indicators = str_replace( '{indicator-num}', $num, $indicators );
            $indicators = str_replace( '{indicator-active}', ( $num == 0 ? 'active' : '' ), $indicators );

        }

        return $indicators;

    }

    private function buildSlides( $slideArray ) {

        $slides = '';

        foreach ( $slideArray as $num => $slide ) {

            $slides .= file_get_contents( $this->dir . '/SlideBuilder.php');
            $slides = str_replace( '{slide-headline}', $slide['headline'], $slides );
            $slides = str_replace( '{slide-caption}', $slide['caption'], $slides );
            $slides = str_replace( '{slide-description}', $slide['description'], $slides );
            $slides = str_replace( '{slide-active}', ( $num == 0 ? 'active' : '' ), $slides );
            $slides = str_replace( '{slide-photo}', $slide['photo'], $slides );

        }

        return $slides;

    }

    private function buildSlider( $slideArray ) {

        $indicators = $this->buildIndicators( $slideArray );
        $slides     = $this->buildSlides( $slideArray );

        $slider = file_get_contents( $this->dir . '/SliderBuilder.php' );
        $slider = str_replace( '{slider-category}', ( $slideArray[0]['category'] != '' ? $slideArray[0]['category'] : 'none' ), $slider );
        $slider = str_replace( '{slider-indicators}', $indicators, $slider );
        $slider = str_replace( '{slider-slides}', $slides, $slider );

        return $slider;

    }

    public function getSlider( $category = '' ) {

        $slideArray = $this->getSlides( $category );
        $slider     = $this->buildSlider( $slideArray );

        return $slider;

    }

}