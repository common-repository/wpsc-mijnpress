<?php
/**
Plugin Name: WPSC MijnPress
Plugin URI: http://www.mijnpress.nl
Description: Developers WordPress framework
Version: 0.0.1
Author: Ramon Fincken
Author URI: http://www.mijnpress.nl
Based on: http://www.mijnpress.nl/blog/plugin-framework/
*/
if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

if(!class_exists('mijnpress_plugin_framework'))
{
	include('mijnpress_plugin_framework.php');
}

class wpsc_mijnpress extends mijnpress_plugin_framework
{
	function __construct()
	{
		$this->showcredits = true;
		$this->showcredits_fordevelopers = true;
		$this->plugin_title = 'WPsc MijnPress';
		$this->plugin_class = 'wpsc_mijnpress';
		$this->plugin_filename = 'wpsc_mijnpress/wpsc_mijnpress.php';
		$this->plugin_config_url = NULL; //'plugins.php?page='.$this->plugin_filename; // If you do not have an admin page: NULL
	}

	function wpsc_mijnpress()
	{
		$args= func_get_args();
		call_user_func_array
		(
		    array(&$this, '__construct'),
		    $args
		);
	}

	
	function init()
	{
		include('c_att.php');
		include('c_parser.php');
	}
	
	function init_admin()
	{
		// perhaps use wp_get_active_and_valid_plugins() ?
		$plugins = get_option('active_plugins');

		// Admin bar frontend fix by disabling autoptimize for admin
    	$required_plugin = 'autoptimize/autoptimize.php';
    	if ( in_array( $required_plugin , $plugins ) ) {	  
    		remove_action('template_redirect','autoptimize_start_buffering',2);	
		}			
	}
	
	function addPluginSubMenu()
	{
		$plugin = new wpsc_mijnpress();
		parent::addPluginSubMenu($plugin->plugin_title,array($plugin->plugin_class, 'admin_menu'),__FILE__);
	}

	/**
	 * Additional links on the plugin page
	 */
	function addPluginContent($links, $file) {
		$plugin = new wpsc_mijnpress();
		$links = parent::addPluginContent($plugin->plugin_filename,$links,$file,$plugin->plugin_config_url);
		return $links;
	}

	/**
	 * Shows the admin plugin page
	 */
	public function admin_menu()
	{
		$plugin = new wpsc_mijnpress();
		$plugin->content_start();		
		
		$plugin->content_end();
	}
}

// Admin only
if(mijnpress_plugin_framework::is_admin())
{
	add_action('admin_menu',  array('wpsc_mijnpress', 'addPluginSubMenu'));
	add_filter('plugin_row_meta',array('wpsc_mijnpress', 'addPluginContent'), 10, 2);
	add_action('init',  array('wpsc_mijnpress', 'init_admin'), 1);
}
add_action('init',  array('wpsc_mijnpress', 'init'), 1);
?>