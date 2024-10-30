<?php

// Add Shortcode to display people
function cdcrm_people_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'category' => '',
			'display' => 'description, image', // options: description, image (featured image), phone, email, address, title, prefix, suffix, business
			'orderby' => 'rand', // options: title, rand, menu_order
			'order'	  => 'ASC', //options: ASC, DESC
			'format' => 'list', // options: list, grid2, grid3, grid4
			'single_link' => 'no', // options: yes, no
			'align'		=> 	'',
			'cd_block'	=> 	'no',
			'textAlignment'	=> 'left',
		), $atts )
	);

	// Enqueue stylesheet if the display format is columns instead of list
	wp_enqueue_style( 'cdash-business-directory', plugin_dir_url( 'cdash-business-directory.php' ) . 'chamber-dashboard-business-directory/css/cdash-business-directory.css' );
	if($format !== 'list') {
		wp_enqueue_script( 'cdash-business-directory', plugin_dir_url( 'cdash-business-directory/php' ) . 'chamber-dashboard-business-directory/js/cdash-business-directory.js' );
	}

	// If user wants to display stuff other than the default, turn their display options into an array for parsing later
	
	if($display !== '') {
		if($cd_block == "yes"){
			$displayopts = explode( ",", $display);
		}else if($cd_block == "no"){
			$displayopts = explode( ", ", $display);
		}
  	}else{
		$displayopts = '';
	}  
	/*if($display !== '') {
  		$displayopts = explode( ", ", $display);
  	}*/

	$args = array(
		'post_type' => 'person',
		'people_category' => $category,
	    'posts_per_page' => -1,
	    'order' => $order,
	    'orderby' => $orderby,
	);

	$text_align = 'has-text-align-'.$textAlignment;
	$block_align = 'align'.$align;

	$people = new WP_Query( $args );
	// Find connected pages

	// The Loop
	if ( $people->have_posts() ) {
		$output = '<div id="cdcrm-people" class="' . $format . ' ' .  $block_align . ' ' .$text_align. '">';
			while ( $people->have_posts() ) : $people->the_post();
				$post_id = get_the_ID();
				global $person_metabox;
				$meta = $person_metabox->the_meta();
				$output .= '<div class="cdcrm-person">';
					if( in_array( 'image', $displayopts ) && has_post_thumbnail() ) {
						// display featured image
						$output .= '<div class="cdcrm-person-image">' . get_the_post_thumbnail($post_id) . '</div>';
					}
					// display the name
					$name = '';
					if( in_array( 'prefix', $displayopts ) && isset( $meta[ 'prefix'] ) && '' !== $meta['prefix'] ) {
						$name = $meta['prefix'] . ' ';
					}
					$name .= get_the_title();
					if( in_array( 'suffix', $displayopts ) && isset( $meta[ 'suffix'] ) && '' !== $meta['suffix'] ) {
						$name .= ',&nbsp;' . $meta['suffix'];
					}
					$permalink = get_the_permalink();
					if($single_link == 'yes'){
						$output .= '<h3 class="cdcrm-name"><a href="'.$permalink.'">' . $name . '</a></h3>';
					}else{
						$output .= '<h3 class="cdcrm-name">' . $name . '</h3>';
					}

					if( in_array( 'title', $displayopts ) && isset( $meta[ 'title'] ) && '' !== $meta['title'] ) {
						$output .= '<p class="cdcrm-title">' . $meta['title'] . '</p>';
					}
					if( in_array( 'description', $displayopts ) ) {
						// display description
						//$output .= '<div class="cdcrm-person-description">' . get_the_content() . '</div>';
						$output .= '<div class="cdcrm-person-description">' . get_the_excerpt() . '</div>';
					}
					if( in_array( 'address', $displayopts ) ) {
						// display address
						$output .= '<p class="cdcrm-address">';
						if( isset( $meta['address'] ) && '' !== $meta['address'] ) {
							$output .= $meta['address'] . '<br />';
						}
						if( isset( $meta['city'] ) && '' !== $meta['city'] ) {
							$output .= $meta['city'] . ',&nbsp;';
						}
						if( isset( $meta['state'] ) && '' !== $meta['state'] ) {
							$output .= $meta['state'] . '&nbsp;';
						}
						if( isset( $meta['zip'] ) && '' !== $meta['zip'] ) {
							$output .= $meta['zip'];
						}
						$output .= '</p>';
					}
					if( in_array( 'phone', $displayopts ) && isset( $meta['phone'] ) )  {
						// display phone
						$output .= cdash_display_phone_numbers( $meta['phone'] );
					}
					if( in_array( 'email', $displayopts ) && isset( $meta['email'] ) ) {
						// display email
						$output .= cdash_display_email_addresses( $meta['email'] );
					}
					if( in_array( 'business', $displayopts ) ) {
						// Display connected businesses
						$output .= '<p class="connected_businesses"><b>Connected Businesses</b><br />';

						$connected = new WP_Query( array(
							'connected_type' => 'businesses_to_people',
							'connected_items' => get_the_id(),
							'nopaging' => true
						) );
						while ( $connected->have_posts() ) : $connected->the_post();
						$permalink = get_the_permalink();
							$output .=  "<a href='" . $permalink . "'>" . get_the_title() . "</a></p>";
						endwhile;
						wp_reset_postdata();
					}
				$output .= '</div>';
			endwhile;
		$output .= '</div>';
	} else {
		$output = __( 'No people found.', 'cdcrm' );
	}

	// Reset Post Data
	wp_reset_postdata();

	return $output;

}
add_shortcode( 'chamber_dashboard_people', 'cdcrm_people_shortcode' );

