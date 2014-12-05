<?php
namespace TinyBS\SimpleMvc\View;

use Zend\View\Model\JsonModel;
use TinyBS\BootStrap\BootStrap;

class TinyBsRender
{
    const DEFAULT_VIEW_STRATEGY = 'VarDumpStrategy';
    static public function render(BootStrap $core, $bootstrapResult){
    	static::renderPrepare($core);
        $resultArray = null;
        if(($bootstrapResult instanceof JsonModel) or  is_callable(array($bootstrapResult, 'getVariables')))
            $resultArray = call_user_func_array(array($bootstrapResult, 'getVariables'), array());
        elseif(is_array($bootstrapResult)) $resultArray = $bootstrapResult;
        return (new StrategyFactory())->getInstance(self::DEFAULT_VIEW_STRATEGY)->render($resultArray);
	}
	static public function renderPrepare($core){
		
	}
}