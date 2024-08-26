<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

/**
 * This is a controller class to inject custom data to frontend page
 * 
 * Important reference document:
 * - Maniuplate DOM elements: https://github.com/scotteh/php-dom-wrapper 
 * 
*/

namespace VncslabCompanion\Includes\Controller\ScopeFrontend;

use DOMWrap\Document as Document;

use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;
use VncslabCompanion\Includes\Controller\ScopeFrontend\ThemeCustomizer as ThemeCustomizer;

class WooCommerceCustomizer{

    /** 1. Define variables & constant */
    /** 1.1. Prerequisite libraries */
    /** 1.1.1. Bootstrap stypes & scripts */
    const PRELIB_BOOTSTRAP_STYLE_FILENAME = 'bootstrap.css';
    const PRELIB_BOOTSTRAP_STYLE_HANDLER = 'vncslab-prelib-bootstrap-style';
    const PRELIB_BOOTSTRAP_SCRIPT_FILENAME = 'bootstrap.js';
    const PRELIB_BOOTSTRAP_SCRIPT_HANDLER = 'vncslab-prelib-bootstrap-script';
    public static $PRELIB_BOOTSTRAP_STYLE_PATH;
    public static $PRELIB_BOOTSTRAP_SCRIPT_PATH;

    /** 1.2. extra styles & scripts for WooCommerce product pages*/
    // 1.2.1. Extra styles
    const WC_EXTRA_STYLE_FILENAME = 'wc-custom-product-page.css';
    const WC_CUSTOM_STYLE_HANDLER = 'vncslab-wc-custom-product-page-style';

    // 1.2.2. Extra scripts
    const WC_EXTRA_SCRIPT_FILENAME = 'wc-custom-product-page.js'; 
    const WC_CUSTOM_SCRIPT_HANDLER = 'vncslab-wc-custom-product-page-script';

    public static $WC_EXTRA_STYLE_PATH;
    public static $WC_EXTRA_SCRIPT_PATH;

    public static string $WC_CURRENT_THEME;
    // public static bool $WC_IS_BLOCKSY_THEME;

    // WooCommerce Product Page template related:

    const PRODUCT_PAGE_TEMPLATE_FILENAME = 'single-product.php';
    const PRODUCT_PAGE_TEMPLATE_RELATIVE_FILEDIR = 'woocommmerce/templates/';
    public static $PRODUCT_PAGE_TEMPLATE_RELATIVE_PATH;

    const DISPLAY_ITEMS_QUANTITY = 3;

    /** 1.2. Debugging variables */
    public Init $pluginInitiator;
    public PluginProperties $localProps;
    public PluginDebugHelper $localDebugger;

    /** 2. Constructors & constructor's helper methods  */
    /** 2.1. Constructors */
    public function __construct(){
        /** 1. Declare system information */
        // 1. Plugin Initiator instance
        $this->pluginInitiator = Init::$INSTANCE ?? new Init();
        
        // 2. Setup local properties
        $this->set_Local_Properties();

        // 3. Setup local debuggger
        $this->set_Local_Debugger();

        /** 2. Setup extra WooCommerce parameters */
        $this->set_WooCommerce_Extra_Params();
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

    /** 2.2.3. Setup extra WooCommerce parameters */
    public function set_WooCommerce_Extra_Params(){
        /** 1. Define additional Styles & Scripts full path */
        /** 1.1. Prerequisite libraries - Bootstrap 5.0 */
        self::$PRELIB_BOOTSTRAP_STYLE_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_PRELIB_ROOT_DIR,
            PluginProperties::CSS_ROOT_DIR, 
            self::PRELIB_BOOTSTRAP_STYLE_FILENAME
        );
        
        self::$PRELIB_BOOTSTRAP_SCRIPT_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_PRELIB_ROOT_DIR,
            PluginProperties::JS_ROOT_DIR, 
            self::PRELIB_BOOTSTRAP_SCRIPT_FILENAME
        );


