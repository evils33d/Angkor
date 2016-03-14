<?php

class ClienteController extends Zend_Controller_Action
{
	protected $_config ;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;
	private $email;

    public function init()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
    	$this->_config = $bootstrap->getOptions();
    	
    	$this->view->nombre_sitio = $this->_config['nombre_sitio'];
    	$this->view->skin = $this->_config['skin'];
    	$this->view->urlaplicacion = $this->_config['urlaplicacion'];
    	
    	$email = $this->_config['email_proyecto'];    	
    	
    	$perfil = 0;
    	$auth = Zend_Auth::getInstance();
    	
    	$this->view->rol = "";
    	$this->view->id_usuario = "";
    	$this->view->tipoperfil = "";
    	
    	if($auth->hasIdentity()) 
    	{	
    		$user = $auth->getIdentity();
    		
    		$perfil = $user->lc02_idPerfil;
    		$id_uduario =$user->lc01_idUsuario;    	
    		
    		$tipoperfilres = new Application_Model_PerfilMapper();
    		
    		$tipoperfil = $tipoperfilres->obtienePerfilById($perfil);    		
    		
    		$this->view->rol = $perfil;
    		$this->view->id_usuario =$id_uduario;
    		
    		$this->view->tipoperfil = $tipoperfil;
    		
    		$this->view->nombrePerfil=$tipoperfilres->obtienePerfilByIdUsuario($id_uduario);
    		
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
    
    //funcion solo carga la vista
    public function ingresarclienteAction()
    {
    	$this->_helper->layout->setLayout('layoutingresarcliente');   	
    }    
    
    public function buscarpornombreAction()
    {
    	$this->_helper->layout->setLayout('layoutbuscarpornombre');
    }
     
    public function detalleclienteAction()
    {
    	$this->_helper->layout->setLayout('layoutdetallecliente');
    	
    	$idCliente = $_REQUEST['idCliente'];
    	
    	$clienteMapper = new Application_Model_ClienteMapper ();
    	$cobroMapper = new Application_Model_CobroMapper ();
    	
    	$filtros = array (
    			'id_ejecutivo' 		=>'',
    			'id_cliente'   		=>$idCliente,
    			'id_rut'       		=>'',
    			'fecha'       		=>'',
    			'tipo_cobro'  		=>'',
    			'monto_desde'       =>'',
    			'monto_hasta'       =>'',
    			'estado_cobro'      =>'1'
    	);
    	
    	$cobros = $cobroMapper->buscarCobrosCompensacion($filtros);
    	$res = $clienteMapper->datosClienteById($idCliente);
    	
    	if($res){
    		$this->view->cliente = $res;
    		$this->view->cant_cobros_pendientes = count($cobros);
    	}
    	
    }
    
    //funcion registra al cliente en la base de datos
    public function ingresoAction()
    {  		 
    	$clienteMapper = new Application_Model_ClienteMapper ();
    	 
    	$response=array();
    	 
    	if (! empty ( $_REQUEST ['nombreCliente'] ) &&
    		! empty ( $_REQUEST ['idEjecutivo'] ) &&
    		! empty ( $_REQUEST ['fechaIngreso'] ))
    	{
    		
    		if(!$clienteMapper->verificarClienteByNombre($_REQUEST ['nombreCliente']))
    		{
    			$datosCliente = array (
    					"nombreCliente" => $_REQUEST ['nombreCliente'],
    					"idEjecutivo" => $_REQUEST ['idEjecutivo'],
    					"fechaIngreso" => $_REQUEST ['fechaIngreso']
    			);
    			 
    			$res = $clienteMapper->ingresarCliente ( $datosCliente );
    			
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
    
    public function listarAction()
    {
    	$clienteMapper = new Application_Model_ClienteMapper ();
    	
    	$listadoClientes = array();
    	
    	if(sizeof($clienteMapper->listadoClientes())>0)
    	{
    		foreach ( $clienteMapper->listadoClientes() as $i => $clientes )
    		{
    			$data =array(
    					$listado[]['ci03_idcliente']= $clientes['ci03_idcliente'],
    					$listado[]['ci03_nombre']= $clientes['ci03_nombre'],
    					$listado[]['lc01_nombreUsuario']= $clientes['lc01_nombreUsuario']
    			);
    				
    			$listadoCliente[] = $data;
    		}
    	}
    	else
    	{
    		$listadoCliente = [];
    	}
    	
    	$arreglo =array(
    			"data" => $listadoCliente
    	);
    		
    	return $this->_helper->json->sendJson($arreglo);
    	
    }

    public function listarbynombreclienteAction()
    {
    	$clienteMapper=new Application_Model_ClienteMapper();
    	
    	$listadoClientes=array();
    	
    	
    	if(sizeof($clienteMapper->listarByNombre($_REQUEST['nombreCliente'],$_REQUEST['idEjecutivo']))>0)
    	{
    		foreach ($clienteMapper->listarByNombre($_REQUEST['nombreCliente'],$_REQUEST['idEjecutivo']) as $i => $cliente)
    		{
    			$data=array(
    					
    					$datoCliente[]['ci03_idcliente']=$cliente['ci03_idcliente'],
    					$datoCliente[]['ci03_nombre']=strtoupper($cliente['ci03_nombre']),
    					$datoCliente[]['lc01_nombreUsuario']=$cliente['lc01_nombreUsuario']
    			);
    			
    			$listadoClientes[]=$data;
    		}
    	}
    	else 
    	{
    		$listadoClientes=[];
    	}
    	
    	
    	$arreglo=array(
    			"data"=>$listadoClientes
    	);    	
    	return $this->_helper->json->sendJson($arreglo);
    }
        
    public function listarbyejecutivoAction()
    {
    	$clienteMapper = new Application_Model_ClienteMapper ();
    	 
    	$listadoClientes = array();
    	 
    	if(sizeof($clienteMapper->listadoClientesByEjecutivo($_REQUEST['idUsuario']))>0)
    	{
    		foreach ( $clienteMapper->listadoClientesByEjecutivo($_REQUEST['idUsuario']) as $i => $clientes )
    		{
    			$data =array(
    					$listado[$i]['ci03_idcliente']= $clientes['ci03_idcliente'],
    					$listado[$i]['ci03_nombre']= $clientes['ci03_nombre'],
    					$listado[$i]['lc01_nombreUsuario']= $clientes['lc01_nombreUsuario']
    			);
    
    			$listadoCliente[] = $data;
    		}
    	}
    	else
    	{
    		$listadoCliente = [];
    	}
    	 
    	$arreglo =array(
    			"data" => $listadoCliente
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    	 
    }
    
    public function obtenerAction()
    {   
    	$clienteMapper = new Application_Model_ClienteMapper();   	
    	
    	$datosCliente=array();
    	
    	if(!empty($_REQUEST ['idCliente']))
    	{
    		
    		if($clienteMapper->verificarClienteById($_REQUEST ['idCliente']))
    		{
    			foreach ($clienteMapper->datosClienteById($_REQUEST ['idCliente']) as $i => $client)
    			{
    				$data =array(
    						$datosCliente[]['ci03_nombre']= $client['ci03_nombre'],
    						$datosCliente[]['ci03_fechaingreso']= $client['ci03_fechaingreso'],
    						$datosCliente[]['lc01_nombreUsuario']= $client['lc01_nombreUsuario'],
    						$datosCliente[]['lc01_idUsuario']= $client['lc01_idUsuario']
    				);
    				 
    			}
    		}
    		else 
    		{
    			$data = null;	
    		}
    	}
    	
    	$arreglo =array(
    			"cliente" => $data
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    }

    public function obtenerbyrutAction()
    {
    	$clienteMapper = new Application_Model_ClienteMapper();
    	 
    	$datosCliente=array();
    	 
    	if(!empty($_REQUEST ['idRut']))
    	{    
    		if($clienteMapper->datosClienteByIdRut($_REQUEST ['idRut']))
    		{
    			foreach ($clienteMapper->datosClienteByIdRut($_REQUEST ['idRut']) as $i => $client)
    			{
    				$data =array(
    						$datosCliente[]['ci03_nombre']= $client['ci03_nombre'],
    						$datosCliente[]['lc01_idusuario']= $client['lc01_idusuario']
    				);
    					
    			}
    		}
    		else
    		{
    			$data = null;
    		}
    	}
    	 
    	$arreglo =array(
    			"cliente" => $data
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);
    }
        
    public function modificarAction()
    {    	
    	$clienteMapper = new Application_Model_ClienteMapper();   	
    	
    	$response =array();
    	
    	if(! empty ($_REQUEST['nombreClienteEdit']) &&
    	   ! empty ($_REQUEST['idUsuarioEdit']) && 
    	   ! empty ($_REQUEST['idClienteEdit']) ){
    					
    					
    		$datosClienteEdit = array (
    				"nombreClienteEdit" => $_REQUEST ['nombreClienteEdit'],
    				"idUsuarioEdit" => $_REQUEST ['idUsuarioEdit'],
    				"idClienteEdit" => $_REQUEST ['idClienteEdit']
    		);
    					
    		$res = $clienteMapper -> modificarCliente ( $datosClienteEdit );
    		
    	 	if($res)
    		{
    			$response[0]='1';
    		}
    		else
    		{
    			$response[0]='2';
    		}
    					
    	}else
    	{
    		$response[0]='2';
    	}
    	
    	$arreglo =array(
    			"edicion" => $response
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    }

    public function eliminarAction()
    {    
    	$clienteMapper = new Application_Model_ClienteMapper ();
    	$cobroMapper=new Application_Model_RutMapper();
    	
    	$response=array();
    
    	if (!empty($_REQUEST ['idClienteElimina'])) 
    	{   			
    		if(!$clienteMapper->verificaCobrosPendientesCliente($_REQUEST ['idClienteElimina']))
    		{
    			if($clienteMapper->verificarClienteById($_REQUEST ['idClienteElimina']))
    			{
    				if($clienteMapper->eliminarCliente($_REQUEST ['idClienteElimina']))
    				{
    					$response[0]='1';
    				}
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
    			"eliminar" => $response
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    
    }
     
    public function eliminacobroAction()
    {
    	$cobroMapper = new Application_Model_CobroMapper ();
    	
    	$response=array();
    	
    	if (!empty($_REQUEST ['idCobro']) && !empty($_REQUEST['tipoCobro'])) 
    	{    		 
    		$res=$cobroMapper->eliminarCobro($_REQUEST ['idCobro'],$_REQUEST['tipoCobro']);
    					
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
    			"eliminar" => $response
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    }
    
    public function envioemailAction()
    {    	
    	$emailMapper=new Application_Model_EmailMapper();
    	$contactoMapper= new Application_Model_ContactoMapper();
    	$cobroMapper=new Application_Model_CobroMapper();   
    	$usuarioMapper= new Application_Model_MantenedorUsuarioMapper();
    	    	
    	$razonSocial="";
    	$monto="";
    	
    	$nombreUsuario="";
    	$perfil="";
    	
    	$response=array();
    	
    	if (! empty ( $_REQUEST ['asunto'] ) &&
    		! empty ( $_REQUEST ['idUsuario']) &&
    		! empty ( $_REQUEST ['idCliente']) &&
    		! empty ( $_REQUEST ['datosCobro']))
    	{   		
    		
    		
    		$datosEmail = array
    		(
    			"textoCuerpo" => $_REQUEST ['textoCuerpo'],
    			"asunto" => $_REQUEST ['asunto'],
    			"idUsuario" => $_REQUEST['idUsuario'],
    			"idCliente" => $_REQUEST['idCliente'],
    		);  
    			
    		//obtengo los datos de los cobros seleccionados
    		$datoCobro=explode("/",$_REQUEST['datosCobro']);
    			
    		$html="";
    		$totalPago=0;
    		$msj="";
    		$forma_pago="";
    		
    		for ($i=0;count($datoCobro)>$i;$i++)
    		{
    			if($datoCobro[$i]!='')
    			{
    				$dataId=explode("-",$datoCobro[$i]);
    					
    				$cobro=$cobroMapper->obtenerDatosCobroEmail($dataId[0],$dataId[1]);
    		
    				$totalPago+=intval($cobro[0]['ci_valormoneda']);
    			
    				$html.="<tr><td style='border: 1px solid black;padding: 5px;'>".$cobro[0]['ci04_razonsocial']."</td>
    					        <td style='border: 1px solid black;padding: 5px;'>".$cobro[0]['ci33_nombre']."</td>
    					        <td style='border: 1px solid black;padding: 5px;'>$ ".$cobro[0]['ci_valoruf']."</td>
    					        <td style='border: 1px solid black;padding: 5px;'>".$cobro[0]['ci_monto']." UF</td>
    					        <td style='border: 1px solid black;padding: 5px;'>$ ". (number_format($cobro[0]['ci_valormoneda'],0,",",".")) ."</td>
    					    </tr>";
    				
    				$forma_pago=$cobro[0]['ci35_idformapago'];
    			}
    		}
    		
    		$html.="<tr>
    					<td style='border: 1px solid black;padding: 5px;' colspan='4'><strong>Total a pagar</strong></td>
    					<td style='border: 1px solid black;padding: 5px;'><strong>$ ".(number_format($totalPago,0,",","."))."</strong></td>       
    				</tr>";
    		
    		switch($forma_pago)
    		{
    			case 1:$msj="(1) Favor depositar o transferir a ANGKOR CONSULTING S.A., RUT 78342990-K, Banco de CHILE, cuenta corriente 00-005-02565-06.";break;
    			
    			case 2:$msj="(4) Cheque a nombre del servicio.";break;
    			
    			case 3:$msj="(2)  Listo para su pago directo en web del servicio.";break;
    			
    			case 4:$msj="(3) Informado por PEC.";break;
    			
    			case 5:$msj="(1) Favor depositar o transferir a ANGKOR CONSULTING S.A., RUT 78342990-K, Banco de CHILE, cuenta corriente 00-005-02565-06.";break;
    		}
    		
    		//termino de obtener los datos de los cobros seleccionados
    		
    		//obtengo los datos del usuarios que envia los email
    		foreach ($usuarioMapper->datosUsuarioEmail($_REQUEST ['idUsuario']) as $i => $user)
    		{
    			$nombreUsuario=$user['lc01_nombreUsuario'];
    			$perfil=$user['lc02_nombrePerfil'];
    		}
    		//Termino de obtener los datos del usuarios que envia los email
    		
    		if(sizeof($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente'] ))>0)
    		{
    			$res = $emailMapper->ingresarEmail ( $datosEmail );
    			$idBitacora=mysql_insert_id();
    		
    			if ($res)
    			{
    				$response[0]='1';  
    				
    				for ($j=0;count($datoCobro)>$j;$j++)
    				{
    					if($datoCobro[$j]!='')
    					{    	    						
    						$dataIds=explode("-",$datoCobro[$j]);
    						
    						if(trim($dataIds[1])=='Honorario')
    						{
    							$emailMapper->registraDetalleEmailHonorario($dataIds[0],$idBitacora);
    						}
    						else
    						{
    							$emailMapper->registraDetalleEmailInidividual($dataIds[0],$idBitacora);
    						}
    					}
    				}
    				
    				foreach ($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente'] ) as $i => $datos)
    				{
    					$destinatario =$datos['ci09_email'];
    					$titulo = $_REQUEST['asunto'];
    					
    								$mensaje = '
												<html>
													<head>
														<title>Email de Cobro</title>
													</head>
													<body>
		    	
														<p>Estimado(a)  <strong>'.$datos['ci09_nombre'].'</strong></p>
														<p>Adjunto información para pago</p>
    		
														<table style="border: 1px solid black; border-collapse: collapse;">
															<thead>
																<th style="border: 1px solid black;padding: 3px;
																 background-color:  #FFCD00;
		    													 color: white;">Contribuyente</th>
    		
																<th style="border: 1px solid black;padding: 3px;
																 background-color: #FFCD00;
		    													 color: white;">Concepto</th>
    		
																<th style="border: 1px solid black;padding: 3px;
																 background-color: #FFCD00;
		    													 color: white;">Valor UF</th>
    		
																<th style="border: 1px solid black;padding: 3px;
																 background-color: #FFCD00;
		    													 color: white;">Total UF</th>
    		
																<th style="border: 1px solid black;padding: 3px;
																 background-color: #FFCD00;
		    													 color: white;">Monto</th>
															</thead>
															<tbody>
																'.$html.'																
															</tbody>
														</table>
										
		    											<br>
															<p>'.$msj.'</p>				
														<br>				
										
														<p><strong>'.$datosEmail['textoCuerpo'].'</strong></p>
    		
														<br>
    		
														<p>Se despide Atentamente <strong>'.$nombreUsuario.'</strong>, '.$perfil.' de ANGKOR.</p>
								
													</body>
												</html>
										';
    		
    					$cabeceras = "MIME-Version: 1.0\r\n";
    					$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";
    					$cabeceras .= "From:".$usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario'])."\r\n";
    					$cabeceras .= "Cc:".$usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario'])."\r\n";
    		
    					mail($destinatario, $titulo, $mensaje, $cabeceras);
    				}
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
    		$response[0]='4';
    	} 			
    	
    	$arreglo =array(
    			"registro" => $response
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    	
    }
    
    public function envioemailrentaAction()
    {    	
    	$emailMapper=new Application_Model_EmailMapper();
    	$contactoMapper= new Application_Model_ContactoMapper();
    	$cobroMapper=new Application_Model_CobroMapper();   
    	$usuarioMapper= new Application_Model_MantenedorUsuarioMapper();
    	
    	$response=array();
    	
    	if(
    	   !empty($_REQUEST['asunto'])&&
    	   !empty($_REQUEST['idUsuario'])&&
    	   !empty($_REQUEST['idCliente']))
    	{
    		
    		$emailEjecutivo = $usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']);
    		
    		
    		$datosEmail = array (
    				"textoCuerpo" => $_REQUEST ['textoCuerpo'],
    				"asunto" => $_REQUEST ['asunto'],
    				"idUsuario" => $_REQUEST['idUsuario'],
    				"idCliente" => $_REQUEST['idCliente']   				
    		);
    		
    		
    		
    		foreach ($usuarioMapper->datosUsuarioEmail($_REQUEST ['idUsuario']) as $i => $user)
    		{
    			$nombreUsuario=$user['lc01_nombreUsuario'];
    			$perfil=$user['lc02_nombrePerfil'];
    		}
    		
    		if(sizeof($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente'] ))>0)
    		{    			
    			$res = $emailMapper->ingresarEmail ( $datosEmail );
    			$idBitacora=mysql_insert_id();
    			
    			if($res)
    			{    				
    				$response[0]='1';  				
    				
    				foreach ($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente'] ) as $i => $datos)
    				{
    					$emailEjecutivo = $usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']);
    					
    					
    					$titulo = $_REQUEST['asunto'];
    					
    					$mensaje = ' <html>
										<head>
											<title>Email de Cobro Renta</title>    											
										</head>
											<body>
		    						
												<p>Estimado  <strong>'.$datos['ci09_nombre'].'</strong>, adjunto informacion para pago por concepto de <strong>RENTA</strong></p>									
												';
    					
    					$mensaje .= '<table style="border: 1px solid black; border-collapse: collapse;">
    								 <thead>
    									<tr>
    										<th style="border: 1px solid black; padding: 5px; background-color: #FFCD00; color: white;">Contribuyente</th>
    										<th style="border: 1px solid black; padding: 5px; background-color: #FFCD00; color: white;">Monto Renta</th>
    										<th style="border: 1px solid black; padding: 5px; background-color: white; color: white;"></th>
    										<th style="border: 1px solid black; padding: 5px; background-color: #FFCD00; color: white;">Dep. Angkor</th>
    										<th style="border: 1px solid black; padding: 5px; background-color: #FFCD00; color: white;">Pago Directo</th>
    									</tr>
    								 </thead>
    								 <tbody>';
    					
    					$total_depAngkor=0;
    					$total_pagoDirecto=0;
    					
    					$msjDepAngkor="";
    					$msjPagoDirecto="";
    					
    					foreach ($cobroMapper->obtieneDatosEmailRenta($_REQUEST['idCliente']) as $i =>$renta)
    					{
    						
    						$emailMapper->registraDetalleEmailRenta($renta['ci04_idrrut'],$idBitacora);
    						
    						$mensaje .= '<tr>
    										<td style="border: 1px solid black; padding: 5px;">'.$renta['ci04_razonsocial'].'</td>
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($renta['ci07_monto'],0,",",".")).'</td>
    										<td style="border: 1px solid black; padding: 5px;"></td>
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($renta['ci_depangkor'],0,",",".")).'</td>
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($renta['ci_pagodirecto'],0,",",".")).'</td>
    									</tr>';
    						
    						$total_depAngkor+=intval($renta['ci_depangkor']);
    						$total_pagoDirecto+=intval($renta['ci_pagodirecto']);
    					}
    					
    					if(intval($total_depAngkor)>0)
    					{
    						$msjDepAngkor="(1) Favor depositar o transferir a ANGKOR CONSULTING S.A., RUT 78342990-K, Banco de CHILE, cuenta corriente 00-005-02565-06.";
    					}
    					
    					if(intval($total_pagoDirecto)>0)
    					{
    						$msjPagoDirecto="(2) Listo para su pago directo en web del servicio";
    					}
    					
    					$mensaje .= '<tr>
    									<td style="border: 1px solid black; padding: 5px;" colspan="2"><strong>Total</strong></td>
    									<td style="border: 1px solid black; padding: 5px;"></td>
    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_depAngkor,0,",",".")).'</strong></td>
		    							<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_pagoDirecto,0,",",".")).'</strong></td>
    								</tr>';   						
															
    					$mensaje .= '</tbody>
    								</table>';		
														
    					$mensaje .= '		<p>'.$msjDepAngkor.'</p>
    										<p>'.$msjPagoDirecto.'</p>
    										
    										<p><strong>'.$datosEmail['textoCuerpo'].'</strong></p>
											<br/>
											<p>Se despide Atentamente <strong>'.$nombreUsuario.'</strong>, '.$perfil.' de ANGKOR.</p>
															
											</body>
								    </html>
    							
    							   ';
    				
    					$cabeceras = "MIME-Version: 1.0\r\n";
    					$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";
    					$cabeceras .= "From:".$usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario'])."\r\n";
    					$cabeceras .='Cc: '.$emailEjecutivo."\r\n";
    				
    					mail($destinatario, $titulo, $mensaje, $cabeceras);
    					
    				}    				
    				
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
    	else
    	{
    		$response[0]='2';
    	}
    	
    	
    	$arreglo=array(
    			"registro"=>$response
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    }
    
    public function datoscobroemailAction()
    {
    	$cobroMapper = new Application_Model_CobroMapper();    	 
    	
    	$datosCliente=array();
    	 
    	if(!empty($_REQUEST ['datosCobros']))
    	{    		
    		$datoCobro=explode("/",$_REQUEST['datosCobros']);
    		
    		$datosCobros=array();
    		
    		$total_cobros=0;
    		
    		for ($i=0;count($datoCobro)>$i;$i++)
    		{
    			if($datoCobro[$i]!='')
    			{    				
	    			$dataId=explode("-",$datoCobro[$i]);	    			 
	    				
	    			$cobro=$cobroMapper->obtenerDatosCobroEmail($dataId[0],$dataId[1]);
	    			
	    			$total_cobros+=floatVal($cobro[0]['ci_valormoneda']);
	    			
	    			$datos=array
	    					(
	    							$cobro[0]['ci33_nombre'],
	    							$cobro[0]['ci04_razonsocial'],
	    							$cobro[0]['ci_monto'],
	    							$cobro[0]['ci_valoruf'],
	    							(number_format($cobro[0]['ci_valormoneda'],0,",",".")),
	    							(number_format($total_cobros,0,",",".")),
	    							$cobro[0]['ci35_idformapago'],
	    							
	    					);
	    			
	    			$datosCobros[]=$datos;
    			}
    		}
    	}
    	 
    	$arreglo =array(
    			"cobro" => $datosCobros
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);
    }

	public function listadoemailAction()
	{
		$emailMapper=new Application_Model_EmailMapper();
		 
		$listadoEmail = array();
		
		if(!empty($_REQUEST['idCliente']))
		{
			if(sizeof($emailMapper->listadoEmailCobro($_REQUEST['idCliente']))>0)
			{
				foreach ( $emailMapper->listadoEmailCobro($_REQUEST['idCliente']) as $i => $email )
				{
					
					$data =array(
							$listado[]['ci04_rut']= $email['ci04_rut'],
							$listado[]['ci33_nombre']= $email['ci33_nombre'],
							$listado[]['ci47_fechaenvio']= $email ['ci47_fechaenvio'],
							$listado[]['ci_nombreUsuario']= $email['ci_nombreUsuario'],
							$listado[]['ci47_emailenvio']= $email['ci47_emailenvio'],
							$listado[]['ci47_asunto']= $email['ci47_asunto']
					);
			
					$listadoEmail[] = $data;
				}
			}
			else
			{
				$listadoEmail = [];
			}			
		}
		else
		{
			$listadoEmail = [];
		}	 
		 
		$arreglo =array(
				"data" => $listadoEmail
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}

	public function obtieneemailasociadosAction()
	{
		$contactoMapper = new Application_Model_ContactoMapper();
		
		$emailContactos=array();
		
		if(!empty($_REQUEST ['idCliente']))
		{
		
			if(sizeof($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente'])))
			{
				foreach ($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente']) as $i => $client)
				{
					$data =array(
							$emailContacto[]['ci09_email']= $client['ci09_email'],
							$emailContacto[]['ci09_nombre']= $client['ci09_nombre']
					);	
					
					$emailContactos[]=$data;					
				}
			}
			else
			{
				$emailContactos = null;
			}
		}
		
		$arreglo =array(
				"emails" => $emailContactos
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function obtieneemailasociadobolteoAction()
	{
		$contactoMapper = new Application_Model_ContactoMapper();
		
		$emailContactos=array();
		
		if(!empty($_REQUEST ['idCliente']))
		{
		
			if(sizeof($contactoMapper->obtieneEmailAsociadoBoleteo($_REQUEST ['idCliente'])))
			{
				foreach ($contactoMapper->obtieneEmailAsociadoBoleteo($_REQUEST ['idCliente']) as $i => $client)
				{
					$data =array(
							$emailContacto[]['ci09_email']= $client['ci09_email']
					);
						
					$emailContactos[]=$data;
				}
			}
			else
			{
				$emailContactos = null;
			}
		}
		
		$arreglo =array(
				"emails" => $emailContactos
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}	
	
	public function obtienedatosusuarioemailAction()
	{
		$usuarioMapper= new Application_Model_MantenedorUsuarioMapper();	
		
		if(!empty($_REQUEST ['idUsuario']))
		{
		
			if(sizeof($usuarioMapper->datosUsuarioEmail($_REQUEST ['idUsuario'])))
			{
				foreach ($usuarioMapper->datosUsuarioEmail($_REQUEST ['idUsuario']) as $i => $user)
				{
					$data =array(
							$usuarios[]['lc01_nombreUsuario']= $user['lc01_nombreUsuario'],
							$usuarios[]['lc02_nombrePerfil']= $user['lc02_nombrePerfil']
					);					
				}
			}
			else
			{
				$data = null;
			}
		}
		
		$arreglo =array(
				"usuario" => $data
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
		
	public function realizadevolucionAction(){
		
		 
		$idCliente = $_POST['cliente'];		
		$montoDevolucion = $_POST['monto'];
		$saldo = $_POST['saldo'];
		$clienteMapper = new Application_Model_ClienteMapper ();
		$res = $clienteMapper->realizaDevolucion($idCliente,$montoDevolucion,$saldo);
		 
		if($res){
			$respuesta = array('respuesta' => true);
		}else
		{
			$respuesta = array('respuesta' => false);
		}
		
		return $this->_helper->json->sendJson($respuesta);
	}
}

