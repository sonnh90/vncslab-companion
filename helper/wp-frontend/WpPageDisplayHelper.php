<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */


 namespace VncslabCompanion\Helper\WpFrontEnd;

 use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;

use DOMWrap\Document as Document;
use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;
use DOMDocument;

class WpPageDisplayHelper{
    /** 1. Const & Variables declarations*/

    /** 1.1. Relevant WP pages information */

    /** 1.2. Debug information */
    public Init $pluginInitiator;
    public PluginProperties $localProps;
    public PluginDebugHelper $localDebugger;


    /** 2. Constructors */
    public function __construct(){
        /** 1. Troubleshooting information */
        // 1.1. Load the plugin initiator
        $this->pluginInitiator = Init::$INSTANCE ?? new Init();
        
        // 1.2. Setup local properties
        $this->setLocalProperties();

        // 1.3. Setup local debuggger
        $this->setLocalDebugger();

        /** 2. Setup local properties */

        /** 3. Run the main functions */
        
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

    /** 3. Operational functions */

    /** 3.1. Main operational functions */

    /** 3.2. Helper operational functions */


}//WpPageDisplayHelper