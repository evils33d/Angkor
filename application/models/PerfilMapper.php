<?php

class Application_Model_PerfilMapper
{
    protected $_dbTable;
 
	public function __construct()
	{
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$options = $bootstrap->getOptions();
		
		$host = $options['resources']['db']['params']['host'];
		$username = $options['resources']['db']['params']['username'];
		$password = $options['resources']['db']['params']['password'];
		$dbname = $options['resources']['db']['params']['dbname'];
		
		$link = mysql_connect($host,$username,$password) or die(mysql_error());
		mysql_select_db($dbname,$link) or die(mysql_error());
		
		$sql1 = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
		
		mysql_query($sql1);
	}
    
    public function obtienePerfilById($id)
    {
    	$sql = "SELECT 
				  lc02_idPerfil,
				  lc02_nombrePerfil,
				  lc02_fechaCreacionPerfil,
				  lc02_estadoPerfil 
				FROM
				  lc02_perfil 
				WHERE lc02_idPerfil = '".$id."';";    	
    	
    	$resultado = mysql_query($sql);
    	
    	$entries = array();
    	
    	while ($row = mysql_fetch_array($resultado))
    	{
    		$entry["lc02_idPerfil"] = $row['lc02_idPerfil'];
    		$entry["lc02_nombrePerfil"] = $row['lc02_nombrePerfil'];
    		$entry["lc02_fechaCreacionPerfil"] = $row['lc02_fechaCreacionPerfil'];
    		$entry["lc02_estadoPerfil"] = $row['lc02_estadoPerfil'];
    		
    		$entries[] = $entry;
    	}
    	
    	return $entries;
    }
    
	public function obtienePerfilByIdUsuario($idUsuario)
	{
		$sql="SELECT 
			  `lc02_perfil`.`lc02_idPerfil`,
			  `lc02_perfil`.`lc02_nombrePerfil` 
			FROM
			  `lc01_usuario` 
			  INNER JOIN `lc02_perfil` 
			    ON `lc01_usuario`.`lc02_idPerfil` = `lc02_perfil`.`lc02_idPerfil` 
			   WHERE `lc01_usuario`.`lc01_idUsuario`='".$idUsuario."';";
		
		$resultado = mysql_query($sql);
		 
		$entries = "";
		 
		while ($row = mysql_fetch_array($resultado))
		{			
			$entries = $row['lc02_nombrePerfil'];
		}
		 
		return $entries;
	}
    
    public function obtienePerfiles()
    {
    	$sql = "SELECT lc02_idPerfil,
		    	lc02_nombrePerfil,
		    	lc02_fechaCreacionPerfil,
		    	lc02_estadoPerfil
		    	FROM lc02_perfil ";
    
    
    	$resultado = mysql_query($sql);
    
    	$entries = array();
    
    	while ($row = mysql_fetch_array($resultado))
    	{
    		$entry["lc02_idPerfil"] = $row['lc02_idPerfil'];
    		$entry["lc02_nombrePerfil"] = $row['lc02_nombrePerfil'];
    		$entry["lc02_fechaCreacionPerfil"] = $row['lc02_fechaCreacionPerfil'];
    		$entry["lc02_estadoPerfil"] = $row['lc02_estadoPerfil'];
    
    		$entries[] = $entry;
    	}
    
    	return $entries;
    }

}
