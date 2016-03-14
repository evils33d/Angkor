<?php
class ConceptoController extends Zend_Controller_Action 
{	
	protected $_config;		
	
	public function init() 
	{
		
		$bootstrap = $this->getInvokeArg ( 'bootstrap' );
		$this->_config = $bootstrap->getOptions ();
		
		$this->view->nombre_sitio = $this->_config ['nombre_sitio'];
		$this->view->skin = $this->_config ['skin'];
	}
	
	public function indexAction() 
	{
		$this->_helper->layout->setLayout ( 'layoutsistema' );
	}
	
	public function mantenedorconceptocobroAction() 
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorconceptocobro' );
	}
	
	public function ingresarAction()
	{
		
		$this->_helper->layout->setLayout ( 'layoutmantenedorconceptocobro' );
		
		$conceptMapper = new Application_Model_ConceptoMapper ();
		
		$response=array();
		
		if (! empty ( $_REQUEST ['nombreConcepto'] ) && 
			! empty ( $_REQUEST ['agrupable'] ) && 
			! empty ( $_REQUEST ['ingreso'] )) {
			
			if(!$conceptMapper->verificaConceptByNombre($_REQUEST ['nombreConcepto']))
			{
				$datosUsuario = array (
						"nombreConcepto" => $_REQUEST ['nombreConcepto'],
						"agrupable" => $_REQUEST ['agrupable'],
						"ingreso" => $_REQUEST ['ingreso']
				);
				
				$res = $conceptMapper->ingresarConcepto ( $datosUsuario );
				
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
				$data =array(
						$listadoConceptos[$i]['ci33_idconcepto']= $concept['ci33_idconcepto'],
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
	
	public function listarconceptosmasivosAction()
	{
		$this->_helper->layout->setLayout ( 'layoutmantenedorconceptocobro' );
	
		$userMapper = new Application_Model_ConceptoMapper();
	
		$listadoConceptos =array();
	
		if(sizeof($userMapper->listadoConceptosMasivos())>0)
		{
			foreach ( $userMapper->listadoConceptosMasivos() as $i => $concept )
			{
				$data =array(
						$listadoConceptos[$i]['ci33_idconcepto']= $concept['ci33_idconcepto'],
						$listadoConceptos[$i]['ci33_nombre']= $concept['ci33_nombre']
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
		$conceptMapper = new Application_Model_ConceptoMapper();					
		
		if(!empty($_REQUEST ['idConcept']))
		{
			foreach ($conceptMapper->datosConceptoById( $_REQUEST['idConcept'] ) as $i => $concept)
			{
				$data =array(
						$datosConcepto[$i]['ci33_nombre']= $concept['ci33_nombre'],
						$datosConcepto[$i]['ci33_tipo_agrupable']= $concept['ci33_tipo_agrupable'],
						$datosConcepto[$i]['ci33_tipo_ingreso']= $concept['ci33_tipo_ingreso']
				);
				
				
			}
		}
	
		$arreglo = array(
				"concepto" => $data
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

	
}

