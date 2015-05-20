<?php class apollo_launchpad
{
	function template(){
		//Which theme are we hooking?
		if(isset($_GET["template"]) && $_GET["template"]) :
			$template = $_GET["template"];
		elseif(get_option("apollo_theme")) :
			$template = get_option("apollo_theme");
		else :
			$template = "default";
		endif;
		//If theme is invalid, change to default
		if(!file_exists($this->template_dir()."/".$template)) :
			$template = "apollo";
		endif;

		return $template;
	}
	function stylesheet(){
		//Which theme are we hooking?
		if(isset($_GET["stylesheet"]) && $_GET["stylesheet"]) :
			$template = $_GET["stylesheet"];
		elseif(get_option("apollo_stylesheet")) :
			$template = get_option("apollo_stylesheet");
		else :
			$template = "apollo";
		endif;
		//If theme is invalid, change to default
		if(!file_exists($this->template_dir()."/".$template)) :
			$template = "default";
		endif;
		return $template;
	}

	function template_dir(){
		$template_path = LAUNCHPADDIR."themes";
		return $template_path;
	}

	function template_uri(){
		$template_path = plugins_url("launchpad-by-obox/")."themes";
		return $template_path;
	}

	// Which stylesheet are we using for the front page?
	function color_style(){
		$options = get_option('apollo_theme_options');

		if(isset($_GET["use_colour"]) && $_GET["use_colour"]) :
			$theme = $_GET["use_colour"];
		elseif(!$options["theme"]) :
			$theme = "slick-gloss";
		else :
			$theme = $options["theme"];
		endif;
		return $theme;
	}

	function add_query_vars($query_vars) {
	    $query_vars[] = 'style';
	    return $query_vars;
	}

	function custom_css() {
	    $style = get_query_var('style');
	    if($style == "custom") {
		    include_once($this->template_dir() . '/' . $this->template() . '/style.php');
	        exit;
	    }
	}

	// Script inclusion
	function scripts(){
		global $pagenow;

		// jQuery inclusion
		wp_enqueue_script( "jquery");

		// Admin Scripts
		if(is_admin()) :
			if(isset($_REQUEST['page']) && $_REQUEST['page'] == "apollo_general_settings") :
				// Scripts
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'jquery-ui-droppable' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'jquery-ui-slider' );

				wp_enqueue_script( 'ui-jquery-timepicker', plugins_url('launchpad-by-obox/js/jquery.timepicker.js'), array( 'jquery' ) );
				wp_enqueue_script( 'jquery-checkboxes', plugins_url('launchpad-by-obox/js/jquery.checkboxes.js'), array( 'jquery' ) );
				wp_enqueue_script( 'apollo-admin', plugins_url('launchpad-by-obox/js/admin.js'), array( 'jquery' ) );
				wp_localize_script( 'apollo-admin', 'base', plugins_url('launchpad'));
				wp_enqueue_script( 'theme-preview' );
				add_thickbox();

				// Styles
				wp_enqueue_style( 'apollo-admin', plugins_url("launchpad-by-obox/css/admin.css"));
				wp_register_style( 'ui-jquery-style', plugins_url("launchpad-by-obox/css/jquery-ui.css"));
				wp_register_style( 'jquery-checkbox', plugins_url("launchpad-by-obox/css/checkboxes.css"));

				wp_enqueue_style( 'apollo-admin' );
				wp_enqueue_style( 'ui-jquery-style' );
				wp_enqueue_style( 'jquery-checkbox' );
				wp_enqueue_style( 'thickbox-css' );

			endif;
		// Front end scripts
		else :
			$apollo_options =  get_option("apollo_display_options");
			wp_enqueue_script( "countdown-jquery", plugins_url("launchpad-by-obox/js/jquery.countdown.js"), array( "jquery" ) );
			wp_enqueue_script( "apollo", plugins_url("launchpad-by-obox/js/jquery.apollo.js"), array( "jquery" ) );
			$date = date("F d, Y G:i:s", strtotime($apollo_options["launchdate"]));
			wp_localize_script( "apollo", "date", $date);
		endif;
	}
	function admin_styles(){
		global $pagenow;

		// jQuery inclusion
		wp_enqueue_script( "jquery");

		// Admin Scripts
		if(is_admin()) :
			if(isset($_REQUEST['page']) && $_REQUEST['page'] == "apollo_general_settings") :
				// Styles
				wp_register_style( 'apollo-admin', plugins_url("launchpad-by-obox/css/admin.css"));
				wp_register_style( 'ui-jquery-style', plugins_url("launchpad-by-obox/css/jquery-ui.css"));
				wp_register_style( 'jquery-checkbox', plugins_url("launchpad-by-obox/css/checkboxes.css"));

				wp_enqueue_style( 'apollo-admin' );
				wp_enqueue_style( 'ui-jquery-style' );
				wp_enqueue_style( 'jquery-checkbox' );
				wp_enqueue_style( 'thickbox-css' );

			endif;
		endif;
	}

	function styles(){
		$apollo_theme_options =  get_option("apollo_theme_options");
		$apollo_css_options =  get_option("apollo_css_options");

		wp_register_style( 'apollo-color', $this->template_uri() . '/' . $this->template() . '/color-styles/' . $this->color_style() . '/style.css');
		wp_enqueue_style( 'apollo-color' );

		if(isset($apollo_theme_options["font"]) && $apollo_theme_options["font"] != "") :
			wp_register_style( 'apollo-fonts', plugins_url("launchpad-by-obox/").'css/fonts/' . $apollo_theme_options["font"] . '.css');
			wp_enqueue_style( 'apollo-fonts' );
		endif;

		if($apollo_css_options["css"] != "" || $apollo_theme_options["background"] != "") :
			wp_register_style( 'apollo-custom', get_home_url() . '?style=custom');
			wp_enqueue_style( 'apollo-custom' );
		endif;
	}

	// Script version removal, because we don't want ?ver=3.3.2 trailing every script inclusion
	function remove_src_version ( $src ) {
		global $wp_version;
		$version_str = '?ver='.$wp_version;
		$version_str_offset = strlen( $src ) - strlen( $version_str );
		if( substr( $src, $version_str_offset ) == $version_str )
			return substr( $src, 0, $version_str_offset );
		else
			return $src;
	}

	//Let the admin know when the plugin is active, and that the public can't see the site.
	function active_warning(){
		global $wp_admin_bar;
		$options = get_option('apollo_display_options');

		 /* Add the main siteadmin menu item */
		 if(isset($options["active"]))
			 $wp_admin_bar->add_menu( array( 'id' => 'apollo_general_settings', 'title' => 'Warning: Maintenance mode is active!', 'href' => admin_url('?page=apollo_general_settings')) );

	}

	// Let's make sure we're not in the admin section and that we don't have the rights to access this site
	function active(){
		$options = get_option('apollo_display_options');

		if(isset($_GET["apollo"])) :
			//Remove Admin bar for preview
			add_filter('show_admin_bar', '__return_false');
			return true;
		elseif(isset($options["automatic_launch"]) && isset($options["launchdate"]) && strtotime($options["launchdate"]) < time()) :
			return false;
		elseif(isset($options["active"]) && strpos( $_SERVER['REQUEST_URI'], '/wp-admin' ) === false) :
			if(!is_user_logged_in()) :
				return true;
			elseif(isset($options["role"])) :
				$current_user = wp_get_current_user();
				if ($options["role"] == 'administrator') :
					$role = 'manage_options';
				elseif ($options["role"] == 'editor') :
					$role = 'manage_categories';
				elseif ($options["role"] == 'author') :
					$role = 'publish_posts';
				elseif ($options["role"] == 'contributor') :
					$role = 'edit_posts';
				elseif ($options["role"] == 'subscriber') :
					$role = 'read';
				else :
					$role = 'manage_options';
				endif;
				if (current_user_can($role)) :
					return false;
				else :
					return true;
				endif;
			endif;
		endif;
	}

	// The Kick Off!
	function initiate(){
		if ($this->active() == true) :
			// Theme takeover
			add_filter( 'stylesheet', array( &$this, 'stylesheet') );
			add_filter( 'template', array( &$this, 'template') );
			add_filter( 'theme_root', array( &$this, 'template_dir') );
			add_filter( 'theme_root_uri', array( &$this, 'template_uri') );
			add_filter( 'query_vars', array(&$this, 'add_query_vars'));
			add_action( 'template_redirect', array(&$this, 'custom_css'));

			//Scripts
			add_action( 'wp_print_scripts', array( &$this, 'scripts') );
			add_action( 'wp_print_styles', array( &$this, 'styles') );
		endif;

		//Scripts we can load without the plugin being active
		add_filter( 'script_loader_src', array( &$this, 'remove_src_version') );
		add_filter( 'style_loader_src', array( &$this, 'remove_src_version') );
		add_action( 'admin_print_styles', array(&$this, 'admin_styles'));
		add_action( 'admin_print_scripts', array(&$this, 'scripts'));
		add_action( 'admin_bar_menu', array(&$this, 'active_warning'), 100);
	}
}