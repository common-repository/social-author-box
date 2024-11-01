<?php
add_action( 'admin_menu', 'social_author_box_add_admin_menu' );
add_action( 'admin_init', 'social_author_box_settings_init' );


function social_author_box_add_admin_menu(  ) { 
	add_options_page( 'Social Author Box', 'Social Author Box', 'manage_options', 'social_author_box', 'social_author_box_options_page' );
}

function social_author_box_settings_sanitize($value) {
  if (!isset($value['social_author_box_checkbox_fa_css'])) {
    $value['social_author_box_checkbox_fa_css'] = '';
  }
  if (!isset($value['social_author_box_checkbox_plugin_css'])) {
    $value['social_author_box_checkbox_plugin_css'] = '';
  }
  return $value;
}

function social_author_box_settings_init(  ) { 
	if( !get_option( 'social_author_box_settings' ) ) {  
	  add_option( 
	   	'social_author_box_settings',
	   	array(
	   		'social_author_box_checkbox_plugin_css' => intval(0),
	   		'social_author_box_checkbox_fa_css' => intval(0),
	   	)
	  );
	} // end if

	register_setting( 'pluginPage', 'social_author_box_settings', 'social_author_box_settings_sanitize');

	add_settings_section(
		'social_author_box_pluginPage_section',  // ID used to identify this section and with which to register options
		__( 'CSS settings', 'wordpress' ), // Title to be displayed on the administration page
		'social_author_box_settings_section_callback', // Callback used to render the description of the section
		'pluginPage' // Page on which to add this section of options
	);

	add_settings_field( 
		'social_author_box_checkbox_fa_css',	// ID used to identify the field throughout the theme
		__( 'Font Awesome', 'wordpress' ),  // The label to the left of the option interface element
		'social_author_box_checkbox_fa_css_render', // The name of the function responsible for rendering the option interface
		'pluginPage', // The page on which this option will be displayed
		'social_author_box_pluginPage_section', // The name of the section to which this field belongs
		array(
			'Include Font Awesome font icons from netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css.'
		) // The array of arguments to pass to the callback. In this case, just a description.
	);

	add_settings_field( 
		'social_author_box_checkbox_plugin_css', 
		__( 'Plugin CSS', 'wordpress' ), 
		'social_author_box_checkbox_plugin_css_render', 
		'pluginPage', 
		'social_author_box_pluginPage_section' ,
		array(
			'Include Social Author Box\'s default CSS to give the Social Author Box widget a basic look.'
		) // The array of arguments to pass to the callback. In this case, just a description.
	);
}

function social_author_box_checkbox_fa_css_render( $args ) { 
	$options = get_option( 'social_author_box_settings' );
	?>
	<input type='checkbox' name='social_author_box_settings[social_author_box_checkbox_fa_css]' <?php checked( $options['social_author_box_checkbox_fa_css'],0 ); ?> value='0'>
	<label for="social_author_box_settings[social_author_box_checkbox_fa_css]"><?php echo $args[0]; ?></label>
	<?php

}

function social_author_box_checkbox_plugin_css_render( $args) { 
	$options = get_option( 'social_author_box_settings' );
	?>
	<input type='checkbox' name='social_author_box_settings[social_author_box_checkbox_plugin_css]' <?php checked( $options['social_author_box_checkbox_plugin_css'],0 ); ?> value='0'>
	<label for="social_author_box_settings[social_author_box_checkbox_plugin_css]"><?php echo $args[0]; ?></label>
	<?php

}


function social_author_box_settings_section_callback(  ) { 
	echo __( 'If you\'re an advanced user you may wish to disable the CSS Stylesheets added by Social Author Box. If you\'re uncertain about this choice, leave the boxes checked.', 'wordpress' );
}


function social_author_box_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Social Author Box</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>