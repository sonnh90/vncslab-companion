<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

namespace VncslabCompanion\Helper;

use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;
class ThemeResourceLoader{
    /** 1. Variable declarations */
    /** 1.1. Constant */
    const BLOCKSY_EXTRA_STYLES_FILENAME = 'theme-blocksy-extra-styles.css';

    /** 1.2. Debug information */
    public Init $pluginInitiator;
    public PluginProperties $localProps;
    public PluginDebugHelper $localDebugger;

    /** 1.3. Resources path */
    public static $BLOCKSY_EXTRA_STYLE_PATH;
    public static $BLOCKSY_EXTRA_SCRIPT_PATH;

    // const BLOCKSY_EXTRA_STYLES_RELATIVE_PATH = 'assets/scope-frontend/css/theme-blocksy'

    /** 2. Constructor */
    public function __construct(){
        /** 1. Troubleshooting information */
        // 1. Load the plugin initiator
        $this->pluginInitiator = Init::$INSTANCE ?? new Init();
        
        // 2. Setup local properties
        $this->setLocalProperties();

        // 3. Setup local debuggger
        $this->setLocalDebugger();

        /** 2. Setup local properties */
        $this->setThemeResourcesInfo();
    }//__construct

    /** 2.2. Helper methods for constructor */
    /** 2.2.1. Initialize the variable that point to general plugin properties */ 
    public function setLocalProperties():void{
        $this->localProps =  $this->pluginInitiator::$PLUGIN_PROPERTIES;
    }//setLocalProperties

    /** 2.2.2. setup the custom debugger for plugin */ 
    public function setLocalDebugger():void{
        $this->localDebugger = $this->pluginInitiator::$PLUGIN_DEBUGGER;
    }//setLocalDebugger

    public function setThemeResourcesInfo(){
        
    }//setThemeResourcesInfo

    /** 3. Operational function for */


}//ThemeResourceLoader
