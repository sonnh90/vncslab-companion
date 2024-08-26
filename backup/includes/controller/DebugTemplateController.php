<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

/**
 * This is a controller class control the injection of the VNCS Companion Debug Page Template
 * - Compatible with WordPress version 4.7 or higher.
*/

namespace VncslabCompanion\Includes\Controller\ScopeFrontend;

use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Includes\Base\Activator as Activator;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;

use VncslabCompanion\Includes\Controller\ScopeFrontend\AbstractTemplateController as AbstractTemplateController;

use \WP_Post;

class DebugTemplateController extends AbstractTemplateController{

    /** 1. Variables declaration. Better to validate in custom debugs */    
    // 1. Implement the declaration of self::TEMPLATE_RELATIVE_DIR const.
    const TEMPLATE_RELATIVE_DIR = 'includes/template/scope-frontend/';

    /** 2. Constructor */
    /** 2.1. Constructor - override abstract class constructor directly */
    public function __construct(){
        // Run the series of functions defined in the parent Template controller
        // parent::__construct();

        // 1. Setup page template variables
        $this->setGenericTemplateVariables();

        // 2. Set specific template variable
        // $this->setTemplateVariables();

        $this->pluginInitiator = new Init();//OK
        
        // 2. Setup local properties
        $this->setLocalProperties();

        // 3. Setup local debuggger
        $this->setLocalDebugger();
  
    }//__construct

    /** === 2.2. Helper functions for constructor === */
    /** 2.2.1. Initialize the variable that point to general plugin properties */ 
    /** 2.2.1.1. Initialize generic variables for debug template */
    public function setGenericTemplateVariables():void{
        $this->templateList = array();
 
        self::$TEMPLATE_ABSOLUTE_DIR = PluginProperties::$PLUGIN_PATH.self::TEMPLATE_RELATIVE_DIR;

        // 2. Need to call this method in child constructor
        $this->setTemplateVariables();
    }//setGenericTemplateVariables

    /** 2.2.1.2. Initialize specific variables for debug template */
    public function setTemplateVariables():void{
        /** 1. Obtain plugin path info from static properties of the plugin properties */
        self::$TEMPLATE_FILENAME = 'content-debug-template.php';
        /** Prioritize to define template path as the web content is proceeded in the backend */
        self::$TEMPLATE_ABSOLUTE_PATH = self::$TEMPLATE_ABSOLUTE_DIR.self::$TEMPLATE_FILENAME;

        /** 2. Upload a new template entry to the array */
        $this->templateList[ self::$TEMPLATE_ABSOLUTE_PATH ] = __( 'VNCSLAB Debug Template' , 'text-domain' );
    }//setPageTemplageVariables

    /** 2.2.2. Initialize the variable that point to general plugin properties */ 
    public function setLocalProperties():void{
        $this->localProps =  $this->pluginInitiator::$PLUGIN_PROPERTIES;
    }//setLocalProperties

    /** 2.2.3. setup the custom debugger for plugin */ 
    public function setLocalDebugger():void{
        $this->localDebugger = $this->pluginInitiator::$PLUGIN_DEBUGGER;
    }//setLocalDebugger  

    /** 3. Operational functions */
    /** === a. Main functions === **/
    /** 3.1. main operation of the class which will be generated an instance 
     * - Register an instance of this class to the WordPress plugin
     * - Do the works that belong to the responsibility of this instance: 
     * + Inject the debug template for post, page
    */
    public function register():void{
        /** 1. Register the VncsLab Debug Template to the Default WordPress post type: post, page*/
        /** 1.1. Register the VncslabCompanion debug template to all default CPT: post, page 
         * - Add a metabox to the WordPress editor
        */ 
        if( version_compare( floatval( get_bloginfo('version') ), '4.7', '<' ) ){
            add_filter(
                'page_attributes_dropdown_pages_args',
			    array( $this, 'registerTemplateAtCacheForDefaultCPT' )
            );
        } else {
            add_filter( 'theme_page_templates', array( $this, 'addTemplateToDefaultCPTEditor') );  
        }

        /** 1.2. Add a filter to the save post to inject our template into the page cache
         *  - Register the custom debug page template to the current active theme
         *  - Add a filter to the save post to inject out template into the page cache
        **/ 
        add_filter( 'wp_insert_post_data', array( $this, 'registerTemplateAtCacheForDefaultCPT' ) );         
        
        /** 1.3. Include the template path*/
        add_filter( 'template_include', array( $this, 'viewTemplateForDefaultCPT' ) );

        /** 1.4. Add your template to the array */
        // add_filter( 'page_template', array( $this, 'registerTemplateForDefaultCPT' ) );

        /** 2. Register the VncsLab Debug Template to the WordPress custom post type */
        /** - Need to be update */
    }//register

