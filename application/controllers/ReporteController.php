<?php

class ReporteController extends Zend_Controller_Action
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

		$this->_helper->layout->setLayout('layoutsistema');

		
    }   
    public function filtrosreportesAction()
    {
    	$this->_helper->layout->setLayout('layoutfiltrosreportes');
    
    }    
    
    public function cobroyestadoAction()
    {
    	$this->_helper->layout->setLayout('layoutcobroyestado');
   
    }
    
    public function consolidadoAction()
    {
    	$this->_helper->layout->setLayout('layoutconsolidado');
    	 
    }
    
    public function cartolabancariaAction()
    {
    	$this->_helper->layout->setLayout('layoutcartolabancaria');
    
    }
    
    public function boleteoAction()
    {
    	$this->_helper->layout->setLayout('layoutboleteo');
    
    }
    


}

