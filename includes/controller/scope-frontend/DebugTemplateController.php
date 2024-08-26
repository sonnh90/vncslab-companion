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
    // const TEMPLATE_FILENAME = 'content-debug-template.php';
    const DEBUG_TEMPLATE_FILENAME = 'content-debug-template.php';
    public static string $DEBUG_TEMPLATE_RELATIVE_PATH; //OK
    public static string $DEBUG_TEMPLATE_ABSOLUTE_PATH; //OK

    /** 2. Constructor */
    /** 2.1. Constructor - override abstract class constructor directly */
    public function __construct(){
        // Run the series of functions defined in the parent Template controller
        parent::__construct(); 
    }//__construct

    /** === 2.2. Helper functions for constructor === */
    /** 2.2.1. Initialize the variable that point to general plugin properties */ 
    /** 2.2.1.1. Initialize generic variables for debug template */
    public function set_Generic_Template_Variables():void{
        $this->templateList = array();
 
        self::$TEMPLATE_ABSOLUTE_DIR = PluginProperties::$PLUGIN_PATH.self::TEMPLATE_RELATIVE_DIR;

        // 2. Need to call this method in child constructor
        $this->set_Template_Variables();
    }//setGenericTemplateVariables

    /** 2.2.1.2. Initialize specific variables for debug template */
    public function set_Template_Variables():void{
        /** 1. Obtain plugin path info from static properties of the plugin properties */
        self::$TEMPLATE_FILENAME = 'content-debug-template.php';
        /** Prioritize to define template path as the web content is proceeded in the backend */
        // self::$DEBUG_TEMPLATE_ABSOLUTE_PATH = self::$TEMPLATE_ABSOLUTE_DIR.self::$TEMPLATE_FILENAME;
        self::$DEBUG_TEMPLATE_ABSOLUTE_PATH = self::$TEMPLATE_ABSOLUTE_DIR.self::DEBUG_TEMPLATE_FILENAME;
        // self::$DEBUG_TEMPLATE_RELATIVE_PATH = self::TEMPLATE_RELATIVE_DIR.self::$TEMPLATE_FILENAME;
        self::$DEBUG_TEMPLATE_RELATIVE_PATH = self::$TEMPLATE_ABSOLUTE_DIR.self::DEBUG_TEMPLATE_FILENAME;

        /** 2. Upload a new template entry to the array */
        $this->templateList[ self::$DEBUG_TEMPLATE_ABSOLUTE_PATH ] = __( 'VNCSLAB Debug Template' , 'text-domain' );
        //$this->templateList[ self::$DEBUG_TEMPLATE_RELATIVE_PATH ] = __( 'VNCSLAB Debug Template' , 'text-domain' );
    
    }//setPageTemplageVariables

    /** 2.2.2. Initialize the variable that point to general plugin properties */ 
    public function set_Local_Properties():void{
        $this->localProps =  $this->pluginInitiator::$PLUGIN_PROPERTIES;
    }//setLocalProperties

    /** 2.2.3. setup the custom debugger for plugin */ 
    public function set_Local_Debugger():void{
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
			    array( $this, 'registerTemplateAtCacheForDefaultCPT' ),
                9
            );
        } else {
            /** 
             * 1. Add VNCSLAB debug template to the page template editor
             * */ 
            add_filter( 'theme_page_templates', array( $this, 'add_Template_To_Default_CPT_Editor'), 9 );            
            // add_filter( 'page_template', array( $this, 'addTemplateToDefaultCPTEditor') );

            /** 2. Add VNCSLAB debug template to the page template editor
             * Need to add to the filter hook theme_post_template - as reference below:
             * https://developer.wordpress.org/reference/hooks/theme_page_templates/
             * */ 
            add_filter( 'theme_post_templates', array( $this, 'add_Template_To_Default_CPT_Editor'), 9 );              
            // add_filter( 'single_template', array( $this, 'addTemplateToDefaultCPTEditor') );

        }// End of checking WP version compare

        /** 1.2. Add a filter to the save post to inject our template into the page cache
         *  - Register the custom debug page template to the current active theme
         *  - Add a filter to the save post to inject out template into the page cache
        **/ 
        add_filter( 'wp_insert_post_data', array( $this, 'register_Template_At_Cache_For_Default_CPT' ) );         
        
        /** 1.3. Include the template path*/
        add_filter( 'template_include', array( $this, 'view_Template_For_Default_CPT' ), 99 );

        /** 1.4. Add your template to the array */
        // add_filter( 'page_template', array( $this, 'registerTemplateForDefaultCPT' ) );

        /** 2. Register the VncsLab Debug Template to the WordPress custom post type */
        /** - Need to be update */
    }//register

    /** === b. Helper functions === **/

    /** 1. Helper functions for the VncslabCompanion Debug Template */
    /** 1.1 Add debug page template to the default custom post type (post, page) Editor 
     * - This method will be called in the dedicated filter hook to inject the Vncslab Debug Theme
     * - Add this method to 2 filter hook for post, page:
     * + For page: "theme_page_templates"
     * + For post: "theme_post_templates" 
    */

    public function add_Template_To_Default_CPT_Editor( $templates ){
        // The $templates will have null value here

        $templates[ self::$DEBUG_TEMPLATE_ABSOLUTE_PATH ] = __( 'VNCSLAB Debug Template' , 'text-domain' );

        return $templates;
    }//add_Template_To_Default_CPT_Editor 

    /** 1.2. Register the VncslabCompanion Debug Template to the current active theme */
    public function register_Template_At_Cache_For_Default_CPT( $attributes ){
        //$cacheKey = 'page-templates-'.md5( get_theme_root().'/'.get_stylesheet() );
        $cacheKey = 'page-templates-'.md5( 'vncslab-debug-template'.get_theme_root().'/'.get_stylesheet() );

        // Get the current WP_Theme object:
        $currentTheme = wp_get_theme();

        // 2024-05-10 : try to log the WP_Theme object
        // $this->localDebugger->write_log_general( $currentTheme );// No value

        $currentTemplates = $currentTheme->get_page_templates();

        if( empty( $currentTemplates ) ){ 
            $currentTemplates = array(); 
        }

        // Try to delete the existed cache key:
        wp_cache_delete( $cacheKey, 'themes' );

        $newTemplates = array_merge( $currentTemplates, $this->templateList );

        wp_cache_add( $cacheKey, $newTemplates, 'themes' , 1000 );

        // No value logged
        // $this->localDebugger->write_log_general( $newTemplates );

        return $attributes;
    }//register_Template_At_Cache_For_Default_CPT

    /** 1.3. Include in the template path selection in default CPT editor */
    public function view_Template_For_Default_CPT( $template ){
        global $post;
       
        if( !$post ) { return $template; }        

        if( $post instanceof WP_POST ){

            $isDefaultWPPostTypes = in_array( $post->post_type, array( 'post','page' ), false );

            // $this->localDebugger->write_log_general( $isDefaultWPPostTypes );// OK

            if(  $isDefaultWPPostTypes  ){
                // If the post, page is set with the VNCSLAB Debug Template, the value will exists
                $metaInfo = get_post_meta( $post->ID );                   
                
                if( array_key_exists( '_wp_page_template' , $metaInfo ) ){
                    $registeredTemplateList = $metaInfo['_wp_page_template'];
                    
                    /** Iterate through all registered template lists, check if there is 
                     * any maching item with the VNCSLAB Debug Template
                     */
                    // The registered template should be $metaInfo['_wp_page_template'][0]; 
                    // $registeredTemplate = $metaInfo['_wp_page_template'][0];  

                    foreach( $registeredTemplateList as $registeredTemplate ){
                        if ( str_contains( $registeredTemplate, self::$DEBUG_TEMPLATE_ABSOLUTE_PATH ) ){            
                            $template = self::$DEBUG_TEMPLATE_ABSOLUTE_PATH;
                        } 
                    }//End of iterating through $registeredTemplateList as $registeredTemplate

                }//End of array_key_exists( '_wp_page_template' , $metaInfo)
                
            }//End of "$isDefaultWPPostTypes " 

        }// End of "$post instanceof WP_POST"        

        return $template;
    }//view_Template_For_Default_CPT

    /** 3.3. Operation of registering template for the WordPress custom post types */
    /** 3.2.1 Add the VncslabCompanion template to the WordPress's CPT Editor 
     * - This method will be called in 'theme_page_templates' filter hook 
     *  to inject the Vncslab Debug Theme
    */
    public function add_Template_To_CPT_Editor( $templates ){}//addTemplateToCPTEditor

    /** 3.2.2. Register the VncslabCompanion Template to the current active theme */
    public function register_Template_At_Cache_For_CPT( $attributes ){}//registerTemplateForCPT

    /** 3.2.3. Include in the template path selection in default CPT editor */
    public function view_Template_For_CPT( $templates ){}//viewTemplateForCPT


}//End of class DebugTemplateController Controller definition
