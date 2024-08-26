<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

/**
 * This is a controller class to inject custom data to frontend page
 * 
*/

namespace VncslabCompanion\Includes\Controller\ScopeFrontend;

use \WP_Theme;
use \ReflectionObject;

use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;
use VncslabCompanion\Helper\ThemeResourceLoader as ThemeResourceLoader;

class ThemeCustomizer{

    /** 1. Define variables & constant */
    public static $THEME_NAME;
    // public static $THEME_PROPERTIES;

    // A comprehensive instance of the current theme
    public static WP_Theme $CURRENT_THEME;

    /** 2. Debug information */
    public Init $pluginInitiator;
    public PluginProperties $localProps;
    public PluginDebugHelper $localDebugger;

    /** 3. Frontend display information*/
    public ThemeResourceLoader $rscLoader;

    /** 2. Constructors & constructor's helper methods  */
    /** 2.1. Constructors */
    public function __construct(){
        /** 1. Troubleshooting information */
        // 1. Load the plugin initiator
        $this->pluginInitiator = Init::$INSTANCE ?? new Init();
        
        // 2. Setup local properties
        $this->set_Local_Properties();

        // 3. Setup local debuggger
        $this->set_Local_Debugger();

        /** 1. Theme activities */
        $this->set_Current_Theme_Data();
    }//__construct

    /** 2.2. Helper methods for constructors */
    /** 2.2.1. Initialize the variable that point to general plugin properties */ 
    public function set_Local_Properties():void{
        $this->localProps =  $this->pluginInitiator::$PLUGIN_PROPERTIES;
    }//setLocalProperties

    /** 2.2.2. setup the custom debugger for plugin */ 
    public function set_Local_Debugger():void{
        $this->localDebugger = $this->pluginInitiator::$PLUGIN_DEBUGGER;
    }//setLocalDebugger

    
    public function set_Current_Theme_Data(){
        self::$CURRENT_THEME = wp_get_theme();

        /** 2. Theme headers are private properties. Need to access with ReflectionObject */
        $reflectedThemeObj = new ReflectionObject( self::$CURRENT_THEME );

        $reflectedThemeHeaders = $reflectedThemeObj->getProperty( 'headers' );
        $reflectedThemeHeaders->setAccessible( true );

        $themeHeaders = $reflectedThemeHeaders->getValue( self::$CURRENT_THEME );

        // $this->localDebugger->write_log_general( self::$CURRENT_THEME );//OK
        // $this->localDebugger->write_log_general( $themeHeaders );//OK
        self::$THEME_NAME = $themeHeaders['Name'] ?? 'Unidentified_Theme';

    }//setCurrentThemeData

    /** 3. Main class operational functions */
    public function register(){
        $this->rscLoader = new ThemeResourceLoader();

    }//register


    /** === Helper methods for main operational functions === */

}//End of class CustomTemplateController Controller definition