    /** === b. Helper functions === **/

    /** 1. Helper functions for the VncslabCompanion Debug Template */
    /** 1.1 Add debug page template to the default custom post type (post, page) Editor 
     * - This method will be called in 'theme_page_templates' filter hook to inject the Vncslab Debug Theme
    */
    public function addTemplateToDefaultCPTEditor( $templates ){
        global $post;

        // Validate the debug template path:
        // $this->localDebugger->write_log_general( self::$TEMPLATE_ABSOLUTE_PATH );//Log OK
        // $this->localDebugger->write_log_general(  );

        if( $post instanceof WP_Post ){

            if(  in_array( $post->post_type, array( 'post','page' ), false ) ){
                // return array_merge( $templates, $this->templateList );
                $templates[ self::$TEMPLATE_ABSOLUTE_PATH ] = __( 'VNCSLABCP Debug Template' , 'text-domain' );
            }// End of in_array( $post->post_type, array( 'post','page' ), false )

        }//$post instanceof WP_Post       

        return $templates;
    }//addTemplateToDefaultCPTEditor

    /** 1.2. Register the VncslabCompanion Debug Template to the current active theme */
    public function registerTemplateAtCacheForDefaultCPT( $attributes ){
        $cacheKey = 'page-templates-'.md5( get_theme_root().'/'.get_stylesheet() );

        $currentTemplates = wp_get_theme()->get_page_templates();
        if( empty( $currentTemplates ) ){ 
            $currentTemplates = array(); 
        }

        wp_cache_delete( $cacheKey, 'themes' );

        $newTemplates = array_merge( $currentTemplates, $this->templateList );

        wp_cache_add( $cacheKey, $newTemplates, 'themes' , 1000 );

        // No value logged
        // $this->localDebugger->write_log_general( $newTemplates );


        return $attributes;
    }//registerTemplateAtCacheForDefaultCPT

    /** 1.3. Include in the template path selection in default CPT editor */
    public function viewTemplateForDefaultCPT( $templates ){
        global $post;

        if( !$post ) { return $templates; }

        if( in_array( $post->post_type, array( 'post','page' ), false ) ){
            
            $metaInfo = get_post_meta( $post->ID );
            // Validate the metadata information obtained from the global variable $post
            $registeredTemplate = $metaInfo['_wp_page_template'][0];

            if( empty( $registeredTemplate ) ) return $templates;

            if ( str_contains( $registeredTemplate, self::$TEMPLATE_ABSOLUTE_PATH ) ){            
                $templates = self::$TEMPLATE_ABSOLUTE_PATH;
            } 
        }        
        
        return $templates;
    }//viewTemplateForDefaultCPT

    public function registerTemplateForDefaultCPT( $template ){
        global $post;

        // Validate the debug template path:
        // $this->localDebugger->write_log_general( self::$TEMPLATE_ABSOLUTE_PATH );//Log OK

        if( $post instanceof WP_Post ){

            if(  in_array( $post->post_type, array( 'post','page' ), false ) ){
                // return array_merge( $templates, $this->templateList );
                $template = self::$TEMPLATE_ABSOLUTE_PATH;
            }// End of in_array( $post->post_type, array( 'post','page' ), false )

        }//$post instanceof WP_Post       

        return $template;
    }//registerTemplateForDefaultCPT

    /** 3.3. Operation of registering template for the WordPress custom post types */
    /** 3.2.1 Add the VncslabCompanion template to the WordPress's CPT Editor 
     * - This method will be called in 'theme_page_templates' filter hook 
     *  to inject the Vncslab Debug Theme
    */
    public function addTemplateToCPTEditor( $templates ){}//addTemplateToCPTEditor

    /** 3.2.2. Register the VncslabCompanion Template to the current active theme */
    public function registerTemplateAtCacheForCPT( $attributes ){}//registerTemplateForCPT

    /** 3.2.3. Include in the template path selection in default CPT editor */
    public function viewTemplateForCPT( $templates ){}//viewTemplateForCPT

}//End of class DebugTemplateController Controller definition
