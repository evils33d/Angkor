<?php

class CompensacionController extends Zend_Controller_Action
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
		
	}
	
	public function indexAction() 
	{
		$this->_helper->layout->setLayout ( 'layoutindexceco' );
		
		$cecosres = new Application_Model_CecoMapper ();
		
		$cecos = $cecosres->listar ();
		$this->view->rol = "";
		
		$auth = Zend_Auth::getInstance();
		

		if($auth->hasIdentity())
		{
			$user = $auth->getIdentity();
			$perfil = $user->lc02_idPerfil;
			
			$this->_rol=$perfil;
		}
		
		
		$this->view->cecos = $cecos;
	}
    
    //funcion que solo carga la vista de movimientos de de cartola
    public function movimientoscartolaAction()
    {
    	$this->_helper->layout->setLayout('layoutmovimientoscartola');
    	
    	$estadosres = new Application_Model_CompensacionMapper();
    	$cartolares = new Application_Model_CartolaMapper();
    	$proveedorres = new Application_Model_ProveedorMapper();
    	$proveedores = $proveedorres->listadoProveedores();
    	
    	
    	$this->view->proveedores = $proveedores;
    	
    	$estados = $estadosres->obtieneEstadosCompensacion();
    	
    	
    	if(isset($_REQUEST['compensacionSelect']) != "" || isset($_REQUEST['fechaDesdeHasta']) != ""){
    		
    		$filtro = array( 'estado' => ($_REQUEST['compensacionSelect'] != "" ? $_REQUEST['compensacionSelect']: ""),
    				         'fecha'  => ($_REQUEST['fechaDesdeHasta'] != "" ? $_REQUEST['fechaDesdeHasta']: "")   				
    				
    		); 

    		$cartola = $cartolares->verDetallePorFiltro($filtro);
    		
    		$this->view->filtroEstado = (isset($_REQUEST['compensacionSelect'])?$_REQUEST['compensacionSelect']:"");
    		$this->view->filtroFecha = (isset($_REQUEST['fechaDesdeHasta'])?$_REQUEST['fechaDesdeHasta']:"");
    		
    		
    	}else{
    		$cartola = $cartolares->verDetalleAll();
    	}

    	
    	
    	if($estados){
    		$this->view->estados = $estados;
    	}
    	
    	$this->view->detalle = $cartola;
    	
    	
    }
    
    //funcion solo para cargar la vista de busqueda de cobros
    public function buscarcobroAction()
    {
    	$this->_helper->layout->setLayout('layoutbuscarcobro');
    }
	
    //funcion para obtener el listado de cobros por filtro segun id de ejecutivo, cliente, rut
    public function busquedacobrosAction()
    {
    	$cobroMapper=new Application_Model_CobroMapper();
    	   	
    	$listadoCobros=array();
    	
    	if(!empty($_REQUEST['id'])&&!empty($_REQUEST['desde']))
    	{   			
    		if(sizeof($cobroMapper->buscarCobros($_REQUEST['id'],$_REQUEST['desde'])>0))
    		{
    			foreach ($cobroMapper->buscarCobros($_REQUEST['id'],$_REQUEST['desde']) as $i => $cobro)
    			{  					
    			
    				$saldo = $cobro ['saldo_cliente'];
    				
    				$data=array(
	    				$datoCobro[]['ci_idCobro']=$cobro['ci_idCobro'],
	    				$datoCobro[]['ci33_tipo_ingreso']=$cobro['ci33_tipo_ingreso'],
	    				$datoCobro[]['ci_fechaCobro']=$cobro ['ci_fechaCobro'],
	    				$datoCobro[]['ci_glosa']=$cobro['ci_glosa'],
	    				$datoCobro[]['ci04_numerosociedad']=$cobro['ci04_numerosociedad'],
	    				$datoCobro[]['ci04_rut']=($saldo > 0?"<p title='Tiene Saldo' style='background-color:green;'>".$cobro['ci04_rut']."</p>":$cobro['ci04_rut']),    						
	    				$datoCobro[]['ci_claveprevired']=$cobro['ci_claveprevired'],
    					$datoCobro[]['ci_clavesii']=$cobro['ci_clavesii'],    						
	    				$datoCobro[]['ci_numerocuenta']=$cobro['ci_numerocuenta'],
	    				$datoCobro[]['ci_monto']="$ ".(number_format($cobro['ci_monto'],0,",",".")),
	    				$datoCobro[]['ci_dinerorecivido']="$ ".(number_format($cobro['ci_dinerorecivido'],0,",",".")),
	    				$datoCobro[]['ci53_nombreestado']=$cobro['ci53_nombreestado'],
	    				$datoCobro[]['ci35_tipopago']=$cobro['ci35_tipopago'],
	    				$datoCobro[]['ci_numerofactura']=$cobro['ci_numerofactura'],
	    				$datoCobro[]['ci_observacion']=$cobro['ci_observacion']
    				);
    				
    				$listadoCobros[]=$data;
    			}
    		}
    		else 
    		{
    			$listadoCobros=null;
    		}
    	}
    	else
    	{
    		$listadoCobros=null;
    	}
    	
    	$arreglo=array
    	(
    		"data"=>$listadoCobros
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    	
    }
    
    public function busquedacobrosporestadoAction()
    {   	
    	$cobroMapper=new Application_Model_CobroMapper();
    		
    	$listadoCobros=array();
    	 
    	if($_REQUEST['id'] != '' && $_REQUEST['desde'] != '')
    	{
    		if(sizeof($cobroMapper->buscarCobros($_REQUEST['id'],$_REQUEST['desde'])))
    		{
    			foreach ($cobroMapper->buscarCobros($_REQUEST['id'],$_REQUEST['desde']) as $i => $cobro)
    			{
    				if($cobro['ci53_nombreestado'] == $_REQUEST['estado'] )
    				{
	    	
	    				$data=array(
	    						$datoCobro[]['ci_idCobro']=$cobro['ci_idCobro'],
	    						$datoCobro[]['ci33_tipo_ingreso']=$cobro['ci33_tipo_ingreso'],
	    						$datoCobro[]['ci_fechaCobro']=$cobro ['ci_fechaCobro'],
	    						$datoCobro[]['ci_glosa']=$cobro['ci_glosa'],
	    						$datoCobro[]['ci04_numerosociedad']=$cobro['ci04_numerosociedad'],
	    						$datoCobro[]['ci04_rut']=$cobro['ci04_rut'],
	    	
	    						$datoCobro[]['ci_claveprevired']=$cobro['ci_claveprevired'],
	    						$datoCobro[]['ci_clavesii']=$cobro['ci_clavesii'],
	    	
	    						$datoCobro[]['ci_numerocuenta']=$cobro['ci_numerocuenta'],
	    						$datoCobro[]['ci_monto']="$ ".(number_format($cobro['ci_monto'],0,",",".")),
	    						$datoCobro[]['ci_dinerorecivido']="$ ".(number_format($cobro['ci_dinerorecivido'],0,",",".")),
	    						$datoCobro[]['ci53_nombreestado']=$cobro['ci53_nombreestado'],
	    						$datoCobro[]['ci35_tipopago']=$cobro['ci35_tipopago'],
	    						$datoCobro[]['ci_numerofactura']=$cobro['ci_numerofactura'],
	    						$datoCobro[]['ci_observacion']=$cobro['ci_observacion']
	    				);
	    	
	    				$listadoCobros[]=$data;
    				}
    			}
    		}
    		else
    		{
    			$listadoCobros=null;
    		}
    	}
    	else
    	{
    		$listadoCobros=null;
    	}
    	 
    	$arreglo=array
    	(
    			"data"=>$listadoCobros
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);    	 
    }
        
    //funcion para obtener el listado de cobros por filtro segun id de cliente, rut , tipo cobro y rangos de fecha
    public function busquedacobrostipofechaAction()
    {
    	$cobroMapper=new Application_Model_CobroMapper();
    		
    	$listadoCobros=array();
    	
    		$data=array(
    				"idEjecutivo"=> $_REQUEST['idEjecutivo'],
    				"idCliente"  => $_REQUEST['idCliente'],
    				"idRut" 	 => $_REQUEST['idRut'],
    				"fechaInicio"=> $_REQUEST['fechaInicio'],
    				"fechaFinal" => $_REQUEST['fechaFinal'],
    				"tipoCobro"  => $_REQUEST['tipoCobro'],
    		);    		
    		
    		foreach ($cobroMapper->listadoBuscarCobros($data) as $i => $cobro)
    		{
    			
    			$claveSII=$cobro['ci_clavesii'];
    			$clavePrevired=$cobro['ci_claveprevired'];
    			
    			if($this->_rol=='4' || $this->_rol=='5' || $this->_rol=='6')
    			{
    				$claveSII="*******";
    			}
    			
    			if($this->_rol=='5' || $this->_rol=='6')
    			{
    				$clavePrevired="*******";
    			}
    			
    			
    			$datosFiltro=array(
    					$datoCobro[]['ci_idCobro']=$cobro['ci_idCobro'],
    					$datoCobro[]['ci33_tipo_ingreso']=$cobro['ci33_tipo_ingreso'],
    					$datoCobro[]['ci_fechaCobro']=$cobro ['ci_fechaCobro'],
    					$datoCobro[]['ci_glosa']=$cobro['ci_glosa'],
    					$datoCobro[]['ci04_numerosociedad']=$cobro['ci04_numerosociedad'],
    					$datoCobro[]['ci04_rut']=$cobro['ci04_rut'],
    					$datoCobro[]['ci_claveprevired']=$clavePrevired,
    					$datoCobro[]['ci_clavesii']=$claveSII,
    					$datoCobro[]['ci_numerocuenta']=$cobro['ci_numerocuenta'],
    					$datoCobro[]['ci_monto']="$ ".(number_format($cobro['ci_monto'],0,",",".")),
    					$datoCobro[]['ci_dinerorecivido']="$ ".(number_format($cobro['ci_dinerorecivido'],0,",",".")),
    					$datoCobro[]['ci53_nombreestado']=$cobro['ci53_nombreestado'],
    					$datoCobro[]['ci35_tipopago']=$cobro['ci35_tipopago'],
    					$datoCobro[]['ci_numerofactura']='<span>'.$cobro['ci_numerofactura'].'</span>',
    					$datoCobro[]['ci_observacion']=$cobro['ci_observacion']
    			);
    			
    			$listadoCobros[]=$datosFiltro;
    		}
    	 
    	$arreglo=array
    	(
    		"data"=>$listadoCobros
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);
    	 
    }
         
    //funcion para obetener todos los cobros
    public function listadocobrosAction()
    {
    	$cobroMapper= new Application_Model_CobroMapper();
    	 
    	$datosCobro =array();
    	 
    	foreach ($cobroMapper->listadoCobro() as $i => $cobro)
    	{    		 
    		    		
    		$saldo = $cobro ['saldo_cliente'];
    
    		$data =array(
    					$datoCobro[]['ci_idCobro']=$cobro['ci_idCobro'],
	    				$datoCobro[]['ci33_tipo_ingreso']=$cobro['ci33_tipo_ingreso'],
	    				$datoCobro[]['ci_fechaCobro']=$cobro ['ci_fechaCobro'],
	    				$datoCobro[]['ci_glosa']=$cobro['ci_glosa'],
	    				$datoCobro[]['ci04_numerosociedad']=$cobro['ci04_numerosociedad'],
	    				$datoCobro[]['ci04_rut']=($saldo > 0?"<p title='Tiene Saldo' style='background-color:#02B308;color:white;'>".$cobro['ci04_rut']."</p>":$cobro['ci04_rut']),  
	    				$datoCobro[]['ci_claveprevired']=$cobro['ci_claveprevired'],
    					$datoCobro[]['ci_clavesii']=$cobro['ci_clavesii'],
	    				$datoCobro[]['ci_numerocuenta']=$cobro['ci_numerocuenta'],
	    				$datoCobro[]['ci_monto']="$ ". (number_format($cobro['ci_monto'],0,",",".")),
	    				$datoCobro[]['ci_dinerorecivido']="$ ".(number_format($cobro['ci_dinerorecivido'],0,",",".")),
	    				$datoCobro[]['ci53_nombreestado']=$cobro['ci53_nombreestado'],
	    				$datoCobro[]['ci35_tipopago']=$cobro['ci35_tipopago'],
	    				$datoCobro[]['ci_numerofactura']=$cobro['ci_numerofactura'],
	    				$datoCobro[]['ci_observacion']=$cobro['ci_observacion']
    		);
    		 
    		$datosCobro[]=$data;
    	}
    	 
    	$arreglo =array(
    			"data" => $datosCobro,
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);
    }
    
    //funcion para registrar y actualizar el numero de la factura
	public function ingresarnumerofacturaAction()
	{
		$facturaMapper=new Application_Model_FacturaMapper();
		
		$response=array();
	
		
		if(!empty($_REQUEST['numero'])&&
		   !empty($_REQUEST['idCobro']))
		{			
			$data = json_decode ( $_REQUEST['idCobro'], true );
			
				
					if(count($data)>1)
					{
						$res=$facturaMapper->registrarNumeroFactura($_REQUEST['numero'],$data);
					}
					else
					{
						if($facturaMapper->verificaIdCobro($data))
						{
							
							$res=$facturaMapper->modificaNumeroFactura($_REQUEST['numero'],$data);
							
							/*
							if(!$facturaMapper->verificaNumeroFactura($_REQUEST['numero']))
							{
								
							}
							else
							{
								$response[0]='3';
							}
							*/
						}
						else
						{
							$res=$facturaMapper->registrarNumeroFactura($_REQUEST['numero'],$data);
						}
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
				"registro"=>$response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	//funcion para ingresar el canje
	public function ingresarcanjeAction()
	{
		$canjeMapper=new Application_Model_CanjeMapper();
		$cobroMapper=new Application_Model_CobroMapper();
		
		$response=array();
		
		$existeID=false;
		
		if(!empty($_REQUEST['pagoElectronico'])&&
		   !empty($_REQUEST['pagoCheque'])&&
		   !empty($_REQUEST['numero'])&&
		   !empty($_REQUEST['dataCobro'])&&
		   !empty($_REQUEST['idUsuario']))
		{
			$datosCanje=array(
					"pagoElectronico"=>$_REQUEST['pagoElectronico'],
					"pagoCheque"=>$_REQUEST['pagoCheque'],
					"numero"=>$_REQUEST['numero'],
					"idUsuario" =>$_REQUEST['idUsuario']
			);
			
			$data = json_decode ( $_REQUEST ['dataCobro'], true );
			
			for ($i = 0; count ( $data ) > $i; $i ++)
			{
				if($canjeMapper->verificarIdCobro($data[$i]['id']))
				{
					$response[0]='4';
					$existeID=true;
					break;
				}
			}
			
			if(!$existeID)
			{
				if(!$canjeMapper->verificaNumeroDocumento($_REQUEST['numero']))
				{
				
					$res=$canjeMapper->registrarCanje($datosCanje);
					$idCanje=mysql_insert_id();
				
					for ($i = 0; count ( $data ) > $i; $i ++)
					{
						if($res)
						{
							$response[0]='1';
				
							switch($data[$i]['tipoCobro'])
							{
								case 1:
									$res=$canjeMapper->registraDetalleCanjeHonorario($data[$i]['id'],$idCanje);
									$cobroMapper->modificarEstadoCobroHonorario(4,$data[$i]['id']);
									break;
								case 2:
									$res=$canjeMapper->registraDetalleCanjeIndividual($data[$i]['id'],$idCanje);
									//$cobroMapper->modificarEstadoCobroIndividual(4,$_REQUEST['idCobro']);
									break;
								case 3:
									$res=$canjeMapper->registraDetalleCanjeMasivo($data[$i]['id'],$idCanje);
									//$cobroMapper->modificarEstadoCobroMasivo(4,$_REQUEST['idCobro']);
									break;
							}
						}
						else
						{
							$response[0]='2';
						}
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
			$response[0]='2';
		}
		
		$arreglo=array(
				"registro"=>$response
		);
		
		$this->_helper->json->sendJson($arreglo);
	}

	public function obtienedatospagoAction()
	{
		$canjeMapper=new Application_Model_CanjeMapper();
		
		$dataCanje=array();
		
		if(!empty($_REQUEST['tipoCobro'])&&
		   !empty($_REQUEST['idCobro']))
		{	
			$idCobro=$_REQUEST['idCobro'];
			
			switch($_REQUEST['tipoCobro'])
			{
				case '1':
					
					if(sizeof($canjeMapper->obtenerDatosPagoCanjeHonorario($idCobro))>0)
					{
						foreach ($canjeMapper->obtenerDatosPagoCanjeHonorario($idCobro) as $i => $canje)
						{
							$dataCanje=array(
								$data[]['ci12_pagocheque']=$canje['ci12_pagocheque'],
								$data[]['ci12_pagoelectronico']=$canje['ci12_pagoelectronico'],
								$data[]['ci12_numerodocumento']=$canje['ci12_numerodocumento'],
								$data[]['lc01_nombreUsuario']=$canje['lc01_nombreUsuario'],
								$data[]['ci_fecha']=$canje['ci_fecha']									
								);
						}
					}
					
					break;
				case '2':
					
					if(sizeof($canjeMapper->obtenerDatosPagoCanjeIndividual($idCobro))>0)
					{
						foreach ($canjeMapper->obtenerDatosPagoCanjeIndividual($idCobro) as $i => $canje)
						{
							$dataCanje=array(
								$data[]['ci12_pagocheque']=$canje['ci12_pagocheque'],
								$data[]['ci12_pagoelectronico']=$canje['ci12_pagoelectronico'],
								$data[]['ci12_numerodocumento']=$canje['ci12_numerodocumento'],
								$data[]['lc01_nombreUsuario']=$canje['lc01_nombreUsuario'],
								$data[]['ci_fecha']=$canje['ci_fecha']
								);
						}
					}
						
					break;
				case '3':
					
					if(sizeof($canjeMapper->obtenerDatosPagoCanjeMasivo($idCobro))>0)
					{
						foreach ($canjeMapper->obtenerDatosPagoCanjeMasivo($idCobro) as $i => $canje)
						{
							$dataCanje=array(
								$data[]['ci12_pagocheque']=$canje['ci12_pagocheque'],
								$data[]['ci12_pagoelectronico']=$canje['ci12_pagoelectronico'],
								$data[]['ci12_numerodocumento']=$canje['ci12_numerodocumento'],
								$data[]['lc01_nombreUsuario']=$canje['lc01_nombreUsuario'],
								$data[]['ci_fecha']=$canje['ci_fecha']
								);
						}
					}
						
					break;
			}			
		}
		
		$areglo=array(
				"canje" => $dataCanje
		);
		
		return $this->_helper->json->sendJson($areglo);
	}
	
	//funcion para obtener el listado de cobros por filtro segun id de ejecutivo, cliente, rut
	public function busquedacobroscompensacionAction()
	{
		$cobroMapper=new Application_Model_CobroMapper();
			
		$listadoCobros=array();
		 
		$filtros = array (
							'id_ejecutivo'  => (isset($_REQUEST['ejecutivoSelect'])? $_REQUEST['ejecutivoSelect']:""),
							'id_cliente'    => (isset($_REQUEST['clienteSelect'])?$_REQUEST['clienteSelect']: ""),
							'id_rut'        => (isset($_REQUEST['rutSelect'])?$_REQUEST['rutSelect']: ""),
							'fecha'         => (isset($_REQUEST['fechaDesdeHastaCompensacion'])?$_REQUEST['fechaDesdeHastaCompensacion']: ""),
							'tipo_cobro'    => (isset($_REQUEST['tipoCobroCompensarSelect'])?$_REQUEST['tipoCobroCompensarSelect']: ""),
							'monto_desde'   => (isset($_REQUEST['montoDesdeCompensarInput'])?$_REQUEST['montoDesdeCompensarInput']: ""),
							'monto_hasta'   => (isset($_REQUEST['montoHastaCompensarInput'])?$_REQUEST['montoHastaCompensarInput']: ""),
							'estado_cobro'  => (isset($_REQUEST['estadoSelect'])?$_REQUEST['estadoSelect']: "")
		);

		$cobrosres = $cobroMapper->buscarCobrosCompensacion($filtros);

		foreach ($cobrosres as $key => $cobro)
		{
					
					
					$llave = $cobro['tipoCobro'].'-'.$cobro['ci_idCobro'].'-'.$cobro['ci_monto'].'-'.$cobro['ci_dinerorecibido'];
					$montoacompensar = $cobro['ci_monto']-$cobro['ci_dinerorecibido'];
					$documento = ($cobro['documento_canje']==""?'0':$cobro['documento_canje']);
					
					$data=array(		
					
							$datoCobro[]['ci_fechaCobro']=$cobro ['ci_fechaCobro'],
							$datoCobro[]['ci_glosa']=$cobro['ci_glosa'],
							$datoCobro[]['ci04_numerosociedad']=$cobro['ci04_numerosociedad'],
							$datoCobro[]['ci04_rut']="<div style='width:80px'>".$cobro['ci04_rut']."</div>",
							$datoCobro[]['monto_cobro']="<div>$ ".number_format($cobro['ci_monto'],0,'','.')."</div>",
							$datoCobro[]['monto_compensado']="$".(($cobro['ci_dinerorecibido'] != "")?number_format($cobro['ci_dinerorecibido'],0,'','.'):"$0"),
							$datoCobro[]['monto_a_compensar']="$".number_format($montoacompensar,0,'','.'),
							$datoCobro[]['ci53_nombreestado']=$cobro['ci53_nombreestado'],
							$datoCobro[]['documento']=$documento,
							$datoCobro[]['input_monto']='<div style="width:120px" class="input-group cobro"><span class="input-group-addon"><i class="fa fa-dollar"></i></span><input  data-documento="'.$documento.'" data-monto="'.$montoacompensar.'" id="'.$llave.'" type="text" class="form-control montoacompensar"  placeholder="0"></div>'
					);
	
					$listadoCobros[]=$data;
		}	
		
		$arreglo=array
		(
				"data"=>$listadoCobros
		);
		 
		return $this->_helper->json->sendJson($arreglo);
		 
	}
		
	public function guardacompensacionAction()
	{
		
		$this->_helper->layout->disableLayout();		
		$cobro = new Application_Model_CobroMapper();		
		
		$respuesta = array();
		
		$datos = json_decode($_POST['data'],true);
		$movimiento = $_POST['mov'];
		$totalcompensado = $_POST['totalcompensado'];
		$flagCompensaConSaldo = $_POST['flagCompensaConsaldo'];
		$idCliente= $_POST['idCliente'];
		$montoSaldoAnterior = $_POST['montoSaldoAnterior'];
		
		$res = $cobro->compensaCobros($datos,$movimiento,$totalcompensado,$flagCompensaConSaldo,$idCliente,$montoSaldoAnterior);
		
		if($res)
		{
			$respuesta = array('respuesta' => true);
		}else{
			$respuesta = array('respuesta' => false);
		}
				
		echo json_encode($respuesta);
		
	}
		
	public function traspasoasaldoclienteAction()
	{
		$this->_helper->layout->disableLayout();
		$cobro = new Application_Model_CobroMapper();
		
		$respuesta = array();
		
		if(isset($_POST['monto']) != '' && isset($_POST['movimiento']) != '' && isset($_POST['cliente']) != ''){
			
			$monto = $_POST['monto'];
			$movimiento = $_POST['movimiento'];
			$cliente = $_POST['cliente'];
			
			$res = $cobro->traspasoMontoaSaldo($monto,$movimiento,$cliente);
			
			if($res)
			{
				$respuesta = array('respuesta' => true);
			}else{
				$respuesta = array('respuesta' => false);
			}
			
		}
		
		
		echo json_encode($respuesta);
		
	}
		
	public function busquedadetallecompensacionAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cobroMapper= new Application_Model_CobroMapper();	
		
		$listadoCobros = array();
		
		if(!empty($_REQUEST ['movimiento']))
		{
			$res = $cobroMapper->obtieneDetalleCompensacion($_REQUEST ['movimiento']);
			if($res)
			{
				foreach ($res as $i => $detalle)
				{
					$data =array(
							$lineasdetalle[]['ci08_fechaCompensacion']	= $detalle['ci08_fechaCompensacion'],
							$lineasdetalle[]['glosa']					= ($detalle['glosa'] == ""?'NA':$detalle['glosa']),
							$lineasdetalle[]['tipo_compensacion']		= $detalle['tipo_compensacion'],
							$lineasdetalle[]['rut']						= ($detalle['rut']==""?'NA':$detalle['rut']),
							$lineasdetalle[]['ci03_nombre']				= ($detalle['ci03_nombre']==""?'NA':$detalle['ci03_nombre']),
							$lineasdetalle[]['ci08_montoCompensacion']	= "$".number_format($detalle['ci08_montoCompensacion'],0,'','.')							
							/*$lineasdetalle[]['accion']					= '<button type="button" class="btn bg-blue btn-flat btn-sm">Descompensar <span class="fa fa-minus-square-o"></span></button>'*/
					);	

					$listadoCobros[]=$data;
				}
			}
			else
			{
				$data = null;
			}
			
		}
		
		
		$arreglo=array
		(
				"data"=>$listadoCobros
		);
		 
		return $this->_helper->json->sendJson($arreglo);
	
	}
	
	public function guardapagoproveedorAction()
	{		
		$this->_helper->layout->disableLayout();
		
		$proveedorMapper= new Application_Model_ProveedorMapper();
		
		$respuesta = array('respuesta' => false,
						   'mensaje' => 'Falta un parametro'
		);
		
		if(	isset($_POST['montopagar']) &&
			isset($_POST['idmovimiento']) &&
			isset($_POST['tipomovimiento']) &&
			isset($_POST['proveedor']) &&
			isset($_POST['observacion'])
			)
		{
			
			$data = array (
					"montoaCompensar" => $_POST['montopagar'],
					"idMovimiento" => $_POST['idmovimiento'],
					"tipoMovimiento" => $_POST['tipomovimiento'],
					"idProveedor" => $_POST['proveedor'],
					"observacion" => $_POST['observacion']
					
			);
			
			$res = $proveedorMapper->compensaPagos($data);
			
			if($res)
			{
				$respuesta = array('respuesta' => true);
			}else{
				$respuesta = array('respuesta' => false,
								   'mensaje' => 'Error en queries'
				);
			}
		}
				
		return $this->_helper->json->sendJson($respuesta);
	}
	
	public function traesaldoclienteAction()
	{
	
		$this->_helper->layout->disableLayout();
	
		$clienteMapper= new Application_Model_ClienteMapper();
	
		$saldo = $clienteMapper->traeSaldo($_POST['idCliente']);
				
			if($saldo >= 0)
			{
				$respuesta = array('respuesta' => true,
								   'saldo'    => $saldo
				);
			}else{
				
				$respuesta = array('respuesta' => false,
						'mensaje' => 'Error en queries'
				);
			}
		
	
		return $this->_helper->json->sendJson($respuesta);
	}
		
	public function busquedatipocobroAction()
	{
		$cobroMapper=new Application_Model_CobroMapper();
		 
		$tipoCobro=array();
		$tipo;
		 
		$listadoCobros=array();
		 
		if(!empty($_REQUEST['tipoCobro']))
		{
			if($_REQUEST['tipoCobro']=='1')
			{
				$tipo=$cobroMapper->buscarCobroHonorarios();
			}
			else
			{
				$tipo=$cobroMapper->buscarCobroIndividual();
			}
	
			if(sizeof($tipo>0))
			{
				foreach ($tipo as $i => $cobro)
				{	
					$data=array(
							$datoCobro[]['ci_idCobro']=$cobro['ci_idCobro'],
							$datoCobro[]['ci33_tipo_ingreso']=$cobro['ci33_tipo_ingreso'],
							$datoCobro[]['ci_fechaCobro']=$cobro['ci_fechaCobro'],
							$datoCobro[]['ci_glosa']=$cobro['ci_glosa'],
							$datoCobro[]['ci04_numerosociedad']=$cobro['ci04_numerosociedad'],
							$datoCobro[]['ci04_rut']=$cobro['ci04_rut'],
							$datoCobro[]['ci_claveprevired']=$cobro['ci_claveprevired'],
							$datoCobro[]['ci_clavesii']=$cobro['ci_clavesii'],
							$datoCobro[]['ci_numerocuenta']=$cobro['ci_numerocuenta'],
							$datoCobro[]['ci_monto']="$ ".(number_format($cobro['ci_monto'],0,",",".")),
							$datoCobro[]['ci_dinerorecivido']="$ ".(number_format($cobro['ci_dinerorecivido'],0,",",".")),
							$datoCobro[]['ci53_nombreestado']=$cobro['ci53_nombreestado'],
							$datoCobro[]['ci35_tipopago']=$cobro['ci35_tipopago'],
							$datoCobro[]['ci_numerofactura']=$cobro['ci_numerofactura'],
							$datoCobro[]['ci_observacion']=$cobro['ci_observacion']
					);	
					$listadoCobros[]=$data;
				}
			}
			else
			{
				$listadoCobros=[];
			}
		}
		else
		{
			$listadoCobros=[];
		}
		 
		$arreglo=array(
				"data" => $listadoCobros
		);
		 
		return $this->_helper->json->sendJson($arreglo);
	}
}

