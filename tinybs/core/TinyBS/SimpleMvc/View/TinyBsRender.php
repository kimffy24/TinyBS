<?php
namespace TinyBS\SimpleMvc\View;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use TinyBS\SimpleMvc\View\Strategy\VarDumpStrategy;
use TinyBS\SimpleMvc\View\Strategy\StringStrategy;
use TinyBS\SimpleMvc\View\Strategy\JsonStrategy;
use TinyBS\Utils\RuntimeException;

class TinyBsRender implements ServiceLocatorAwareInterface{
    const DEFAULT_VIEW_STRATEGY = 'vardump';
    
    public function render($bootstrapResult=null){
        if($bootstrapResult === null) return;
    	$this->renderPrepare();
        return call_user_func_array(
                (($this->viewFunction)?
                	$this->viewFunction:
            		array($this->getInstance($this->viewStrategy),'render')),
                array($bootstrapResult));
	}
	

	private $viewFunction = null;
	private $viewStrategy = self::DEFAULT_VIEW_STRATEGY;
	private function renderPrepare(){
		$serviceLocator = $this->getServiceLocator();
	    //获取命中命名空间
		$matchNamespace = $serviceLocator
		      ->get('TinyBS\RouteMatch\Route')
		      ->getMatchNamespace();
		//获取项目配置集合
		$tbsConfig = $serviceLocator->get('config');
		$matchTbsViewConfig = $tbsConfig['tbs_view'][$matchNamespace];
		/*if(isset($matchTbsViewConfig['factory'])) {
			$tbsViewFactory = new $matchTbsViewConfig['factory'];
			if($tbsViewFactory instanceof ViewStrategyInterface)
				$this->viewFunction = array(&$tbsViewFactory, 'render');
		}*/
		if(isset($matchTbsViewConfig['strategy']))
		    $this->viewStrategy = $matchTbsViewConfig['strategy'];
		else $this->viewStrategy=self::DEFAULT_VIEW_STRATEGY;
	}
	
	/**
	 * get View Strategy Instace
	 * @param unknown $name
	 * @throws RuntimeException
	 * @return \TinyBS\SimpleMvc\View\Strategy\ViewStrategyInterface
	 */
	private function getInstance($name='vardump'){
		$targetStrategy = strtolower($name);
		switch($targetStrategy){
			case 'json':
				return new  JsonStrategy();
			case 'string':
				return new StringStrategy();
			case 'print_r':
			case 'vardump':
				return new VarDumpStrategy();
			default :
				throw new RuntimeException(__METHOD__.'() view strategy class '.$targetStrategy.' not found');
		}
	}
	
	/** (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		// TODO Auto-generated method stub
		$this->serviceLocator = $serviceLocator;
	}

	/** (non-PHPdoc)
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
	 */
	public function getServiceLocator() {
		// TODO Auto-generated method stub
		return $this->serviceLocator;
	}

	private $serviceLocator;
}