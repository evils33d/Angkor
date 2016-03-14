<?php
class UsuarioController extends Zend_Controller_Action 
{	
	protected $_config;	
	
	public function init() {
		
		$bootstrap = $this->getInvokeArg ( 'bootstrap' );
		$this->_config = $bootstrap->getOptions ();
		
		$this->view->nombre_sitio = $this->_config ['nombre_sitio'];
		$this->view->skin = $this->_config ['skin'];
	}
	
	public function indexAction() {
		$this->_helper->layout->setLayout ( 'layoutsistema' );
	}
	
	public function mantenedorusuariosAction() {
		$this->_helper->layout->setLayout ( 'layoutmantenedorusuarios' );	
	}
	
	public function ingresarAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorusuarios' );
		
		$userMapper = new Application_Model_MantenedorUsuarioMapper ();
		
		$response=array();
		
		if (! empty ( $_REQUEST ['nombreUsuarioInput'] ) && ! empty ( $_REQUEST ['emailUsuarioInput'] ) && ! empty ( $_REQUEST ['perfilUsuarioSelect'] ) && ! empty ( $_REQUEST ['passwordUsuario'] )) {
				
			if (! $userMapper->verificaUsuariobyEmail ( $_REQUEST ['emailUsuarioInput'] )) {
				$datosUsuario = array (
						"nombre" => $_REQUEST ['nombreUsuarioInput'],
						"email" => $_REQUEST ['emailUsuarioInput'],
						"pass" => $_REQUEST ['passwordUsuario'],
						"perfil" => $_REQUEST ['perfilUsuarioSelect']
				);
		
				$res = $userMapper->ingresarUsuario ( $datosUsuario );
		
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
	
	public function eliminarAction(){
		
		$userMapper = new Application_Model_MantenedorUsuarioMapper ();
		
		$response=array();
		
		if (!empty($_REQUEST ['idUsuario'])) {
			
			if($userMapper->verificaUsuariobyId($_REQUEST ['idUsuario']))
			{
				if($userMapper->eliminaUsuario($_REQUEST ['idUsuario']))
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
				$response[0]='2';
			}

		}
		
		$arreglo =array(
				"eliminar" => $response
		);		
		
		return $this->_helper->json->sendJson($arreglo);
		
	}
	
	public function listarAction()
	{
		
		$this->_helper->layout->setLayout ( 'layoutmantenedorusuarios' );
		
		$userMapper = new Application_Model_MantenedorUsuarioMapper ();
		
		$listadoUser =array();
		
		if(sizeof($userMapper->listadoUsuarios())>0)
		{
			foreach ( $userMapper->listadoUsuarios() as $i => $user ) 
			{					
				$data =array($listadoUser[]['lc01_idUsuario']= $user['lc01_idUsuario'],
						$listadoUser[]['lc01_nombreUsuario']= $user['lc01_nombreUsuario'],
						$listadoUser[]['lc02_idPerfil']= $user['lc02_idPerfil'],
						$listadoUser[]['lc01_emailUsuario']= $user['lc01_emailUsuario']
				);
					
				$listadoUsusuarios[] = $data;
			}
		}
		else 
		{
			$listadoUsusuarios = [];
		}
		
		$arreglo =array(			
				"data" => $listadoUsusuarios
		);		
			
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function listartodosAction()
	{
	
		$this->_helper->layout->setLayout ( 'layoutmantenedorusuarios' );
	
		$userMapper = new Application_Model_MantenedorUsuarioMapper ();
	
		$listadoUser =array();
	
		if(sizeof($userMapper->listadoTodosUsuarios())>0)
		{
			foreach ( $userMapper->listadoTodosUsuarios() as $i => $user )
			{
				$data =array($listadoUser[]['lc01_idUsuario']= $user['lc01_idUsuario'],
						$listadoUser[]['lc01_nombreUsuario']= $user['lc01_nombreUsuario'],
						$listadoUser[]['lc02_idPerfil']= $user['lc02_idPerfil'],
						$listadoUser[]['lc01_emailUsuario']= $user['lc01_emailUsuario']
				);
					
				$listadoUsusuarios[] = $data;
			}
		}
		else
		{
			$listadoUsusuarios = [];
		}
	
		$arreglo =array(
				"data" => $listadoUsusuarios
		);
			
		return $this->_helper->json->sendJson($arreglo);
	}
		
	public function modificarAction()
	{		
		$this->_helper->layout->setLayout ( 'layoutmantenedorusuarios' );
		
		$userMapper = new Application_Model_MantenedorUsuarioMapper ();
		
		$response =array();
		
		if(! empty ($_REQUEST['idUsuarioEdit']) && 
		   ! empty ($_REQUEST['nombreUsuarioEdit']) && 
		   ! empty ($_REQUEST['emailUsuarioEdit']) && 
		   ! empty ($_REQUEST['passwordUsuarioEdit']) && 
		   ! empty ($_REQUEST['perfilUsuarioSelectEdit'])){
			
			//if(!$userMapper->verificaUsuariobyEmail($_REQUEST['emailUsuarioEdit']))
			//{
				$datosUsuarioEdit = array (
						"nombreEdit" => $_REQUEST ['nombreUsuarioEdit'],
						"emailEdit" => $_REQUEST ['emailUsuarioEdit'],
						"passEdit" => $_REQUEST ['passwordUsuarioEdit'],
						"perfilEdit" => $_REQUEST ['perfilUsuarioSelectEdit'],
						"idEdit" => $_REQUEST ['idUsuarioEdit']
				);
					
				$res = $userMapper-> modificarUsuario ( $datosUsuarioEdit );
				
				if($res)
				{
					$response[0]='1';
				}
				else
				{
					$response[0]='2';
				}
			//}
			//else
			//{
				//$response[0]='3';
			//}
		}
		
		$arreglo =array(
				"edicion" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);
		
	}
	
	public function obtenerAction(){
		
		$this->_helper->layout->setLayout ( 'layoutmantenedorusuarios' );
		
		$userMapper = new Application_Model_MantenedorUsuarioMapper ();
		
		$datosUsuario=array();
		
		
		if(!empty($_REQUEST ['idUsuario'])){
			foreach ($userMapper->datosUsuariosById($_REQUEST ['idUsuario']) as $i => $user)
			{
				$data =array(
							 $datosUsuario[$i]['lc01_idUsuario']= $user['lc01_idUsuario'],
							 $datosUsuario[$i]['lc01_nombreUsuario']= $user['lc01_nombreUsuario'],
							 $datosUsuario[$i]['lc02_idPerfil']= $user['lc02_idPerfil'],
							 $datosUsuario[$i]['lc01_emailUsuario']= $user['lc01_emailUsuario'],
							 $datosUsuario[$i]['lc01_contrasenaUsuario']=$user['lc01_contrasenaUsuario']
							);
				
			}
		}
		
		$arreglo =array(
				"usuario" => $data,							
		);

		return $this->_helper->json->sendJson($arreglo);		
	}

	
}

