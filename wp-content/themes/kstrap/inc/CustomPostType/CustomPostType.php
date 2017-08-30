<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 8/23/2017
 * Time: 3:10 PM
 */

//namespace KMA\Helpers;


class CustomPostType
{

    private $dir;
    public $postTypeName;
    public $postTypeArgs;
    public $postTypeLabels;

    public function __construct($name, $args = array(), $labels = array())
    {

        $this->dir            = dirname(__FILE__);
        $this->postTypeName   = strtolower(str_replace(' ', '_', $name));
        $this->postTypeArgs   = $args;
        $this->postTypeLabels = $labels;

        // Add action to register the post type, if the post type does not already exist
        if ( ! post_type_exists($this->postTypeName)) {
            add_action('init', array(&$this, 'registerPostType'));
        }

        // Listen for the save post hook
        $this->save();

    }

    private function formatName($postName)
    {

        $niceName = ucwords(str_replace('_', ' ', $postName));

        return $niceName;

    }

    private function pluralizeName($postName)
    {

        $niceName = $this->formatName($postName);
        $last     = $postName[strlen($niceName) - 1];

        if ($last == 'y') {
            $cut = substr($niceName, 0, -1);
            //convert y to ies
            $pluralName = $cut . 'ies';
        } else {
            // just attach an s
            $pluralName = $niceName . 's';
        }

        return $pluralName;

    }

    private function definePostLabels($postName)
    {

        $name   = $this->formatName($postName);
        $plural = $this->pluralizeName($postName);

        $mergedLabels = array_merge(

            array(
                'name'               => _x($plural, 'post type general name'),
                'singular_name'      => _x($name, 'post type singular name'),
                'add_new'            => _x('Add New', strtolower($name)),
                'add_new_item'       => __('Add New ' . $name),
                'edit_item'          => __('Edit ' . $name),
                'new_item'           => __('New ' . $name),
                'all_items'          => __('All ' . $plural),
                'view_item'          => __('View ' . $name),
                'search_items'       => __('Search ' . $plural),
                'not_found'          => __('No ' . strtolower($plural) . ' found'),
                'not_found_in_trash' => __('No ' . strtolower($plural) . ' found in Trash'),
                'parent_item_colon'  => '',
                'menu_name'          => $plural
            ),

            $this->postTypeLabels

        );

        return $mergedLabels;

    }

    private function definePostArgs($postName)
    {

        $mergedArgs = array_merge(

            array(
                'label'             => $this->pluralizeName($postName),
                'labels'            => $this->definePostLabels($postName),
                'public'            => true,
                'show_ui'           => true,
                'supports'          => array('title', 'editor'),
                'show_in_nav_menus' => true,
                '_builtin'          => false,
            ),

            $this->postTypeArgs

        );

        return $mergedArgs;

    }

    public function registerPostType()
    {

        register_post_type($this->postTypeName, $this->definePostArgs($this->postTypeName));

    }

    private function defineTaxLabels($taxName, $taxonomyLabels)
    {

        $name   = $this->formatName($taxName);
        $plural = $this->pluralizeName($taxName);

        $mergedLabels = array_merge(

            array(
                'name'              => _x($plural, 'taxonomy general name'),
                'singular_name'     => _x($name, 'taxonomy singular name'),
                'search_items'      => __('Search ' . $plural),
                'all_items'         => __('All ' . $plural),
                'parent_item'       => __('Parent ' . $name),
                'parent_item_colon' => __('Parent ' . $name . ':'),
                'edit_item'         => __('Edit ' . $name),
                'update_item'       => __('Update ' . $name),
                'add_new_item'      => __('Add New ' . $name),
                'new_item_name'     => __('New ' . $name . ' Name'),
                'menu_name'         => __($name),
            ),

            $taxonomyLabels

        );

        return $mergedLabels;

    }

    private function defineTaxArgs($taxName, $taxonomyArgs, $taxonomyLabels)
    {

        $mergedArgs = array_merge(

            array(
                'label'             => $this->pluralizeName($taxName),
                'labels'            => $this->defineTaxLabels($taxName, $taxonomyLabels),
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                '_builtin'          => false,
            ),

            $taxonomyArgs

        );

        return $mergedArgs;

    }

