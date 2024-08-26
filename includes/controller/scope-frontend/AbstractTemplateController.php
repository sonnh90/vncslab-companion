<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

/**
 * 1. This is a controller class control the injection of the VNCS Companion Debug Page Template
 * - Compatible with WordPress version 4.7 or higher.
 * - Apply for injecting single template to all WP default post type / custom post type
 * => Extend this abstract class to implement for specific templates
 * 
 * 2. Referential material:
 * https://wordpress.stackexchange.com/questions/3396/create-custom-page-templates-with-plugins
 * https://www.wpexplorer.com/wordpress-page-templates-plugin/
 * 
*/

namespace VncslabCompanion\Includes\Controller\ScopeFrontend;

use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Includes\Base\Activator as Activator;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;

abstract class AbstractTemplateController{
    /** 1. Variables declaration. Better to validate in custom debugs */
    /** Force all child class of GenericTemplateController re-initialize the TEMPLATE_RELATIVE_DIR const 
     *  - Example :
     *  const TEMPLATE_RELATIVE_DIR = 'includes/template/scope-frontend/'; */
    
    const TEMPLATE_RELATIVE_DIR = self::TEMPLATE_RELATIVE_DIR;
    public static string $TEMPLATE_ABSOLUTE_DIR;
    public static string $TEMPLATE_FILENAME; 
    public static string $TEMPLATE_RELATIVE_PATH; //OK
    public static string $TEMPLATE_ABSOLUTE_PATH; //OK
    public static string $TEMPLATE_ABSOLUTE_URI; // Not necessary

    public array $templateList;
    public Init $pluginInitiator;
    public PluginProperties $localProps;
    public PluginDebugHelper $localDebugger;

    /** 2. Constructor */
    /** 2.1. Main constructors*/

    public function __construct(){
        // 1. Setup page template variables
        $this->set_Generic_Template_Variables();

        // 2. Set specific template variable
        $this->set_Template_Variables();

        $this->pluginInitiator = new Init();//OK
        
        // 2. Setup local properties
        $this->set_Local_Properties();

        // 3. Setup local debuggger
        $this->set_Local_Debugger();
    }//__construct

    /** 2.2. Helper methods for constructor */
    /** 2.2.1. Set template variables */
    abstract public function set_Generic_Template_Variables():void;//setTemplateVariables
    abstract public function set_Template_Variables():void;//setTemplateVariables
    /** 2.2.2. Initialize the variable that point to general plugin properties */ 
    abstract public function set_Local_Properties():void;//setTemplateVariables
    /** 2.2.3. setup the custom debugger for plugin */ 
    abstract public function set_Local_Debugger():void;//setTemplateVariables

    /** 3. Operational functions */
    /** 3.1. Main operation of the template registration to WordPress */
            /** 1. Register the VncsLab Companion Template to all WordPress's default post types */
        /** 1.1. Register the VncslabCompanion debug template to all default CPT: post, page 
         * - Add a metabox to the WordPress editor
        */
        // add_filter( 'theme_page_templates', array( $this, 'addTemplateToDefaultCPTEditor') );          

        /** 1.2. Add a filter to the save post to inject our template into the page cache
         *  - Register the custom debug page template to the current active theme
         *  - Add a filter to the save post to inject out template into the page cache
        **/ 
        // add_filter( 'wp_insert_post_data', array( $this, 'registerTemplateForDefaultCPT' ) );          
            
        /** 1.3. Include the template path*/
        // add_filter( 'template_include', array( $this, 'viewTemplateForDefaultCPT' )  );

         /** 2. Register the VncsLab Companion Template to WordPress's custom post types */
                 /** 1.1. Register the VncslabCompanion debug template to all default CPT: post, page 
         * - Add a metabox to the WordPress editor
        */
        // add_filter( 'theme_page_templates', array( $this, 'addDTemplateToCPTEditor') );          

        /** 1.2. Add a filter to the save post to inject our template into the page cache
         *  - Register the custom debug page template to the current active theme
         *  - Add a filter to the save post to inject out template into the page cache
        **/ 
        // add_filter( 'wp_insert_post_data', array( $this, 'registerTemplateForCPT' ) );          
            
        /** 1.3. Include the template path*/
        // add_filter( 'template_include', array( $this, 'viewTemplateForCPT' )  );
    abstract public function register():void;

    /** 3.2. Operation of registering template for the WordPress default post type: post, page */
    /** 3.2.1 Add the VncslabCompanion template to the WordPress's default custom post type (post, page) Editor 
     * - This method will be called in 'theme_page_templates' filter hook 
     *  to inject the Vncslab Debug Theme.
     * + Do not type hint any operational function because they will be bound to WordPress's filter hooks
    */
    abstract public function add_Template_To_Default_CPT_Editor( $templates );//addTemplateToDefaultCPTEditor

    /** 3.2.2. Register the VncslabCompanion Template to the current active theme */
    abstract public function register_Template_At_Cache_For_Default_CPT( $attributes  );//registerTemplateForDefaultCPT

    /** 3.2.3. Include in the template path selection in default CPT editor */
    abstract public function view_Template_For_Default_CPT( $templates );//viewTemplateForDefaultCPT


    /** 3.3. Operation of registering template for the WordPress custom post types */
        /** 3.2.1 Add the VncslabCompanion template to the WordPress's CPT Editor 
     * - This method will be called in 'theme_page_templates' filter hook 
     *  to inject the Vncslab Debug Theme
    */
    abstract public function add_Template_To_CPT_Editor( $templates );//addDTemplateToCPTEditor

    /** 3.2.2. Register the VncslabCompanion Template to the current active theme */
    abstract public function register_Template_At_Cache_For_CPT( $attributes );//registerTemplateForDefaultCPT

    /** 3.2.3. Include in the template path selection in default CPT editor */
    abstract public function view_Template_For_CPT( $templates );//viewTemplateForDefaultCPT


}//End of GenericTemplateController class definition