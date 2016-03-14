<?php

class ImpuestoController extends Zend_Controller_Action 
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
				
		$this->view->path_pdfboleteo = $this->_config ['path_pdfboleteo'];
		$this->view->path_ppm = $this->_config ['path_pdfppm'];
		
		$this->view->host=$this->_config['urlaplicacion'];		
		$this->view->path_css = $this->_config['url_css'];
		
		$this->_rol = 0;
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
			
			$this->view->nombrePerfil=$tipoperfilres->obtienePerfilByIdUsuario($id_uduario);
			
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
   
    public function modificaimpuestoAction()
    {
    	$this->_helper->layout->setLayout('layoutmodificaimpuesto');
    	
    	$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    	$this->view->titulo=""; 
    	$this->view->hideImpuesto="0";    	
    	
    	$this->view->idEjecutivo='0';
    	$this->view->idCliente='0';
    	$this->view->idRut='0';   	

    	$mes=$meses[date('n')];
    	$mesNum=date('n');
    	$anio=date('Y');
    	
    	if(date('j')<=20)
    	{
    		$mes=$meses[date('n')-1];
    		$mesNum=(date('n')-1);
    	
    		if((date('n')-1)<1)
    		{
    			$mes=$meses[12];
    			$anio=(date('Y')-1);
    			$mesNum=12;
    		}
    	}
    	 
    	$this->view->calendario=str_pad($mesNum, 2, "0", STR_PAD_LEFT).'-'.$anio;
    	 
    	$this->view->titulo="- periodo ".$meses[$mesNum]." ".$anio;
    	
    	if(!empty($_REQUEST['tipoCobroSelect']))
    	{
    		$this->view->hideImpuesto="1";
    		$this->obtieneImpuestosUnicos();
    		
    		$this->view->titulo="- periodo ".$this->fechaNombre($this->fecha($mes,$meses,$anio),'1',$meses);
    		$this->view->calendario=$this->fecha($mes,$meses,$anio);
    	}
    }
    
    private function obtieneImpuestosUnicos()
    {    	
    	$impuestoMapper=new Application_Model_ImpuestoMapper();    	
    
    	$tabla="";
    	
    	$data=array(    			
    			"idEjecutivo" =>$_REQUEST['ejecutivoSelect'],
    			"idCliente" =>$_REQUEST['idClienteSelect'],
    			"idRut" =>$_REQUEST['idRutSelect'],
    			"fecha" =>$_REQUEST['dateCobroInput'],
    	);
    	
    	if($_REQUEST['ejecutivoSelect']!='')
    	{
    		$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
    	}
    	
    	if($_REQUEST['idClienteSelect']!='')
    	{
    		$this->view->idCliente=$_REQUEST['idClienteSelect'];
    	}
    	
    	if($_REQUEST['idRutSelect']!='')
    	{
    		$this->view->idRut=$_REQUEST['idRutSelect'];
    	}
    	
    	$tabla.="<div style='width: auto;height: 500px;overflow: scroll;'>
    					<table class='table table-striped table-bordered nowrap' cellspacing='0' width='100%'
    				 		id='tableModificaImpuestoUnico'>
					 		<thead>
					 			<tr>
					 				<th>Id</th>
					 				<th style='width:300px;' >Cliente</th>
									<th>Contribuyente</th>
									<th>N&deg; Soc</th>
									<th style='width:300px;'>Raz&oacute;n Social</th>
									<th>Tipo</th>									
									<th>Impuesto &Uacute;nico</th>									
    								<th>Fecha Cobro</th>
								</tr>
							</thead>
							<tbody>";
    	
    	$resultados=$impuestoMapper->busquedaImpuestoUnico($data);
    	
    	$this->view->resultados=count($resultados);
    	
    	foreach ($resultados as $i => $impuesto):
    	
    			if(!$impuestoMapper->verificaImpuestoIngresadoCobroMasivo($impuesto['ci04_idrrut'],$impuesto['ci64_fecharegistro']))
    			{
    				$tabla.="	<tr>
				    				<td>".$impuesto['ci64_idimpuestounico']."</td>
					    			<td>".$impuesto['ci03_nombre']."</td>
					    			<td>".$impuesto['ci04_rut']."</td>
					    			<td>".$impuesto['ci04_numerosociedad']."</td>
					    			<td>".$impuesto['ci04_razonsocial']."</td>
					    			<td>".$impuesto['ci40_tiposociedad']."</td>
					    			<td>
    	    							<div class='input-group' style='width:150px;'>
    	    								<span class='input-group-addon'>
    	    								<i class='fa fa-dollar'></i>
    	    								</span>
    	    								<input type='text' value='".$impuesto['ci64_valorimpuesto']."' class='form-control montoImpuesto'>
    	    							</div>
    	    						</td>
					    			<td>
		    							<div class='input-group' style='width:120px;'>
										     	<input disabled class='form-control dateCobroImpuesto'  value='".$impuesto['ci64_fecharegistro']."'
												value='' data-provide='datepicker'
												type='text'> <span class='input-group-addon'><i
												class='fa fa-calendar'></i></span>
										</div>
									</td>				  
				    			</tr>";
    			}
		
    	endforeach;
    	
    	$tabla.="			</tbody>
    					</table>
    			 </div>";
    	
    	$this->view->tablaImpuesto =$tabla;
    	
    }
    
    private function fecha($mes,$meses,$anio)
    {
    	$fechaCalendario="";
    	$fechaCliente=isset($_REQUEST['dateCobroInput']);
    
    	if($fechaCliente=='')
    	{
    		//asigna fecha para f29 y previred
    		if($_REQUEST['tipoCobroSelect']!='2')
    		{
    			$fechaCalendario=str_pad(array_search($mes,$meses), 2, "0", STR_PAD_LEFT)."-".$anio;
    		}
    		else
    		{
    			$fechaCalendario=date('Y');
    		}
    		 
    	}
    	else
    	{
    		$fechaCalendario=$_REQUEST['dateCobroInput'];
    	}
    
    	return $fechaCalendario;
    }

    private function fechaNombre($fecha,$tipoCobro,$meses)
    {
    	$fechaNombre="";
    
    	if($tipoCobro!='2')
    	{
    		$date=explode("-",$fecha);
    
    		$fechaNombre=$meses[intval($date[0])]." ".$date[1];
    	}
    	else
    	{
    		$fechaNombre=$fecha;
    	}
    
    	return $fechaNombre;
    }

    public function editarimpuestoAction()
    {
    	$impuestoMapper=new Application_Model_ImpuestoMapper();      	 
    	 
    	if(!empty($_REQUEST['jsonImpuesto']))
    	{
    		$data = json_decode ( $_REQUEST['jsonImpuesto'], true );
    		 
    		$response = array();
    		 
    		$res=$impuestoMapper->modificaImpuestoUnico($data);
    		 
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
    	 
    	$arreglo=array(
    			"modifica"=>$response
    	);
    	
    	return $this->_helper->json->sendJson($arreglo);
    }
    
}
