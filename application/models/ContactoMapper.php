<?php

class Application_Model_ContactoMapper
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
        
	public function ingresarContacto($data)
	{
		$sql = "INSERT INTO 
				`ci09_contactoasociado`
				(`ci03_idcliente`,`ci09_nombre`,`ci09_email`,`ci09_telefono`,ci09_celular,ci09_cobro,ci09_boleteo) 
				VALUES
				(
				'".$data['idCliente']."',
				'".$data['nombreContacto']."',
				'".$data['emailContacto']."',
				'".$data['telefonoContacto']."',
				'".$data['celularContacto']."',
				'".$data['cobro']."',
				'".$data['boleteo']."');";
	
		$res = mysql_query( $sql );
	
		if ($res) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	public function listadoContactocByIdCliente($id) 
	{	
		  $sql = "SELECT 
				  ca.`ci09_idcontacto`,
				  ca.`ci09_nombre`,
				  ca.`ci09_telefono`,
				  ca.`ci09_celular`,
				  ca.`ci09_email`,
				  IF(ca.ci09_boleteo = 1, 'si', 'no') AS ci09_boleteo,
				  IF(ca.`ci09_cobro` = 1, 'si', 'no') AS `ci09_cobro` 
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `ci09_contactoasociado` ca 
				    ON c.`ci03_idcliente` = ca.`ci03_idcliente` 
				WHERE c.`ci03_idcliente` = '".$id."' ";
	    
		$listadoContactos = mysql_query ( $sql );
	    
		$contactos = array ();
	    
		while ( $row = mysql_fetch_array ( $listadoContactos ) ) {
			$entry ['ci09_idcontacto'] = $row ['ci09_idcontacto'];
			$entry ['ci09_nombre'] = $row ['ci09_nombre'];
			$entry ['ci09_telefono'] = $row ['ci09_telefono'];
			$entry ['ci09_celular'] = $row ['ci09_celular'];
			$entry ['ci09_email'] = $row ['ci09_email'];
			$entry ['ci09_boleteo'] = $row ['ci09_boleteo'];
			$entry ['ci09_cobro'] = $row ['ci09_cobro'];
				
			$contactos [] = $entry;
		}
	
		return $contactos;
	}
	
	public function obtenerContactocByIdContacto($id)
	{
		  $sql = "SELECT 
				  CONCAT(
				    UCASE(LEFT(ca.`ci09_nombre`, 1)),
				    LCASE(SUBSTRING(ca.`ci09_nombre`, 2))
				  ) AS ci09_nombre,
				  ca.`ci09_telefono`,
				  ca.`ci09_celular`,
				  ca.`ci09_email`,
				  ca.`ci09_boleteo`,
				  ca.`ci09_cobro` 
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `ci09_contactoasociado` ca 
				    ON c.`ci03_idcliente` = ca.`ci03_idcliente` 
				WHERE ca.`ci09_idcontacto` = '".$id."' ";
		 
		$listadoContactos = mysql_query ( $sql );
		 
		$contactos = array ();
		 
		while ( $row = mysql_fetch_array ( $listadoContactos ) ) 
		{
			$entry ['ci09_nombre'] = $row ['ci09_nombre'];
			$entry ['ci09_telefono'] = $row ['ci09_telefono'];
			$entry ['ci09_celular'] = $row ['ci09_celular'];
			$entry ['ci09_email'] = $row ['ci09_email'];
			$entry ['ci09_boleteo'] = $row ['ci09_boleteo'];
			$entry ['ci09_cobro'] = $row ['ci09_cobro'];
	
			$contactos [] = $entry;
		}
	
		return $contactos;
	}
	
	public function verificarEmailByEmail($email)
	{
		$sql="SELECT `ci09_email` FROM `ci09_contactoasociado` WHERE `ci09_email`='".$email."';";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
	
	public function eliminarContactoAsociado($idContacto)
	{
	
		$sql="DELETE FROM `ci09_contactoasociado` WHERE `ci09_idcontacto`='".$idContacto."' ;";
	
		$res=mysql_query($sql);
	
		if($res){
			return true;
		}else{
			return false;
		}
	
	}
	
	public function modificarContacto($data)
	{
		$sql = "UPDATE 
				`ci09_contactoasociado` 
				SET 
				`ci09_nombre`='".$data['nombreContacto']."',
				`ci09_email`='".$data['emailContacto']."',
				`ci09_telefono`='".$data['telefonoContacto']."',
				`ci09_celular`='".$data['celularContacto']."',
				`ci09_cobro`='".$data['cobroContacto']."',
				`ci09_boleteo`='".$data['boleteoContacto']."'
				WHERE `ci09_idcontacto`='".$data['idContacto']."';";
			
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}	
	
	public function obtieneEmailAsociado($idCliente)
	{		
		$sql = "SELECT 
				  ca.`ci09_email`,
				  ca.`ci09_nombre` 
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `ci09_contactoasociado` ca 
				    ON c.`ci03_idcliente` = ca.`ci03_idcliente` 
				WHERE c.`ci03_idcliente` = '".$idCliente."' AND ca.`ci09_cobro`='1';";
			
		$listadoEmail = mysql_query ( $sql );
			
		$email = array ();
			
		while ( $row = mysql_fetch_array ( $listadoEmail ) ) {
			$entry ['ci09_email'] = $row ['ci09_email'];
			$entry ['ci09_nombre'] = $row ['ci09_nombre'];
	
			$email [] = $entry;
		}
	
		return $email;
	}
	
	public function obtieneEmailAsociadoBoleteo($idCliente)
	{
		$sql = "SELECT 
			  ca.`ci09_email`,
			  ca.`ci09_nombre` 
			FROM
			  `ci03_cliente` c 
			  INNER JOIN `ci09_contactoasociado` ca 
			    ON c.`ci03_idcliente` = ca.`ci03_idcliente` 
			WHERE c.`ci03_idcliente` = '".$idCliente."' 
			  AND ca.`ci09_boleteo` = '1' ;";
			
		$listadoEmail = mysql_query ( $sql );
			
		$email = array ();
			
		while ( $row = mysql_fetch_array ( $listadoEmail ) ) {
			$entry ['ci09_email'] = $row ['ci09_email'];
			$entry ['ci09_nombre'] = $row ['ci09_nombre'];
	
			$email [] = $entry;
		}
	
		return $email;
	}
	
	
	
}
