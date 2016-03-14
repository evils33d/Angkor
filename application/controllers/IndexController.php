<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
    	$config = $bootstrap->getOptions();
    	
    	$this->view->urlaplicacion = $config['urlaplicacion'];
    	$this->view->nombre_sitio = $config['nombre_sitio'];
    	$this->view->skin = $config['skin'];
    	
    	$resource = $bootstrap->getPluginResource('db');    	
    	$db = $resource->getDbAdapter();
    	Zend_Registry::set("db", $db);
    	
    }

    public function indexAction()
    {
    	   		
     $this->_redirect('/index.php/sistema/index/');
    	
   
    }

    
    

}

