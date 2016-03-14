<?php
class SociedadController extends Zend_Controller_Action 
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
	
	public function mantenedorsociedadesAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorsociedades' );
	}	
	
	public function registrarAction()
	{
		$sociedadMapper = new Application_Model_SociedadMapper ();
	
		$response=array();
	
		if (! empty ( $_REQUEST ['idSociedad'] ) &&
			! empty ( $_REQUEST ['impuestoSociedad'] )) {						
					
				$datosSociedad = array (
						"idSociedad" => $_REQUEST ['idSociedad'],
						"impuestoSociedad" => $_REQUEST ['impuestoSociedad']
				);			
				
				if($sociedadMapper->buscarSociedadById($datosSociedad))
				{					
					$res = $sociedadMapper->modificarSociedad( $datosSociedad );
				}
				else
				{
					$res = $sociedadMapper->guardarSociedad( $datosSociedad );
				}
					
					if ($res)
					{
						$response[0]='1';
					}
					else
					{
						$response[0]='2';
					}				
		}
	
		$arreglo =array(
				"guardado" => $response
		);
	
		return $this->_helper->json->sendJson($arreglo);
	
	}

	public function obtenervalorsociedadAction()
	{	
		$sociedadMapper = new Application_Model_SociedadMapper();
	
		if(!empty($_REQUEST ['idSociedad']))
		{
			if(sizeof($sociedadMapper->valoSociedadoById($_REQUEST ['idSociedad']))>0)
			{			
				foreach ($sociedadMapper->valoSociedadoById($_REQUEST ['idSociedad']) as $i => $uf)
				{
					$data =array(
							$datoUf[$i]['ci40_valorimpuesto']= $uf['ci40_valorimpuesto']
					);
				}
			}
		}
		else 
		{
			$data=[];
		}		
	
		$arreglo =array(
				"valorsociedad" => $data,
		);
	
		return $this->_helper->json->sendJson($arreglo);
	
	}
	
	public function listarAction()
	{
		$sociedadMapper = new Application_Model_SociedadMapper();
		
		$listadoSociedades =array();
		
		if(sizeof($sociedadMapper->obtieneSociedades())>0)
		{
			foreach ( $sociedadMapper->obtieneSociedades() as $i => $sociedad )
			{
				$data =array
				(
						$listadoSociedades[]['ci40_idsociedad']= $sociedad['ci40_idsociedad'],
						$listadoSociedades[]['ci40_tiposociedad']= $sociedad['ci40_tiposociedad']
				);
					
				$listadoSociedad[] = $data;
			}
		}
		else
		{
			$listadoSociedad = [];
		}
		
		$arreglo =array(
				"data" => $listadoSociedad
		);
			
		return $this->_helper->json->sendJson($arreglo);
	}
	
}

