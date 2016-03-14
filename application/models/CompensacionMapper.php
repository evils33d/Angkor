<?php
class Application_Model_CompensacionMapper 
{
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
	
	
	public function obtieneEstadosCompensacion(){

		$sql = "SELECT 
				  ci36_idEstadoCompensacion,
				  ci36_nombreEstado 
				FROM
				  ci36_estadocompensacion ";
		
		$res = mysql_query($sql);
		
		$estados = array();
		while ( $row = mysql_fetch_array ( $res ) ) {
		
			$valor ['ci36_idEstadoCompensacion']=  $row ['ci36_idEstadoCompensacion'];
			$valor ['ci36_nombreEstado'] = $row ['ci36_nombreEstado'];
		
			$estados [] = $valor;
		}
		
		
		
		if($res){
			return $estados;
		}else{
			return false;
		}
		
		
	}
	

}


























