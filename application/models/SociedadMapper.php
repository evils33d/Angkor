<?php
class Application_Model_SociedadMapper {
	
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
		mysql_query( $sql1 );
	}
	
	public function modificarSociedad($data)
	{
		$sql = "UPDATE 
				`ci40_sociedad` 
				SET 
				`ci40_valorimpuesto`='".$data['impuestoSociedad']."' 
				WHERE `ci40_idsociedad`='".$data['idSociedad']."';";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function buscarSociedadById($data)
	{
		$sql = "SELECT * FROM `ci40_sociedad` WHERE `ci40_idsociedad`='".$data['idSociedad']."';";	
		
		if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}   	
	}
   	
	public function valoSociedadoById($id)
	{
		$sql = "SELECT `ci40_valorimpuesto` FROM `ci40_sociedad` WHERE `ci40_idsociedad`='".$id."';";
	
		$datos = mysql_query ( $sql );
	
		$valorUf = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci40_valorimpuesto'] = $row ['ci40_valorimpuesto'];
				
			$valorUf [] = $entry;
		}
	
		return $valorUf;
	}
	
	public function obtieneSociedades()
	{
		$sql = "SELECT 
				  `ci40_idsociedad`,
				  `ci40_tiposociedad` 
				FROM
				  `ci40_sociedad`;";
	
		$res = mysql_query($sql);
	
		$listadoSociedades = array();
	
		while ($row = mysql_fetch_array($res))
		{
			if($row["ci40_tiposociedad"]!="natural")
			{
				$entry['ci40_idsociedad'] = $row['ci40_idsociedad'];
				$entry['ci40_tiposociedad'] = $row['ci40_tiposociedad'];
	
				$listadoSociedades[] = $entry;
				
			}
			
		}
	
		return $listadoSociedades;
	}

}
