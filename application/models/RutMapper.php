<?php

class Application_Model_RutMapper
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
		
		mysql_query( $sql1 );
	}
        
	/** Funciones Rut **/
	
	public function ingresarRut($data)
	{
		$sql = "INSERT INTO `ci04_rut` (
				  `ci03_idcliente`,
				  `ci40_idsociedad`,
				  `ci04_razonsocial`,
				  `ci04_rut`,
				  `ci04_tipopersona`,
				  `ci04_numerosociedad`,
				  `ci04_valormensualidad`,
				  `ci04_valorservicios`,
				  `ci04_iva`,
				  `ci04_f29`,
				  `ci04_renta`,
				  `ci04_previred`,				
				  `ci04_empresarial`,
				  `ci04_independiente`,
				  `ci04_nanas`,
				  `ci04_otro`,
				  `ci04_socio`,
				  `ci04_trabajadores`,				
				  `ci04_estadodisponibilidad`			
				)
				VALUES
			   ('" . $data ['idCliente'] . "',
				'" . $data ['idSociedad'] . "',
				'" . $data ['razonSocial'] . "',
				'" . $data ['rut'] . "',
				'" . $data ['tipoPersona'] . "',
				'" . $data ['numeroSociedad'] . "',
				'" . $data ['valorMensualidad'] . "',
				'" . $data ['valorServicios'] . "',
				'" . $data ['iva'] . "',
				'" . $data ['f29'] . "',
				'" . $data ['renta'] . "',
				'" . $data ['previred'] . "',						
				'" . $data ['empresarial'] . "',
				'" . $data ['independiente'] . "',
				'" . $data ['nanas'] . "',
				'" . $data ['otro'] . "',
				'" . $data ['socio'] . "',
				'" . $data ['trabajadores'] . "',						
				'1'
				);";
		
	
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public function verificaExisteByRut($rut)
	{
		if($rut!='1-9')
		{
			$sql="SELECT * 
					FROM ci04_rut WHERE ci04_rut='".$rut."' 
					AND ci04_estadodisponibilidad = 1;";
			
			if(mysql_num_rows(mysql_query($sql))>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}
	
	public function verificaNumeroSociedad($numero)
	{
		if($numero!='')
		{
			$sql="SELECT `ci04_numerosociedad` FROM  `ci04_rut`  WHERE  `ci04_numerosociedad`='".$numero."';";
		
			if(mysql_num_rows(mysql_query($sql))>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	public function obtenerDatosRutByID($idRut)
	{
	   $sql = "SELECT 
			  c.`ci03_nombre`,
			  u.`lc01_idUsuario`,
			  r.`ci40_idsociedad`,
			  r.`ci04_razonsocial`,
			  r.`ci04_rut`,
			  r.`ci04_tipopersona`,
			  IF(
			    r.`ci04_numerosociedad` = 0,
			    'PN',
			    r.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  r.`ci04_valormensualidad`,
			  r.`ci04_valorservicios`,
			  r.`ci04_iva`,
			  r.`ci04_f29`,
			  r.`ci04_renta`,
			  r.`ci04_previred`,
			  r.`ci04_empresarial`,
			  r.`ci04_independiente`,
			  r.`ci04_nanas`,
			  r.`ci04_otro`,
			  r.`ci04_socio`,
			  r.`ci04_trabajadores`
			FROM
			  `ci04_rut` r 
			  INNER JOIN `ci03_cliente` c 
			    ON r.`ci03_idcliente` = c.`ci03_idcliente` 
			  INNER JOIN `lc01_usuario` u 
			    ON c.`lc01_idusuario` = u.`lc01_idUsuario` 
			WHERE r.`ci04_idrrut` = '".$idRut."' 
			  AND r.ci04_estadodisponibilidad = '1'; ";
		
		$datos = mysql_query ( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{			
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['lc01_idUsuario'] = $row ['lc01_idUsuario'];
			$entry ['ci40_idsociedad'] = $row ['ci40_idsociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci04_tipopersona'] = $row ['ci04_tipopersona'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_valormensualidad'] = $row ['ci04_valormensualidad'];
			$entry ['ci04_valorservicios'] = $row ['ci04_valorservicios'];
			$entry ['ci04_iva'] = $row ['ci04_iva'];
			$entry ['ci04_f29'] = $row ['ci04_f29'];
			$entry ['ci04_renta'] = $row ['ci04_renta'];
			$entry ['ci04_previred'] = $row ['ci04_previred'];			
			$entry ['ci04_empresarial'] = $row ['ci04_empresarial'];
			$entry ['ci04_independiente'] = $row ['ci04_independiente'];
			$entry ['ci04_nanas'] = $row ['ci04_nanas'];
			$entry ['ci04_otro'] = $row ['ci04_otro'];
			$entry ['ci04_socio'] = $row ['ci04_socio'];
			$entry ['ci04_trabajadores'] = $row ['ci04_trabajadores'];	
					
			$datosRut [] = $entry;
		}
		
		return $datosRut;
	}
	
	public function listadoRut() 
	{	
		$sql = "SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  IF(r.`ci40_idsociedad`='0','PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  u.`lc01_nombreUsuario`  
			FROM
			  `ci03_cliente` c 
			  INNER JOIN `lc01_usuario` u 
			    ON c.`lc01_idusuario` = u.`lc01_idUsuario` 
			  INNER JOIN `ci04_rut` r 
			    ON r.`ci03_idcliente` = c.`ci03_idcliente` 
			WHERE r.ci04_estadodisponibilidad = '1' ;";
	
		$listadoRut = mysql_query ( $sql );
	
		$rut = array ();
	
		while ( $row = mysql_fetch_array ( $listadoRut ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
	
			$rut [] = $entry;
		}
	
		return $rut;
	}
	
	public function listadoRutByIdCliente($idCliente) {
	
		$sql = "SELECT 
			  `ci04_idrrut`,
			  `ci04_razonsocial`,
			  IF(
			    `ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut` 
			FROM
			  `ci04_rut` 
			WHERE `ci03_idcliente` = '".$idCliente."' 
			  AND ci04_estadodisponibilidad = '1';";
	
		$listadoRut = mysql_query ( $sql );
	
		$rut = array ();
	
		while ( $row = mysql_fetch_array ( $listadoRut ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
	
			$rut [] = $entry;
		}
	
		return $rut;
	}
	
	public function listadoRutBusqueda($id,$idUser,$desde) 
	{	
	  $filtro="";
		
	  switch($desde)
	  {
	  	case '1':
	  		$filtro="u.`lc01_idUsuario` = '".$id."' ";
	  		break;
	  	case '2':
	  		$filtro="r.`ci04_razonsocial` LIKE '".$id."%' AND u.`lc01_idUsuario`='".$idUser."' ";
	  		break;
	  	case '3':
	  		$filtro="r.`ci04_rut`  LIKE '".$id."%' AND u.`lc01_idUsuario`='".$idUser."' ";
	  		break;
	  	case '4':
	  		$filtro="r.`ci04_numerosociedad` LIKE '".$id."%' AND u.`lc01_idUsuario`='".$idUser."' ";
	  		break;
	  }		
		
	  $sql = "SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  IF(
			    r.`ci04_numerosociedad` = '0',
			    'PN',
			    r.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  u.`lc01_nombreUsuario` 
			FROM
			  `ci03_cliente` c 
			  INNER JOIN `lc01_usuario` u 
			    ON c.`lc01_idusuario` = u.`lc01_idUsuario` 
			  INNER JOIN `ci04_rut` r 
			    ON r.`ci03_idcliente` = c.`ci03_idcliente` 
			WHERE ".$filtro."
			  AND r.ci04_estadodisponibilidad = '1';";
	
		$listadoRut = mysql_query ( $sql );
	
		$rut = array ();
	
		while ( $row = mysql_fetch_array ( $listadoRut ) ) 
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
	
			$rut [] = $entry;
		}
	
		return $rut;
	}

	public function listadoRutBusquedaFiltro($data)
	{		
		$filtro="";
		
		if($data['idEjecutivo']!='')
		{
			$filtro.="AND u.`lc01_idUsuario` = '".$data['idEjecutivo']."' ";
		}
		
		if($data['razonSocial']!='')
		{
			$filtro.="AND r.`ci04_razonsocial` LIKE '".$data['razonSocial']."%'";
		}
		
		if($data['rut']!='')
		{
			$filtro.="AND r.`ci04_rut` LIKE '".$data['rut']."%' ";
		}
		
		if($data['numSodiedad']!='')
		{
			$filtro.="AND r.`ci04_numerosociedad` LIKE '".$data['numSodiedad']."%' ";
		}
		
		$sql = "SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  u.`lc01_nombreUsuario` 
			FROM
			  `ci03_cliente` c 
			  INNER JOIN `lc01_usuario` u 
			    ON c.`lc01_idusuario` = u.`lc01_idUsuario` 
			  INNER JOIN `ci04_rut` r 
			    ON r.`ci03_idcliente` = c.`ci03_idcliente` 
			WHERE r.ci04_estadodisponibilidad = '1' $filtro ;";
	
		$listadoRut = mysql_query ( $sql );
	
		$rut = array ();
	
		while ( $row = mysql_fetch_array ( $listadoRut ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['lc01_nombreUsuario'] = $row ['lc01_nombreUsuario'];
	
			$rut [] = $entry;
		}
	
		return $rut;
	}
		
	public function verificaIdRutExiste($idRut)
	{
		$sql="SELECT * FROM `ci04_rut` WHERE `ci04_idrrut`='".$idRut."' and ci04_estadodisponibilidad='1';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}	
	
	public function modificarRut($data)
	{
		$sql = "UPDATE `ci04_rut` 
					SET 
						`ci40_idsociedad`='" . $data ['idSociedad'] . "',
						`ci04_razonsocial`='" . $data ['razonSocial'] . "',
						`ci04_rut`='" . $data ['rut'] . "',
						`ci04_tipopersona`='" . $data ['tipoPersona'] . "',
						`ci04_numerosociedad`='" . $data ['numeroSociedad'] . "',						
						`ci04_valormensualidad`='" . $data ['valorMensualidad'] . "',
						`ci04_valorservicios`='" . $data ['valorServicios'] . "',						
						`ci04_iva`='" . $data ['iva'] . "',
						`ci04_f29`='" . $data ['f29'] . "',
						`ci04_renta`='" . $data ['renta'] . "',
						`ci04_previred`='" . $data ['previred'] . "',					
						`ci04_empresarial`='" . $data ['empresarial'] . "',
						`ci04_independiente`='" . $data ['independiente'] . "',
						`ci04_nanas`='" . $data ['nanas'] . "',
						`ci04_otro`='" . $data ['otro'] . "',
						`ci04_socio`='" . $data ['socio'] . "',
						`ci04_trabajadores`='" . $data ['trabajadores'] . "'
				 WHERE `ci04_idrrut`='" . $data ['idRut'] . "';";			
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public function eliminarRutAsociado($idRut)
	{
		$sql="UPDATE ci04_rut SET ci04_estadodisponibilidad='0' WHERE ci04_idrrut='". $idRut ."';";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function eliminarRutAsociadoAlCliente($idCliente)
	{
		$sql="UPDATE 
			  `ci04_rut` 
			SET
			  `ci04_estadodisponibilidad` = '0' 
			WHERE `ci03_idcliente` = '".$idCliente."';";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function obtieneRutRenta($idCliente)
	{
		$sql="SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_rut`,
			  cm.`ci07_monto` AS ci_monto,
			  cm.`ci53_idestadocobro`,
			  SUBSTRING(cm.`ci07_fechapago`, 1, 4) AS ci_anio 
			FROM
			  `ci04_rut` r 
			  INNER JOIN `ci03_cliente` c 
			    ON r.`ci03_idcliente` = c.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` cm 
			    ON r.`ci04_idrrut` = cm.`ci04_idrrut` 
			WHERE c.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_renta` = '1' 
			  AND cm.`ci33_idconcepto` = '2' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND cm.`ci53_idestadocobro` !='5'
			  AND SUBSTRING(cm.`ci07_fechapago`,1,4)=YEAR(NOW());";
		
		$datos = mysql_query ( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{				
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_anio'] = $row ['ci_anio'];
		
			$datosRut [] = $entry;
		}
		
		
		return $datosRut;
	}
	
	public function obtieneRutRentaByAnio($idCliente,$anio)
	{
		$sql="SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_rut`,
			  cm.`ci07_monto` AS ci_monto,
			  cm.`ci53_idestadocobro`,
			  SUBSTRING(cm.`ci07_fechapago`, 1, 4) AS ci_anio 
			FROM
			  `ci04_rut` r 
			  INNER JOIN `ci03_cliente` c 
			    ON r.`ci03_idcliente` = c.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` cm 
			    ON r.`ci04_idrrut` = cm.`ci04_idrrut` 
			WHERE c.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_renta` = '1' 
			  AND cm.`ci33_idconcepto` = '2' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND cm.`ci53_idestadocobro` != '5' 
			  AND SUBSTRING(cm.`ci07_fechapago`,1,4)='".$anio."';";
		
		$datos = mysql_query ( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_anio'] = $row ['ci_anio'];
		
			$datosRut [] = $entry;
		}
		
		
		return $datosRut;
		
	}
	
	//listado rut renta para cobros masivo
	public function obtieneRutCobroRenta($data)
	{
		$filtro="";
		
		if($data['idejecutivo']!='' && $data['idCliente']=='' && $data['idRut']=='' )
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = ".$data['idejecutivo']." ";
		}
		else if($data['idejecutivo']!='' && $data['idCliente']!='' && $data['idRut']=='' )
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = ".$data['idejecutivo']." AND `ci03_cliente`.`ci03_idcliente` = ".$data['idCliente']." ";
		}
		else if($data['idejecutivo']!='' && $data['idCliente']!='' && $data['idRut']!='' )
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = ".$data['idejecutivo']." AND `ci04_rut`.`ci04_idrrut` = ".$data['idRut']." ";
		}
		
		$sql="SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_sii`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial` 
			FROM
			  `ci04_rut` 
			  INNER JOIN `ci03_cliente` 
			    ON `ci04_rut`.`ci03_idcliente` = `ci03_cliente`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_renta` = '1' 
			  AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			$filtro
			ORDER BY `ci04_rut`.`ci04_idrrut` ;";
		
		$datos = mysql_query ( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci11_sii'] = $row ['ci11_sii'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
		
			$datosRut [] = $entry;
		}
		
		
		return $datosRut;
	}
	
	//listado rut previred para cobros masivo
	public function obtieneRutCobroPrevired($data)
	{		
		$filtro="";
		
		if($data['idejecutivo']!='')
		{
			$filtro.=" AND `lc01_usuario`.`lc01_idUsuario` = ".$data['idejecutivo']." ";
		}
		
		if($data['idCliente']!='' )
		{
			$filtro.=" AND `ci03_cliente`.`ci03_idcliente` = ".$data['idCliente']." ";
		}
		
		if($data['idRut']!='' )
		{
			$filtro.=" AND `ci04_rut`.`ci04_idrrut` = ".$data['idRut']." ";
		}
		
		
		$sql="SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Empresarial' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_empresarial` = '1' 
			$filtro
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Independiente' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_independiente` = '1'
			$filtro
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Nanas' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_nanas` = '1'
			$filtro
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Otros' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_otro` = '1'
			$filtro
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Socio' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_socio` = '1'
			$filtro
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Trabajadores' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_trabajadores` = '1' 
			$filtro
			ORDER BY `ci04_idrrut`;";
	
		$datos = mysql_query ( $sql );
	
		$datosRut = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci11_previred'] = $row ['ci11_previred'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];	
			$entry ['ci_nombreconcepto'] = $row ['ci_nombreconcepto'];
	
			$datosRut [] = $entry;
		}	
		
		return $datosRut;
	}
	
	//listado rut f29 para cobro masivo
	public function obtieneRutCobroF29($data)
	{		
		$where="";		
		
		$filtro="";
		
		if($data['idejecutivo']!='' && $data['idCliente']=='' && $data['idRut']=='' )
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = ".$data['idejecutivo']." ";
		}
		else if($data['idejecutivo']!='' && $data['idCliente']!='' && $data['idRut']=='' )
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = ".$data['idejecutivo']." AND `ci03_cliente`.`ci03_idcliente` = ".$data['idCliente']." ";
		}
		else if($data['idejecutivo']!='' && $data['idCliente']!='' && $data['idRut']!='' )
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = ".$data['idejecutivo']." AND `ci04_rut`.`ci04_idrrut` = ".$data['idRut']." ";
		}
		
		if($data['perfil']=='4')
		{
			$where="AND `ci04_rut`.`ci04_previred`='1' AND `ci04_rut`.`ci40_idsociedad` !='0' ";
		}
		
		$sql="SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  `ci11_clave`.`ci11_sii`,
			  `ci40_sociedad`.`ci40_tiposociedad`,
			  `ci04_rut`.`ci04_iva`,
			  `ci04_rut`.`ci04_previred`,
			  '' AS `ci07_ingsinretencion`,
			  '' AS `ci07_ingconretencion`,
			  '' AS `ci07_ingsociedad`,
			  '' AS `ci07_retsociedad`,
			  
			  '' AS `ci07_tasaprimeracat`,
			  '' AS `ci37_idtasa`,
			  '' AS `ci39_idretencion`,
			  
			  '' AS `ci07_ppmnetdet`,
			  '' AS `ci07_bolretterceros`,
			  '' AS `ci07_retencion`,
			  '' AS `ci07_impuestounico`,
			  '' AS `ci07_ivapago`,
			  '' AS `ci07_remanente`,
			  '' AS `ci07_monto`,
			  '' AS `ci35_idformapago`,
			  'a' AS ci_rut1,
				
			  (SELECT `ci37_idtasa` FROM `ci37_tasa` WHERE `ci37_anio`=".$data['anio']." AND `ci37_mes`=".$data['mes'].") AS ci_idtasa,
			  		
  			  (SELECT `ci37_valor` FROM `ci37_tasa` WHERE `ci37_anio`=".$data['anio']." AND `ci37_mes`=".$data['mes'].") AS ci_valortasa,
  
  			  (SELECT `ci39_idretencion` FROM `ci39_retencion` WHERE `ci39_anio`=".$data['anio']." AND `ci39_mes`=".$data['mes'].") AS ci_idretencion,
  			  		
  			  (SELECT `ci39_valor` FROM `ci39_retencion` WHERE `ci39_anio`=".$data['anio']." AND `ci39_mes`=".$data['mes'].") AS ci_valorretencion
			
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci04_rut`.`ci03_idcliente` = `ci03_cliente`.`ci03_idcliente`   
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `ci40_sociedad` 
			    ON `ci04_rut`.`ci40_idsociedad` = `ci40_sociedad`.`ci40_idsociedad` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_f29` = '1' 
			  AND `ci04_rut`.`ci04_estadodisponibilidad` = '1'  
			  $where  $filtro
			ORDER BY `ci04_idrrut` ;";
		
		$datos = mysql_query( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci11_sii'] = $row ['ci11_sii'];
			$entry ['ci40_tiposociedad'] = $row ['ci40_tiposociedad'];
			$entry ['ci04_iva'] = $row ['ci04_iva'];
			$entry ['ci04_previred'] = $row ['ci04_previred'];			
			$entry ['ci07_ingsinretencion'] = $row ['ci07_ingsinretencion'];
			$entry ['ci07_ingconretencion'] = $row ['ci07_ingconretencion'];
			$entry ['ci07_ingsociedad'] = $row ['ci07_ingsociedad'];
			$entry ['ci07_retsociedad'] = $row ['ci07_retsociedad'];
			$entry ['ci07_tasaprimeracat'] = $row ['ci07_tasaprimeracat'];
			$entry ['ci37_idtasa'] = $row ['ci37_idtasa'];
			$entry ['ci39_idretencion'] = $row ['ci39_idretencion'];
			$entry ['ci07_ppmnetdet'] = $row ['ci07_ppmnetdet'];
			$entry ['ci07_bolretterceros'] = $row ['ci07_bolretterceros'];
			$entry ['ci07_retencion'] = $row ['ci07_retencion'];
			$entry ['ci07_impuestounico'] = $row ['ci07_impuestounico'];
			$entry ['ci07_ivapago'] = $row ['ci07_ivapago'];
			$entry ['ci07_remanente'] = $row ['ci07_remanente'];
			$entry ['ci07_monto'] = $row ['ci07_monto'];
			$entry ['ci35_idformapago'] = $row ['ci35_idformapago'];			
			$entry ['ci_rut1'] = $row ['ci_rut1'];			
			$entry ['ci_idtasa'] = $row ['ci_idtasa'];
			$entry ['ci_valortasa'] = $row ['ci_valortasa'];			
			$entry ['ci_idretencion'] = $row ['ci_idretencion'];
			$entry ['ci_valorretencion'] = $row ['ci_valorretencion'];
			
		
			$datosRut [] = $entry;
		}
		
		
		return $datosRut;
	}
	
	//lista para cobros de mensualidad y servicio variable
	public function obtieneRutCobroMensualidadServicio($data)
	{			
		$filtro="";
		
		if($data['idEjecutivo']!='' && $data['idCliente']==''&& $data['idRut']=='')
		{
			$filtro="WHERE `lc01_usuario`.`lc01_idUsuario` ='".$data['idEjecutivo']."' ";
		}
		elseif ($data['idEjecutivo']!='' && $data['idCliente']!='' && $data['idRut']=='')
		{
			$filtro="WHERE `lc01_usuario`.`lc01_idUsuario` = ".$data['idEjecutivo']." AND `ci03_cliente`.`ci03_idcliente` = ".$data['idCliente']."";
		}
		elseif ($data['idEjecutivo']!='' && $data['idCliente']!='' && $data['idRut']!='')
		{
			$filtro="WHERE `lc01_usuario`.`lc01_idUsuario` = ".$data['idEjecutivo']." AND `ci03_cliente`.`ci03_idcliente` = ".$data['idCliente']." AND `ci04_rut`.`ci04_idrrut` = ".$data['idRut']." ";
		}
		
		$sql="SELECT 
			  `ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci04_rut`.`ci04_razonsocial`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = '0',
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_valormensualidad`,
			  `ci04_rut`.`ci04_valorservicios` 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
				$filtro
			ORDER BY `ci04_idrrut` ;";
		
		$datos = mysql_query( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_valormensualidad'] = $row ['ci04_valormensualidad'];
			$entry ['ci04_valorservicios'] = $row ['ci04_valorservicios'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
		
			$datosRut [] = $entry;
		}
		
		return $datosRut;
	}	
	
	public function filtroRutCobrof29($data)
	{
		$filtro="";
		
		if($data['origen']=='1')
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = '".$data['idUsuario']."'";
		}
		else if($data['origen']=='2')
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = '".$data['idUsuario']."' AND `ci03_cliente`.`ci03_idcliente` = '".$data['nombreCliente']."' ";
		}
		else if($data['origen']=='3')
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = '".$data['idUsuario']."' AND `ci04_rut`.`ci04_idrrut` = '".$data['rutCliente']."' ";
		}
		
		
				
		$sql="SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  `ci11_clave`.`ci11_sii`,
			  `ci40_sociedad`.`ci40_tiposociedad`,
			  `ci04_rut`.`ci04_iva`,
			  `ci04_rut`.`ci04_previred`,
			  '' AS `ci07_ingsinretencion`,
			  '' AS `ci07_ingconretencion`,
			  '' AS `ci07_ingsociedad`,
			  '' AS `ci07_retsociedad`,
			  '' AS `ci07_tasaprimeracat`,
			  '' AS `ci37_idtasa`,
			  '' AS `ci39_idretencion`,
			  '' AS `ci07_ppmnetdet`,
			  '' AS `ci07_bolretterceros`,
			  '' AS `ci07_retencion`,
			  '' AS `ci07_impuestounico`,
			  '' AS `ci07_ivapago`,
			  '' AS `ci07_remanente`,
			  '' AS `ci07_monto`,
			  '' AS `ci35_idformapago` 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `ci40_sociedad` 
			    ON `ci04_rut`.`ci40_idsociedad` = `ci40_sociedad`.`ci40_idsociedad` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_f29` = '1' 
			  AND `ci04_rut`.`ci04_estadodisponibilidad` = '1'
			  ".$filtro."  			
			ORDER BY `ci04_idrrut` ;";
		
		$datos = mysql_query ( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci11_sii'] = $row ['ci11_sii'];
			$entry ['ci40_tiposociedad'] = $row ['ci40_tiposociedad'];
			$entry ['ci04_iva'] = $row ['ci04_iva'];
			$entry ['ci04_previred'] = $row ['ci04_previred'];
		
			$datosRut [] = $entry;
		}
		
		
		return $datosRut;
	}

	public function filtroRutCobroRenta($data)
	{		
		$filtro="";
		
		if($data['origen']=='1')
		{
			$filtro="AND u.`lc01_idUsuario` = '".$data['idUsuario']."'";
		}
		else if($data['origen']=='2')
		{
			$filtro="AND u.`lc01_idUsuario` = '".$data['idUsuario']."' AND c.`ci03_idcliente` = '".$data['nombreCliente']."' ";
		}
		else if($data['origen']=='3')
		{
			$filtro="AND u.`lc01_idUsuario` = '".$data['idUsuario']."' AND r.`ci04_idrrut` = '".$data['rutCliente']."' ";
		}
		
		$sql="SELECT 
			  r.`ci04_idrrut`,
			  c.`ci03_nombre`,
			  r.`ci04_rut`,
			  cla.`ci11_sii`,
			  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
			  r.`ci04_razonsocial` 
			FROM
			  `ci04_rut` r 
			  INNER JOIN `ci03_cliente` c 
			    ON r.`ci03_idcliente` = c.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` cla 
			    ON r.`ci04_idrrut` = cla.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` u 
			    ON c.`lc01_idUsuario` = u.`lc01_idUsuario` 
			WHERE r.`ci04_renta` = '1' 
			  AND r.`ci04_estadodisponibilidad` = '1'
			  ".$filtro." 
			ORDER BY r.`ci04_idrrut`";
		
		$datos = mysql_query ( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci11_sii'] = $row ['ci11_sii'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
		
			$datosRut [] = $entry;
		}
		
		
		return $datosRut;
	}
		
	public function filtroRutCobroPrevired($data)
	{
		$filtro="";
		
		if($data['origen']=='1')
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = '".$data['idUsuario']."'";
		}
		else if($data['origen']=='2')
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = '".$data['idUsuario']."' AND `ci03_cliente`.`ci03_idcliente` ='".$data['nombreCliente']."'";
		}
		else if($data['origen']=='3')
		{
			$filtro="AND `lc01_usuario`.`lc01_idUsuario` = '".$data['idUsuario']."' AND `ci04_rut`.`ci04_idrrut` = '".$data['rutCliente']."'";
		}
		
		$sql="SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Empresarial' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_empresarial` = '1'
			".$filtro."	
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Independiente' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_independiente` = '1' 
			".$filtro."	
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Nanas' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_nanas` = '1'
			".$filtro."	
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Otros' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_otro` = '1'
			".$filtro."	
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Socio' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_socio` = '1'
			".$filtro."	
			UNION
			SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci11_clave`.`ci11_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  'Trabajadores' AS ci_nombreconcepto 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_trabajadores` = '1' 
			".$filtro."	
			ORDER BY `ci04_idrrut`;";
		
		$datos = mysql_query ( $sql );
		
		$datosRut = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci11_previred'] = $row ['ci11_previred'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci_nombreconcepto'] = $row ['ci_nombreconcepto'];
		
			$datosRut [] = $entry;
		}	
		return $datosRut;
	}
		
	public function metaBoleteoRutByCliente($idCliente,$anio)
	{
		$sql="SELECT 
			  meta.ci04_idrrut,
			  meta.ci04_razonsocial,
			  meta.ci10_monto 
			FROM
			  (SELECT 
			    r.`ci04_idrrut`,
			    r.`ci04_razonsocial`,
			    mb.`ci10_monto` 
			  FROM
			    `ci03_cliente` c 
			    INNER JOIN `ci04_rut` r 
			      ON c.`ci03_idcliente` = r.`ci03_idcliente` 
			    INNER JOIN `ci10_metaboleteo` mb 
			      ON r.`ci04_idrrut` = mb.`ci04_idrrut` 
			  WHERE c.`ci03_idcliente` = '".$idCliente."' 
			    AND mb.`ci10_anio` = '".$anio."' 
			  UNION
			  SELECT 
			    `ci04_rut`.`ci04_idrrut`,
			    `ci04_rut`.`ci04_razonsocial`,
			    '0' AS ci10_monto 
			  FROM
			    `ci03_cliente` 
			    INNER JOIN `ci04_rut` 
			      ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' ) AS meta 
			GROUP BY ci04_idrrut ;";
		
		$datos = mysql_query ( $sql );
		
		$datosMetaBoleteo= array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{			
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci10_monto'] = $row ['ci10_monto'];
		
			$datosMetaBoleteo [] = $entry;
		}
		
		return $datosMetaBoleteo;			
	}
	
	public function obtieneRazonSocialBoleteo($idCliente,$fecha)
	{
		$sql="SELECT
			  `ci03_cliente`.`ci03_idcliente`,
			  `ci04_rut`.`ci04_idrrut`,
			  `ci04_rut`.`ci04_razonsocial`,
			  `ci04_rut`.`ci40_idsociedad`,
			  IFNULL(
			    (SELECT
			      `ci10_monto`
			    FROM
			      `ci10_metaboleteo`
			    WHERE `ci04_idrrut` = `ci04_rut`.`ci04_idrrut`
			      AND `ci10_anio` = '".$fecha."'),
			    0
			  ) AS ci_montoboleteo
			FROM
			  `ci03_cliente`
			  INNER JOIN `ci04_rut`
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente`
			WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'
			AND ci04_rut.`ci04_estadodisponibilidad` <> 0 ;";
	
		$datos = mysql_query ( $sql );
	
		$datosRazon = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci40_idsociedad'] = $row ['ci40_idsociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci_montoboleteo'] = $row ['ci_montoboleteo'];
	
			$datosRazon [] = $entry;
		}
	
		return $datosRazon;
	
	}
		
	public function obtieneRazonSocialCliente($idCliente)
	{
		$sql="SELECT 
				  r.`ci04_idrrut`,
				  r.`ci04_razonsocial` 
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
				WHERE c.`ci03_idcliente` = '".$idCliente."';";
		
		$datos = mysql_query ( $sql );
		
		$datosRazonCliente= array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
		
			$datosRazonCliente [] = $entry;
		}
		
		return $datosRazonCliente;
	}
		
	public function totalCobrosPendientesDelRut($idRut)
	{
		$sql="SELECT 
			  SUM(pendientes.ci_monto) AS ci_monto 
			FROM
			  (SELECT 
			    SUM(
			      ROUND(
			        (
			          `ci05_cobroindividual`.`ci05_monto` * `ci05_cobroindividual`.`ci05_valoruf`
			        )
			      )
			    ) AS ci_monto 
			  FROM
			    `ci04_rut` 
			    INNER JOIN `ci05_cobroindividual` 
			      ON `ci04_rut`.`ci04_idrrut` = `ci05_cobroindividual`.`ci04_idrrut` 
			  WHERE `ci05_cobroindividual`.`ci53_idestadocobro` = '1' 
			    AND `ci04_rut`.`ci04_idrrut` = '".$idRut."' 
			    AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			  UNION
			  SELECT 
			    SUM(
			      ROUND(
			        `ci06_honorario`.`ci06_monto` * `ci06_honorario`.`ci06_valoruf`
			      )
			    ) AS ci_monto 
			  FROM
			    `ci04_rut` 
			    INNER JOIN `ci06_honorario` 
			      ON `ci04_rut`.`ci04_idrrut` = `ci06_honorario`.`ci04_idrrut` 
			  WHERE `ci06_honorario`.`ci53_idestadocobro` = '1' 
			    AND `ci04_rut`.`ci04_idrrut` = '".$idRut."'
			    AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			  UNION
			  SELECT 
			    SUM(
			      `ci07_cobromasivo`.`ci07_monto`
			    ) AS ci_monto 
			  FROM
			    `ci04_rut` 
			    INNER JOIN `ci07_cobromasivo` 
			      ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			  WHERE `ci07_cobromasivo`.`ci53_idestadocobro` = '1' 
			    AND `ci04_rut`.`ci04_idrrut` = '".$idRut."' 
			    AND `ci04_rut`.`ci04_estadodisponibilidad` = '1') AS pendientes ";
		
		$res=mysql_query($sql);
		
		$cobroPendiente=0;
		
		while($monto=mysql_fetch_array($res))
		{
			$cobroPendiente=$monto['ci_monto'];
		}
		
		return $cobroPendiente;
	}
	
	
	/** Terminan funciones Rut **/
	
	/** funciones Meta de Boleteo **/
	
	public function ingresarMeta($data)
	{
		$sql = "INSERT INTO 
				ci10_metaboleteo (`ci04_idrrut`, `ci10_anio`, `ci10_monto`)
				VALUES
				('" . $data ['idRut'] . "','" . $data ['anioMeta'] . "','" . $data ['montoMeta'] . "' );";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
		
	public function verificaExisteAnioMeta($data)
	{
		$sql="SELECT * FROM `ci10_metaboleteo` WHERE ci04_idrrut='".$data['idRut']."' and `ci10_anio` = '".$data['anioMeta']."'; ";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
		
	public function obtenerIdMeta($data)
	{
		$sql = "SELECT ci10_idmeta 
				FROM ci10_metaboleteo 
				WHERE 
				ci04_idrrut='".$data['idRut']."' and ci10_anio='".$data['anioMeta']."';";
		
		$datos = mysql_query ( $sql );
		
		$idMeta = "";
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$idMeta  = $row ['ci10_idmeta'];
		}
		
		return $idMeta;
	}
		
	public function modificarMeta($data,$id)
	{
		$sql="UPDATE ci10_metaboleteo SET ci10_monto='".$data['montoMeta']."' WHERE ci04_idrrut='".$data['idRut']."' and ci10_anio='".$data['anioMeta']."';";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}	
	
	public function obtenerMetasByIdRut($id)
	{
		$sql="SELECT `ci10_anio`,`ci10_monto` FROM `ci10_metaboleteo` WHERE `ci04_idrrut`='".$id."';";
		
		$datos = mysql_query ( $sql );
		
		$datosMeta = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci10_anio'] = $row ['ci10_anio'];
			$entry ['ci10_monto'] = $row ['ci10_monto'];
				
			$datosMeta [] = $entry;
		}
		
		return $datosMeta;
		
	}
	
	/** Terminan funciones Meta de Boleteo **/
	
	/*** funciones Claves ***/
	
	public function ingresarClaves($data)
	{
		$sql = "INSERT INTO
				ci11_clave (`ci04_idrrut`, `ci11_sii`, `ci11_previred`)
				VALUES
				('" . $data ['idRut'] . "','" . $data ['claveSii'] . "','" . $data ['clavePrevired'] . "' );";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function obtenerClavesByIdRut($id)
	{
		$sql="SELECT 
			  `ci11_sii`,
			  `ci11_previred` 
			FROM
			  `ci11_clave` 
			WHERE `ci04_idrrut` = '".$id."';";
	
		$datos = mysql_query ( $sql );
	
		$datosClaves = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci11_sii'] = $row ['ci11_sii'];
			$entry ['ci11_previred'] = $row ['ci11_previred'];
	
			$datosClaves [] = $entry;
		}
	
		return $datosClaves;
	
	}
	
	public function actualizaClaves($data)
	{
		$sql="UPDATE 
			  `ci11_clave` 
			SET
			  `ci11_sii` = '".$data['claveSii']."',
			  `ci11_previred` = '".$data['clavePrevired']."'
			WHERE `ci04_idrrut` = '".$data['idRut']."';";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function existenClaves($idRut)
	{
		$sql="SELECT `ci11_idclave` FROM  `ci11_clave` WHERE `ci04_idrrut`='".$idRut."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/*** Termina funciones Claves ***/

	/*** funciones Cuenta Corriente ***/
	
	public function ingresarCuentaCorriente($data)
	{
		$sql = "INSERT INTO
				ci14_cuentacorriente (`ci03_idrrut`,`ci58_idbanco`, `ci14_numerocuenta`)
				VALUES
				('" . $data ['idRut'] . "','" . $data ['nombreBanco'] . "','" . $data ['numeroCuenta'] . "' );";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function obtenerCuentaCorrienteByIdRut($idRut)
	{
		$sql="SELECT 
			  b.`ci58_idbanco`,
			  b.`ci58_nombrebanco`,
			  cc.`ci14_numerocuenta` 
			FROM
			  `ci04_rut` r 
			  INNER JOIN `ci14_cuentacorriente` cc 
			    ON r.`ci04_idrrut` = cc.`ci03_idrrut` 
			  INNER JOIN `ci58_banco` b 
			    ON cc.`ci58_idbanco` = b.`ci58_idbanco` 
			WHERE `ci03_idrrut` = '".$idRut."' ";
	
		$datos = mysql_query ( $sql );
	
		$datosCuenta = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci14_numerocuenta'] = $row ['ci14_numerocuenta'];
			$entry ['ci58_idbanco'] = $row ['ci58_idbanco'];
			$entry ['ci58_nombrebanco'] = $row ['ci58_nombrebanco'];
	
			$datosCuenta [] = $entry;
		}
	
		return $datosCuenta;	
	}
	
	public function actualizaCuentaCorriente($data)
	{
		$sql="UPDATE 
			  `ci14_cuentacorriente` 
			SET
			  `ci58_idbanco` = '".$data['nombreBanco']."',
			  `ci14_numerocuenta` = '".$data['numeroCuenta']."' 
			WHERE `ci03_idrrut` = '".$data['idRut']."';";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function verificaCuentaCorrienteExite($idRut)
	{
		$sql="SELECT 
			    `ci14_idcuenta` 
			  FROM
			    `ci14_cuentacorriente` 
			  WHERE `ci03_idrrut` = '".$idRut."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function verificaExisteNumeroCuenta($numCuenta)
	{
		$sql="SELECT 
			    `ci14_idcuenta` 
			  FROM
			    `ci14_cuentacorriente` 
			  WHERE `ci14_numerocuenta`='".$numCuenta."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
		
	/*** Termina funciones Cuenta Corriente ***/
	
	/*** funciones Datos Pesonales ***/
	
	public function ingresarDatosPersonales($data)
	{
		$sql = "INSERT INTO ci52_datospersonales 
				(
				 `ci04_idrrut`,
				 `ci52_fechanacimiento`,
				 `ci52_especialidad`,
				 `ci52_sexo`,
				 `ci52_trabajo1`,
				 `ci52_trabajo2`,
				 `ci52_seguro`
			   )
			   VALUES
			   ('" . $data ['idRut'] . "',
				'" . $data ['fechaNacimiento'] . "',
				'" . $data ['especialidad'] . "',
				'" . $data ['sexo'] . "',
				'" . $data ['trabajo1'] . "',
				'" . $data ['trabajo2'] . "',
				'" . $data ['seguro'] . "' );";
		
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function obtenerDatosPersonalesByIdRut($id)
	{
		$sql="SELECT 
			`ci52_fechanacimiento`,
			`ci52_especialidad`,
			`ci52_sexo`,
			`ci52_trabajo1`,
			`ci52_trabajo2`,
			`ci52_seguro` 
			FROM
			`ci52_datospersonales` 
			WHERE `ci04_idrrut`='".$id."';";
	
		$datos = mysql_query ( $sql );
	
		$datosPersonales = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci52_fechanacimiento'] = $row ['ci52_fechanacimiento'];
			$entry ['ci52_especialidad'] = $row ['ci52_especialidad'];
			$entry ['ci52_sexo'] = $row ['ci52_sexo'];
			$entry ['ci52_trabajo1'] = $row ['ci52_trabajo1'];
			$entry ['ci52_trabajo2'] = $row ['ci52_trabajo2'];
			$entry ['ci52_seguro'] = $row ['ci52_seguro'];
	
			$datosPersonales [] = $entry;
		}
	
		return $datosPersonales;
	}
	
	public function verificaRutEnDatosPersonales($id)
	{
		$sql="SELECT * FROM `ci52_datospersonales` WHERE `ci04_idrrut`='".$id."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function actualizaDatosPersonales($data)
	{
		$sql="UPDATE 
			  `ci52_datospersonales` 
			SET
			  `ci52_fechanacimiento` = '".$data['fechaNacimiento']."',
			  `ci52_especialidad` = '".$data['especialidad']."',
			  `ci52_sexo` = '".$data['sexo']."',
			  `ci52_trabajo1` = '".$data['trabajo1']."',
			  `ci52_trabajo2` = '".$data['trabajo2']."',
			  `ci52_seguro` = '".$data['seguro']."' 
			WHERE `ci04_idrrut` ='".$data['idRut']."';";
				
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function verificaExistenDatosPesonales($idRut)
	{
		$sql="SELECT 
			  `ci52_idpersonales` 
			FROM
			  `ci52_datospersonales` 
			WHERE `ci04_idrrut` = '".$idRut."';";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/*** Termina funcionesDatos Pesonales ***/
	
	/*** Funcion Banco ***/
	
	public function listadoBancos()
	{
		$sql="SELECT 
			   * 
			 FROM
			  `ci58_banco` ";
		
		$listadoBancos = mysql_query ( $sql );
		
		$bancos = array ();
		
		while ( $row = mysql_fetch_array ( $listadoBancos ) )
		{
			$entry ['ci58_idbanco'] = $row ['ci58_idbanco'];
			$entry ['ci58_nombrebanco'] = $row ['ci58_nombrebanco'];
		
			$bancos [] = $entry;
		}
		
		return $bancos;
		
	}
	
	/*** Termina Funcion Banco ***/
}
