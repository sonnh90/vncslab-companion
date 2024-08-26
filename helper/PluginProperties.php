<?php 
/** 
 * @Package vncslab-companion
 * - The global $PluginProperties object is initialized at "inc/config-general.php"
 * (included in the beginning of the file functions.php )
 * **/

namespace VncslabCompanion\Helper;

use \SplFixedArray;
use \Exception;
use \StdClass;
use \Mobile_Detect;

class PluginProperties{
    /** I. Define a variable to store unique singleton instance */
    // private static $instance;

    /** 1. Define constant, variables */
    /** 1.1. Constants */
    /** 1.1.1. Set the position for the admin setting pages of the plugin */
    /* - 110 means below all other page in WordPress admin setting page*/
    const VNCSLAB_PARENT_PAGE_POSITION = 110;
    const VNCSLAB_CHILD_PAGE_POSITION = 111;
    const IS_SINGLETON_CLASS = true;
    const PLUGIN_NAME = 'vncslab-companion';

    /** Relative information about resources */
    const RESOURCES_ROOT_DIR = 'assets/';
    const RESOURCES_ADMIN_ROOT_DIR = 'assets/scope-admin/';
    const RESOURCES_EDITOR_ROOT_DIR = 'assets/scope-editor/';
    const RESOURCES_PRELIB_ROOT_DIR = 'assets/scope-prelib/';
    const RESOURCES_FRONTEND_ROOT_DIR = 'assets/scope-frontend/';

    const CSS_ROOT_DIR = 'css/';
    const JS_ROOT_DIR = 'js/';
    const IMAGES_ROOT_DIR = 'images/';
    const FONTS_ROOT_DIR = 'fonts';
    const ICONS_ROOT_DIR = 'icons';


    /** 1.1.2. List a common default mime type */
    const IMAGE_MIME_TYPES = array(
        'image/png', 'image/jpeg', 'image/jpg', 
        'image/webp', 'image/avif', 'image/gif'
    );

    /** 1.2. Plugin variables - within vncslab-companion plugin scope */
    /** 1.2.1. Directory variables */
    // a. Plugin variables
    public static string $PLUGIN_ROOT_DIR_PATH;
    public static string $PLUGIN_ROOT_DIR_URI;

    // For backward compatibility
    /** Example of $PLUGIN_PATH: C:\WebPlatform\apache24\htdocs\vnlabwin\wp-content\plugins\vncslab-companion\ */
    public static string $PLUGIN_PATH;
    /** Example of $PLUGIN_URL: http://vncslab.local.info/wp-content/plugins/vncslab-companion/ */
    public static string $PLUGIN_URL;
    /** Example of $PLUGIN_BASENAME: vncslab-companion/vncslab-companion */
    public static string $PLUGIN_BASENAME;
    public static string $PLUGIN_NAME;

    // b. Themes variable
    public static $THEME_ROOT_DIR_PATH;
    public static $THEME_ROOT_DIR_URI;

    /** 1.2.2. Operational variables */
    public static $DEFAULT_POST_FORMATS; //SplFixedArray data type - for performance optimization
    public static $QUERY_POST_FORMATS;

    /** 1.2.3. Variables to store a set of Admin setting pages*/
    public static array $WP_ADMIN_PAGES;

    /** 1.2.4. Variables to store additional resources  */
    public static string $THEME_EXTRA_CSS_RELATIVE_DIR;
    public static string $THEME_EXTRA_CSS_ABSOLUTE_DIR;


    /** 2. Constructor */

    // 2.1. Declare a masked constructor for plugin properties
    function __construct(){
        // Set plugin path & directory variables
        $this->set_Plugin_Path_Variables();
        // 
        $this->set_Default_Post_Format();
    }//End of __construct

    // 2.2. Redeclare the built-in __clone() to prevent from cloning object
    // protected function __clone(){}

    // 2.3. Redeclare the built-in __wakeup() method to prevent from being restored from strings
    // - Need to be restored from a string
    /* public function __wakeup(){
        throw new Exception( "Cannot unserialize a singleton." );
    } */

    // 2.4. Declare the method to get singleton:
    /* public static function getInstance(): PluginProperties{
        // $className = static::class;

        if( !isset( self::$instance ) ){
            self::$instance = new self();
        }

        return self::$instance;
    }//getInstance() */

    /** 3. Functions to initialize variables */
    /** 3.1. Paths & Directories variables */
    private function set_Plugin_Path_Variables(){
        // 1. Plugin path variables
        self::$PLUGIN_ROOT_DIR_PATH = plugin_dir_path( dirname(__FILE__ , 1) ); 
        self::$PLUGIN_ROOT_DIR_URI = plugin_dir_url( dirname(__FILE__ , 1) );

        self::$PLUGIN_PATH = self::$PLUGIN_PATH ?? plugin_dir_path( dirname(__FILE__ , 1) );
        // Convert DIRECTORY_SEPARATOR - for Windows OS:
        if( in_array( PHP_OS, array('WINNT', 'Windows') ) ){
            self::$PLUGIN_PATH = str_replace("\\", "/", self::$PLUGIN_PATH);
        }  
        // Example of Plugin URL: http://vnlabwin.local.info/wp-content/plugins/sunsetpro/   
        self::$PLUGIN_URL = self::$PLUGIN_URL ?? plugin_dir_url( dirname(__FILE__ , 1) );
        // Example of plugin name : sunsetpro 
        self::$PLUGIN_NAME = plugin_basename( dirname(__FILE__ , 1)  );
        self::$PLUGIN_BASENAME = plugin_basename( dirname(__FILE__ , 1).'/vncslab-companion.php' );

        // 2. Theme path variables
        self::$THEME_ROOT_DIR_PATH = get_theme_root().'/'.wp_get_theme();
        self::$THEME_ROOT_DIR_URI = get_theme_root_uri().'/'.wp_get_theme();
    }//setPluginPathVariables

    /** 3.2. Set post format */
    private function set_Default_Post_Format(){
        self::$DEFAULT_POST_FORMATS = new SplFixedArray( 9 );

        // Specify all post format supported by WordPress
        self::$DEFAULT_POST_FORMATS[0] = 'standard';
        self::$DEFAULT_POST_FORMATS[1] = 'gallery';
        self::$DEFAULT_POST_FORMATS[2] = 'link';
        self::$DEFAULT_POST_FORMATS[3] = 'image';
        self::$DEFAULT_POST_FORMATS[4] = 'quote';
        self::$DEFAULT_POST_FORMATS[5] = 'status';
        self::$DEFAULT_POST_FORMATS[6] = 'video';
        self::$DEFAULT_POST_FORMATS[7] = 'audio';
        self::$DEFAULT_POST_FORMATS[8] = 'aside'; 
    }//setDefaultPostFormat
    /** 4. Operational functions */

}//End of PluginProperties