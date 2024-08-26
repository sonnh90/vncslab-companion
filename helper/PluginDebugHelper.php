<?php 
/** 
 * @Package vncslab-compantion
 * 
 * **/

namespace VncslabCompanion\Helper;

use VncslabCompanion\Helper\PluginProperties as PluginProperties;

class PluginDebugHelper{
    

    // public $logDirectory; 
    // public $logFilePathGeneral;
    // public $logFilePathCustom;
    const LOG_TIMEZONE = 'Asia/Ho_Chi_Minh';
    public static string $LOG_DIRECTORY;
    public static string $LOG_FILE_PATH_GENERAL;
    // public static string $LOG_FILE_PATH_CUSTOM;

    public PluginProperties $localProps;

    public static int $COUNT_DISPLAY_FUNCTION;

    public function __construct(){
        $this->set_Variables_For_Custom_Debug();
            
    }//_construct

    /** Helper function for constructor */

    public function set_Variables_For_Custom_Debug(){
        /** Set the date defaul time zone :
         * - Reference : https://www.php.net/manual/en/function.date-default-timezone-set.php
         * 
         * */ 
        date_default_timezone_set( self::LOG_TIMEZONE );

        self::$LOG_DIRECTORY = dirname( __DIR__ );

        self::$LOG_FILE_PATH_GENERAL = self::$LOG_DIRECTORY.DIRECTORY_SEPARATOR.'troubleshoot'.DIRECTORY_SEPARATOR.'vncslab-companion-debug.log';

        if( !file_exists( self::$LOG_FILE_PATH_GENERAL )){
            // Create the new log file if not exist
            file_put_contents( self::$LOG_FILE_PATH_GENERAL, null );
        }
    
        self::$COUNT_DISPLAY_FUNCTION = isset( self::$COUNT_DISPLAY_FUNCTION )
            ? self::$COUNT_DISPLAY_FUNCTION : 0;
    }//setVariablesForCustomDebug
    public static function show_Debug_Separator(){
        $separatorContent = <<<HTML
            <p>-----------Debug Separator -----------------</p>
        HTML;

        return $separatorContent;
    }

    public static function show_Problematic_Var ( $displayVar ){
        $count = isset( self::$COUNT_DISPLAY_FUNCTION )
            ? self::$COUNT_DISPLAY_FUNCTION + 1
            : 1;

        self::$COUNT_DISPLAY_FUNCTION = isset( self::$COUNT_DISPLAY_FUNCTION )
            ? self::$COUNT_DISPLAY_FUNCTION++
            : 1;

        $problematicVar = var_export( $displayVar, true );
        
        $displayData = <<<HTML
        <p class="sunset-debug" style="padding-left:10em">---- Displaying the problematic variable :------</p>
        <p class="sunset-debug" style="padding-left:10em"> Called showProblematicVar at : {$count} times </p>';
        <pre class="sunset-debug" style="padding-left:10em">{$problematicVar}</pre>
        <p class="sunset-debug" style="padding-left:10em">------------------------------------------------</p>
        HTML;
       
        echo $displayData;
    }

    public static function get_Problematic_Var ( $displayVar ){

        $count = isset( self::$COUNT_DISPLAY_FUNCTION )
            ? self::$COUNT_DISPLAY_FUNCTION + 1
            : 1;

        self::$COUNT_DISPLAY_FUNCTION = isset( self::$COUNT_DISPLAY_FUNCTION )
            ? self::$COUNT_DISPLAY_FUNCTION++
            : 1;
        
        $problematicVar = var_export( $displayVar, true );

        $displayData = <<<HTML
        <p class="sunset-debug" style="padding-left:10em">---- Displaying the problematic variable :------</p>
        <p class="sunset-debug" style="padding-left:10em"> Called showProblematicVar at : {$count} times </p>';
        <pre class="sunset-debug" style="padding-left:10em">{$problematicVar}</pre>
        <p class="sunset-debug" style="padding-left:10em">------------------------------------------------</p>
        HTML;
 
        return $displayData;
    }

    /** Useful functions */
    public function write_log_general($logVariable): bool
    {
        if(!is_file( self::$LOG_FILE_PATH_GENERAL ) ) {
        return false;
        }
        // If not a string, parse a var_dump result
            $logMessage = function($item){
            if(is_string($item)){
                return $item;
            }
            ob_start();
            var_dump($item);
            return ob_get_clean();
        };

        error_log( date("Y-m-d, H:i:s") . " --> Start logging variable ...  " . PHP_EOL,
                3,  self::$LOG_FILE_PATH_GENERAL );
        error_log( date("Y-m-d, H:i:s") . " \$logMessage : " . print_r($logMessage($logVariable), true) . "\n",
                3,  self::$LOG_FILE_PATH_GENERAL );
        error_log( date("Y-m-d, H:i:s") . " --> End of logging variable ...  " . PHP_EOL,
                3,  self::$LOG_FILE_PATH_GENERAL );

        return true;
    }//write_log_general

    public function write_log_simple($logVariable): bool
    {
        if( !is_file( self::$LOG_FILE_PATH_GENERAL ) ) {
            return false;
        }
        // If not a string, parse a var_dump result
        $logMessage = function($item){
            if(is_string($item)){
                return $item;
            }
            ob_start();
            var_dump($item);
            return ob_get_clean();
        };

        error_log(
        sprintf("%s : %s \n", date("Y-m-d, H:i:s"), print_r($logMessage($logVariable), true)),
                3, self::$LOG_FILE_PATH_GENERAL );

        return true;
    }//write_log_simple

    public static function get_All_Callback_Functions_Of_Hook( $hook ){
        global $wp_filter;

        return  isset( $wp_filter[ $hook] ) 
            ? self::get_Problematic_Var( $wp_filter[ $hook ] ) 
            : false;
    }//displayAllCallbackFunctionsOfHook

    public static function show_All_Callback_Functions_Of_Hook( $hook ){
        echo self::get_All_Callback_Functions_Of_Hook( $hook );
    }//showAllCallbackFunctionsOfHook



}// end of class PluginDebugHelper declaration


