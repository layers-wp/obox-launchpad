<?php
/*
	Plugin Name: Launchpad by Obox
	Plugin URI: http://obox-design.com
	Description: The best looking maintenance plugin on the net
	Author: Obox Design
	Version: 1.0.8
	Author URI: http://www.obox-design.com
*/

define("LAUNCHPADDIR", ABSPATH."wp-content/plugins/launchpad-by-obox/");

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
