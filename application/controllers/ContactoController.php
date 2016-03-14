<?php

class ContactoController extends Zend_Controller_Action
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
    	$this->view->urlaplicacion = $this->_config['urlaplicacion'];
    	
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
    	
    	$this->_helper->contextSwitch()
    	->addActionContext('listadojsonAction', array('json'))
    	->initContext();   	

    }

    public function indexAction()
    { 
		$this->_helper->layout->setLayout('layoutindexceco');
		
		$cecosres = new Application_Model_CecoMapper();   
		
		$cecos = $cecosres->listar();
		
		$this->view->cecos = $cecos;
    }
    
    //esta funcion solo carga la vista
    public function ingresarcontactoAction()
    {
    	$this->_helper->layout->setLayout('layoutingresarcontacto');   	
    }   
    
    public function ingresoAction()
    {  		 
    	$contactoMapper = new Application_Model_ContactoMapper ();
    	 
    	$response=array();
    	 
    	if (! empty ( $_REQUEST ['nombreContacto'] ) &&
    		! empty ( $_REQUEST ['emailContacto'] ) &&    		
    		! empty ( $_REQUEST ['cobro']) &&
    		! empty ( $_REQUEST ['boleteo']) &&
    		! empty ( $_REQUEST ['idCliente'] ))
    	{  
    		
    		if(!$contactoMapper->verificarEmailByEmail($_REQUEST ['emailContacto']))
    		{
    			$datosContacto = array (
    					"nombreContacto" => $_REQUEST ['nombreContacto'],
    					"emailContacto" => $_REQUEST ['emailContacto'],
    					"telefonoContacto" => $_REQUEST ['telefonoContacto'],
    					"celularContacto" => $_REQUEST ['celularContacto'],
    					"cobro" => $_REQUEST ['cobro'],
    					"boleteo" => $_REQUEST ['boleteo'],
    					"idCliente" => $_REQUEST ['idCliente']
    			);
    			
    			$res = $contactoMapper->ingresarContacto ( $datosContacto );
    			 
    			if ($res)
    			{
    				$response[0]='1';
    			}
    			else
    			{
    				$response[0]='2';
    			}
    		}
    		else 
    		{
    			$response[0]='3';
    		}   		
    	}
    	else{
    		$response[0]='4';
    	}
    
    	$arreglo =array(
    			"registro" => $response
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    }
          
    public function listarcontactosbyidclienteAction()
    {
    	$contactoMapper = new Application_Model_ContactoMapper ();
    	 
    	$listadoContactos = array();
    	 
    	if(sizeof($contactoMapper->listadoContactocByIdCliente($_REQUEST['idCliente']))>0)
    	{
    		foreach ( $contactoMapper->listadoContactocByIdCliente($_REQUEST['idCliente']) as $i => $contacto )
    		{
    			$data =array(
    					$listadoContactos[]['ci09_idcontacto']= $contacto['ci09_idcontacto'],
    					$listadoContactos[]['ci09_nombre']= $contacto['ci09_nombre'],
    					$listadoContactos[]['ci09_telefono']= $contacto['ci09_telefono'],
    					$listadoContactos[]['ci09_celular']= $contacto['ci09_celular'],
    					$listadoContactos[]['ci09_email']= $contacto['ci09_email'],
    					$listadoContactos[]['ci09_boleteo']= $contacto['ci09_boleteo'],
    					$listadoContactos[]['ci09_cobro']= $contacto['ci09_cobro']
    			);
    
    			$listadoContacto[] = $data;
    		}
    	}
    	else
    	{
    		$listadoContacto = [];
    	}
    	 
    	$arreglo =array(
    			"data" => $listadoContacto
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    }
    
    public function obtenercontactosbyidcontactoAction()
    {
    	$contactoMapper = new Application_Model_ContactoMapper ();
    
    	$listadoContactos = array();
    
    	if(sizeof($contactoMapper->obtenerContactocByIdContacto($_REQUEST['idContacto']))>0)
    	{
    		foreach ( $contactoMapper->obtenerContactocByIdContacto($_REQUEST['idContacto']) as $i => $contacto )
    		{
    			$data =array(
    					
    					$listadoContactos[$i]['ci09_nombre']= $contacto['ci09_nombre'],
    					$listadoContactos[$i]['ci09_email']= $contacto['ci09_email'],
    					$listadoContactos[$i]['ci09_telefono']= $contacto['ci09_telefono'],
    					$listadoContactos[$i]['ci09_celular']= $contacto['ci09_celular'],    				
    					$listadoContactos[$i]['ci09_boleteo']= $contacto['ci09_boleteo'],
    					$listadoContactos[$i]['ci09_cobro']= $contacto['ci09_cobro']
    			);
    
    			
    		}
    	}
    	else
    	{
    		$data = [];
    	}
    
    	$arreglo =array(
    			"contactos" => $data
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    }    
    
    public function eliminarAction()
    {
    	$contactoMapper = new Application_Model_ContactoMapper();
    
    	$response=array();
    
    	if (!empty($_REQUEST ['idContacto'])) {
    		 
    		
    		if($contactoMapper->eliminarContactoAsociado($_REQUEST ['idContacto']))
    		{
    				$response[0]='1';
    		}
    		else
    		{
    			$response[0]='2';
    		}
    
    	}
    
    	$arreglo =array(
    			"eliminar" => $response
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    
    }
    
    public function modificarAction()
    {
    	$contactoMapper = new Application_Model_ContactoMapper();
    	 
    	$response =array();
    	 
    	if(
    	   ! empty ($_REQUEST['nombreContacto']) &&
    	   ! empty ($_REQUEST['emailContacto'])&&
    	   ! empty ($_REQUEST['cobroContacto']) &&
    	   ! empty ($_REQUEST['boleteoContacto'])&&
    	   ! empty ($_REQUEST['idContacto']) )
    	{   					
    		
    		$datosContactoEdit = array (
    				"nombreContacto" => $_REQUEST ['nombreContacto'],
    				"emailContacto" => $_REQUEST ['emailContacto'],
    				"telefonoContacto" => $_REQUEST ['telefonoContacto'],
    				"celularContacto" => $_REQUEST ['celularContacto'],
    				"cobroContacto" => $_REQUEST ['cobroContacto'],
    				"boleteoContacto" => $_REQUEST ['boleteoContacto'],
    				"idContacto" => $_REQUEST ['idContacto']    				
    		);  		
    					
    		$res = $contactoMapper -> modificarContacto ( $datosContactoEdit );
    
    		if($res)
    		{
    			$response[0]='1';
    		}
    		else
    		{
    			$response[0]='2';
    		}
    				
    	}
    	else
    	{
    		$response[0]='4';
    	}
    	 
    	$arreglo =array(
    			"edicion" => $response
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);
    }
 
}

