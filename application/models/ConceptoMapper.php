<?php
class Application_Model_ConceptoMapper {
	
	protected $_dbTable;
	
	public function __construct() 
	{
		
		$bootstrap = Zend_Controller_Front::getInstance ()->getParam ( 'bootstrap' );
		$options = $bootstrap->getOptions ();
		
		$host = $options ['resources'] ['db'] ['params'] ['host'];
		$username = $options ['resources'] ['db'] ['params'] ['username'];
		$password = $options ['resources'] ['db'] ['params'] ['password'];
		$dbname = $options ['resources'] ['db'] ['params'] ['dbname'];
		
		$link = mysql_connect ( $host, $username, $password ) or die ( mysql_error () );
		mysql_select_db ( $dbname, $link ) or die ( mysql_error () );
		
		$sql1 = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
		
		mysql_query($sql1);
	}
	
	public function ingresarConcepto($data) 
	{
		$sql = "INSERT INTO ci33_conceptocobro (
				ci33_nombre,
				ci33_tipo_agrupable,
				ci33_tipo_ingreso,
				ci33_estado_concepto) 
				VALUES('" . $data ['nombreConcepto'] . "','" . $data ['agrupable'] . "','" . $data ['ingreso'] . "','1')";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
    public function verificaConceptById($id){
    	 
    	$sql="SELECT * FROM `ci33_conceptocobro` WHERE `ci33_idconcepto` = '".$id."' ";
    	 
    	if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}    	 
    }
    
    public function verificaConceptByNombre($nombre){
    
    	$sql="SELECT * FROM `ci33_conceptocobro` WHERE `ci33_nombre` = '".$nombre."' ";
    
    	if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
       
	public function listadoConceptos() {
		
		$sql = "SELECT 
				  ci33_idconcepto,
				  CONCAT(
				    UCASE(LEFT(`ci33_nombre`, 1)),
				    LCASE(SUBSTRING(`ci33_nombre`, 2))
				  ) AS `ci33_nombre`,
				  IF(ci33_tipo_agrupable = 1, 'si', 'no') AS ci33_tipo_agrupable,
				  IF(ci33_tipo_ingreso = 1, 'si', 'no') AS ci33_tipo_ingreso 
				FROM
				  ci33_conceptocobro 
				 WHERE ci33_idconcepto>3; ";			
		
		$listadoConceptos = mysql_query ( $sql );
		
		$conceptos = array ();
		
		while ( $row = mysql_fetch_array ( $listadoConceptos ) ) {
			$entry ['ci33_idconcepto'] = $row ['ci33_idconcepto'];
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
			$entry ['ci33_tipo_agrupable'] = $row ['ci33_tipo_agrupable'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			
			$conceptos [] = $entry;
		}
		
		return $conceptos;
	}	
	
	public function listadoConceptosMasivos() {
	
		$sql = "SELECT
				  ci33_idconcepto,
				  CONCAT(
				    UCASE(LEFT(`ci33_nombre`, 1)),
				    LCASE(SUBSTRING(`ci33_nombre`, 2))
				  ) AS `ci33_nombre`,
				  IF(ci33_tipo_agrupable = 1, 'si', 'no') AS ci33_tipo_agrupable,
				  IF(ci33_tipo_ingreso = 1, 'si', 'no') AS ci33_tipo_ingreso
				FROM
				  ci33_conceptocobro
				LIMIT 3; ";
	
		$listadoConceptos = mysql_query ( $sql );
	
		$conceptos = array ();
	
		while ( $row = mysql_fetch_array ( $listadoConceptos ) ) {
			$entry ['ci33_idconcepto'] = $row ['ci33_idconcepto'];
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
				
			$conceptos [] = $entry;
		}
	
		return $conceptos;
	}
		
	public function eliminarConcepto($idUsuario){
		
		$sql="DELETE FROM ci33_conceptocobro WHERE ci33_idconcepto='".$idUsuario."'";
		
		$res=mysql_query($sql);
		
		if($res){
			return true;
		}else{
			return false;
		}
		
	}
	
	public function datosConceptoById($id)
	{
		$sql = "SELECT 
				  * 
				FROM
				  ci33_conceptocobro 
				WHERE ci33_idconcepto = ' ". $id ." ';";
	
		$datos = mysql_query ( $sql );
	
		$datosConcepto = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
			$entry ['ci33_tipo_agrupable'] = $row ['ci33_tipo_agrupable'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			
			$datosConcepto [] = $entry;
		}
	
		return $datosConcepto;
	}
	
	public function modificarConcepto($data)
	{
		$sql = "UPDATE ci33_conceptocobro SET 				
				ci33_nombre='" . $data ['nombreConceptoEdit'] . "',						
				ci33_tipo_agrupable = '" . $data ['agrupableEdit'] . "',						
				ci33_tipo_ingreso = '" . $data ['ingresoEdit'] . "'			
				WHERE ci33_idconcepto = '" . $data ['idConcept'] . "'";
			
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	

}
