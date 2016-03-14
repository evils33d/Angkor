<?php

ini_set('memory_limit','2048M');
set_time_limit(300);
ignore_user_abort(true);

class CobromasivoController extends Zend_Controller_Action 
{
	protected $_config;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;
	private $path_boleteo;
	private $path_css;
	private $path_ppm;	
		
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
   
    public function enviomasivocobroAction()
    {
    	$this->_helper->layout->setLayout('layoutenviomasivocobro');
    }
    
    public function modificarcobromasivoAction()
    {
    	$this->_helper->layout->setLayout('layoutmodificarcobromasivo');
    	
    	$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    	     	
    	$this->view->titulo="";
    	$this->view->hideRenta ='0';  
    	
    	$this->view->idCliente='0';
    	$this->view->idRut='0';
    	$this->view->idEjecutivo='0';
    	
    	$this->view->resultados='1';
    	
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
    	
    	$this->view->titulo=$meses[$mesNum]." ".$anio;
    	
    	if(!empty($_REQUEST['tipoCobroSelect']))
    	{
    		switch ($_REQUEST['tipoCobroSelect'])
    		{
    			case '1':   $this->view->hidef29 ='1';
			    			$this->view->hideRenta ='2';
			    			$this->view->hidePrevired='3';
			    			$this->view->titulo="- F29 periodo ".$this->fechaNombre($this->fecha($mes,$meses,$anio),'1',$meses);
			    			$this->view->calendario=$this->fecha($mes,$meses,$anio);
			    			
			    			$this->cargaDatosModificacionCobrof29();
			    				
			    			$this->view->tipoCobro=$_REQUEST['tipoCobroSelect'];
			    				
			    			if($_REQUEST['ejecutivoSelect']!='')
			    			{
			    				$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idEjecutivo='0';
			    			}
			    				
			    			if($_REQUEST['nombreClienteSelect']!='')
			    			{
			    				$this->view->idCliente=$_REQUEST['nombreClienteSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idCliente='0';
			    			}
			    				
			    			if($_REQUEST['rutCobroMasivoSelect']!='')
			    			{
			    				$this->view->idRut=$_REQUEST['rutCobroMasivoSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idRut='0';
			    			}
    				
    						break;
    			 
    			case '2':   $this->view->hideRenta ='1';
			    			$this->view->hidef29 ='2';
			    			$this->view->hidePrevired='3';
			    			$this->view->titulo="- Renta A&ntilde;o ".$this->fechaNombre($this->fecha($mes,$meses,$anio),'2',$meses);
			    			$this->view->calendario=$this->fecha($mes,$meses,$anio);
			    			
			    			$this->cargaDatosModificacionCobroRenta();
			    			
			    			$this->view->tipoCobro=$_REQUEST['tipoCobroSelect'];
			    			
			    			if($_REQUEST['ejecutivoSelect']!='')
			    			{
			    				$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idEjecutivo='0';
			    			}
			    				
			    			if($_REQUEST['nombreClienteSelect']!='')
			    			{
			    				$this->view->idCliente=$_REQUEST['nombreClienteSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idCliente='0';
			    			}
			    				
			    			if($_REQUEST['rutCobroMasivoSelect']!='')
			    			{
			    				$this->view->idRut=$_REQUEST['rutCobroMasivoSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idRut='0';
			    			}
    				
    						break;
    			 
    			case '3':   $this->view->hidePrevired='1';
			    			$this->view->hidef29 ='2';
			    			$this->view->hideRenta ='3';
			    			$this->view->titulo="- Previred periodo ".$this->fechaNombre($this->fecha($mes,$meses,$anio),'3',$meses);
			    			$this->view->calendario=$this->fecha($mes,$meses,$anio);
			    						    							    			
			    			$this->cargaDatosModificacionCobroPrevired();
			    			
			    			$this->view->tipoCobro=$_REQUEST['tipoCobroSelect'];
			    				
			    			if($_REQUEST['ejecutivoSelect']!='')
			    			{
			    				$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idEjecutivo='0';
			    			}
			    				
			    			if($_REQUEST['nombreClienteSelect']!='')
			    			{
			    				$this->view->idCliente=$_REQUEST['nombreClienteSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idCliente='0';
			    			}
			    				
			    			if($_REQUEST['rutCobroMasivoSelect']!='')
			    			{
			    				$this->view->idRut=$_REQUEST['rutCobroMasivoSelect'];
			    			}
			    			else
			    			{
			    				$this->view->idRut='0';
			    			}
    				
    						break;
    		}
    	}
    }
    
    private function cargaDatosModificacionCobrof29()
    {
    	$rutMapper=new Application_Model_RutMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    
    	$select='';
    	
    	$tabla="";
    	 
    	if(isset($_REQUEST['perfil']) && 
    	   isset($_REQUEST['tipoCobroSelect'])&& 
    	   isset($_REQUEST['ejecutivoSelect'])&& 
    	   isset($_REQUEST['nombreClienteSelect'])&& 
    	   isset($_REQUEST['rutCobroMasivoSelect'])&& 
    	   isset($_REQUEST['dateCobroInput'])
    	  )
    	{ 
    		
    		$idPerfil=$_REQUEST['perfil'];
    
    		$data=array(    
    				"perfil"=>$idPerfil,
    				"tipoCobroSelect"=>$_REQUEST['tipoCobroSelect'],
    				"ejecutivoSelect"=>$_REQUEST['ejecutivoSelect'],
    				"nombreClienteSelect"=>$_REQUEST['nombreClienteSelect'],
    				"rutCobroMasivoSelect"=>$_REQUEST['rutCobroMasivoSelect'],
    				"fecha" => $_REQUEST['dateCobroInput']
    		);
    		    
    		$tabla.="<div style='width: auto;height: 500px;overflow: scroll;'>
    					<table class='table table-striped table-bordered nowrap' cellspacing='0' width='100%'
    				 		id='tableIngresoCobroMasivoF29'>
					 		<thead>
					 			<tr>
					 				<th>ID</th>
					 				<th style='width:300px;' >Cliente</th>
									<th>Contribuyente</th>
									<th>N&deg; Soc</th>
									<th style='width:300px;'>Raz&oacute;n Social</th>
									<th>Clave SII</th>
									<th>Tipo</th>
									<th>Ingresos s/retenciones</th>
									<th>Ingresos c/retenciones</th>
									<th>Ingresos Soc</th>
									<th>Retiros Soc</th>
									<th>Tasa (115)</th>
									<th>PPM neto Det</th>
									<th>Bol ret a tercero</th>
									<th>Retenciones</th>
									<th>Impuesto &Uacute;nico</th>
									<th>IVA a pago</th>
									<th>Remanente</th>
									<th>Total</th>
									<th>Forma Pago</th>
    								<th>Fecha Cobro</th>
								</tr>
							</thead>
							<tbody>";
    		
    		$ingresoSinRetenciones='';
    		$ingresoConRetenciones='';
    		$IngresoSociedad='';
    		$retiroSociedad='';
    
    		$impuestoUnico='';
    		$iva='';
    		$remanate='';
    		$controltasa='';
    		$boleteoRetTerceros='';
    
    		$valTasaPrimeraCategoria='';
    		$valTasaImpuestoUnico='';
    		$valBoleteoTercero='';
    		$valIva='';
    		$valRemanate='';
    
    		$valIngresoSinRetenciones='';
    		$valIngresoConRetenciones='';
    		$valIngresoSociedad='';
    		$valRetiroSociedad='';
    
    		$tasaPrimraCategoria='';
    		
    		$claveSii='';
    		
    		$resultados=$cobroMapper->buscarCobroMasivo($data);
    		
    		$this->view->resultados=count($resultados);
    
    		foreach ($resultados as $i => $rut):
    		
		    		$select=$this->formaPago($cobroMapper,$rut['ci35_idformapago']);
		    		 
		    		switch($rut['ci40_tiposociedad'])
		    		{
		    			case "Persona Natural":
		    					
							    				$ingresoSinRetenciones='';
							    				$ingresoConRetenciones='';
							    				$IngresoSociedad='disabled';
							    				$retiroSociedad='disabled';
							    			  
							    				$valIngresoSinRetenciones=$rut['ci07_ingsinretencion'];
							    				$valIngresoConRetenciones=$rut['ci07_ingconretencion'];
							    				$valIngresoSociedad='0';
							    				$valRetiroSociedad='0';							    			  
							    				
							    				$controltasa='<select style="width:100px;" class="form-control" id="" disabled>
							    								  	<option value="'.$rut['ci37_idtasa'].'" selected>'.$rut['ci_valortasapn'].'%</option>
							    								  </select>';
							    				
							    				break;
		    			case "Primera Categoria":
		    					
							    				$ingresoSinRetenciones='disabled';
							    				$ingresoConRetenciones='disabled';
							    				$IngresoSociedad='';
							    				$retiroSociedad='disabled';
							    					
							    				$valIngresoSinRetenciones='0';
							    				$valIngresoConRetenciones='0';
							    				$valIngresoSociedad=$rut['ci07_ingsociedad'];
							    				$valRetiroSociedad='0';
							    					
							    				if($idPerfil=='4')
							    				{
							    					$controltasa='<div class="input-group" style="width:100px;"><input disabled type="text" value="'.str_ireplace(".",",",$rut['ci07_tasaprimeracat']).'"  class="form-control tasaInput" ><span class="input-group-addon"><strong>%</strong></span></div>';
							    				}
							    				else
							    				{
							    					$controltasa='<div class="input-group" style="width:100px;"><input type="text" value="'.str_ireplace(".",",",$rut['ci07_tasaprimeracat']).'"  class="form-control tasaInput" ><span class="input-group-addon"><strong>%</strong></span></div>';
							    				}
							    
							    				break;
		    			case "Segunda Categoria":
		    					
							    				$ingresoSinRetenciones='';
							    				$ingresoConRetenciones='';
							    				$IngresoSociedad='disabled';
							    				$retiroSociedad='disabled';
							    					
							    				$valIngresoSinRetenciones=$rut['ci07_ingsinretencion'];
							    				$valIngresoConRetenciones=$rut['ci07_ingconretencion'];
							    				$valIngresoSociedad='0';
							    				$valRetiroSociedad='0';
							    					
							    				$controltasa='<select style="width:100px;" class="form-control" id="" disabled>
							    								 <option value="'.$rut['ci37_idtasa'].'" selected>'.$rut['ci_valortasapn'].'%</option>
							    							  </select>';
							    					
							    				break;
		    			case "14 BIS":
		    					
							    				$ingresoSinRetenciones='disabled';
							    				$ingresoConRetenciones='disabled';
							    				$IngresoSociedad='';
							    				$retiroSociedad='';
							    					
							    				$valIngresoSinRetenciones='0';
							    				$valIngresoConRetenciones='0';
							    				$valIngresoSociedad=$rut['ci07_ingsociedad'];
							    				$valRetiroSociedad=$rut['ci07_retsociedad'];
							    					
							    				
							    				$controltasa='<select style="width:100px;" class="form-control" id="" disabled>
							    								 	<option value="'.$rut['ci39_idretencion'].'" selected>'.$rut['ci_valortasaretencion'].'%</option>
							    								 </select>';
							    					
							    				break;
		    		}
		    		 
		    		if($rut['ci04_iva']=='2')
		    		{
		    			$iva='disabled';
		    			$remanate='disabled';
		    			$valIva='0';
		    			$valRemanate='0';
		    		}
		    		else
		    		{
		    			$iva='';
		    			$remanate='';
		    			$valIva=$rut ['ci07_ivapago'];
		    			$valRemanate=$rut ['ci07_remanente'];
		    		}
		    		 
		    		if($idPerfil!='4')
		    		{
		    			
		    			if($idPerfil=='5'||$idPerfil=='6')
		    			{
		    				$claveSii='******';
		    			}
		    			else
		    			{
		    				$claveSii=$rut['ci11_sii'];
		    			}
		    			
		    			
		    			$impuestoUnico='disabled';
		    			$valTasaImpuestoUnico=$rut['ci07_impuestounico'];
		    			$valBoleteoTercero=$rut['ci07_bolretterceros'];
		    					    			
		    			if($idPerfil=='7' && $rut['ci04_previred']=='1' && $rut['ci04_numerosociedad']!='PN')
		    			{
		    				$impuestoUnico='';		    				
		    			}
		    		}
		    		else
		    		{
		    			$claveSii='******';
		    			$impuestoUnico='';
		    			$iva='disabled';
		    			$remanate='disabled';
		    			$ingresoSinRetenciones='disabled';
		    			$ingresoConRetenciones='disabled';
		    			$IngresoSociedad='disabled';
		    			$retiroSociedad='disabled';
		    			$boleteoRetTerceros='disabled';
		    			$valIva=$rut ['ci07_ivapago'];
		    			$valRemanate=$rut ['ci07_remanente'];		    
		    
		    			$valTasaImpuestoUnico=$rut['ci07_impuestounico'];
		    			$valBoleteoTercero=$rut['ci07_bolretterceros'];
		    			
		    			if($rut['ci04_numerosociedad']=='PN')
		    			{
		    				$impuestoUnico='disabled';
		    				$valTasaImpuestoUnico='0';
		    			}
		    		} 
		    		
		    			$tabla.="<tr>
											<td>".$rut['ci07_idcobromasivo']."</td>
											<td>".$rut['ci03_nombre']."</td>
											<td>".$rut['ci04_rut']."</td>
											<td>".$rut['ci04_numerosociedad']."</td>
											<td>".$rut['ci04_razonsocial']."</td>
											<td>".$claveSii."</td>
											<td>".$rut['ci40_tiposociedad']."</td>
		    
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valIngresoSinRetenciones' class='form-control montoF29' $ingresoSinRetenciones>
		    									</div>
		    								</td>
		    									 
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valIngresoConRetenciones' class='form-control montoF29' $ingresoConRetenciones>
		    									</div>
		    								</td>
		    
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valIngresoSociedad' class='form-control montoF29' $IngresoSociedad>
		    									</div>
		    								</td>
		    
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valRetiroSociedad' class='form-control montoF29' $retiroSociedad>
		    									</div>
		    								</td>
		    									 
		    								<td>$controltasa</td>
		    
		    								<td style='width: 130px;'>$ ".(number_format($rut['ci07_ppmnetdet'],0,",","."))."</td>
		    									 
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valBoleteoTercero' class='form-control montoF29' $boleteoRetTerceros>
		    									</div>
		    								</td>
		    									 
		    								<td style='width: 130px;'>$ ".(number_format($rut['ci07_retencion'],0,",","."))."</td>
		    									 
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valTasaImpuestoUnico' class='form-control montoF29' $impuestoUnico>
		    									</div>
		    								</td>
		    										
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valIva' class='form-control montoF29' $iva>
		    									</div>
		    								</td>
		    										
		    								<td>
		    									<div class='input-group' style='width: 130px;'>
		    									<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
		    									<input type='text' value='$valRemanate' class='form-control montoF29' $remanate>
		    									</div>
		    								</td>
		    										
		    								<td style='width: 130px;'>$ ".(number_format($rut['ci07_monto'],0,",","."))."</td>
		    									 
		    								<td>$select</td>
		    									
		    								<td>
		    									<div class='input-group' style='width:120px;'>
													<input disabled class='form-control dateCobroF29'  value='".$rut['ci07_fechapago']."'
														value='' data-provide='datepicker'
														type='text'> <span class='input-group-addon'><i
														class='fa fa-calendar'></i></span>
												</div>
											</td>
		    										
		    							</tr>";
		    		
		    		 
		    		$iva='';
		    		$remanate='';
    		 
    		endforeach;
    		 
    		$tabla.="
							</tbody>
					 </table></div>";
    		 
    		$this->view->tablaf29 =$tabla;
    	}
    }
    
    private function cargaDatosModificacionCobroRenta()
    {
    	$rutMapper=new Application_Model_RutMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    	
    	$tabla="";
    	 
    	$select="";
    	
    	$datosRut=array();
    	 
    	if(isset($_REQUEST['perfil']) && 
    	   isset($_REQUEST['tipoCobroSelect'])&& 
    	   isset($_REQUEST['ejecutivoSelect'])&& 
    	   isset($_REQUEST['nombreClienteSelect'])&& 
    	   isset($_REQUEST['rutCobroMasivoSelect'])&& 
    	   isset($_REQUEST['dateCobroInput'])
    	  )
    	{ 
    
    		$data=array(    
    				"perfil"=>$_REQUEST['perfil'],
    				"tipoCobroSelect"=>$_REQUEST['tipoCobroSelect'],
    				"ejecutivoSelect"=>$_REQUEST['ejecutivoSelect'],
    				"nombreClienteSelect"=>$_REQUEST['nombreClienteSelect'],
    				"rutCobroMasivoSelect"=>$_REQUEST['rutCobroMasivoSelect'],
    				"fecha" => $_REQUEST['dateCobroInput']
    		);    		
    	
    		$tabla.="<div style='width: auto;height: 500px;overflow: scroll;'>
    						<div class='form-group' id='resultadosRenta'>
								<table class='table table-striped table-bordered nowrap'
									ellspacing='0' width='100%' id='tableIngresoCobroMasivoRenta'>
									<thead>
										<tr>
											<th>ID</th>
											<th>Cliente</th>
											<th>Contribuyente</th>
											<th>Clave Sii</th>
											<th>N&deg; Soc</th>
											<th>Raz&oacute;n Social Contribuyente</th>
											<th>Monto Renta</th>
											<th>Forma Pago</th>
    										<th>Fecha Cobro</th>
										</tr>
									</thead>
									<tbody>";
    		
    		$this->view->resultados=count($cobroMapper->buscarCobroMasivo($data));
    		
    		foreach ($cobroMapper->buscarCobroMasivo($data) as $i => $rut):
    		 
    		$select=$this->formaPago($cobroMapper,$rut['ci35_idformapago']);   		
    			     			
    			$tabla.="
    									<tr>
											<td>".$rut['ci07_idcobromasivo']."</td>
    										<td>".$rut['ci03_nombre']."</td>
								            <td>".$rut['ci04_rut']."</td>
    										<td>".$rut['ci11_sii']."</td>
											<td>".$rut['ci04_numerosociedad']."</td>
    										<td>".$rut['ci04_razonsocial']."</td>
    	    								<td>
    	    									<div class='input-group' style='width:150px;'>
    	    										<span class='input-group-addon'>
    	    										<i class='fa fa-dollar'></i>
    	    										</span>
    	    										<input type='text' value='".$rut['ci07_monto']."' class='form-control montoRenta'>
    	    									</div>
    	    								</td>
    	    								<td>$select</td>
    	    								<td>
		    									<div class='input-group' style='width:120px;'>
													<input disabled class='form-control dateCobroRenta'  value='".$rut['ci07_fechapago']."'
														value='' data-provide='datepicker'
														type='text'> <span class='input-group-addon'><i
														class='fa fa-calendar'></i></span>
												</div>
											</td>
    	    							</tr>";
    		
    		endforeach;
    		 
    		$tabla.="
									</tbody>
								</table>
							</div>
    					</div>";
    	
    		 
    		$this->view->tablaRenta =$tabla;
    	}
    }
    
    private function cargaDatosModificacionCobroPrevired()
    {
    	$rutMapper=new Application_Model_RutMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    	 
    	$tabla="";
    	$select="";
    	 
    	if(isset($_REQUEST['perfil']) && 
    	   isset($_REQUEST['tipoCobroSelect'])&& 
    	   isset($_REQUEST['ejecutivoSelect'])&& 
    	   isset($_REQUEST['nombreClienteSelect'])&& 
    	   isset($_REQUEST['rutCobroMasivoSelect'])&& 
    	   isset($_REQUEST['dateCobroInput'])
    	  )
    	{ 
    
    		$data=array(    
    				"perfil"=>$_REQUEST['perfil'],
    				"tipoCobroSelect"=>$_REQUEST['tipoCobroSelect'],
    				"ejecutivoSelect"=>$_REQUEST['ejecutivoSelect'],
    				"nombreClienteSelect"=>$_REQUEST['nombreClienteSelect'],
    				"rutCobroMasivoSelect"=>$_REQUEST['rutCobroMasivoSelect'],
    				"fecha" => $_REQUEST['dateCobroInput']
    		); 
    	
    		$this->view->resultados='1';
    		
    		$resultados=$cobroMapper->buscarCobroMasivo($data);
    		
    		$this->view->resultados=count($resultados);
    	
    		$tabla .="<div style='width: auto;height: 500px;overflow: scroll;'>
	    				<table class='table table-striped table-bordered nowrap'
								cellspacing='0' width='100%' id='tableIngresoCobroMasivoPrevired'>
								<thead>
									<tr>
										<th>ID</th>
										<th>Cliente</th>
										<th>Contribuyente</th>
										<th>Clave Previred</th>
										<th>N&deg; Soc</th>
										<th>Raz&oacute;n Social Contribuyente</th>
										<th>Concepto</th>
										<th>Monto</th>
										<th>Forma Pago</th>
    									<th>Fecha Cobro</th>
									</tr>
								</thead>
								<tbody>";
    		
    		foreach ($resultados as $i => $rut):
    		 
		    		$select=$this->formaPago($cobroMapper,$rut['ci35_idformapago']);
				    	
				    		$tabla .="
													<tr>
														<td>".$rut['ci07_idcobromasivo']."</td>
														<td>".$rut['ci03_nombre']."</td>
														<td>".$rut['ci04_rut']."</td>
														<td>".$rut['ci11_previred']."</td>
														<td>".$rut['ci04_numerosociedad']."</td>
														<td>".$rut['ci04_razonsocial']."</td>
														<td>".$rut['ci07_conceptoprevired']."</td>
				    									<td>
				    										<div class='input-group' style='width:150px;'>
				    											<span class='input-group-addon'>
				    											<i class='fa fa-dollar'></i>
				    											</span>
				    											<input type='text' value='".$rut['ci07_monto']."' class='form-control montoPrevired'>
				    										</div>
				    									</td>
				    									<td>$select</td>
				    									<td>
					    									<div class='input-group' style='width:120px;'>
																<input disabled class='form-control dateCobroF29'  value='".$rut['ci07_fechapago']."'
																	value='' data-provide='datepicker'
																	type='text'> <span class='input-group-addon'><i
																	class='fa fa-calendar'></i></span>
															</div>
														</td>
				    								</tr>";
		    		
    		endforeach;
    	
    		$tabla .="
								</tbody>
							</table>
	    				</div>";
    	
    		$this->view->tablaPrevired =$tabla;
    	}
    }
          
    public function ingresomasivocobroAction()
    {    	
    	$valoresMapper=new Application_Model_ValoresMapper();
    	
    	$this->_helper->layout->setLayout('layoutingresomasivocobro');
    	
    	$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    	     	
    	$this->view->titulo="";
    	$this->view->hideRenta ='0';    	
    	
    	$this->view->idCliente='0';
    	$this->view->idRut='0';
    	$this->view->idEjecutivo='0';
    	$this->view->tasas='1';
    	 	
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
    	
    	$this->view->titulo=$meses[$mesNum]." ".$anio;
    	
    	if(!empty($_REQUEST['tipoCobroSelect']))
    	{	    	
	    	switch ($_REQUEST['tipoCobroSelect'])
	    	{
	    		case '1': $this->view->hidef29 ='1';
	    				  $this->view->hideRenta ='2';
	    				  $this->view->hidePrevired='3';	    				  
	    				  $this->view->titulo="- F29 periodo ".$this->fechaNombre($this->fecha($mes,$meses,$anio),'1',$meses);	    				 
	    				  $this->view->calendario=$this->fecha($mes,$meses,$anio);	
	    				  	    				  
	    				  $date=explode('-',$this->fecha($mes,$meses,$anio));
	    				  	    				  
	    				  $data=array(
	    				  		"mes"=>$date[0],
	    				  		"anio"=>$date[1] 
	    				  );
	    				  
	    				  if($valoresMapper->verificaValorTasa($data) && $valoresMapper->verificaValorRetencion($data))
	    				  {
	    				  		$this->view->tasas='1';
	    				  		$this->obtenerListadoCobrosf29($this->fecha($mes,$meses,$anio));
	    				  }
	    				  else
	    				  {
	    				  		$this->view->hidef29 ='2';
	    				  		$this->view->tasas='0';
	    				  }    				  
	    				  
	    				  $this->view->tipoCobro=$_REQUEST['tipoCobroSelect'];
	    				 	    				  
	    				  if($_REQUEST['ejecutivoSelect']!='')
	    				  {
	    				  	$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idEjecutivo='0';
	    				  }
	    				  
	    				  if($_REQUEST['nombreClienteSelect']!='')
	    				  {
	    				  	$this->view->idCliente=$_REQUEST['nombreClienteSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idCliente='0';
	    				  }
	    				  
	    				  if($_REQUEST['rutCobroMasivoSelect']!='')
	    				  {
	    				  	$this->view->idRut=$_REQUEST['rutCobroMasivoSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idRut='0';
	    				  }
	    				  
	    				  break;
	    		
	    		case '2': $this->view->hideRenta ='1';
	    				  $this->view->hidef29 ='2';
	    				  $this->view->hidePrevired='3';	    				  
	    				  $this->view->titulo="- Renta A&ntilde;o ".$this->fechaNombre($this->fecha($mes,$meses,$anio),'2',$meses);		    				 
	    				  $this->view->calendario=$this->fecha($mes,$meses,$anio);
	    				  $this->obtenerListadoCobrosRenta($this->fecha($mes,$meses,$anio));
	    				  
	    				  $this->view->tipoCobro=$_REQUEST['tipoCobroSelect'];
	    				 if($_REQUEST['ejecutivoSelect']!='')
	    				  {
	    				  	$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idEjecutivo='0';
	    				  }
	    				  
	    				  if($_REQUEST['nombreClienteSelect']!='')
	    				  {
	    				  	$this->view->idCliente=$_REQUEST['nombreClienteSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idCliente='0';
	    				  }
	    				  
	    				  if($_REQUEST['rutCobroMasivoSelect']!='')
	    				  {
	    				  	$this->view->idRut=$_REQUEST['rutCobroMasivoSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idRut='0';
	    				  }
	    				  
	    				  break;
	    		
	    		case '3': $this->view->hidePrevired='1';
	    				  $this->view->hidef29 ='2';
	    				  $this->view->hideRenta ='3';	    				  
	    				  $this->view->titulo="- Previred periodo ".$this->fechaNombre($this->fecha($mes,$meses,$anio),'3',$meses);		    				 
	    				  $this->view->calendario=$this->fecha($mes,$meses,$anio);  
	    				  $this->obtenerListadoCobrosPrevired($this->fecha($mes,$meses,$anio));
	    				  
	    				  $this->view->tipoCobro=$_REQUEST['tipoCobroSelect'];
	    				  
	    				  if($_REQUEST['ejecutivoSelect']!='')
	    				  {
	    				  	$this->view->idEjecutivo=$_REQUEST['ejecutivoSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idEjecutivo='0';
	    				  }
	    				  
	    				  if($_REQUEST['nombreClienteSelect']!='')
	    				  {
	    				  	$this->view->idCliente=$_REQUEST['nombreClienteSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idCliente='0';
	    				  }
	    				  
	    				  if($_REQUEST['rutCobroMasivoSelect']!='')
	    				  {
	    				  	$this->view->idRut=$_REQUEST['rutCobroMasivoSelect'];
	    				  }
	    				  else
	    				  {
	    				  	$this->view->idRut='0';
	    				  }
	    				  
	    				  break;
	    	}
    	}
    }
          
    private function obtenerListadoCobrosf29($fechaRegistro)
    {
    	$rutMapper=new Application_Model_RutMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    	 
    	$select='';
    	$select2='';
    	$tabla="";  
    	
    	if(isset($_REQUEST['perfil']) && 
    	   isset($_REQUEST['tipoCobroSelect'])&& 
    	   isset($_REQUEST['ejecutivoSelect'])&& 
    	   isset($_REQUEST['nombreClienteSelect'])&& 
    	   isset($_REQUEST['rutCobroMasivoSelect'])
    	  )
    	{ 
    		
    		$idPerfil=$_REQUEST['perfil'];
    		$tipoCobro=$_REQUEST['tipoCobroSelect'];
    		
    		
    		$fechas=explode('-',$fechaRegistro);
    		
    		$data=array(
    		
    				"perfil"=>$_REQUEST['perfil'],
    				"tipocobro"=>$_REQUEST['tipoCobroSelect'],
    				"idejecutivo"=>$_REQUEST['ejecutivoSelect'],
    				"idCliente"=>$_REQUEST['nombreClienteSelect'],
    				"idRut"=>$_REQUEST['rutCobroMasivoSelect'],
    				"mes"=>$fechas[0],
    				"anio"=>$fechas[1],
    				
    		);
    		
    		$select=$this->formaPago($cobroMapper,'');  
    		
    		$resultados=$rutMapper->obtieneRutCobroF29($data);
    		
    		$this->view->resultados='1';
    		
    		$tabla.="<div style='width: auto;height: 500px;overflow: scroll;'>
    					<table class='table table-striped table-bordered nowrap' cellspacing='0' width='100%'
    				 		id='tableIngresoCobroMasivoF29'>
					 		<thead>
					 			<tr>
					 				<th>ID</th>
					 				<th style='width:300px;' >Cliente</th>
									<th>Contribuyente</th>
									<th>N&deg; Soc</th>
									<th style='width:300px;'>Raz&oacute;n Social</th>
									<th>Clave SII</th>
									<th>Tipo</th>
									<th>Ingresos s/retenciones</th>
									<th>Ingresos c/retenciones</th>
									<th>Ingresos Soc</th>
									<th>Retiros Soc</th>
									<th>Tasa (115)</th>
									<th>PPM neto Det</th>
									<th>Bol ret a tercero</th>
									<th>Retenciones</th>
									<th>Impuesto &Uacute;nico</th>
									<th>IVA a pago</th>
									<th>Remanente</th>
									<th>Total</th>
									<th>Forma Pago</th>
								</tr>
							</thead>
							<tbody>";
    		
    		$ingresoSinRetenciones='';
    		$ingresoConRetenciones='';
    		$IngresoSociedad='';
    		$retiroSociedad='';  
    		
    		$impuestoUnico='';    		
    		$iva='';
    		$remanate='';    		
    		$controltasa='';
    		$boleteoRetTerceros='';    
    		
    		$valTasaPrimeraCategoria='';
    		$valTasaImpuestoUnico='';
    		$valBoleteoTercero='';
    		$valIva='';
    		$valRemanate='';
    		
    		$valIngresoSinRetenciones='';
    		$valIngresoConRetenciones='';
    		$valIngresoSociedad='';
    		$valRetiroSociedad='';
    		
    		$tasaPrimraCategoria='';
    		
    		$claveSII='';
    		
    		$valColor='';
    		
    		foreach ($resultados as $i => $rut):    			    			
    			
    			if($cobroMapper->verificaRutCobroMasivo($rut['ci04_idrrut'],'1'))
    			{
    				$idFormaPago=$cobroMapper->obtieneUltimaFormaPago($rut['ci04_idrrut'],'1','','2');
    				$select2=$this->formaPago($cobroMapper,$idFormaPago);
    			
    				$tasaPrimraCategoria=$cobroMapper->obtieneUltimaTasaPCIngresada($rut['ci04_idrrut']);
    			}
    			else
    			{
    				$tasaPrimraCategoria='';
    				$select2=$select;
    			}    			
    			
    			switch($rut['ci40_tiposociedad'])
    			{
    				case "Persona Natural":
    					
    					$ingresoSinRetenciones='';
			    		$ingresoConRetenciones='';
			    		$IngresoSociedad='disabled';
			    		$retiroSociedad='disabled';
			    		
			    		$valIngresoSinRetenciones='';
			    		$valIngresoConRetenciones='';
			    		$valIngresoSociedad='0';
			    		$valRetiroSociedad='0';
			    		
    					$controltasa='<select style="width:100px;" class="form-control" id="" disabled>
    								  	<option value="'.$rut['ci_idtasa'].'" selected>'.$rut['ci_valortasa'].'%</option>
    								  </select>';
    					
    					break;
    				case "Primera Categoria":    					  					
    					
    					$ingresoSinRetenciones='disabled';
    					$ingresoConRetenciones='disabled';
    					$IngresoSociedad='';
    					$retiroSociedad='disabled';
    					
    					$valIngresoSinRetenciones='0';
    					$valIngresoConRetenciones='0';
    					$valIngresoSociedad='';
    					$valRetiroSociedad='0';
    					
    					if($idPerfil=='4')
    					{
    						$controltasa='<div class="input-group" style="width:100px;"><input disabled type="text" value="'.str_ireplace(".",",",$tasaPrimraCategoria).'"  class="form-control tasaInput" ><span class="input-group-addon"><strong>%</strong></span></div>';
    					}
    					else
    					{
    						$controltasa='<div class="input-group" style="width:100px;"><input type="text" value="'.str_ireplace(".",",",$tasaPrimraCategoria).'"  class="form-control tasaInput" ><span class="input-group-addon"><strong>%</strong></span></div>';
    					}
    						
    					break;
    				case "Segunda Categoria":
    					
    					$ingresoSinRetenciones='';
    					$ingresoConRetenciones='';
    					$IngresoSociedad='disabled';
    					$retiroSociedad='disabled';
    					
    					$valIngresoSinRetenciones='';
    					$valIngresoConRetenciones='';
    					$valIngresoSociedad='0';
    					$valRetiroSociedad='0';
    					
    					$controltasa='<select style="width:100px;" class="form-control" id="" disabled>
    									<option value="'.$rut['ci_idtasa'].'" selected>'.$rut['ci_valortasa'].'%</option>
    								  </select>';
    					
    					break;
    				case "14 BIS":
    					
    					$ingresoSinRetenciones='disabled';
    					$ingresoConRetenciones='disabled';
    					$IngresoSociedad='';
    					$retiroSociedad='';
    					
    					$valIngresoSinRetenciones='0';
    					$valIngresoConRetenciones='0';
    					$valIngresoSociedad='';
    					$valRetiroSociedad='';
    					
    					$controltasa='<select style="width:100px;" class="form-control" id="" disabled>
    								 	<option value="'.$rut['ci_idretencion'].'" selected>'.$rut['ci_valorretencion'].'%</option> 
    								 </select>';    					
    					break;
    			}
    			
    			if($rut['ci04_iva']=='2')
    			{
    				$iva='disabled';
    				$remanate='disabled';
    				$valIva='0';
    				$valRemanate='0';
    			}
    			else
    			{
    				$valIva='';
    				$valRemanate='';
    			}
    			
    			if($idPerfil!='4')
    			{
    				$impuestoUnico='disabled';    				
    				
    				if($idPerfil=='5' || $idPerfil=='6' )
    				{    					
    					$claveSII='******';
    				}
    				else
    				{
    					$claveSII=$rut['ci11_sii'];
    				}
    				    				
    				
    				//obtiene impuesto unico ingresado por el administrador de remuneraciones
    				$valTasaImpuestoUnico=$cobroMapper->obtenerImpuestoUnico($rut['ci04_idrrut'],$fechaRegistro);
    				$valTasaImpuestoUnico2=$valTasaImpuestoUnico;
    			 		    				
    				if($rut['ci04_numerosociedad']=='PN')
    				{
    					$valColor="style='color: black;'";
    					$valTasaImpuestoUnico='0';
    				}
    				elseif($rut['ci04_previred']=='1' && $valTasaImpuestoUnico=='')
    				{
    					$valColor="style='color: red;'";
    					$valTasaImpuestoUnico='0';
    				}
    				elseif ($rut['ci04_previred']=='1' && $valTasaImpuestoUnico!='')
    				{
    					$valColor="style='color: #66CD00;'";
    				}
    				elseif($rut['ci04_previred']=='2')
    				{
    					$valColor="style='color: black;'";
    					$valTasaImpuestoUnico='0';
    				}    				    			
    			
    				$valBoleteoTercero='';
    				
    				if($idPerfil=='7' && $rut['ci04_previred']=='1' && $rut['ci04_numerosociedad']!='PN')
    				{
    					if($valTasaImpuestoUnico=='')
    					{
    						$valTasaImpuestoUnico='';
    						$impuestoUnico='';
    					}
    					else
    					{
    						if($valTasaImpuestoUnico2!='')
    						{
    							$valTasaImpuestoUnico=$valTasaImpuestoUnico2;
    							$impuestoUnico='disabled';
    						}
    						else
    						{
    							$valTasaImpuestoUnico='';
    							$impuestoUnico='';
    						}
    					}
    				}
    			}
    			else
    			{    	
    				 
    				$claveSII='******';
    				
    				$impuestoUnico='';
    				$iva='disabled';
    				$remanate='disabled';
    				$ingresoSinRetenciones='disabled';
    				$ingresoConRetenciones='disabled';
    				$IngresoSociedad='disabled';
    				$retiroSociedad='disabled';
    				$boleteoRetTerceros='disabled';
    				$valIva='0';
    				$valRemanate='0';    				
    				
    				$valTasaImpuestoUnico='';
    				$valBoleteoTercero='0';
    				
    				if($rut['ci04_numerosociedad']=='PN')
    				{
    					$impuestoUnico='disabled';
    					$valTasaImpuestoUnico='0';
    				}
    				
    				$select2='<select disabled style="width:130px;" class="form-control formPago"><option value="">Seleccione</option></select>';
    			}        			
    			
    			if($this->_rol=='4' && $tipoCobro='1')
    			{
    				//si ingresa como administrativo de remuneraciones, 
    				//ocupa el siguiente método para validar si el valor ya fue ingresado 
    				$validacionListado=$cobroMapper->verificaImpuestoUnicoIngresado($rut['ci04_idrrut'],$fechaRegistro);
    			}
    			else
    			{
    				$validacionListado=$cobroMapper->existeCobroF29MesAnio($rut['ci04_idrrut'],$fechaRegistro);
    			}   
    			
    			
    			if(!$validacionListado)
    			{    				
    				$this->view->resultados='';
    			
    			$tabla.="<tr>
									<td $valColor>".$rut['ci04_idrrut']."</td>
									<td $valColor>".$rut['ci03_nombre']."</td>
									<td $valColor>".$rut['ci04_rut']."</td>
									<td $valColor>".$rut['ci04_numerosociedad']."</td>
									<td $valColor>".$rut['ci04_razonsocial']."</td>
									<td $valColor>".$claveSII."</td>
									<td $valColor>".$rut['ci40_tiposociedad']."</td>									
										
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valIngresoSinRetenciones' class='form-control montoF29' $ingresoSinRetenciones>
	    										</div>
    										</td>
    	
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valIngresoConRetenciones' class='form-control montoF29' $ingresoConRetenciones>
	    										</div>
    										</td>
    										
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valIngresoSociedad' class='form-control montoF29' $IngresoSociedad>
	    										</div>
    										</td>
    										
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valRetiroSociedad' class='form-control montoF29' $retiroSociedad>
	    										</div>
    										</td>
    			
    										<td>$controltasa</td>  										
    										    										
    										<td>$</td>
    	
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valBoleteoTercero' class='form-control montoF29' $boleteoRetTerceros>
	    										</div>
    										</td>
    	
    										<td>$</td>
    	
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valTasaImpuestoUnico' class='form-control montoF29' $impuestoUnico>
	    										</div>
    										</td>
    											
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valIva' class='form-control montoF29' $iva>
	    										</div>
    										</td>
    											
    										<td>
	    										<div class='input-group' style='width: 130px;'>
	    											<span class='input-group-addon'><i class='fa fa-dollar'></i></span>
	    											<input type='text' value='$valRemanate' class='form-control montoF29' $remanate>
	    										</div>
    										</td>
    											
    										<td>$</td>
    	
    										<td>$select2</td>
    											
    										</tr>";
    			}
    			
    			$iva='';
    			$remanate='';
    			
    		endforeach;
    		 
    		$tabla.="
							</tbody>
					 </table></div>";
    	
    		$this->view->tablaf29 =$tabla;
    	}   	
    }
           
    private function obtenerListadoCobrosRenta($anioRegistro)
    {
    	$rutMapper=new Application_Model_RutMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    	 
    	$tabla="";
        	
    	$select="";
    	$select2="";    	
    	
    	$datosRut=array();
    	
    	if(isset($_REQUEST['perfil']) &&
    	   isset($_REQUEST['tipoCobroSelect'])&&
    	   isset($_REQUEST['ejecutivoSelect'])&&
    	   isset($_REQUEST['nombreClienteSelect'])&&
    	   isset($_REQUEST['rutCobroMasivoSelect'])
    	  )
    	{
    		$data=array(
    		
    				"perfil"=>$_REQUEST['perfil'],
    				"tipocobro"=>$_REQUEST['tipoCobroSelect'],
    				"idejecutivo"=>$_REQUEST['ejecutivoSelect'],
    				"idCliente"=>$_REQUEST['nombreClienteSelect'],
    				"idRut"=>$_REQUEST['rutCobroMasivoSelect'],
    		);
    		
    		$select=$this->formaPago($cobroMapper,'');    
    	
    		$resultados=$rutMapper->obtieneRutCobroRenta($data);
    		$this->view->resultados='1';  		
    		
    			$tabla.="<div style='width: auto;height: 500px;overflow: scroll;'>
    						<div class='form-group' id='resultadosRenta'>
								<table class='table table-striped table-bordered nowrap'
									ellspacing='0' width='100%' id='tableIngresoCobroMasivoRenta'>
									<thead>
										<tr>
											<th>ID</th>
											<th>Cliente</th>
											<th>Contribuyente</th>
											<th>Clave Sii</th>
											<th>N&deg; Soc</th>
											<th>Raz&oacute;n Social Contribuyente</th>
											<th>Monto Renta</th>
											<th>Forma Pago</th>
										</tr>
									</thead>
									<tbody>";   			
    			
    			foreach ($resultados as $i => $rut):   
    			
	    			if($cobroMapper->verificaRutCobroMasivo($rut['ci04_idrrut'],'2'))
	    			{
	    				$idFormaPago=$cobroMapper->obtieneUltimaFormaPago($rut['ci04_idrrut'],'2','','2');
	    				$select2=$this->formaPago($cobroMapper,$idFormaPago);
	    			}
	    			else
	    			{
	    				$select2=$select;
	    			}
    			
	    			if(!$cobroMapper->existeCobroRentaAnio($rut['ci04_idrrut'],$anioRegistro,'1'))
	    			{       		
	    				
	    				$this->view->resultados='';
	    				
    			$tabla.="
    									<tr>
											<td>".$rut['ci04_idrrut']."</td>
    										<td>".$rut['ci03_nombre']."</td>
								            <td>".$rut['ci04_rut']."</td>
    										<td>".$rut['ci11_sii']."</td>
											<td>".$rut['ci04_numerosociedad']."</td>
    										<td>".$rut['ci04_razonsocial']."</td>
											<td>
    											<div class='input-group' style='width:150px;'>
    												<span class='input-group-addon'>
    													<i class='fa fa-dollar'></i>
    												</span>
    												<input type='text' name='' id='montoRenta$i' class='form-control montoRenta'>
    											</div>
    										</td>
									        <td>$select2</td>
								      	</tr>";
	    			}
    			endforeach;    			
    			
	    		$tabla.="
									</tbody>
								</table>
							</div>
    					</div>";
    			 
    	
	    		$this->view->tablaRenta =$tabla;
    	}    	
    }
       
    private function obtenerListadoCobrosPrevired($fechaRegistro)
    {
    	$rutMapper=new Application_Model_RutMapper();
    	$cobroMapper=new Application_Model_CobroMapper();
    	
    	$tabla=""; 
    	$select="";
    	$select2="";  
    	
    	if(isset($_REQUEST['perfil']) &&
    	   isset($_REQUEST['tipoCobroSelect'])&&
    	   isset($_REQUEST['ejecutivoSelect'])&&
    	   isset($_REQUEST['nombreClienteSelect'])&&
    	   isset($_REQUEST['rutCobroMasivoSelect']))
    	{    		
    		$data=array(	
    				"perfil"=>$_REQUEST['perfil'],
    				"tipocobro"=>$_REQUEST['tipoCobroSelect'],
    				"idejecutivo"=>$_REQUEST['ejecutivoSelect'],
    				"idCliente"=>$_REQUEST['nombreClienteSelect'],
    				"idRut"=>$_REQUEST['rutCobroMasivoSelect'],
    		);
    		
    		$select=$this->formaPago($cobroMapper,'');
    		$select2=$select;
    		
    		$resultados=$rutMapper->obtieneRutCobroPrevired($data);
    		$this->view->resultados='1';
    		
	    	$tabla .="<div style='width: auto;height: 500px;overflow: scroll;'>
	    				<table class='table table-striped table-bordered nowrap'
								cellspacing='0' width='100%' id='tableIngresoCobroMasivoPrevired'>
								<thead>
									<tr>
										<th>ID</th>
										<th>Cliente</th>
										<th>Contribuyente</th>
										<th>Clave Previred</th>
										<th>N&deg; Soc</th>
										<th>Raz&oacute;n Social Contribuyente</th>
										<th>Concepto</th>
										<th>Monto</th>
										<th>Forma Pago</th>
									</tr>
								</thead>
								<tbody>";
	    	
	    	foreach ($resultados as $i => $rut):	    	
	    	
			    	if($cobroMapper->verificaRutCobroMasivo($rut['ci04_idrrut'],'3'))
			    	{
			    		$idFormaPago=$cobroMapper->obtieneUltimaFormaPago($rut['ci04_idrrut'],'3',$rut['ci_nombreconcepto'],'1');
			    		$select2=$this->formaPago($cobroMapper,$idFormaPago);
			    	}
			    	else
			    	{
			    		$select2=$select;
			    	}
			    	 
			    		if(!$cobroMapper->existeCobroPreviredMesAnio($rut['ci04_idrrut'],$rut['ci_nombreconcepto'],$fechaRegistro)):
			    		
			    			$this->view->resultados='';
			    		
	    					$tabla .="
									<tr>
										<td>".$rut['ci04_idrrut']."</td>
										<td>".$rut['ci03_nombre']."</td>
										<td>".$rut['ci04_rut']."</td>
										<td>".$rut['ci11_previred']."</td>
										<td>".$rut['ci04_numerosociedad']."</td>
										<td>".$rut['ci04_razonsocial']."</td>
										<td>".$rut['ci_nombreconcepto']."</td>
										<td>
	    									<div class='input-group' style='width:150px;'>
	    										<span class='input-group-addon'>
	    											<i class='fa fa-dollar'></i>
	    										</span>
	    										<input type='text' name='' id='' class='form-control montoPrevired'>
	    									</div>
	    								</td>
										<td>$select2</td>
									</tr>";	
			    		endif;
	    	endforeach;
	    	
	    	$tabla .="
								</tbody>
							</table>
	    				</div>";
	    	
	    	$this->view->tablaPrevired =$tabla;    	
    	}
    }
     
    private function formaPago($cobroMapper,$idFormaPago)
    {
    	$select='';
    	
    	$disabledAdminRemuneraciones='';
    	
    	if($this->_rol=='4' && $_REQUEST['tipoCobroSelect']=='1')
    	{
    		$disabledAdminRemuneraciones='disabled';
    	}
    	
    	
    	
    	$select.='<select style="width:130px;" class="form-control formPago" '.$disabledAdminRemuneraciones.'><option value="">Seleccione</option>';
    	foreach ($cobroMapper->obtenerFormasDePago() as $i => $formas):
    	
    		if($idFormaPago==$formas['ci35_idformapago'])
    		{
    			$select.='<option value="'.$formas['ci35_idformapago'].'" selected>'.$formas['ci35_tipopago'].'</option>';
    		}
    		else
    		{
    			$select.='<option value="'.$formas['ci35_idformapago'].'">'.$formas['ci35_tipopago'].'</option>';
    		}
    	 
    	endforeach;
    	 
    	$select.='</select>';
    	
    	
    	return  $select;
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
    
    public function obtenerestadosAction() 
    {    
    	$cobro= new Application_Model_CobroMapper();
    	 
    	$estados =array();
    	 
    	foreach ($cobro->obtenerEstadosCobro() as $i => $estado)
    	{    		 
    
    		$data =array(
    				    'id_estado'=>$estado['ci53_idestadocobro'],
	    				'nombre_estado' => $estado['ci53_nombreestado'],
    					'descripcion' => $estado['ci53_descripcion']
    		);
    		 
    		$estados[]=$data;
    	}
    	 
    	$arreglo =array(
    			"data" => $estados,
    	);
    	 
    	return $this->_helper->json->sendJson($arreglo);
    }
	   
    //registro cobros masivos
    
    public function registracobrof29Action()
    {
    	$cobroMasivoMapper=new Application_Model_CobroMapper();
		$response = array ();
		$registraDatosMultiples = array();
		
		if (! empty ( $_REQUEST ['dataF29'] )) {
			$data = json_decode ( $_REQUEST ['dataF29'], true );
			
			for($i = 0; count ( $data ) > $i; $i ++) 
			{
				if ($data [$i] ['primCat'] == '1') 
				{
					$tasaPrim = $data [$i] ['idTasa'];
					$tasa = '1';
					$retencion = '1';
				} 
				else
				{
					$tasaPrim = '';
					
					if ($data [$i] ['sociedad'] == '14 BIS') {
						$retencion = $data [$i] ['idTasa'];
						$tasa = '1';
					} 
					else
					{
						$tasa = $data [$i] ['idTasa'];
						$retencion = '1';
					}
				}	
				
				$dato = array (
						"idRut" => $data [$i] ['id'],
						"idConcepto" => '1',
						"idFormaPago" => $data [$i] ['idForPago'],
						"compensacion"=>'1',
    					"tasa"=>$tasa,     					
    					"retencion"=>$retencion,   					
    					"tasaprimeracat"=>$tasaPrim,    					
    					"estadocobro"=>'1',
    					"ingsinretencion"=>$data[$i]['ingSRet'],
    					"ingconretencion"=>$data[$i]['ingCRet'],
    					"ingsociedad"=>$data[$i]['IngSoc'],
    					"retsociedad"=>$data[$i]['RetSoc'],
    					"bolretterceros"=>$data[$i]['BolRet'],
    					"impuestounico"=>$data[$i]['ImpUnic'],
    					"ivapago"=>$data[$i]['IvaPa'],
    					"remanente"=>$data[$i]['Rema'],
    					"monto"=>$data[$i]['monto'],
    					"autorizapago"=>'2',
    					"ppmnetdet"=>$data[$i]['ppmnetdet'],
    					"ret"=>$data[$i]['retencion'],
						"conceptoPrevired"=>'',
						"fechaRegistro"=>$data[$i]['fechaCobro']
    			); 			
				
				if($this->_rol=='7')
				{
					if($cobroMasivoMapper->verificaIngresoImpuestoUnicoAdministrador($data[$i]['id']))
					{	
						if($cobroMasivoMapper->verificaExisteImpuestoUnico($data[$i]['id'],$data[$i]['fechaCobro']))
						{
							$res=$cobroMasivoMapper->registraCobroMasivo($dato);
						}
						else
						{
							$registraDatosMultiples[]=$dato;
						}						
					}
					elseif(!$cobroMasivoMapper->verificaIngresoImpuestoUnicoAdministrador($data [$i]['id']))
					{
						
						$res=$cobroMasivoMapper->registraCobroMasivo($dato);
					}
				}	
				else
				{
					$res=$cobroMasivoMapper->registraCobroMasivo($dato);
				}													
    		}    		
    		
    		if($this->_rol=='7' && count($registraDatosMultiples)>0)
    		{
    			$res=$cobroMasivoMapper->registraCobroMasivoImpuestoUnico($registraDatosMultiples);
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
    	
    	$arreglo=array(
    			"registro"=>$response
    	);
    	 
    	$this->_helper->json->sendJson($arreglo);
    }
    
    public function registracobrorentaAction()
    {    	
    	$cobroMasivoMapper=new Application_Model_CobroMapper();
        	
    	$response=array();
    	
    	if(!empty($_REQUEST['dataRenta']))
    	{        		
    		$data=json_decode($_REQUEST['dataRenta'],true);
    		
    		for($i=0 ; count($data) > $i ; $i++)
    		{    			
    			$dato=array(
    					"idRut"=>$data[$i]['idRut'],
    					"idConcepto"=>'2',
    					"idFormaPago"=>$data[$i]['idPago'],
    					"compensacion"=>'1',
    					"tasa"=>'1',
    					"retencion"=>'1',
    					"tasaprimeracat"=>'0',
    					"estadocobro"=>'1',
    					"ingsinretencion"=>'',
    					"ingconretencion"=>'',
    					"ingsociedad"=>'',
    					"retsociedad"=>'',
    					"bolretterceros"=>'',
    					"impuestounico"=>'',
    					"ivapago"=>'',
    					"remanente"=>'',
    					"monto"=>$data[$i]['monto'],
    					"autorizapago"=>'2',
    					"ppmnetdet"=>'',
    					"ret"=>'',
    					"conceptoPrevired"=>'',
    					"fechaRegistro"=>$data[$i]['fechaCobro'],
    					"registrauser" => '1',
    					"registraadmin" => '1'
    			);
    			
    			$res=$cobroMasivoMapper->registraCobroMasivo($dato);
    			
    			if($res)
    			{
    				$response[0]='1';
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
    	
    	$arreglo=array(
    			"registro"=>$response
    	);
    	
    	
    	
    	$this->_helper->json->sendJson($arreglo);
    }
    
    public function registracobropreviredAction()
    {
    	$cobroMasivoMapper=new Application_Model_CobroMapper();
    	 
    	$response=array();
    	 
    	if(!empty($_REQUEST['dataPrevired']))
    	{
    		$data=json_decode($_REQUEST['dataPrevired'],true);
    	
    		for($i=0 ; count($data) > $i ; $i++)
    		{
    			$dato=array(
    					"idRut"=>$data[$i]['idRut'],
    					"idConcepto"=>'3',
    					"idFormaPago"=>$data[$i]['idPago'],
    					"compensacion"=>'1',
    					"tasa"=>'1',
    					"retencion"=>'1',
    					"tasaprimeracat"=>'0',
    					"estadocobro"=>'1',
    					"ingsinretencion"=>'',
    					"ingconretencion"=>'',
    					"ingsociedad"=>'',
    					"retsociedad"=>'',
    					"bolretterceros"=>'',
    					"impuestounico"=>'',
    					"ivapago"=>'',
    					"remanente"=>'',
    					"monto"=>$data[$i]['monto'],
    					"autorizapago"=>'2',
    					"ppmnetdet"=>'',
    					"ret"=>'',
    					"conceptoPrevired"=>$data[$i]['conceptoPrevired'],
    					"fechaRegistro"=>$data[$i]['fechaCobro'],
    					"registrauser" => '1',
    					"registraadmin" => '1'
    			);
    			 
    			$res=$cobroMasivoMapper->registraCobroMasivo($dato);
    			 
    			if($res)
    			{
    				$response[0]='1';
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
    	 
    	$arreglo=array(
    			"registro"=>$response
    	);
    	 
    	$this->_helper->json->sendJson($arreglo);
    }    
        
    public function registraimpuestounicoAction()
    {
    	$cobroMasivoMapper=new Application_Model_CobroMapper();
    	$response = array ();
    	
    	if (! empty ( $_REQUEST ['dataF29'] )) 
    	{
    		$data = json_decode ( $_REQUEST ['dataF29'], true );
    			
    		for($i = 0; count ( $data ) > $i; $i ++)
    		{    			
    			$dato = array (
    					"idRut" => $data [$i] ['id'],    					
    					"monto"=>$data[$i]['monto'],
    					"fecha"=>$data[$i]['fechaCobro']
    			);
    	
    			$res=$cobroMasivoMapper->registraimpuestounico($dato);
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
    	 
    	$arreglo=array(
    			"registro"=>$response
    	);
    	
    	$this->_helper->json->sendJson($arreglo);
    }
    
    //modificar cobro masivos
    
	public function editarcobromasivoAction()
    {
    	$cobroMasivoMapper=new Application_Model_CobroMapper();
		$response = array ();
		$dataActualizacionMultiple=array();
		
		if (! empty ( $_REQUEST ['dataF29'] )) 
		{
			$data = json_decode ( $_REQUEST ['dataF29'], true );
			
			for($i = 0; count ( $data ) > $i; $i ++)
			{			
				if ($data [$i] ['primCat'] == '1') 
				{
					$tasaPrim = $data [$i] ['idTasa'];
					$tasa = '1';
					$retencion = '1';
				} 
				else 
				{
					$tasaPrim = '';
					
					if ($data [$i] ['sociedad'] == '14 BIS') 
					{
						$retencion = $data [$i] ['idTasa'];
						$tasa = '1';
					} 
					else
					{
						$tasa = $data [$i] ['idTasa'];
						$retencion = '1';
					}
				}
				
				$rutCobroMasivo=$cobroMasivoMapper->obtieneIdRutDeCobroMasivoF29($data[$i]['id'],$data[$i]['fechaCobro']);
				
				$dato = array (
						"idCobroMasivo" => $data [$i] ['id'],
						"idFormaPago" => $data [$i] ['idForPago'],
    					"tasa"=>$tasa,     					
    					"retencion"=>$retencion,   					
    					"tasaprimeracat"=>$tasaPrim,   
    					"ingsinretencion"=>$data[$i]['ingSRet'],
    					"ingconretencion"=>$data[$i]['ingCRet'],
    					"ingsociedad"=>$data[$i]['IngSoc'],
    					"retsociedad"=>$data[$i]['RetSoc'],
    					"bolretterceros"=>$data[$i]['BolRet'],
    					"impuestounico"=>$data[$i]['ImpUnic'],
    					"ivapago"=>$data[$i]['IvaPa'],
    					"remanente"=>$data[$i]['Rema'],
    					"monto"=>$data[$i]['monto'],
    					"ppmnetdet"=>$data[$i]['ppmnetdet'],
    					"ret"=>$data[$i]['retencion'],
						"fechaCobro"=>$data[$i]['fechaCobro'],
						"idRut" => $rutCobroMasivo,						
    			);				
				
				if($this->_rol=='4')
				{
					$dataActualizacionMultiple[]=$dato;
				}
				
				if($this->_rol=='7')
				{
					if($cobroMasivoMapper->verificaIngresoImpuestoUnicoAdministrador($rutCobroMasivo))
					{
						$dataActualizacionMultiple[]=$dato;
					}
				}
				
				if($this->_rol!='4')
				{
					if($this->_rol=='7')
					{
						if(!$cobroMasivoMapper->verificaIngresoImpuestoUnicoAdministrador($rutCobroMasivo))
						{
							$res=$cobroMasivoMapper->editaraCobroMasivo($dato);
						}
					}
					else
					{
						$res=$cobroMasivoMapper->editaraCobroMasivo($dato);
					}					
				}							
			}
			
			if($this->_rol=='4' || $this->_rol=='7')
			{
				if(count($dataActualizacionMultiple)>0)
				{
					$res=$cobroMasivoMapper->editarCobroMasivoImpuestoUnico($dataActualizacionMultiple);
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
    		$response[0]='3';
    	}
    	
    	$arreglo=array(
    			"registro"=>$response
    	);
    	 
    	$this->_helper->json->sendJson($arreglo);
    }

    public function editarcobromasivorentaAction()
    {
    	$cobroMasivoMapper=new Application_Model_CobroMapper();
    	$response = array ();
    	
    	if (! empty ( $_REQUEST ['dataRenta'] ))
    	{
    		$data = json_decode ( $_REQUEST ['dataRenta'], true );
    			
    		for($i = 0; count ( $data ) > $i; $i ++)
    		{
    			
    			$dato = array (
    					"idCobroMasivo" => $data [$i] ['idCobroMasivo'],
    					"idConcepto"=>'2',
    					"idFormaPago" => $data [$i] ['idPago'],    					
    					"tasa"=>'1',
    					"retencion"=>'1',
    					"tasaprimeracat"=>'0',
    					"estadocobro"=>'1',
    					"ingsinretencion"=> '',
    					"ingconretencion"=> '',
    					"ingsociedad"=> '',
    					"retsociedad"=> '',
    					"bolretterceros"=> '',
    					"impuestounico"=> '',
    					"ivapago"=> '',
    					"remanente"=> '',
    					"monto"=>$data[$i]['monto'],    					
    					"ppmnetdet"=> '',
    					"ret"=> '',
    					"fechaCobro"=>$data[$i]['fechaCobro']
    	
    			);    			
    			
    			$res=$cobroMasivoMapper->editaraCobroMasivo($dato);
    			
    			/*
    			if(!$cobroMasivoMapper->existeCobroRentaAnio($data [$i] ['idCobroMasivo'],$data[$i]['fechaCobro'],'2'))
    			{    	
    				   			
    			}
    			else
    			{
    				$res=true;
    			}
    			*/
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
    	 
    	$arreglo=array(
    			"registro"=>$response
    	);
    	
    	$this->_helper->json->sendJson($arreglo);
    }
    
    public function editarcobromasivopreviredAction()
    {
    	$cobroMasivoMapper=new Application_Model_CobroMapper();
    	$response = array ();
    	 
    	if (! empty ( $_REQUEST ['dataPrevired'] ))
    	{
    		$data = json_decode ( $_REQUEST ['dataPrevired'], true );
    		 
    		for($i = 0; count ( $data ) > $i; $i ++)
    		{
    			 
    			$dato = array (
    					"idCobroMasivo" => $data [$i] ['idCobroMasivo'],
    					"idConcepto" => '3',
    					"idFormaPago" => $data [$i] ['idPago'],
    					"tasa"=>'1',
    					"retencion"=>'1',
    					"tasaprimeracat"=>'0',
    					"estadocobro"=>'1',
    					"ingsinretencion"=> '',
    					"ingconretencion"=> '',
    					"ingsociedad"=> '',
    					"retsociedad"=> '',
    					"bolretterceros"=> '',
    					"impuestounico"=> '',
    					"ivapago"=> '',
    					"remanente"=> '',
    					"monto"=>$data[$i]['monto'],
    					"ppmnetdet"=> '',
    					"ret"=> '',
    					"fechaCobro"=>$data[$i]['fechaCobro']
    			);
    			
    			$res=$cobroMasivoMapper->editaraCobroMasivo($dato);
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
    	
    	$arreglo=array(
    			"registro"=>$response
    	);
    	 
    	$this->_helper->json->sendJson($arreglo);
    }
                  
    //Datos Email Cobro Masivos Vista Previa
    
    public function datosemailppmAction()
    {
    	$cobroMapper=new Application_Model_CobroMapper ();
    	    	
    	$datoEmailPPM=array();
    	
    	$total_ppm=0;
    	
    	$total_dep_angkor=0;
    	$total_pago_directo=0;
    	$total_pec=0;
    	$total_cheque=0;
    	
    	$total_saldo=0;
    	
    	if(!empty($_REQUEST['idCliente']))
    	{
    		//asignar fecha mes anterior.
    		
 			$fecha=$this->obtieneMesPPMSistema(); 		
    		
    		if(sizeof($cobroMapper->obtieneDatosEmailPPM($_REQUEST['idCliente'],$fecha))>0)
    		{
    			foreach ($cobroMapper->obtieneDatosEmailPPM($_REQUEST['idCliente'],$fecha) as $i => $cobro)
    			{
    				$total_ppm+=intval($cobro ['ci_montof29'])+intval($cobro ['ci_montoPrevired'])+intval($cobro ['ci_otros'])+intval($cobro ['ci_honorarios']);
    				
    				$total_dep_angkor+=(intval($cobro ['ci_depAngkor'])+intval($cobro ['ci_depAngkor_pendiente']));
    				$total_pago_directo+=(intval($cobro ['ci_pagodirecto'])+intval($cobro ['ci_pagodirecto_pendiente']));
    				$total_pec+=(intval($cobro ['ci_pec'])+intval($cobro ['ci_pec_pendiente']));
    				$total_cheque+=(intval($cobro ['ci_ordenpago'])+intval($cobro ['ci_ordenpago_pendiente']));   				
    				
    				$total_saldo=intval($total_dep_angkor)-intval($cobro ['ci_saldo']);
    				
    				$datos=array(

    						$datoPPM []['ci04_idrrut'] = $cobro ['ci04_idrrut'],
    						$datoPPM []['ci_razonsocial'] = $cobro ['ci_razonsocial'],
    						
    						$datoPPM []['ci_montof29'] = (number_format($cobro ['ci_montof29'],0,",",".")),
    						$datoPPM []['ci_montoPrevired'] = (number_format($cobro ['ci_montoPrevired'],0,",",".")),    							
    						$datoPPM []['ci_otros'] = (number_format($cobro ['ci_otros'],0,",",".")),
    						$datoPPM []['ci_honorarios'] = (number_format($cobro ['ci_honorarios'],0,",",".")),    						
    						
    						$datoPPM []['suma_contribuyente']=(number_format(intval($cobro ['ci_montof29'])+intval($cobro ['ci_montoPrevired'])+intval($cobro ['ci_otros'])+intval($cobro ['ci_honorarios']),0,",",".")),
    						$datoPPM []['total_ppm']=(number_format($total_ppm,0,",",".")),
    						
    						$datoPPM []['ci_depAngkor'] =(number_format( (intval($cobro ['ci_depAngkor'])+intval($cobro ['ci_depAngkor_pendiente'])),0,",",".")),    						
    						$datoPPM []['ci_pagodirecto'] = (number_format((intval($cobro ['ci_pagodirecto'])+intval($cobro ['ci_pagodirecto_pendiente'])),0,",",".")),    						
    						$datoPPM []['ci_pec'] = (number_format((intval($cobro ['ci_pec'])+intval($cobro ['ci_pec_pendiente'])),0,",",".")),    						
    						$datoPPM []['ci_ordenpago'] =(number_format( (intval($cobro ['ci_ordenpago'])+intval($cobro ['ci_ordenpago_pendiente'])),0,",",".")),
    						
    						$datoPPM []['ci_total_depAngkor'] =(number_format( $total_dep_angkor,0,",",".")),
    						$datoPPM []['ci_total_pago_directo'] =(number_format( $total_pago_directo,0,",",".")),
    						$datoPPM []['ci_total_pec'] =(number_format( $total_pec,0,",",".")),
    						$datoPPM []['ci_total_cheque'] =(number_format( $total_cheque,0,",",".")),    						
    						
    						$datoPPM []['ci_saldo'] = (number_format($cobro ['ci_saldo'],0,",",".")),
    						$datoPPM []['ci_total_saldo'] = (number_format($total_saldo,0,",","."))
    						
    				);
    				
    				$datoEmailPPM[]=$datos;
    			}
    		}
    		else
    		{
    			$datoEmailPPM=[];
    		}
    	}
    	
    	$arreglo=array(
    			"ppm"=>$datoEmailPPM
    	);
    
    	return $this->_helper->json->sendJson($arreglo);
    }

    public function datosemailrentaAction()
    {
    	$cobroMapper=new Application_Model_CobroMapper();
    	
    	$datosRenta=array();
    	
    	$total_depngkor=0;
    	$total_pagodirecto=0;
    	
    	if(!empty($_REQUEST['idCliente']))
    	{    		
    		foreach ($cobroMapper->obtieneDatosEmailRenta($_REQUEST['idCliente']) as $i => $renta)
    		{
    			
    			$total_depngkor+=intval($renta['ci_depangkor']);
    			$total_pagodirecto+=intval($renta['ci_pagodirecto']);
    			
    			$data=array(
    					
    					$datoRenta[]['ci04_razonsocial']=$renta['ci04_razonsocial'],
    					$datoRenta[]['ci07_monto']=(number_format($renta['ci07_monto'],0,",",".")),
    					
    					$datoRenta[]['ci_depangkor']=(number_format($renta['ci_depangkor'],0,",",".")),
    					$datoRenta[]['ci_pagodirecto']=(number_format($renta['ci_pagodirecto'],0,",",".")), 
    					
    					$datoRenta[]['ci_total_depangkor']=(number_format($total_depngkor,0,",",".")),
    					$datoRenta[]['ci_total_pagodirecto']=(number_format($total_pagodirecto,0,",",".")),
    			);
    			 
    			$datosRenta[]=$data;
    		}	
    	}
    	else
    	{
    		$datosRenta=['2'];
    	}
    	
    	$arreglo=array(
    		"renta"=>$datosRenta
    	);
    	
    	$this->_helper->json->sendJson($arreglo);
    }
        
    //Envio de Email
    
    public function envioemailppmAction()
    {    	
    	require_once ("../library/MisClases/dompdf-master/dompdf_config.inc.php");
    	require_once ("../library/MisClases/PHPMailer-master/class.phpmailer.php");
    	
    	$emailMapper=new Application_Model_EmailMapper();
    	$usuarioMapper= new Application_Model_MantenedorUsuarioMapper();
    	$cobroMapper=new Application_Model_CobroMapper(); 
    	$contactoMapper=new Application_Model_ContactoMapper();
    	
    	$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
									 
		$numeroMes = date("n");
		$mesActual = $meses[$numeroMes];
		
		if(date("j")<=20){
			$resta = $numeroMes-1;
										
			if($resta == 0)
			{
				$mesActual = 12;
			}else{
				$mesActual = $meses[$resta];
			}										
										
		}
    	
    	$response=array();
    	    	
    	if(!empty($_REQUEST['asunto'])&&
    	   !empty($_REQUEST['emails'])&&
    	   !empty($_REQUEST['idCliente'])&&
    	   !empty($_REQUEST['idUsuario']))
    	{
    		$email=explode(',',$_REQUEST['emails']);
    		$cuerpoEmail="";
    		
    		if(isset($_REQUEST['cuerpo'])=='')
    		{
    			$cuerpoEmail="-";
    		}elseif (isset($_REQUEST['cuerpo'])!=''){
    			$cuerpoEmail = $_REQUEST['cuerpo'];
    		}
    		
    		$datosEmail = array (
    				"textoCuerpo" => $cuerpoEmail,
    				"asunto" => $_REQUEST ['asunto'],
    				"idUsuario" => $_REQUEST['idUsuario'],
    				"idCliente" => $_REQUEST['idCliente']
    		);	
    			
    		$mipdf = new DOMPDF();
    		 
    		$mipdf->set_paper("A4", "portrait");
    		
    		$fecha=$this->obtieneMesPPMSistema();
    		
    		$mipdf->load_html($this->pdfPPM($fecha));
    		$mipdf->render();
    		 
    		$pdf=$mipdf->output();
    		 
    		$idPdf=time();
    		$name="Pdf_PPM"."-".$idPdf;
    		$nombrePdf=$name.".pdf";
    			 
    		$nombreDirectorio= $this->view->path_ppm;
    			 
    		$rutaGuardado=$nombreDirectorio.$nombrePdf;
    			 
    		if(file_put_contents($rutaGuardado,$pdf))
    		{   			
    			$res=$emailMapper->ingresarEmail($datosEmail);
    			$idBitacora=mysql_insert_id();
    			
    			if($res)
    			{    				
    				$response[0]='1';
    				
    				$emailMapper->registraDetalleEmailMasivo($_REQUEST['idCliente'],$idBitacora,'PPM',$nombrePdf);    				
    				
    				foreach ($usuarioMapper->datosUsuarioEmail($_REQUEST ['idUsuario']) as $i => $user)
    				{
    					$nombreUsuario=$user['lc01_nombreUsuario'];
    					$perfil=$user['lc02_nombrePerfil'];
    				}
    				
    		$ppm='	
    				<!---------------------Cuadro Resumen PPM----------------------------->
    				<div style="page-break-inside:avoid;">
    				
    				<table style="border-collapse: collapse;">
	    				<thead>
		    				<tr>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Contribuyente</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">F29</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Previred</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Otros</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Honorarios Angkor</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Total</th>
			    					
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;"></th>
			    					
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Dep. Angkor (1)</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Pago Directo Cliente (2)</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">PEC (3)</th>
			    				<th style="border: 1px solid black; padding: 5px; background-color: #039be5; color: white;">Cheque SS (4)</th>
		    				</tr>
	    				</thead>
	    				<tbody>';
    		
    			
				
				$suma_f29=0;
				$total_f29=0;
				
				$total_depAngkor=0;
				$total_pagoDirecto=0;
				$total_pec=0;
				$total_ordenPago=0;
				
				$saldo=0;
				$total_saldo=0;
				
				$msjDepAngkor="";
				$msjPagoDirecto="";
				$msjPEC="";
				$msjCheque="";				
				
				$rutConCobro=array();
				
				foreach ($cobroMapper->obtieneDatosEmailPPM($_REQUEST['idCliente'],$fecha) as $i => $cobro)
				{
					
						array_push($rutConCobro,$cobro['ci04_idrrut']);
					
						$suma_fila=intval($cobro['ci_montof29'])+intval($cobro['ci_montoPrevired'])+intval($cobro['ci_otros'])+intval($cobro['ci_honorarios']);
						$total_f29+=intval($suma_fila);
						
						
						$total_depAngkor+=(intval($cobro['ci_depAngkor'])+intval($cobro['ci_depAngkor_pendiente']));
						$total_pagoDirecto+=intval($cobro['ci_pagodirecto'])+intval($cobro['ci_pagodirecto_pendiente']);
						$total_pec+=intval($cobro['ci_pec'])+intval($cobro['ci_pec_pendiente']);
						$total_ordenPago+=intval($cobro['ci_ordenpago'])+intval($cobro['ci_ordenpago_pendiente']);
						
						$saldo=$cobro['ci_saldo'];
						
						$ppm .='	<tr>
    										<td style="border: 1px solid black; padding: 5px;">'.$cobro['ci_razonsocial'].'</td>
    												
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($cobro['ci_montof29'],0,",",".")).'</td>
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($cobro['ci_montoPrevired'],0,",",".")).'</td>
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($cobro['ci_otros'],0,",",".")).'</td>
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($cobro['ci_honorarios'],0,",",".")).'</td>
    												
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($suma_fila,0,",",".")).'</td>
    				
    										<td></td>
    											
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format((intval($cobro['ci_depAngkor'])+intval($cobro['ci_depAngkor_pendiente'])),0,",",".")).'</td>
    										<td style="border: 1px solid black; padding: 5px;">$ '.(number_format((intval($cobro['ci_pagodirecto'])+intval($cobro['ci_pagodirecto_pendiente'])),0,",",".")).'</td>
											<td style="border: 1px solid black; padding: 5px;">$ '.(number_format((intval($cobro['ci_pec'])+intval($cobro['ci_pec_pendiente'])),0,",",".")).'</td>    				
											<td style="border: 1px solid black; padding: 5px;">$ '.(number_format((intval($cobro['ci_ordenpago'])+intval($cobro['ci_ordenpago_pendiente'])),0,",",".")).'</td>
    								</tr>';
					}
					
					foreach ($cobroMapper->razonesSocialesClient($_REQUEST['idCliente']) as $i => $razon)
					{
						if(!in_array($razon['ci04_idrrut'],$rutConCobro))
						{
							$ppm .='<tr>
											<td style="border: 1px solid black; padding: 5px;">'.$razon['ci04_razonsocial'].'</td>
					
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
					
											<td style="border: 1px solid black; padding: 5px;"></td>
					
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
											<td style="border: 1px solid black; padding: 5px;">$ 0</td>
									    </tr>';
						}
					}
							
					$total_saldo=intval($total_depAngkor)-intval($saldo);
					
					
					
					$ppm .='		</tbody>
    								<tfoot>
    									<tr>
	    									<td style="border: 1px solid black; padding: 5px;" colspan="5" align="right"><strong>Total</strong></td>
	    									<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($total_f29,0,",",".")).'</td>
							
	    									<td ></td>
							
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_depAngkor,0,",",".")).'</strong></td>
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_pagoDirecto,0,",",".")).'</strong></td>
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_pec,0,",",".")).'</strong></td>
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_ordenPago,0,",",".")).'</strong></td>
    									</tr>	 
	    								<tr>
	    									<td colspan="10"></td>	
	    									<td colspan="10"></td>	
	    								</tr>';
					
										if(intval($saldo)>0):										
										$ppm .='<tr>
			    									<td style="border: 1px solid black; padding: 5px;" colspan="6"><strong>Saldo a Favor</strong></td>
			    									<td></td>
			    									<td style="border: 1px solid black; padding: 5px;">$ '.(number_format($saldo,0,",",".")).'</td>	
			    									<td style="border: 1px solid black; padding: 5px;">$ 0</td>	
			    									<td style="border: 1px solid black; padding: 5px;">$ 0</td>	
			    									<td style="border: 1px solid black; padding: 5px;">$ 0</td>	
			    								</tr>';	    								
	    								endif;
	    			$ppm.='					
	    								<tr>
	    									<td style="border: 1px solid black; padding: 5px;" colspan="6"><strong>Total</strong></td>	
	    									<td></td>		
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_saldo,0,",",".")).'</strong></td>	
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_pagoDirecto,0,",",".")).'</strong></td>
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_pec,0,",",".")).'</strong></td>
	    									<td style="border: 1px solid black; padding: 5px;"><strong>$ '.(number_format($total_ordenPago,0,",",".")).'</strong></td>	
	    								</tr>
    								</tfoot>
    							</table>
    						</div>';
				
					if(intval($total_depAngkor)>0)
					{
						$msjDepAngkor="(1) Favor depositar o transferir a ANGKOR CONSULTING S.A., RUT 78342990-K, Banco de CHILE, cuenta corriente 00-005-02565-06.";						
					}
					
					if(intval($total_pagoDirecto)>0)
					{
						$msjPagoDirecto="(2)  Listo para su pago directo en web del servicio.";						
					}
					
					if(intval($total_pec)>0)
					{
						$msjPEC="(3) Informado por PEC.";						
					}
					
					if(intval($total_ordenPago)>0)
					{
						$msjCheque="(4) Cheque a nombre del servicio.";
					}
				
					
    				foreach ($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente'] ) as $i => $datos)
    				{
    					$destinatario =$datos['ci09_email'];
    					$titulo = $_REQUEST['asunto'];
    						
    					$mensaje = '
	    				<html>
							<head>
								<title>Email de Cobro PPM</title>
							</head>
								<body>
    							
    								<p>Estimado(a) <strong>'.$datos['ci09_nombre'].'</strong></p>
    								<p>  Adjunto informaci&oacute;n PPM '.$mesActual.'</p>
    							
									'.$ppm.'							
    
    								<br>
    								
    								<p>'.$msjDepAngkor.'</p>
											
									<p>'.$msjPagoDirecto.'</p>
											
									<p>'.$msjPEC.'</p>
													
									<p>'.$msjCheque.'</p>
    				
									<strong id="cuerpoVistaEmailPPM"></strong>
    									'.utf8_decode($cuerpoEmail).'
									<p>
										Se despide atentamente <strong>'.$nombreUsuario.'</strong>,
										<strong>'.$perfil.'</strong> de ANGKOR.
									</p>
								</body>
						    </html>
    				
							';    				
    				
    					$mail=new PHPMailer();
    				
    					//Nombre del servidor
    					$mail->Host = "localhost";
    					//Email del remitente
    					$mail->From 	= $usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']);
    				
    					//Nombre del remitente
    					$mail->FromName = $usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']);
    					//Asunto
    					$mail->Subject 	= utf8_decode($titulo);
    					//Email y nombre destinatario
    					$mail->AddAddress($destinatario,"");
    					$mail->AddCC($usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']));
    					$mail->IsHTML(true);
    					$mail->MsgHTML($mensaje);
    				
    					$mail->AddAttachment($rutaGuardado, $name);
    				
    					$mail->Send();
    				}		
    			}
    			else
    			{
    				//problemas al registrar
    				$response[0]='2';
    			}    			
    		}  
    		else
    		{
    			//no creo el pdf
    			$response[0]='3';
    		}
    	}
    	else
    	{
    		//problema recepcion datos
    		$response[0]='4';
    	}
    	
    	$arreglo=array(
    		"registro"=>$response    			
    	);
    	
    	$this->_helper->json->sendJson($arreglo);
    }
    
    public function envioemailboleteoAction()
    {    	
    	require_once ("../library/MisClases/dompdf-master/dompdf_config.inc.php"); 
    	require_once ("../library/MisClases/PHPMailer-master/class.phpmailer.php");
    	
    	$emailMapper=new Application_Model_EmailMapper();
    	$usuarioMapper= new Application_Model_MantenedorUsuarioMapper();
    	$contactoMapper=new Application_Model_ContactoMapper();
    	    	    	
    	$meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    	
    	$response=array();
    	
    	if(!empty($_REQUEST['asunto'])&&
    	   !empty($_REQUEST['idCliente'])&&
    	   !empty($_REQUEST['idUsuario']))
    	{        		
    		
    		$cuerpoEmail="";
    		
    		if(isset($_REQUEST['cuerpo'])=='')
    		{
    			$cuerpoEmail="-";
    		}elseif (isset($_REQUEST['cuerpo'])!=''){
    			$cuerpoEmail = $_REQUEST['cuerpo'];
    		}
    		
    		
    		
    		
    		$datosEmail = array (
    				"textoCuerpo" => $cuerpoEmail,
    				"asunto" => $_REQUEST ['asunto'],
    				"idUsuario" => $_REQUEST['idUsuario'],
    				"idCliente" => $_REQUEST['idCliente']
    		); 
    		
    		$idPdf=time();
    		$name="Pdf_Boleteo"."-".$idPdf;
    		$nombrePdf=$name.".pdf";
    		
    		$nombreDirectorio= $this->view->path_pdfboleteo;
    		
    		$rutaGuardado=$nombreDirectorio.$nombrePdf;
    		
    		$mipdf = new DOMPDF();
    		
    		$mipdf->set_paper("A4", "portrait");
    		$mipdf->load_html($this->pdfBoleteo());
    		$mipdf->render();
    		
    		$pdf=$mipdf->output();    		
    		
    		if(file_put_contents($rutaGuardado,$pdf))
    		{    
    			$res=$emailMapper->ingresarEmail($datosEmail);
    			$idBitacora=mysql_insert_id();
    			
    			if($res)
    			{
    				$response[0]='1';
    				 
    				$emailMapper->registraDetalleEmailMasivo($_REQUEST['idCliente'],$idBitacora,'Boleteo',$nombrePdf);
    				 
    				foreach ($usuarioMapper->datosUsuarioEmail($_REQUEST ['idUsuario']) as $i => $user)
    				{
    					$nombreUsuario=$user['lc01_nombreUsuario'];
    					$perfil=$user['lc02_nombrePerfil'];
    				}
    				 
    				$html="";
    				 
    				foreach ($contactoMapper->obtieneEmailAsociado($_REQUEST ['idCliente'] ) as $i => $datos)
    				{
    					$destinatario =$datos['ci09_email'];
    					$titulo = $_REQUEST['asunto'];
    						
    					$mensaje = '<html>
								<head>
									<title>Email de Boleteo</title>    							
								</head>
									<body>
    
    									<p>Estimado(a) <strong>'.$datos['ci09_nombre'].'</strong> </p>
    			
    									<p>Adjuntamos reporte acumulado de boleteo anual por contribuyente.</p>
    			
    									'.utf8_decode($cuerpoEmail).'		
    											
    									<p>Saludos</p>
    
    									<p>Se despide atentamente <strong>'.$nombreUsuario.', '.$perfil.'</strong> de Angkor.</p>
									</body>
							    </html>
								';
    					
    					$mail=new PHPMailer();
    					
    					//Nombre del servidor
    					$mail->Host = "localhost";
    					//Email del remitente
    					$mail->From 	= $usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']);
    					
    					//Nombre del remitente
    					$mail->FromName = $usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']);
    					//Asunto
    					$mail->Subject 	= utf8_decode($titulo);
    					//Email y nombre destinatario
    					$mail->AddAddress($destinatario,"");
    					$mail->AddCC($usuarioMapper->obtenerEmailUsuario($_REQUEST['idUsuario']));
    					$mail->IsHTML(true);
    					$mail->MsgHTML($mensaje);
    					
    					$mail->AddAttachment($rutaGuardado, $name);
    					
    					$mail->Send();
    				}
    			}
    			else
    			{
    				//no registro
    				$response[0]='2';
    			}
    		}
    		else
    		{
    			//error no crea pdf en servidor
    			$response[0]='3';
    		}
    	}
    	else
    	{
    		//problemas recepcion de datos
    		$response[0]='4';
    	}
    	
    	$arreglo=array(
    			"registro"=>$response
    	);
    	    	
    	
    	return $this->_helper->json->sendJson($arreglo);
    }

    //Genera PDF invidual para descarga inmediata
	
    public function generapdfclienteboleteoAction()
    {   	
    	require_once ("../library/MisClases/dompdf-master/dompdf_config.inc.php");
    	   	
    	$mipdf = new DOMPDF ();
		$mipdf->set_paper ( "A4", "portrait" );
		$mipdf->load_html ( $this->pdfBoleteo() );
		$mipdf->render ();
		$mipdf->stream ( "Boleteo" . time () . ".pdf", array (
				"Attachment" => false 
		) );
		exit();
    }

	public function generapdfclienteppmAction()
	{	
		require_once ("../library/MisClases/dompdf-master/dompdf_config.inc.php");
		
		ob_start();
		
		$fecha=$_REQUEST['fecha'];
		
		$mipdf = new DOMPDF ();
		$mipdf->set_paper ( "A4", "portrait" );
		$mipdf->load_html ( $this->pdfPPM($fecha) );
		$mipdf->render ();
		$mipdf->stream ( "Cobro_PPM" . time () . ".pdf", array (
				"Attachment" => false
		) );
		exit();	
	}

	//Genera PDF masivo	
	
	public function generapdfclienteboleteomasivoAction()
	{
		require_once ("../library/MisClases/dompdf-master/dompdf_config.inc.php");
			
		$mipdf = new DOMPDF ();
		$mipdf->set_paper ( "A4", "portrait" );
		$mipdf->load_html ( $this->pdfMasivoBoleteo() );
		$mipdf->render ();
		$mipdf->stream ( "Boleteo_Masivo" . time () . ".pdf", array (
				"Attachment" => false
		) );
		exit();
	}
	
	public function generapdfclienteppmmasivoAction()
	{
		require_once ("../library/MisClases/dompdf-master/dompdf_config.inc.php");
		
		ob_start();
		
		$mipdf = new DOMPDF ();
		$mipdf->set_paper ( "A4", "portrait" );
		$mipdf->load_html ( $this->pdfMasivoPPM() );
		$mipdf->render ();
		$mipdf->stream ( "Cobro_PPM_Masivo" . time () . ".pdf", array (
				"Attachment" => false
		) );
		exit();
	}	
	
	/*************Verifica Datos***************/
	
	public function verificainfodesacargapdfAction()
	{
		$cobroMapper=new Application_Model_CobroMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['idCliente']))
		{
			$response[0]='2';			
			
			if($cobroMapper->verificaInfoDescargaPDFCliente($_REQUEST['idCliente'],$this->obtieneMesPPMSistema()))
			{
				$response[0]='1';
			}			
		}		
		return $this->_helper->json->sendJson($response);
	}
	
	//para verificar que el rut tenga metas de boleteo en el año seleccionados
	public function verificametabolteoanioactualclienteAction()
	{
		$rutMapper=new Application_Model_RutMapper();
		$response=array();
		
		if(!empty($_REQUEST['idCliente'])&&!empty($_REQUEST['anio']))
		{			
			$response[0]='2';
			
			if(sizeof($rutMapper->metaBoleteoRutByCliente($_REQUEST['idCliente'],$_REQUEST['anio']))>0)
			{
				$response[0]='1';
			}
		}
		
		return $this->_helper->json->sendJson($response);
	}
	
	public function verificadatosmasivosAction()
	{		
		$cobroMapper=new Application_Model_CobroMapper();
		
		$response=array();
		
		if(!empty($_REQUEST['fecha'])&&
		   !empty($_REQUEST['origen']))
		{			
			$response[0]='2';
			
			if($cobroMapper->verificaDatosMasivos($_REQUEST['fecha'],'1'))
			{
				$response[0]='1';
			}				
		}
		
		return $this->_helper->json->sendJson($response);
	}
	
	//funcion para crear pdf
	
	private function pdfPPM($fecha)
	{
		$rutMapper=new Application_Model_RutMapper();
		$cobroMapper=new Application_Model_CobroMapper();
					
		$conceptoPrevired=array("Empresarial","Independiente","Nana","Otros","Socios","Trabajadores");
		
		$content='';
		
		if(!empty($_REQUEST['idCliente'])&&
		   !empty($_REQUEST['nombreCliente']))
		{				
			
			$content .= '<html>
			    		    <head>
			    				<title>Detalle declaraci&oacute;n tesorer&iacute;a general de la rep&uacute;blica</title>
	    						<style>
					
			    					table {
									  width: 100%;
			    					  heigth: auto;
									  border: 1px solid #000;
			    					  border-collapse: collapse;
									  font:75%;
									}
		
			    					th{
			    						border: 0.5px solid black;
			    						padding: 4px;
			    						background-color: #039be5;
			    						color: white;
			    					}					
								
			    					td{
			    						border: 0.5px solid black;
			    						padding: 3px;
			    					}
		
			    					.title{
			    						background-color: white;
			    						color: black;
			    					}
		
			    					.result{
			    						background-color: #4db6ac;
			    						color: white;
			    					}
			    				</style>
			    			</head>
			    		<body>
								
						<div style="page-break-inside:avoid;">
						<label><strong>Cliente:</strong> '.$_REQUEST['nombreCliente'].'</label>
									<table>
										<thead>
											<tr>
												<th style="width:100px;">Contribuyente</th>
												<th>F29</th>
												<th>Previred</th>
												<th>Otros</th>
												<th>Honorarios Angkor</th>
												<th>Total</th>
					
												<th style="background-color: white;width:1px;"></th>
					
												<th>Dep. Angkor</th>
												<th>Pago Directo Cliente</th>
												<th>PEC</th>
												<th>Cheque SS</th>
											</tr>
										</thead>
										<tbody>';
						
				$sumtaTotal=0;
				$total=0;				
				$sumDepAngkor=0;
				$sumPagoDirecto=0;
				$sumPec=0;
				$sumOrdenPago=0;
				
				foreach ($cobroMapper->obtieneDatosEmailPPM($_REQUEST['idCliente'],$fecha) as $i => $cobro)
				{ 						
					$total=intval($cobro['ci_montof29'])+intval($cobro['ci_montoPrevired'])+intval($cobro['ci_otros'])+intval($cobro['ci_honorarios']);
					$sumtaTotal+=$total;
					
					$sumDepAngkor+=(intval($cobro['ci_depAngkor']) + intval($cobro['ci_depAngkor_pendiente']));
					$sumPagoDirecto+=(intval($cobro['ci_pagodirecto']) + intval($cobro['ci_pagodirecto_pendiente']));
					$sumPec+=(intval($cobro['ci_pec']) + intval($cobro['ci_pec_pendiente']));
					$sumOrdenPago+=(intval($cobro['ci_ordenpago']) + intval($cobro['ci_ordenpago_pendiente']));
					
						$content .='	<tr>
											<td>'.$cobro['ci_razonsocial'].'</td>
													
											<td>$ '.(number_format($cobro['ci_montof29'],0,",",".")).'</td>													
											<td>$ '.(number_format($cobro['ci_montoPrevired'],0,",",".")).'</td>
											<td>$ '.(number_format($cobro['ci_otros'],0,",",".")).'</td>
											<td>$ '.(number_format($cobro['ci_honorarios'],0,",",".")).'</td>
											<td>$ '.(number_format($total,0,",",".")).'</td>
												
											<td></td>
											
											<td>$ '.(number_format((intval($cobro['ci_depAngkor'])+ intval($cobro['ci_depAngkor_pendiente'])),0,",",".")).'</td>
											<td>$ '.(number_format(intval($cobro['ci_pagodirecto'] ),0,",",".")).'</td>	
											<td>$ '.(number_format(intval($cobro['ci_pec'] ),0,",",".")).'</td>						
											<td>$ '.(number_format(intval($cobro['ci_ordenpago']),0,",",".")).'</td>
								    </tr>';						
				}				
		
					$content .='	</tbody>
									<tfoot>
										<tr>
											<td colspan="5" align="right"><strong>Total</strong></td>
											<td><strong>$ '.(number_format($sumtaTotal,0,",",".")).'</strong></td>
											<td></td>';
										
					$content .='			<td><strong>$ '.(number_format($sumDepAngkor,0,",",".")).'</strong></td>
											<td><strong>$ '.(number_format($sumPagoDirecto,0,",",".")).'</strong></td>
											<td><strong>$ '.(number_format($sumPec,0,",",".")).'</strong></td>
											<td><strong>$ '.(number_format($sumOrdenPago,0,",",".")).'</strong></td>';
					
					$content .='		</tr>
									</tfoot>
								</table>								
								</div>							
								<br>';
					
					$content .='<h3>Detalle declaraci&oacute;n tesorer&iacute;a general de la rep&uacute;blica (F29), mes de '.$this->mesPPM($fecha).'</h3>
								<hr>';
					
				
			//Cuadro Persona Natural		
			$ingSinRetencion=0;			
				
			foreach ($cobroMapper->obtieneResumenPPM($_REQUEST['idCliente'],'1','0',$fecha) as $i => $ppm)
			{					
				if($ppm['ci07_ingsinretencion']!='')
				{
					$ingSinRetencion=$ppm['ci07_ingsinretencion'];
				}
				
				if($ppm['ci07_ingconretencion']!='')
				{
					$ppm['ci07_ingconretencion']=(number_format($ppm['ci07_ingconretencion'],0,",","."));
				}
				else
				{
					$ppm['ci07_ingconretencion']='0';
				}
					
				$content .='<div style="page-break-inside:avoid;">
								<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (PN)</label>
		
									<table>
										<thead>
											<tr>
												<th></th>
												<th>VALOR</th>
												<th>IMPUESTO</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>INGRESOS SIN RETENCI&Oacute;N (Tasa '.$ppm['ci_impuesto'].'%)</td>
												<td>$ '.(number_format($ingSinRetencion,0,",",".")).'</td>
												<td>$ '.(number_format(((intval($ppm['ci07_ingsinretencion'])*floatval($ppm['ci_impuesto'])) / 100),0,",",".")).'</td>
											</tr>
											<tr>
												<td>INGRESOS CON RETENCI&Oacute;N</td>
												<td>$ '.$ppm['ci07_ingconretencion'].'</td>
												<td>-</td>
											</tr>
											<tr>
												<td>RETENCIONES A TERCEROS</td>
												<td>$ '.(number_format($ppm['ci07_retencion'],0,",",".")).'</td>
												<td>-</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
												<td>-</td>
												<td><strong>$ '.(number_format(((intval($ppm['ci07_ingsinretencion'])*intval($ppm['ci_impuesto'])) / 100),0,",",".")).'</strong></td>
											</tr>
										</tfoot>
									</table>
									<br>
								</div>';
			}
			
			//Cuadro Sociedad Primera Categoria
			
			foreach ($cobroMapper->obtieneResumenPPM($_REQUEST['idCliente'],'1','1',$fecha) as $i => $ppm)
			{						
					$ingresos=(intval($ppm['ci07_ingsociedad']) * floatval($ppm['ci_impuesto']) / 100);
					$retencion=((intval($ppm['ci07_bolretterceros']) * 10) / 100 );
					$impuestounico=intval($ppm['ci07_impuestounico']);
					
					$content .='<div style="page-break-inside:avoid;">
								<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (Soc. Primera Categoria)</label>
									<table>
										<thead>
											<tr>
												<th></th>
												<th>VALOR</th>
												<th>IMPUESTO</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>INGRESOS (Tasa '.$ppm['ci_impuesto'].'%)</td>
												<td>$ '.(number_format($ppm['ci07_ingsociedad'],0,",",".")).'</td>
												<td>$ '.(number_format($ingresos,0,",",".")).'</td>
											</tr>
											<tr>
												<td>RETENCIONES A TERCEROS</td>
												<td>$ '.(number_format($ppm['ci07_bolretterceros'],0,",",".")).'</td>
												<td>$ '.(number_format($retencion,0,",",".")).'</td>
											</tr>
											<tr>
												<td>IMPUESTO &Uacute;NICO</td>
												<td>-</td>
												<td>$ '.(number_format($impuestounico,0,",",".")).'</td>
											</tr>';
					
					//estas filas se muestra solo si en el mantenedor del rut esta seleccionada la opcion de IVA
					$impuestoIVA=0;
					
					if($ppm['ci04_iva']=='1')
					{						
						if($ppm['ci07_ivapago']!='')
						{
							$ppm['ci07_ivapago']=(number_format($ppm['ci07_ivapago'],0,",","."));
						}
						else
						{
							$ppm['ci07_ivapago']='0';
						}
						
						if($ppm['ci07_remanente']!='')
						{
							$ppm['ci07_remanente']=(number_format($ppm['ci07_remanente'],0,",","."));
						}
						else
						{
							$ppm['ci07_remanente']='0';
						}
							
						$content .='		<tr>
												<td>IVA A PAGO</td>
												<td>-</td>
												<td>$ '.$ppm['ci07_ivapago'].'</td>
											</tr>
											<tr>
												<td>REMANENTE IVA CRED MES SIGUIENTE</td>
												<td>$ '.$ppm['ci07_remanente'].'</td>
												<td>-</td>
											</tr>';
							
											$impuestoIVA=str_replace(".","",$ppm['ci07_ivapago']);
							
					}
					$content .='		</tbody>
										<tfoot>
											<tr>
												<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
												<td>-</td>
												<td><strong> $ '.(number_format(($ingresos + $retencion + $impuestounico + $impuestoIVA),0,",",".")).'</strong></td>
											</tr>
										</tfoot>
									</table>
									<br>
								</div>';
				}
				
				//Cuadro Sociedad Segunda Categoria		
				
				foreach ($cobroMapper->obtieneResumenPPM($_REQUEST['idCliente'],'1','2',$fecha) as $i => $ppm)
				{						
					$ingresoTasa=(intval($ppm['ci07_ingsinretencion'])*floatval($ppm['ci_impuesto'])/100);
					
					$content .='<div style="page-break-inside:avoid;">
									<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (Soc. Segunda Categor&iacute;a)</label>
									<table>
										<thead>
											<tr>
												<th></th>
												<th>VALOR</th>
												<th>IMPUESTO</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>INGRESOS SIN RETENCI&Oacute;N (Tasa '.$ppm['ci_impuesto'].'%)</td>
												<td>$ '.(number_format($ppm['ci07_ingsinretencion'],0,",",".")).'</td>
												<td>$ '.(number_format($ingresoTasa,0,",",".")).'</td>
											</tr>
											<tr>
												<td>INGRESOS CON RETENCI&Oacute;N</td>
												<td>$ '.(number_format($ppm['ci07_ingconretencion'],0,",",".")).'</td>
												<td>-</td>
											</tr>
											<tr>
												<td>RETENCIONES A TERCEROS</td>
												<td>$ '.(number_format($ppm['ci07_retencion'],0,",",".")).'</td>
												<td>-</td>
											</tr>
											<tr>
												<td>IMPUESTO &Uacute;NICO</td>
												<td>-</td>
												<td>$ '.(number_format($ppm['ci07_impuestounico'],0,",",".")).'</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
												<td></td>
												<td><strong>$ '.(number_format(($ingresoTasa+intval($ppm['ci07_impuestounico'])),0,",",".")).'</strong></td>
											</tr>
										</tfoot>
									</table>
								</div><br>';
				}
			
				//Cuadro Sociedad 14 BIS
			
				foreach ($cobroMapper->obtieneResumenPPM($_REQUEST['idCliente'],'1','3',$fecha) as $i => $ppm)
				{		
					$ingresoTasa= ((intval($ppm['ci07_retsociedad'])*floatval($ppm['ci_impuesto']) / 100));
											
					if($ppm['ci07_ingsociedad']!='')
					{
						$ppm['ci07_ingsociedad']=(number_format($ppm['ci07_ingsociedad'],0,",","."));
					}
					else
					{
						$ppm['ci07_ingsociedad']='0';
					}
					
					$content .='<div style="page-break-inside:avoid;">
										<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (Soc. 14 BIS)</label>
										<table>
											<thead>
												<tr>
													<th></th>
													<th>VALOR</th>
													<th>IMPUESTO</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>INGRESOS</td>
													<td>$ '.$ppm['ci07_ingsociedad'].'</td>
													<td>-</td>
												</tr>
												<tr>
													<td>RETIROS (Tasa '.$ppm['ci_impuesto'].'%)</td>
													<td>$ '.(number_format($ppm['ci07_retsociedad'],0,",",".")).'</td>
													<td>$ '.(number_format($ingresoTasa,0,",",".")).'</td>
												</tr>
												<tr>
													<td>RETENCIONES A TERCEROS</td>
													<td>$ '.(number_format($ppm['ci07_bolretterceros'],0,",",".")).'</td>
													<td>$ '.(number_format($ppm['ci07_retencion'],0,",",".")).'</td>
												</tr>
												<tr>
													<td>IMPUESTO &Uacute;NICO</td>
													<td>-</td>
													<td>$ '.(number_format($ppm['ci07_impuestounico'],0,",",".")).'</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
													<td>-</td>
													<td><strong>$ '.(number_format($ingresoTasa+intval($ppm['ci07_impuestounico'])+intval($ppm['ci07_retencion']),0,",",".")).'</strong></td>
												</tr>
											</tfoot>
										</table>
									</div><br>';
				}
				
				//cuadros informacion Previred
				
				$dataPrevired=$cobroMapper->obtieneDatosPrvired($_REQUEST['idCliente'],$fecha);
				
				if(count($dataPrevired)>0)
				{
				
					$content .='		<div style="page-break-inside:avoid;">
					
											<h3>Detalle Previred</h3>
											<hr>';
					
					foreach ($cobroMapper->obtieneRazonSocialPrevired($_REQUEST['idCliente']) as $i => $prev)
					{	
							$content .=' <label><strong>'.$prev['ci04_razonsocial'].'</strong></label>
							
											<table>
												<tbody>';
						
							foreach ($dataPrevired as $a => $previ)
							{
								
								if($prev['ci04_razonsocial']==$previ['ci04_razonsocial'])
								{
								
									$content.='<tr>
													<td>'.$previ['ci07_conceptoprevired'].'</td>
													<td>$ '.(number_format($previ['ci07_monto'],0,",",".")).'</td>
													<td>'.$previ['ci35_tipopago'].'</td>
										      </tr>';
								}
							}
							
							$content.='</tbody>
											</table>									
										<br>';
					}				
					
					$content .='</div>';
				}
				
					
				
				//Detalle Otros
				if(sizeof($cobroMapper->obtieneDetalleOtros($_REQUEST['idCliente'],$fecha))>0)
				{
					$content.= '<div style="page-break-inside:avoid;">
								<h3>Detalle Otros</h3>
								<hr>';
				
					foreach ($cobroMapper->obtieneRazonSocialOtros($_REQUEST['idCliente'],$fecha) as $i =>$razon)
					{
						$totalOtros=0;
								
							$content.='	<table>
												<thead>
													<tr>
														<th>Contribuyente</th>
														<th>Concepto</th>
														<th>Monto UF</th>
														<th>Monto Monetario</th>
														<th>Forma De pago</th>
													</tr>
												</thead>
												<tbody>';
								
							foreach ($cobroMapper->obtieneDetalleOtros($razon['ci03_idcliente'],$fecha) as $o =>$otros)
							{
								$uf="";
				
								if($otros['ci_montouf']!='')
								{
									$uf=$otros['ci_montouf'].' UF';
								}
				
								if($razon['ci04_razonsocial']==$otros['ci04_razonsocial'])
								{
									$totalOtros+=intval($otros['ci_monto']);
										
										$content.='<tr>
														<td>'.$razon['ci04_razonsocial'].'</td>
														<td>'.$otros['ci33_nombre'].' - '.$this->mesPPM($otros['ci_fecha']).'</td>
														<td>'.$uf.'</td>
														<td>$ '.(number_format($otros['ci_monto'],0,",",".")).'</td>
														<td>'.$otros['ci35_tipopago'].'</td>
												   </tr>';
								}
							}
				
								$content.='	</tbody>
												<tfoot>
				 									<tr>
				 										<td colspan="4" align="right"><strong>Total</strong></td>
				 										<td><strong>$ '.(number_format($totalOtros,0,",",".")).'</strong></td>
				 									</tr>
				 								</tfoot>
											</table>
					 					<br>';
					}
					
					$content.='</div>';
				}
				
				//Detalle Honorarios
				
				if(sizeof($cobroMapper->obtieneHonorarios($_REQUEST['idCliente'],$fecha))>0)
				{
					$content.='<div style="page-break-inside:avoid;">
											<h3>Detalle Honorarios de Angkor</h3>
											<hr>';
					//deta PRueba
					$totalHonorarios=0;
					foreach ($cobroMapper->obtieneRazonSocialHonorariosbyCliente($_REQUEST['idCliente'],$fecha) as $l =>$hon)
					{				
						$content.='
								  <table>
										<thead>
											<tr>
										    	<th>Contribuyente</th>
												<th>Concepto</th>
												<th>Monto UF Unit</th>
												<th>Monto Monetario Unit</th>
												<th>Monto Total</th>
											</tr>
										</thead>
										<tbody>';
					
						//foreach lista los datos en la tabla
						foreach ($cobroMapper->obtieneHonorarios($hon['ci03_idcliente'],$fecha) as $i => $honorario)
						{
							if($hon['ci04_razonsocial']==$honorario['ci04_razonsocial'])
							{
								$totalHonorarios+=intval($honorario['ci_monto']);
								
								$content.='	<tr>
												<td>'.$honorario['ci04_razonsocial'].'</td>
												<td>'.$honorario['ci33_nombre'].'</td>
												<td>'.$honorario['ci_montouf'].' UF</td>
												<td>$ '.(number_format($honorario['ci_monto'],0,",",".")).'</td>
												<td>$ '.(number_format($honorario['ci_monto'],0,",",".")).'</td>
											</tr>';
							}							
						}
					
						$content.='	 </tbody>
									 <tfoot>
									 	<tr>
									 		<td colspan="4" align="right"><strong>Total</strong></td>
											<td><strong>$ '.(number_format($totalHonorarios,0,",",".")).'</strong></td>
										</tr>
									 </tfoot>
								</table>
								<br>
										';
					
						$totalHonorarios=0;
					}
					
					
			
					$content.='</div>';
				}
			
			
			
				 $content.='   	</body>
				    		</html>';
		}
		
		return $content;
	}
	
	private function pdfBoleteo()
	{		
		$cobroMapper=new Application_Model_CobroMapper();
    	$rutMapper=new Application_Model_RutMapper();
		
		$meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		 
		$content='';
		
		if(!empty($_REQUEST['idCliente'])&&
		   !empty($_REQUEST['anio'])&&
		   !empty($_REQUEST['nombreCliente']))
		{	
			
			$anio=$_REQUEST['anio'];
			
			
			$content .= '<html>
		    		     <head>
		    				<title>Reporte acumulado de boleteo anual por contribuyente</title>
    						<style>
					
		    					table 
		    					{
								  width: 230px;
		    					  heigth: 150px;
								  border: 1px solid #000;
		    					  border-collapse: collapse;
								  font:75%;								  
								}
    		
    							.tablePN
    							{
    				 				heigth: auto;
    								width: auto;
    							}    		
		
		    					th
		    					{
		    						border: 1px solid black;
		    						padding: 4px;
		    						background-color: #039be5;
		    						color: white;
		    					}
		
		    					td
		    					{
		    						border: 1px solid black;
		    						padding: 3px;
		    					}
		   
		    					.title
		    					{
		    						background-color: white;
		    						color: black;
		    					}
		    	
		    					.result
		    					{
		    						background-color: #4db6ac;
		    						color: white;
		    					}
					
		    				</style>
		    			</head>
		    			<body>
		
    						<div>
    							<h3>Informe boleteo anual '.$_REQUEST['anio'].' por contribuyente</h3>
    							<hr>
    						</div>';
    									
			
				$content.='<div style="page-break-inside:avoid;">
							
							<label><strong>Cliente:</strong> '.$_REQUEST['nombreCliente'].'</label>
							
    						<table>
    							<thead>
    								<tr>
    									<th colspan="2">Plan ' .$_REQUEST['anio']. '</th>
    								</tr>
    							</thead>
    							<tbody>';	
					
				foreach ($rutMapper->obtieneRazonSocialBoleteo($_REQUEST['idCliente'],$_REQUEST['anio']) as $i => $meta)
				{	
					$content .='<tr>
	    							<td>'.$meta['ci04_razonsocial'].'</td>
	    							<td>$ '.(number_format($meta['ci_montoboleteo'],0,",",".")).'</td>
	    						</tr>';
				}	
				
				$content .='	</tbody>
    						  </table>
		    				</div>
		  				  <br>';
				
			
			//obtengo las razones sociales del cliente
			foreach ($rutMapper->obtieneRazonSocialBoleteo($_REQUEST['idCliente'],$_REQUEST['anio']) as $i => $boleteo)
			{
				
				$idCliente=$boleteo['ci03_idcliente'];			
				
				switch($boleteo['ci40_idsociedad'])
				{
					case '0':		$mes="";
					
									$sumValorMes=0;
									$valorMesSinRetencion=0;
									$valorMesConRetencion=0;
									
									$content .='<div style="page-break-inside:avoid;">
									
												<label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (PN)</label>
										
												<table class="tablePN">
									    			<thead>
									    				<tr>
									    					<th>Mes</th>
												    		<th>Sin Retenci&oacute;n</th>
												    		<th>Con Retenci&oacute;n</th>
												    		<th>Total Mes</th>
												    		<th>Total Acumulado</th>
									    				</tr>
									    			</thead>
									    		<tbody>';
									
									for ($i=0;12>$i;$i++)
									{
										if($i<9)
										{
											$mes='0'.($i+1);
										}
										else
										{
											$mes=$i+1;
										}
									
										$valorMesSinRetencion=$this->obtieneDataPNSegundaCat('1',$idCliente,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
										$valorMesConRetencion=$this->obtieneDataPNSegundaCat('2',$idCliente,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
										
										$sumValorMes+=intval($valorMesSinRetencion)+intval($valorMesConRetencion);
																		
										$content .='<tr>
										    			<td>'.$meses[$i].'</td>
										    			<td>$ '.(number_format($valorMesSinRetencion,0,",",".")).'</td>
										    			<td>$ '.(number_format($valorMesConRetencion,0,",",".")).'</td>
										    			<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
										    			<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
										    	    </tr>';
									}
									
									if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="4"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
										    				</tr>
										    				<tr>
										    					<td><strong>% Avance</strong></td>
										    					<td colspan="4"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									else
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="4"><strong>Actualizar Meta de Boleteo</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									
									
							 break;
							 
					case '1':		$mes="";
									$sumValorMes=0;
										
									$content .='<div style="page-break-inside:avoid;">
															   <label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (Soc. Primera Categoria)</label>
															   <table class="tablePN">
															       <thead>
															       		<tr>
															       		    <th>Mes</th>
																   	        <th>Ingreso Sociedad</th>
																		    <th>Total Acumulado</th>
															    		</tr>
															        </thead>
														    		<tbody>';
									for ($i=0;12>$i;$i++)
									{
											
										if($i<9)
										{
											$mes='0'.($i+1);
										}
										else
										{
											$mes=$i+1;
										}
											
										$valorMes=$this->obtieneDataPrimeraCat14Bis($idCliente,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
										$sumValorMes+=$valorMes;
									
									
										$content.='			<tr>
																<td>'.$meses[$i].'</td>
																<td>$ '.(number_format($valorMes,0,",",".")).'</td>
																<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
															</tr>';
									}
									
									if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="2"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
										    				</tr>
										    				<tr>
										    					<td><strong>% Avance</strong></td>
										    					<td colspan="2"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									else
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="2"><strong>Actualizar Meta de Boleteo</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									
						
							break;
					case '2':
						
									$mes="";
									$sumValorMes=0;
									$valorMesSinRetencion=0;
									$valorMesConRetencion=0;
									
									$content .='<div style="page-break-inside:avoid;">
									
											<label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (Soc. Segunda Categoria)</label>
									
											<table class="tablePN">
								    			<thead>
								    				<tr>
								    					<th>Mes</th>
											    		<th>Sin Retenci&oacute;n</th>
											    		<th>Con Retenci&oacute;n</th>
											    		<th>Total Mes</th>
											    		<th>Total Acumulado</th>
								    				</tr>
								    			</thead>
								    		<tbody>';
									
									for ($i=0;12>$i;$i++)
									{
										if($i<9)
										{
											$mes='0'.($i+1);
										}
										else
										{
											$mes=$i+1;
										}
									
										$valorMesSinRetencion=$this->obtieneDataPNSegundaCat('1',$idCliente,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
										$valorMesConRetencion=$this->obtieneDataPNSegundaCat('2',$idCliente,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
										
										$sumValorMes+=intval($valorMesSinRetencion)+intval($valorMesConRetencion);									
									
										$content .='<tr>
										    				 	<td>'.$meses[$i].'</td>
										    				 	<td>$ '.(number_format($valorMesSinRetencion,0,",",".")).'</td>
										    				 	<td>$ '.(number_format($valorMesConRetencion,0,",",".")).'</td>
										    				 	<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
										    				 	<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
										    				 </tr>';
									}
									
									if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="4"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
										    				</tr>
										    				<tr>
										    					<td><strong>% Avance</strong></td>
										    					<td colspan="4"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									else
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="4"><strong>Actualizar Meta de Boleteo</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									
									
						break;
						
						
					case '3':
						
						$mes="";
						$sumValorMes=0;
							
						$content.='<div style="page-break-inside:avoid;">
								   <label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (Soc. 14 BIS)</label>
								   <table class="tablePN">
								       <thead>
								       		<tr>
								       		    <th>Mes</th>
									   	        <th>Ingreso Sociedad</th>
											    <th>Total Acumulado</th>
								    		</tr>
								        </thead>
							    		<tbody>';
						for ($i=0;12>$i;$i++)
						{
								
							if($i<9)
							{
								$mes='0'.($i+1);
							}
							else
							{
								$mes=$i+1;
							}
								
							$valorMes=$this->obtieneDataPrimeraCat14Bis($idCliente,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
							$sumValorMes+=$valorMes;
						
						
							$content.='			<tr>
												<td>'.$meses[$i].'</td>
												<td>$ '.(number_format($valorMes,0,",",".")).'</td>
												<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
											</tr>';
						}
						
						
						
									if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="2"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
										    				</tr>
										    				<tr>
										    					<td><strong>% Avance</strong></td>
										    					<td colspan="2"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									else
									{
										$content .='</tbody>
										    			<tfoot>
										    				<tr>
										    					<td><strong>Ideal</strong></td>
										    					<td colspan="2"><strong>Actualizar Meta de Boleteo</strong></td>
										    				</tr>
										    			</tfoot>
										    		</table>
								    			</div>
										    	<br>';
									}
									
						break;
				}
			}
				
			
    		$content .='		<br>				
    					</body>
			    	 </html>';
		}
		
		return $content;
	}

	private function pdfMasivoPPM()
	{
		$rutMapper=new Application_Model_RutMapper();
		$cobroMapper=new Application_Model_CobroMapper();
					
		$conceptoPrevired=array("Empresarial","Independiente","Nana","Otros","Socios","Trabajadores");
		
		$content='';
		
		if(!empty($_REQUEST['fecha']))
		{				
			//page-break-inside:avoid; =>genera el salto de linea en el documento PDF

			$fecha=$_REQUEST['fecha'];
			
			$content .= '<html>
			    		    <head>
			    				<title>Detalle declaraci&oacute;n tesorer&iacute;a general de la rep&uacute;blica</title>
					
	    						<style>
			    					table {
									  width: 100%;
			    					  heigth: auto;
									  border: 1px solid #000;
			    					  border-collapse: collapse;
					 				  font:75%;
									}
			
			    					th{
			    						border: 1px solid black;
			    						padding: 4px;
			    						background-color: #039be5;
			    						color: white;
			    					}
			
			    					td{
			    						border: 1px solid black;
			    						padding: 3px;
			    					}
			
			    					.title{
			    						background-color: white;
			    						color: black;
			    					}
			
			    					.result{
			    						background-color: #4db6ac;
			    						color: white;
			    					}
			    						</style>
			    				</head>
			    				<body>
				';
			
			foreach ($cobroMapper->obtieneIDCliente($_REQUEST['idUsuario']) as $j => $idCliente)
			{
				
				if($j>=1)
				{
					$content .='<div style="page-break-before: always;">';
				}
				else
				{
					$content .='<div style="page-break-inside:avoid;">';
				}
				
				$content .='<!---------------------Cuadro Resumen PPM----------------------------->
													
							<label><strong>Cliente:</strong> '.$idCliente['ci03_nombre'].'</label>
									
							<table>
								<thead>
									<tr>
										<th>Contribuyente</th>
										<th>F29</th>
										<th>Previred</th>
										<th>Otros</th>
										<th>Honorarios Angkor</th>
										<th>Total</th>									
										<th style="background-color: white;"></th>									
										<th>Dep. Angkor</th>
										<th>Pago Directo Cliente</th>
										<th>PEC</th>
										<th>Cheque SS</th>
									</tr>
								</thead>
								<tbody>
							';
				
				$sumtaTotal=0;
				$total=0;				
				$sumDepAngkor=0;
				$sumPagoDirecto=0;
				$sumPec=0;
				$sumOrdenPago=0;
				
				foreach ($cobroMapper->obtieneDatosEmailPPM($idCliente['ci03_idcliente'],$fecha) as $i => $cobro)
				{ 						
					$total=intval($cobro['ci_montof29'])+intval($cobro['ci_montoPrevired'])+intval($cobro['ci_otros'])+intval($cobro['ci_honorarios']);
					$sumtaTotal+=$total;
					
					$sumDepAngkor+=(intval($cobro['ci_depAngkor']) + intval($cobro['ci_depAngkor_pendiente']));
					$sumPagoDirecto+=(intval($cobro['ci_pagodirecto']) + intval($cobro['ci_pagodirecto_pendiente']));
					$sumPec+=(intval($cobro['ci_pec']) + intval($cobro['ci_pec_pendiente']));
					$sumOrdenPago+=(intval($cobro['ci_ordenpago']) + intval($cobro['ci_ordenpago_pendiente']));
					
						$content .='	<tr>
											<td>'.$cobro['ci_razonsocial'].'</td>
													
											<td>$ '.(number_format($cobro['ci_montof29'],0,",",".")).'</td>													
											<td>$ '.(number_format($cobro['ci_montoPrevired'],0,",",".")).'</td>
											<td>$ '.(number_format($cobro['ci_otros'],0,",",".")).'</td>
											<td>$ '.(number_format($cobro['ci_honorarios'],0,",",".")).'</td>
											<td>$ '.(number_format($total,0,",",".")).'</td>
												
											<td></td>
											
											<td>$ '.(number_format((intval($cobro['ci_depAngkor'])+ intval($cobro['ci_depAngkor_pendiente'])),0,",",".")).'</td>
											<td>$ '.(number_format((intval($cobro['ci_pagodirecto']) + intval($cobro['ci_pagodirecto_pendiente'])),0,",",".")).'</td>	
											<td>$ '.(number_format((intval($cobro['ci_pec']) + intval($cobro['ci_pec_pendiente'])),0,",",".")).'</td>						
											<td>$ '.(number_format((intval($cobro['ci_ordenpago']) + intval($cobro['ci_ordenpago_pendiente'])),0,",",".")).'</td>
								    </tr>';
						
				}				
		
					$content .='	</tbody>
									<tfoot>
										<tr>
											<td colspan="5" align="right"><strong>Total</strong></td>
											<td><strong>$ '.(number_format($sumtaTotal,0,",",".")).'</strong></td>
											<td></td>';
										
					$content .='			<td><strong>$ '.(number_format($sumDepAngkor,0,",",".")).'</strong></td>
											<td><strong>$ '.(number_format($sumPagoDirecto,0,",",".")).'</strong></td>
											<td><strong>$ '.(number_format($sumPec,0,",",".")).'</strong></td>
											<td><strong>$ '.(number_format($sumOrdenPago,0,",",".")).'</strong></td>';
					
					$content .='		</tr>
									</tfoot>
								</table>								
								</div>							
								<br>
														
								<h3>Detalle declaraci&oacute;n tesorer&iacute;a general de la rep&uacute;blica (F29), mes de '.$this->mesPPM($fecha).'</h3>
								<hr>
						';
				
							
			//Cuadro Persona Natural		
			$ingSinRetencion=0;
			
			foreach ($cobroMapper->obtieneResumenPPM($idCliente['ci03_idcliente'],'1','0',$fecha) as $i => $ppm)
			{					
				if($ppm['ci07_ingsinretencion']!='')
				{
					$ingSinRetencion=$ppm['ci07_ingsinretencion'];
				}				
								
				if($ppm['ci07_ingconretencion']!='')
				{
					$ppm['ci07_ingconretencion']=(number_format($ppm['ci07_ingconretencion'],0,",","."));
				}
				else
				{
					$ppm['ci07_ingconretencion']='0';
				}
					
				$content .='<div style="page-break-inside:avoid;">
								<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (PN)</label>
		
									<table>
										<thead>
											<tr>
												<th></th>
												<th>VALOR</th>
												<th>IMPUESTO</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>INGRESOS SIN RETENCI&Oacute;N (Tasa '.$ppm['ci_impuesto'].'%)</td>
												<td>$ '.(number_format($ingSinRetencion,0,",",".")).'</td>
												<td>$ '.(number_format(((intval($ppm['ci07_ingsinretencion'])*floatval($ppm['ci_impuesto'])) / 100),0,",",".")).'</td>
											</tr>
											<tr>
												<td>INGRESOS CON RETENCI&Oacute;N</td>
												<td>$ '.$ppm['ci07_ingconretencion'].'</td>
												<td>-</td>
											</tr>
											<tr>
												<td>RETENCIONES A TERCEROS</td>
												<td>$ '.(number_format($ppm['ci07_retencion'],0,",",".")).'</td>
												<td>-</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
												<td>-</td>
												<td><strong>$ '.(number_format(((intval($ppm['ci07_ingsinretencion'])*intval($ppm['ci_impuesto'])) / 100),0,",",".")).'</strong></td>
											</tr>
										</tfoot>
									</table>
									<br>
								</div>';
			}	
				
			//Cuadro Sociedad Primera Categoria
		
			foreach ($cobroMapper->obtieneResumenPPM($idCliente['ci03_idcliente'],'1','1',$fecha) as $i => $ppm)
			{
				
					$ingresos=(intval($ppm['ci07_ingsociedad']) * floatval($ppm['ci_impuesto']) / 100);
					$retencion=((intval($ppm['ci07_bolretterceros']) * 10) / 100 );
					$impuestounico=intval($ppm['ci07_impuestounico']);
					
					$content .='<div style="page-break-inside:avoid;">
								<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (Soc. Primera Categoria)</label>
									<table>
										<thead>
											<tr>
												<th></th>
												<th>VALOR</th>
												<th>IMPUESTO</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>INGRESOS (Tasa '.$ppm['ci_impuesto'].'%)</td>
												<td>$ '.(number_format($ppm['ci07_ingsociedad'],0,",",".")).'</td>
												<td>$ '.(number_format($ingresos,0,",",".")).'</td>
											</tr>
											<tr>
												<td>RETENCIONES A TERCEROS</td>
												<td>$ '.(number_format($ppm['ci07_bolretterceros'],0,",",".")).'</td>
												<td>$ '.(number_format($retencion,0,",",".")).'</td>
											</tr>
											<tr>
												<td>IMPUESTO &Uacute;NICO</td>
												<td>-</td>
												<td>$ '.(number_format($impuestounico,0,",",".")).'</td>
											</tr>';
					//estas filas se muestra solo si en el mantenedor del rut esta seleccionada la opcion de IVA
					
					$impuestoIVA=0;
					
					if($ppm['ci04_iva']=='1')
					{						
						if($ppm['ci07_ivapago']!='')
						{
							$ppm['ci07_ivapago']=(number_format($ppm['ci07_ivapago'],0,",","."));
						}
						else
						{
							$ppm['ci07_ivapago']='0';
						}
						
						if($ppm['ci07_remanente']!='')
						{
							$ppm['ci07_remanente']=(number_format($ppm['ci07_remanente'],0,",","."));
						}
						else
						{
							$ppm['ci07_remanente']='0';
						}
							
						$content .='		<tr>
												<td>IVA A PAGO</td>
												<td>-</td>
												<td>$ '.$ppm['ci07_ivapago'].'</td>
											</tr>
											<tr>
												<td>REMANENTE IVA CRED MES SIGUIENTE</td>
												<td>$ '.$ppm['ci07_remanente'].'</td>
												<td>-</td>
											</tr>';
							
											$impuestoIVA=str_replace(".","",$ppm['ci07_ivapago']);
							
					}
					$content .='		</tbody>
										<tfoot>
											<tr>
												<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
												<td>-</td>
												<td><strong> $ '.(number_format(($ingresos + $retencion + $impuestounico + $impuestoIVA),0,",",".")).'</strong></td>
											</tr>
										</tfoot>
									</table>
									<br>
								</div>';
				}
				
					
				//Cuadro Sociedad Segunda Categoria
				foreach ($cobroMapper->obtieneResumenPPM($idCliente['ci03_idcliente'],'1','2',$fecha) as $i => $ppm)
				{					
				
						$ingresoTasa=(intval($ppm['ci07_ingsinretencion'])*floatval($ppm['ci_impuesto'])/100);
				
						$content .='<div style="page-break-inside:avoid;">
									<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (Soc. Segunda Categor&iacute;a)</label>
									<table>
										<thead>
											<tr>
												<th></th>
												<th>VALOR</th>
												<th>IMPUESTO</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>INGRESOS SIN RETENCI&Oacute;N (Tasa '.$ppm['ci_impuesto'].'%)</td>
												<td>'.$ppm['ci07_ingsinretencion'].'</td>
												<td>$ '.(number_format($ingresoTasa,0,",",".")).'</td>
											</tr>
											<tr>
												<td>INGRESOS CON RETENCI&Oacute;N</td>
												<td>'.(number_format($ppm['ci07_ingconretencion'],0,",",".")).'</td>
												<td>-</td>
											</tr>
											<tr>
												<td>RETENCIONES A TERCEROS</td>
												<td>$ '.(number_format($ppm['ci07_retencion'],0,",",".")).'</td>
												<td>-</td>
											</tr>
											<tr>
												<td>IMPUESTO &Uacute;NICO</td>
												<td>-</td>
												<td>$ '.(number_format($ppm['ci07_impuestounico'],0,",",".")).'</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
												<td></td>
												<td><strong>$ '.(number_format(($ingresoTasa+intval($ppm['ci07_impuestounico'])),0,",",".")).'</strong></td>
											</tr>
										</tfoot>
									</table>
								</div><br>';
					}
					
				//Cuadro Sociedad 14 BIS
				foreach ($cobroMapper->obtieneResumenPPM($idCliente['ci03_idcliente'],'1','3',$fecha) as $i => $ppm)
				{						
					$ingresoTasa= ((intval($ppm['ci07_retsociedad'])*floatval($ppm['ci_impuesto']) / 100));
											
					if($ppm['ci07_ingsociedad']!='')
					{
						$ppm['ci07_ingsociedad']=(number_format($ppm['ci07_ingsociedad'],0,",","."));
					}
					else
					{
						$ppm['ci07_ingsociedad']='0';
					}
					
					$content .='<div style="page-break-inside:avoid;">
										<label><strong>'.$ppm['ci04_razonsocial'].'</strong> (Soc. 14 BIS)</label>
										<table>
											<thead>
												<tr>
													<th></th>
													<th>VALOR</th>
													<th>IMPUESTO</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>INGRESOS</td>
													<td>$ '.$ppm['ci07_ingsociedad'].'</td>
													<td>-</td>
												</tr>
												<tr>
													<td>RETIROS (Tasa '.$ppm['ci_impuesto'].'%)</td>
													<td>$ '.(number_format($ppm['ci07_retsociedad'],0,",",".")).'</td>
													<td>$ '.(number_format($ingresoTasa,0,",",".")).'</td>
												</tr>
												<tr>
													<td>RETENCIONES A TERCEROS</td>
													<td>$ '.(number_format($ppm['ci07_bolretterceros'],0,",",".")).'</td>
													<td>$ '.(number_format($ppm['ci07_retencion'],0,",",".")).'</td>
												</tr>
												<tr>
													<td>IMPUESTO &Uacute;NICO</td>
													<td>-</td>
													<td>$ '.(number_format($ppm['ci07_impuestounico'],0,",",".")).'</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td>Forma de Pago: <strong>'.$ppm['ci35_tipopago'].'</strong></td>
													<td>-</td>
													<td><strong>$ '.(number_format($ingresoTasa+intval($ppm['ci07_impuestounico'])+intval($ppm['ci07_retencion']),0,",",".")).'</strong></td>
												</tr>
											</tfoot>
										</table>
									</div><br>';
				}
				
				//cuadros informacion Previred	
				
				$dataPrevired=$cobroMapper->obtieneDatosPrvired($idCliente['ci03_idcliente'],$fecha);
				
				if(count($dataPrevired)>0)
				{
				
					$content .='		<div style="page-break-inside:avoid;">
					
											<h3>Detalle Previred</h3>
											<hr>';
					
					foreach ($cobroMapper->obtieneRazonSocialPrevired($idCliente['ci03_idcliente']) as $i => $prev)
					{	
							$content .=' <label><strong>'.$prev['ci04_razonsocial'].'</strong></label>
							
											<table>
												<tbody>';
						
							foreach ($dataPrevired as $a => $previ)
							{
								
								if($prev['ci04_razonsocial']==$previ['ci04_razonsocial'])
								{
								
									$content.='<tr>
													<td>'.$previ['ci07_conceptoprevired'].'</td>
													<td>$ '.(number_format($previ['ci07_monto'],0,",",".")).'</td>
													<td>'.$previ['ci35_tipopago'].'</td>
										      </tr>';
								}
							}
							
							$content.='</tbody>
											</table>									
										<br>';
					}				
					
					$content .='</div>';
				}							
					
				//Detalle Otros				
				if(sizeof($cobroMapper->obtieneDetalleOtros($idCliente['ci03_idcliente'],$fecha))>0)
				{
					$content.= '<div style="page-break-inside:avoid;">
							<h3>Detalle Otros</h3>
							<hr>';
						
					foreach ($cobroMapper->obtieneRazonSocialOtros($idCliente['ci03_idcliente'],$fecha) as $i =>$razon)
					{
						$totalOtros=0;
							
						$content.='	<table>
											<thead>
												<tr>
													<th>Contribuyente</th>
													<th>Concepto</th>
													<th>Monto UF</th>
													<th>Monto Monetario</th>
													<th>Forma De pago</th>
												</tr>
											</thead>
											<tbody>';
							
						foreach ($cobroMapper->obtieneDetalleOtros($razon['ci03_idcliente'],$fecha) as $o =>$otros)
						{
							$uf="";
								
							if($otros['ci_montouf']!='')
							{
								$uf=$otros['ci_montouf'].' UF';
							}
								
							if($razon['ci04_razonsocial']==$otros['ci04_razonsocial'])
							{
								$totalOtros+=intval($otros['ci_monto']);
									
								$content.='
												<tr>
													<td>'.$razon['ci04_razonsocial'].'</td>
													<td>'.$otros['ci33_nombre'].' - '.$this->mesPPM($otros['ci_fecha']).'</td>
													<td>'.$uf.'</td>
													<td>$ '.(number_format($otros['ci_monto'],0,",",".")).'</td>
													<td>'.$otros['ci35_tipopago'].'</td>
												</tr>';
							}
						}
							
						$content.='	</tbody>
												<tfoot>
			 										<tr>
		
				 										<td colspan="4" align="right"><strong>Total</strong></td>
				 										<td>$ '.(number_format($totalOtros,0,",",".")).'</td>
				 									</tr>
				 								</tfoot>
				 							</table>
				 					<br>';
							
					}
				
					$content.='</div>';
				}		
				
				//Detalle Honorarios
				if(sizeof($cobroMapper->obtieneHonorarios($idCliente['ci03_idcliente'],$fecha))>0)
				{
					$content.='<div style="page-break-inside:avoid;">
										<h3>Detalle Honorarios de Angkor</h3>
										<hr>';
					//deta PRueba
					$totalHonorarios=0;
					foreach ($cobroMapper->obtieneRazonSocialHonorariosbyCliente($idCliente['ci03_idcliente'],$fecha) as $l =>$hon)
					{
						$content.='
							  <table>
										<thead>
											<tr>
										    	<th>Contribuyente</th>
												<th>Concepto</th>
												<th>Monto UF Unit</th>
												<th>Monto Monetario Unit</th>
												<th>Monto Total</th>
											</tr>
										</thead>
										<tbody>';
				
						//foreach lista los datos en la tabla
						foreach ($cobroMapper->obtieneHonorarios($hon['ci03_idcliente'],$fecha) as $i => $honorario)
						{
							if($hon['ci04_razonsocial']==$honorario['ci04_razonsocial'])
							{
								$totalHonorarios+=intval($honorario['ci_monto']);
								$content.='	<tr>
												<td>'.$honorario['ci04_razonsocial'].'</td>
												<td>'.$honorario['ci33_nombre'].'</td>
												<td>'.$honorario['ci_montouf'].' UF</td>
												<td>$ '.(number_format($honorario['ci_monto'],0,",",".")).'</td>
												<td>$ '.(number_format($honorario['ci_monto'],0,",",".")).'</td>
											</tr>';
							}
						}
				
						$content.='	 </tbody>
											 <tfoot>
												<tr>
										     		<td colspan="4" align="right"><strong>Total</strong></td>
													<td>$ '.(number_format($totalHonorarios,0,",",".")).'</td>
												</tr>
											 </tfoot>
										</table>
															<br>
									';
				
						$totalHonorarios=0;
					}
				
					$content.='</div>';
				}
			
				$content .='<hr>';
			}			
					$content .=' 
				    			</body>
				    		</html>';
			
		}
		
		return $content;
	}
	
	private function pdfMasivoBoleteo()
	{
		$cobroMapper=new Application_Model_CobroMapper();
		$rutMapper=new Application_Model_RutMapper();		
		
		$meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
				
		if(!empty($_REQUEST['anio']))
		{					
			$fecha=$_REQUEST['anio'];
			
			$content = '<html>
			    		<head>
			    		   <title>Reporte acumulado de boleteo anual por contribuyente</title>
					
			    				  <style>
							
				    					table 
				    					{
										  width: 230px;
				    					  heigth: 150px;
										  border: 1px solid #000;
				    					  border-collapse: collapse;
										  font:75%;										  
										}
		    		
		    							.tablePN
		    							{
		    				 				heigth: auto;
		    								width: auto;
		    							}    		
				
				    					th
				    					{
				    						border: 1px solid black;
				    						padding: 4px;
				    						background-color: #039be5;
				    						color: white;
				    					}
				
				    					td
				    					{
				    						border: 1px solid black;
				    						padding: 3px;
				    					}
				   
				    					.title
				    					{
				    						background-color: white;
				    						color: black;
				    					}
				    	
				    					.result
				    					{
				    						background-color: #4db6ac;
				    						color: white;
				    					}
					
				    				</style>
					
			    				</head>
					
			    				<body>
									<h2>Resumen Boleteo a&ntilde;o '.$fecha.'</h2>
									<hr>';
			
			foreach ($cobroMapper->obtieneIDCliente($_REQUEST['idUsuario']) as $j => $cliente)
			{			
					$idCli=$cliente['ci03_idcliente'];	
					
					$idRutBoleteo=array();
				
					if($j>=1)
					{
						$content .='<div style="page-break-before: always;">';
					}
					else
					{
						$content .='<div style="page-break-inside:avoid;">';
					}					
					
					$content.='<label><strong>Cliente:</strong> '.$cliente['ci03_nombre'].'</label>
					
	    						<table>
	    							<thead>
	    								<tr>
	    									<th colspan="2">Plan ' .$fecha. '</th>
	    								</tr>
	    							</thead>
	    							<tbody>';
						
					foreach ($rutMapper->obtieneRazonSocialBoleteo($idCli,$fecha) as $i => $meta)
					{
						$content .='<tr>
		    							<td>'.$meta['ci04_razonsocial'].'</td>
		    							<td>$ '.(number_format($meta['ci_montoboleteo'],0,",",".")).'</td>
		    						</tr>';
					}
					
						$content .='</tbody>
	    						  </table>
			    				</div>
			  				  <br>';
			    
			    foreach ($rutMapper->obtieneRazonSocialBoleteo($idCli,$fecha) as $b => $boleteo)
			    {			    
			    	$idCliente=$boleteo['ci03_idcliente'];
			    	$idSociedad=$boleteo['ci40_idsociedad'];
			    
			    	switch($idSociedad)
			    	{
			    		case '0': 
			    			
			    			$mes="";
			    				
			    			$sumValorMes=0;
			    			$valorMesSinRetencion=0;
			    			$valorMesConRetencion=0;
			    				
			    			$content .='<div style="page-break-inside:avoid;">
					
												<label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (PN)</label>
			    			
												<table class="tablePN">
									    			<thead>
									    				<tr>
									    					<th>Mes</th>
												    		<th>Sin Retenci&oacute;n</th>
												    		<th>Con Retenci&oacute;n</th>
												    		<th>Total Mes</th>
												    		<th>Total Acumulado</th>
									    				</tr>
									    			</thead>
									    		<tbody>';
			    				
			    			for ($i=0;12>$i;$i++)
			    			{
			    				if($i<9)
			    				{
			    					$mes='0'.($i+1);
			    				}
			    				else
			    				{
			    					$mes=$i+1;
			    				}
			    					
			    				$valorMesSinRetencion=$this->obtieneDataPNSegundaCat('1',$idCli,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
			    				$valorMesConRetencion=$this->obtieneDataPNSegundaCat('2',$idCli,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
			    			
			    				$sumValorMes+=intval($valorMesSinRetencion)+intval($valorMesConRetencion);
			    			
			    				$content .='<tr>
										    	<td>'.$meses[$i].'</td>
										    	<td>$ '.(number_format($valorMesSinRetencion,0,",",".")).'</td>
										    	<td>$ '.(number_format($valorMesConRetencion,0,",",".")).'</td>
										    	<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
										    	<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
										    </tr>';
			    			}
			    				
			    			if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
			    			{
			    				$content .='</tbody>
										    	<tfoot>
										    		<tr>
										    			<td><strong>Ideal</strong></td>
										    			<td colspan="4"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
										    		</tr>
										    		<tr>
										    			<td><strong>% Avance</strong></td>
										    			<td colspan="4"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
										    		</tr>
										    	</tfoot>
										    	</table>
								    		</div>
										  <br>';
			    			}
			    			else
			    			{
			    				$content .='</tbody>
										    	<tfoot>
										    		<tr>
										    			<td><strong>Ideal</strong></td>
										    			<td colspan="4"><strong>Actualizar Meta de Boleteo</strong></td>
										    		</tr>
										    	</tfoot>
										    	</table>
								    		</div>
										 <br>';
			    			}
			    			
			    			break;
			    			
			    	case '1':
					    		$mes="";
					    		$sumValorMes=0;
					    		
					    		$content .='<div style="page-break-inside:avoid;">
												<label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (Soc. Primera Categoria)</label>
												   <table class="tablePN">
												       <thead>
												    		<tr>
												       		    <th>Mes</th>
													   	        <th>Ingreso Sociedad</th>
															    <th>Total Acumulado</th>
												    		</tr>
												        </thead>
												 		<tbody>';
					    		for ($i=0;12>$i;$i++)
					    		{
					    				
					    			if($i<9)
					    			{
					    				$mes='0'.($i+1);
					    			}
					    			else
					    			{
					    				$mes=$i+1;
					    			}
					    				
					    			$valorMes=$this->obtieneDataPrimeraCat14Bis($idCli,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
					    			$sumValorMes+=$valorMes;
					    				
					    				
					    			$content.='			<tr>
															<td>'.$meses[$i].'</td>
															<td>$ '.(number_format($valorMes,0,",",".")).'</td>
															<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
														</tr>';
					    		}
					    			
					    		if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
					    		{
					    				$content .='</tbody>
												    	<tfoot>
												    		<tr>
												    			<td><strong>Ideal</strong></td>
												    			<td colspan="2"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
												    		</tr>
												    		<tr>
												    			<td><strong>% Avance</strong></td>
												    			<td colspan="2"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
												    		</tr>
												    	</tfoot>
												   	</table>
										    	</div>
											 	<br>';
					    		}
					    		else
					    		{
					    				$content .='</tbody>
												    	<tfoot>
												    		<tr>
												    			<td><strong>Ideal</strong></td>
												    			<td colspan="2"><strong>Actualizar Meta de Boleteo</strong></td>
												    		</tr>
												    	</tfoot>
												   		</table>
										    		</div>
										    	<br>';
					    		}
					    		
			    			break;
			    			
			    	case '2':
			    		
				    		$mes="";
				    		$sumValorMes=0;
				    		$valorMesSinRetencion=0;
				    		$valorMesConRetencion=0;
				    			
				    			$content .='<div style="page-break-inside:avoid;">						
												<label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (Soc. Segunda Categoria)</label>
												<table class="tablePN">
									    			<thead>
									    				<tr>
									    					<th>Mes</th>
												    		<th>Sin Retenci&oacute;n</th>
												    		<th>Con Retenci&oacute;n</th>
												    		<th>Total Mes</th>
												    		<th>Total Acumulado</th>
									    				</tr>
									    			</thead>
									    		<tbody>';
				    			
				    		for ($i=0;12>$i;$i++)
				    		{
				    			if($i<9)
				    			{
				    				$mes='0'.($i+1);
				    			}
				    			else
				    			{
				    				$mes=$i+1;
				    			}
				    				
				    			$valorMesSinRetencion=$this->obtieneDataPNSegundaCat('1',$idCli,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
				    			$valorMesConRetencion=$this->obtieneDataPNSegundaCat('2',$idCli,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
				    		
				    			$sumValorMes+=intval($valorMesSinRetencion)+intval($valorMesConRetencion);
				    				
				    				$content .='<tr>
											    	<td>'.$meses[$i].'</td>
											    	<td>$ '.(number_format($valorMesSinRetencion,0,",",".")).'</td>
											    	<td>$ '.(number_format($valorMesConRetencion,0,",",".")).'</td>
											    	<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
											    	<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
											  </tr>';
				    		}
				    			
				    		if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
				    		{
				    			$content .='</tbody>
											    <tfoot>
											    	<tr>
											    		<td><strong>Ideal</strong></td>
											    		<td colspan="4"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
											    	</tr>
											   		<tr>
											   			<td><strong>% Avance</strong></td>
											   			<td colspan="4"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
											   			</tr>
											   	</tfoot>
											   	</table>
									    	</div>
									    	<br>';
				    		}
				    		else
				    		{
				    			$content .='</tbody>
											    <tfoot>
											    	<tr>
											    		<td><strong>Ideal</strong></td>
											    		<td colspan="4"><strong>Actualizar Meta de Boleteo</strong></td>
											    	</tr>
											    </tfoot>
											</table>
									    	</div>
								    	<br>';
				    		}
			    			break;
			    			
			    	case '3':
							
				    		$mes="";
				    		$sumValorMes=0;
				    			
				    		$content.='<div style="page-break-inside:avoid;">
									   <label>Raz&oacute;n Social: <strong>'.$boleteo['ci04_razonsocial'].'</strong> (Soc. 14 BIS)</label>
									   <table class="tablePN">
									       <thead>
									       		<tr>
									       		    <th>Mes</th>
										   	        <th>Ingreso Sociedad</th>
												    <th>Total Acumulado</th>
									    		</tr>
									        </thead>
								    		<tbody>';
				    		for ($i=0;12>$i;$i++)
				    		{
				    		
				    			if($i<9)
				    			{
				    				$mes='0'.($i+1);
				    			}
				    			else
				    			{
				    				$mes=$i+1;
				    			}
				    		
				    			$valorMes=$this->obtieneDataPrimeraCat14Bis($idCli,$boleteo['ci40_idsociedad'],$mes,$_REQUEST['anio'],$cobroMapper,$boleteo['ci04_idrrut']);
				    			$sumValorMes+=$valorMes;
				    		
				    		
				    			$content.='	<tr>
												<td>'.$meses[$i].'</td>
												<td>$ '.(number_format($valorMes,0,",",".")).'</td>
												<td>$ '.(number_format($sumValorMes,0,",",".")).'</td>
											</tr>';
				    		}
				    		
				    		if($boleteo['ci_montoboleteo']!=''&&$boleteo['ci_montoboleteo']!='0')
				    		{
				    			$content .='</tbody>
											<tfoot>
											   	<tr>
											   		<td><strong>Ideal</strong></td>
											   		<td colspan="2"><strong>$ '.(number_format($boleteo['ci_montoboleteo'],0,",",".")).'</strong></td>
											   	</tr>
											   	<tr>
											   		<td><strong>% Avance</strong></td>
											   		<td colspan="2"><strong>'.round((($sumValorMes*100)/intval($boleteo['ci_montoboleteo'])),2).' %</strong></td>
											   	</tr>
											</tfoot>
											</table>
									   	</div>
								  	<br>';
				    		}
				    		else
				    		{
				    			$content .='</tbody>
											<tfoot>
											   	<tr>
													<td><strong>Ideal</strong></td>
											    	<td colspan="2"><strong>Actualizar Meta de Boleteo</strong></td>
												</tr>
							    			</tfoot>
								    		</table>
								    	</div>
							    	<br>';
				    		}
			    		
			    			break;				    			
			    	}
			    }
			
			    $content.='<hr>
			    		<br>';
			}
			
			$content .='		</body>
							</html>';
			
		}		
		
		return $content;
	}
	
	private function verificaDataIngreso($origen,$idCliente,$idSociedad,$mapper)
	{
		$mes=0;
		$data=false;
	
		for ($i=0;12>$i;$i++)
		{
			if($i<9)
			{
				$mes='0'.($i+1);
			}
			else
			{
				$mes=$i+1;
			}
	
			if($this->obtieneDataPNSegundaCat($origen,$idCliente,$idSociedad,$mes,$mapper)!='0')
			{
				$data=true;
				break;
			}
		}
	
		return $data;
	}
	
	private function obtieneDataPNSegundaCat($origen,$idCliente,$idSociedad,$mes,$anio,$mapper,$idRut)
	{
		$valor="0";
	
		foreach ($mapper->obtieneResumenBoleteo($idCliente,$idSociedad,$mes,$anio,$idRut) as $i => $data)
		{
			if($origen=='1')
			{	
				if($data['ci07_ingsinretencion']!='0')
				{
					$valor=$data['ci07_ingsinretencion'];
				}
			}
			else
			{
				if($data['ci07_ingconretencion']!='0')
				{
					$valor=$data['ci07_ingconretencion'];
				}
			}
		}
	
		return $valor;
	}
	
	private function obtieneDataPrimeraCat14Bis($idCliente,$idSociedad,$mes,$anio,$mapper,$idRut)
	{
		$valor="0";	
	
		foreach ($mapper->obtieneResumenBoleteo($idCliente,$idSociedad,$mes,$anio,$idRut) as $i => $data)
		{
			if($data['ci07_ingsociedad']!='0')
			{
				$valor=$data['ci07_ingsociedad'];
			}
		}
	
		return $valor;
	}
	
	private function mesPPM($fecha)
	{
		$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		
		$date=explode('-',$fecha);
		
		$mes=$meses[intval($date[0])].' '.$date[1];
	
		return $mes;
	}

	private function obtieneMesPPMSistema()
	{
		$fecha=date('m').'-'.date('Y');
		 
		if(date('j')<=20)
		{			
			$fecha=str_pad((intval(date('m'))-1), 2, "0", STR_PAD_LEFT)."-".date('Y');
			
			if((date('n')-1)<1)
			{
				$fecha=str_pad((intval(date('m'))-1), 2, "0", STR_PAD_LEFT)."-".(intval(date('Y'))-1);
			}
		}
		
		return $fecha;
	}
	
}
