<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

namespace VncslabCompanion\Includes\Controller\ScopeFrontend;

use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Includes\Base\Activator as Activator;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;

use VncslabCompanion\Helper\WpFrontend\WpPostResourceLoader as WpPostResourceLoader;
use VncslabCompanion\Helper\WpFrontend\WpPostDisplayHelper as WpPostDisplayHelper;

class WpPostDisplayController{

    /** 1. Variables & constant for post properties */

    /** 2. Debug information */
    public Init $pluginInitiator;
    public PluginProperties $localProps;
    public PluginDebugHelper $localDebugger;

    /** 3. Frontend display information*/
    public WpPostResourceLoader $rscLoader;
    public WpPostDisplayHelper $displayHelper;

    public function __construct(){
        /** 1. Troubleshooting information */
        // 1. Load the plugin initiator
        $this->pluginInitiator = Init::$INSTANCE ?? new Init();
        
        // 2. Setup local properties
        $this->set_Local_Properties();

        // 3. Setup local debuggger
        $this->set_Local_Debugger();
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

    /** 3. Operational function */
    /** 3.1. Main operational functions */
    /** - Decide the operation of WpPostDisplayController.
     * - The constructor of any ResourceLoader or DisplayHelper will do the jobs
     */
    public function register(){
        /** 1. Run the extra resource loader for WP Post type */
        /** - This will load extra resources if matching the requirement (i.e specific post ID)*/
        $this->rscLoader = new WpPostResourceLoader();

        /** 2. Customize the output HTML of the post content */
        /** - Add lazy load for specific HTML content */
        $this->displayHelper = new WpPostDisplayHelper();
    }//register


    /** 3.2. Helper functions */
    

}//WpPostDisplayController