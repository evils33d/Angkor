<?php
class ValoresController extends Zend_Controller_Action 
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
	
	public function indexAction() 
	{
		$this->_helper->layout->setLayout ( 'layoutindexceco' );
		
		$cecosres = new Application_Model_CecoMapper ();
		
		$cecos = $cecosres->listar ();
		
		$this->view->cecos = $cecos;
	}
	
	public function mantenedorvaloresAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorvalores' );
	}
	
	//*** Metodos para el Manetenedor de UF ***//
	public function guardarufAction()
	{
		$valorMapper = new Application_Model_ValoresMapper ();
	
		$response=array();
	
		if (! empty ( $_REQUEST ['valorUf'] ) &&
			! empty ( $_REQUEST ['anioUf'] ) &&
			! empty ( $_REQUEST ['mesUf'] )) {						
					
				$datosUf = array (
						"valorUf" => $_REQUEST ['valorUf'],
						"anioUf" => $_REQUEST ['anioUf'],
						"mesUf" => $_REQUEST ['mesUf']
				);			
				
				if($valorMapper->buscarAnioMesUf($datosUf))
				{
					$idUf=$valorMapper->obtenerIdUf($datosUf);
					
					$res = $valorMapper->modificarUf( $datosUf , $idUf );
				}
				else
				{
					$res = $valorMapper->guardarUf( $datosUf );
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

	public function obtenerufAction()
	{	
		$valoresMapper = new Application_Model_ValoresMapper();
	
		$datosConcepto=array();
		
		if(!empty($_REQUEST ['anioUf'])&&!empty($_REQUEST['mesUf']))
		{			
			$datosUf = array (				
					"anioUf" => $_REQUEST ['anioUf'],
					"mesUf" => $_REQUEST ['mesUf']
			);
	
			if(sizeof($valoresMapper->valoUfoByAnioMes($datosUf))>0)
			{			
				foreach ($valoresMapper->valoUfoByAnioMes($datosUf) as $i => $uf)
				{
					$data =array(
							$datoUf[]['ci43_valor']= $uf['ci43_valor'],
							$datoUf[]['ci43_iduf']= $uf['ci43_iduf']
					);
				}
			}
			else
			{
				$data=[];
			}
		}
		else 
		{
			$data=[];
		}		
	
		$arreglo =array(
				"valoruf" => $data
		);
	
		return $this->_helper->json->sendJson($arreglo);
	
	}

	public function obtenerufactualAction()
	{
		$valoresMapper=new Application_Model_ValoresMapper();
		
		if(sizeof($valoresMapper->obtenerUfActual())>0)
		{
				foreach ($valoresMapper->obtenerUfActual() as $i => $valor)
				{
					$data =array(
							$datoUf[$i]['ci43_iduf']= $valor['ci43_iduf'],
							$datoUf[$i]['ci43_valor']= $valor['ci43_valor']
					);
				}	
		}
		else 
		{
			$data=[];
		}
		
		$arreglo =array(
				"valoruf" => $data,
		);
		
		return $this->_helper->json->sendJson($arreglo);
		
	}
	
	//*** Metodos para el Manetenedor de TASAS ***//	

	public function guardartasaAction()
	{	
		$valorMapper = new Application_Model_ValoresMapper ();
	
		$response=array();
	
		if (! empty ( $_REQUEST ['valorTasa'] ) &&
			! empty ( $_REQUEST ['anioTasa'] ) && 
			! empty ( $_REQUEST ['mesTasa'] )) {
						
					$datosTasa = array (
							"valorTasa" => $_REQUEST ['valorTasa'],
							"anioTasa" => $_REQUEST ['anioTasa'],
							"mesTasa" => $_REQUEST ['mesTasa']
					);
	
					if($valorMapper->buscarAnioMesTasa($datosTasa))
					{
						$idTasa=$valorMapper->obtenerIdTasa($datosTasa);
							
						$res = $valorMapper->modificarTasa( $datosTasa , $idTasa );
					}
					else
					{
						$res = $valorMapper->guardarTasa( $datosTasa );
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
	
	public function obtenertasaAction()
	{		
		$valoresMapper = new Application_Model_ValoresMapper();
	
		$data=array();
	
		if(!empty($_REQUEST ['anioTasa']))
		{				
			$datosTasa = array (
					"anioTasa" => $_REQUEST ['anioTasa'],
					"mesTasa" => $_REQUEST ['mesTasa']
			);
			
			if(sizeof($valoresMapper->valorTasaByAnioMes($datosTasa))>0)
			{
			
				foreach ($valoresMapper->valorTasaByAnioMes($datosTasa) as $i => $tasa)
				{
					$data = $tasa['ci37_valor'];
				}
			}
		}
		else
		{
			$data=[];
		}
	
		$arreglo =array(
				"valortasa" => $data,
		);
	
		return $this->_helper->json->sendJson($arreglo);
	
	}
		
	public function valortasaactualAction()
	{
		$valoresMapper = new Application_Model_ValoresMapper();
		
		if(sizeof($valoresMapper->valorTasaActual())>0)
		{					
			foreach ($valoresMapper->valorTasaActual() as $i => $tasa)
			{
				$data=array(
						$datoTasa[]['ci37_idtasa']=$tasa['ci37_idtasa'],
						$datoTasa[]['ci37_valor']=$tasa['ci37_valor'],
				);	
				
				
			}
		}
		else 
		{
			$data=[];
		}
		
		$arreglo =array(
				"data" => $data
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function valortasaactualmesanioAction()
	{
		$valoresMapper = new Application_Model_ValoresMapper();
	
		if(!empty($_REQUEST['fecha']))
		{
			
			$fecha=explode('-',$_REQUEST['fecha']);			
			
			$data=array(
					
					"mesTasa"=>$fecha[0],
					"anioTasa"=>$fecha[1],
			);		
			
			
			if(sizeof($valoresMapper->valorTasaByAnioMes($data))>0)
			{
				foreach ($valoresMapper->valorTasaByAnioMes($data)as $i => $tasa)
				{
					$data=array(
							$datoTasa[]['ci37_idtasa']=$tasa['ci37_idtasa'],
							$datoTasa[]['ci37_valor']=$tasa['ci37_valor'],
					);
				}
			}
			else
			{
				$data=[];
			}
			
			
		}
	
		$arreglo =array(
				"data" => $data
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}
	
	
	public function listartasasAction()
	{
		$valoresMapper=new Application_Model_ValoresMapper();
		
		$tasa=array();
		
		foreach ($valoresMapper->listarTasas() as $i => $tasas)
		{
			$data=array(
				$t[]['ci37_idtasa']=$tasas['ci37_idtasa'],
				$t[]['ci37_valor']=$tasas['ci37_valor'],
			);
			
			$tasa[]=$data;
		}	
		
		$arreglo=array(
				"tasas"=>$tasa
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	//*** Metodos para el Manetenedor de RETENCIONES ***//
	
	public function guardaretencionAction()
	{	
		$valorMapper = new Application_Model_ValoresMapper ();
	
		$response=array();
	
		if (! empty ( $_REQUEST ['valorRetencion'] ) &&
			! empty ( $_REQUEST ['anioRetencion'] ) && 
			! empty ( $_REQUEST ['mesRetencion'] ) ) 
		{
	
					$datosRetencion = array (
							"valorRetencion" => $_REQUEST ['valorRetencion'],
							"anioRetencion" => $_REQUEST ['anioRetencion'],
							"mesRetencion" => $_REQUEST ['mesRetencion']
					);
	
					if($valorMapper->buscarAnioMesRetencion($datosRetencion))
					{
						$idRetencion=$valorMapper->obtenerIdRetencion($datosRetencion);
							
						$res = $valorMapper->modificarRetencion( $datosRetencion , $idRetencion );
					}
					else
					{
						$res = $valorMapper->guardarRetencion( $datosRetencion );
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
	
	public function obtenerretencionAction()
	{
		$valoresMapper = new Application_Model_ValoresMapper();
	
		$datosConcepto=array();
	
		$data=array();
		
		if(!empty($_REQUEST ['anioRetencion']))
		{			
			$datosRetencion = array (
					"anioRetencion" => $_REQUEST ['anioRetencion'],
					"mesRetencion" => $_REQUEST ['mesRetencion']
			);
			
			if(sizeof($valoresMapper->valoRetencionoByAnioMes($datosRetencion))>0)
			{
			
				foreach ($valoresMapper->valoRetencionoByAnioMes($datosRetencion) as $i => $retencion)
				{
					$data = $retencion['ci39_valor'];	
				}
			}
		}
		else
		{
			$data=[];
		}
		
		$arreglo =	array(
				"valorretencion" => $data
		);
		
	
		return $this->_helper->json->sendJson($arreglo);
	
	}

	public function valorretencionactualAction()
	{
		$valoresMapper = new Application_Model_ValoresMapper();
		
				
		if(sizeof($valoresMapper->valorRetencionActual())>0)
		{			
			foreach ($valoresMapper->valorRetencionActual() as $i => $retencion)
			{
				$data=array(
						$datoRetencion[$i]['ci39_idretencion']=$retencion['ci39_idretencion'],
						$datoRetencion[$i]['ci39_valor']=$retencion['ci39_valor']
				);
				
				
			}
		}
		else
		{
			$data=[];
		}
		
		$arreglo =	array(
				"data" => $data
		);
		
		
		return $this->_helper->json->sendJson($arreglo);
	}

	public function listarretencionesAction()
	{
		$valoresMapper=new Application_Model_ValoresMapper();
		
		$retenciones=array();
		
		foreach ($valoresMapper->listarRetenciones() as $i => $retencion)
		{
			$data=array(
					$r[]['ci39_idretencion']=$retencion['ci39_idretencion'],
					$r[]['ci39_valor']=$retencion['ci39_valor']
			);
				
			$retenciones[]=$data;
		}
		
		$arreglo=array(
				"retenciones"=>$retenciones
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
}

