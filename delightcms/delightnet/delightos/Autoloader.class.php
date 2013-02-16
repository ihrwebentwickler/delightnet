<?php
/*
 * autoloading of whole envirement
 * 
 * @author    Gunnar von Spreckelsen <service@ihrwebentwickler.de>
 * @package   delightos
 * @version   3.22
 * 
 */
namespace delightnet\delightos;

class Autoloader {
  public static $instance;
  private $arrayExtension = array('.class.php', '.interface.php', '.config.php');

  public static function init(){
    if(self::$instance==NULL){
      self::$instance=new self();
    }
    return self::$instance;
  }

  private function __construct(){
    spl_autoload_register(array($this, 'loadBasicAutoload'));
  }

  private function loadBasicAutoload($class){
    $class = preg_replace('#\\\\#', '/', $class);
    
    $strFileExtension = false;
    foreach($this->arrayExtension as $extension){
      if (file_exists($class . $extension)) {
        $strFileExtension = $extension;
        break;
      }
    }
    
    require ($class . $strFileExtension);
  }
}