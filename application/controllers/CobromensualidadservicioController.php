<?php 
class CobromensualidadservicioController extends Zend_Controller_Action 
{
	protected $_config;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;
	protected $_url;
	
	protected $_redirector = null;
		
	public function init()
	{
		$bootstrap = $this->getInvokeArg ( 'bootstrap' );
		$this->_config = $bootstrap->getOptions ();
		
		$this->_redirector = $this->_helper->getHelper('Redirector');
		
		$this->view->nombre_sitio = $this->_config ['nombre_sitio'];
		$this->view->skin = $this->_config ['skin'];
		$this->_url = $this->_config['urlaplicacion'];
				
		$this->view->path_pdfboleteo = $this->_config ['path_pdfboleteo'];
		$this->view->path_ppm = $this->_config ['path_pdfppm'];
		
		$this->view->host=$this->_config['urlaplicacion'];		
		$this->view->path_css = $this->_config['url_css'];
		
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
   
    public function ingresomensualidadserviciosAction()
    {
    	$this->_helper->layout->setLayout('layoutingresomensualidadservicios');
    	$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    	     	
    	$this->view->msnbusqueda="1";
    	
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
    	
    	$this->view->fecha=str_pad($mesNum, 2, "0", STR_PAD_LEFT).'-'.$anio;
    	
    	$this->view->titulo="periodo ".$meses[$mesNum]." ".$anio;
    	
    	$this->obtieneMensualidadesServicios();
    }
           
    private function obtieneMensualidadesServicios()
    {
    	$rutMapper=new Application_Model_RutMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    	$valoresMapper=new Application_Model_ValoresMapper();    	
    	
    	$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    	 
    	$tabla="";
    	$select="";
    	
    	$valMensualidad='';
    	$valServicio='';
    	
    	$disabledMensualidad='';
    	$disabledServicioVariable='';    	
    	
    	if(isset($_REQUEST['ejecutivoSelect'])&&
    	   isset($_REQUEST['clienteEjecutivoSelect'])&&
    	   isset($_REQUEST['rutClieneteSelect'])&&
    	   isset($_REQUEST['fecha']))
    	{    	    		
    		
    		$this->view->fecha=$_REQUEST['fecha'];
    		
    		$fecha=explode("-",$_REQUEST['fecha']);
    		
    		$this->view->titulo="periodo ".$meses[intval($fecha[0])]." ".$fecha[1];
    		
    		if($_REQUEST['ejecutivoSelect']!='')
    		{
    			$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
    		}
    		else
    		{
    			$this->view->idEjecutivo='0';
    		}
    		
    		if($_REQUEST['clienteEjecutivoSelect']!='')
    		{
    			$this->view->idCliente=$_REQUEST['clienteEjecutivoSelect'];
    		}
    		else
    		{
    			$this->view->idCliente='0';
    		}
    		
    		if($_REQUEST['rutClieneteSelect']!='')
    		{
    			$this->view->idRut=$_REQUEST['rutClieneteSelect'];
    		}
    		else
    		{
    			$this->view->idRut='0';
    		}
    		
    		$this->view->msnbusqueda="2";
    		
    	   $data=array(   
    	   		"idEjecutivo"=>$_REQUEST['ejecutivoSelect'],
    	   		"idCliente"=>$_REQUEST['clienteEjecutivoSelect'],
    	   		"idRut"=>$_REQUEST['rutClieneteSelect']
    	   );
    		
    	$select=$this->obtieneFormaPago($cobroMapper);
    	
    	$tabla.="<div style='width: auto;height: 500px;overflow: scroll;'>
    				<table id='tableMensualidadServicio'
						class='table table-bordered cell-border compact'>
						<thead>
							<tr>
								<th>Id</th>
								<th style='width: 170px;'>Cliente</th>
								<th>RUT</th>
    							<th>N Sociedad</th>
								<th style='width: 250px;'>Nombre / Raz&oacute;n Social</th>
								<th>Mensualidad</th>
								<th>Servicio Variable</th>
								<th>Forma de pago</th>
							</tr>
						</thead>
						<tbody>";    	
    		
    		$datosUF=array(
    				"mesUf"=>$fecha[0],
    				"anioUf"=>$fecha[1]
    		);  
    		
    			    	
    		$ufMesSeleccionado=$valoresMapper->valorUf($datosUF);   	

	    	foreach ($rutMapper->obtieneRutCobroMensualidadServicio($data) as $i =>$rut):	    	
	    	
	    		$valMensualidad=round(floatval($rut['ci04_valormensualidad'])*floatval($ufMesSeleccionado),0);
	    	
	    		$valServicio=round(floatval($rut['ci04_valorservicios'])*floatval($ufMesSeleccionado),0);
	    	
	    		if ($valMensualidad==0)
	    		{
	    			$valMensualidad='0';	    		
	    		}
	    		
	    		if ($valServicio==0)
	    		{
	    			$valServicio='0';
	    		}	 	    		
	    		
	    		//VERIFICA MENSUALIDAD
	    		if($cobroMapper->verificaMensualidadServicioByIdRutFecha($rut['ci04_idrrut'],'4',$_REQUEST['fecha']))
	    		{	    			
	    			$valMensualidad='0';
	    		}
	    		
	    		//VERIFICA SERVICIO VARIABLE
	    		if($cobroMapper->verificaMensualidadServicioByIdRutFecha($rut['ci04_idrrut'],'5',$_REQUEST['fecha']))
	    		{	    			
	    			$valServicio='0';
	    		}
	    		
	    		$tabla.="
	    				<tr>
	    					<td>".$rut['ci04_idrrut']."</td>
	    					<td>".$rut['ci03_nombre']."</td>
	    					<td>".$rut['ci04_rut']."</td>
	    					<td>".$rut['ci04_numerosociedad']."</td>		
	    					<td>".$rut['ci04_razonsocial']."</td>
	    					<td>
	    						<div class='input-group' style='width:160px;'>
	    							<span class='input-group-addon'><strong>$</strong></span>
	    							<input type='text' value='$valMensualidad'  class='form-control mensualidad' disabled>
	    						</div>
	    					</td>
	    					<td>
	    						<div class='input-group' style='width:160px;'>
	    							<span class='input-group-addon'><strong>$</strong></span>
	    							<input type='text' value='$valServicio'  class='form-control servicio' disabled>
	    						</div>
	    					</td>
	    					<td>$select</td>
	    				</tr>    				
	    				";    	
	    		
	    		$disabledMensualidad='';
	    		$disabledServicioVariable='';
	    		
	    	endforeach;
	    			
	    	$tabla.="   </tbody>
					</table>
	    		</div>";
    	
    	$this->view->tablaMenSer =$tabla;   
    	}
    	
    }
     
    private function obtieneFormaPago($cobroMapper)
    {
    	$select='';

    	$select.='<select style="width:100px;" class="form-control" disabled><option value="">Seleccione</option>';
    	foreach ($cobroMapper->obtenerFormasDePago() as $i => $formas):
    	 
    	if($formas['ci35_tipopago']=='N/A')
    	{
    		$select.='<option selected value="'.$formas['ci35_idformapago'].'">'.$formas['ci35_tipopago'].'</option>';
    	}
    	else
    	{
    		$select.='<option value="'.$formas['ci35_idformapago'].'">'.$formas['ci35_tipopago'].'</option>';
    	}
    	 
    	endforeach;
    	 
    	$select.='</select>';
    	
    	
    	return $select;
    }
    
    public function registracobromensualidadservicioAction()
    {    	    	
    	$cobroMapper=new Application_Model_CobroMapper();
    	
    	$direccion='cobromensualidadservicio/ingresomensualidadservicios';
    	
    	if(!empty($_POST['data']))
    	{
    		$data = json_decode ( $_POST['data'], true );
    		 
    		$response = array();
    		 
    		$res=$cobroMapper->ingresarCobroHonorarioMensualidadServicio($data);
    		 
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
    			"registro"=>$response
    	);    	
	  	
    	return $this->_helper->json->sendJson($arreglo);
    }

}