// Display people connected with a business
function cdcrm_display_connected_people( ) {
	$contacts = '';

	$options = get_option( 'cdcrm_options' );

	// find connected people
	$connected = new WP_Query( array(
	  'connected_type' => 'businesses_to_people',
	  'connected_items' => get_the_id(),
	  'nopaging' => true,
	  'connected_meta' => array( 'Display' => 'Yes' )
	) );

	// Display connected people
	if ( $connected->have_posts() ) :
		$contacts .= '<div id="cdash-contacts"><strong>' . __( 'Contact: ', 'cdcrm' ) . '</strong><br />';
		while ( $connected->have_posts() ) : $connected->the_post();
			global $person_metabox;
			$meta = $person_metabox->the_meta();
			$permalink = get_the_permalink();
			$contacts .= '<p class="cdash-person">';
			if(isset($options['person_display_fields']['link']) && '' != $options['person_display_fields']['link'] ){
				$contacts .= '<a href="'.$permalink.'">';
			}
			if( isset( $options['person_display_fields']['prefix'] ) && isset( $meta['prefix'] ) && '' !== $meta['prefix'] ) {
				$contacts .= $meta['prefix'] . '&nbsp;';
			}

			$contacts .= get_the_title();
			if( isset( $options['person_display_fields']['suffix'] ) && isset( $meta['suffix'] ) && '' !== $meta['suffix'] ) {
				$contacts .= '&nbsp;, ' . $meta['suffix'];
			}

			if(isset($options['person_display_fields']['link']) && '' != $options['person_display_fields']['link'] ){
				$contacts .= '</a>';
			}

			if( isset( $options['person_display_fields']['title'] ) && isset( $meta['title'] ) && '' !== $meta['title'] ) {
				$contacts .= '<br />' . $meta['title'];
			}
			if( isset( $options['person_display_fields']['role'] ) ) {
				$contacts .= '<br />' . p2p_get_meta( get_post()->p2p_id, 'role', true );
			}
			if( isset( $options['person_display_fields']['address'] ) ) {
				if( isset( $meta['address'] ) && '' !== $meta['address'] ) {
					$contacts .= '<br />' . $meta['address'] . '<br />';
				}
				if( isset( $meta['city'] ) && '' !== $meta['city'] ) {
					$contacts .= $meta['city'] . ',&nbsp;';
				}
				if( isset( $meta['state'] ) && '' !== $meta['state'] ) {
					$contacts .= $meta['state'] . '&nbsp;';
				}
				if( isset( $meta['zip'] ) && '' !== $meta['zip'] ) {
					$contacts .= $meta['zip'];
				}
			}
			$contacts .= '</p>';
			if( isset( $options['person_display_fields']['phone'] ) && isset( $meta['phone'] ) ) {
				$contacts .= cdash_display_phone_numbers( $meta['phone'] );
			}
			if( isset( $options['person_display_fields']['email'] ) && isset( $meta['email'] ) ) {
				$contacts .= cdash_display_email_addresses( $meta['email'] );
			}
			if( isset($options['person_custom'] )) {
				$contacts .= cdash_crm_display_custom_fields( get_the_id() );
			}

		endwhile;
		$contacts .= '</div>';
	endif;
	wp_reset_postdata();

	return $contacts;
}

add_action( 'init', 'cdcrm_check_where_to_display_people' );
function cdcrm_check_where_to_display_people() {
	$options = get_option( 'cdcrm_options' );
	if( isset($options['person_display'] ) ) {
		if( isset( $options['person_display']['single'] ) ) {
			add_action( 'cdash_single_business_before_map', 'cdcrm_display_connected_people' );
		}
		if( isset( $options['person_display']['category'] ) ) {
			add_action( 'cdash_end_of_taxonomy_view', 'cdcrm_display_connected_people' );
		}
		if( isset( $options['person_display']['shortcode'] ) ) {
			add_action( 'cdash_end_of_shortcode_view', 'cdcrm_display_connected_people' );
		}
	}
}

