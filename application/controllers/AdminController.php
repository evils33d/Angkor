<?php

class AdminController extends Zend_Controller_Action
{

	protected $_config ;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;
	
	
    public function init()
    {

        $bootstrap = $this->getInvokeArg('bootstrap');
    	$this->_config = $bootstrap->getOptions();
    	
    	$this->view->urlaplicacion = $this->_config['urlaplicacion'];
    	$this->view->nombre_sitio = $this->_config['nombre_sitio'];
    	$this->view->skin = $this->_config['skin'];
    	
    	$resource = $bootstrap->getPluginResource('db');    	
    	$db = $resource->getDbAdapter();
    	Zend_Registry::set("db", $db);
    	
    	$perfil = 0;
    	$auth = Zend_Auth::getInstance();
    	
    	$this->view->rol = "";
    	$this->view->id_usuario = "";
    	$this->view->tipoperfil = "";
    	
    	if($auth->hasIdentity()) {
    		$user = $auth->getIdentity();
    	
    		$perfil = $user->lc02_idPerfil;
    		$id_uduario =$user->lc01_idUsuario;

    		
    		$tipoperfilres = new Application_Model_PerfilMapper();
    		
    		$tipoperfil = $tipoperfilres->obtienePerfilById($perfil);
    	
    		
    		$this->view->rol = $perfil;
    		$this->view->id_usuario =$id_uduario;
    		$this->view->tipoperfil = $tipoperfil;
    	}
    	

    	
    }

    public function indexAction()
    {
    	$this->_helper->layout->setLayout('layoutadmin');
    	$auth = Zend_Auth::getInstance();
    	if($auth->hasIdentity()) {
    		$user = $auth->getIdentity();
    		 
    		$perfil = $user->lc02_idPerfil;
    		$idUsuario = $user->lc01_idUsuario;
    	}
    	 
    	 
    	/*$proyectosres = new Application_Model_ProyectoMapper();
    	 
    	$proyectos = $proyectosres->obtieneProyectos($perfil,$idUsuario);
    	
    	$this->view->proyectos = $proyectos;
    	 */
    	
    }
    
  
    
    

}

