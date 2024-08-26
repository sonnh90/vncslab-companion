<?php 
/*
 * @package vncslab-companion
 * @version 1.0.1
 */

namespace VncslabCompanion\Helper;

class PluginClassLoader {
    /** I. Define a variable to store unique singleton instance */
    // private static $instance;
    // const IS_SINGLETON_CLASS = true;

    /** 1. Variables declaration */    
    public $pluginDirectoryList;
    /** 1. This must contain all valid Class Files */
    public static array $DIRECTORY_LIST;
    public $pluginClassList;
    public static array $CLASSES_LIST;

    /** 2. Constructor methods */
    public function construct(){
        $this->set_Plugin_Directory_List();

    }//construct

    /** 2.2. Helper functions for constructor */
    /** 2.2.1. Directory lists */
    private function set_Plugin_Directory_List(){
        /* $this->pluginDirectoryList = array();

        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/base';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/api';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/controller';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/display';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/content';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/template';  */
        
        self::$DIRECTORY_LIST = array(
          dirname( __FILE__, 2 ).'/includes/base',
          dirname( __FILE__, 2 ).'/includes/api',
          dirname( __FILE__, 2 ).'/includes/controller',
          dirname( __FILE__, 2 ).'/includes/display',
          dirname( __FILE__, 2 ).'/includes/content',
        );     

    }//setPluginDirectoryList

    /** 2.2.2. Class list need to be loaded */

    /** 3. Functions */
    /** 3.1. Main operation function */
    /** 3.1.1. Load all necessary files for Plugin - in case Composer failed to load 
     * The directory list is defined in self::$DIRECTORY_LIST
     * 
    */
    public static function Include_PHP_Files_From_Plugin_Directories(){
      self::$DIRECTORY_LIST = array(
        dirname( __FILE__, 2 ).'/includes/base',
        dirname( __FILE__, 2 ).'/includes/api',
        dirname( __FILE__, 2 ).'/includes/controller',
        dirname( __FILE__, 2 ).'/includes/display',
        dirname( __FILE__, 2 ).'/includes/content',        
      ); 

      foreach( self::$DIRECTORY_LIST as $directory ){
        $includedFiles = self::Get_All_Files_From_Single_Directory( $directory );

        foreach ( $includedFiles as $fileItem ){
          // check if the suffix is .php file
          if(".php" == substr($fileItem, -4)){
            // echo 'item included : '.$item.PHP_EOL;
            // include( $item );
            // https://www.simplilearn.com/tutorials/php-tutorial/include-in-php
            include_once( $fileItem );
          }
        }//foreach ( $includedFiles as $fileItem )
      }//foreach( self::$DIRECTORY_LIST as $directory )

      //2. Manually include AJAX handler
      // include_once( dirname(__FILE__).'/ajax-handler-sunsetpro.php' );
    }//IncludeAllPHPFilesFromPluginDirectories

    /** 3.2. Helper methods*/
    /** 3.2.1. Load all files in the single directory*/
    public static function Get_All_Files_From_Single_Directory( $dir, &$results = array() ){
      // $scannedResults = scandir($dir);
    
      if( is_dir($dir) ){
        $scannedResults = array_diff( scandir($dir) , array('.', '..') ); // Remove the default back directory
      } else {
        $scannedResults = array();
      }
    
      foreach($scannedResults as $item){
        //echo 'item : '.var_dump($item).PHP_EOL;
        $itemPath = realpath( $dir.DIRECTORY_SEPARATOR.$item );
        // echo '$itemPath : '.var_dump($itemPath).PHP_EOL;
    
        // If not a directory
        if( !is_dir($itemPath) ){
          $results[] = $itemPath;
        }
    
        // If is directory
        if( ("." != $item) && (".." != $item) ){
          self::Get_All_Files_From_Single_Directory( $itemPath, $results );
          // $results[] = $itemPath;
        }
    
      }// End foreach
    
      return $results;
  }//getAllFilesInTheDirectory



}// End of PluginClassLoader definition