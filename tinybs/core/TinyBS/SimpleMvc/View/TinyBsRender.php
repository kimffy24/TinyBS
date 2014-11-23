<?php
namespace TinyBS\SimpleMvc\View;

use Zend\View\Model\JsonModel;

class TinyBsRender
{
    static public function render($bootstrapResult){
        $resultArray = null;
        if(($bootstrapResult instanceof JsonModel) or  is_callable(array($bootstrapResult, 'getVariables')))
            $resultArray = call_user_func_array(array($bootstrapResult, 'getVariables'), array());
        elseif(is_array($bootstrapResult)) $resultArray = $bootstrapResult;
        header('Content-type: application/json');
	    echo json_encode($resultArray);
	    return $resultArray;
	}
}