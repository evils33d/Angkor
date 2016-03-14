<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initAppConfig()
	{
		
		
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('MyProject_');
		
		$objFront = Zend_Controller_Front::getInstance();
		$objFront->registerPlugin(new MyProject_Controller_Plugin_ACL(), 1);
		return $objFront;
		
		
	}
	
	


}

