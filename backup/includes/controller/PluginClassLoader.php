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
    public static array $DIRECTORY_LIST;
    public $pluginClassList;
    public static array $CLASSES_LIST;

    /** 2. Constructor methods */
    public function construct(){
        $this->setPluginDirectoryList();

    }//construct

    /** 2.2. Helper functions for constructor */
    /** 2.2.1. Directory lists */
    private function setPluginDirectoryList(){
        $this->pluginDirectoryList = array();

        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/base';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/api';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/controller';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/display';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/content';
        $this->pluginDirectoryList[] = dirname( __FILE__, 2 ).'/includes/template'; 
        
        self::$DIRECTORY_LIST = $this->pluginDirectoryList;      
    }//setPluginDirectoryList

    /** 2.2.2. Class list need to be loaded */

    /** 3. Functions */

    /** 3.1. Get all files in the given directory & sub directories
     * - recursive method vncslab_get_all_files_in_given_directories
     * - $dir variable is a given directory to be scann
     *  */ 
    function getAllFilesInTheDirectory( $dir, &$results = array() ){
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
            $this->getAllFilesInTheDirectory( $itemPath, $results );
            // $results[] = $itemPath;
          }
      
        }// End foreach
      
        return $results;
    }//getAllFilesInTheDirectory

    public static function GetAllFilesInTheGivenDirectory( $dir, &$results = array() ){
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
          self::GetAllFilesInTheGivenDirectory( $itemPath, $results );
          // $results[] = $itemPath;
        }
    
      }// End foreach
    
      return $results;
  }//getAllFilesInTheDirectory

    public static function RetrieveAllFilesInTheDirectory( $dir, &$results = array() ){
        if( is_dir($dir) ){
          // Remove the default back directory
          $scannedResults = array_diff( scandir($dir) , array('.', '..') ); 
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
            self::retrieveAllFilesInTheDirectory( $itemPath, $results );
            // $results[] = $itemPath;
          }
      
        }// End foreach
      
        return $results;
    }//retrieveAllFilesInTheDirectory

}// End of PluginClassLoader definition