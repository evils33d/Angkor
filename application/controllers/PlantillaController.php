<?php
class PlantillaController extends Zend_Controller_Action 
{	
	protected $_config;		
	
	public function init() {
		
		$bootstrap = $this->getInvokeArg ( 'bootstrap' );
		$this->_config = $bootstrap->getOptions ();
		
		$this->view->nombre_sitio = $this->_config ['nombre_sitio'];
		$this->view->skin = $this->_config ['skin'];
	}
	
	public function indexAction() {
		$this->_helper->layout->setLayout ( 'layoutsistema' );
	}
	
	public function mantenedorplantillacorreoAction() {
		$this->_helper->layout->setLayout ( 'layoutmantenedorplantillacorreo' );
	}
	
	public function ingresarAction()
	{		
		$this->_helper->layout->setLayout ( 'layoutmantenedorplantillacorreo' );
		
		$plantillaMapper = new Application_Model_PlantillaMapper ();
		
		$response=array();
		
		if (! empty ( $_REQUEST ['nombrePlantilla'] ) && 
			! empty ( $_REQUEST ['textoPlantilla'] ) ) {
			
			if(!$plantillaMapper->verificaConceptByNombre($_REQUEST ['nombrePlantilla']))
			{
				$datosPlantilla = array (
						"nombrePlantilla" => $_REQUEST ['nombrePlantilla'],
						"textoPlantilla" => $_REQUEST ['textoPlantilla']
				);
				
				$res = $plantillaMapper->ingresarPlantilla ( $datosPlantilla );
				
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
		
		$arreglo =array(
				"registro" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);		
	}
	
	/*
	
	public function eliminarAction()
	{
		
		$this->_helper->layout->setLayout ( 'layoutmantenedorconceptocobro' );
	
		$conceptMapper = new Application_Model_ConceptoMapper ();
		
		$response=array();
		
		if (!empty($_REQUEST ['idConcepto'])) {
			
			if($conceptMapper->verificaConceptById($_REQUEST ['idConcepto']))
			{
				if($conceptMapper->eliminarConcepto($_REQUEST ['idConcepto']))
				{
					$response[0]='1';
				}
			}
			else
			{
				$response[0]='2';
			}

		}
		
		$arreglo =array(
				"eliminar" => $response
		);		
		
		return $this->_helper->json->sendJson($arreglo);
		
	}
	
	public function listarAction()
	{		
		$this->_helper->layout->setLayout ( 'layoutmantenedorconceptocobro' );
		
		$userMapper = new Application_Model_ConceptoMapper();
		
		$listadoConceptos =array();
		
		if(sizeof($userMapper->listadoConceptos())>0)
		{
			foreach ( $userMapper->listadoConceptos() as $i => $concept )
			{
				$data =array($listadoConceptos[$i]['ci33_idconcepto']= $concept['ci33_idconcepto'],
						$listadoConceptos[$i]['ci33_nombre']= $concept['ci33_nombre'],
						$listadoConceptos[$i]['ci33_tipo_agrupable']= $concept['ci33_tipo_agrupable'],
						$listadoConceptos[$i]['ci33_tipo_ingreso']= $concept['ci33_tipo_ingreso']
				);
					
				$listadoConcetp[] = $data;
			}
		}
		else
		{
			$listadoConcetp = [];
		}	
		
		$arreglo =array(				
				"data" => $listadoConcetp
		);
			
		return $this->_helper->json->sendJson($arreglo);
		
	}
	
	public function obtenerAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorconceptocobro' );
	
		$conceptMapper = new Application_Model_ConceptoMapper();
	
		$datosConcepto=array();
	
		if(!empty($_REQUEST ['idConcept']))
		{
			foreach ($conceptMapper->datosConceptoById($_REQUEST ['idConcept']) as $i => $concept)
			{
				$data =array(
						$datosUsuario[$i]['ci33_nombre']= $concept['ci33_nombre'],
						$datosUsuario[$i]['ci33_tipo_agrupable']= $concept['ci33_tipo_agrupable'],
						$datosUsuario[$i]['ci33_tipo_ingreso']= $concept['ci33_tipo_ingreso']
				);
	
			}
		}
	
		$arreglo =array(
				"concepto" => $data,
		);
	
		return $this->_helper->json->sendJson($arreglo);
	
	}
	
	public function modificarAction()
	{	
		
		$this->_helper->layout->setLayout ( 'layoutmantenedorconceptocobro' );	
		$conceptMapper = new Application_Model_ConceptoMapper();
		
		$response =array();
		
		if(! empty ($_REQUEST['nombreConcptoEdit']) && 
		   ! empty ($_REQUEST['agrupableEdit']) && 
		   ! empty ($_REQUEST['ingresoEdit']) && 
		   ! empty ($_REQUEST['idConcept']) ){
			
			
				$datosConceptoEdit = array (
						"nombreConceptoEdit" => $_REQUEST ['nombreConcptoEdit'],
						"agrupableEdit" => $_REQUEST ['agrupableEdit'],
						"ingresoEdit" => $_REQUEST ['ingresoEdit'],
						"idConcept" => $_REQUEST ['idConcept']
						
				);
					
				$res = $conceptMapper -> modificarConcepto ( $datosConceptoEdit );
				
				if($res)
				{
					$response[0]='1';
				}
				else
				{
					$response[0]='2';
				}
			
		}
		
		$arreglo =array(
				"edicion" => $response
		);
		
		return $this->_helper->json->sendJson($arreglo);
		
	}
*/
	
}

