<?php
/* Options Page for Chamber Dashboard CRM */

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'cdcrm_delete_plugin_options')
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function cdcrm_delete_plugin_options() {
	delete_option('cdcrm_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'cdcrm_add_defaults')
// ------------------------------------------------------------------------------

// Define default option settings
function cdcrm_add_defaults() {
	$tmp = get_option('cdcrm_options');
    if(!is_array($tmp)) {
		delete_option('cdcrm_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(
						"person_phone_type" => "Work, Home, Cell",
			 			"person_email_type" => "Work, Personal",
			 			"person_business_roles" => "Owner, Manager, Employee, Accounting",
		);
		update_option('cdcrm_options', $arr);
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'cdcrm_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function cdcrm_init(){
	register_setting( 'cdcrm_plugin_options', 'cdcrm_options', 'cdcrm_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'cdcrm_add_options_page');
// ------------------------------------------------------------------------------

// Add menu page
function cdcrm_add_options_page() {
	//add_submenu_page( '/chamber-dashboard-business-directory/options.php', __('CRM Options', 'cdcrm'), __('CRM Options', 'cdcrm'), 'manage_options', 'cdash-crm', 'cdcrm_render_form' );
	//add_submenu_page( '/cd-settings', __('CRM Options', 'cdcrm'), __('CRM Options', 'cdcrm'), 'manage_options', 'cd-settings&tab=crm', 'cdcrm_render_form' );
}




// ------------------------------------------------------------------------------
// Register Settings
// ------------------------------------------------------------------------------

add_action( 'admin_init', 'cdcrm_settings_init' );

function cdcrm_settings_init() {
	register_setting( 'cdcrm_settings_page', 'cdcrm_options' );
	register_setting( 'cdcrm_import_page', 'cdcrm_options' );

	add_settings_section(
		'cdcrm_settings_page_section',
		__( 'Chamber Dashboard CRM Settings', 'cdcrm' ),
		'cdcrm_settings_section_callback',
		'cdcrm_settings_page'
	);

	add_settings_field(
		'person_phone_type',
		__( 'Phone Number Types', 'cdcrm' ),
		'cdcrm_person_phone_type_render',
		'cdcrm_settings_page',
		'cdcrm_settings_page_section',
		array(
			__( 'When you enter a phone number for a person, you can choose what type of phone number it is.  The default options are "Work, Home, Cell".  To change these options, enter a comma-separated list here.  (Note: your entry will over-ride the default, so if you still want main and/or office and/or cell, you will need to enter them.)', 'cdcrm')
		)
	);

	add_settings_field(
		'person_email_type',
		__( 'Email Types', 'cdcrm' ),
		'cdcrm_person_email_type_render',
		'cdcrm_settings_page',
		'cdcrm_settings_page_section',
		array(
			__( 'When you enter an email address for a business, you can choose what type of email address it is.  The default options are "Work, Personal".  To change these options, enter a comma-separated list here.  (Note: your entry will over-ride the default, so if you still want main and/or sales and/or accounting and/or HR, you will need to enter them.)', 'cdcrm')
		)
	);

	add_settings_field(
		'person_business_roles',
		__( 'Business Roles', 'cdcrm' ),
		'cdcrm_person_business_roles_render',
		'cdcrm_settings_page',
		'cdcrm_settings_page_section',
		array(
			__( 'You can connect people to businesses, and describe the person\'s role in that business. The default options are "Owner, Manager, Employee, Accounting". To change these options, enter a comma-separated list here. (Note: your entry will over-ride the default, so if you still want owner and/or manager and/or employee, you will need to enter them.', 'cdcrm')
		)
	);

	add_settings_field(
		'person_display',
		__( 'Display Contacts', 'cdcrm' ),
		'cdcrm_person_display_render',
		'cdcrm_settings_page',
		'cdcrm_settings_page_section',
		array(
			__( 'When you connect a person to a business, you can choose to display that person\'s contact information in the business directory by checking the box next to "Display."  Select where you want that person\'s information to display.', 'cdcrm')
		)
	);

	add_settings_field(
		'person_display_fields',
		__( 'Fields to Display', 'cdcrm' ),
		'cdcrm_person_display_fields_render',
		'cdcrm_settings_page',
		'cdcrm_settings_page_section',
		array(
			__( 'If you want to display contacts, select what contact information to display.', 'cdcrm')
		)
	);

	add_settings_field(
		'person_custom',
		__( 'Custom Fields', 'cdcrm' ),
		'cdcrm_person_custom_render',
		'cdcrm_settings_page',
		'cdcrm_settings_page_section',
		array(
			__( 'If you need to store additional information about people, you can create custom fields here. <br /><b>Note:</b>If you change the name of an existing custom field, you will lose all data stored in that field! ', 'cdcrm')
		)
	);

	// import tab
	add_settings_section(
		'cdcrm_import_page_section',
		__( 'Import', 'cdcrm' ),
		'cdcrm_import_section_callback',
		'cdcrm_import_page'
	);

}

function cdcrm_person_phone_type_render( $args ) {

	$options = get_option( 'cdcrm_options' );
	?>
	<input type='text' name='cdcrm_options[person_phone_type]' value='<?php echo $options['person_phone_type']; ?>'>
	<br /><span class="description"><?php echo $args[0]; ?></span>
	<?php

}

function cdcrm_person_email_type_render( $args ) {

	$options = get_option( 'cdcrm_options' );
	?>
	<input type='text' name='cdcrm_options[person_email_type]' value='<?php echo $options['person_email_type']; ?>'>
	<br /><span class="description"><?php echo $args[0]; ?></span>
	<?php

}

function cdcrm_person_business_roles_render( $args ) {

	$options = get_option( 'cdcrm_options' );
	?>
	<input type='text' name='cdcrm_options[person_business_roles]' value='<?php echo $options['person_business_roles']; ?>'>
	<br /><span class="description"><?php echo $args[0]; ?></span>
	<?php

}

function cdcrm_person_display_render( $args ) {

	$options = get_option( 'cdcrm_options' );
	?>
	<span class="description"><?php echo $args[0]; ?></span><br />
	<?php $choices = array(
		'single' => __( 'Single Business View', 'cdcrm' ),
		'category' => __( 'Category/Membership Level View', 'cdcrm' ),
		'shortcode' => __( 'Shortcode View', 'cdcrm' ),
	);
	foreach( $choices as $value => $description ) {
		$checked = false;
		if( isset( $options['person_display'] ) && in_array( $value, $options['person_display'] ) ) {
			$checked = true;
		} ?>
		<input type='checkbox' name='cdcrm_options[person_display][<?php echo $value; ?>]' id="<?php echo $value; ?>" value='<?php echo $value; ?>' <?php checked( $checked, true, true ); ?>><label for="<?php echo $value; ?>"><?php echo $description; ?></label><br />
	<?php }

}

function cdcrm_person_display_fields_render( $args ) {

	$options = get_option( 'cdcrm_options' );
	?>
	<span class="description"><?php echo $args[0]; ?></span><br />
	<?php $choices = array(
		'link' => __('Link to the person', 'cdcrm'),
		'title' => __( 'Title', 'cdcrm' ),
		'prefix' => __( 'Prefix', 'cdcrm' ),
		'suffix' => __( 'Suffix', 'cdcrm' ),
		'role' => __( 'Role in the business', 'cdcrm' ),
		'phone' => __( 'Phone Number(s)', 'cdcrm' ),
		'email' => __( 'Email Address(es)', 'cdcrm' ),
		'address' => __( 'Mailing Address', 'cdcrm' )
	);
	foreach( $choices as $value => $description ) {
		$checked = false;
		if( isset( $options['person_display_fields'] ) && in_array( $value, $options['person_display_fields'] ) ) {
			$checked = true;
		} ?>
		<input type='checkbox' name='cdcrm_options[person_display_fields][<?php echo $value; ?>]' id="<?php echo $value; ?>" value='<?php echo $value; ?>' <?php checked( $checked, true, true ); ?>><label for="<?php echo $value; ?>"><?php echo $description; ?></label><br />
	<?php }

}


function cdcrm_person_custom_render( $args ) {

	$options = get_option( 'cdcrm_options' );
	?>
	<span class="description"><?php echo $args[0]; ?></span>
	<?php if(isset($options['person_custom']) && is_array($options['person_custom']) && array_filter($options['person_custom']) != [] ) {
  	$field_set = true;
			$customfields = $options['person_custom'];
			$i = 1;

			foreach($customfields as $field) {
	       ?>
	  		<div class="repeating" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
	  			<?php
						cdcrm_custom_fields_name($field_set, $options, $i);
						cdcrm_custom_fields_type($field_set, $options, $i);
						cdcrm_custom_fields_display_front($field_set, $options, $i);
						//cdcrm_custom_fields_display_single($field_set, $options, $i);
	        ?>
	        <br />
	        <a href="#" class="delete-this"><?php _e('Delete This Custom Field', 'cdash'); ?></a>
	  		</div>
	  		<?php $i++;
	  	}
	  } else {
	    ?>
	  	<div class="repeating" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
	  		<?php
					cdcrm_custom_fields_name(false, $options, '');
					cdcrm_custom_fields_type(false, $options, '');
					cdcrm_custom_fields_display_front(false, $options, '');
					//cdcrm_custom_fields_display_single(false, $options, '');
	       ?>
	       <br />
	  		<a href="#" class="delete-this"><?php _e('Delete This Custom Field', 'cdash'); ?></a>
	  	</div>
	  <?php
	} ?>
	<p><a href="#" class="repeat"><?php _e('Add Another Custom Field', 'cdash'); ?></a></p>
	<?php
}

function cdcrm_custom_fields_name($field_set, $options, $i){
  ?>
  <p><strong><?php _e('Custom Field Name', 'cdash'); ?></strong></p>
  <p><span style="color:#666666;margin-left:2px;"><?php _e('<strong>Note:</strong> If you change the name of an existing custom field, you will lose all data stored in that field!', 'cdcrm'); ?></span></p>
  <?php
  if($field_set){
  ?>
    <input type="text" size="30" name="cdcrm_options[person_custom][<?php echo $i; ?>][name]" value="<?php if(isset($options['person_custom'])){ echo $options['person_custom'][$i]['name']; } ?>" />
  <?php
  }else{
  ?>
    <input type="text" size="30" name="cdcrm_options[person_custom][1][name]" value="<?php if(isset($options['person_custom'])){ echo $options['person_custom'][1]['name']; } ?>" />
  <?php
  }
}

function cdcrm_custom_fields_type($field_set, $options, $i){
  if($field_set){
    ?>
    <p><strong><?php _e('Custom Field Type'); ?></strong></p>
      <select name='cdcrm_options[person_custom][<?php echo $i; ?>][type]'>
        <option value=''></option>
        <option value='text' <?php selected('text', $options['person_custom'][$i]['type']); ?>><?php _e('Short Text Field', 'cdcrm'); ?></option>
        <option value='textarea' <?php selected('textarea', $options['person_custom'][$i]['type']); ?>><?php _e('Multi-line Text Area', 'cdcrm'); ?></option>
      </select>
    <?php
  }else{
    ?>
    <p><strong><?php _e('Custom Field Type'); ?></strong></p>
		<select name='cdcrm_options[person_custom][1][type]'>
			<option value=''></option>
			<option value='text'><?php _e('Short Text Field', 'cdcrm'); ?></option>
			<option value='textarea'><?php _e('Multi-line Text Area', 'cdcrm'); ?></option>
		</select>
    <?php
  }
}

function cdcrm_custom_fields_display_front($field_set, $options, $i){
  if($field_set){
    ?>
    <p><strong><?php _e('Display on the front end?', 'cdcrm'); ?></strong></p>
    <?php $field['display_front'] = ""; 
		if(isset($options['person_custom'][$i]['display_front'])){
			$display_front = $options['bus_custom'][$i]['display_front'];
		}else{
			$display_front = '';
		}
	?>
      <label><input name="cdcrm_options[person_custom][<?php echo $i; ?>][display_front]" type="radio" value="yes" <?php checked('yes', $display_front, true ); ?> /><?php _e(' Yes', 'cdcrm'); ?></label><br />
	  
      <label><input name="cdcrm_options[person_custom][<?php echo $i; ?>][display_front]" type="radio" value="no" <?php checked('no', $display_front, true); ?> /><?php _e(' No', 'cdcrm'); ?></label><br />
    <?php
  }else{
    ?>
    <p><strong><?php _e('Display on the front end?', 'cdcrm'); ?></strong></p>
      <label><input name="cdcrm_options[person_custom][1][display_front]" type="radio" value="yes" /> <?php _e('Yes', 'cdash'); ?></label><br />
      <label><input name="cdcrm_options[person_custom][1][display_front]" type="radio" value="no" /><?php _e('No', 'cdash'); ?></label><br />
    <?php
  }
}

function cdcrm_custom_fields_display_single($field_set, $options, $i){
  if($field_set){
    ?>
    <p><strong><?php _e('Display in Single Business View?', 'cdash'); ?></strong></p>
    <?php $field['display_single'] = ""; ?>
      <label><input name="cdcrm_options[person_custom][<?php echo $i; ?>][display_single]" type="radio" value="yes" <?php checked('yes', $options['person_custom'][$i]['display_single']); ?> /><?php _e(' Yes', 'cdcrm'); ?></label><br />
      <label><input name="cdcrm_options[person_custom][<?php echo $i; ?>][display_single]" type="radio" value="no" <?php checked('no', $options['person_custom'][$i]['display_single']); ?> /><?php _e(' No', 'cdcrm'); ?></label><br />
    <?php
  }else{
    ?>
    <p><strong><?php _e('Display in Single Business View?', 'cdash'); ?></strong></p>
      <label><input name="cdcrm_options[person_custom][1][display_single]" type="radio" value="yes" /><?php _e('Yes', 'cdcrm'); ?></label><br />
      <label><input name="cdcrm_options[person_custom][1][display_single]" type="radio" value="yes" /><?php _e('No', 'cdcrm'); ?></label><br />
    <?php
  }
}



function cdcrm_settings_section_callback() {
	echo __('<span class="desc"></span>', 'cdcrm');
}

function cdcrm_import_section_callback() {
	do_action( 'cdcrm_import_page' );
}


if(function_exists( 'cdash_requires_wordpress_version' )){
    $plugins = cdash_get_active_plugin_list();
    if( !in_array( 'cdash-crm-importer.php', $plugins ) ) {
        //cdcrm_import_edd_license_page();
        add_action( 'cdcrm_import_page', 'cdcrm_import_promo', 10 );

        function cdcrm_import_promo() { ?>
            <p><?php _e( 'CRM Importer is now available! You can purchase this addon from the <a href="admin.php?page=cd-addons">addons</> page.', 'cdash-crm' ); ?></p>
        <?php }
    }
}

// Render the Plugin options form
function cdcrm_render_form() {
	?>
	<div class="wrap">

		<?php
        $page = sanitize_text_field($_GET['page']);
        if(isset($_GET['tab'])){
            $tab = sanitize_text_field($_GET['tab']);
        }
        if(isset($_GET['section'])){
            $section = sanitize_text_field($_GET['section']);
        }else{
            $section = "crm_settings";
        }
        ?>

		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h1><?php _e('Chamber Dashboard CRM Settings', 'cdcrm'); ?></h1>
		<?php settings_errors(); ?>

		<div id="main" class="cd_settings_tab_group" style="width: 100%; float: left;">
            <div class="cdash section_group">
                <ul>
                    <li class="<?php echo $section == 'crm_settings' ? 'section_active' : ''; ?>">
                        <a href="?page=cd-settings&tab=crm&section=crm_settings" class="<?php echo $section == 'crm_setings' ? 'section_active' : ''; ?>"><?php esc_html_e( 'CRM Settings', 'cdash' ); ?></a><span>|</span>
                    </li>
                    <li class="<?php echo $section == 'cdcrm_import' ? 'section_active' : ''; ?>">
                        <a href="?page=cd-settings&tab=crm&section=cdcrm_import" class="<?php echo $section == 'cdcrm_import' ? 'section_active' : ''; ?>"><?php esc_html_e( 'CRM Import', 'cdash' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="cdash_section_content">
                <?php
                if( $section == 'crm_settings' )
                {
					cdcrm_settings();
                }else if($section == 'cdcrm_import'){
					settings_fields( 'cdcrm_import_page' );
	                do_settings_sections( 'cdcrm_import_page' );
                }
              ?>
            </div>
        </div><!--end of #main-->

		<?php
            //$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'cdcrm_settings_page';
        ?>

        <!--<h2 class="nav-tab-wrapper">
            <a href="?page=cdash-crm&tab=cdcrm_settings_page" class="nav-tab <?php echo $active_tab == 'cdcrm_settings_page' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'cdcrm' ); ?></a>
            <a href="?page=cdash-crm&tab=cdcrm_import_page" class="nav-tab <?php echo $active_tab == 'cdcrm_import_page' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Import', 'cdcrm' ); ?></a>
        </h2>-->
	</div>
	<?php
}

// Sanitize and validate input.
function cdcrm_validate_options($input) {
	// $msg = "<pre>" . print_r($input, true) . "</pre>";
	// wp_die($msg);
	if( isset( $input['person_phone_type'] ) ) {
    	$input['person_phone_type'] = wp_filter_nohtml_kses( $input['person_phone_type'] );
    }
    if( isset( $input['person_email_type'] ) ) {
    	$input['person_email_type'] = wp_filter_nohtml_kses( $input['person_email_type'] );
    }
    if( isset( $input['person_business_roles'] ) ) {
    	$input['person_business_roles'] = wp_filter_nohtml_kses( $input['person_business_roles'] );
    }

	return $input;
}

// Display a Settings link on the main Plugins page
function cdcrm_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$cdcrm_links = '<a href="'.get_admin_url().'options-general.php?page=cdash-crm/options.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $cdcrm_links );
	}

	return $links;
}

function cdcrm_settings(){
	?>
	<div id="crm_settings" class="cdash_plugin_settings">
		<form method="post" action="options.php">
			<?php settings_fields( 'cdcrm_settings_page' );
			?>
			<div class="settings_sections">
			<?php
			do_settings_sections( 'cdcrm_settings_page' );
			?>
			</div>
		<?php
			submit_button(); ?>
		</form>
	</div>
	<script type="text/javascript">
	// Add a new repeating section
	var attrs = ['for', 'id', 'name'];
	function resetAttributeNames(section, idx) {
		//var tags = section.find('input, label, select'), idx = section.index();
  var tags = section.find('input, label, select');
  //alert("Section Index idx: " + idx);
		tags.each(function() {
		  var $this = jQuery(this);
		  jQuery.each(attrs, function(i, attr) {
			var attr_val = $this.attr(attr);
			if (attr_val) {
				$this.attr(attr, attr_val.replace(/\[person_custom\]\[\d+\]\[/, '\[person_custom\]\['+(idx + 1)+'\]\['));
			}
		  })
		})
	}

	jQuery('.repeat').click(function(e){
			e.preventDefault();
			var lastRepeatingGroup = jQuery('.repeating').last();
	  var idx = jQuery('.repeating').length;

					//alert("Number: " + idx);

	  //Saving the value of the radio buttons from the last repeating section
	  var displayFrontName = "cdcrm_options[person_custom]["+idx+"][display_front]";
	  var display_front = jQuery("input[name='"+displayFrontName+"']:checked").val();

	  //Clone the lastRepeatingGroup
	  var cloned = lastRepeatingGroup.clone(true);

			cloned.insertAfter(lastRepeatingGroup);

	  //Clearing out the values in the newly cloned section
			cloned.find("input[type=text]").val("");
			cloned.find("select").val("");
	  cloned.find('input[type=radio]').removeAttr('checked');
			resetAttributeNames(cloned, idx);

	  //Resetting the values of the radio buttons in the previous section
	  jQuery("input[name='"+displayFrontName+"']").filter("[value="+display_front+"]").attr("checked", true);
	  //jQuery("input[name='"+displaySingleName+"']").filter("[value="+display_single+"]").attr("checked", true);
		});

	jQuery('.delete-this').click(function(e){
		e.preventDefault();
		jQuery(this).parent('div').remove();
	});

	</script>
	<?php
}

?>
