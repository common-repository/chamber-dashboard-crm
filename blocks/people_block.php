<?php 
//People Shortcode rendering
if ( function_exists( 'register_block_type' ) ) {
    // Hook server side rendering into render callback
    register_block_type(
        'cdash-bd-blocks/cdcrm-people', [
            'render_callback' => 'cdcrm_people_block_callback',
            'attributes'  => array(
                'align'  => array(
                    'type'  => 'string',
                    'default' => 'center',
                ),
                'textAlignment' => array(
                    'type'      =>  'string',
                    'default'   =>  'left',
                ),
                'cd_block'  => array(
                    'type'  => 'string',
                    'default' => 'yes',
                ),
                'category'  => array(
                    'type'  => 'string',
                    'default' => '',
                ),
                'categoryArray'  => array(
                    'type'  => 'array',
                    'default' => array(),
                ),
                'display'  => array(
                    'type'  => 'string',
                    'default' => '',
                ),
                'displayDescriptionToggle'  => array(
                    'type'  => 'boolean',
                    'default' => true,
                ),
                'displayImageToggle'  => array(
                    'type'  => 'boolean',
                    'default' => true,
                ),
                'displayPhoneToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'displayEmailToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'displayAddressToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'displayTitleToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'displayPrefixToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'displaySuffixToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'displayBusinessToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'orderby'  => array(
                    'type'  => 'string',
                    'default' => 'rand',
                ),
                'order'  => array(
                    'type'  => 'string',
                    'default' => 'ASC',
                ),
                'format'  => array(
                    'type'  => 'string',
                    'default' => 'list',
                ),
                'single_link'  => array(
                    'type'  => 'string',
                    'default' => 'no',
                ),
                'singleLinkToggle'  => array(
                    'type'  => 'boolean',
                    'default' => false,
                ),
                'format'  => array(
                    'type'  => 'string',
                    'default' => 'list',
                ),
            )
        ]
    );
}

function cdcrm_set_display_options($attributes, $displayOptions, $toggle_name, $string_value){
    if(isset($attributes[$toggle_name]) && $attributes[$toggle_name] === true){
        array_push($displayOptions, $string_value);
    }
    return $displayOptions;
  }

function cdcrm_people_block_callback($attributes){
    $displayOptions = [];

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayDescriptionToggle', 'description');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayImageToggle', 'image');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayPhoneToggle', 'phone');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayEmailToggle', 'email');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayAddressToggle', 'address');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayTitleToggle', 'title');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayPrefixToggle', 'prefix');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displaySuffixToggle', 'suffix');

    $displayOptions = cdcrm_set_display_options($attributes, $displayOptions, 'displayBusinessToggle', 'business');

    $attributes['display'] = implode(',', $displayOptions);

    if(isset($attributes['categoryArray']) && '' != $attributes['categoryArray']){
        $attributes['category'] = $attributes['categoryArray'];
    }

    $people_records = cdcrm_people_shortcode($attributes);

    return $people_records;
}