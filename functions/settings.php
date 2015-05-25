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
		$this->pagehook = add_object_page( 'Launchpad',
			'Launchpad',
			'administrator',
			'apollo_general_settings', array(&$this, 'apollo_display'),
			'http://oboxthemes.com/images/ocmx-favicon.png' );
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
		echo '<p class="top">' . $this->buttons() . 'Setup the look &amp; feel of your launch page.</p>';
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

			<div class="promo-layers">
				<h4>Try Layers</h4>
				<p>Setting up a new site? Why not try Layers? A revolutionary site builder specifically for WordPress.</p>
				<iframe width="560" height="315" src="https://www.youtube.com/embed/lRogY6qKBvQ" frameborder="0" allowfullscreen></iframe>
				<p>
					<a href="http://www.layerswp.com/" target="_blank">Layers</a> is a revolutionary new site builder that makes creating beautiful, responsive websites, fast, fun and easy.
				</p>
				<a class="go-to-layers" href="http://www.layerswp.com/" target="_blank">Find out more</a>
			</div>

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
			array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings',
			array('active',
			'checkbox', 0,
			'') // ID, Input Type, Default, Excerpt, Options (if Select box) Check this ON to activate launchpad.
		);

		$options = array();
		$editable_roles = get_editable_roles();
		foreach ( $editable_roles as $role => $details ) :
			$name = translate_user_role($details['name'] );
			$options[$name] = esc_attr($role);
		endforeach;

		add_settings_field(
			'launchdate',
			'Launch Date', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('launchdate',
			'date', date("Y/m/d G:i:s", time()),
			'When does your site launch?')
		);

		add_settings_field(
			'automatic_launch',
			'Automatic Launch', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('automatic_launch',
			'checkbox', 0,
			'Check this ON to automatically disable the plugin after the launch date.') // ID, Input Type, Default, Excerpt, Options (if Select box)
		);

		add_settings_field(
			'display_tagline',
			'Display Site Tagline', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('display_tagline', 'checkbox', 1, '') // ID, Input Type, Default, Excerpt, Options (if Select box)
		);

		add_settings_field(
			'title',
			'Introduction Title', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('title', 'text', 'Welcome!')
		);

		add_settings_field(
			'intro',
			'Introduction Copy', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('intro', 'memo', 'We are launching a new site very soon! Be sure to return later.')
		);

		add_settings_field(
			'video',
			'Intro Video', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('video',
			'text',
			'',
			'If you have a video, enter it\'s URL here.')
		);

		add_settings_field(
			'subscription_embed',
			'Newsletter Embed Code', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('subscription_embed',
			'memo',
			'',
			'Newsletter signup form embed code.')
		);


		add_settings_field(
			'show_obox_logo',
			'Display Obox Logo', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('show_obox_logo', 'checkbox', '', 'Display the Obox logo, creators of The Launchpad, in your footer.')
		);

		add_settings_field(
			'copyright_text',
			'Footer Copyright Text', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('copyright_text', 'text', 'Copyright ' . get_bloginfo("name").' ' .date( 'Y' ). '. ', 'Enter in your custom copyright text for the site\'s footer.')
		);

		add_settings_field(
			'role',
			'Minimum User Rights', array(&$this, 'apollo_input'),
			'apollo_display_options',
			'apollo_general_settings', array('role', 'select', 'administrator', 'Select which users are able to access the front end site.', $options) // ID, Input Type, Default, Excerpt, Options (if Select box) Check this ON to activate launchpad.
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
			'apollo_general_settings', array('facebook', 'text', '', '')
		);

		add_settings_field(
			'vimeo',
			'Vimeo', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings', array('vimeo', 'text', '', '')
		);

		add_settings_field(
			'tumblr',
			'Tumblr', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings', array('tumblr', 'text', '', '')
		);

		add_settings_field(
			'wordpress',
			'Wordpress', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings', array('wordpress', 'text', '', '')
		);

		add_settings_field(
			'twitter',
			'Twitter', array(&$this, 'apollo_input'),
			'apollo_social_options',
			'apollo_general_settings', array('twitter', 'text', '', '')
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
			'apollo_general_settings', array('theme',
			'select',
			'',
			'', array("Grunge" => "grunge", "Minimal" => "minimal", "Slick Gloss" => "slick-gloss"))
		);
		add_settings_field(
			'font',
			'Font', array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings', array('font', 'select', '', '', array(
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
			'apollo_general_settings', array('typekit', 'text', '', 'Enter in the Typekit Kit ID for your custom font.')
		);

		add_settings_field(
			'logo',
			'Logo', array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings', array('logo', 'file', '', '')
		);

		add_settings_field(
			'background',
			'Background',
			array(&$this, 'apollo_input'),
			'apollo_theme_options',
			'apollo_general_settings',
			array('background', 'file', '', '', array("Blue Haze" => $templateuri."/".$template."/images/bg/2co-bg.jpg",
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
					"texture" => $templateuri."/".$template."/images/bg/texture.jpg")
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
			'apollo_general_settings', array(
					'Count Down Timer' => 'count-down-timer',
					'Video' => 'video',
					'Secondary Title &amp; Intro' => 'welcome',
					'Email Subscription Form' => 'subs-form',
					'Social Links' => 'social-links'
				) // ID, Input Type, Default, Excerpt, Options (if Select box)
		);		// Finally, we register the fields with WordPress

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
			'apollo_general_settings', array('css', 'memo', '', '')
		);
		register_setting(
			'apollo_css_options',
			'apollo_css_options', array(&$this, 'handle_form')
		);

	}

	function apollo_order($args){
		$options = get_option("apollo_order_options");
		$defaults = $args;
		if(!empty($options))
			$args = $options;

		$input = '<h2 class="home-page-order">Active</h2>';
		$input .= '<ul class="home-page-order">';
		foreach($args as $name => $function) :
			$input .= '<li><label for="' . $function . '">' . $name . '<input type="checkbox" id="' . $function . '" name="apollo_order_options[' . $name . ']" checked="checked" value="' . $function . '" /></label></li>';
		endforeach;
		$input .= '</ul>';

		$inactive = '';

		if(!empty($options) && $options != "") :
			foreach($defaults as $name => $function) :
				if(!in_array($function, $args))
					$inactive .= '<li><label for="' . $function . '">' . $name . '<input type="checkbox" id="' . $function . '" name="apollo_order_options[' . $name . ']" value="' . $function . '" /></label></li>';
			endforeach;
			if( isset( $inactive ) && $inactive != "") :
				$input .= '<h2 class="home-page-order">In-active</h2>';
				$input .= '<ul class="home-page-order">';
				$input .= $inactive;
				$input .= '</ul>';
			endif;
		endif;
		echo $input;

	}

	function apollo_input($args) {
		// First, we read the options collection
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display';
		$option = 'apollo_'.$active_tab.'_options';
		$options = get_option($option);
		$label = "";
		$id =  ( isset( $args[0] ) ? $args[0] : '' );
		$type =  ( isset( $args[1] ) ? $args[1] : '' );
		$default = ( isset( $args[2] ) ? $args[2] : '' );
		$excerpt = ( isset( $args[3] ) ? $args[3] : '' );


		if($type == "checkbox" && isset($options[$id])) :
			$value = "1";
		elseif( isset($options[$id])) :
			$value = $options[$id];
		else :
			$value = $default;
		endif;

		if ($type == "checkbox") :
			$checked = '';
			if(isset($options[$id])) { $checked = 'checked="checked"'; };
			$input = '<input type="checkbox" id="' . $id . '" name="' . $option . '[' . $id . ']" ' . $checked . '/>';
		elseif ($type == "file") :

			$checked = '';
			$count = 0;
			$selected = 0;
			$images = "";
			$uploaded = array();
				$uploadclass='';
			$uploaded = get_posts( array( 'post_type' => 'attachment',
			'meta_key' => '_apollo_related_image',
			'meta_value' => $id,
			'orderby' => 'none',
			'nopaging' => true ) );
			if( $value != "" ){
				$checked = 'checked="checked"';
			} else {
				$uploadclass='class="no_display"';
			}
			$input = '<input id="clear-' . $id . '" name="" type="checkbox" ' . $checked . ' /> <label class="clear" for="clear-' . $id . '">Enable ' . $id . ' </label>';
			$input .= '<div ' . $uploadclass . '>';
			$input .= '<input type="file" id=upload-"' . $id . '" name="' . $id . '_file" />';
			$input .= '<input id="no-' . $id . '" name="' . $option . '[' . $id . ']" type="radio" value="" ' . $checked . '" class="no_display" />';
			$checked = '';
			if(!empty($uploaded)) :
				foreach($uploaded as $image) :
					$full = wp_get_attachment_url($image->ID, "full");
					$thumb = wp_get_attachment_url($image->ID, "thumb");
					$checked = "";
					$class = "";
					if($value == $full){$checked .= 'checked="checked"'; $class = ' active'; $selected = $count;}

					$images .= '<li class="default-header' . $class . '">';
						$images .= '<label>';

							$images .= '<input id="' . $id . '" name="' . $option . '[' . $id . ']" type="radio" value="' . $full . '" ' . $checked . ' class="no_display" />';
							$images .= '<img src="' . $thumb . '" alt="" title="" />';
						$images .= '</label>';
					$images .= '</li>';
					$count++;
				endforeach;

			endif;
			if(isset($args[4])) :
				foreach($args[4] as $image => $path) :
					$checked = "";
					$class = "";
					if($value == $path){$checked = 'checked="checked"'; $class = ' active'; $selected = $count;}
					$images .= '<li class="default-header' . $class . '">';
						$images .= '<label>';

							$images .= '<input id="' . $id . '" name="' . $option . '[' . $id . ']" type="radio" value="' . $path . '" ' . $checked . ' class="no_display" />';
							$images .= '<img src="' . str_replace("bg/", "bg/thumbs/", $path) . '" alt="" title="" width="150" />';
						$images .= '</label>';
					$images .= '</li>';
					$count++;
				endforeach;

			endif;
			if(isset($args[4]) || !empty($uploaded)) :
				$images = '<div class="available-headers"><ul>'.$images.'</ul></div>';
				 /*if($count > 3)
					$images = '<p><a href="#" class="prev">Prev</a><a href="#" class="next">Next</a></p>'.$images;*/
			endif;
			$input = $input.$images;
		elseif ($type == "memo") :
			$input = '<textarea id="' . $id . '" name="' . $option . '[' . $id . ']" cols="50" rows="5">' . $value . '</textarea>';
		elseif ($type == "select") :
			$options = $args[4];
			$input = '<select id="' . $id . '" name="' . $option . '[' . $id . ']">' ;
			if(!empty($options)) :
				foreach($options as $option => $option_value) :
					$selected = '';
					if($value == $option_value){$selected = 'selected="selected"';}
					$input .= '<option value="' . $option_value . '" '. $selected . '>' . $option . '</option>';
				endforeach;
			endif;
			$input .= '</select>';
		else :
			$input = '<input type="text" id="' . $id . '" name="' . $option . '[' . $id . ']" value="' . $value . '" />';
		endif;

		if(!empty($excerpt))
			$label = '<label for="' . $id .'"> '  . $excerpt . '</label>';

		$input .= '</div>';
		$html = $input.$label;

		echo $html;

	}
	function handle_form($input){
		$newinput = $input;
		$files = $_FILES;
		foreach($files as $input => $values) :
			if(!empty($values["name"])) :
				$id = media_handle_upload($input, 0);
				$attachment = wp_get_attachment_image_src( $id, "full");
				$option = 	str_replace("_file", "", $input);
				update_post_meta($id,
			'_apollo_related_image', $option);
				$newinput[$option] = $attachment[0];
			endif;
		endforeach;
		return $newinput;
	}
}