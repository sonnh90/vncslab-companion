<?php 
/** 
 * @package VNCSLAB-Companion
 * @version 1.0.1
 */

/*
Plugin Name: vncslab-companion
Plugin URI: http://vncslab.local.info
Description: This is my 3rd WordPress plugin - following Aleccad plugin development tutorial.
Author: Leon Nguyen
Author URI: https://www.facebook.com/leon.nguyen.vn
Version: 1.0.1
License: GPLv2 or later
Text Domain: devsunshine-plugin
*/

use VncslabCompanion\Includes\Base\Activator as Activator;
use VncslabCompanion\Includes\Base\Deactivator as Deactivator;
use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Helper\PluginClassLoader as PluginClassLoader;


/** 1. Security check whether this plugin is initialized in a proper manner */
defined( 'ABSPATH' ) or die( 'You are not authenticated to use this plugin !' );

if( !function_exists('add_action') ){
    echo '<p>You are not authenticated to access this file !</p>';
    exit;
}//function_exists('add_action')

/* 2. Include PHP vendor library & custom classnames using composer autoload:
 * - PHP vendor library (mPdf, MobileDetect ...)
 * - Custom classnames (Plugin_Activator, Plugin_Deactivator ...)
 *  */
if( file_exists( dirname(__FILE__).'/vendor/autoload.php' ) ){
    /* Also include the file with autoload */
    require_once( dirname(__FILE__).'/vendor/autoload.php' );
}//file_exists( dirname(__FILE__).'vendor/autoload.php'

/* 3. Define several global variables use across the current plugin (Devsunshine plugin scope) */

/** Some common global variables used for the plugin 
 * - These variables can be put in PluginProperties
 * PLUGIN_PATH: C:\WebPlatform\apache24\htdocs\vnlabwin\wp-content\plugins\sunsetpro\ *
 * PLUGIN_URL: http://vnlabwin.local.info/wp-content/plugins/sunsetpro/
 * PLUGIN: sunsetpro/sunsetpro.php (plugin basename)
 * **/

define('PLUGIN_PATH', plugin_dir_path(__FILE__)); //OK
define('PLUGIN_URL', plugin_dir_url(__FILE__)); //OK
define('PLUGIN', plugin_basename(__FILE__)); //OK

/** 4. Register activation hooks, deactivation hooks for the current plugin */
register_activation_hook( __FILE__, 'activate_vncslab_companion_plugin' );
function activate_vncslab_companion_plugin(){
	/**
	 * Initialize an Init object in the Activator Class. The Init instance includes
	 * - Plugin properties
	 * - Plugin debug properties
	 * */ 
    Activator::activate();
}

register_deactivation_hook( __FILE__, 'deactivate_vncslab_companion_plugin' );
function deactivate_vncslab_companion_plugin(){
    Deactivator::deactivate();
}

/** 4.2. Custom function to enable write information to debug.log */
/* if ( !function_exists('vncslab_write_wp_log') ) {
	function vncslab_write_wp_log ( $log )  {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
} */

/** 5. Start initialize all plugins services if exists the Init class files **/
if( class_exists( PluginClassLoader::class ) ){
    // 2. Initialize all instances of all necessary services 
    $pluginClassLoader = new PluginClassLoader();

	// 2. Custom autoload beside Composer autoload - temporary pending because of using composer already
	// PluginClassLoader::IncludePhpFilesFromPluginDirectories();

	if( class_exists( Init::class ) ){
		// 1. Initialize all instances of all necessary services 
		// Init::register_services();//Used to work
		Init::register_frontend_services();
	
		// 2. Manually add PluginClassLoader to the Init::$PLUGIN_INSTANCE_LIST;
		// Init::$PLUGIN_INSTANCE_LIST[ PluginClassLoader::class ] = $pluginClassLoader;

		// 2. Manually add PluginClassLoader to the Init::$FRONTEND_INSTANCE_LIST
		Init::$FRONTEND_INSTANCES_LIST[ PluginClassLoader::class ] = $pluginClassLoader;
	}
	
}// End of class_exists( PluginClassLoader::class )


