<?php
class PagoController extends Zend_Controller_Action {
	protected $_config;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;
	
	public function init() {
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
	
 	public function ingresoordendepagoAction()
    {
    	$this->_helper->layout->setLayout('layoutingresoordendepago');    	
    }
    
    public function verificacionordendepagoAction()
    {
    	$this->_helper->layout->setLayout('layoutverificacionordendepago');
    }
    
    public function verificacionpecautopagoAction() {
    	$this->_helper->layout->setLayout ( 'layoutverificacionpecautopago' );
    }
    
    public function registrarordenpagoAction()
    {
    	$pagoMapper=new Application_Model_PagoMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    	
    	$response=array();
    	
    	if(!empty($_REQUEST['numeroCheque'])&&
    	   !empty($_REQUEST['titular'])&&
    	   !empty($_REQUEST['banco'])&&
    	   !empty($_REQUEST['idCobro'])&&
    	   !empty($_REQUEST['tipoCobro']))
    	{  		
    		
    		$datosOrdenPago = array (
    			"banco" => $_REQUEST ['banco'],
    			"numeroCheque" => $_REQUEST ['numeroCheque'],
    			"titular" => $_REQUEST ['titular'],
    			"montoOrden" => $_REQUEST ['montoOrden']
    		);
    		
    		if(!$pagoMapper->verificaNumeroDeCheque($_REQUEST ['numeroCheque']))
    		{
    			switch($_REQUEST['tipoCobro'])
    			{
    				case 1:
    						$res=$pagoMapper->ingresarOrdenPago($datosOrdenPago);
    						$idOrdenPago=mysql_insert_id();
    							
    						if($res)
    						{
    							$response[0]='1';
    							$pagoMapper->ingresarOrdenDetalleMasivo($_REQUEST['idCobro'],$idOrdenPago);
    							$cobroMapper->modificarEstadoCobroMasivo('2',$_REQUEST ['idCobro']);
    						}
    						else
    						{
    							$response[0]='2';
    						}
    			
    					break;
    				case 2:  			
    						$res=$pagoMapper->ingresarOrdenPago($datosOrdenPago);
    						$idOrdenPago=mysql_insert_id();
    			
    						if($res)
    						{
    							$response[0]='1';
    							$pagoMapper->ingresarOrdenDetalleIndividual($_REQUEST['idCobro'],$idOrdenPago);
    							$cobroMapper->modificarEstadoCobroIndividual('2',$_REQUEST ['idCobro']);
    						}
    						else
    						{
    							$response[0]='2';
    						}   				
    			
    					break;
    			}
    		}
    		else
    		{
    			$response[0]='3';
    		}    		
    	}
    	else
    	{
    		$response[0]='variables';
    	}    	
    	
    	$arreglo =array(
    			"registro" => $response
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    }
    
   	public function obtenerordenespagoAction()
   	{
   		$pagoMapper=new Application_Model_PagoMapper();
   		
   		$listadoOrdenesPagos=array();
   	
   			
   				foreach ($pagoMapper->listadosOrdenesPago($_REQUEST['idCliente'],$_REQUEST['idRut'],$_REQUEST['fechaInicio'],$_REQUEST['fechaFinal'],$_REQUEST['idUsuario']) as $i => $pago)
   				{
   					$data =array(   							
   							$datoOrdenesPago []['ci_idcobro'] = $pago ['ci_idcobro'],
   							$datoOrdenesPago []['ci_tipocobro'] = $pago ['ci_tipocobro'],
   							$datoOrdenesPago []['ci22_idordenpago'] = $pago ['ci22_idordenpago'],
   							$datoOrdenesPago []['ci22_fechaordenpago'] =$pago ['ci22_fechaordenpago'],
   							$datoOrdenesPago []['ci_glosa'] = $pago ['ci_glosa'],
   							$datoOrdenesPago []['ci04_numerosociedad'] = $pago ['ci04_numerosociedad'],
   							$datoOrdenesPago []['ci04_rut'] = $pago ['ci04_rut'],
   							$datoOrdenesPago []['ci_monto'] ="$ ". (number_format($pago ['ci_monto'],0,",",".")),
   							$datoOrdenesPago []['ci22_dinerorecibido'] ="$ ".(number_format($pago ['ci22_dinerorecibido'],0,",",".")),
   							$datoOrdenesPago []['ci_numerofolio'] =$pago ['ci_numerofolio'],
   							$datoOrdenesPago []['ci53_nombreestado'] = $pago ['ci53_nombreestado'],
   							$datoOrdenesPago []['ci35_tipopago'] = $pago ['ci35_tipopago'],
   							$datoOrdenesPago []['ci_observacion'] = $pago ['ci_observacion']
   					);
   					
   					$listadoOrdenesPagos[]=$data;
   				}
   			
   			
   		
   		$arreglo = array (
			"data" => $listadoOrdenesPagos		
   		);
   		
   		return $this->_helper->json->sendJson($arreglo);   		
   	}	
  	   	
	public function registrarfolioAction()
	{
		$pagoMapper=new Application_Model_PagoMapper();	
		$cobroMapper=new Application_Model_CobroMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['idPago'])&&
		   !empty($_REQUEST['idCobro'])&&
		   !empty($_REQUEST['tipoCobro'])&&
		   !empty($_REQUEST['folio']))
		{			
			$res=$pagoMapper->registraFolio($_REQUEST['folio'],$_REQUEST['idPago']);
			
			if($res)
			{
				$response[0]='1';
				
				if($_REQUEST['tipoCobro']=='1')
				{
					$cobroMapper->modificarEstadoCobroMasivo('4',$_REQUEST['idCobro']);
				}
				else
				{
					$cobroMapper->modificarEstadoCobroIndividual('4',$_REQUEST['idCobro']);
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
		
		$arreglo=array(
				"registro"=>$response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}  	
   	
	public function registrarfoliopecAction()
	{
		$pagoMapper=new Application_Model_PagoMapper();
		$cobroMapper=new Application_Model_CobroMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['folio'])&&
		   !empty($_REQUEST['idCobro'])&&
		   !empty($_REQUEST['tipoCobro']))
		{
			
			if(!$pagoMapper->verificarNumeroFolioPec($_REQUEST['folio']))
			{
				$res=$pagoMapper->registraFolioPec($_REQUEST['folio']);
					
				$idFolioPec=mysql_insert_id();
				
				if($res)
				{
					$response[0]='1';
				
					if($_REQUEST['tipoCobro']=='1')
					{
						$cobroMapper -> modificarEstadoCobroMasivo('4',$_REQUEST['idCobro']);
						$pagoMapper -> registraDetalleFolioPecMasivo($idFolioPec,$_REQUEST['idCobro']);
					}
					else
					{
						$cobroMapper->modificarEstadoCobroIndividual('4',$_REQUEST['idCobro']);
						$pagoMapper -> registraDetalleFolioPecInidividual($idFolioPec,$_REQUEST['idCobro']);
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
			$response[0]='2';
		}
		
		$arreglo=array(
				"registro"=>$response
		);
		
		return $this->_helper->json->sendJson($arreglo);
	}
	
	public function obtieneordenpagobyidcobroAction()
	{
		$pagoMapper=new Application_Model_PagoMapper();
		 
		$datosOrdenesPagos=array();
		 
		if(!empty($_REQUEST['idCobro'])&&
		   !empty($_REQUEST['tipoCobro']))
		{
			if(sizeof($pagoMapper->obtieneDatosOrdenPago($_REQUEST['idCobro'],$_REQUEST['tipoCobro']))>0)
			{
				foreach ($pagoMapper->obtieneDatosOrdenPago($_REQUEST['idCobro'],$_REQUEST['tipoCobro']) as $i => $orden)
				{		
					$data =array(
							$datoOrdenes [$i]['ci58_idbanco'] = $orden ['ci58_idbanco'],
							$datoOrdenes [$i]['ci22_titular'] = $orden ['ci22_titular'],
							$datoOrdenes [$i]['ci22_numerocheque'] = $orden ['ci22_numerocheque'],
							$datoOrdenes [$i]['ci22_idordenpago'] = $orden ['ci22_idordenpago'],
							$datoOrdenes [$i]['ci22_dinerorecibido'] = $orden ['ci22_dinerorecibido']
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
		 
		$arreglo = array (
				"data" => $data
		);
		 
		return $this->_helper->json->sendJson($arreglo);
	}

	
	
}

