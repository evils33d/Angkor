<?php
class Application_Model_ValoresMapper {
	
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
	}
	
	//*** funciones para el mantenedor de UF ***//
	
	public function guardarUf($data) 
	{
		$sql = "INSERT INTO ci43_uf (ci43_anio,	ci43_mes, ci43_valor) 
				VALUES ('" . $data ['anioUf'] . "','" . $data ['mesUf'] . "','" . $data ['valorUf'] . "')";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function modificarUf($data , $idUf)
	{
		$sql = "UPDATE ci43_uf 
				SET 
				ci43_anio='".$data['anioUf']."', 
				ci43_mes='".$data['mesUf']."', 
				ci43_valor='".$data['valorUf']."'
				WHERE 
				ci43_iduf='". $idUf ."';";				
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function valoUfoByAnioMes($data)
	{
		$sql = "SELECT ci43_iduf,ci43_valor FROM ci43_uf WHERE ci43_anio='".$data['anioUf']."' and  ci43_mes='".$data['mesUf']."'";
	
		$datos = mysql_query ( $sql );
	
		$valorUf = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci43_valor'] = $row ['ci43_valor'];
			$entry ['ci43_iduf'] = $row ['ci43_iduf'];
			
			$valorUf [] = $entry;
		}
	
		return $valorUf;
	}

	public function valorUf($data)
	{
		$sql = "SELECT ci43_iduf,ci43_valor FROM ci43_uf WHERE ci43_anio='".$data['anioUf']."' and  ci43_mes='".$data['mesUf']."'";
	
		$datos = mysql_query ( $sql );
	
		$valorUf = "";
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$valorUf = $row ['ci43_valor'];		
		}
	
		return $valorUf;
	}
		
	public function obtenerIdUf($data)
	{
		$sql = "SELECT ci43_iduf FROM ci43_uf WHERE ci43_anio='".$data['anioUf']."' and  ci43_mes='".$data['mesUf']."';";
		
		$datos = mysql_query ( $sql );
		
		$idUf = "";
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{				
			$idUf  = $row ['ci43_iduf'];
		}
		
		return $idUf;
	}
	
	public function buscarAnioMesUf($data)
	{
		$sql = "SELECT * FROM ci43_uf WHERE ci43_anio='".$data['anioUf']."' and  ci43_mes='".$data['mesUf']."';";	
		
		if(mysql_num_rows(mysql_query($sql))>0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}   	
	}
   
	public function obtenerUfActual()
	{
		$sql="SELECT 
			  `ci43_iduf`,
			  `ci43_valor` 
			FROM
			  `ci43_uf` 
			WHERE `ci43_mes` =  DATE_FORMAT(NOW(),'%m') 
			  AND `ci43_anio` =  DATE_FORMAT(NOW(),'%Y');";
		
		$datos = mysql_query ( $sql );
		
		$ValoresUf = array();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$ValorUf ['ci43_iduf'] = $row ['ci43_iduf'];
			$ValorUf ['ci43_valor'] = $row ['ci43_valor'];
			
			$ValoresUf[]=$ValorUf;
		}
		
		return $ValoresUf;
		
	}
		
	//*** funciones para el mantenedor de TASAS ***//
	
	public function guardarTasa($data)
	{
		$sql = "INSERT INTO ci37_tasa (ci37_anio, ci37_mes, ci37_valor)
				VALUES ('" . $data ['anioTasa'] . "','" . $data ['mesTasa'] . "','" . $data ['valorTasa'] . "')";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function buscarAnioMesTasa($data)
	{
		$sql = "SELECT * FROM ci37_tasa WHERE ci37_anio='".$data ['anioTasa']."' and ci37_mes='".$data ['mesTasa']."';";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	
	}

	public function obtenerIdTasa($data)
	{
		$sql = "SELECT ci37_idtasa FROM ci37_tasa WHERE ci37_anio='".$data ['anioTasa']."' and ci37_mes='".$data ['mesTasa']."';";
	
		$datos = mysql_query ( $sql );
	
		$idTasa = "";
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$idTasa  = $row ['ci37_idtasa'];
		}
	
		return $idTasa;
	}
	
	public function modificarTasa($data , $idTasa)
	{
		$sql = "UPDATE ci37_tasa
				SET
				ci37_valor='".$data['valorTasa']."'
				WHERE
				ci37_idtasa='". $idTasa ."';";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function valorTasaByAnioMes($data)
	{
		$sql = "SELECT 
				  ci37_idtasa,
				  ci37_valor 
				FROM
				  ci37_tasa 
				WHERE ci37_anio = '".$data['anioTasa']."' 
				  AND ci37_mes = '".$data['mesTasa']."';";
	
		$datos = mysql_query ( $sql );
	
		$valorTasa = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci37_valor'] = $row ['ci37_valor'];
			$entry ['ci37_idtasa'] = $row ['ci37_idtasa'];
				
			$valorTasa [] = $entry;
		}
	
		return $valorTasa;
	}
	
	public function valorTasaActual()
	{
		$sql = "SELECT `ci37_idtasa`,`ci37_valor` FROM `ci37_tasa`  WHERE `ci37_anio`= DATE_FORMAT(NOW(),'%Y') AND `ci37_mes`=  DATE_FORMAT(NOW(),'%m');";
		
		$datos = mysql_query ( $sql );
		
		$valorUf = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci37_idtasa'] = $row ['ci37_idtasa'];
			$entry ['ci37_valor'] = $row ['ci37_valor'];
		
			$valorUf [] = $entry;
		}
		
		return $valorUf;
	}
	
	public function listarTasas()
	{
		$sql="SELECT 
			    `ci37_idtasa`,
			    `ci37_valor` 
			FROM
			    `ci37_tasa`;";
		
		$datos = mysql_query ( $sql );
		
		$valorUf = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci37_idtasa'] = $row ['ci37_idtasa'];
			$entry ['ci37_valor'] = $row ['ci37_valor'];
		
			$valorUf [] = $entry;
		}
		
		return $valorUf;
		
	}
	
	public function verificaValorTasa($data)
	{
		$sql="SELECT 
			  `ci37_valor` 
			FROM
			  `ci37_tasa` 
			WHERE `ci37_anio` = '".$data['anio']."'
			  AND `ci37_mes` = '".$data['mes']."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//*** funciones para el mantenedor de RETENCIONES ***//      
	
	public function guardarRetencion($data)
	{
		$sql = "INSERT INTO ci39_retencion (ci39_anio,	ci39_mes, ci39_valor)
				VALUES ('" . $data ['anioRetencion'] . "','" . $data ['mesRetencion'] . "','" . $data ['valorRetencion'] . "')";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function modificarRetencion($data , $idRetencion)
	{
		$sql = "UPDATE ci39_retencion 
				SET 
				ci39_anio='".$data['anioRetencion']."', 
				ci39_mes='".$data['mesRetencion']."',
				ci39_valor='".$data['valorRetencion']."'
				WHERE
				ci39_idretencion='". $idRetencion ."';";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function valoRetencionoByAnioMes($data)
	{
		$sql = "SELECT ci39_valor FROM ci39_retencion WHERE ci39_anio='".$data['anioRetencion']."' and  ci39_mes='".$data['mesRetencion']."'";
	
		$datos = mysql_query ( $sql );
	
		$valorRetencion = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci39_valor'] = $row ['ci39_valor'];
				
			$valorRetencion [] = $entry;
		}
	
		return $valorRetencion;
	}
	
	public function valorRetencionActual()
	{
		$sql = "SELECT `ci39_idretencion`,`ci39_valor` FROM `ci39_retencion` WHERE `ci39_anio`= DATE_FORMAT(NOW(),'%Y') AND `ci39_mes`= DATE_FORMAT(NOW(),'%m');";
		
		$datos = mysql_query ( $sql );
		
		$valorRetencion = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci39_idretencion'] = $row ['ci39_idretencion'];
			$entry ['ci39_valor'] = $row ['ci39_valor'];
		
			$valorRetencion [] = $entry;
		}
		
		return $valorRetencion;
	}
	
	public function obtenerIdRetencion($data)
	{
		$sql = "SELECT ci39_idretencion FROM ci39_retencion WHERE ci39_anio='".$data['anioRetencion']."' and  ci39_mes='".$data['mesRetencion']."';";
	
		$datos = mysql_query ( $sql );
	
		$idRetencion = "";
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$idRetencion  = $row ['ci39_idretencion'];
		}
	
		return $idRetencion;
	}
	
	public function buscarAnioMesRetencion($data)
	{
		$sql = "SELECT * FROM ci39_retencion WHERE ci39_anio='".$data['anioRetencion']."' and  ci39_mes='".$data['mesRetencion']."';";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function listarRetenciones()
	{
		$sql="SELECT 
			  `ci39_idretencion`,
			  `ci39_valor` 
			FROM
			  `ci39_retencion`;";
		
		$datos = mysql_query ( $sql );
		
		$valorRetencion = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci39_idretencion'] = $row ['ci39_idretencion'];
			$entry ['ci39_valor'] = $row ['ci39_valor'];
		
			$valorRetencion [] = $entry;
		}
		
		return $valorRetencion;		
	}
	
	public function verificaValorRetencion($data)
	{
		$sql="SELECT 
			    `ci39_valor` 
			  FROM
			    `ci39_retencion` 
			  WHERE `ci39_anio` = '".$data['anio']."'
			    AND `ci39_mes` = '".$data['mes']."';";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
