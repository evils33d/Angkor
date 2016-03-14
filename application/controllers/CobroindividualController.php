<?php
class CobroindividualController extends Zend_Controller_Action 
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
	
  	public function cobroindividualAction()
    {
    	$this->_helper->layout->setLayout('layoutcobroindividual');
    }
    
    public function modificarAction()
    {
    	$this->_helper->layout->setLayout('layoutmodificarcobro');
    }
        
    public function obtenerformapagoAction()
    {
    	$formapagoMapper = new Application_Model_CobroMapper();
    
    	$listadoFormasPago=array();
    	
    	foreach ($formapagoMapper->obtenerFormasDePago() as $i => $formas)
    	{
    		$data =array(
    				$datosUsuario[$i]['ci35_idformapago']= $formas['ci35_idformapago'],
    				$datosUsuario[$i]['ci35_tipopago']= $formas['ci35_tipopago']
    		);
    		
    		$listadoFormasPago[]=$data;
    	}
    
    	$arreglo =array(
    			"formapago" => $listadoFormasPago,
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    
    }

    public function ingresarcobroAction()
    {    	
    	$cobroMapper= new Application_Model_CobroMapper();
    	
    	$response=array();    
    	
    	if(
    	   !empty($_REQUEST['idConcepto']) &&
    	   !empty($_REQUEST['idRut']) &&	
    	   !empty($_REQUEST['glosa'])&&    	  
    	   !empty($_REQUEST['fecha']) &&
    	   !empty($_REQUEST['idPago'])&&
    	   !empty($_REQUEST['esingreso'])&&
    	   !empty($_REQUEST['valorUF'])&&
    	   isset($_REQUEST['valorMoneda']) != '')
    	{
    		
    		$datosCobro = array (
    				"idConcepto" => $_REQUEST ['idConcepto'],
    				"idRut" => $_REQUEST ['idRut'],
    				"glosa" => $_REQUEST ['glosa'],
    				"montoCobro" => $_REQUEST ['montoCobro'],
    				"observacion" => $_REQUEST ['observacion'],
    				"fecha" => $_REQUEST ['fecha'],
    				"idPago" => $_REQUEST ['idPago'],
    				"valorUF" => $_REQUEST ['valorUF'],
    				"valorMoneda" => $_REQUEST ['valorMoneda'],
    				
    		);
    		
    		if($_REQUEST['esingreso']=='1')
    		{
    			//registro en tabla de honorarios
    			$res=$cobroMapper->ingresarCobroHonorario($datosCobro);
    		}
    		else
    		{
    			//registro en tabla de cobroindividual
    			$res=$cobroMapper->ingresarCobroIndividual($datosCobro);
    			
    		}
    		
    		if($res)
    		{
    			$response[0]='1';
    		}
    		else 
    		{
    			$response[0]='2';
    		}
    		
    		$arreglo =array(
    				"registro" => $response
    		);
    		
    		return $this->_helper->json->sendJson($arreglo);
    		
    	}
    }
    
    //obtengo los datos del cobro asociado al rut
	public function obtenercobrosAction()
	{							
		$cobroMapper= new Application_Model_CobroMapper();
		
		$datosCobro =array();
		
		if(!empty($_REQUEST ['idRut']))
		{
			foreach ($cobroMapper->obtenerCobrosByRut($_REQUEST ['idRut']) as $i => $cobro)
			{				
				$nomtoPago=0;
				
				if($cobro ['ci_montopago']!=null)
				{
					$nomtoPago=$cobro ['ci_montopago'];
				}				
				
				$data =array(						
						$datosCobros []['ci_idCobro'] = $cobro ['ci_idCobro'],
						$datosCobros []['ci33_tipo_ingreso'] = $cobro ['ci33_tipo_ingreso'],
						$datosCobros []['ci_fechaCobro'] = $cobro ['ci_fechaCobro'],
						$datosCobros []['ci_glosaCobro'] = $cobro ['ci_glosaCobro'],
						$datosCobros []['ci04_rut'] = $cobro ['ci04_rut'],
						$datosCobros []['ci_montouf'] = $cobro ['ci_montouf'],
						$datosCobros []['ci_monto'] = "$ ".(number_format($cobro ['ci_monto'],0,",",".")),
						$datosCobros []['ci_montopago'] = "$ ".(number_format($nomtoPago,0,",",".")),
						$datosCobros []['ci53_nombreestado'] = $cobro ['ci53_nombreestado'],
						$datosCobros []['ci35_tipopago'] = $cobro ['ci35_tipopago']						
				);	
				
				$datosCobro[]=$data;
			}
		}	
		
		$arreglo =array(
				"data" => $datosCobro,
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	//obtengo los cobros asociados al cliente
	public function obtenercobrobyidclienteAction()
	{
		$cobroMapper= new Application_Model_CobroMapper();
	
		$datosCobro =array();
	
		if(!empty($_REQUEST ['idCliente']))
		{
			foreach ($cobroMapper->obtenerCobrosByCliente($_REQUEST ['idCliente']) as $i => $cobro)
			{
					
				$data =array(
						$datosCobros []['ci_idCobro'] = $cobro ['ci_idCobro'],
						$datosCobros []['ci33_tipo_ingreso'] = $cobro ['ci33_tipo_ingreso'],
						$datosCobros []['ci_fechaCobro'] =$cobro ['ci_fechaCobro'],
						$datosCobros []['ci_glosaCobro'] = $cobro ['ci_glosaCobro'],
						$datosCobros []['ci04_rut'] = $cobro ['ci04_rut'],
						$datosCobros []['ci_montouf'] = $cobro ['ci_montouf'],
						$datosCobros []['ci_monto'] = "$ ".(number_format($cobro ['ci_monto'],0,",",".")),
						$datosCobros []['ci_montopagado'] = "$ ".(number_format($cobro ['ci_montopagado'],0,",",".")),
						$datosCobros []['ci53_nombreestado'] = $cobro ['ci53_nombreestado'],
						$datosCobros []['ci35_tipopago'] = $cobro ['ci35_tipopago']
				);
	
				$datosCobro[]=$data;
			}
		}
	
		$arreglo =array(
				"data" => $datosCobro,
		);
	
		return $this->_helper->json->sendJson($arreglo);
	}	
	
	public function filtramovimientosAction()
	{
		$cobroMapper= new Application_Model_CobroMapper();
		
		$datosCobro =array();
	
			$dato=array(
					"id"=>$_REQUEST['id'],
					"fechaInicial"=>$_REQUEST['fechaInicial'],
					"fechaFinal"=>$_REQUEST['fechaFinal'],
					"idEstado"=>$_REQUEST['idEstado'],
					"glosa"=>$_REQUEST['glosa'],
					"origen"=>$_REQUEST['origen'],
			);
			
			foreach ($cobroMapper->filtrarMovimientos($dato) as $i => $cobro)
			{				
				$data =array(
						$datosCobros []['ci_idCobro'] = $cobro ['ci_idCobro'],
						$datosCobros []['ci33_tipo_ingreso'] = $cobro ['ci33_tipo_ingreso'],
						$datosCobros []['ci_fechaCobro'] =$cobro ['ci_fechaCobro'],
						$datosCobros []['ci_glosaCobro'] = $cobro ['ci_glosaCobro'],
						$datosCobros []['ci04_rut'] = $cobro ['ci04_rut'],
						$datosCobros []['ci_montouf'] = $cobro ['ci_montouf'],
						$datosCobros []['ci_monto'] = "$ ".(number_format($cobro ['ci_monto'],0,",",".")),
						$datosCobros []['ci_montopagado'] = "$ ".(number_format($cobro ['ci_montopagado'],0,",",".")),
						$datosCobros []['ci53_nombreestado'] = $cobro ['ci53_nombreestado'],
						$datosCobros []['ci35_tipopago'] = $cobro ['ci35_tipopago']
				);
		
				$datosCobro[]=$data;
			}
		
		
		$arreglo =array(
				"data" => $datosCobro,
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
		
	//obtiene el listado de cobros para vista orden de pago
	public function obtenerlistadocobrosAction()
	{		
		$cobroMapper= new Application_Model_CobroMapper();
		
		$cobros=array();
	
			$filtros = array (
								'idCliente' => $_REQUEST['idCliente'],
								'fechaInicio' =>$_REQUEST['fechaInicio'],
								'fechaFinal' =>$_REQUEST['fechaFinal'] ,
								'idRut' => $_REQUEST['idRut'],
								'idUsuario'=> $_REQUEST['idUsuario']
					
			);
			
			foreach ($cobroMapper->listadoCobrosOrdenPago($filtros) as $i =>$cobro)
			{				
				$data=array(
						$datosCobros []['ci_idCobro'] = $cobro ['ci_idCobro'],
						$datosCobros []['ci33_tipo_ingreso'] = $cobro ['ci33_tipo_ingreso'],
						$datosCobros []['ci_fechaCobro'] = $cobro ['ci_fechaCobro'],
						$datosCobros []['ci_glosa'] = $cobro ['ci_glosa'],
						$datosCobros []['ci04_numerosociedad'] = $cobro ['ci04_numerosociedad'],
						$datosCobros []['ci04_rut'] = $cobro ['ci04_rut'],
						$datosCobros []['ci_monto'] ="$ ". (number_format($cobro ['ci_monto'],0,",",".")),
						$datosCobros []['ci_dinerorecivido'] = "$ ". (number_format($cobro ['ci_dinerorecivido'],0,",",".")),
						$datosCobros []['ci_numeroorden'] = $cobro ['ci_numeroorden'],
						$datosCobros []['ci53_nombreestado'] = $cobro ['ci53_nombreestado'],
						$datosCobros []['ci35_tipopago'] = $cobro ['ci35_tipopago'],
						$datosCobros []['ci_observacion'] = $cobro ['ci_observacion'],
				);
					
				$cobros[]=$data;
			}
	
		
		$arreglo =array(
				"data" => $cobros,
		);
			
		return $this->_helper->json->sendJson($arreglo);
	}

	//obtiene el listado de cobros para vista PEC
	public function obtenerlistadocobrospecAction()
	{
		$cobroMapper= new Application_Model_CobroMapper();
		
		$cobros=array();
				
			foreach ($cobroMapper->listadoCobrosPec($_REQUEST['idRut'],$_REQUEST['idCliente'],$_REQUEST['fechaInicio'],$_REQUEST['fechaFinal'],$_REQUEST['idUsuario']) as $i =>$cobro)
			{				
				$data=array(
						$datosCobros []['ci_idCobro'] = $cobro ['ci_idCobro'],
						$datosCobros []['ci33_tipo_ingreso'] = $cobro ['ci33_tipo_ingreso'],
						$datosCobros []['ci_fechaCobro'] = $cobro ['ci_fechaCobro'],
						$datosCobros []['ci_glosa'] = $cobro ['ci_glosa'],
						$datosCobros []['ci04_numerosociedad'] = $cobro ['ci04_numerosociedad'],
						$datosCobros []['ci04_rut'] = $cobro ['ci04_rut'],
						$datosCobros []['ci_monto'] = "$ ".(number_format($cobro ['ci_monto'],0,",",".")),
						$datosCobros []['ci_numerofolio'] = $cobro ['ci_numerofolio'],
						$datosCobros []['ci53_nombreestado'] = $cobro ['ci53_nombreestado'],
						$datosCobros []['ci35_tipopago'] = $cobro ['ci35_tipopago']
						);
					
				$cobros[]=$data;
			}
		
		
		$arreglo =array(
				"data" => $cobros,
		);
			
		return $this->_helper->json->sendJson($arreglo);
	}
	
	//obtengo los datos del cobro para editar
	public function obtenerdatoscobrobyidAction()
	{		
		$cobroMapper= new Application_Model_CobroMapper();
		
		if(!empty($_REQUEST['idCobro']) && !empty($_REQUEST['tipoCobro']))
		{			
			if($_REQUEST['tipoCobro']=='1')
			{
				foreach ($cobroMapper->obtenerHonorarioById($_REQUEST['idCobro']) as $i =>$cobro)
				{
					$data=array(
							$datosCobros []['ci04_idrrut'] = $cobro ['ci04_idrrut'],
							$datosCobros []['ci04_rut'] = $cobro ['ci04_rut'],
							$datosCobros []['ci33_idconcepto'] = $cobro ['ci33_idconcepto'],
							$datosCobros []['ci35_idformapago'] = $cobro ['ci35_idformapago'],
							$datosCobros []['ci06_fechacobro'] = $cobro ['ci06_fechacobro'],
							$datosCobros []['ci06_glosa'] = $cobro ['ci06_glosa'],
							$datosCobros []['ci06_monto'] = $cobro ['ci06_monto'],
							$datosCobros []['ci06_observacion'] = $cobro ['ci06_observacion'],
							$datosCobros []['tipoCobro'] = '1',
							$datosCobros []['ci06_valoruf'] = $cobro ['ci06_valoruf'],
							$datosCobros []['ci06_valormoneda'] = $cobro ['ci06_valormoneda'],
						);
				}
			}
			else
			{
				foreach ($cobroMapper->obtenerCobroIndividualById($_REQUEST['idCobro']) as $i =>$cobro)
				{
					$data=array(
							$datosCobros []['ci04_idrrut'] = $cobro ['ci04_idrrut'],
							$datosCobros []['ci04_rut'] = $cobro ['ci04_rut'],
							$datosCobros []['ci33_idconcepto'] = $cobro ['ci33_idconcepto'],
							$datosCobros []['ci35_idformapago'] = $cobro ['ci35_idformapago'],
							$datosCobros []['ci05_fechacobro'] = $cobro ['ci05_fechacobro'],
							$datosCobros []['ci05_glosa'] = $cobro ['ci05_glosa'],
							$datosCobros []['ci05_monto'] = $cobro ['ci05_monto'],
							$datosCobros []['ci05_observacion'] = $cobro ['ci05_observacion'],
							$datosCobros []['ci05_valoruf'] = $cobro ['ci05_valoruf'],
							$datosCobros []['ci05_valormoneda'] = $cobro ['ci05_valormoneda'],
					);
				}
			}
				
			$arreglo =array(
					"obtener" => $data,
			);
			
			return $this->_helper->json->sendJson($arreglo);
			
		}
	}
	
	//entrega un resumen de los cobros (monto y saldo)
	public function detallecobrosAction()
	{
		$cobroMapper= new Application_Model_CobroMapper();
		
		$detalleCobro =array();
		
		if(!empty($_REQUEST ['idCliente']))
		{
			foreach ($cobroMapper->detalleCobrosByIdCliente($_REQUEST ['idCliente']) as $i => $cobro)
			{		
				$data =array(
						$datosCobros []['ci_monto'] = "$ ".(number_format($cobro ['ci_monto'],0,",",".")),
						$datosCobros []['ci_saldo'] = $cobro ['ci_saldo'],						
				);
		
				$detalleCobro[]=$data;
			}
		}
		
		$arreglo =array(
				"data" => $detalleCobro,
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	//modifica los datos del cobros
	public function modificarcobroAction()
	{
		$cobroMapper= new Application_Model_CobroMapper();
				
		$response=array();
		
		if(!empty($_REQUEST['idConceptoCobro'])&&
		   !empty($_REQUEST['fechaCobro'])&&
		   !empty($_REQUEST['glosaCobro'])&&
		   !empty($_REQUEST['pagoCobro'])&&
		   !empty($_REQUEST['esingreso'])&&
		   !empty($_REQUEST['idCobro'])&&
		   !empty($_REQUEST['idUsuario'])&&
		   !empty($_REQUEST['valorUF'])&&
		   isset($_REQUEST['valorMoneda'])!=''
				)
		{
			
			$datosCobroModifica=array(	
					"idConceptoCobro" => $_REQUEST ['idConceptoCobro'],
					"fechaCobro" => $_REQUEST ['fechaCobro'],
					"glosaCobro" => $_REQUEST ['glosaCobro'],
					"montoCobro" => $_REQUEST ['montoCobro'],
					"pagoCobro" => $_REQUEST ['pagoCobro'],
					"observacionCobro" => $_REQUEST ['observacionCobro'],							
					"idCobro" => $_REQUEST ['idCobro'],
					"idUsuario"=>$_REQUEST ['idUsuario'],
					"valorUF"=>$_REQUEST ['valorUF'],
					"valorMoneda"=>$_REQUEST ['valorMoneda'],
			);			
			
			if($_REQUEST['esingreso']=='1')
			{
				//modifico tabla de honorarios
				$res=$cobroMapper->modificaHonorario($datosCobroModifica);
				//registro de que usuario modifica el cobro
				$cobroMapper->registroModificacionHonorario($datosCobroModifica);				
			}
			else
			{
				//modifico tabla de cobro individual
				$res=$cobroMapper->modificaCobroIndividual($datosCobroModifica);
				//registro de que usuario modifica el cobro
				$cobroMapper->registroModificacionCobroIndividual($datosCobroModifica);
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
		
		$arreglo =array(
				"modifica" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	//elimina los cobros de la base de datos
	public function eliminarcobro()
	{
		$cobroMapper= new Application_Model_CobroMapper();		
		
		$response=array();
		
		if (!empty($_REQUEST ['idCobro'])&&!empty($_REQUEST['tipo'])) 
		{			
			if($_REQUEST['tipo']=='1')
			{
				if($clienteMapper->eliminarHonorario($_REQUEST ['idCobro']))
				{
					$response[0]='1';
				}
			}
			else
			{
				if($clienteMapper->eliminarCobroIndividual($_REQUEST ['idClienteElimina']))
				{
					$response[0]='1';
				}
			}
		}
		
		$arreglo =array(
				"eliminar" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	//cambia el estado de los cobros
	public function modificaestadocobroAction()
	{
		$cobroMapper=new Application_Model_CobroMapper();
	
		$response=array();
		
		if(!empty($_REQUEST['idCobro'])&&
		   !empty($_REQUEST['tipoCobro']))
		{
			
			switch($_REQUEST['tipoCobro'])
			{			
				case 1:
					
					$res=$cobroMapper->modificarEstadoCobroHonorario('5',$_REQUEST['idCobro']);
					
					break;
				case 2:
					$res=$cobroMapper->modificarEstadoCobroIndividual('5',$_REQUEST['idCobro']);
					
					break;	
				case 3:					
					$res=$cobroMapper->modificarEstadoCobroMasivo('5',$_REQUEST['idCobro']);
					
					break;
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
		
		$arreglo=array(
			"estado"=>	$response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
		
	//autoriza los pagos que NO son sin movimiento y que son cobros pendientes.
	public function autorizapagoextraordinarioAction()
	{
		$cobroMapper=new Application_Model_CobroMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['idCobro'])&&!empty($_REQUEST['tipoCobro']))
		{			
			$data=array(
					"idCobro"=>$_REQUEST['idCobro'],
					"tipoCobro"=>$_REQUEST['tipoCobro']
			);
			
			$res=$cobroMapper->autorizaPagoExtraordinario($data);
			
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
				"autoriza"=>$response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	public function verificaautorizacionpagoAction()
	{
		$cobroMapper=new Application_Model_CobroMapper();
		
		$response=array();
		
		
		if(!empty($_REQUEST['dataVerificacion']))
		{			
			$data = json_decode ( $_REQUEST ['dataVerificacion'], true );
						
			for($i=0;count($data)>$i;$i++)
			{		
				if(sizeof($cobroMapper->verificaAutorizacionPago($data[$i]['tipoCobro'],$data[$i]['id']))>0)
				{
					$response[0]=$cobroMapper->verificaAutorizacionPago($data[$i]['tipoCobro'],$data[$i]['id']);
					
					if($response[0]=='2')
					{							
						break;
					}
				}
				else
				{
					$response[0]='3';
				}					
			}
			
		}
		else
		{
			$response[0]='4';
		}
		
		$arreglo=array(
				"autoriza"=>$response
		);
		
		$this->_helper->json->sendJson($arreglo);
	}
}

























