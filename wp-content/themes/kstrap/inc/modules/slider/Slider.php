<?php
/**
 * Slider Class
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Slider {

    /**
     * Slider constructor.
     */
    function __construct() {

    }

    /**
     * @return null
     */
    public function createPostType() {

        $slider = new Custom_Post_Type( 'Slide Image', array(
            'supports'           => array( 'title', 'revisions' ),
            'menu_icon'          => 'dashicons-images-alt2',
            'rewrite'            => array( 'with_front' => false ),
            'has_archive'        => false,
            'menu_position'      => null,
            'public'             => false,
            'publicly_queryable' => false,
        ) );

        $slider->add_taxonomy( 'Slider' );

        $slider->add_meta_box( 'Slide Details', array(
            'Photo File'         => 'image',
            'Headline'           => 'text',
            'Caption'            => 'text',
            'Alt Tag'            => 'text',
            'Link'               => 'text',
            'Open in New Window' => 'boolean',
        ) );

        $slider->add_meta_box(
            'Photo Description',
            array(
                'HTML' => 'wysiwyg',
            )
        );

    }

    /**
     * @return null
     */
    public function createAdminColumns() {

        //TODO: make this work...

    }

    /**
     * @param slider ( post type category )
     * @return array
     */
    public function getSlides( $category = '' ){

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
        foreach ( $slidelist as $slide ){

            array_push($slideArray, array(
                'id'            => (isset($slide->ID)                               ? $slide->ID : null),
                'name'          => (isset($slide->post_title)                       ? $slide->post_title : null),
                'slug'          => (isset($slide->post_name)                        ? $slide->post_name : null),
                'photo'         => (isset($slide->slide_details_photo_file)         ? $slide->slide_details_photo_file : null),
                'headline'      => (isset($slide->slide_details_headline)           ? $slide->slide_details_headline : null),
                'caption'       => (isset($slide->slide_details_caption)            ? $slide->slide_details_caption : null),
                'alt'           => (isset($slide->slide_details_alt_tag)            ? $slide->slide_details_alt_tag : null),
                'url'           => (isset($slide->slide_details_link)               ? $slide->slide_details_link : null),
                'target'        => (isset($slide->slide_details_open_in_new_window) ? $slide->slide_details_open_in_new_window : null),
                'description'   => (isset($slide->photo_description_html)           ? $slide->photo_description_html : null),
                'link'          => get_permalink($slide->ID),

            ));

        }

        return $slideArray;

    }

    /**
     * @param slider ( post type category )
     * @return HTML
     */
    public function getSlider($category = ''){

        $slides = $this->getSlides($category);
        $slider = '';
        $slidercontent = '';
        $indicators = '';

        $i = 0;
        foreach($slides as $slide){

            $slidercontent .= '<div class="carousel-item full-bg '.($i == 0 ? ' active' : '').'" style="background-image:url('.$slide['photo'].')" >
                    <div class="carousel-caption d-md-block">'
                    . ($slide['headline'] != '' ? '<h2 class="slider-headline">'.$slide['headline'].'</h2>' : '')
                    . ($slide['caption'] != '' ? '<p class="slider-subtitle">'.$slide['caption'].'</p>' : '')
                    . ($slide['description'] != '' ? $slide['description'] : '') .
                    '</div>
                </div>';

            $indicators .= '<li data-target="#carousel-' . $category[0]->slug . '" data-slide-to="' . $i . '" ';
            if ( $i < 1 ) {
                $indicators .= 'class="active"';
            }
            $indicators .= '></li>';

            $i++;
        }

        $slider .= '    
        <div id="carousel-' . $category . '" class="carousel slide carousel-fade" data-ride="carousel">
            
            <ol class="carousel-indicators">' . $indicators . '</ol>
            
            <div class="carousel-inner" role="listbox">
            ' . $slidercontent . '
            </div>
            
            <a class="carousel-control-prev" href="#carousel-' . $category . '" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 4.5 8" style="enable-background:new 0 0 4.5 8;" xml:space="preserve">
                <path d="M4,0L0,4l4,4l0.5-0.5L1,4l3.5-3.5L4,0z"/>
                </svg></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-' . $category . '" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 4.5 8" style="enable-background:new 0 0 4.5 8;" xml:space="preserve">
                <path d="M0.5,8l4-4l-4-4L0,0.5L3.5,4L0,7.5L0.5,8z"/>
                </svg></span>
                <span class="sr-only">Next</span>
            </a>
            
        </div>';

        return $slider;

    }

}