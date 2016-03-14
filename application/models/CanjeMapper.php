<?php
class Application_Model_CanjeMapper {
	
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
			
	public function registrarCanje($data)
	{
		$sql="INSERT INTO `ci12_canje` (
			  `ci12_pagocheque`,
			  `lc01_idUsuario`,
			  `ci12_pagoelectronico`,
			  `ci12_numerodocumento`,
			  `ci12_fecha`
			  ) 
			VALUES
			  ('".$data['pagoCheque']."', 
			   '".$data['idUsuario']."', 
			   '".$data['pagoElectronico']."',
			   '".$data['numero']."',
			   CURDATE())";
		
		$res=mysql_query($sql);
		
		if($res)
		{
			return  true;
		}
		else 
		{
			return false;
		}		
	}
	
	public function registraDetalleCanjeHonorario($idCobro,$idCanje)
	{
	$sql="INSERT INTO `ci60_detallecanjehonorario` (
			  `ci06_idhonorario`,
			  `ci12_idcanje`
			) 
			VALUES
			  (
			'".$idCobro."',
			'".$idCanje."')";
		
		$res=mysql_query($sql);
		
		if($res)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}
	
	public function registraDetalleCanjeIndividual($idCobro,$idCanje)
	{
		$sql="INSERT INTO `ci59_detallecanjeindividual` (
			  `ci05_idcobroindividual`,
			  `ci12_idcanje`
			) 
			VALUES
			  (
			'".$idCobro."',
			'".$idCanje."')";
		
		$res=mysql_query($sql);
		
		if($res)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}
	
	public function registraDetalleCanjeMasivo($idCobro,$idCanje)
	{
		$sql="INSERT INTO `ci61_detallecanjemasivo` (
			  `ci07_idcobromasivo`,
			  `ci12_idcanje`
			)
			VALUES
			  (
			'".$idCobro."',
			'".$idCanje."')";
		
		$res=mysql_query($sql);
		
		if($res)
		{
			return  true;
		}
		else
		{
			return false;
		}
	}
	
	public function verificaNumeroDocumento($numero)
	{
		$sql="SELECT 
			  * 
			FROM
			  `ci12_canje` 
			WHERE `ci12_numerodocumento` = '".$numero."';";
			
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public function verificarIdCobro($idCobro)
	{
		$sql="SELECT 
			  c.`ci12_idcanje` 
			FROM
			  `ci12_canje` c 
			  INNER JOIN `ci59_detallecanjeindividual` dci 
			    ON c.`ci12_idcanje` = dci.`ci12_idcanje` 
			WHERE dci.`ci05_idcobroindividual` = '".$idCobro."' 
			UNION
			SELECT 
			  c.`ci12_idcanje` 
			FROM
			  `ci12_canje` c 
			  INNER JOIN `ci60_detallecanjehonorario` dch 
			    ON c.`ci12_idcanje` = dch.`ci12_idcanje` 
			WHERE dch.`ci06_idhonorario` ='".$idCobro."'
			UNION
			SELECT 
			c.`ci12_idcanje`
			FROM
			  `ci12_canje` c 
			  INNER JOIN `ci61_detallecanjemasivo` dcm 
			    ON c.`ci12_idcanje` = dcm.`ci12_idcanje` 
			WHERE dcm.`ci07_idcobromasivo` = '".$idCobro."';";
			
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function obtenerDatosPagoCanjeIndividual($idCobro)
	{
		$sql="
			  SELECT 
			  `lc01_usuario`.`lc01_nombreUsuario`,
			  `ci12_canje`.`ci12_pagocheque`,
			  `ci12_canje`.`ci12_pagoelectronico`,
			  `ci12_canje`.`ci12_numerodocumento`,
			  DATE_FORMAT(
			    `ci12_canje`.`ci12_fecha`,
			    '%d-%m-%Y'
			  ) AS ci_fecha 
			FROM
			  `ci12_canje` 
			  INNER JOIN `ci59_detallecanjeindividual` 
			    ON `ci12_canje`.`ci12_idcanje` = `ci59_detallecanjeindividual`.`ci12_idcanje` 
			  INNER JOIN `ci05_cobroindividual` 
			    ON `ci59_detallecanjeindividual`.`ci05_idcobroindividual` = `ci05_cobroindividual`.`ci05_idcobroindividual` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci12_canje`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci05_cobroindividual`.`ci05_idcobroindividual` = '".$idCobro."';";
		
		
		$dataCanje=array();
		
		$res=mysql_query($sql);
		
		while($row=mysql_fetch_array($res))
		{
			$entry ['ci12_pagocheque'] = $row ['ci12_pagocheque'];
			$entry ['ci12_pagoelectronico'] = $row ['ci12_pagoelectronico'];
			$entry ['ci12_numerodocumento'] = $row ['ci12_numerodocumento'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['ci_fecha'] = $row ['ci_fecha'];
			
				
			$dataCanje [] = $entry;
		}		
		
		return $dataCanje;
	}
	
	public function obtenerDatosPagoCanjeHonorario($idCobro)
	{
		$sql="SELECT 
			  `lc01_usuario`.`lc01_nombreUsuario`,
			  `ci12_canje`.`ci12_pagocheque`,
			  `ci12_canje`.`ci12_pagoelectronico`,
			  `ci12_canje`.`ci12_numerodocumento`,
			  DATE_FORMAT(
			    `ci12_canje`.`ci12_fecha`,
			    '%d-%m-%Y'
			  ) AS ci_fecha 
			FROM
			  `ci12_canje` 
			  INNER JOIN `ci60_detallecanjehonorario` 
			    ON `ci12_canje`.`ci12_idcanje` = `ci60_detallecanjehonorario`.`ci12_idcanje` 
			  INNER JOIN `ci06_honorario` 
			    ON `ci60_detallecanjehonorario`.`ci06_idhonorario` =`ci06_honorario`.`ci06_idhonorario` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci12_canje`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario`
			  WHERE `ci06_honorario`.`ci06_idhonorario`='".$idCobro."';";
	
	
		$dataCanje=array();
	
		$res=mysql_query($sql);
	
		while($row=mysql_fetch_array($res))
		{
			$entry ['ci12_pagocheque'] = $row ['ci12_pagocheque'];
			$entry ['ci12_pagoelectronico'] = $row ['ci12_pagoelectronico'];
			$entry ['ci12_numerodocumento'] = $row ['ci12_numerodocumento'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['ci_fecha'] = $row ['ci_fecha'];
	
			$dataCanje [] = $entry;
		}
	
		return $dataCanje;
	}
	
	public function obtenerDatosPagoCanjeMasivo($idCobro)
	{
		$sql="SELECT 
			  `lc01_usuario`.`lc01_nombreUsuario`,
			  `ci12_canje`.`ci12_pagocheque`,
			  `ci12_canje`.`ci12_pagoelectronico`,
			  `ci12_canje`.`ci12_numerodocumento`,
			  DATE_FORMAT(
			    `ci12_canje`.`ci12_fecha`,
			    '%d-%m-%Y'
			  ) AS ci_fecha  
			FROM
			  `ci12_canje` 
			  INNER JOIN `ci61_detallecanjemasivo`
			    ON `ci12_canje`.`ci12_idcanje` = `ci61_detallecanjemasivo`.`ci12_idcanje`
			  INNER JOIN `ci07_cobromasivo`
			    ON `ci61_detallecanjemasivo`.`ci07_idcobromasivo` = `ci07_cobromasivo`.`ci07_idcobromasivo`
			  INNER JOIN `lc01_usuario` 
			    ON `ci12_canje`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario`
			  WHERE `ci07_cobromasivo`.`ci07_idcobromasivo`='".$idCobro."';";
		
	
	
		$dataCanje=array();
	
		$res=mysql_query($sql);
	
		while($row=mysql_fetch_array($res))
		{
			$entry ['ci12_pagocheque'] = $row ['ci12_pagocheque'];
			$entry ['ci12_pagoelectronico'] = $row ['ci12_pagoelectronico'];
			$entry ['ci12_numerodocumento'] = $row ['ci12_numerodocumento'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
			$entry ['ci_fecha'] = $row ['ci_fecha'];
			
			$dataCanje [] = $entry;
		}
	
		return $dataCanje;
	}
}