        /** 1.2. Extra styles & scripts */
        self::$WC_EXTRA_STYLE_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_FRONTEND_ROOT_DIR,
            PluginProperties::CSS_ROOT_DIR, 
            self::WC_EXTRA_STYLE_FILENAME
        );
        
        self::$WC_EXTRA_SCRIPT_PATH = sprintf( 
            '%s%s%s%s', 
            PluginProperties::$PLUGIN_URL, 
            PluginProperties::RESOURCES_FRONTEND_ROOT_DIR,
            PluginProperties::JS_ROOT_DIR, 
            self::WC_EXTRA_SCRIPT_FILENAME
        );

        /** 2. Define the current Theme Name */
        self::$WC_CURRENT_THEME = ThemeCustomizer::$THEME_NAME;

        /** 3. Product page display information */
        self::$PRODUCT_PAGE_TEMPLATE_RELATIVE_PATH = sprintf(
            '%s%s',             
            self::PRODUCT_PAGE_TEMPLATE_RELATIVE_FILEDIR,
            self::PRODUCT_PAGE_TEMPLATE_FILENAME,
        );
    }//setWooCommerceExtraParams    

    /** 3. Main class operational functions */

    /** 3.1. Main operation of WooCommmerce customizer*/
    public function register(){
        // 1. General WooCommerce product page customizations
        $this->customize_WC_After_loaded();        

        // 2. Customize product thumbnail images 
        $this->customize_WC_Product_Thumbnail_Images();

        // 3. Customize product price display:
        $this->customize_WC_Product_Price();
    }//register


    /** Define actions after :
     * - WooCommerce plugin is loaded
     * - All other plugins are loaded
    */
    public function customize_WC_After_Loaded(){      
        /** 1. Enqueue the wc extra styles & scripts if the current active theme is Blocksy Theme */
        /** Only load custom styles scripts for Blocky theme */
        if( 0 === strcasecmp( self::$WC_CURRENT_THEME, 'blocksy' ) ){
            
            /** 1. Add customizing activities after all plugins are loaded */
            add_action( 'after_setup_theme', array( $this, 'register_Extra_Resources_To_WC_pages'), 100 );

            /** 1.2. Enqueue extra styles & scripts after WooCommerce & all plugins are loaded*/
            add_action( 'woocommerce_loaded', array( $this,'enqueue_Extra_Resources_To_WC_Pages' ) );


        }//End of checking if the current active theme is blocksy theme

    }//customize_WC_After_Loaded

    public function customize_WC_Product_Thumbnail_Images(){                    
        add_filter( 
            'woocommerce_single_product_carousel_options', 
            array( $this, 'update_WC_Product_Images_Slider' ) 
        );

        /** 
         * 1. The HTML data of product images obtained in the filter hook:
        * "woocommerce_single_product_image_thumbnail_html" - OK.
        **/
        add_filter(
            'woocommerce_single_product_image_thumbnail_html',
            array($this, 'modify_WC_Product_Thumbnail_Images' )
        );

    }//customize_WC_Product_Thumbnail_Images

    public function customize_WC_Product_Price(){
        // add_filter( 'woocommerce_template_single_price', array( $this, 'modify_WC_Product_Price' ) ); 
        // try action hook "woocommerce_single_product_summary"  - not work   
       
        add_filter( 
            'woocommerce_single_product_image_thumbnail_html',
            array( $this, 'modify_WC_Product_Price' ) 
        ); 
    }//customize_WC_Product_Price

    /** === Helper methods for main operational functions === */
    /** 1. Helper functions for customize_WC_After_Loaded() */

    /** 1.1. Register extra CSS & JS to frontend WooCommerce Display */
    public function register_Extra_Resources_To_WC_pages(){
        /** 1. Register the prerequisites libraries*/
        /** 1.1. Bootstrap */
        wp_register_style( 
            self::PRELIB_BOOTSTRAP_STYLE_HANDLER, 
            self::$PRELIB_BOOTSTRAP_STYLE_PATH, 
            array(), '5.3.3', 'all'
        );
     
        wp_register_script( 
            self::PRELIB_BOOTSTRAP_SCRIPT_HANDLER, 
            self::$PRELIB_BOOTSTRAP_SCRIPT_PATH,
            array(), '5.3.3', true
        );

        /** 2. Register the custom styles & scripts for the custom product pages */
        /** 2.1. Register the custom styles */
        wp_register_style( 
            self::WC_CUSTOM_STYLE_HANDLER, self::$WC_EXTRA_STYLE_PATH, 
            array(), '1.0.1', 'all'
        );

        /** 2.2. Register the custom scripts */
        wp_register_script( 
            self::WC_CUSTOM_SCRIPT_HANDLER, self::$WC_EXTRA_SCRIPT_PATH,
            array(), '1.0.1', true
        );
    }//register_Extra_Resources_To_WC_pages

    /** 1.2. Enqueue extra CSS & JS to frontend WooCommerce Display */
    public function enqueue_Extra_Resources_To_WC_Pages(){
        /** 1. Enqueue the extra prerequisite libraries*/
        /** 1.1. Bootstrap */
        wp_enqueue_style( 
            self::PRELIB_BOOTSTRAP_STYLE_HANDLER, 
            self::$PRELIB_BOOTSTRAP_STYLE_PATH, 
            array(), '5.3.3', 'all'
        );
        
        wp_enqueue_script( 
            self::PRELIB_BOOTSTRAP_SCRIPT_HANDLER, 
            self::$PRELIB_BOOTSTRAP_SCRIPT_PATH,
            array(), '5.3.3', true
        );

        /** 2. Enqueue extra styles & scripts  */
        /** 2.1. Enqueue the custom styles */
        wp_enqueue_style( 
            self::WC_CUSTOM_STYLE_HANDLER, self::$WC_EXTRA_STYLE_PATH, 
            array(), '1.0.1', 'all'
        );

        /** 2.2. Enqueue the custom scripts */
        wp_enqueue_script( 
            self::WC_CUSTOM_SCRIPT_HANDLER, self::$WC_EXTRA_SCRIPT_PATH,
            array(), '1.0.1', true
        );
    }//inject_Extra_Resources_To_WC_Pages

    /** 2. Helper function for customize_WC_Product_Thumbnail_Images() */
    /** 2.1. Customize WooCommerce product images thumbnails
     * Ref: https://wp-kama.com/plugin/woocommerce/hook/woocommerce_single_product_carousel_options
     * 
     */
    public function update_WC_Product_Images_Slider( $options ){
        // Customize some options
        $options[ 'directionNav' ] = true;

        // $this->localDebugger->write_log_general( $options );

        return $options;
    }//updateWooCommerceProductImagesSlider

    /* 2.2. modify WC product thumbnail images */
    public function modify_WC_Product_Thumbnail_Images( $srcData ){
        /**1. Using PHP-DOM_Wrapper libary to manipulate DOM data
         * https://github.com/scotteh/php-dom-wrapper 
        */
        // $this->localDebugger->write_log_general( $srcData );
        $productImagesDoc = new Document();
        $productImagesDoc->html( $srcData );        

        $thumbnailsPillsContainerDoc = $productImagesDoc->find('div.flexy-pills[data-type="thumbs"]');//OK
        $thumbnailsPillInnerContainerDoc = $productImagesDoc->find('div.flexy-pills[data-type="thumbs"] > ol');//OK
        $thumbnailsPillsListDoc = $productImagesDoc->find('div.flexy-pills[data-type="thumbs"] > ol > li');//OK

        $quantityThumbnailImg = $thumbnailsPillsListDoc->count();

        // 1.1. If there is less than 5 thumbnails image : display as normal
        if( $quantityThumbnailImg <= 5 )  {
            $updatedData = $productImagesDoc->html();

            return $updatedData;
        } 

        // 1.2. If there are more than 5 thumbnails: display in carousel format:
        // 1.2.1. Assign the product thumbnail pills an ID
        $thumbnailsPillsCarouselID = 'vncslab-wc-product-thumbnails-pills-carousel';
        
        $thumbnailsPillsContainerDoc->attr('id', $thumbnailsPillsCarouselID);
        
        // 1.2.2. Assign the class demonstrated for carousels
        $thumbnailsPillsContainerDoc->addClass('pills-container');
        $thumbnailsPillInnerContainerDoc->addClass('pills-inner-container');
        $thumbnailsPillsListDoc->addClass('pills-item');

        // 1.2.3. Assign active class for the first 3 slides
        $activePillsSelector = sprintf(
            'div.flexy-pills[data-type="thumbs"] > ol > li:nth-child(-n+%s)', 
            self::DISPLAY_ITEMS_QUANTITY
        );

        $thumbnailsPillsActiveListDoc = $productImagesDoc->find( $activePillsSelector );
        $thumbnailsPillsActiveListDoc->addClass('show');


        // 2. Append controller button
        // 2.1. Previous controller button
        $prevControllerButtonHTML = <<<HTML
        <button class="pills-control-prev" type="button" 
            data-pills-target="{$thumbnailsPillsCarouselID}" 
            data-pills-slide="prev">
            <span class="pills-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        HTML;

        $thumbnailsPillsContainerDoc->appendWith( $prevControllerButtonHTML );

        // 2.2. Next controller button
        $nextControllerButtonHTML = <<<HTML
        <button class="pills-control-next" type="button" 
            data-pills-target="{$thumbnailsPillsCarouselID}"
            data-pills-slide="next">
            <span class="pills-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        HTML;

        $thumbnailsPillsContainerDoc->appendWith( $nextControllerButtonHTML );

        // temporary modify nothing
        $updatedData = $productImagesDoc->html();  

        // Redeclare temp for debug - OK 
        // $updatedData = $srcData;
        //$updatedData = '<p>This is the product images area !</p>';

        return $updatedData;
    }//modify_WC_Product_Thumbnail_Images

    /** 3. Helper function for customize_WC_Product_Price */
    public function modify_WC_Product_Price( $srcData ){    
        $updatedData = $srcData;

        return $updatedData;
    }//modify_WC_Product_Thumbnail_Images

}//End of class WooCommerceCustomizer Controller definition