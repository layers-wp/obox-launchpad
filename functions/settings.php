<?php class apollo_launchpad_settings
{
	function init() {
		add_action( 'admin_menu', array(&$this, 'apollo_menu'));
		add_action( 'admin_init', array(&$this,
			'home_page_order_fallback'));
		add_action( 'admin_init', array(&$this, 'apollo_initialize_options'));
		if(isset($_GET["refresh"]))
			add_action( 'admin_init', array(&$this,
			'clear_options'));
	}

	function clear_options(){
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display';
		delete_option('apollo_'.$active_tab.'_options');
		wp_redirect("?page=apollo_general_settings&tab=$active_tab");

	}

	function home_page_order_fallback(){
		if(!get_option("apollo_order_options")) :
			global $wp_settings_fields;
			foreach ( (array) $wp_settings_fields['apollo_order_options']['apollo_general_settings'] as $field ) :
				update_option('apollo_order_options', $field['args']);
			endforeach;
		endif;
	}

	function apollo_menu() {
		$this->pagehook = add_menu_page( 'Launchpad',
			'Launchpad',
			'administrator',
			'apollo_general_settings', array(&$this, 'apollo_display'),
			'dashicons-welcome-view-site' );
	}

	function buttons(){
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display';
		$html = "";
		$html .='<a href="' . get_home_url() . '?apollo=true&TB_iframe=true&width=640&height=632" class="preview thickbox thickbox-preview" title="Be sure to save your settings first">Preview</a>';
		$html .= '<a id="clear" href="?page=apollo_general_settings&tab=' . $active_tab . '&refresh" class="clear-settings">Clear Settings</a>' ;
		$html .= get_submit_button("Save Changes", "primary", "submit", false);
		$html = '<span>' . $html . '</span>';
		return $html;
	}
	function apollo_general_options_callback() {
		echo '<p class="top">' . $this->buttons() . 'Activate your landing page, set the launch date and add your copy. Get started here!</p>';
	} // end apollo_general_options_callback

	function apollo_social_options_callback() {
		echo '<p class="top">' . $this->buttons() . 'Enter in your social network URLs. Leave blank to hide the buttons.</p>';
	} // end apollo_general_options_callback

	function apollo_theme_options_callback() {
		echo '<p class="top">' . $this->buttons() . 'Setup the look &amp; feel of your launch page. <a href="http://photodune.net/search?ref=obox&tags%5B%5D=background&sales=rank-4">Get some cool backgrounds here.</a></p>';
	} // end apollo_general_options_callback

	function apollo_order_options_callback() {
		echo '<p class="top">' . $this->buttons() . 'Dictate the order of the elements on the home page.</p>';
	} // end apollo_general_options_callback

	function apollo_css_options_callback() {
		echo '<p class="top">' . $this->buttons() . 'If you want to fully cusomize your landing page, add your custom CSS here.</p>';
	} // end apollo_general_options_callback



	function apollo_display() {
		$tabs = array(
				"display" => "General",
				"theme" => "Look & Feel",
				"social" => "Social Links",
				"order" => "Page Elements Order",
				"css" => "Custom CSS"
			);

		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display'; ?>

		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">
			<?php $theme = wp_get_theme();
			if( 'layerswp' != $theme->template ) { ?>
				<div class="promo-layers">
					<h4>Try Layers</h4>
					<p>Setting up a new site? Why not try Layers? A revolutionary site builder specifically for WordPress.</p>
					<iframe width="560" height="315" src="https://www.youtube.com/embed/lRogY6qKBvQ" frameborder="0" allowfullscreen></iframe>
					<p>
						<a href="http://www.layerswp.com/?&utm_source=launchpad&utm_medium=cta&utm_campaign=Launchpad20%Layers20%Promo" target="_blank">Layers</a> is a revolutionary new site builder that makes creating beautiful, responsive websites, fast, fun and easy.
					</p>
					<a class="go-to-layers" href="http://www.layerswp.com/?&utm_source=launchpad&utm_medium=cta&utm_campaign=Launchpad20%Layers20%Promo" target="_blank">Find out more</a>
				</div>
			<?php } ?>
			<div class="lp-settings-section">

				<h2>Launchpad</h2>
				<?php settings_errors(); ?>
				<h2 class="nav-tab-wrapper">
					<?php foreach($tabs as $tab => $label) : ?>
						<a href="?page=apollo_general_settings&tab=<?php echo $tab; ?>" class="nav-tab <?php echo $active_tab == $tab ? 'nav-tab-active' : ''; ?>"><?php echo $label; ?></a>
					<?php endforeach; ?>
				</h2>

				<form method="post" action="options.php" enctype="multipart/form-data" class="launchpad-form">
					<?php do_settings_sections( 'apollo_'.$active_tab.'_options' ); ?>
					<?php settings_fields('apollo_'.$active_tab.'_options'); ?>
					<p><?php echo $this->buttons(); ?></p>
				</form>
			</div>

		</div><!-- /.wrap -->
	<?php
	}

	function apollo_initialize_options() {
		$apollo = new apollo_launchpad();
		$template = $apollo->template();
		$templateuri = $apollo->template_uri();

		// If the theme options don't exist, create them.
		if(!get_option('apollo_display_options')) :
			add_option('apollo_display_options');
		endif;		// First, we register a section. This is necessary since all future options must belong to a

		add_settings_section(
			'apollo_general_settings',				// Page on which to add this section of options
			'Launchpad Options',					// Title to be displayed on the administration page
			array(&$this, 'apollo_general_options_callback'),	// Callback used to render the description of the section
			'apollo_display_options'				// ID used to identify this section and with which to register options
		);

		add_settings_field(
			'active',
			'Activate Launchpad',
			array( &$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'active',
				'type' => 'checkbox',
				'default' => 0,
			)
		);

		add_settings_field(
			'launchdate',
			'Launch Date', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'launchdate',
				'type' => 'date',
				'default' => date( "Y/m/d G:i:s", current_time('timestamp') ),
				'excerpt' => 'When does your site launch? Server time is: <em>'  . current_time( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) . '</em> <a href="' . admin_url( 'options-general.php' ) . '" target="_blank">(Edit)</a>'
			)
		);

		add_settings_field(
			'automatic_launch',
			'Automatic Launch', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'automatic_launch',
				'type' => 'checkbox',
				'default' => 0,
				'excerpt' => 'Check this ON to automatically disable the plugin after the launch date.'
			)
		);

		add_settings_field(
			'display_tagline',
			'Display Site Tagline', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'display_tagline',
				'type' => 'checkbox',
				'default' => 1,
			)
		);

		add_settings_field(
			'title',
			'Introduction Title', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'title',
				'type' => 'text',
				'default' => 'Welcome!'
			)
		);

		add_settings_field(
			'intro',
			'Introduction Copy', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'intro',
				'type' => 'memo',
				'excerpt' => 'We are launching a new site very soon! Be sure to return later.'
			)
		);

		add_settings_field(
			'video',
			'Intro Video', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'video',
				'type' => 'text',
				'excerpt' => 'If you have a video, enter it\'s URL here.'
			)
		);

		add_settings_field(
			'subscription_embed',
			'Newsletter Embed Code', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'subscription_embed',
				'type' => 'memo',
				'excerpt' => 'Newsletter signup form embed code.'
			)
		);


		add_settings_field(
			'show_obox_logo',
			'Display Obox Logo', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'show_obox_logo',
				'type' => 'checkbox',
				'excerpt' => 'Display the Obox logo, creators of The Launchpad, in your footer.'
			)
		);

		add_settings_field(
			'copyright_text',
			'Footer Copyright Text', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'copyright_text',
				'type' => 'text',
				'default' => 'Copyright ' . get_bloginfo("name").' ' .date( 'Y' ). '. ', 'Enter in your custom copyright text for the site\'s footer.'
			)
		);

		$role_options = array();
		$editable_roles = get_editable_roles();

		foreach ( $editable_roles as $role => $details ) :
			$name = translate_user_role($details['name'] );
			$role_options[$name] = esc_attr($role);
		endforeach;

		add_settings_field(
			'role',
			'Minimum User Rights', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array(
				'name' => 'role',
				'type' => 'select',
				'default' => 'administrator',
				'excerpt' => 'Select which users are able to access the front end site.',
				'options' => $role_options
			)
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'apollo_display_options',
			'apollo_display_options', array(&$this, 'handle_form')
		);

		add_settings_section(
			'apollo_general_settings',			// Page on which to add this section of options
			'Social Link Options',					// Title to be displayed on the administration page
			array(&$this, 'apollo_social_options_callback'),	// Callback used to render the description of the section
			'apollo_social_options'			// ID used to identify this section and with which to register options
		);

		add_settings_field(
			'facebook',
			'Facebook', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings',
			array(
				'name' => 'facebook',
				'type' => 'text'
			)
		);

		add_settings_field(
			'vimeo',
			'Vimeo', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings',
			array(
				'name' => 'vimeo',
				'type' => 'text'
			)
		);

		add_settings_field(
			'tumblr',
			'Tumblr', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings',
			array(
				'name' => 'tumblr',
				'type' => 'text'
			)
		);

		add_settings_field(
			'wordpress',
			'Wordpress', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings',
			array(
				'name' => 'wordpress',
				'type' => 'text'
			)
		);

		add_settings_field(
			'twitter',
			'Twitter', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings',
			array(
				'name' => 'twitter',
				'type' => 'text'
			)
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'apollo_social_options',
			'apollo_social_options', array(&$this, 'handle_form')
		);

		add_settings_section(
			'apollo_general_settings',			// Page on which to add this section of options
			'Theme &amp; Display Options',					// Title to be displayed on the administration page
			array(&$this, 'apollo_theme_options_callback'),	// Callback used to render the description of the section
			'apollo_theme_options'			// ID used to identify this section and with which to register options
		);

		add_settings_field(
			'theme',
			'Theme', array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings',
			array(
				'name' => 'theme',
				'type' => 'select',
				'options' => array(
					"Grunge" => "grunge",
					"Minimal" => "minimal",
					"Slick Gloss" => "slick-gloss"
				)
			)
		);
		add_settings_field(
			'font',
			'Font', array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings',
			array(
				'name' => 'font',
				'type' => 'select',
				'options' => array(
					"-- Theme Default --" => "",
					"Sans Serif" => "sans-serif-style",
					"Serif Sans Serif" => "serif-sans-style",
					"Serif" => "serif-style"
				)
			)
		);

		add_settings_field(
			'typekit',
			'Typekit ID', array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings',
			array(
				'name' => 'typekit',
				'type' => 'text',
				'excerpt' => 'Enter in the Typekit Kit ID for your custom font.'
			)
		);

		add_settings_field(
			'logo',
			'Logo', array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings',
			array(
				'name' => 'logo',
				'type' => 'file',
				'options' => array()
			)
		);

		add_settings_field(
			'background',
			'Background',
			array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings',
			array(
				'name' => 'background',
				'type' => 'file',
				'options' => array(
					"Blue Haze" => $templateuri."/".$template."/images/bg/2co-bg.jpg",
					"aurorarain" => $templateuri."/".$template."/images/bg/aurorarain.jpg",
					"beachsunset" => $templateuri."/".$template."/images/bg/beachsunset.jpg",
					"california" => $templateuri."/".$template."/images/bg/california.jpg",
					"deepwater" => $templateuri."/".$template."/images/bg/deepwater.jpg",
					"dusksky" => $templateuri."/".$template."/images/bg/dusksky.jpg",
					"field" => $templateuri."/".$template."/images/bg/field.jpg",
					"meadow" => $templateuri."/".$template."/images/bg/meadow.jpg",
					"nightsky" => $templateuri."/".$template."/images/bg/nightsky.jpg",
					"rocky" => $templateuri."/".$template."/images/bg/rocky.jpg",
					"silentshore" => $templateuri."/".$template."/images/bg/silentshore.jpg",
					"texture" => $templateuri."/".$template."/images/bg/texture.jpg"
				)
			)
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'apollo_theme_options',
			'apollo_theme_options', array(&$this, 'handle_form')
		);

		add_settings_section(
			'apollo_general_settings',			// Page on which to add this section of options
			'Home Page Order',					// Title to be displayed on the administration page
			array(&$this, 'apollo_order_options_callback'),	// Callback used to render the description of the section
			'apollo_order_options'			// ID used to identify this section and with which to register options
		);

		add_settings_field(
			'order',
			'Click and drag the blocks to order them on your landing page', array(&$this, 'apollo_order'),
			'apollo_order_options',
			'apollo_general_settings',
			array(
				'count-down-timer' => 'Count Down Timer',
				'video' => 'Video',
				'welcome' => 'Secondary Title &amp; Intro',
				'subs-form' => 'Email Subscription Form',
				'social-links' => 'Social Links'
			)
		); // Finally, we register the fields with WordPress

		$this->home_page_order_fallback();
		register_setting(
			'apollo_order_options',
			'apollo_order_options', array(&$this, 'handle_form')
		);

		add_settings_section(
			'apollo_general_settings',			// Page on which to add this section of options
			'Custom CSS',					// Title to be displayed on the administration page
			array(&$this, 'apollo_css_options_callback'),	// Callback used to render the description of the section
			'apollo_css_options'			// ID used to identify this section and with which to register options
		);

		add_settings_field(
			'css',
			'Custom CSS', array(&$this, 'apollo_input'),
			'apollo_css_options',
			'apollo_general_settings',
			array(
				'name' => 'css',
				'type' => 'memo'
			)
		);
		register_setting(
			'apollo_css_options',
			'apollo_css_options', array(&$this, 'handle_form')
		);

	}

	function apollo_order( $order_options ){

		$active_order = get_option("apollo_order_options");

		if( empty( $active_order ) ) $active_order = $order_options;

		$active = '';
		$inactive = ''; ?>

		<h2 class="home-page-order"><?php _e( 'Active', 'launchpad' ); ?></h2>
		<ul class="home-page-order">
			<?php foreach( $active_order as $value => $label ) :

				if( array_key_exists( $value , $active_order ) ) : ?>
					<li>
						<label for="<?php echo esc_attr( $value ); ?>">
							<?php echo $label; ?>
							<input disabled type="checkbox" id="<?php echo esc_attr( $value ); ?>" name="apollo_order_options[<?php echo esc_attr( $value ); ?>]" checked="checked" value="<?php echo esc_attr( $label ); ?>" />
						</label>
					</li>
				<?php endif;

			endforeach; ?>
		</ul>

		<?php if( count( $order_options ) != count( $active_order ) ) : ?>
			<h2 class="home-page-order"><?php _e( 'In-Active', 'launchpad' ); ?></h2>
			<ul class="home-page-order">
			<?php foreach( $order_options as $value => $label ) :

				if( !array_key_exists( $value , $active_order ) ) : ?>
					<li>
						<label for="<?php echo esc_attr( $value ); ?>">
							<?php echo $label; ?>
							<input disabled type="checkbox" id="<?php echo esc_attr( $value ); ?>" name="apollo_order_options[<?php echo esc_attr( $value ); ?>]" value="<?php echo esc_attr( $label ); ?>" />
						</label>
					</li>
				<?php endif;

			endforeach; ?>
			</ul>
		<?php endif;

	}

	function apollo_input( $args ) {

		// First, we read the options collection
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display';
		$option = 'apollo_'.$active_tab.'_options';
		$options = get_option($option);
		$label = "";
		$id =  ( isset( $args[ 'name' ] ) ? $args[ 'name' ] : '' );
		$type =  ( isset( $args[ 'type' ] ) ? $args[ 'type' ] : '' );
		$default = ( isset( $args[ 'default' ] ) ? $args[ 'default' ] : '' );
		$excerpt = ( isset( $args[ 'excerpt' ] ) ? $args[ 'excerpt' ] : '' );


		if( $type == "checkbox" && isset( $options[ $id ] ) ) :
			$value = TRUE;
		elseif( isset( $options[ $id ] ) ) :
			$value = $options[ $id ];
		else :
			$value = $default;
		endif;

		if ($type == "checkbox") :

			$checked = '';

			if( isset( $options[ $id ] ) ) $checked = 'checked="checked"'; ?>

			<input disabled type="checkbox" id="<?php echo $id; ?>" name="<?php echo $option; ?>[<?php echo $id; ?>]" <?php echo $checked; ?> />
		<?php elseif ($type == "file") :

			$checked = '';
			$count = 0;
			$selected = 0;
			$images = "";
			$uploaded = array();
			$uploadclass='';

			$uploaded = get_posts(
				array(
					'post_type' => 'attachment',
					'meta_key' => '_apollo_related_image',
					'meta_value' => $id,
					'orderby' => 'none',
					'nopaging' => true
				) );

			if( $value != "" ){
				$checked = 'checked="checked"';
			} else {
				$uploadclass='no_display';
			} ?>

			<input disabled id="clear-<?php echo $id; ?>" data-input-key="<?php echo $id; ?>" name="" type="checkbox" <?php echo $checked; ?> />
			<label class="clear" for="clear-<?php echo $id; ?>">
				<?php _e( 'Enable', 'launchpad' ); ?> <?php echo $id; ?>
			</label>

			<div id="<?php echo $id; ?>-list" class="clear <?php echo $uploadclass; ?>">
				<input disabled type="file" id="upload-<?php echo $id; ?>" name="<?php echo $id; ?>_file" />
				<input disabled id="no-<?php echo $id; ?>" name="<?php echo $option; ?>[<?php echo $id; ?>]" type="radio" value="" <?php echo $checked; ?> class="no_display" />
				<div class="available-headers">
					<ul>
						<?php if(!empty($uploaded)) :
							foreach($uploaded as $image) :
								$full = wp_get_attachment_url($image->ID, "full");
								$thumb = wp_get_attachment_url($image->ID, "thumb");
								$checked = "";
								$class = "";
								if($value == $full){
									$checked = 'checked="checked"';
									$class = ' active';
									$selected = $count;
								} ?>

								<li class="default-header <?php echo $class; ?>">
									<input disabled id="<?php echo $id; ?>-<?php echo $image->ID; ?>" name="<?php echo $option; ?>[<?php echo $id; ?>]" type="radio" value="<?php echo $full; ?>" <?php echo $checked; ?> class="no_display" />
									<label for="<?php echo $id; ?>-<?php echo $image->ID; ?>">
										<img src="<?php echo esc_attr( $thumb ); ?>" alt="" title="" />
									</label>
								</li>
								<?php $count++;
							endforeach;

						endif;

						if(isset($args[ 'options' ])) :
							foreach($args[ 'options' ] as $image => $path) :
								$checked = "";
								$class = "";
								if($value == $path){
									$checked = 'checked="checked"';
									$class = ' active';
									$selected = $count;
								} ?>
								<li class="default-header <?php echo $class; ?>">
									<input disabled id="<?php echo $id; ?>-<?php echo $image; ?>" name="<?php echo $option; ?>[<?php echo $id; ?>]" type="radio" value="<?php echo $path; ?>" <?php echo $checked; ?> class="no_display" />
									<label for="<?php echo $id; ?>-<?php echo $image; ?>">
										<img src="<?php echo str_replace("bg/", "bg/thumbs/", $path); ?>" alt="" title="" width="150" />
									</label>
								</li>
								<?php $count++;
							endforeach;

						endif; ?>
					</ul>
				</div>
			</div>
		<?php elseif ($type == "memo") : ?>

			<textarea disabled id="<?php echo $id; ?>" name="<?php echo $option; ?>[<?php echo $id; ?>]" cols="50" rows="5"><?php echo $value; ?></textarea>
		<?php elseif ($type == "select") :
			$options = $args[ 'options' ]; ?>

			<select disabled id="<?php echo $id; ?>" name="<?php echo $option; ?>[<?php echo $id; ?>]">
				<?php if( isset( $options ) ) :
					foreach($options as $option => $option_value) :
						$selected = '';

						if($value == $option_value) $selected = 'selected="selected"'; ?>

						<option value="<?php echo $option_value; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
					<?php endforeach;
				endif; ?>
			</select>
		<?php else : ?>

			<input disabled type="text" id="<?php echo $id; ?>" name="<?php echo $option; ?>[<?php echo $id; ?>]" value="<?php echo $value; ?>" />
		<?php endif;

		if(!empty($excerpt)) : ?>
			<label for="<?php echo $id; ?>"><?php echo $excerpt; ?></label>
		<?php endif; ?>
	<?php }
	function handle_form($input){

		$newinput = $input;
		$files = $_FILES;
		foreach($files as $input => $values) :
			if(!empty($values["name"])) :
				$id = media_handle_upload($input, 0);
				$attachment = wp_get_attachment_image_src( $id, "full");
				$option = 	str_replace("_file", "", $input);

				update_post_meta($id, '_apollo_related_image', $option);

				$newinput[$option] = $attachment[0];
			endif;
		endforeach;
		return $newinput;
	}
}