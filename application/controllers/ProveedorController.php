<?php
class ProveedorController extends Zend_Controller_Action 
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
		
		$this->_helper->contextSwitch ()->addActionContext ( 'listadovendedoresjsonAction', array (
				'json' 
		) )->initContext ();
	}
	
	public function indexAction() 
	{
		$this->_helper->layout->setLayout ( 'layoutsistema' );
	}
	
	public function mantenedorproveedoresAction() 
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorproveedores' );
	}
	
	public function ingresarAction() 
	{	
		$this->_helper->layout->setLayout ( 'layoutmantenedorproveedores' );
		
		$proveedorMapper=new Application_Model_ProveedorMapper();
		
		$response =array();
		
		if (! empty ( $_REQUEST ['nombreProveedor'] ) ) 
		{
						
			if(!$proveedorMapper->verificaProveedorByNombre($_REQUEST ['nombreProveedor']))
			{
				$datosProveedor = array (
						"nombreProveedor" => $_REQUEST ['nombreProveedor']
				);
		
				$res = $proveedorMapper->ingresarProveedor ( $datosProveedor );
		
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
		
		$arreglo =array(
				"registro" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);	
		
	}
	
	public function listarAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorproveedores' );
		
		$proveedorMapper=new Application_Model_ProveedorMapper();
		
		$listadoProveedores =array();
		
		if(sizeof($proveedorMapper->listadoProveedores())>0)
		{
			foreach ( $proveedorMapper->listadoProveedores() as $i => $prov )
			{
				$data =array(
						$listadoProveedores[$i]['ci28_idproveedor']= $prov['ci28_idproveedor'],
						$listadoProveedores[$i]['ci28_nombreproveedor']= utf8_encode($prov['ci28_nombreproveedor'])
				);
					
				$listadoProv[] = $data;
			}
		}
		else
		{
			$listadoProv = [];
		}
		
		$arreglo =array(
				"data" => $listadoProv
		);
			
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function eliminarAction() 
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorproveedores' );
		$proveedorMapper=new Application_Model_ProveedorMapper();
		
		$response=array();
		
		if (!empty($_REQUEST ['idProveedor'])) {
				
			if($proveedorMapper->verificaConceptById($_REQUEST ['idProveedor']))
			{
				if($proveedorMapper->eliminarProveedor($_REQUEST ['idProveedor']))
				{
					$response[0]='1';
				}
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
	
	/*
	public function obtenerAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorproveedores' );
		$proveedorMapper=new Application_Model_ProveedorMapper();
	}
	*/
	
	public function modificarAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorproveedores' );
		$proveedorMapper=new Application_Model_ProveedorMapper();
		
		$response =array();
		
		if(! empty ($_REQUEST['nombreProveedorEdit']) &&
		   ! empty ($_REQUEST['idProveedor']) )
		{		
	
			$datosProveedorEdit = array (
				"nombreProveedorEdit" => $_REQUEST ['nombreProveedorEdit'],
				"idProveedor" => $_REQUEST ['idProveedor']				
			);
					
			$res = $proveedorMapper -> modificarProveedor ( $datosProveedorEdit );
				
			if($res)
			{
				$response[0]='1';
			}
			else
			{
				$response[0]='2';
			}			
						
		}
		
		$arreglo =array(
				"edicion" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
}

