<?php
class RutController extends Zend_Controller_Action 
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
			
			$this->view->nombrePerfil=$tipoperfilres->obtienePerfilByIdUsuario($id_uduario);
			
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
	
	//Funciones RUTs
	
	//funcion que solo carga la vista ingresar rut
	public function ingresardatosrutAction()
	{
		$this->_helper->layout->setLayout('layoutingresardatosrut');
	}
	
	public function datosrutAction()
	{
		$this->_helper->layout->setLayout ( 'layoutdatosrut' );
	}
	
	public function buscarporsociedadAction()
	{
		$this->_helper->layout->setLayout('layoutbuscarporsociedad');
	}
	
	//funcion que registra en la base de datos el rut asociado al cliente
	public function ingresarAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
    	 
    	$response=array();
    	
    	if (! empty ( $_REQUEST ['idCliente'] ) &&  
    		! empty ( $_REQUEST ['razonSocial'] ) && 
    		! empty ( $_REQUEST ['rut']))
    	{    		
    		if(!$rutMapper->verificaExisteByRut($_REQUEST ['rut']))
    		{
    			if(!$rutMapper->verificaNumeroSociedad($_REQUEST ['numeroSociedad']))
    			{    				
    				
		    			$datosRut = array (
		    					"idCliente" => $_REQUEST ['idCliente'],
		    					"idSociedad" => $_REQUEST ['idSociedad'],
		    					"razonSocial" => $_REQUEST ['razonSocial'],
		    					"rut" => $_REQUEST ['rut'],
		    					"tipoPersona" => $_REQUEST ['tipoPersona'],
		    					"numeroSociedad" => $_REQUEST ['numeroSociedad'],
		    					"valorMensualidad" => $_REQUEST ['valorMensualidad'],
		    					"valorServicios" => $_REQUEST ['valorServicios'],  					
		    					"iva" => $_REQUEST ['iva'],
		    					"f29" => $_REQUEST ['f29'],
		    					"renta" => $_REQUEST ['renta'],
		    					"previred" => $_REQUEST ['previred'],    					
		    					"empresarial" => $_REQUEST ['empresarial'],
		    					"independiente" => $_REQUEST ['independiente'],
		    					"nanas" => $_REQUEST ['nanas'],
		    					"otro" => $_REQUEST ['otro'],
		    					"socio" => $_REQUEST ['socio'],
		    					"trabajadores" => $_REQUEST ['trabajadores']
		    			);			
		    			
		    			$res = $rutMapper->ingresarRut ( $datosRut );
		    			
		    			//obtengo el ultimo id registrado en la base de datos
		    			$idRut=mysql_insert_id();
		    			
		    			$datosMeta = array (
		    					"anioMeta" => $_REQUEST ['anioMeta'],
		    					"montoMeta" => $_REQUEST ['montoMeta'],
		    					"idRut" => $idRut
		    			);
		    			
		    			$datosClaves = array (
		    					"claveSii" => $_REQUEST ['claveSii'],
		    					"clavePrevired" => $_REQUEST ['clavePrevired'],
		    					"idRut" => $idRut
		    			);
		    			
		    			$datosCuentaCorriente = array (
		    					"nombreBanco" => $_REQUEST ['nombreBanco'],
		    					"numeroCuenta" => $_REQUEST ['numeroCuenta'],
		    					"idRut" => $idRut
		    			);
		    			
		    			$datosPersonales=array(
		    					"fechaNacimiento"=> $_REQUEST ['fechaNacimiento'],
		    					"especialidad"=> $_REQUEST ['especialidad'],
		    					"sexo"=> $_REQUEST ['sexo'],
		    					"trabajo1"=> $_REQUEST ['trabajo1'],
		    					"trabajo2"=> $_REQUEST ['trabajo2'],
		    					"seguro"=> $_REQUEST ['seguro'],
		    					"idRut" => $idRut
		    			);
		    			 
		    			if ($res)
		    			{
		    				$response[0]='1'; 
		    				
		    				$resmeta  = $rutMapper->ingresarMeta ( $datosMeta ); 
		    				
		    				if($resmeta){
		    					$response[0]='6';
		    				}
		    				
		    				$resclave = $rutMapper->ingresarClaves ( $datosClaves );
		    				
		    				if($resclave){
		    					$response[0]='7';
		    				}
		    				
		    				$resctacte = $rutMapper->ingresarCuentaCorriente ( $datosCuentaCorriente );
		    				
		    				if($resctacte){
		    					$response[0]='8';
		    				}
		    				
		    				$resdatospersonales = $rutMapper->ingresarDatosPersonales ( $datosPersonales );
		    				
		    				if($resdatospersonales){
		    					$response[0]='9';
		    				}
		    				
		    			}
		    			else
		    			{
		    				$response[0]='2';
		    			}
    			}
    			else
    			{
    				$response[0]='5';
    			}
    		}
    		else
    		{
    			$response[0]='3';
    		}  			
    
    	}else{
    		$response[0]='4';
    	}
    
    	$arreglo =array(
    			"registro" => $response
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
	}
	
	public function listarAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
	
		$listadoRuts = array();
	
		if(sizeof($rutMapper->listadoRut())>0)
		{
			foreach ( $rutMapper->listadoRut() as $i => $ruts )
			{
				$data =array(
						$listadoRuts[]['ci04_idrrut']= $ruts['ci04_idrrut'],
						$listadoRuts[]['ci04_razonsocial']= $ruts['ci04_razonsocial'],
						$listadoRuts[]['ci04_numerosociedad']= $ruts['ci04_numerosociedad'],
						$listadoRuts[]['ci04_rut']= $ruts['ci04_rut'],
						$listadoRuts[]['lc01_nombreUsuario']= $ruts['lc01_nombreUsuario']
				);
	
				$listadoRut[] = $data;
			}
		}
		else
		{
			$listadoRut = [];
		}
	
		$arreglo =array(
				"data" => $listadoRut
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function listadorutbyidclienteAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		$listadoRuts = array();
		
		if(sizeof($rutMapper->listadoRutByIdCliente($_REQUEST['idCliente']))>0)
		{
			foreach ( $rutMapper->listadoRutByIdCliente($_REQUEST['idCliente']) as $i => $ruts )
			{
				$data =array(
						$listadoRuts[]['ci04_idrrut']= $ruts['ci04_idrrut'],
						$listadoRuts[]['ci04_razonsocial']= $ruts['ci04_razonsocial'],
						$listadoRuts[]['ci04_numerosociedad']= $ruts['ci04_numerosociedad'],
						$listadoRuts[]['ci04_rut']= $ruts['ci04_rut']
				);
		
				$listadoRut[] = $data;
			}
		}
		else
		{
			$listadoRut = [];
		}
		
		$arreglo =array(
				"data" => $listadoRut
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	public function listadorutbyejecutivoAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		$listadoRuts = array();
		
		if(!empty($_REQUEST['id'])&&!empty($_REQUEST['desde'])&&!empty($_REQUEST['idEjecutivo']))
		{			
			if(sizeof($rutMapper->listadoRutBusqueda($_REQUEST['id'],$_REQUEST['idEjecutivo'],$_REQUEST['desde']))>0)
			{
				foreach ( $rutMapper->listadoRutBusqueda($_REQUEST['id'],$_REQUEST['idEjecutivo'],$_REQUEST['desde']) as $i => $ruts )
				{
					$data =array(
							$listadoRuts[]['ci04_idrrut']= $ruts['ci04_idrrut'],
							$listadoRuts[]['ci04_razonsocial']= $ruts['ci04_razonsocial'],
							$listadoRuts[]['ci04_numerosociedad']= $ruts['ci04_numerosociedad'],
							$listadoRuts[]['ci04_rut']= $ruts['ci04_rut'],
							$listadoRuts[]['lc01_nombreUsuario']= $ruts['lc01_nombreUsuario']
					);
			
					$listadoRut[] = $data;
				}
			}
			else
			{
				$listadoRut = [];
			}
		}
		else
		{
			$listadoRut=[];
		}
		
		$arreglo =array(
				"data" => $listadoRut
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function listadorutbyfiltroAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
	
		$listadoRuts = array();
		
			$dato=array(
					"idEjecutivo"=>$_REQUEST['idEjecutivo'],
					"razonSocial"=>$_REQUEST['razonSocial'],
					"rut"=>$_REQUEST['rut'],
					"numSodiedad"=>$_REQUEST['numSodiedad'],
			);		
			
			if(count($rutMapper->listadoRutBusquedaFiltro($dato))>0)
			{			
				foreach ( $rutMapper->listadoRutBusquedaFiltro($dato) as $i => $ruts )
				{
					$data =array(
							$listadoRuts[]['ci04_idrrut']= $ruts['ci04_idrrut'],
							$listadoRuts[]['ci04_razonsocial']= $ruts['ci04_razonsocial'],
							$listadoRuts[]['ci04_numerosociedad']= $ruts['ci04_numerosociedad'],
							$listadoRuts[]['ci04_rut']= $ruts['ci04_rut'],
							$listadoRuts[]['lc01_nombreUsuario']= $ruts['lc01_nombreUsuario']
					);
						
					$listadoRut[] = $data;
				}
			}
			else 
			{
				$listadoRut=[];
			}
			
	
		$arreglo =array(
				"data" => $listadoRut
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}
		
	public function obtenerrubyidAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		$listadoRuts = array();	
		
		if(!empty($_REQUEST['idRut']))
		{
			if($rutMapper->verificaIdRutExiste($_REQUEST['idRut']))
			{
				foreach ( $rutMapper->obtenerDatosRutByID($_REQUEST['idRut']) as $i => $ruts )
				{
					$data =array(							
							$listadoRuts[]['ci03_nombre']= $ruts['ci03_nombre'],
							$listadoRuts[]['lc01_idUsuario']= $ruts['lc01_idUsuario'],							
							$listadoRuts[]['ci40_idsociedad']= $ruts['ci40_idsociedad'],							
							$listadoRuts[]['ci04_razonsocial']= $ruts['ci04_razonsocial'],
							$listadoRuts[]['ci04_rut']= $ruts['ci04_rut'],
							$listadoRuts[]['ci04_tipopersona']= $ruts['ci04_tipopersona'],							
							$listadoRuts[]['ci04_numerosociedad']= $ruts['ci04_numerosociedad'],
							
							$listadoRuts[]['ci04_valormensualidad']= $ruts['ci04_valormensualidad'],
							$listadoRuts[]['ci04_valorservicios']= $ruts['ci04_valorservicios'],	
							
							$listadoRuts[]['ci04_iva']= $ruts['ci04_iva'],
							$listadoRuts[]['ci04_f29']= $ruts['ci04_f29'],
							$listadoRuts[]['ci04_renta']= $ruts['ci04_renta'],
							$listadoRuts[]['ci04_previred']= $ruts['ci04_previred'],							
							$listadoRuts[]['ci04_empresarial']= $ruts['ci04_empresarial'],	
							$listadoRuts[]['ci04_independiente']= $ruts['ci04_independiente'],
							$listadoRuts[]['ci04_nanas']= $ruts['ci04_nanas'],
							$listadoRuts[]['ci04_otro']= $ruts['ci04_otro'],
							$listadoRuts[]['ci04_socio']= $ruts['ci04_socio'],
							$listadoRuts[]['ci04_trabajadores']= $ruts['ci04_trabajadores']							
					);
				}	
			}
			else 
			{
				$data=null;
			}			
		}	
		
		$arreglo =array(
				"rut" => $data,
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
			
	public function modificarAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
	
		$response=array();		 
		
		if (! empty ( $_REQUEST ['idRut'] ) && 
			! empty ( $_REQUEST ['razonSocial'] ) && 
			! empty ( $_REQUEST ['rut']))
		{
	
			$datosRut = array (
						"idRut" => $_REQUEST ['idRut'],
						"idSociedad" => $_REQUEST ['idSociedad'],
						"razonSocial" => $_REQUEST ['razonSocial'],
						"rut" => $_REQUEST ['rut'],
						"tipoPersona" => $_REQUEST ['tipoPersona'],
						"numeroSociedad" => $_REQUEST ['numeroSociedad'],
						"valorMensualidad" => $_REQUEST ['valorMensualidad'],
						"valorServicios" => $_REQUEST ['valorServicios'],					
						"iva" => $_REQUEST ['iva'],
						"f29" => $_REQUEST ['f29'],
						"renta" => $_REQUEST ['renta'],
						"previred" => $_REQUEST ['previred'],
						"empresarial" => $_REQUEST ['empresarial'],
    					"independiente" => $_REQUEST ['independiente'],
    					"nanas" => $_REQUEST ['nanas'],
    					"otro" => $_REQUEST ['otro'],
    					"socio" => $_REQUEST ['socio'],
    					"trabajadores" => $_REQUEST ['trabajadores']   
				);
	
				$res = $rutMapper->modificarRut ( $datosRut );
	
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
			$response[0]='4';
		}
	
		$arreglo =array(
				"modifcar" => $response
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}

	public function eliminarAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		$response=array();
			
		if (! empty ( $_REQUEST ['idRut'] ))
		{				
			if($rutMapper->verificaIdRutExiste( $_REQUEST ['idRut'] ))
			{
				$res = $rutMapper->eliminarRutAsociado ( $_REQUEST ['idRut']  );
				
				if ($res)
				{
					$response[0]='1';
				}
				else
				{
					$response[0]='2';
				}
			}		
		}
		
		$arreglo =array(
				"eliminar" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	public function obtienerutrentaAction()
	{
		$rutMapper=new Application_Model_RutMapper();
		
		$rutsRenta=array();
		$data = array();
		
		if(!empty($_REQUEST['idCliente']))
		{
			if(sizeof($rutMapper->obtieneRutRenta($_REQUEST['idCliente']))>0)
			{
				foreach ($rutMapper->obtieneRutRenta($_REQUEST['idCliente']) as $i => $ruts)
				{					
					$data =array(
							$listadoRuts[]['ci04_idrrut']= $ruts['ci04_idrrut'],
							$listadoRuts[]['ci04_rut']= $ruts['ci04_rut'],
							$listadoRuts[]['ci_monto']= "$ ".(number_format($ruts['ci_monto'],0,",",".")),
							$listadoRuts[]['ci_anio']= $ruts['ci_anio']
					);
					
					$rutsRenta[]=$data;
				}
			}
			else
			{
				$rutsRenta=$data;
			}
		}
		else
		{
			$rutsRenta=$data;
		}
		
		$arreglo = array(				
				"data" => $rutsRenta	
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}	
	
	public function obtinerutrentabyanioAction()
	{
		$rutMapper=new Application_Model_RutMapper();
		
		$rutsRenta=array();
		$data=array();
		
		if(!empty($_REQUEST['idCliente'])&&!empty($_REQUEST['anio']))
		{
			if(sizeof($rutMapper->obtieneRutRentaByAnio($_REQUEST['idCliente'],$_REQUEST['anio']))>0)
			{
				foreach ($rutMapper->obtieneRutRentaByAnio($_REQUEST['idCliente'],$_REQUEST['anio']) as $i => $ruts)
				{
					$data =array(
							$listadoRuts[]['ci04_idrrut']= $ruts['ci04_idrrut'],
							$listadoRuts[]['ci04_rut']= $ruts['ci04_rut'],
							$listadoRuts[]['ci_monto']= "$ ".$ruts['ci_monto'],
							$listadoRuts[]['ci_anio']= $ruts['ci_anio']
					);
						
					$rutsRenta[]=$data;
				}
			}
			else
			{
				$rutsRenta=$data;
			}
		}
		else
		{
			$rutsRenta=$data;
		}
		
		$arreglo = array(
				"data" => $rutsRenta
		);
		
		
		
		return $this->_helper->json->sendJson($arreglo);
	}
		
	public function listadobancosAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		$listBancos=array();
		
		if(sizeof($rutMapper->listadoBancos())>0)
		{
			foreach ( $rutMapper->listadoBancos() as $i => $bancos )
			{
				$data =array(
						$listadoBanco[]['ci58_idbanco']= $bancos['ci58_idbanco'],
						$listadoBanco[]['ci58_nombrebanco']= $bancos['ci58_nombrebanco']
				);
				
				$listBancos[]=$data;
			}
		}
		else
		{
			$listBancos = [];
		}
		
		$arreglo =array(
				"bancos" => $listBancos
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function obtienetotalcobrospendientesAction()
	{
		$rutMapper=new Application_Model_RutMapper();
		
		$montoPendiente=0;
		
		$data=array();
		
		if(!empty($_REQUEST['idRut']))
		{
			$montoPendiente=$rutMapper->totalCobrosPendientesDelRut($_REQUEST['idRut']);
			
			if($montoPendiente!=null)
			{
				$data=array(
						"valor" => (number_format(intval($montoPendiente),0,",",".")),
						"estado" =>'1'
				);
			}
			else
			{
				$data=array(
						"valor" => 0,
						"estado" =>'1'
				);
			}			
		}
		else 
		{
			$data=array(
					"valor" => $montoPendiente,
					"estado" =>'2'
			);
		}
		
		return $this->_helper->json->sendJson($data);
	}	
	
	//Funciones metas de boleteo
	
	public function ingresarmetaAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
	
		$response=array();
	
		if (! empty ( $_REQUEST ['anioMeta'] ) && ! empty ( $_REQUEST ['montoMeta']) && ! empty ( $_REQUEST ['idRut']))
		{
				
			$datosMeta = array (
					"anioMeta" => $_REQUEST ['anioMeta'],
					"montoMeta" => $_REQUEST ['montoMeta'],
					"idRut" => $_REQUEST ['idRut']
			);
	
			if($rutMapper->verificaExisteAnioMeta($datosMeta))
			{
				$id = $rutMapper->obtenerIdMeta($datosMeta);
	
				$res= $rutMapper->modificarMeta ( $datosMeta , $id );
			}
			else
			{
				$res = $rutMapper->ingresarMeta ( $datosMeta );
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
				"guardar" => $response
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}

	public function listarmetaAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		$listadoMetas = array();
		
		if(sizeof($rutMapper->obtenerMetasByIdRut($_REQUEST['idRut']))>0)
		{
			foreach ( $rutMapper->obtenerMetasByIdRut($_REQUEST['idRut']) as $i => $ruts )
			{
				$data =array(
						$listadoMetas[]['ci10_anio']= $ruts['ci10_anio'],
						$listadoMetas[]['ci10_monto']= (number_format($ruts['ci10_monto'],0,",",".")),
				);
		
				$listadoMeta[] = $data;
			}
		}
		else
		{
			$listadoMeta = [];
		}
		
		$arreglo =array(
				"data" => $listadoMeta
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	//Funciones claves Sii y Previred
	
	public function obtenerclavescyidrutAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		$data=array();
		
		if(!empty($_REQUEST['idRut']))
		{
			if($rutMapper->verificaIdRutExiste($_REQUEST['idRut']))
			{
				foreach ( $rutMapper->obtenerClavesByIdRut($_REQUEST['idRut']) as $i => $clave )
				{
					$data =array(
							$claves[]['ci11_sii']= $clave['ci11_sii'],
							$claves[]['ci11_previred']= $clave['ci11_previred']
					);
				}
			}
			else
			{
				$data=[];
			}
		}
		
		$arreglo =array(
				"claves" => $data
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	public function actualizaclavesAction()
	{
		$rutMapper= new Application_Model_RutMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['idRut'])&&
		   !empty($_REQUEST['sii'])&&
		   !empty($_REQUEST['previred']))
		{
			
			$data=array(
				"idRut" => $_REQUEST['idRut'],
				"claveSii" => $_REQUEST['sii'],
				"clavePrevired" => $_REQUEST['previred']					
			);
			
			if ($rutMapper->existenClaves($_REQUEST['idRut']))
			{
				$res=$rutMapper->actualizaClaves($data);	
			}
			else
			{
				$res=$rutMapper->ingresarClaves($data);
			}
			
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
			$response[0]='2';
		}
		
		$arreglo=array(				
			"actualiza"=>$response	
		);
		
		return $this->_helper->json->sendJson($arreglo);
		
	}	
	
	//Funcion obtener datos banco
	
	public function obtenercuentasbyidrutAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
		
		if(!empty($_REQUEST['idRut']))
		{			
			if($rutMapper->verificaIdRutExiste($_REQUEST['idRut']))
			{
				if(sizeof($rutMapper->obtenerCuentaCorrienteByIdRut($_REQUEST['idRut']))>0)
				{
					foreach ( $rutMapper->obtenerCuentaCorrienteByIdRut($_REQUEST['idRut']) as $i => $cuenta )
					{
						$data =array(
									$cuentas[]['ci58_idbanco']= $cuenta['ci58_idbanco'],
									$cuentas[]['ci14_numerocuenta']= $cuenta['ci14_numerocuenta'],
									$cuentas[]['ci58_nombrebanco']= $cuenta['ci58_nombrebanco']
								);
					}
				}
				else
				{
					$data=null;
				}
				
			}
			else
			{
				$data=null;
			}
		}
	
		$arreglo =array(
				"cuenta" => $data
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}

	public function actualizadatosbancariosAction()
	{
		$rutMapper= new Application_Model_RutMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['idRut'])&&
		   !empty($_REQUEST['idBanco'])&&
		   !empty($_REQUEST['numeroCuenta']))
		{
				
			$data=array(
					"idRut" => $_REQUEST['idRut'],
					"nombreBanco" => $_REQUEST['idBanco'],
					"numeroCuenta" => $_REQUEST['numeroCuenta']
			);
			
			
			if(!$rutMapper->verificaExisteNumeroCuenta($_REQUEST['numeroCuenta']))
			{
				if ($rutMapper->verificaCuentaCorrienteExite($_REQUEST['idRut']))
				{
					$res=$rutMapper->actualizaCuentaCorriente($data);
				}
				else
				{
					$res=$rutMapper->ingresarCuentaCorriente($data);
				}
				
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
				$response[0]='3';
			}	
				
		}
		else
		{
			$response[0]='2';
		}
		
		$arreglo=array(
				"actualiza"=>$response
		);
		
		return $this->_helper->json->sendJson($arreglo);
		
	}
	
	//funcion Obtener datos personales
	
	public function obtenerdatospersonalesbyidrutAction()
	{
		$rutMapper = new Application_Model_RutMapper ();
	
		$personales = array();
	
		if(!empty($_REQUEST['idRut']))
		{
			if($rutMapper->verificaIdRutExiste($_REQUEST['idRut']))
			{
				if($rutMapper->verificaRutEnDatosPersonales($_REQUEST['idRut']))
				{
					foreach ( $rutMapper->obtenerDatosPersonalesByIdRut($_REQUEST['idRut']) as $i => $personal)
					{
						$data =array(
								$personales[]['ci52_fechanacimiento']= $personal['ci52_fechanacimiento'],
								$personales[]['ci52_especialidad']= $personal['ci52_especialidad'],
								$personales[]['ci52_sexo']= $personal['ci52_sexo'],
								$personales[]['ci52_trabajo1']= $personal['ci52_trabajo1'],
								$personales[]['ci52_trabajo2']= $personal['ci52_trabajo2'],
								$personales[]['ci52_seguro']= $personal['ci52_seguro'],
						);
					}
				}
				else 
				{
					$data=null;
				}				
			}
			else
			{
				$data=null;
			}
		}
	
		$arreglo =array(
				"datospersonales" => $data,
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function actualizadatospersonalesAction()
	{
		$rutMapper= new Application_Model_RutMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['idRut']))
		{		
			$data=array(
					
					"idRut" => $_REQUEST['idRut'],
					"fechaNacimiento" => $_REQUEST['fechaNacimiento'],
					"especialidad" => $_REQUEST['especialidad'],
					"sexo" => $_REQUEST['sexo'],
					"trabajo1" => $_REQUEST['trabajo1'],
					"trabajo2" => $_REQUEST['trabajo2'],
					"seguro" => $_REQUEST['seguro']
			);
			
			if ($rutMapper->verificaExistenDatosPesonales($_REQUEST['idRut']))
			{
				$res=$rutMapper->actualizaDatosPersonales($data);
			}
			else
			{
				$res=$rutMapper->ingresarDatosPersonales($data);
			}
		
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
			$response[0]='2';
		}
		
		$arreglo=array(
				"actualiza"=>$response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
}