    public function addTaxonomy($name, $args = array(), $labels = array())
    {

        if ( ! empty($name)) {

            $postTypeName = $this->postTypeName;
            $taxonomyName = strtolower(str_replace(' ', '_', $name));
            $taxonomyArgs = $this->defineTaxArgs($name, $args, $labels);

            if ( ! taxonomy_exists($taxonomyName)) {

                add_action('init',
                    function () use ($taxonomyName, $postTypeName, $taxonomyArgs) {
                        register_taxonomy($taxonomyName, $postTypeName, $taxonomyArgs);
                    }
                );

            } else { //Attach existing taxonomy to new post type

                add_action('init',
                    function () use ($taxonomyName, $postTypeName) {
                        register_taxonomy_for_object_type($taxonomyName, $postTypeName);
                    }
                );

            }

        }

    }

    private function uglify( $text ) {
        return strtolower(str_replace(' ', '_', $text));
    }

    private function createField( $label, $type, $meta, $data )
    {
        $fieldIdName  = $this->uglify($data['id']) . '_' . $this->uglify($label);
        $templateFile = $this->dir . '/templates/' . $type . '.php';
        if (file_exists($templateFile)) {
            $field = file_get_contents($templateFile);
            $field = str_replace('{field-name}', $fieldIdName, $field);
            $field = str_replace('{field-label}', $label, $field);
            $field = str_replace('{field-value}', $meta[$fieldIdName][0], $field);

	        //TODO: Add enque if scripts are needed (image and wysiwyg)

            echo $field;
        }
    }

    private function createBoxfields($boxId, $boxTitle, $boxContext, $boxPriority, $postTypeName, $fields)
    {

        add_action('admin_init',
            function () use ($boxId, $boxTitle, $boxContext, $boxPriority, $postTypeName, $fields) {
                add_meta_box(
                    $boxId,
                    $boxTitle,
                    function ($post, $data) {
                        global $post;

	                    wp_nonce_field( plugin_basename( __FILE__ ), 'CustomPostType' );
	                    $customFields = $data['args'][0];
	                    $meta         = get_post_custom( $post->ID );

	                    if ( ! empty( $customFields ) ) {
		                    foreach ( $customFields as $label => $type ) {
			                    $this->createField( $label, $type, $meta, $data );
		                    }
	                    }
                    },
	                $postTypeName,
                    $boxContext,
                    $boxPriority,
                    array( $fields )
                );
            }
        );

    }

    public function addMetaBox($title, $fields = array(), $context = 'normal', $priority = 'default')
    {
        if ( ! empty($title)) {
            $postTypeName = $this->postTypeName;

            $boxId       = strtolower(str_replace(' ', '_', $title));
            $boxTitle    = ucwords(str_replace('_', ' ', $title));
            $boxContext  = $context;
            $boxPriority = $priority;

            global $customFields;
            $customFields[$title] = $fields;

            $this->createBoxfields($boxId, $boxTitle, $boxContext, $boxPriority, $postTypeName, $fields);

        }
    }

    public function save()
    {
        $postTypeName = $this->postTypeName;

        add_action( 'save_post',
            function() use( $postTypeName )
            {
                // Deny the WordPress autosave function
                if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

                if ( ! wp_verify_nonce( $_POST['custom_post_type'], plugin_basename(__FILE__) ) ) return;

                global $post;

                if( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == $postTypeName )
                {
                    global $customFields;

                    // Loop through each meta box
                    foreach( $customFields as $title => $fields )
                    {
                        // Loop through all fields
                        foreach( $fields as $label => $type )
                        {
                            $fieldIdName  = $this->uglify($title ) . '_' . $this->uglify($label);
                            update_post_meta( $post->ID, $fieldIdName, $_POST['custom_meta'][$fieldIdName] );
                        }

                    }
                }
            }
        );
    }

}