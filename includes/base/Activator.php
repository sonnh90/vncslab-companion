<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

namespace VncslabCompanion\Includes\Base;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Includes\Init as Init;

class Activator {

    public static Init $PLUGIN_INITIATOR;
    public static Activator $PUBLIC_INSTANCE;
    public function __construct(){
        /** 1. Initialize an instance of plugin properties & plugin debug information 
         * Setup the Plugin Activator right after setting up theme */ 
        // add_action( 'after_setup_theme', array( $this, 'setupPluginActivator' ) );
        // add_action( 'setup_theme', array( self::class, 'setupPluginActivator' ) );
        $this->setupPluginActivator();
    }

    /* // Use for array $this
    public static function setupPluginActivator(){
        self::$PLUGIN_INITIATOR = self::$PLUGIN_INITIATOR ?? new Init();
    }//setPluginActivator */

    public function setupPluginActivator(){
        self::$PLUGIN_INITIATOR = new Init();
        // self::$PUBLIC_INSTANCE = new Activator();

        // Update Init instance list
        self::$PLUGIN_INITIATOR::$PLUGIN_INSTANCE_LIST[ self::class ] = self::$PLUGIN_INITIATOR;
    }//setPluginActivator

    /* public function getPublicInstance(){
        return self::$PUBLIC_INSTANCE ?? new self();
    } */

    public static function activate(){
        // Remove rewrite rules & re-create new rewrite rules for plugin
        flush_rewrite_rules();

        // Initialize an Activator object - calling things fron constructor
        self::$PUBLIC_INSTANCE = new self();
    }//activate

}//End of class Activator