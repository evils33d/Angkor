<?php

class SistemaController extends Zend_Controller_Action
{

	protected $_config ;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;

    public function init()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
    	$this->_config = $bootstrap->getOptions();
    	
    	$this->view->nombre_sitio = $this->_config['nombre_sitio'];
    	$this->view->skin = $this->_config['skin'];
    	
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
    		
    		$this->_rol=$perfil;
    		$this->_idusuario=$id_uduario;
    		$this->_tipoperfil = $tipoperfil;
    		
    	}

    }

    public function indexAction()
    { 
    	if($this->_rol == 7){
    		$this->inicioAction();
    		//$this->_redirect('admin/index');
    	}else{    	
    		
	    	$this->inicioAction();    	
    	}
    }
       
    public function inicioAction()
    {
    	$this->_helper->layout->setLayout('layoutsistema');
    	$auth = Zend_Auth::getInstance();
    	if($auth->hasIdentity()) {
    		$user = $auth->getIdentity();
    	
    		$perfil = $user->lc02_idPerfil;
    		$idUsuario = $user->lc01_idUsuario;
    	}  	 
    	
    	
    	$this->_helper->viewRenderer('inicio'); 	
    	  	
    	
    }
        
    public function ingresoAction()
    {
    	$this->_helper->layout->setLayout('layoutproyecto');
    	
    	$bootstrap = $this->getInvokeArg('bootstrap');
    	$config = $bootstrap->getOptions();
    	
    	$tiendasres    = new Application_Model_TiendaMapper();
    	$comentariores = new Application_Model_ComentarioMapper();
    	$coordinadoresres = new Application_Model_CoordinadorMapper();
    	
    	
    	
    	$this->view->tiendas = array();
    	$this->view->tipos = array();
    	
    	$tiendas = $tiendasres->obtieneTiendas();
    	$tipos = $comentariores->obtieneTiposComentario();
    	$coordinadores = $coordinadoresres->obtieneCoordinadores();
    	
    	$this->view->tiendas = $tiendas;
    	$this->view->tipos = $tipos;
    	$this->view->coordinadores = $coordinadores;
    	

    }
    
    public function buscaporestadosajaxAction()
    {
    	$this->_helper->layout->disableLayout();
    
    	$proyectores = new Application_Model_ProyectoMapper();
    
    	$proyectos = $proyectores->buscarPorEstados();
    	
    	$data = array();
    	foreach($proyectos as $key => $proyecto){
    		
    		$data[] = array ("name" => $proyecto['ap07_nombreEstado'], "y" => intval($proyecto['contador']));
    		
    	}
    
    	$this->view->data = $data;
    
    
    
    }
        
    public function buscaporcoordinadoresajaxAction()
    {
    	$this->_helper->layout->disableLayout();
    
    	$proyectores = new Application_Model_ProyectoMapper();
    	$estadosres = new Application_Model_EstadoProyectoMapper();
    
    	$coordinadores = $proyectores->buscarPorCoordinadores();
    	$estados = $estadosres->obtieneEstados();
    
    	
    	$entry = array();
    	$i =1;
    	foreach($estados as $key1 => $estado){
    		
    		foreach($coordinadores as $key2 => $coordinador){
    			
    			foreach($coordinador['data'] as $key2 => $dato){    			
		    			if($estado['ap07_idEstado'] == $dato['ap07_idEstado']){
		    				$entry[$estado['ap07_nombreEstado']][$coordinador['nombre_coordinador']] =intval($dato['contador']);
		    			}    			 		
    			}
    		}
    		$entry2[] = array("name" => $estado['ap07_nombreEstado'],"data" => $entry[$estado['ap07_nombreEstado']]);
    	}
    	
    	
    	$this->view->data = $entry2;
    
    
    
    }
    
    

}

