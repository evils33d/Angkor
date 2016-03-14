<?php
class CobroController extends Zend_Controller_Action 
{
	protected $_config;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;
	
	public function init()
	{
		$bootstrap = $this->getInvokeArg ( 'bootstrap' );
		$this->_config = $bootstrap->getOptions ();
		
		$this->view->nombre_sitio = $this->_config ['nombre_sitio'];
		$this->view->skin = $this->_config ['skin'];
		
		$perfil = 0;
		$auth = Zend_Auth::getInstance ();
		
		$this->view->rol = "";
		$this->view->id_usuario = "";
		$this->view->tipoperfil = "";
		
		if ($auth->hasIdentity ()) {
			$user = $auth->getIdentity ();
			
			$perfil = $user->lc02_idPerfil;
			$id_uduario = $user->lc01_idUsuario;
			
			$tipoperfilres = new Application_Model_PerfilMapper ();
			
			$tipoperfil = $tipoperfilres->obtienePerfilById ( $perfil );
			
			$this->view->rol = $perfil;
			$this->view->id_usuario = $id_uduario;
			$this->view->tipoperfil = $tipoperfil;
			
			$this->_rol = $perfil;
			$this->_idusuario = $id_uduario;
			$this->_tipoperfil = $tipoperfil;
		}
		
		$this->_helper->contextSwitch ()->addActionContext ( 'listadojsonAction', array (
				'json' 
		) )->initContext ();
	}
	
	public function indexAction() {
		$this->_helper->layout->setLayout ( 'layoutindexceco' );
		
		$cecosres = new Application_Model_CecoMapper ();
		
		$cecos = $cecosres->listar ();
		
		$this->view->cecos = $cecos;
	}
	
  	public function ingresocobroAction()
    {
    	$this->_helper->layout->setLayout('layoutingresocobro');
    }
   
    public function enviomasivocobroAction()
    {
    	$this->_helper->layout->setLayout('layoutenviomasivocobro');
    }
    
    public function ingresomasivocobroAction()
    {
    	$this->_helper->layout->setLayout('layoutingresomasivocobro');
    }
        
    public function obtenerestadosAction() {
    
    	$cobro= new Application_Model_CobroMapper();
    	 
    	$estados =array();
    	 
    	foreach ($cobro->obtenerEstadosCobro() as $i => $estado)
    	{    		 
    
    		$data =array(
    				    'id_estado'=>$estado['ci53_idestadocobro'],
	    				'nombre_estado' => $estado['ci53_nombreestado'],
    					'descripcion' => $estado['ci53_descripcion']
    		);
    		 
    		$estados[]=$data;
    	}
    	 
    	$arreglo =array(
    			"data" => $estados,
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);
    }
	
	
}

