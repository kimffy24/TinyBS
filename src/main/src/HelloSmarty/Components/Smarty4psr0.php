<?php
namespace HelloSmarty\Components;

/**
 * do something to use smarty libraries.
 * @author Administrator
 *
 */
if(!defined('SMARTY_DIR')){
    define('SMARTY_DIR', dirname(__DIR__).DS.'non_psr_lib'.DS.'Smarty-3.1.21'.DS.'libs'.DS);
    require(SMARTY_DIR . 'Smarty.class.php');
}

use \Smarty;
/**
 * just test use smarty
 * @author Administrator
 *
 */
class Smarty4psr0 extends Smarty
{
    public function __construct(){
        
        parent::__construct();
        
        $workPath = dirname(SMARTY_DIR).DS.'work';
        $this->setTemplateDir($workPath.DS.'templates'.DS);
        $this->setCompileDir($workPath.DS.'templates_c'.DS);
        $this->setConfigDir($workPath.DS.'configs'.DS);
        $this->setCacheDir($workPath.DS.'cache'.DS);
        
        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        
    }
}