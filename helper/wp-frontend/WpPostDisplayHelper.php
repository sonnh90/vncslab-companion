<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

namespace VncslabCompanion\Helper\WpFrontEnd;

use Symfony\Component\DomCrawler\Crawler;
use VncslabCompanion\Includes\Init as Init;
use VncslabCompanion\Helper\PluginProperties as PluginProperties;
use VncslabCompanion\Helper\PluginDebugHelper as PluginDebugHelper;

use DOMWrap\Document as Document;
use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;
use DOMDocument;

class WpPostDisplayHelper{
    /** 1. Const & Variables declarations*/
    
    /**
     * 4070 is the post display only for Desktop
     * 4129 is the post display responsively for both desktop & mobile
    */
    const WP_POST_EXAMINED_LIST = [ 4079, 4129 ];

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
        /** 3.1. Display control */
        /** 3.1. Disable content pagination for all posts & pages */

        /** 3.2. Disable content pagination for specific all posts */
        add_filter('content_pagination', [$this, 'disable_Pagination_WP_Post_Display'], 10, 2 );

        /** 3.1.1. Stop displaying post from pagination */

        /** 3.2. Customize the HTML Output if matching the requirements */
        global $post;//NULL here

        //$this->localDebugger->write_log_general( $post ); //OK

        /** - Add to the filter hook: */
        // add_action('pre_get_posts', [$this, 'customize_HTML_output_If_Post_4079'] );
        // template_redirect would be the most appropriate
        // add_action('the_post', [$this, 'customize_HTML_output_If_Post_4079'] );
        $this->customize_HTML_output_If_Post_4079();//OK. Get the OutputHTML Content
        // add_action('template_redirect', [$this, 'customize_HTML_output_If_Post_4079'] );

        $this->customize_HTML_output_If_Post_4129();//OK. Get the OutputHTML Content
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
    /** 3.1. Display control */
    /** 3.1.1. Stop pagination when displaying WP Post - $wpQuery */
    function disable_Pagination_WP_Post_Display( $pages, $post ){
        //global $query; // WP_Query class 
        global $post;

        // $this->localDebugger->write_log_general( $post->ID );
        if( is_null( $post )  ) return ;

        /** Input argument $wpQuery is WP_Query object */

        /** Stop pagination for WP Post 4129 */
        if( 4079 == $post->ID ){
            // 1. Disable pagination for WP Post display
            if( in_the_loop() && 'post' === $post->post_type ){
                $pages = [ join( '', $pages ) ];
            }

        }//if( 4129 == $post->ID )

        if( 4129 == $post->ID ){
            // 1. Disable pagination for WP Post display
            if( in_the_loop() && 'post' === $post->post_type ){
                $pages = [ join( '', $pages ) ];
            }

        }//if( 4129 == $post->ID )

        return $pages;

    }//disable_Pagination_WP_Post_Display

    /** 3.2. Customize output HTML for specific WP posts  */
    /** 3.2.1 Customize output HTML for post ID 4079 - chocolate benefits */
    /** - Reference https://wordpress.stackexchange.com/questions/52840/filter-all-html-output */
    /** This function is called a lot. */
    public function customize_HTML_output_If_Post_4079(){
        //global $post; // WP_Post

        //$this->localDebugger->write_log_general( $post->ID );
        $this->get_Final_Data_From_Buffer();

        add_filter( 'final_output' , [$this, 'update_HTML_Output_If_Post_4079'] );
    }//customize_HTML_output_If_Post_4079 

    /** 3.2.2 Customize output HTML for post ID 4129 - chocolate benefits */
    public function customize_HTML_output_If_Post_4129(){
        //global $post; // WP_Post

        //$this->localDebugger->write_log_general( $post->ID );
        $this->get_Final_Data_From_Buffer();

        add_filter( 'final_output' , [$this, 'update_HTML_Output_If_Post_4129'] );
    }//customize_HTML_output_If_Post_4129 

