<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

 namespace VncslabCompanion\Helper\WpFrontEnd;

use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;

class WpPostResourceLoader{
    /** 1. Variable declarations */
    /** 1.1. Constant */
    /** 1.1.1. Resources information */
    /** a. Extra resources for WP Post 4079 */
    const WP_POST_EXTRA_STYLES_FILENAME = 'wp-post-extra-styles.css';
    const WP_POST_EXTRA_STYLES_HANDLER = 'vncslab-wp-post-extra-styles';
    const WP_POST_EXTRA_SCRIPTS_FILENAME = 'wp-post-extra-scripts.js';
    const WP_POST_EXTRA_SCRIPTS_HANDLER = 'vncslab-wp-post-extra-scripts';

    /** b. Extra resources for WP Post 4129 */
    const WP_POST_4129_EXTRA_STYLES_FILENAME = 'wp-post-4129-extra.css';
    const WP_POST_4129_EXTRA_STYLES_HANDLER = 'vncslab-wp-post-4129-extra-styles';
    const WP_POST_4129_EXTRA_SCRIPTS_FILENAME = 'wp-post-4129-extra.js';
    const WP_POST_4129_EXTRA_SCRIPTS_HANDLER = 'vncslab-wp-post-4129-extra-scripts';

    /** 1.1.2. Special posts to be used as condition */
    /** a. Post ID */
    const WP_POST_EXAMINED_LIST = [ 4079, 4129 ];

    /** 1.2. Debug information */
    public Init $pluginInitiator;
    public PluginProperties $localProps;
    public PluginDebugHelper $localDebugger;

    /** 1.3. Resources path */
    /** 1.3.1. File path for WP Post 4079 */
    //public static $BLOCKSY_EXTRA_STYLE_PATH;
    //public static $BLOCKSY_EXTRA_SCRIPT_PATH;
    public static $WP_POST_EXTRA_STYLES_PATH;
    public static $WP_POST_EXTRA_SCRIPTS_PATH;

    /** 1.3.2. File Path for WP Post 4129 */
    public static $WP_POST_4129_EXTRA_STYLES_PATH;
    public static $WP_POST_4129_EXTRA_SCRIPTS_PATH;

    // const BLOCKSY_EXTRA_STYLES_RELATIVE_PATH = 'assets/scope-frontend/css/theme-blocksy'

    /** 2. Constructor */
    public function __construct(){
        /** 1. Troubleshooting information */
        // 1.1. Load the plugin initiator
        $this->pluginInitiator = Init::$INSTANCE ?? new Init();
        
        // 1.2. Setup local properties
        $this->setLocalProperties();

        // 1.3. Setup local debuggger
        $this->setLocalDebugger();

        /** 2. Setup local properties */
        $this->setPostResourcesInfo();

        /** 3. Run the main functions */
        // $this->load_Extra_Resources_If_Post_4079();
        /** Hook to the_post - right after the WP Query is executed */ 
        add_action( 'the_post' , [$this,'load_Extra_Resources_If_Post_4079'] );//OK but not optimized
        add_action( 'the_post' , [$this,'load_Extra_Resources_If_Post_4129'] );//OK but not optimized        
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

    public function setPostResourcesInfo(){
        /** 20240816 Load extra resources for a specific posts */
        /** 1. Set the resources information - extra styles & scripts files for WP posts */
        /** 1.1. For Post 4079 - display for only desktop */
        self::$WP_POST_EXTRA_STYLES_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_FRONTEND_ROOT_DIR,
            PluginProperties::CSS_ROOT_DIR, 
            self::WP_POST_EXTRA_STYLES_FILENAME
        );
        
        self::$WP_POST_EXTRA_SCRIPTS_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_FRONTEND_ROOT_DIR,
            PluginProperties::JS_ROOT_DIR, 
            self::WP_POST_EXTRA_SCRIPTS_FILENAME
        );

