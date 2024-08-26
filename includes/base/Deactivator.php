<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

namespace VncslabCompanion\Includes\Base;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Includes\Base\Activator as Activator;
use VncslabCompanion\Includes\Init as Init;

class Deactivator{
    /** 2. Constructor 
     * Unset all objects & database entries created by VNCS companion plugins
    */
    public function __construct(){
        
    }

    public function unsetPluginActivator(){
        // 1. Unset the property $PLUGIN_INITIATOR
        if( isset( Activator::$PLUGIN_INITIATOR ) ) {
            unset( Activator::$PLUGIN_INITIATOR );
        }

        // 2. Remove initialization method in Activatoin class
        // remove_action( 'before_setup_theme', array( Activator::class , 'setupPluginActivator' ) );
    }

    public static function deactivate(){
        // Remove rewrite rules & re-create new rewrite rules for plugin
        flush_rewrite_rules();

        if( isset( Activator::$PLUGIN_INITIATOR ) ) {
            unset( Activator::$PLUGIN_INITIATOR );
        }

    }//deactivate

}//Deactivator