    /******************************/
    /** Helper functions for 3.2. */    
    public function get_Final_Data_From_Buffer(){
        /** 1. Guarding the function operations */
        // 1. Skip execution if loading the WordPress admin setting pages
        if( is_admin() ) return '';

        ob_start();

        add_action(
            'shutdown',  
            function(){
                $finalData = '';

                $totalLevels = ob_get_level();
        
                for( $i = 0; $i < $totalLevels; $i++ ){
                    $finalData .= ob_get_clean();
                }

                echo apply_filters( 'final_output', $finalData );     
            },
            0
        );

    }//get_Final_Data_From_Buffer

    /** Helper functions for 3.2.1 */
    public function update_HTML_Output_If_Post_4079( $bufferedData ){
        /** 1. Guarding the data validation */
        
        // 1.1. Guarding if the bufferedData is null or empty
        if( strlen( $bufferedData ) == 0 ) return '';
        $resultHTMLData = '';

        // 1.2. Guarding if the bufferedData is valid HTML
        // $this->localDebugger->write_log_general( $bufferedData ); //OK
        // Fetch the DomWrapperDocument data
        $outputHTMLDoc = new Document(); 
        $outputHTMLDoc->html( $bufferedData );

        /** For 2.1. - update custom properties for custom elements */
        $articleRelativeSelector = 'article#post-4079';
        $mediaItemsRelativeSelector = 'div.entry-content > div.wp-block-media-text.chocolate-benefits';
        $mediaItemsSelector = "$articleRelativeSelector > $mediaItemsRelativeSelector";

        $articleDoc = $outputHTMLDoc->find( $articleRelativeSelector );
        $articleQuantity = $articleDoc->count();//OK
        // $this->localDebugger->write_log_general( $articleQuantity );//OK

        // 1.3. If it does not contain dedicated HTML element, return the bufferedData immediately
        if( $articleQuantity == 0 ) return $bufferedData;

        //$articleHTML = $articleDoc->html();//OK for testing

        /** For 2.2. - replace the DOM wrapped properties to original */
        $outputImagesDoc = $outputHTMLDoc->find('img');

        /** 2. Proceed the buffered HTML data */
        $mediaItemsDoc = $articleDoc->find( $mediaItemsRelativeSelector );
        // $this->localDebugger->write_log_general( $articleDoc->html() ); //OK
        // $this->localDebugger->write_log_general( $articleQuantity );//OK
        
        $mediaItemsQuantity = $mediaItemsDoc->count();//OK
        // $this->localDebugger->write_log_general( $mediaItemsQuantity );//OK  
        
        // If finding the article content of post 4079, proceed the buffered data
        if( ( $articleQuantity > 0 ) && ( $mediaItemsQuantity > 0 ) ){
            /** 2.1. Update the lazy load properties for all relevant media items */
            // $resultHTMLData = $bufferedData;
            for( $i = 0; $i < $mediaItemsQuantity; $i++ ){
                $index = $i + 1;
                $mediaItemRelativeSelector = "$mediaItemsRelativeSelector.item-$index";//OK

                // 2.1.1. Query the cocoa benefits media items
                $mediaItemDoc = $articleDoc->find( $mediaItemRelativeSelector );//OK
                // $mediaItemDoc->attr('loading','lazy');

                $mediaItemImageContainerDocRelativeSelector = "figure.wp-block-media-text__media";
                $mediaItemTextContainerDocRelativeSelector = "div.wp-block-media-text__content";
                
                // 2.1.2 Get the DOM Object of cocoa benefits media sub items
                // a. Image container
                $mediaItemImageContainerDoc = $mediaItemDoc->find( $mediaItemImageContainerDocRelativeSelector );
                $mediaItemImageDoc = $mediaItemImageContainerDoc->find('img');

                // b. Textual content Container
                $mediaItemTextContainerDoc = $mediaItemDoc->find( $mediaItemTextContainerDocRelativeSelector );

                // 2.1.3. Add class "hide" to image containers & textual container 
                // a. Image container
                $imgContainerClassList = $mediaItemImageContainerDoc->getAttribute('class');
                $imgContainerClassList = "$imgContainerClassList hide";
                $mediaItemImageContainerDoc->setAttribute('class', $imgContainerClassList);

                // b. Textual content container
                $textContainerClassList = $mediaItemTextContainerDoc->getAttribute('class');
                $textContainerClassList = "$textContainerClassList hide";
                $mediaItemTextContainerDoc->setAttribute('class', $textContainerClassList);

                // 2.1.4. Add lazy load properties
                $mediaItemImageContainerDoc->attr( 'loading', 'lazy' );//OK
                $mediaItemImageDoc->attr( 'loading', 'lazy');//OK
                $mediaItemTextContainerDoc->attr( 'loading', 'lazy' );//OK

            }//End of for( $i = 0; $i < $mediaItemsQuantity; $i++ )

            /** 2.2. Remove all DOMWRAP--ATTR-xx--src, replate with original source */
            /*** --- Still stuck on how to manipulate DOM elements, HTML documents in PHP --- */
            $outputImagesQuantity = $outputImagesDoc->count();//OK - total 25 items
            // $this->localDebugger->write_log_general( $outputImagesQuantity );//OK         

            // $this->localDebugger->write_log_general( $mediaItemsQuantity );//OK
            // $resultHTMLData = $outputHTMLDoc->html();//Will return error because "DOMWRAP--ATTR-xx--src" hasn't been converted to "src" property yet

            $outputDOMDoc = new DOMDocument();
            /** @2024-08-18 ignore PHP warning when loading unwell-form HTML */
            libxml_use_internal_errors(true); 
            $outputDOMDoc->loadHTML( $outputHTMLDoc->html() );
            $outputImagesDOM = $outputDOMDoc->getElementsByTagName('img');
            //$imgsCrawler = new SymfonyCrawler( $outputHTMLDoc->html() );
            // $this->localDebugger->write_log_general( $resultImages );
            $outputImagesSrcList = [];

            /** Declare the pattern for attribute DOMWRAP--ATTR-[XX]--src */ 
            // $attrPattern = '/^domwrap$//^\-\-/$/^attr$//^\-$//^[0-9]{1,2}$//^\-\-/$//^src$/';
            $attrPattern = "/^domwrap--attr-\d{1,2}--src$/";
            $attrPatternUpperCase = "/^DOMWRAP--ATTR-\d{1,2}--SRC$/";
            $matchedResult = [];

            for($i = 0; $i < $outputImagesQuantity; $i++ ){
                $imgIndex = $i + 1;
                $wrappedPropertyName = "DOMWRAP--ATTR-$imgIndex--src";

                // Get the image Item nodes
                // $imgItem = $imgsCrawler->filter('img')->eq( $i );
                $outputImageDom = $outputImagesDOM->item($i);

                if( $outputImageDom->hasAttributes() ){
                    foreach( $outputImageDom->attributes as $attribute ){
                        $attrName = $attribute->nodeName;//OK
                        $attrValue = $attribute->nodeValue; //OK
                        //$attrInfo = "attrName: $attrName ; attrValue: $attrValue";
                        //$this->localDebugger->write_log_general( $attrInfo );

                        $matchedStatus = preg_match( $attrPattern, $attrName, $matchedResult );

                        /** If finding the attribute "DOMWRAP--ATTR-[XX]--src" */ 
                        if( $matchedStatus == 1){
                            /** Save attribute to an array for backup purpose */
                            $outputImagesSrcList[ $attrName ] = $attrValue;
                            /** set the attribute src for image items */
                            $outputImageDom->setAttribute( 'src', $attrValue );
                            /** Delete the wrapped properties generated by DOMWraper */
                            $outputImageDom->removeAttribute( $attrName );
                        }                        
                    }//foreach( $outputImageDom->attributes as $attribute )
                }//if( $outputImageDom->hasAttributes() )

                
                // Get all properties
                //$imgItemAttributes = $imgItem->getValues();
                // Get data of "DOMWRAP-ATTR-xx--src" property 
            }//End of for($i = 0; $i < $outputImagesQuantity; $i++ )  
            
            // $this->localDebugger->write_log_general( $outputImagesSrcList );//OK

            $resultHTMLData = $outputDOMDoc->saveHTML();//OK
            libxml_use_internal_errors(false);
            /** Workaround while waiting for debug - Temporary modify nothing */
            // $resultHTMLData = $bufferedData;
        } else {
            $resultHTMLData = $bufferedData;
        }

        /** 1. Start processing the outputHTMLData of Post 4097 - chocolate benefits here */
        // Temporary assing result HTML data
        

        return $resultHTMLData;
    }//update_HTML_Output_If_Post_4079

