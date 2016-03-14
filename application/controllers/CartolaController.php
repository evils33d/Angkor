<?php
class CartolaController extends Zend_Controller_Action {
	protected $_config;
	protected $_rol;
	protected $_idusuario;
	protected $_tipoperfil;
	
	public function init() {
		$bootstrap = $this->getInvokeArg ( 'bootstrap' );
		$this->_config = $bootstrap->getOptions ();
		
		$this->view->nombre_sitio = $this->_config ['nombre_sitio'];
		$this->view->path_cartolas = $this->_config ['path_cartolas'];
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
	
	public function leecartolaexcelAction() 
	{		       
		  		$this->_helper->layout->disableLayout();
				/** PHPExcel_IOFactory */
		  		
		  		//AQUÍ HAGO EL LLAMADO A LA CLASE PARA LEER EL EXCEL
				require_once APPLICATION_PATH . '/../library/MisClases/ExcelReader/Classes/PHPExcel/IOFactory.php';
				
				//ESTE ES EL ARCHIVO SUBIDO AL SERVIDOR Y QUE SE DEBE LEER
				$inputFileName = 'cartolas/cartola.xlsx';
				
				//echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
				
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);		
				
				//echo '<hr />';
				
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$cabecera="<table>";
				$linea = "";
				
				foreach ($sheetData as $key => $data){
					
					if($key > 1){						
							$valor = "";
							foreach ($data as $key1 => $celda){									
										$valores = 	explode(";", $celda);								
										foreach ($valores as $key2 => $valorCelda){
											$valor .= "<td>".$valorCelda."</td>";
										}
							}						
							$linea .= "<tr>".$valor."</tr>";
				
					}
					
				}
				$cabecera .= $linea;
				$cabecera .= "</table>";
				$this->view->excel =  $cabecera;
		
		
	}

	public function cargacartolaAction()
	{
		$this->_helper->layout->setLayout('layoutcargacartola');

		
		if(!empty($_FILES)){
			
			$cuenta = $_REQUEST['numeroCuentaSelect'];
			
			$nombreDirectorio = $this->view->path_cartolas;
			$nombreFichero = $_FILES['archivo']['name'];
			
			$ext = explode('.',$nombreFichero);
			
			if($ext[1] == 'xls' || $ext[1] == 'xlsx' )
			{	
				$idUnico = time();
				$nombreFichero = $idUnico . "-" . $nombreFichero;
				$rutaCompleta = $nombreDirectorio.$nombreFichero;

				if(move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaCompleta)){

					$cartolaRes = new Application_Model_CartolaMapper ();
						
					$datos = array(
									'nombre_archivo' => $nombreFichero,
									'cuenta'         => $cuenta
					);
					
					$resultado = $cartolaRes->cargaDocumento($datos);
					
					if($resultado){
						
						$resCarga = $this->cargaexcel($rutaCompleta,$resultado);
						
						if($resCarga){
							$this->view->msg = array ('valor' => true, 
													  'mensaje' => "El archivo fue subido y cargado con &eacute;xito",
													  'id'      => $resultado);
						}else{
							$this->view->msg = array ('valor' => false, 'mensaje' => "Problemas con la Carga del detalle");
						}						
						
					}else{
						$this->view->msg = array ('valor' => false, 'mensaje' => "Problemas al ingresar documento a la BD ");
					}
					//			
					
				}else{
					$this->view->msg = array ('valor' => false, 'mensaje' => "Problemas a subir archivo.");
				}				
			}else{
				$this->view->msg = array ('valor' => false, 'mensaje' => "La extensi&oacute;n del archivo no es correcta.");
			}
			
		}
		
	}
	
	public function cargaexcel($fichero,$idCartola) 
	{	
		$cartolaRes = new Application_Model_CartolaMapper ();
	
		//AQUÍ HAGO EL LLAMADO A LA CLASE PARA LEER EL EXCEL
		require_once APPLICATION_PATH . '/../library/MisClases/ExcelReader/Classes/PHPExcel/IOFactory.php';
	
		//ESTE ES EL ARCHIVO SUBIDO AL SERVIDOR Y QUE SE DEBE LEER
		$inputFileName = $fichero;
	
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
		foreach ($sheetData as $key => $data){
				
			if($key > 2){				
				foreach ($data as $key1 => $fila){
					$valores = 	explode(";", $fila);
					
					$res = $cartolaRes->cargaLineaDetalle($idCartola,$valores);
					
					if(!$res){
						return false;
					}
					
				}
			}				
		}		
		return true;
	}
		
	public function detallecartolaAction()
	{
		$this->_helper->layout->setLayout('layoutdetallecartola');
		
		if(isset($_REQUEST['id_cartola'])){
			
		
			$cartolaRes = new Application_Model_CartolaMapper ();
			$detalle = $cartolaRes->verDetalle($_REQUEST['id_cartola']);
			
			$this->view->detalle = $detalle;
		}

	}

	public function listadocartolasAction()
	{
		$this->_helper->layout->setLayout('layoutlistadocartolas');
				
	
			$cartolasRes = new Application_Model_CartolaMapper ();
			$cartolas = $cartolasRes->obtenerCartolas();
				
			$this->view->cartolas = $cartolas;
	}
	
	
}