// Add Shortcode to display activities
function cdcrm_activity_shortcode( $atts ) {
    // Attributes
	extract( shortcode_atts(
		array(
			'category' => '',
      'content' => 'yes', //options: yes, no
			'orderby' => 'rand', // options: title, rand, menu_order
			'format' => 'list', // options: list, grid2, grid3, grid4
		), $atts )
	);

  // Enqueue stylesheet if the display format is columns instead of list
	wp_enqueue_style( 'cdash-business-directory', plugin_dir_url( 'cdash-business-directory.php' ) . 'chamber-dashboard-business-directory/css/cdash-business-directory.css' );
	if($format !== 'list') {
		wp_enqueue_script( 'cdash-business-directory', plugin_dir_url( 'cdash-business-directory/php' ) . 'chamber-dashboard-business-directory/js/cdash-business-directory.js' );
	}

    if($content !== '') {
  		$contentopts = explode( ", ", $content);
  	}
    if($category !== ''){
        $args = array(
		  'post_type' => 'activity',
	      'tax_query' => array(
              array(
                'taxonomy' => 'activity_category',
                'field' => 'slug',
                'terms' => $category,
                'include_children' => false,
                'operator' => 'IN'
              ),
	    ),
	    'posts_per_page' => -1,
	    'order' => 'ASC',
	    'orderby' => $orderby,
	   );
    }else{
        $args = array(
            'post_type' => 'activity',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => $orderby,
        );
    }
	$activities = new WP_Query( $args );

    // The Loop
	if ( $activities->have_posts() ) {
		$output = '<div id="cdcrm-people" class="' . $format . '">';
			while ( $activities->have_posts() ) : $activities->the_post();
                $output .= '<div class="cdcrm-person">';

					$name = get_the_title();

					$output .= '<h3 class="cdcrm-name">' . $name . '</h3>';
					if( in_array( 'yes', $contentopts ) ) {
						// display description
						$output .= '<p class="cdcrm-person-description">' . get_the_content() . '</p>';
                    }
        $output .= '</div>';
			endwhile;
		$output .= '</div>';
	} else {
		$output = __( 'No activities found.', 'cdcrm' );
	}

	// Reset Post Data
	wp_reset_postdata();
    return $output;
}

add_shortcode( 'chamber_dashboard_activities', 'cdcrm_activity_shortcode' );

// ------------------------------------------------------------------------
// DISPLAY CUSTOM FIELDS
// ------------------------------------------------------------------------
function cdash_crm_display_custom_fields( $postid ) {
	$options = get_option( 'cdcrm_options' );
	$customfields = $options['person_custom'];
	global $custom_metabox;
	$custommeta = $custom_metabox->the_meta();

	$custom_fields = '';
	if( isset( $customfields ) && is_array( $customfields ) ) {
		$custom_fields .= "<div class='custom-fields'>";
		foreach($customfields as $field) {
			if( isset( $field['display_front'] ) && "yes" == $field['display_front'] ) {
				$fieldname = $field['name'];
				if( isset( $custommeta[$fieldname] ) ) {
					$custom_fields .= "<p class='custom " . $field['name'] . "'><strong class='custom cdash-label " . $field['name'] . "'>" . $field['name'] . ":</strong>&nbsp;" . $custommeta[$fieldname] . "</p>";
				} elseif( isset( $custommeta['_cdcrm_'.$fieldname] ) ) {
					$custom_fields .= "<p class='custom " . $field['name'] . "'><strong class='custom cdash-label " . $field['name'] . "'>" . $field['name'] . ":</strong>&nbsp;" . $custommeta['_cdcrm_'.$fieldname] . "</p>";
				}
			}
		}
		$custom_fields .= "</div>";
	}

	$custom_fields = apply_filters( 'cdash_crm_filter_custom_fields', $custom_fields, $postid );
	return $custom_fields;
}

add_action( 'cdash_settings_tab', 'cdash_crm_tab', 30 );
function cdash_crm_tab(){
	global $cdash_active_tab; ?>
    <a class="nav-tab <?php echo $cdash_active_tab == 'crm' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=cd-settings&tab=crm' ); ?>"><?php _e( 'Contacts', 'cdash' ); ?> </a>
	<?php
}

add_action( 'cdash_settings_content', 'cdash_crm_settings' );
function cdash_crm_settings(){
    global $cdash_active_tab;

	switch($cdash_active_tab){
		case 'crm':
		cdcrm_render_form();
		break;
	}
}
?>
