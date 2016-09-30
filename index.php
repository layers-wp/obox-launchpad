<?php
/*
	Plugin Name: Launchpad - Coming Soon & Maintenance Mode Plugin
	Plugin URI: http://oboxthemes.com
	Description: Deploys a beautiful, responsive Coming Soon or Down for Maintenance page for your site. Collect emails, change styles, show social links and more
	Author: Obox Themes
	Version: 1.0.13
	Author URI: http://oboxthemes.com
*/

define( 'LAUNCHPADDIR',  plugin_dir_path( __FILE__ ) );
define( 'LAUNCHPADURI', untrailingslashit( plugin_dir_url( __FILE__ ) )  );

// Kick off Plugin
function apollo_includes(){
	include_once ("functions/load-includes.php");
	$launchpad = new apollo_launchpad();
	$launchpad->initiate();
}
add_action("plugins_loaded", "apollo_includes");

function apollo_admin(){
	$settings = new apollo_launchpad_settings();
	$settings->init();
}
add_action("plugins_loaded", "apollo_admin");

function apollo_activate() {
	add_option('apollo_do_activation_redirect', true);
}

function apollo_redirect() {
	if (get_option('apollo_do_activation_redirect', false)) {
		delete_option('apollo_do_activation_redirect');
		wp_redirect(admin_url('admin.php?page=apollo_general_settings'));
	}
}
register_activation_hook(__FILE__, 'apollo_activate');
add_action('admin_init', 'apollo_redirect');
