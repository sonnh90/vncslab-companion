<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

namespace VncslabCompanion\Includes;

use \ReflectionClass;

use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginClassLoader as PluginClassLoader;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;

use VncslabCompanion\Includes\Controller\ScopeFrontend\DebugTemplateController as DebugTemplateController;
use VncslabCompanion\Includes\Controller\ScopeFrontend\CustomTemplateController as CustomTemplateController;
use VncslabCompanion\Includes\Controller\ScopeFrontend\ThemeCustomizer as ThemeCustomizer;
use VncslabCompanion\Includes\Controller\ScopeFrontend\WpPostDisplayController as WpPostDisplayController;
use VncslabCompanion\Includes\Controller\ScopeFrontend\WooCommerce\WooCommerceCustomizer as WooCommerceCustomizer;


/** This class is dedicated to initialize all services (instances) 
 * which may be need for the plugin operation
 * 
 */
final class Init{

    public static Init $INSTANCE;

    public static array $PLUGIN_INSTANCE_LIST;
    public static array $ADMIN_INSTANCES_LIST;
    public static array $EDITOR_INSTANCES_LIST;
    public static array $FRONTEND_INSTANCES_LIST;
    public static PluginProperties $PLUGIN_PROPERTIES;
    public static PluginDebugHelper $PLUGIN_DEBUGGER;

    public function __construct(){
        // 1. Initialize the plugin properties
        self::$PLUGIN_PROPERTIES = self::$PLUGIN_PROPERTIES ?? new PluginProperties();
        // 2. Initialize the plugin debugger
        self::$PLUGIN_DEBUGGER = self::$PLUGIN_DEBUGGER ?? new PluginDebugHelper();
    }//__construct

    /** Initialize an instance - all services from all scopes
     * 1. Admin scope
     * 2. Editor scope
     * 3. Frontend scope
     */

    public static function register_plugin_services(){
        self::register_admin_services();
        self::register_editor_services();
        self::register_frontend_services();
    }//register_plugin_services

    public static function register_admin_services(){
        return false;
    }//register_admin_services

    public static function register_editor_services(){
        return false;
    }//register_editor_services

    public static function register_frontend_services(){
        $servicesList = self::get_frontend_services();

        foreach( $servicesList as $serviceItem ){
            // 1. Initialize an instance of the dedicated service
            $instance = self::instantiate( $serviceItem );

            // 2. Run all actions defined in the corresponding services in "register" method
            if( method_exists( $instance , 'register' ) ){
                $instance->register();
            }//method_exists( $instance , 'register' )

            self::$FRONTEND_INSTANCES_LIST[ $serviceItem ] = $instance;

        }// Endforeach $servicesList as $serviceItem
    }//register_frontend_services

    /* public static function register_services(){
        $serviceList = self::get_services();

        // serviceName is class name that will be initialized 
        foreach( $serviceList as $serviceName ){
            // 1. Initialize an object of registered class
            $serviceInstance = self::instantiate( $serviceName );

            // 2. Run the corresponding service role in each instance initialized before
            if( method_exists($serviceInstance, 'register') ){
                $serviceInstance->register();
            }
            
            // Put a reference to a static array for managing all created instances
            self::$PLUGIN_INSTANCE_LIST[ $serviceName ] = $serviceInstance;
        }// End of iterating through all service lists 
        
        // Validate the service list initialized & registered - stored in self::$PLUGIN_INSTANCE_LIST
        // self::$PLUGIN_DEBUGGER->write_log_general( self::$PLUGIN_INSTANCE_LIST ); 
    }//register_services */

    // Define a list of instance that will be initialized to run plugin
    /****
     * Make sure the order of the instance initialized
     * 1. Plugin properties
     * 2. Plugin Debug helper
     * 3. Plugin functions
     * - Inject debug template for WP default post type: page, post
     * 
     * II. The PluginClassLoader class's instance is initialized separately,
     */
    public static function get_services(): array{
        return array(
            PluginProperties::class,            
            PluginDebugHelper::class,
            DebugTemplateController::class,
            CustomTemplateController::class,
        );
    }//get_services

    public static function get_admin_services(){
        return array(
            PluginProperties::class,            
            PluginDebugHelper::class,
        );
    }//get_admin_services

    public static function get_editor_services(){
        return array(
            PluginProperties::class,            
            PluginDebugHelper::class,
        );
    }//get_editor_services


    /** Change log
     * 1. 2024-08-01: Temporary remove WooCommerce Customizers
     *  Stop initializing the WooCommerceCustomizer instance - WooCommerceCustomizer::class
     * 2. 2024-08-16: Load extra resources for WP Post if match requirement
     * Adding an instance WpPostDisplayController::class,
     */
    public static function get_frontend_services(){
        return array(
            PluginProperties::class,            
            PluginDebugHelper::class,
            ThemeCustomizer::class,                      
            DebugTemplateController::class,
            CustomTemplateController::class,   
            WpPostDisplayController::class,           
        );
    }//get_frontend_services

    /* Initialize an object of each registered service.
    * @param class $class : class names from the services array
    * @return return an instance of the parsing class
    * ***/
    private static function instantiate( $serviceName ){

        return new $serviceName();

    }//instantiate()

    // Check if a class is a singleton 
}//Init