        /** 1.2. For Post 4129 - display for both desktop & mobile */
        self::$WP_POST_4129_EXTRA_STYLES_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_FRONTEND_ROOT_DIR,
            PluginProperties::CSS_ROOT_DIR, 
            self::WP_POST_4129_EXTRA_STYLES_FILENAME
        );
        
        self::$WP_POST_4129_EXTRA_SCRIPTS_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_FRONTEND_ROOT_DIR,
            PluginProperties::JS_ROOT_DIR, 
            self::WP_POST_4129_EXTRA_SCRIPTS_FILENAME
        );

    }//setThemeResourcesInfo
    
    /** 3. Operational function for WP Post resource loader class */
    /** 3.1. Load the extra resources for WP Post if match requirement
     * - Binding this function at "the_post" hook
    */

    /** 3.1.1. Post 4079 */
    public function load_Extra_Resources_If_Post_4079(){
        /** 20240816 load extra resources if post ID = 4079*/
        global $post; // WP_POST type
        // $this->localDebugger->write_log_general( $post ); //OK 

        /** 1. Add customizing activities after all plugins are loaded */
        add_action( 'after_setup_theme', [ $this, 'register_Extra_Resources_To_WP_posts'], 100 );

        if( 4079 == $post->ID ){
        //if( in_array( $post->ID , self::WP_POST_EXAMINED_LIST) ){
            // $this->localDebugger->write_log_general( $post->ID );
            add_action( 'template_redirect', [ $this,'enqueue_Extra_Resources_To_WP_posts' ] );
        }// in_array( $post->ID , self::WP_POST_EXAMINED_LIST)

    }//load_Extra_Resources_If_Post_4079

    /** --- Helper functions for the main operational functions --- */
    public function register_Extra_Resources_To_WP_posts(){
        /** 2. Register the custom styles & scripts for the custom product pages */
        /** 2.1. For default WP Post 4079 */
        /** 2.1.1. Register the custom styles */
        wp_register_style( 
            self::WP_POST_EXTRA_STYLES_HANDLER, 
            self::$WP_POST_EXTRA_STYLES_PATH, 
            [], '1.0.1', 'all'
        );

        /** 2.1.2. Register the custom scripts */
        wp_register_script( 
            self::WP_POST_EXTRA_SCRIPTS_HANDLER, 
            self::$WP_POST_EXTRA_SCRIPTS_PATH,
            [], '1.0.1', true
        );

    }//register_Extra_Resources_To_WP_posts

    public function enqueue_Extra_Resources_To_WP_posts(){
        // global $post; # WP_POST

        /** 2. Enqueue extra styles & scripts  */
        /** 2.1. Enqueue the custom styles */
        wp_enqueue_style( 
            self::WP_POST_EXTRA_STYLES_HANDLER, 
            self::$WP_POST_EXTRA_STYLES_PATH, 
            [], '1.0.1', 'all'
        );

        /** 2.2. Enqueue the custom scripts */
        wp_enqueue_script( 
            self::WP_POST_EXTRA_SCRIPTS_HANDLER, 
            self::$WP_POST_EXTRA_SCRIPTS_PATH,
            [], '1.0.1', true
        );
    }//enqueue_Extra_Resources_To_WP_posts

    /** 3.1.2. WP Post 4129 */
    public function load_Extra_Resources_If_Post_4129(){
        /** 20240816 load extra resources if post ID = 4129*/
        global $post; // WP_POST type

        add_action( 'after_setup_theme', [ $this, 'register_WP_Post_4129_Resources'], 100 );

        if( 4129 == $post->ID ){
            add_action( 'template_redirect', [ $this,'enqueue_WP_Post_4129_Resources' ] );
        }
    }//load_Extra_Resources_If_Post_4129


    public function register_WP_Post_4129_Resources(){
        /** 2.2. For the default WP Post 4129 */
        /** 2.2.1. Register the custom styles */
        wp_register_style( 
            self::WP_POST_4129_EXTRA_STYLES_HANDLER, 
            self::$WP_POST_4129_EXTRA_STYLES_PATH, 
            [], '1.0.1', 'all'
        );

        /** 2.2.2. Register the custom scripts */
        wp_register_script( 
            self::WP_POST_4129_EXTRA_SCRIPTS_HANDLER, 
            self::$WP_POST_4129_EXTRA_SCRIPTS_PATH,
            [], '1.0.1', true
        );
    }//register_WP_Post_4129_Resources


    public function enqueue_WP_Post_4129_Resources(){
        /** 2.1. Enqueue the custom styles */
        wp_enqueue_style( 
            self::WP_POST_4129_EXTRA_STYLES_HANDLER, 
            self::$WP_POST_4129_EXTRA_STYLES_PATH, 
            [], '1.0.1', 'all'
        );

        /** 2.2. Enqueue the custom scripts */
        wp_enqueue_script( 
            self::WP_POST_4129_EXTRA_SCRIPTS_HANDLER, 
            self::$WP_POST_4129_EXTRA_SCRIPTS_PATH,
            [], '1.0.1', true
        );
    }//enqueue_WP_Post_4129_Resources

}//ThemeResourceLoader