        /** Helper functions for 3.2.2 */
    public function update_HTML_Output_If_Post_4129( $bufferedData ){
        /** 1. Guarding the data validation */
        // $this->localDebugger->write_log_general( $bufferedData );

        /** 1.1. Guarding if the $bufferedData is null or empty */
        if( strlen( $bufferedData ) == 0 ) return '';
        $resultHTMLData = '';

        /** 1.2. Guarding if the $bufferedData is valid HTML */
        // Fetch the DomWrapperDocument the input HTML Data
        $outputHTMLDoc = new Document();
        $outputHTMLDoc->html( $bufferedData );

        /** For 2.1. Update custom properties for custom elements */
        $articleRelativeSelector = 'article#post-4129';
        $mediaItemsRelativeSelector = 'div.entry-content > div.wp-block-group.cocoa-benefits-container-desktop > div.wp-block-media-text.chocolate-benefits';
        $mediaItemsSelector = "$articleRelativeSelector > $mediaItemsRelativeSelector";

        $articleDoc = $outputHTMLDoc->find( $articleRelativeSelector );
        $articleQuantity = $articleDoc->count();//OK

        //$this->localDebugger->write_log_general( $articleQuantity );

        // 1.3. If it does not contain dedicated HTML element, return the bufferedData immediately
        if( $articleQuantity == 0 ) return $bufferedData;

        /** For 2.2. - replace the DOM wrapped properties to original */
        $outputImagesDoc = $outputHTMLDoc->find('img');

        /** 2. Proceed the buffered HTML data */
        $mediaItemsDoc = $articleDoc->find( $mediaItemsRelativeSelector );
        $mediaItemsQuantity = $mediaItemsDoc->count();//OK

        $resultHTMLData = '';

        // If finding the article content of post 4129, proceed the buffered data
        if( ( $articleQuantity > 0 ) && ( $mediaItemsQuantity > 0 ) ){
            /** 2.1. Update the lazy load properties for all relevant media items */
            for( $i = 0; $i < $mediaItemsQuantity; $i++ ){
                $index = $i + 1;
                $mediaItemRelativeSelector = "$mediaItemsRelativeSelector.item-$index";//OK

                // 2.1.1. Query the cocoa benefits media items
                $mediaItemDoc = $articleDoc->find( $mediaItemRelativeSelector );//OK

                $mediaItemImageContainerDocRelativeSelector = "figure.wp-block-media-text__media";
                $mediaItemTextContainerDocRelativeSelector = "div.wp-block-media-text__content";

                 // 2.1.2 Get the DOM Object of cocoa benefits media sub items
                // a. Image container
                $mediaItemImageContainerDoc = $mediaItemDoc->find( $mediaItemImageContainerDocRelativeSelector );
                $mediaItemImageDoc = $mediaItemImageContainerDoc->find('img');

                // b. Textual content Container
                $mediaItemTextContainerDoc = $mediaItemDoc->find( $mediaItemTextContainerDocRelativeSelector );

                // 2.1.3. Add class "hide" to image containers & textual container 
                // a. Image container
                $imgContainerClassList = $mediaItemImageContainerDoc->getAttribute('class');
                $imgContainerClassList = "$imgContainerClassList hide";
                $mediaItemImageContainerDoc->setAttribute('class', $imgContainerClassList);
                // Validate the $mediaItemImageContainerDoc
                //$this->localDebugger->write_log_general( $mediaItemImageContainerDoc->html() );

                // b. Textual content container
                $textContainerClassList = $mediaItemTextContainerDoc->getAttribute('class');
                $textContainerClassList = "$textContainerClassList hide";
                $mediaItemTextContainerDoc->setAttribute('class', $textContainerClassList);
                // Validate the $mediaItemImageContainerDoc
                //$this->localDebugger->write_log_general( $mediaItemTextContainerDoc->html() );

                // 2.1.4. Add lazy load properties
                $mediaItemImageContainerDoc->attr( 'loading', 'lazy' );//OK
                $mediaItemImageDoc->attr( 'loading', 'lazy');//OK
                $mediaItemTextContainerDoc->attr( 'loading', 'lazy' );//OK
            }//for( $i = 0; $i < $mediaItemsQuantity; $i++ )

            /** 2.2. Remove all DOMWRAP--ATTR-xx--src, replate with original source */
            $outputImagesQuantity = $outputImagesDoc->count();//OK - total 25 + 10 items

            $outputDOMDoc = new DOMDocument();
            /** @2024-08-18 ignore PHP warning when loading unwell-form HTML */
            libxml_use_internal_errors(true); 
            $outputDOMDoc->loadHTML( $outputHTMLDoc->html() );
            $outputImagesDOM = $outputDOMDoc->getElementsByTagName('img');

            $outputImagesSrcList = [];
            /** Declare the pattern for attribute DOMWRAP--ATTR-[XX]--src */ 
            $attrPattern = "/^domwrap--attr-\d{1,2}--src$/";
            $attrPatternUpperCase = "/^DOMWRAP--ATTR-\d{1,2}--SRC$/";
            $matchedResult = [];

            // Iterate through all image items in the WP Post content
            for($i = 0; $i < $outputImagesQuantity; $i++ ){
                // Get the image Item nodes
                $outputImageDom = $outputImagesDOM->item($i);

                if( $outputImageDom->hasAttributes() ){
                    foreach( $outputImageDom->attributes as $attribute ){
                        $attrName = $attribute->nodeName;//OK
                        $attrValue = $attribute->nodeValue; //OK
                        //$attrInfo = "attrName: $attrName ; attrValue: $attrValue";
                        //$this->localDebugger->write_log_general( $attrInfo );

                        $matchedStatus = preg_match( $attrPattern, $attrName, $matchedResult );

                        /** If finding the attribute "DOMWRAP--ATTR-[XX]--src" */ 
                        if( $matchedStatus == 1){
                            /** Save attribute to an array for backup purpose */
                            $outputImagesSrcList[ $attrName ] = $attrValue;
                            /** set the attribute src for image items */
                            $outputImageDom->setAttribute( 'src', $attrValue );
                            /** Delete the wrapped properties generated by DOMWraper */
                            $outputImageDom->removeAttribute( $attrName );
                        }                        
                    }//foreach( $outputImageDom->attributes as $attribute )
                }//if( $outputImageDom->hasAttributes() )
            }//for($i = 0; $i < $outputImagesQuantity; $i++ )

            /** 2.3. Save the output HTML  */
            $resultHTMLData = $outputDOMDoc->saveHTML();//OK
            libxml_use_internal_errors(false);


        } else{
            $resultHTMLData = $bufferedData;
        }

        return $resultHTMLData;
    }//update_HTML_Output_If_Post_4129



}//WpPostDisplayHelper