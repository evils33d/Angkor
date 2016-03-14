<?php
class Application_Model_CobroMapper 
{
	protected $_dbTable;
	
	protected $_host;
	protected $_username;
	protected $_password;
	protected $_dbname;
	protected $_link;
	
	public function __construct() 
	{
		$bootstrap = Zend_Controller_Front::getInstance ()->getParam ( 'bootstrap' );
		$options = $bootstrap->getOptions ();
		
		$this->_host = $options ['resources'] ['db'] ['params'] ['host'];
		$this->_username = $options ['resources'] ['db'] ['params'] ['username'];
		$this->_password = $options ['resources'] ['db'] ['params'] ['password'];
		$this->_dbname = $options ['resources'] ['db'] ['params'] ['dbname'];
		
		//$this->_link = mysql_connect ( $host, $username, $password ) or die ( mysql_error () );
		$this->_link = mysqli_connect($this->_host,$this->_username,$this->_password, $this->_dbname) or die ( mysqli_error($this->_link));
		//mysql_select_db ( $dbname, $link ) or die ( mysql_error () );
		
		$sql1 = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
		
		mysql_query($sql1);
	}
	
	/**************COBROS Masivos********************/
	
	public function registraCobroMasivo($data)
	{
		
		$sql="INSERT INTO `ci07_cobromasivo` (
			  `ci33_idconcepto`,
			  `ci04_idrrut`,			 
			  `ci35_idformapago`,
			  `ci36_idestadocomepnsacion`,
			  `ci37_idtasa`,
			  `ci39_idretencion`,
			  `ci07_tasaprimeracat`,
			  `ci53_idestadocobro`,
			  `ci07_ingsinretencion`,
			  `ci07_ingconretencion`,
			  `ci07_ingsociedad`,
			  `ci07_retsociedad`,
			  `ci07_bolretterceros`,
			  `ci07_impuestounico`,
			  `ci07_ivapago`,
			  `ci07_remanente`,
			  `ci07_monto`,
			  `ci07_autorizapago`,
			  `ci07_ppmnetdet`,
			  `ci07_retencion`,
			  `ci07_fechapago`,
			  `ci07_conceptoprevired`
			)
			VALUES
			  (
				'".$data['idConcepto']."',
				'".$data['idRut']."',				
				'".$data['idFormaPago']."',
				'".$data['compensacion']."',
				'".$data['tasa']."',
				'".$data['retencion']."',
				'".$data['tasaprimeracat']."',
				'".$data['estadocobro']."',
				'".$data['ingsinretencion']."',
				'".$data['ingconretencion']."',
				'".$data['ingsociedad']."',
				'".$data['retsociedad']."',
				'".$data['bolretterceros']."',
				'".$data['impuestounico']."',
				'".$data['ivapago']."',
				'".$data['remanente']."',
				'".$data['monto']."',
				'".$data['autorizapago']."',
				'".$data['ppmnetdet']."',
				'".$data['ret']."',						
				'".$data['fechaRegistro']."',
				'".$data['conceptoPrevired']."'
						
			  );";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
		
	public function registraCobroMasivoImpuestoUnico($datos)
	{
		$sql="";	
		
		foreach ($datos as $i => $data):
		
				$sql.="INSERT INTO `ci07_cobromasivo` (
					  `ci33_idconcepto`,
					  `ci04_idrrut`,
					  `ci35_idformapago`,
					  `ci36_idestadocomepnsacion`,
					  `ci37_idtasa`,
					  `ci39_idretencion`,
					  `ci07_tasaprimeracat`,
					  `ci53_idestadocobro`,
					  `ci07_ingsinretencion`,
					  `ci07_ingconretencion`,
					  `ci07_ingsociedad`,
					  `ci07_retsociedad`,
					  `ci07_bolretterceros`,
					  `ci07_impuestounico`,
					  `ci07_ivapago`,
					  `ci07_remanente`,
					  `ci07_monto`,
					  `ci07_autorizapago`,
					  `ci07_ppmnetdet`,
					  `ci07_retencion`,
					  `ci07_fechapago`,
					  `ci07_conceptoprevired`
					)
					VALUES
					  (
						'".$data['idConcepto']."',
						'".$data['idRut']."',
						'".$data['idFormaPago']."',
						'".$data['compensacion']."',
						'".$data['tasa']."',
						'".$data['retencion']."',
						'".$data['tasaprimeracat']."',
						'".$data['estadocobro']."',
						'".$data['ingsinretencion']."',
						'".$data['ingconretencion']."',
						'".$data['ingsociedad']."',
						'".$data['retsociedad']."',
						'".$data['bolretterceros']."',
						'".$data['impuestounico']."',
						'".$data['ivapago']."',
						'".$data['remanente']."',
						'".$data['monto']."',
						'".$data['autorizapago']."',
						'".$data['ppmnetdet']."',
						'".$data['ret']."',
						'".$data['fechaRegistro']."',
						'".$data['conceptoPrevired']."'	
					  );";
			
				$sql.="INSERT INTO `ci64_impuestounico` (
					  `ci04_idrut`,
					  `ci64_fecharegistro`,
					  `ci64_valorimpuesto`
					) 
					VALUES
					  (
						'".$data['idRut']."',
						'".$data['fechaRegistro']."',
						'".$data['impuestounico']."'
					  );";
		
		endforeach;
		
		$res = mysqli_multi_query ($this->_link, $sql );		
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function registraimpuestounico($data)
	{
		$sql="INSERT INTO `ci64_impuestounico` (
			  `ci04_idrut`,
			  `ci64_fecharegistro`,
			  `ci64_valorimpuesto`
			) 
			VALUES
			  (
				'".$data['idRut']."',
				'".$data['fecha']."',
				'".$data['monto']."'
			  );";
		

		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function verificaImpuestoUnicoIngresado($idRut,$fecha)
	{
		  $sql="SELECT 
				  `ci64_idimpuestounico` 
				FROM
				  `ci64_impuestounico` 
				WHERE `ci04_idrut` = '".$idRut."' 
				  AND `ci64_fecharegistro` = '".$fecha."';";
		  
		  $row=mysql_query($sql);
		  
		  if(mysql_num_rows($row)>0)
		  {
		  	return true;
		  }
		  else
		  {
		  	return false;
		  }
	}
	
	public function obtenerImpuestoUnico($idRut,$fecha)
	{
		$sql="SELECT 
			  `ci64_valorimpuesto`
			FROM
			  `ci64_impuestounico` 
			WHERE `ci04_idrut` = '".$idRut."' 
			  AND `ci64_fecharegistro` = '".$fecha."';";
		
		$res=mysql_query($sql);
		$impuestoUnico="";
		
		while($row=mysql_fetch_array($res))
		{
			$impuestoUnico=$row['ci64_valorimpuesto'];
		}
		
		return $impuestoUnico;		
	}
	
	public function verificaPreviredRut($idRut)
	{
		$sql="SELECT `ci04_idrrut` FROM `ci04_rut` WHERE `ci04_idrrut`='".$idRut."' AND `ci04_previred`='1';";
		
		$row=mysql_query($sql);
		
		if(mysql_num_rows($row)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
	
	public  function verificaIngresoImpuestoUnicoAdministrador($idRut)
	{
		$sql="SELECT 
			  `ci04_idrrut` 
			FROM
			  `ci04_rut` 
			WHERE `ci04_idrrut` = '".$idRut."' 
			  AND `ci04_previred` = '1' 
			  AND `ci40_idsociedad` != '0';";
		
		$row=mysql_query($sql);
		
		if(mysql_num_rows($row)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
		
	public function verificaExisteImpuestoUnico($idrut,$fecha)
	{
		$sql="SELECT 
			  `ci64_idimpuestounico` 
			FROM
			  `ci64_impuestounico` 
			WHERE `ci04_idrut` = '".$idrut."' 
			  AND `ci64_fecharegistro` = '".$fecha."';";
		
		$row=mysql_query($sql);
		
		if(mysql_num_rows($row)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
		
	public function editaraCobroMasivo($data)
	{	
		$fecha="";		
		
		if($data['fechaCobro']!='-')
		{
			$fecha="`ci07_fechapago`='".$data['fechaCobro']."',";
		}		
		
		$sql="UPDATE 
				`ci07_cobromasivo` 
			  SET  					 	 
				  $fecha
				  `ci35_idformapago`='".$data['idFormaPago']."',				  		
				  `ci37_idtasa`='".$data['tasa']."',				  
				  `ci39_idretencion`='".$data['retencion']."',				  		
				  `ci07_tasaprimeracat`='".$data['tasaprimeracat']."',	
				  `ci07_ingsinretencion`='".$data['ingsinretencion']."',
				  `ci07_ingconretencion`='".$data['ingconretencion']."',
				  `ci07_ingsociedad`='".$data['ingsociedad']."',
				  `ci07_retsociedad`='".$data['retsociedad']."',
				  `ci07_bolretterceros`='".$data['bolretterceros']."',
				  `ci07_impuestounico`='".$data['impuestounico']."',
				  `ci07_ivapago`='".$data['ivapago']."',
				  `ci07_remanente`='".$data['remanente']."',
				  `ci07_monto`='".$data['monto']."',				  
				  `ci07_ppmnetdet`='".$data['ppmnetdet']."',
				  `ci07_retencion`='".$data['ret']."'				  
			  WHERE 
				  `ci07_idcobromasivo`='".$data['idCobroMasivo']."';";
	
		$res = mysql_query ( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
			
	public function editarCobroMasivoImpuestoUnico($datoMasivo)
	{		
		$sql="";		
		
		foreach ($datoMasivo as $i => $data):	
			$sql.="UPDATE 
					  `ci07_cobromasivo` 
					SET
					  `ci35_idformapago` = '".$data['idFormaPago']."',
					  `ci37_idtasa` = '".$data['tasa']."',
					  `ci39_idretencion` = '".$data['retencion']."',
					  `ci07_tasaprimeracat` = '".$data['tasaprimeracat']."',
					  `ci07_ingsinretencion` = '".$data['ingsinretencion']."',
					  `ci07_ingconretencion` = '".$data['ingconretencion']."',
					  `ci07_ingsociedad` = '".$data['ingsociedad']."',
					  `ci07_retsociedad` = '".$data['retsociedad']."',
					  `ci07_bolretterceros` = '".$data['bolretterceros']."',
					  `ci07_impuestounico` = '".$data['impuestounico']."',
					  `ci07_ivapago` = '".$data['ivapago']."',
					  `ci07_remanente` = '".$data['remanente']."',
					  `ci07_monto` = '".$data['monto']."',
					  `ci07_ppmnetdet` = '".$data['ppmnetdet']."',
					  `ci07_retencion` = '".$data['ret']."' 
					WHERE `ci07_idcobromasivo` = '".$data['idCobroMasivo']."';";
			
			$sql.="UPDATE 
					  `ci64_impuestounico` 
					SET
					  `ci64_valorimpuesto` = '".$data['impuestounico']."'
					WHERE `ci04_idrut` = '".$data['idRut']."'
					  AND `ci64_fecharegistro` = '".$data['fechaCobro']."';";		
		endforeach;
		
	
		$res = mysqli_multi_query ($this->_link, $sql );
		
		
		if ($res) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function obtieneIdRutDeCobroMasivoF29($idCobroMasivo,$fecha)
	{
		$sql="SELECT 
			  `ci04_idrrut` 
			FROM
			  `ci07_cobromasivo` 
			WHERE `ci07_idcobromasivo` = '".$idCobroMasivo."' 
			  AND `ci07_fechapago` = '".$fecha."'
			  AND `ci33_idconcepto`='1';";
		
		$res=mysql_query($sql);
		
		$idRut="";
		
		while($row=mysql_fetch_array($res))
		{
			$idRut=$row['ci04_idrrut'];
		}
		
		return $idRut;
	}
	
	public function existeCobroRentaAnio($id,$anio,$origen)
	{		
		$filtro='';
		
		if($origen=='1')
		{
			$filtro='AND `ci04_idrrut`="'.$id.'"';
		}else {
			$filtro='AND `ci07_idcobromasivo`="'.$id.'"';
		}		
		
		$sql="SELECT 
			  `ci07_idcobromasivo` 
			FROM
			  `ci07_cobromasivo` 
			WHERE 
			  `ci07_fechapago` = '".$anio."' 
			  $filtro
			  AND `ci33_idconcepto`='2'
			  AND `ci53_idestadocobro` !='5' ;";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function existeCobroPreviredMesAnio($idRut,$nombreConcepto,$fechaRegistro)
	{
		$sql="SELECT 
			  `ci07_idcobromasivo` 
			FROM
			  `ci07_cobromasivo` 
			WHERE `ci07_fechapago` = '".$fechaRegistro."'
			  AND `ci04_idrrut` = '".$idRut."' 
			  AND `ci07_conceptoprevired`='".$nombreConcepto."'
			  AND `ci53_idestadocobro` !='5' 
			  AND `ci33_idconcepto` = '3' ;";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
		
	public function existeCobroF29MesAnio($idRut,$fecha)
	{
		$sql="SELECT 
			  `ci07_idcobromasivo`
			FROM
			  `ci07_cobromasivo` 
			WHERE `ci07_fechapago` = '".$fecha."'
			  AND `ci04_idrrut` = '".$idRut."' 
			  AND `ci53_idestadocobro` !='5' 		
			  AND `ci33_idconcepto` = '1';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function verificaRutCobroMasivo($idRut,$idConcepto)
	{		
		$sql="SELECT 
			  `ci07_idcobromasivo` 
			FROM
			  `ci07_cobromasivo` 
			WHERE `ci04_idrrut` = '".$idRut."' 
			  AND `ci33_idconcepto` = '".$idConcepto."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function obtieneUltimaFormaPago($idRut,$idConcepto,$nombre,$origen)
	{		
		$filtro="";
		
		if($origen=='1')
		{
			$filtro="AND `ci07_conceptoprevired`='".$nombre."'";
		}
		
		$sql="SELECT 
			  `ci35_idformapago` 
			FROM
			  `ci07_cobromasivo` 
			WHERE `ci04_idrrut` = '".$idRut."' 
			AND `ci33_idconcepto` = '".$idConcepto."'
			".$filtro."
			ORDER BY `ci07_idcobromasivo` DESC 
			LIMIT 1";	
			
		$res=mysql_query($sql);
		$idFormaPago="";
		
		while($row=mysql_fetch_array($res))
		{
			$idFormaPago=$row['ci35_idformapago'];
		}
		
		return $idFormaPago;
	}
	
	public function obtieneUltimaTasaPCIngresada($idRut)
	{	
		  $sql="SELECT 
				  `ci07_tasaprimeracat`
				FROM
				  `ci07_cobromasivo` 
				WHERE `ci04_idrrut` = '".$idRut."' 
				  AND `ci33_idconcepto` = '1' 
				ORDER BY `ci07_idcobromasivo` DESC  
				LIMIT 1 ;";
			
		$res=mysql_query($sql);
		$tasaPC="";
	
		while($row=mysql_fetch_array($res))
		{
			$tasaPC=$row['ci07_tasaprimeracat'];
		}
	
		return $tasaPC;
	}
	
	public function obtieneUltimaFecha($idRut)
	{	
		$sql="SELECT 
			  `ci07_fechapago` 
			FROM
			  `ci07_cobromasivo` 
			WHERE `ci04_idrrut` = '".$idRut."' 
			  AND `ci33_idconcepto` = '1' 
			ORDER BY `ci07_fechapago`  ASC 
			LIMIT 1;";
			
		$res=mysql_query($sql);
		$fechaCM="";
	
		while($row=mysql_fetch_array($res))
		{
			$fechaCM=$row['ci07_fechapago'];
		}
	
		return $fechaCM;
	}
	
	public function obtieneRutCobroF29Pendientes($data)
	{
		$where='';
		$filtro="";		
		
		if ($data['perfil']=='4'):
			$where	= "AND `ci07_cobromasivo`.`ci07_registraejecutivo`='1'
					   AND `ci07_cobromasivo`.`ci07_registraadmrem`='0'";
		endif;
	
		if($data['perfil']=='1'||$data['perfil']=='2'||$data['perfil']=='3'):
			$where	= "AND `ci07_cobromasivo`.`ci07_registraejecutivo`='0'
					   AND `ci07_cobromasivo`.`ci07_registraadmrem`='1'";
		endif;
		
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
	
		$sql= "SELECT
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
			  IF(
			    `ci07_cobromasivo`.`ci07_ingsinretencion` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ingsinretencion`
			  ) AS `ci07_ingsinretencion`,
			  IF(
			    `ci07_cobromasivo`.`ci07_ingconretencion` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ingconretencion`
			  ) AS `ci07_ingconretencion`,
			  IF(
			    `ci07_cobromasivo`.`ci07_ingsociedad` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ingsociedad`
			  ) AS `ci07_ingsociedad`,
			  IF(
			    `ci07_cobromasivo`.`ci07_retsociedad` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_retsociedad`
			  ) AS `ci07_retsociedad`,			  
			  
			  IF(
			    `ci07_cobromasivo`.`ci07_tasaprimeracat` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_tasaprimeracat`
			  ) AS `ci07_tasaprimeracat`,
			    `ci37_tasa`.`ci37_valor`,
  				`ci39_retencion`.`ci39_valor`,
			  `ci07_cobromasivo`.`ci07_ppmnetdet`,
			  IF(
			    `ci07_cobromasivo`.`ci07_bolretterceros` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_bolretterceros`
			  ) AS `ci07_bolretterceros`,
			  `ci07_cobromasivo`.`ci07_retencion`,
			  IF(
			    `ci07_cobromasivo`.`ci07_impuestounico` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_impuestounico`
			  ) AS `ci07_impuestounico`,
			  IF(
			    `ci07_cobromasivo`.`ci07_ivapago` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ivapago`
			  ) AS `ci07_ivapago`,
			  IF(
			    `ci07_cobromasivo`.`ci07_remanente` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_remanente`
			  ) AS `ci07_remanente`,
			  `ci07_cobromasivo`.`ci07_monto`,
			  `ci07_cobromasivo`.`ci35_idformapago`,
			  'b' AS ci_rut1,
			  `ci07_cobromasivo`.`ci07_idcobromasivo`
				FROM
				`ci03_cliente`
				INNER JOIN `ci04_rut`
					ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente`
				INNER JOIN `ci07_cobromasivo`
					ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut`
				INNER JOIN `ci11_clave`
					ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut`
				INNER JOIN `ci40_sociedad`
					ON `ci04_rut`.`ci40_idsociedad` = `ci40_sociedad`.`ci40_idsociedad`
				INNER JOIN `ci37_tasa` 
			   		ON `ci07_cobromasivo`.`ci37_idtasa` = `ci37_tasa`.`ci37_idtasa` 
			    INNER JOIN `ci39_retencion` 
			    	ON `ci07_cobromasivo`.`ci39_idretencion` = `ci39_retencion`.`ci39_idretencion` 
				WHERE `ci04_rut`.`ci04_f29` = '1'
				$where	$filtro
				ORDER BY `ci04_idrrut` ;";	
	
		$datos = mysql_query( $sql );
	
		$datosRut = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci07_idcobromasivo'] = $row ['ci07_idcobromasivo'];
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
			$entry ['ci37_valor'] = $row ['ci37_valor'];
			$entry ['ci39_valor'] = $row ['ci39_valor'];
			
			$entry ['ci07_ppmnetdet'] = $row ['ci07_ppmnetdet'];
			$entry ['ci07_bolretterceros'] = $row ['ci07_bolretterceros'];
			$entry ['ci07_retencion'] = $row ['ci07_retencion'];
			$entry ['ci07_impuestounico'] = $row ['ci07_impuestounico'];
			$entry ['ci07_ivapago'] = $row ['ci07_ivapago'];
			$entry ['ci07_remanente'] = $row ['ci07_remanente'];
			$entry ['ci07_monto'] = $row ['ci07_monto'];
			$entry ['ci35_idformapago'] = $row ['ci35_idformapago'];
	
			$entry ['ci_rut1'] = $row ['ci_rut1'];
	
			$datosRut [] = $entry;
		}	
	
		return $datosRut;
	}
	
	public function obtieneDatosEmailPPM($idCliente,$fecha)
	{		
		$sql="SELECT 
			  a.ci04_idrrut,
			  a.rs AS ci_razonsocial,
			  a.ci_montof29 AS ci_montof29,
			  a.ci_montoPrevired ci_montoPrevired,
			  a.ci_otros AS ci_otros,
			  a.ci_honorarios AS ci_honorarios,
			  IFNULL(a.ci_depAngkor_actual, 0) AS ci_depAngkor,
			  IFNULL(a.ci_pagodirecto_actual, 0) AS ci_pagodirecto,
			  IFNULL(a.ci_pec_actual, 0) AS ci_pec,
			  IFNULL(a.ci_ordenpago_actual, 0) AS ci_ordenpago,
			  IFNULL(a.ci_depAngkor_pendiente, 0) AS ci_depAngkor_pendiente,
			  IFNULL(a.ci_pagodirecto_pendiente, 0) AS ci_pagodirecto_pendiente,
			  IFNULL(a.ci_pec_pendiente, 0) AS ci_pec_pendiente,
			  IFNULL(a.ci_ordenpago_pendiente, 0) AS ci_ordenpago_pendiente,
			  a.ci_saldo,
			  a.ci40_idsociedad 
			FROM
			  (SELECT 
			    r.`ci04_idrrut`,
			    r.`ci04_razonsocial` rs,
			    r.`ci40_idsociedad`,
				
				-- MONTO F29
				
			    IFNULL(
			      (SELECT 
			        SUM(
			          IF(
			            `ci07_cobromasivo`.`ci33_idconcepto` = '1',
			            `ci07_cobromasivo`.`ci07_monto`,
			            ''
			          )
			        ) AS ci_montof29 
			      FROM
			        `ci07_cobromasivo` 
			      WHERE `ci07_cobromasivo`.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND `ci07_cobromasivo`.`ci53_idestadocobro`='1'
			        AND  DATE_FORMAT(STR_TO_DATE( `ci07_cobromasivo`.`ci07_fechapago`, '%m-%Y'), '%m-%Y') = DATE_FORMAT( STR_TO_DATE('".$fecha."', '%m-%Y'),'%m-%Y')	),
			      0
			    ) AS ci_montof29,	
			        		
			    -- MONTO PREVIRED    		
				
			    IFNULL(
			      (SELECT 
			        SUM(
			          IF(
			            `ci07_cobromasivo`.`ci33_idconcepto` = '3',
			            `ci07_cobromasivo`.`ci07_monto`,
			            ''
			          )
			        ) AS ci_montoPrevired 
			      FROM
			        `ci07_cobromasivo` 
			      WHERE `ci07_cobromasivo`.`ci04_idrrut` = r.`ci04_idrrut` 
			        AND `ci07_cobromasivo`.`ci53_idestadocobro`='1'
			        AND DATE_FORMAT(STR_TO_DATE( `ci07_cobromasivo`.`ci07_fechapago`, '%m-%Y'), '%m-%Y') = DATE_FORMAT( STR_TO_DATE('".$fecha."', '%m-%Y'),'%m-%Y')),
			      0
			    ) AS ci_montoPrevired,
				
			    -- OTROS
			      
			    IFNULL(
			      (SELECT 
			        SUM(dato.ci_monto) AS ci_monto 
			      FROM
			        (SELECT 
				    'ci' AS tipo,
				    c.`ci05_valormoneda` AS ci_monto,
				    rut.`ci04_idrrut` AS rut 
				  FROM
				    `ci05_cobroindividual` c 
				    INNER JOIN `ci04_rut` rut 
				      ON c.`ci04_idrrut` = rut.`ci04_idrrut` 
				  WHERE rut.`ci04_estadodisponibilidad` = '1' 
				    AND c.`ci53_idestadocobro` = '1' 
				    AND c.`ci35_idformapago` = '1'      
				    AND STR_TO_DATE(c.`ci05_fechacobro`, '%d-%m-%Y') <= STR_TO_DATE('31-".$fecha."', '%d-%m-%Y')
				  UNION
				  SELECT 
				    'ho' AS tipo,
				    h.`ci06_valormoneda` AS ci_monto,
				    rut.`ci04_idrrut` AS rut 
				  FROM
				    `ci06_honorario` h 
				    INNER JOIN `ci04_rut` rut 
				      ON h.`ci04_idrrut` = rut.`ci04_idrrut` 
				  WHERE rut.`ci04_estadodisponibilidad` = '1' 
				    AND h.`ci53_idestadocobro` = '1' 
				    AND h.`ci35_idformapago` = '5' 
				    AND STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y') 
				  UNION
				  SELECT 
				    'cm' AS tipo,
				    cm.`ci07_monto` AS ci_monto,
				    rut.`ci04_idrrut` AS rut 
				  FROM
				    `ci07_cobromasivo` cm 
				    INNER JOIN `ci04_rut` rut 
				      ON cm.`ci04_idrrut` = rut.`ci04_idrrut` 
				  WHERE rut.`ci04_estadodisponibilidad` = '1' 
				    AND cm.`ci53_idestadocobro` = '1' 
				    AND cm.`ci35_idformapago` = '1' 
				    AND STR_TO_DATE(CONCAT('01-', cm.`ci07_fechapago`), '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y')) dato 
			        WHERE rut = r.`ci04_idrrut`),
			        0
			      ) AS ci_otros,
				      
				  
				  -- HONORARIOS ANGKOR
				    		
			     				      		
				    (
				    		
				    SELECT 
					  SUM(`ci06_valormoneda`) AS valor 
					FROM
					  `ci06_honorario` 
					WHERE `ci04_idrrut` = r.`ci04_idrrut` 
					  AND `ci06_honorario`.`ci53_idestadocobro` = '1' 
					  AND DATE_FORMAT(
					    STR_TO_DATE(`ci06_fechacobro`, '%d-%m-%Y'),
					    '%m-%Y'
					  ) = DATE_FORMAT(
					    STR_TO_DATE('".$fecha."', '%m-%Y'),
					    '%m-%Y'
					  )
					    		
					  ) AS ci_honorarios,
				
				
			      -- SALDO CLIENTE
			          		
			          		
			      (SELECT 
			        `ci03_saldo` 
			      FROM
			        `ci03_cliente` 
			      WHERE `ci03_idcliente` = '".$idCliente."') AS ci_saldo,
			      		
			      		
			      -- DESPOSITO ANGKOR ACTUAL
				
			      (SELECT 
			        SUM(IFNULL(d.ci_monto, 0)) AS ci_monto 
			      FROM
			        (
			      		
			      	SELECT 
					  'ci' AS tipo,
					  `ci05_cobroindividual`.`ci05_valormoneda` AS ci_monto,
					  `ci04_rut`.`ci04_idrrut` AS rut
					FROM
					  `ci03_cliente` 
					  INNER JOIN `ci04_rut` 
					    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
					  INNER JOIN `ci05_cobroindividual` 
					    ON `ci04_rut`.`ci04_idrrut` = `ci05_cobroindividual`.`ci04_idrrut` 
					WHERE DATE_FORMAT(
					    STR_TO_DATE(
					      `ci05_cobroindividual`.`ci05_fechacobro`,
					      '%d-%m-%Y'
					    ),
					    '%m-%Y'
					  ) = DATE_FORMAT(
					    STR_TO_DATE('".$fecha."', '%m-%Y'),
					    '%m-%Y'
					  ) 
					  AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
					  AND `ci05_cobroindividual`.`ci53_idestadocobro` = '1' 
					  AND `ci05_cobroindividual`.`ci35_idformapago` = '1'
					  AND `ci03_cliente`.`ci03_idcliente`='".$idCliente."' 
			      		
			      		UNION
			      		
			        SELECT 
			          'ho' AS tipo,
			          `ci06_honorario`.`ci06_valormoneda` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci06_honorario` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci06_honorario`.`ci04_idrrut`	      		
			        WHERE 
			      	  DATE_FORMAT(STR_TO_DATE(`ci06_honorario`.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y') = DATE_FORMAT( STR_TO_DATE('".$fecha."', '%m-%Y'),'%m-%Y') 
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci06_honorario`.`ci53_idestadocobro` = '1'
			          AND `ci06_honorario`.`ci35_idformapago`='5'
			      	  AND `ci03_cliente`.`ci03_idcliente`='".$idCliente."' 		
			          		
			        UNION
			          		
			        SELECT 
			      	  'cm' AS tipo,
			          `ci07_cobromasivo`.`ci07_monto` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci07_cobromasivo` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			        WHERE `ci07_cobromasivo`.`ci33_idconcepto` != '2' 
			          AND DATE_FORMAT(
			            STR_TO_DATE(
			              `ci07_cobromasivo`.`ci07_fechapago`,
			              '%m-%Y'
			            ),
			            '%m-%Y'
			          ) = DATE_FORMAT( STR_TO_DATE('".$fecha."', '%m-%Y'),'%m-%Y') 
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1'
			          AND `ci07_cobromasivo`.`ci35_idformapago`='1'
			          AND `ci03_cliente`.`ci03_idcliente`='".$idCliente."' 
			          		) AS d 
			      WHERE rut = r.`ci04_idrrut` 
			          		
			       ) AS ci_depAngkor_actual,
			          		
			          		
			      -- DESPOSITO ANGKOR PENDIENTE
				
			      (SELECT 
			        SUM(IFNULL(d.ci_monto, 0)) AS ci_monto 
			      FROM
			        (SELECT 
			          'ci' AS tipo,		
			          `ci05_cobroindividual`.`ci05_valormoneda` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci05_cobroindividual` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci05_cobroindividual`.`ci04_idrrut` 
			        WHERE 
			          STR_TO_DATE(`ci05_cobroindividual`.`ci05_fechacobro`, '%d-%m-%Y') <= STR_TO_DATE('01-".$fecha."', '%d-%m-%Y')
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci05_cobroindividual`.`ci53_idestadocobro` = '1' 
			          AND `ci05_cobroindividual`.`ci35_idformapago`='1'
			          AND `ci03_cliente`.`ci03_idcliente`='".$idCliente."'	
			          		
			          		
			        UNION
			          		
			        SELECT 
			          'ho' AS tipo,
			          `ci06_honorario`.`ci06_valormoneda` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci06_honorario` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci06_honorario`.`ci04_idrrut`
			        WHERE			          		
			          STR_TO_DATE(`ci06_honorario`.`ci06_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y')
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci06_honorario`.`ci53_idestadocobro` = '1' 	
			          AND `ci06_honorario`.`ci35_idformapago`='5'
			          AND `ci03_cliente`.`ci03_idcliente`='".$idCliente."'
			          		
			          		
			        UNION
			          		
			        SELECT 
			          'cm' AS tipo,		
			          `ci07_cobromasivo`.`ci07_monto` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci07_cobromasivo` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			        WHERE 			          
			          STR_TO_DATE(CONCAT('01-', `ci07_cobromasivo`.`ci07_fechapago`), '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y')
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci07_cobromasivo`.`ci33_idconcepto` != '2' 
			          AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1'
			          AND `ci07_cobromasivo`.`ci35_idformapago`='1'
			          AND `ci03_cliente`.`ci03_idcliente`='".$idCliente."') AS d 
			      WHERE rut = r.`ci04_idrrut` 
			      ) AS ci_depAngkor_pendiente,
			        		
			       -- DESPOSITO PAGO DIRECTO CLIENTE PENDIENTE
			          		
			      '0' AS ci_pagodirecto_pendiente,
			        		
			       -- DESPOSITO PAGO DIRECTO CLIENTE ACTUAL
			        		
			      (SELECT 
			        SUM(IFNULL(d.ci_monto, 0)) AS ci_monto 
			      FROM
			        (
			        SELECT 
			          `ci07_cobromasivo`.`ci07_monto` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut,
			          `ci03_cliente`.`ci03_idcliente` AS cliente
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci07_cobromasivo` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			        WHERE `ci07_cobromasivo`.`ci33_idconcepto` != '2' 
			          AND DATE_FORMAT(
			            STR_TO_DATE(
			              `ci07_cobromasivo`.`ci07_fechapago`,
			              '%m-%Y'
			            ),
			            '%m-%Y'
			          ) = DATE_FORMAT( STR_TO_DATE('".$fecha."', '%m-%Y'),'%m-%Y')
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1'
			          AND `ci07_cobromasivo`.`ci35_idformapago`='3') AS d 
			      WHERE rut = r.`ci04_idrrut` 
			        AND cliente = '".$idCliente."' -- ID CLIENTE CAMBIAR
			        )  AS ci_pagodirecto_actual,
			        		
			      -- DESPOSITO PEC CLIENTE PENDIENTE
			     
			      '0' AS  ci_pec_pendiente,
			        
			       -- DESPOSITO PEC CLIENTE ACTUAL  		
			        		
			      (SELECT 
			        SUM(IFNULL(d.ci_monto, 0)) AS ci_monto 
			      FROM
			        (
			        SELECT 
			          `ci07_cobromasivo`.`ci07_monto` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut,
			          `ci03_cliente`.`ci03_idcliente` AS cliente
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci07_cobromasivo` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			        WHERE `ci07_cobromasivo`.`ci33_idconcepto` != '2' 
			          AND DATE_FORMAT(
			            STR_TO_DATE(
			              `ci07_cobromasivo`.`ci07_fechapago`,
			              '%m-%Y'
			            ),
			            '%m-%Y'
			          ) = DATE_FORMAT( STR_TO_DATE('".$fecha."', '%m-%Y'),'%m-%Y')		
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1'
			           AND `ci07_cobromasivo`.`ci35_idformapago`='4') AS d 
			      WHERE rut = r.`ci04_idrrut` 
			        AND cliente = '".$idCliente."') AS ci_pec_actual,
			        		
			     	-- DESPOSITO ORDEN PAGO CLIENTE PENDIENTE
			      
			      '0' AS ci_ordenpago_pendiente,			        		
			        		
			     	-- DESPOSITO ORDEN PAGO CLIENTE ACTUAL			        		
			        		
			       (SELECT 
			        	SUM(d.ci_monto) AS ci_monto
			      FROM
			        (
			        SELECT 
			          `ci07_cobromasivo`.`ci07_monto` AS ci_monto,
			          `ci04_rut`.`ci04_idrrut` AS rut,
			          `ci03_cliente`.`ci03_idcliente` AS cliente
			        FROM
			          `ci03_cliente` 
			          INNER JOIN `ci04_rut` 
			            ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			          INNER JOIN `ci07_cobromasivo` 
			            ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			        WHERE `ci07_cobromasivo`.`ci33_idconcepto` != '2' 
			          AND DATE_FORMAT(
			            STR_TO_DATE(
			              `ci07_cobromasivo`.`ci07_fechapago`,
			              '%m-%Y'
			            ),
			            '%m-%Y'
			          ) = DATE_FORMAT( STR_TO_DATE('".$fecha."', '%m-%Y'),'%m-%Y')	
			          AND `ci04_rut`.`ci04_estadodisponibilidad` = '1' 
			          AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1'
			          AND `ci07_cobromasivo`.`ci35_idformapago` ='2') AS d 
			      WHERE rut = r.`ci04_idrrut` 
			        AND cliente = '".$idCliente."' 
			       ) AS  ci_ordenpago_actual		        		
			        		
			  FROM
			    `ci03_cliente` c 
			    INNER JOIN `ci04_rut` r 
			      ON c.`ci03_idcliente` = r.`ci03_idcliente` 
			  WHERE 
			      c.`ci03_idcliente` = '".$idCliente."' 
				 AND (
				    `ci04_f29` = '1' 
				    OR `ci04_previred` = '1'
				  )
			  ) a 
			ORDER BY ci40_idsociedad ;";
		
		$datos = mysql_query ( $sql );
		
		$datosEmailPPM = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) 
		{			
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci_razonsocial'] = $row ['ci_razonsocial'];			
			$entry ['ci_montof29'] = $row ['ci_montof29'];
			$entry ['ci_montoPrevired'] = $row ['ci_montoPrevired'];
			$entry ['ci_otros'] = $row ['ci_otros'];
			$entry ['ci_honorarios'] = $row ['ci_honorarios'];
			
			
			$entry ['ci_depAngkor'] = $row ['ci_depAngkor'];
			$entry ['ci_pagodirecto'] = $row ['ci_pagodirecto'];
			$entry ['ci_pec'] = $row ['ci_pec'];
			$entry ['ci_ordenpago'] = $row ['ci_ordenpago'];
			
		
			
			$entry ['ci_depAngkor_pendiente'] = $row ['ci_depAngkor_pendiente'];
			$entry ['ci_pagodirecto_pendiente'] = $row ['ci_pagodirecto_pendiente'];
			$entry ['ci_pec_pendiente'] = $row ['ci_pec_pendiente'];
			$entry ['ci_ordenpago_pendiente'] = $row ['ci_ordenpago_pendiente'];
			
			
			$entry ['ci_saldo'] = $row ['ci_saldo'];			
			
			$datosEmailPPM [] = $entry;
		}
		
		return $datosEmailPPM;
		
	}
	
	public function razonesSocialesClient($idCliente)
	{
		$sql="SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci04_rut`.`ci04_razonsocial` 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
			ORDER BY ci04_idrrut;";
		
		$datos = mysql_query ( $sql );
		
		$razonSocial = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
		
			$razonSocial [] = $entry;
		}
		
		return $razonSocial;
		
	}
	
	public function obtieneDatosPrvired($idCliente,$fecha)
	{
		   $sql="SELECT 
				  previ.ci04_razonsocial,
				  previ.ci07_conceptoprevired,
				  previ.ci07_monto,
				  previ.ci35_tipopago 
				FROM
				  (SELECT 
				    `ci04_rut`.`ci04_razonsocial`,
				    `ci07_cobromasivo`.`ci07_conceptoprevired`,
				    `ci07_cobromasivo`.`ci07_monto`,
				    `ci35_formapago`.`ci35_tipopago` 
				  FROM
				    `ci03_cliente` 
				    INNER JOIN `ci04_rut` 
				      ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
				    INNER JOIN `ci07_cobromasivo` 
				      ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
				    INNER JOIN `ci35_formapago` 
				      ON `ci07_cobromasivo`.`ci35_idformapago` = `ci35_formapago`.`ci35_idformapago` 
				  WHERE `ci07_cobromasivo`.`ci33_idconcepto` = '3' 
				    AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'
				    AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1' 
				    AND DATE_FORMAT(
				      STR_TO_DATE(
				        `ci07_cobromasivo`.`ci07_fechapago`,
				        '%m-%Y'
				      ),
				      '%m-%Y'
				    ) = DATE_FORMAT(
				      STR_TO_DATE('".$fecha."', '%m-%Y'),
				      '%m-%Y'
				    ) 
				      		
				  UNION
				      		
				  SELECT 
				    previred.ci04_razonsocial,
				    previred.ci_nombreconcepto,
				    '0' AS ci07_monto,
				    '' AS ci35_tipopago 
				  FROM
				    (SELECT 
				      `ci04_rut`.`ci04_idrrut`,
				      `ci03_cliente`.`ci03_idcliente`,
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
				      AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
				    UNION
				    SELECT 
				      `ci04_rut`.`ci04_idrrut`,
				      `ci03_cliente`.`ci03_idcliente`,
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
				      AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'
				    UNION
				    SELECT 
				      `ci04_rut`.`ci04_idrrut`,
				      `ci03_cliente`.`ci03_idcliente`,
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
				      AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
				    UNION
				    SELECT 
				      `ci04_rut`.`ci04_idrrut`,
				      `ci03_cliente`.`ci03_idcliente`,
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
				      AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'
				    UNION
				    SELECT 
				      `ci04_rut`.`ci04_idrrut`,
				      `ci03_cliente`.`ci03_idcliente`,
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
				      AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'
				    UNION
				    SELECT 
				      `ci04_rut`.`ci04_idrrut`,
				      `ci03_cliente`.`ci03_idcliente`,
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
				      AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'
				    ORDER BY `ci04_idrrut`) AS previred) AS previ 
				GROUP BY ci04_razonsocial;";
		
		$datos = mysql_query ( $sql );
		
		$datosEmailPrevired = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{			
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];				
			$entry ['ci07_conceptoprevired'] = $row ['ci07_conceptoprevired'];
			$entry ['ci07_monto'] = $row ['ci07_monto'];				
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
				
			$datosEmailPrevired [] = $entry;
		}
		
		return $datosEmailPrevired;
	}
	
	public function obtieneRazonSocialPrevired($idCliente)
	{
		$sql="SELECT 
				`ci03_cliente`.`ci03_idcliente`,
				`ci04_rut`.`ci04_razonsocial` 
			  FROM
			    `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
				AND `ci04_rut`.`ci04_previred` = '1' ;";
						
		$datos = mysql_query ( $sql );
		
		$datosMasivoRazon = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$datosMasivoRazon [] = $entry;
		}
		
		return $datosMasivoRazon;
	}
	
	public function obtieneDatosEmailRenta($idCliente)
	{
		$sql="SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  cm.`ci07_monto`,
				
			  IFNULL(
			    (SELECT 
			      SUM(`ci07_cobromasivo`.`ci07_monto`) AS ci07_monto
			    FROM
			      `ci03_cliente` 
			      INNER JOIN `ci04_rut` 
			        ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			      INNER JOIN `ci07_cobromasivo` 
			        ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			    WHERE `ci07_cobromasivo`.`ci33_idconcepto` = '2' 
			      AND `ci07_cobromasivo`.`ci35_idformapago` = '1' 
				  AND `ci07_cobromasivo`.`ci53_idestadocobro` != '5' 
			      AND `ci07_cobromasivo`.`ci07_fechapago`=YEAR(NOW())     
			      AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'    
			      AND `ci04_rut`.`ci04_idrrut` = r.`ci04_idrrut`),
			    0
			  ) AS ci_depangkor,
			      		
			  IFNULL(
			    (SELECT 
			      SUM(`ci07_cobromasivo`.`ci07_monto`)AS ci07_monto 
			    FROM
			      `ci03_cliente` 
			      INNER JOIN `ci04_rut` 
			        ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			      INNER JOIN `ci07_cobromasivo` 
			        ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			    WHERE `ci07_cobromasivo`.`ci33_idconcepto` = '2' 
			      AND `ci07_cobromasivo`.`ci35_idformapago` = '3'
			      AND `ci07_cobromasivo`.`ci53_idestadocobro` != '5' 		
			      AND `ci07_cobromasivo`.`ci07_fechapago`=YEAR(NOW()) 
			      AND `ci03_cliente`.`ci03_idcliente` =  '".$idCliente."' 
			      AND `ci04_rut`.`ci04_idrrut` = r.`ci04_idrrut`),
			    0
			  ) AS ci_pagodirecto 
			      		
			FROM
			  `ci03_cliente` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` cm 
			    ON r.`ci04_idrrut` = cm.`ci04_idrrut` 
			WHERE cm.`ci33_idconcepto` = '2' 
			  AND cm.`ci07_fechapago` = YEAR(NOW()) 
			  AND cm.`ci53_idestadocobro`='1'
			  AND c.`ci03_idcliente` =  '".$idCliente."';";

		$datos = mysql_query ( $sql );
		
		$datosEmailRenta = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
				
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci07_monto'] = $row ['ci07_monto'];
			$entry ['ci_depangkor'] = $row ['ci_depangkor'];
			$entry ['ci_pagodirecto'] = $row ['ci_pagodirecto'];
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
				
			$datosEmailRenta [] = $entry;
		}
		
		return $datosEmailRenta;
		
	}
	
	public function obtieneRazonSocialCobros($idCliente,$idSociedad,$fecha)
	{
		$sql="SELECT
			  `ci04_rut`.`ci04_razonsocial` as ci_razon
			FROM
			  `ci03_cliente`
			  INNER JOIN `ci04_rut`
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente`
			  INNER JOIN `ci07_cobromasivo`
			    ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut`
			  INNER JOIN `ci40_sociedad`
			    ON `ci04_rut`.`ci40_idsociedad` = `ci40_sociedad`.`ci40_idsociedad`
			WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."'
			  AND SUBSTRING(
			    `ci07_cobromasivo`.`ci07_fechapago`,
			    1,
			    7
			  ) = '".$fecha."' 
			  AND `ci04_rut`.`ci40_idsociedad` = '".$idSociedad."'
			  AND `ci07_cobromasivo`.`ci33_idconcepto` = '1';";
	
		$data=mysql_query ( $sql );
		$razonSocial = array ();
	
		while ( $row = mysql_fetch_array ($data ) )
		{
			$entry ['ci_razon'] = $row ['ci_razon'];
			$razonSocial [] = $entry;
		}
	
		return $razonSocial;
	}
		
	public function obtieneResumenBoleteo($idCliente,$idSociedad,$mes,$anio,$idRut)
	{
		$sql="SELECT 
			  `ci04_rut`.`ci04_razonsocial`,
			  SUBSTRING(
			    `ci07_cobromasivo`.`ci07_fechapago`,
			    1,
			    2
			  ) AS ci_mes,
				
			  (SELECT 
			    `ci10_monto` 
			  FROM
			    `ci10_metaboleteo` 
			  WHERE `ci04_idrrut` = `ci04_rut`.`ci04_idrrut` 
			    AND `ci10_anio` = '".$anio."') AS ci_montoboleteo,				
				
			  IF(
			    `ci07_cobromasivo`.`ci07_ingsinretencion` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ingsinretencion`
			  ) AS ci07_ingsinretencion,
			    		
			  IF(
			    `ci07_cobromasivo`.`ci07_ingconretencion` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ingconretencion`
			  ) AS ci07_ingconretencion,
			    		
			  IF(
			    `ci07_cobromasivo`.`ci07_ingsociedad` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ingsociedad`
			  ) AS ci07_ingsociedad 
			    		
			FROM
			  `ci07_cobromasivo` 
			  INNER JOIN `ci04_rut` 
			    ON `ci07_cobromasivo`.`ci04_idrrut` = `ci04_rut`.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` 
			    ON `ci04_rut`.`ci03_idcliente` = `ci03_cliente`.`ci03_idcliente` 
			WHERE `ci07_cobromasivo`.`ci33_idconcepto` = '1' 
			  AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
			  AND `ci04_rut`.`ci40_idsociedad` = '".$idSociedad."' 
  			  AND `ci04_rut`.`ci04_idrrut`='".$idRut."'		
			  AND SUBSTRING(
			    `ci07_cobromasivo`.`ci07_fechapago`,
			    4,
			    4
			  ) = '".$anio."' 
			  AND SUBSTRING(
			    `ci07_cobromasivo`.`ci07_fechapago`,
			    1,
			    2
			  ) = '".$mes."';";
		
		$datos = mysql_query ( $sql );
		
		$datosBoleteoResumen = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) 
		{		
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci_mes'] = $row ['ci_mes'];
			$entry ['ci_montoboleteo'] = $row ['ci_montoboleteo'];
			$entry ['ci07_ingsinretencion'] = $row ['ci07_ingsinretencion'];
			$entry ['ci07_ingconretencion'] = $row ['ci07_ingconretencion'];
			$entry ['ci07_ingsociedad'] = $row ['ci07_ingsociedad'];
			
		
			$datosBoleteoResumen [] = $entry;
		}
		
		return $datosBoleteoResumen;
	}
		
	public function obtieneResumenPPM($idCliente,$idConcepto,$idSociedad,$fecha)
	{		
		$filtro="";
		$filtro2="";
		
		if($idConcepto=='1')
		{
			$filtro="AND `ci40_sociedad`.`ci40_idsociedad`='".$idSociedad."'";
			$filtro2="AND `ci04_rut`.`ci40_idsociedad`='".$idSociedad."'";
		}
		
		
		$sql="SELECT 
			  dato.ci04_idrrut,
			  dato.`ci04_razonsocial`,
			  dato.`ci40_tiposociedad`,
			  dato.`ci40_idsociedad`,
			  dato.ci07_ingsinretencion,
			  dato.`ci07_ingconretencion`,
			  dato.`ci07_bolretterceros`,
			  dato.`ci07_retencion`,
			  dato.`ci07_ingsociedad`,
			  dato.`ci07_retsociedad`,
			  dato.ci_impuesto,
			  IFNULL(dato.`ci07_impuestounico`,0)  AS ci07_impuestounico,
			  dato.`ci07_ivapago`,
			  dato.`ci07_remanente`,
			  dato.`ci07_monto`,
			  dato.`ci35_tipopago`,
			  dato.`ci04_iva` 
			FROM
			  (SELECT 
			    `ci04_rut`.`ci04_idrrut`,
			    `ci04_rut`.`ci04_razonsocial`,
			    `ci40_sociedad`.`ci40_tiposociedad`,
			    `ci40_sociedad`.`ci40_idsociedad`,
			    `ci07_cobromasivo`.`ci07_ingsinretencion` AS ci07_ingsinretencion,
			    `ci07_cobromasivo`.`ci07_ingconretencion`,
			    `ci07_cobromasivo`.`ci07_bolretterceros` AS `ci07_bolretterceros`,
			    `ci07_cobromasivo`.`ci07_retencion`,
			    `ci07_cobromasivo`.`ci07_ingsociedad`,
			    `ci07_cobromasivo`.`ci07_retsociedad`,
				
			    IF(
			      `ci40_sociedad`.`ci40_idsociedad` = '0',
			      `ci37_tasa`.`ci37_valor`,
			      IF(
			        `ci40_sociedad`.`ci40_idsociedad` = '1',
			        `ci07_cobromasivo`.`ci07_tasaprimeracat`,
			        IF(
			          `ci40_sociedad`.`ci40_idsociedad` = '2',
			          `ci37_tasa`.`ci37_valor`,
			          `ci39_retencion`.`ci39_valor`
			        )
			      )
			    ) AS ci_impuesto,
								
			       (SELECT 
			        `ci64_impuestounico`.`ci64_valorimpuesto` 
			      FROM
			        `ci64_impuestounico` 
			      WHERE `ci64_impuestounico`.`ci04_idrut` = `ci04_rut`.`ci04_idrrut` 
			        AND `ci64_impuestounico`.`ci64_fecharegistro` = '".$fecha."') AS ci07_impuestounico,
			    
				
				`ci07_cobromasivo`.`ci07_ivapago`,
			    `ci07_cobromasivo`.`ci07_remanente`,
			    `ci07_cobromasivo`.`ci07_monto`,
			    `ci35_formapago`.`ci35_tipopago`,
			    `ci04_rut`.`ci04_iva` 
			  FROM
			    `ci03_cliente` 
			    INNER JOIN `ci04_rut` 
			      ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			    INNER JOIN `ci07_cobromasivo` 
			      ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			    INNER JOIN `ci37_tasa` 
			      ON `ci07_cobromasivo`.`ci37_idtasa` = `ci37_tasa`.`ci37_idtasa` 
			    INNER JOIN `ci39_retencion` 
			      ON `ci07_cobromasivo`.`ci39_idretencion` = `ci39_retencion`.`ci39_idretencion` 
			    INNER JOIN `ci40_sociedad` 
			      ON `ci04_rut`.`ci40_idsociedad` = `ci40_sociedad`.`ci40_idsociedad` 
			    INNER JOIN `ci35_formapago` 
			      ON `ci07_cobromasivo`.`ci35_idformapago` = `ci35_formapago`.`ci35_idformapago` 
			  WHERE `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' 
			    AND `ci04_rut`.`ci04_f29`='1'
			    AND `ci07_cobromasivo`.`ci33_idconcepto` = '1' 
			    AND `ci07_cobromasivo`.`ci53_idestadocobro` = '1' 
			    $filtro
			    AND DATE_FORMAT(
			      STR_TO_DATE(
			        `ci07_cobromasivo`.`ci07_fechapago`,
			        '%m-%Y'
			      ),
			      '%m-%Y'
			    ) = DATE_FORMAT(
			      STR_TO_DATE('".$fecha."', '%m-%Y'),
			      '%m-%Y'
			    )
			  AND ci04_rut.`ci04_estadodisponibilidad` <> 0			      		
			  UNION
			  SELECT 
			    `ci04_rut`.`ci04_idrrut`,
			    `ci04_rut`.`ci04_razonsocial`,
			    '' AS `ci40_tiposociedad`,
			    '' AS `ci40_idsociedad`,
			    '0' AS ci07_ingsinretencion,
			    '0' AS `ci07_ingconretencion`,
			    '0' AS `ci07_bolretterceros`,
			    '0' AS `ci07_retencion`,
			    '0' AS `ci07_ingsociedad`,
			    '0' AS `ci07_retsociedad`,
			    '0' AS ci_impuesto,
			      		
			     (SELECT 
			        `ci64_impuestounico`.`ci64_valorimpuesto` 
			      FROM
			        `ci64_impuestounico` 
			      WHERE `ci64_impuestounico`.`ci04_idrut` = `ci04_rut`.`ci04_idrrut` 
			        AND `ci64_impuestounico`.`ci64_fecharegistro` = '".$fecha."') AS ci07_impuestounico,
			        		
			    '0' AS `ci07_ivapago`,
			    '0' AS `ci07_remanente`,
			    '0' AS `ci07_monto`,
			    '' AS `ci35_tipopago`,
			    '' AS `ci04_iva` 
			  FROM
			    `ci04_rut` 
			  WHERE `ci03_idcliente` = '".$idCliente."'
			  AND `ci04_rut`.`ci04_f29`='1'
			  AND ci04_rut.`ci04_estadodisponibilidad` <> 0
			 $filtro2) AS dato 
			GROUP BY ci04_idrrut 
			ORDER BY ci04_idrrut ";
		
		$data=mysql_query ( $sql );
		
		$datosPPM = array ();
		
		while ( $row = mysql_fetch_array ($data ) )
		{
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci40_tiposociedad'] = $row ['ci40_tiposociedad'];
			$entry ['ci40_idsociedad'] = $row ['ci40_idsociedad'];
			$entry ['ci07_ingsinretencion'] = $row ['ci07_ingsinretencion'];
			$entry ['ci07_ingconretencion'] = $row ['ci07_ingconretencion'];
			$entry ['ci07_bolretterceros'] = $row ['ci07_bolretterceros'];
			$entry ['ci07_retencion'] = $row ['ci07_retencion'];
			$entry ['ci07_ingsociedad'] = $row ['ci07_ingsociedad'];
			$entry ['ci07_retsociedad'] = $row ['ci07_retsociedad'];
			$entry ['ci_impuesto'] = $row ['ci_impuesto'];
			$entry ['ci07_impuestounico'] = $row ['ci07_impuestounico'];
			$entry ['ci07_ivapago'] = $row ['ci07_ivapago'];
			$entry ['ci07_remanente'] = $row ['ci07_remanente'];
			$entry ['ci07_monto'] = $row ['ci07_monto'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			$entry ['ci04_iva'] = $row ['ci04_iva'];
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$datosPPM [] = $entry;
		}
		
		return $datosPPM;
	}
	
	public function razonSocialResumenPPM($idCliente,$idSociedad)
	{
		$sql="SELECT 
			  `ci04_rut`.`ci04_idrrut`,
			  `ci04_rut`.`ci04_razonsocial`,
  			  `ci04_rut`.`ci04_iva`
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			WHERE `ci04_rut`.`ci40_idsociedad` = '".$idSociedad."' 
			AND `ci03_cliente`.`ci03_idcliente`='".$idCliente."';";
		
		$data=mysql_query ( $sql );
		
		$datosRazonPPM = array ();
		
		while ( $row = mysql_fetch_array ($data ) )
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci04_iva'] = $row ['ci04_iva'];
			
			$datosRazonPPM [] = $entry;
		}
		
		return $datosRazonPPM;
	}
	
	public function verificaInfoDescargaPDFCliente($idCliente,$fecha)
	{	
		$sql="SELECT 
			  `ci07_cobromasivo`.`ci07_idcobromasivo` 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			WHERE DATE_FORMAT(
			    STR_TO_DATE(
			      `ci07_cobromasivo`.`ci07_fechapago`,
			      '%m-%Y'
			    ),
			    '%m-%Y'
			  ) = '".$fecha."' 
			  AND `ci03_cliente`.`ci03_idcliente` = '".$idCliente."' ;";
		
		$row=mysql_query($sql);
		
		if(mysql_num_rows($row)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
		
	public function obtieneIDCliente($idUsuario)
	{	
		if($idUsuario!='')
		{
			$sql="SELECT `ci03_idcliente`,`ci03_nombre` FROM `ci03_cliente` WHERE `lc01_idUsuario`='".$idUsuario."';";
		}
		else
		{
			$sql="SELECT `ci03_idcliente`,`ci03_nombre` FROM `ci03_cliente`;";
		}
		
		$data=mysql_query ( $sql );
		
		$idCliente = array ();
		
		while ( $row = mysql_fetch_array ($data ) )
		{
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			
			$idCliente [] = $entry;
		}
		return $idCliente;
	}
	
	public function verificaDatosMasivos($fecha, $origen)
	{	
		$sql="SELECT 
			  `ci07_idcobromasivo` 
				FROM
				  `ci07_cobromasivo` 
				WHERE `ci07_fechapago` = '".$fecha."' ;";
		
		$res=mysql_query($sql);
		
		if(mysql_num_rows($res)>0)
		{
			return true;
		}
		else 
		{
			return false;
		}		
	}
			
	public function buscarCobroMasivo($data)
	{		
		$where="";
		
		if ($data['ejecutivoSelect']!='')
		{
			$where.=" AND `lc01_usuario`.`lc01_idUsuario`='".$data['ejecutivoSelect']."' ";
		}	
			
		if ($data['nombreClienteSelect']!='') 
		{
			$where.=" AND `ci03_cliente`.`ci03_idcliente`='".$data['nombreClienteSelect']."' ";
		}
		
		if ( $data['rutCobroMasivoSelect']!='') 
		{
			$where.=" AND `ci04_rut`.`ci04_idrrut`='".$data['rutCobroMasivoSelect']."' ";
		}
		
		$sql="SELECT 
			  `ci07_cobromasivo`.`ci07_idcobromasivo`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci04_rut`.`ci04_iva`,
			  `ci04_rut`.`ci04_previred`,
			  IF(
			    `ci04_rut`.`ci04_numerosociedad` = 0,
			    'PN',
			    `ci04_rut`.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  `ci04_rut`.`ci04_razonsocial`,
			  `ci11_clave`.`ci11_sii`,
			  `ci11_clave`.`ci11_previred`,
			  `ci07_cobromasivo`.`ci07_conceptoprevired`,
			  `ci40_sociedad`.`ci40_tiposociedad`,
			  `ci07_cobromasivo`.`ci07_ingsinretencion`,
			  `ci07_cobromasivo`.`ci07_ingconretencion`,
			  `ci07_cobromasivo`.`ci07_ingsociedad`,
			  `ci07_cobromasivo`.`ci07_retsociedad`,
			  `ci07_cobromasivo`.`ci07_tasaprimeracat`,
			  `ci07_cobromasivo`.`ci07_impuestounico`,
			  `ci07_cobromasivo`.`ci39_idretencion`,
			  IF(
			    `ci07_cobromasivo`.`ci07_ppmnetdet` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_ppmnetdet`
			  ) AS ci07_ppmnetdet,
			  IF(
			    `ci07_cobromasivo`.`ci07_bolretterceros` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_bolretterceros`
			  ) AS ci07_bolretterceros,
			  IF(
			    `ci07_cobromasivo`.`ci07_retencion` = '',
			    0,
			    `ci07_cobromasivo`.`ci07_retencion`
			  ) AS ci07_retencion,
			  `ci07_cobromasivo`.`ci07_impuestounico`,
			  `ci07_cobromasivo`.`ci07_ivapago`,
			  `ci07_cobromasivo`.`ci07_remanente`,
			  `ci07_cobromasivo`.`ci07_monto`,
			  `ci07_cobromasivo`.`ci35_idformapago`,
			  `ci07_cobromasivo`.`ci37_idtasa`,
			  `ci07_cobromasivo`.`ci07_fechapago`,
				(SELECT 
			    `ci37_valor` 
			  FROM
			    `ci37_tasa` 
			  WHERE `ci37_idtasa` = `ci07_cobromasivo`.`ci37_idtasa`) AS ci_valortasapn,
			  
			  (SELECT 
			    `ci39_valor` 
			  FROM
			    `ci39_retencion` 
			  WHERE  `ci39_idretencion` = `ci07_cobromasivo`.`ci39_idretencion`) AS ci_valortasaretencion
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci07_cobromasivo`.`ci04_idrrut` 
			  INNER JOIN `ci11_clave` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci11_clave`.`ci04_idrrut` 
			  INNER JOIN `ci40_sociedad` 
			    ON `ci04_rut`.`ci40_idsociedad` = `ci40_sociedad`.`ci40_idsociedad` 
			  INNER JOIN `lc01_usuario` 
			    ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci07_cobromasivo`.`ci33_idconcepto` = '".$data['tipoCobroSelect']."' 
			  AND `ci07_cobromasivo`.`ci07_fechapago`='".$data['fecha']."'
			  $where 
		      ORDER BY `ci07_idcobromasivo`;";
			
		$data=mysql_query ( $sql );
	
		$datosCobroMasivo = array ();
	
		while ( $row = mysql_fetch_array ($data ) )
		{				
	   /*0*/$entry ['ci07_idcobromasivo'] = $row ['ci07_idcobromasivo'];
	        $entry ['ci03_nombre'] = $row ['ci03_nombre'];
	   	    $entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
	   /*5*/$entry ['ci11_sii'] = $row ['ci11_sii'];
			$entry ['ci11_previred'] = $row ['ci11_previred'];
			$entry ['ci40_tiposociedad'] = $row ['ci40_tiposociedad'];
			$entry ['ci07_ingsinretencion'] = $row ['ci07_ingsinretencion'];
			$entry ['ci07_ingconretencion'] = $row ['ci07_ingconretencion'];
	  /*10*/$entry ['ci07_ingsociedad'] = $row ['ci07_ingsociedad'];
			$entry ['ci07_retsociedad'] = $row ['ci07_retsociedad'];
			$entry ['ci07_tasaprimeracat'] = $row ['ci07_tasaprimeracat'];
			$entry ['ci07_impuestounico'] = $row ['ci07_impuestounico'];
			$entry ['ci39_idretencion'] = $row ['ci39_idretencion'];				
	  /*15*/$entry ['ci07_ppmnetdet'] = $row ['ci07_ppmnetdet'];
			$entry ['ci07_bolretterceros'] = $row ['ci07_bolretterceros'];
			$entry ['ci07_retencion'] = $row ['ci07_retencion'];
			$entry ['ci07_impuestounico'] = $row ['ci07_impuestounico'];
			$entry ['ci07_ivapago'] = $row ['ci07_ivapago'];
	  /*20*/$entry ['ci07_remanente'] = $row ['ci07_remanente'];
			$entry ['ci07_monto'] = $row ['ci07_monto'];
			$entry ['ci35_idformapago'] = $row ['ci35_idformapago'];
			$entry ['ci07_conceptoprevired'] = $row ['ci07_conceptoprevired'];
			$entry ['ci37_idtasa'] = $row ['ci37_idtasa'];
	  /*25*/$entry ['ci07_fechapago'] = $row ['ci07_fechapago'];
	  		$entry ['ci04_iva'] = $row ['ci04_iva'];
	  		$entry ['ci_valortasapn'] = $row ['ci_valortasapn'];
	  		$entry ['ci_valortasaretencion'] = $row ['ci_valortasaretencion'];
	  		$entry ['ci04_previred'] = $row ['ci04_previred'];
	
			$datosCobroMasivo [] = $entry;
		}
		return $datosCobroMasivo;
	}
	
	/**************COBROS INDIVIDUALES********************/
	
	public function obtenerFormasDePago() 
	{
		$sql = "SELECT * FROM ci35_formapago";
		
		$formasPago = mysql_query ( $sql );
		
		$formapago = array ();
		
		while ( $row = mysql_fetch_array ( $formasPago ) ) {
			$entry ['ci35_idformapago'] = $row ['ci35_idformapago'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			
			$formapago [] = $entry;
		}
		
		return $formapago;
	}
	
	public function ingresarCobroIndividual($data) 
	{
		$sql = "INSERT INTO 
				`ci05_cobroindividual` 
				(
				`ci04_idrrut`,
				`ci33_idconcepto`,
				`ci35_idformapago`,
				`ci36_idestadocomepnsacion`,
				`ci53_idestadocobro`,
				`ci05_fechacobro`,
				`ci05_glosa`,
				`ci05_monto`,
				`ci05_valoruf`,
				`ci05_observacion`,
				`ci05_autorizapago`,
				`ci05_valormoneda`
				)
				VALUES
				(
				'" . $data ['idRut'] . "',
				'" . $data ['idConcepto'] . "',
				'" . $data ['idPago'] . "',
				'1',
				'1',
				'" . $data ['fecha'] . "',
				'" . $data ['glosa'] . "',
				'" . $data ['montoCobro'] . "',
				'" . $data ['valorUF'] . "',
				'" . $data ['observacion'] . "',
				'2',
				'" . $data ['valorMoneda'] . "'
				)";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function ingresarCobroHonorario($data) 
	{
				
		$sql = "INSERT INTO `ci06_honorario`(
				`ci04_idrrut`,
				`ci33_idconcepto`,
				`ci35_idformapago`,
				`ci36_idestadocomepnsacion`,
				`ci53_idestadocobro`,
				`ci06_fechacobro`,
				`ci06_glosa`,
				`ci06_monto`,
				`ci06_valoruf`,
				`ci06_observacion`,
				`ci06_autorizapago`,
				`ci06_valormoneda`
				)
				VALUES
				(
				'" . $data ['idRut'] . "',
				'" . $data ['idConcepto'] . "',
				'" . $data ['idPago'] . "',
				'1',
				'1',
				'" . $data ['fecha'] . "',
				'" . $data ['glosa'] . "',
				'" . $data ['montoCobro'] . "',
				'" . $data ['valorUF'] . "',
				'" . $data ['observacion'] . "',
				'2',
				'" . $data ['valorMoneda'] . "'
				);";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function ingresarCobroHonorarioMensualidadServicio($data)
    {	
		$sql="";
		
		foreach ($data as $i =>$key):		
			
			if($key['valorMensualidad']!=''&& $key['valorMensualidad']!='0'&& $key['valorMensualidad']!='0.00'):
						
					$sql .= "INSERT INTO `ci06_honorario`(	`ci04_idrrut`,
															`ci33_idconcepto`,
															`ci35_idformapago`,
															`ci36_idestadocomepnsacion`,
															`ci53_idestadocobro`,
															`ci06_fechacobro`,
															`ci06_glosa`,
															`ci06_monto`,
															`ci06_valoruf`,
															`ci06_observacion`,
															`ci06_autorizapago`,
															`ci06_valormoneda`
															)
															VALUES
															(
															'" . $key['idRut'] . "',
															'4',
															'" . $key['formaPago']. "',
															'1',
															'1',
															'".date('d').'-'.$key['fechaIngreso']."',
															'Cobro Mensualidad',
															'" . $key['valorMensualidad']. "',
															'" . $key['valorUF']. "',
															'',
															'2',
															'" . $key['valorMonedaMensualidad']. "'		
															);";
					
			endif;
				
			if($key['valorServicio']!=''&& $key['valorServicio']!='0'&& $key['valorServicio']!='0.00'):
					
					$sql .= "INSERT INTO `ci06_honorario`(  `ci04_idrrut`,
															`ci33_idconcepto`,
															`ci35_idformapago`,
															`ci36_idestadocomepnsacion`,
															`ci53_idestadocobro`,
															`ci06_fechacobro`,
															`ci06_glosa`,
															`ci06_monto`,
															`ci06_valoruf`,
															`ci06_observacion`,
															`ci06_autorizapago`,
															`ci06_valormoneda`														
														 )
															VALUES
															(
															'" . $key['idRut'] . "',
															'5',
															'" . $key['formaPago'] . "',
															'1',
															'1',
															'".date('d').'-'.$key['fechaIngreso']."',
															'Cobro Servicio Variable',
															'". $key['valorServicio'] . "',
															'". $key['valorUF'] . "',
															'',
															'2',
															'". $key['valorMonedaServicio'] . "'
															);";
				
			endif;
				
		endforeach;
		
		if($sql!=''):
			$res = mysqli_multi_query ($this->_link, $sql );
		else:
			$res = true;	
		endif;
				
		
		if ($res) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	public function verificaMensualidadServicioByIdRutFecha($idRut,$idConcepto,$fecha)
	{
		$sql="SELECT 
			  `ci04_idrrut` 
			FROM
			  `ci06_honorario` 
			WHERE `ci04_idrrut` = '".$idRut."' 
			  AND `ci33_idconcepto` = '".$idConcepto."' 
			  AND `ci53_idestadocobro` !='5'		
			  AND DATE_FORMAT(
			    STR_TO_DATE(`ci06_fechacobro`, '%d-%m-%Y'),
			    '%m-%Y'
			  ) = DATE_FORMAT(
			    STR_TO_DATE('".$fecha."', '%m-%Y'),
			    '%m-%Y'
			  )
			GROUP BY `ci04_idrrut`;";
		 
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function verificaServicioByIdRutFecha($idRut,$fecha)
	{
		$sql="SELECT
			  `ci04_idrrut`
			FROM
			  `ci06_honorario`
			WHERE `ci04_idrrut` = '".$idRut."'
			  AND `ci33_idconcepto`='5'
			  AND  DATE_FORMAT(STR_TO_DATE(`ci06_fechacobro`,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('".$fecha."','%m-%Y'),'%m-%Y')
			GROUP BY `ci04_idrrut`";
			
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	
	}
	
	public function buscarCobros($id, $desde) 
	{
		$query = '';
		
		if($id != 0 && $desde != 0)
		{
		
			switch ($desde) {
				case '1' :
					$query = "AND u.`lc01_idUsuario` = '" . $id . "'"; // filtro por el id del ejecutivo
					break;
				case '2' :
					$query = "AND cl.`ci03_idcliente`='" . $id . "'"; // filtro por el id del cliente asoicado al ejecutivo
					break;
				case '3' :
					$query = "AND r.`ci04_idrrut`='" . $id . "'"; // filtro por el id del rut asociado al cliente
					break;
			}
		}
		
		$sql = "SELECT 
				  c.`ci05_idcobroindividual` AS ci_idCobro,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
				  c.`ci05_fechacobro` AS ci_fechaCobro,
				  c.`ci05_glosa` AS ci_glosa,
				  IF(
				    r.`ci04_numerosociedad` = '0',
				    'PN',
				    r.`ci04_numerosociedad`
				  ) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT 
				    cla.`ci11_previred` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci05_cobroindividual` ci 
				      ON ru.`ci04_idrrut` = ci.`ci04_idrrut` 
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND ru.`ci04_previred` = '1' 
				    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_claveprevired,
				  (SELECT 
				    cla.`ci11_sii` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci05_cobroindividual` ci 
				      ON ru.`ci04_idrrut` = ci.`ci04_idrrut` 
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND ru.`ci04_iva` = '1' 
				    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_clavesii,
				  IF(
				      ec.`ci53_idestadocobro` = '2',
				      IFNULL(
				        (SELECT 
				          `ci62_cuentaCteAngkor`.`ci62_nroCuenta` 
				        FROM
				          `ci08_compensacion` 
				          INNER JOIN `ci17_detallecartola` 
				            ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
				          INNER JOIN `ci16_cartola` 
				            ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
				          INNER JOIN `ci62_cuentaCteAngkor` 
				            ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
				        WHERE `ci08_compensacion`.`ci08_tipoCobro` = '1' 
				          AND `ci08_compensacion`.`ci08_idCobro` = c.`ci05_idcobroindividual`
				          GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro` 
				        ),
				        '-'
				      ),
				      '-'
				    ) AS `ci_numerocuenta`,
				  (
				    ROUND(
				      c.`ci05_monto` * c.`ci05_valoruf`,
				      0
				    )
				  ) AS ci_monto,
				  (SELECT 
				    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido 
				  FROM
				    `ci05_cobroindividual` ci 
				    INNER JOIN `ci25_detalleindividualordenpago` dci 
				      ON ci.`ci05_idcobroindividual` = dci.`ci05_idcobroindividual` 
				    INNER JOIN `ci22_ordenpago` op 
				      ON dci.`ci22_idordenpago` = op.`ci22_idordenpago` 
				  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_dinerorecivido,
				  IF(
				    c.`ci05_monto` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`,
				  '-' AS ci_numerofactura,
				  c.`ci05_observacion` AS ci_observacion,
				  (SELECT 
				    ci03_cliente.ci03_saldo 
				  FROM
				    ci03_cliente 
				  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente 
				FROM
				  `ci05_cobroindividual` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cl 
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `lc01_usuario` u 
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
				  INNER JOIN `ci35_formapago` fp 
				    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				WHERE r.`ci04_estadodisponibilidad` = '1' " . $query . " 
				  AND ec.`ci53_idestadocobro` BETWEEN '1' 
				  AND fp.`ci35_idformapago` = '1' 
				  AND '2' 
				UNION
				SELECT 
				  h.`ci06_idhonorario` AS ci_idCobro,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
				  IF(
				    h.`ci33_idconcepto` = '4' 
				    OR h.`ci33_idconcepto` = '5',
				    (
				      DATE_FORMAT(
				        STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y'),
				        '%m-%Y'
				      )
				    ),
				    h.`ci06_fechacobro`
				  ) AS ci_fechaCobro,
				  h.`ci06_glosa` AS ci_glosa,
				  IF(
				    r.`ci04_numerosociedad` = '0',
				    'PN',
				    r.`ci04_numerosociedad`
				  ) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT 
				    cla.`ci11_previred` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci06_honorario` hon 
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut` 
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND ru.`ci04_previred` = '1' 
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_claveprevired,
				  (SELECT 
				    cla.`ci11_sii` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci06_honorario` hon 
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut` 
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND ru.`ci04_iva` = '1' 
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_clavesii,
				  IF(
				    ec.`ci53_idestadocobro` = '2',
				    IFNULL(
				      (SELECT 
					        `ci62_cuentaCteAngkor`.`ci62_nroCuenta`
					      FROM
					        `ci08_compensacion` 
					        INNER JOIN `ci17_detallecartola` 
					          ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
					        INNER JOIN `ci16_cartola` 
					          ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
					        INNER JOIN `ci62_cuentaCteAngkor` 
					          ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
					      WHERE `ci08_compensacion`.`ci08_tipoCobro` = '2' 
					        AND `ci08_compensacion`.`ci08_idCobro` = h.`ci06_idhonorario`
					        GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro`
				      ),
				      '-'
				    ),
				    '-'
				  ) AS `ci_numerocuenta`,
				  (
				    ROUND(
				      h.`ci06_monto` * h.`ci06_valoruf`,
				      0
				    )
				  ) AS ci_monto,
				  (SELECT 
				    SUM(op.`ci22_dinerorecibido`) 
				  FROM
				    `ci06_honorario` ho 
				    INNER JOIN `ci24_detallehonorarioordenpago` dho 
				      ON ho.`ci06_idhonorario` = dho.`ci06_idhonorario` 
				    INNER JOIN `ci22_ordenpago` op 
				      ON dho.`ci22_idordenpago` = op.`ci22_idordenpago` 
				  WHERE ho.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_dinerorecivido,
				  IF(
				    h.`ci06_monto` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`,
				  (SELECT 
				    fac.`ci34_numerofcactura` 
				  FROM
				    `ci06_honorario` hon 
				    INNER JOIN `ci34_factura` fac 
				      ON hon.`ci06_idhonorario` = fac.`ci06_idhonorario` 
				  WHERE hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_numerofactura,
				  h.`ci06_observacion` AS ci_observacion,
				  (SELECT 
				    ci03_cliente.ci03_saldo 
				  FROM
				    ci03_cliente 
				  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente 
				FROM
				  `ci06_honorario` h 
				  INNER JOIN `ci04_rut` r 
				    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cl 
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `lc01_usuario` u 
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
				  INNER JOIN `ci35_formapago` fp 
				    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				WHERE r.`ci04_estadodisponibilidad` = '1' " . $query . " 
				  AND fp.`ci35_idformapago` = '5' 
				  AND ec.`ci53_idestadocobro` BETWEEN '1' 
				  AND '2' 
				UNION
				SELECT 
				  cm.`ci07_idcobromasivo` AS ci_idCobro,
				  'Masivo' AS ci33_tipo_ingreso,
				  cm.`ci07_fechapago` AS ci_fechaCobro,
				  cc.`ci33_nombre` AS ci_glosa,
				  IF(
				    r.`ci04_numerosociedad` = '0',
				    'PN',
				    r.`ci04_numerosociedad`
				  ) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT 
				    cla.`ci11_previred` 
				  FROM
				    `ci04_rut` r 
				    INNER JOIN `ci11_clave` cla 
				      ON r.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci07_cobromasivo` cma 
				      ON r.`ci04_idrrut` = cma.`ci04_idrrut` 
				  WHERE r.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND r.`ci04_previred` = '1' 
				    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_claveprevired,
				  (SELECT 
				    cla.`ci11_sii` 
				  FROM
				    `ci04_rut` r 
				    INNER JOIN `ci11_clave` cla 
				      ON r.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci07_cobromasivo` cma 
				      ON r.`ci04_idrrut` = cma.`ci04_idrrut` 
				  WHERE r.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND r.`ci04_iva` = '1' 
				    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_clavesii,
				   IF(
				        ec.`ci53_idestadocobro` = '2',
				        IFNULL(
				          (SELECT 
				            `ci62_cuentaCteAngkor`.`ci62_nroCuenta` 
				          FROM
				            `ci08_compensacion` 
				            INNER JOIN `ci17_detallecartola` 
				              ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
				            INNER JOIN `ci16_cartola` 
				              ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
				            INNER JOIN `ci62_cuentaCteAngkor` 
				              ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
				          WHERE `ci08_compensacion`.`ci08_tipoCobro` = '3' 
				            AND `ci08_compensacion`.`ci08_idCobro` = cm.`ci07_idcobromasivo`
				            GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro` 
				          ),
				          '-'
				        ),
				        '-'
				      ) AS `ci_numerocuenta`,
				  cm.`ci07_monto` AS ci_monto,
				  (SELECT 
				    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido 
				  FROM
				    `ci07_cobromasivo` cma 
				    INNER JOIN `ci23_detallemasivoordenpago` dmp 
				      ON cma.`ci07_idcobromasivo` = dmp.`ci07_idcobromasivo` 
				    INNER JOIN `ci22_ordenpago` op 
				      ON dmp.`ci22_idordenpago` = op.`ci22_idordenpago` 
				  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_dinerorecivido,
				  IF(
				    cm.`ci07_monto` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`,
				  '-' AS ci_numerofactura,
				  '' AS ci_observacion,
				  (SELECT 
				    ci03_cliente.ci03_saldo 
				  FROM
				    ci03_cliente 
				  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente 
				FROM
				  `ci07_cobromasivo` cm 
				  INNER JOIN `ci04_rut` r 
				    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cl 
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `lc01_usuario` u 
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
				  INNER JOIN `ci35_formapago` fp 
				    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				  INNER JOIN `ci36_estadocompensacion` ecom 
				    ON cm.`ci36_idestadocomepnsacion` = ecom.`ci36_idEstadoCompensacion` 
				WHERE r.`ci04_estadodisponibilidad` = '1' " . $query . " 
				  AND ecom.`ci36_idEstadoCompensacion` = '1' 
				  AND fp.`ci35_idformapago` = '1' 
				  AND ec.`ci53_idestadocobro` BETWEEN '1' 
				  AND '2'   ;";
		
	
		$datos = mysql_query ( $sql );
		
		$listadoCobros = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			
			$entry ['ci_claveprevired'] = $row ['ci_claveprevired'];
			$entry ['ci_clavesii'] = $row ['ci_clavesii'];
			$entry ['ci_numerocuenta'] = $row ['ci_numerocuenta'];
			
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_dinerorecivido'] = $row ['ci_dinerorecivido'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			
			$entry ['ci_numerofactura'] = $row ['ci_numerofactura'];
			
			$entry ['ci_observacion'] = $row ['ci_observacion'];
			$entry ['saldo_cliente'] = $row ['saldo_cliente'];
			
			$listadoCobros [] = $entry;
		}
		
		return $listadoCobros;
	}
	
	public function listadoBuscarCobros($data)
	{		
		$query="";
		$filtroHonorarios="";
		$filtroCanjeIndividual="";
		$filtroCanjeMasivo="";
		
		
			if($data ['idEjecutivo']!='')
			{
				$filtroHonorarios=" AND cl.`lc01_idUsuario`='" . $data ['idEjecutivo'] . "' ";
				$filtroCanjeIndividual=" AND cl.`lc01_idUsuario`='" . $data ['idEjecutivo'] . "' ";
				$filtroCanjeMasivo=" AND cl.`lc01_idUsuario`='" . $data ['idEjecutivo'] . "' ";
			}		
				
			if($data ['idCliente']!='')
			{
				$filtroHonorarios.=" AND cl.`ci03_idcliente`='" . $data ['idCliente'] . "' ";				
				$filtroCanjeIndividual.=" AND cl.`ci03_idcliente`='" . $data ['idCliente'] . "' ";
				$filtroCanjeMasivo.=" AND cl.`ci03_idcliente`='" . $data ['idCliente'] . "'";
			}
			
			if ($data ['idRut']!='')
			{
				$filtroHonorarios.=" AND r.`ci04_idrrut`='" . $data ['idRut'] . "' ";
				$filtroCanjeIndividual.=" AND r.`ci04_idrrut`='" . $data ['idRut'] . "'";
				$filtroCanjeMasivo.=" AND r.`ci04_idrrut`='" . $data ['idRut'] . "'";
			}
			
			if ($data ['fechaInicio']!='' && $data ['fechaFinal']!='')
			{
				$filtroHonorarios.=" AND STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y') BETWEEN STR_TO_DATE('" . $data ['fechaInicio'] . "','%d-%m-%Y') AND STR_TO_DATE('" . $data ['fechaFinal'] . "','%d-%m-%Y')";
				$filtroCanjeIndividual.=" AND STR_TO_DATE(c.`ci05_fechacobro`,'%d-%m-%Y') BETWEEN STR_TO_DATE('" . $data ['fechaInicio'] . "','%d-%m-%Y') AND STR_TO_DATE('" . $data ['fechaFinal'] . "','%d-%m-%Y')";
				$filtroCanjeMasivo.="AND cm.`ci07_fechapago` BETWEEN DATE_FORMAT(STR_TO_DATE('" . $data ['fechaInicio'] . "','%d-%m-%Y'),   '%m-%Y') AND DATE_FORMAT(STR_TO_DATE('" . $data ['fechaFinal'] . "','%d-%m-%Y'), '%m-%Y')";
			}
		
		
		$sqlHonorario="SELECT 
						  h.`ci06_idhonorario` AS ci_idCobro,
						  IF(
						    cc.`ci33_tipo_ingreso` = 1,
						    'Honorario',
						    'Cobro'
						  ) AS ci33_tipo_ingreso,
							
							
				
						  IF(
						    h.`ci33_idconcepto` = '4' 
						    OR h.`ci33_idconcepto` = '5',
						    (
						      DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y')
						    ),
						    h.`ci06_fechacobro`
						  ) AS ci_fechaCobro,
				
				
						  h.`ci06_glosa` AS ci_glosa,
						  IF(
						    r.`ci04_numerosociedad` = '0',
						    'PN',
						    r.`ci04_numerosociedad`
						  ) AS ci04_numerosociedad,
						  r.`ci04_rut`,
						  (SELECT
							  cla.`ci11_previred` 
							FROM
							  `ci04_rut` ru 
							  INNER JOIN `ci11_clave` cla 
							    ON ru.`ci04_idrrut` = cla.`ci04_idrrut`  
							WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
							  AND ru.`ci04_previred` = '1' ) AS ci_claveprevired,
						  (			
							SELECT 
							  cla.`ci11_sii` 
							FROM
							  `ci04_rut` ru 
							  INNER JOIN `ci11_clave` cla 
							    ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
							WHERE ru.`ci04_idrrut`=r.`ci04_idrrut`) AS ci_clavesii,
						   IF(
						    ec.`ci53_idestadocobro` = '2',
						    IFNULL(
						      (SELECT 
						        `ci62_cuentaCteAngkor`.`ci62_nroCuenta`
						      FROM
						        `ci08_compensacion` 
						        INNER JOIN `ci17_detallecartola` 
						          ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
						        INNER JOIN `ci16_cartola` 
						          ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
						        INNER JOIN `ci62_cuentaCteAngkor` 
						          ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
						      WHERE `ci08_compensacion`.`ci08_tipoCobro` = '2' 
						        AND `ci08_compensacion`.`ci08_idCobro` = h.`ci06_idhonorario`
						        GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro`),
						      '-'
						    ),
						    '-'
						  ) AS `ci_numerocuenta`,
				
						  h.`ci06_valormoneda` AS ci_monto,
				
				
						  (SELECT 
						    SUM(op.`ci22_dinerorecibido`) 
						  FROM
						    `ci06_honorario` ho 
						    INNER JOIN `ci24_detallehonorarioordenpago` dho 
						      ON ho.`ci06_idhonorario` = dho.`ci06_idhonorario` 
						    INNER JOIN `ci22_ordenpago` op 
						      ON dho.`ci22_idordenpago` = op.`ci22_idordenpago` 
						  WHERE ho.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_dinerorecivido,
						  IF(
						    h.`ci06_valormoneda` = 0,
						    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
						    ec.`ci53_nombreestado`
						  ) AS ci53_nombreestado,
						  fp.`ci35_tipopago`,
						  (SELECT 
						    fac.`ci34_numerofcactura` 
						  FROM
						    `ci06_honorario` hon 
						    INNER JOIN `ci34_factura` fac 
						      ON hon.`ci06_idhonorario` = fac.`ci06_idhonorario` 
						  WHERE hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_numerofactura,
						  h.`ci06_observacion` AS ci_observacion,
						  (SELECT 
						    ci03_cliente.ci03_saldo 
						  FROM
						    ci03_cliente 
						  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente 
						FROM
						  `ci06_honorario` h 
						  INNER JOIN `ci04_rut` r 
						    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
						  INNER JOIN `ci36_estadocompensacion` eco 
						    ON h.`ci36_idestadocomepnsacion` = eco.`ci36_idEstadoCompensacion` 
						  INNER JOIN `ci03_cliente` cl 
						    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
						  INNER JOIN `lc01_usuario` u 
						    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
						  INNER JOIN `ci35_formapago` fp 
						    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
						  INNER JOIN `ci53_estadocobro` ec 
						    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
						  INNER JOIN `ci33_conceptocobro` cc 
						    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
						WHERE r.`ci04_estadodisponibilidad` = '1' 
						  AND fp.`ci35_idformapago` = '5' 
						  AND ec.`ci53_idestadocobro` in ('1','2')  ".$filtroHonorarios." ";
		
		$sqlUNION=" UNION ";
		
		$sqlCanjes="SELECT 
					  c.`ci05_idcobroindividual` AS ci_idCobro,
					  IF(
					    cc.`ci33_tipo_ingreso` = 1,
					    'Honorario',
					    'Cobro'
					  ) AS ci33_tipo_ingreso,
					  c.`ci05_fechacobro` AS ci_fechaCobro,
					  c.`ci05_glosa` AS ci_glosa,
					  IF(
					    r.`ci04_numerosociedad` = '0',
					    'PN',
					    r.`ci04_numerosociedad`
					  ) AS ci04_numerosociedad,
					  r.`ci04_rut`,
					 (SELECT
							  cla.`ci11_previred` 
							FROM
							  `ci04_rut` ru 
							  INNER JOIN `ci11_clave` cla 
							    ON ru.`ci04_idrrut` = cla.`ci04_idrrut`  
							WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
							  AND ru.`ci04_previred` = '1' ) AS ci_claveprevired,
					  (SELECT 
							  cla.`ci11_sii` 
					  FROM
						  `ci04_rut` ru 
					  INNER JOIN `ci11_clave` cla 
						    ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
					  WHERE ru.`ci04_idrrut`=r.`ci04_idrrut`) AS ci_clavesii,
					  IF(
					     ec.`ci53_idestadocobro` = '2',
					    IFNULL(
					      (SELECT 
					        `ci62_cuentaCteAngkor`.`ci62_nroCuenta` 
					      FROM
					        `ci08_compensacion` 
					        INNER JOIN `ci17_detallecartola` 
					          ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
					        INNER JOIN `ci16_cartola` 
					          ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
					        INNER JOIN `ci62_cuentaCteAngkor` 
					          ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
					      WHERE `ci08_compensacion`.`ci08_tipoCobro` = '1' 
					        AND `ci08_compensacion`.`ci08_idCobro` = c.`ci05_idcobroindividual`
					        GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro` 
							),
					      '-'
					    ),
					    '-'
					  ) AS `ci_numerocuenta`,
				
					  c.`ci05_valormoneda` AS ci_monto,
					  
					  (SELECT 
					  SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido   
					    
					  FROM
					    `ci05_cobroindividual` ci 
					    INNER JOIN `ci25_detalleindividualordenpago` dci 
					      ON ci.`ci05_idcobroindividual` = dci.`ci05_idcobroindividual` 
					    INNER JOIN `ci22_ordenpago` op 
					      ON dci.`ci22_idordenpago` = op.`ci22_idordenpago` 
					  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_dinerorecivido,
					  IF(
					    c.`ci05_valormoneda` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
					  fp.`ci35_tipopago`,
					  '-' AS ci_numerofactura,
					  c.`ci05_observacion` AS ci_observacion,
					  (SELECT 
					    ci03_cliente.ci03_saldo 
					  FROM
					    ci03_cliente 
					  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente 
					FROM
					  `ci05_cobroindividual` c 
					  INNER JOIN `ci04_rut` r 
					    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
					  INNER JOIN `ci36_estadocompensacion` eco 
					    ON c.`ci36_idestadocomepnsacion` = eco.`ci36_idEstadoCompensacion` 
					  INNER JOIN `ci03_cliente` cl 
					    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
					  INNER JOIN `lc01_usuario` u 
					    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
					  INNER JOIN `ci35_formapago` fp 
					    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
					  INNER JOIN `ci53_estadocobro` ec 
					    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
					  INNER JOIN `ci33_conceptocobro` cc 
					    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
					WHERE r.`ci04_estadodisponibilidad` = '1' 
					  AND c.`ci35_idformapago` = '1' 
					  AND ec.`ci53_idestadocobro` in ('1','2')  ".$filtroCanjeIndividual." 
					
					UNION
					
					SELECT 
					  cm.`ci07_idcobromasivo` AS ci_idCobro,
					  'Masivo' AS ci33_tipo_ingreso,
					  cm.`ci07_fechapago` AS ci_fechaCobro,
					  cc.`ci33_nombre` AS ci_glosa,
					  IF(
					    r.`ci04_numerosociedad` = '0',
					    'PN',
					    r.`ci04_numerosociedad`
					  ) AS ci04_numerosociedad,
					  r.`ci04_rut`,
					 (SELECT
							  cla.`ci11_previred` 
							FROM
							  `ci04_rut` ru 
							  INNER JOIN `ci11_clave` cla 
							    ON ru.`ci04_idrrut` = cla.`ci04_idrrut`  
							WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
							  AND ru.`ci04_previred` = '1' ) AS ci_claveprevired,
					  (SELECT 
							  cla.`ci11_sii` 
					  FROM
						  `ci04_rut` ru 
					  INNER JOIN `ci11_clave` cla 
						    ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
					  WHERE ru.`ci04_idrrut`=r.`ci04_idrrut`) AS ci_clavesii,
					  IF(
						  ec.`ci53_idestadocobro` = '2',
						  IFNULL(
						    (SELECT 
						      `ci62_cuentaCteAngkor`.`ci62_nroCuenta` 
						    FROM
						      `ci08_compensacion` 
						      INNER JOIN `ci17_detallecartola` 
						        ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
						      INNER JOIN `ci16_cartola` 
						        ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
						      INNER JOIN `ci62_cuentaCteAngkor` 
						        ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
						    WHERE `ci08_compensacion`.`ci08_tipoCobro` = '3' 
						      AND `ci08_compensacion`.`ci08_idCobro` = cm.`ci07_idcobromasivo`
						      GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro`
						    ),
						    '-'
						  ),
						  '-'
						) AS `ci_numerocuenta`,

					  cm.`ci07_monto` AS ci_monto,
					  (SELECT 
					    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido 
					  FROM
					    `ci07_cobromasivo` cma 
					    INNER JOIN `ci23_detallemasivoordenpago` dmp 
					      ON cma.`ci07_idcobromasivo` = dmp.`ci07_idcobromasivo` 
					    INNER JOIN `ci22_ordenpago` op 
					      ON dmp.`ci22_idordenpago` = op.`ci22_idordenpago` 
					  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_dinerorecivido,
					  IF(
					    cm.`ci07_monto` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
					  fp.`ci35_tipopago`,
					  '-' AS ci_numerofactura,
					  '' AS ci_observacion,
					  (SELECT 
					    ci03_cliente.ci03_saldo 
					  FROM
					    ci03_cliente 
					  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente 
					FROM
					  `ci07_cobromasivo` cm 
					  INNER JOIN `ci04_rut` r 
					    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
					  INNER JOIN `ci03_cliente` cl 
					    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
					  INNER JOIN `lc01_usuario` u 
					    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
					  INNER JOIN `ci35_formapago` fp 
					    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
					  INNER JOIN `ci53_estadocobro` ec 
					    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
					  INNER JOIN `ci33_conceptocobro` cc 
					    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
					  INNER JOIN `ci36_estadocompensacion` ecom 
					    ON cm.`ci36_idestadocomepnsacion` = ecom.`ci36_idEstadoCompensacion` 
					WHERE r.`ci04_estadodisponibilidad` = '1' 
					  AND ec.`ci53_idestadocobro` in ('1','2') 
					  AND cm.`ci35_idformapago` = '1' ".$filtroCanjeMasivo." ";
		
		if($data['tipoCobro']=='')
		{
			$query=$sqlHonorario.$sqlUNION.$sqlCanjes;
		}
		elseif ($data['tipoCobro']=='1')
		{
			$query=$sqlHonorario;
		}
		elseif ($data['tipoCobro']=='2')
		{
			$query=$sqlCanjes;
		}		
		

		$datos = mysql_query ( $query );
		
		$listadoCobros = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{								
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];				
			$entry ['ci_claveprevired'] = $row ['ci_claveprevired'];
			$entry ['ci_clavesii'] = $row ['ci_clavesii'];				
			$entry ['ci_numerocuenta'] = $row ['ci_numerocuenta'];				
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_dinerorecivido'] = $row ['ci_dinerorecivido'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];				
			$entry ['ci_numerofactura'] = $row ['ci_numerofactura'];				
			$entry ['ci_observacion'] = $row ['ci_observacion'];
			$entry ['saldo_cliente'] = $row ['saldo_cliente'];
				
			$listadoCobros [] = $entry;
		}
		
		return $listadoCobros;
	}
	
	// lista cobros con los filtros en orden de pago
	public function listadoCobrosOrdenPago($filtros) 
	{		
		$where1 = '';
		$where2 = '';
		
		if($filtros['idCliente'] != ''){
			
			$idcliente = $filtros['idCliente'];
			
			$where1 .= " AND cl.`ci03_idcliente` = '$idcliente'";
			$where2 .= " AND cli.`ci03_idcliente` = '$idcliente' ";
		}
		
		if($filtros['fechaInicio'] != '' && $filtros['fechaFinal'] != '')
		{
			
			$fechaInicio = $filtros['fechaInicio'] ;
			$fechaFinal = $filtros['fechaFinal'];
			
			$where1 .= " AND c.`ci05_fechacobro` BETWEEN '".$fechaInicio."' AND '".$fechaFinal."'";
			$where2 .= " AND cm.`ci07_fechapago` BETWEEN DATE_FORMAT(STR_TO_DATE('".$fechaInicio."','%d-%m-%Y'),'%m-%Y') AND DATE_FORMAT(STR_TO_DATE('".$fechaFinal."','%d-%m-%Y'),'%m-%Y')";
		}
		
		if($filtros['idRut'] != ''){
			
			$idRut = $filtros['idRut'];
			
			$where1 .= " AND r.`ci04_idrrut` = '$idRut' ";
			$where2 .= " AND r.`ci04_idrrut` = '$idRut' ";
		}
		
		if($filtros['idUsuario'] != ''){
			
			$idUsuario = $filtros['idUsuario'];
			
			$where1 .= " AND us.`lc01_idUsuario` = '$idUsuario'";
			$where2 .= " AND usu.`lc01_idUsuario` = '$idUsuario' ";
		}
		
		$sql = "SELECT 
			  c.`ci05_idcobroindividual` AS ci_idCobro,
			  IF(
			    cc.`ci33_tipo_ingreso` = 1,
			    'Honorario',
			    'Cobro'
			  ) AS ci33_tipo_ingreso,
			  
			  c.`ci05_fechacobro` AS ci_fechaCobro,
			  
			  c.`ci05_glosa` AS ci_glosa,
			  IF(
			    r.`ci04_numerosociedad` = 0,
			    'PN',
			    r.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  
			   c.`ci05_valormoneda` AS ci_monto,
			   
			  (SELECT 
			    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido 
			  FROM
			    `ci05_cobroindividual` ci 
			    INNER JOIN `ci25_detalleindividualordenpago` dci 
			      ON ci.`ci05_idcobroindividual` = dci.`ci05_idcobroindividual` 
			    INNER JOIN `ci22_ordenpago` op 
			      ON dci.`ci22_idordenpago` = op.`ci22_idordenpago` 
			  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_dinerorecivido,
			  (SELECT 
			    op.`ci22_numerocheque` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci25_detalleindividualordenpago` dip 
			      ON op.`ci22_idordenpago` = dip.`ci22_idordenpago` 
			  WHERE dip.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_numeroorden,
			 
			    IF(
			    c.`ci05_monto` = 0,
			    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
			    ec.`ci53_nombreestado`
			  ) AS ci53_nombreestado,
			  
			  fp.`ci35_tipopago`,
			  c.`ci05_observacion` AS ci_observacion 
			FROM
			  `ci05_cobroindividual` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			  INNER JOIN `lc01_usuario` us 
			    ON cl.`lc01_idUsuario` = us.`lc01_idUsuario` 
			WHERE r.`ci04_estadodisponibilidad` = '1' 
			  AND fp.`ci35_idformapago` = '2' 
			  AND ec.`ci53_idestadocobro` BETWEEN '1' 
			  AND '2' 
			   $where1
			UNION
			SELECT 
			  cm.`ci07_idcobromasivo`,
			  'Masivo' ci33_tipo_ingreso,
			  cm.`ci07_fechapago` AS ci_fechaCobro,
			  cc.`ci33_nombre` AS ci_glosa,
			  IF(
			    r.`ci04_numerosociedad` = 0,
			    'PN',
			    r.`ci04_numerosociedad`
			  ) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  cm.`ci07_monto`,
			  (SELECT 
			    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido 
			  FROM
			    `ci07_cobromasivo` cma 
			    INNER JOIN `ci23_detallemasivoordenpago` dmp 
			      ON cma.`ci07_idcobromasivo` = dmp.`ci07_idcobromasivo` 
			    INNER JOIN `ci22_ordenpago` op 
			      ON dmp.`ci22_idordenpago` = op.`ci22_idordenpago` 
			  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_dinerorecivido,
			  (SELECT 
			    op.`ci22_numerocheque` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci23_detallemasivoordenpago` dmp 
			      ON op.`ci22_idordenpago` = dmp.`ci22_idordenpago` 
			  WHERE dmp.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_numeroorden,
			 
			  IF(
			    cm.`ci07_monto` = 0,
			    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
			    ec.`ci53_nombreestado`
			  ) AS ci53_nombreestado,
			 
			  fp.`ci35_tipopago`,
			  '' AS ci_observacion 
			FROM
			  `ci07_cobromasivo` cm 
			  INNER JOIN `ci04_rut` r 
			    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cli 
			    ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			  INNER JOIN `lc01_usuario` usu 
			    ON cli.`lc01_idUsuario` = usu.`lc01_idUsuario` 
			WHERE r.`ci04_estadodisponibilidad` = '1' 
			  AND fp.`ci35_idformapago` = '2' 
			  AND ec.`ci53_idestadocobro` BETWEEN '1' 
			  AND '2' 
			   $where2;";
		
		
		$datos = mysql_query ( $sql );
		
		$listadoCobros = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) 
		{
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_dinerorecivido'] = $row ['ci_dinerorecivido'];
			$entry ['ci_numeroorden'] = $row ['ci_numeroorden'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			$entry ['ci_observacion'] = $row ['ci_observacion'];
			
			$listadoCobros [] = $entry;
		}
		
		return $listadoCobros;
	}
	
	// lista cobros con los filtros en PEC
	public function listadoCobrosPec($idRut, $idCliente, $fechaInicio, $fechaFinal,$idUsuario) 
	{
		
		$where1="WHERE r.`ci04_estadodisponibilidad` = '1' ";
		$where2="WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`";
		$where3="WHERE r.`ci04_estadodisponibilidad` = '1' ";
		
		
		if($idUsuario!='')
		{
			$where1 .="AND cl.`lc01_idUsuario`='" . $idUsuario . "' ";			
			$where3 .="AND cli.`lc01_idUsuario`='" . $idUsuario . "' ";	
		}
		
		if($idCliente!='')
		{
			$where1 .=" AND cl.`ci03_idcliente` = '" . $idCliente . "' ";
			$where3 .=" AND cli.`ci03_idcliente` = '" . $idCliente . "' ";
		}
		
		if($idRut!='')
		{
			$where1 .=" AND r.`ci04_idrrut`='" . $idRut . "' ";
			$where2="WHERE ru.`ci04_idrrut` = '. $idRut .' ";			
			$where3 .=" AND r.`ci04_idrrut` = '" . $idRut . "' ";
		}
		
		if($fechaInicio!='' && $fechaFinal!='')
		{
			$where1 .=" AND STR_TO_DATE(c.`ci05_fechacobro`,'%d-%m-%Y') BETWEEN STR_TO_DATE('" . $fechaInicio . "','%d-%m-%Y') AND STR_TO_DATE('" . $fechaFinal . "','%d-%m-%Y')";
			$where3 .=" AND cm.`ci07_fechapago` BETWEEN DATE_FORMAT(STR_TO_DATE('" . $fechaInicio . "','%d-%m-%Y'),'%m-%Y')  AND DATE_FORMAT(STR_TO_DATE('" . $fechaFinal . "','%d-%m-%Y'),'%m-%Y')";
		}
		
		
		$sql = "SELECT 
		     	  c.`ci05_idcobroindividual` AS ci_idCobro,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
				  c.`ci05_fechacobro` AS ci_fechaCobro,
				  c.`ci05_glosa` AS ci_glosa,
				  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  c.`ci05_valormoneda` AS ci_monto,			 	  
				  (SELECT 
				    fp.`ci54_numerofolio` 
				  FROM
				    `ci54_foliopec` fp 
				    INNER JOIN `ci55_detallefolioinidividual` dfi 
				      ON fp.`ci54_idfoliopec` = dfi.`ci54_idfoliopec` 
				    INNER JOIN `ci05_cobroindividual` ci 
				      ON dfi.`ci05_idcobroindividual` = ci.`ci05_idcobroindividual` 
				    INNER JOIN `ci04_rut` ru 
				      ON ci.`ci04_idrrut` = ru.`ci04_idrrut` 
				 $where2 
				    AND ci.`ci05_idcobroindividual`=ci_idCobro) AS ci_numerofolio,				    
				      IF(
					     c.`ci05_monto` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`
				FROM
				  `ci05_cobroindividual` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cl 
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `ci35_formapago` fp 
				    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				 $where1
				  AND fp.`ci35_idformapago` BETWEEN '3' AND '6'
				UNION				
				SELECT 
				  cm.`ci07_idcobromasivo` AS ci_idCobro,
				  'Masivo' AS ci33_tipo_ingreso,
				  cm.`ci07_fechapago`,
				  cc.`ci33_nombre` AS ci_glosa,
				  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  cm.`ci07_monto`,				  		
				  (SELECT 
				  fp.`ci54_numerofolio` 
				FROM
				  `ci54_foliopec` fp 
				  INNER JOIN `ci56_detallefoliomasivo` dfm
				    ON fp.`ci54_idfoliopec` = dfm.`ci54_idfoliopec`
				  INNER JOIN `ci07_cobromasivo` cma 
				    ON dfm.`ci07_idcobromasivo` = cma.`ci07_idcobromasivo`
				  INNER JOIN `ci04_rut` ru 
				    ON cma.`ci04_idrrut` = ru.`ci04_idrrut` 
				$where2  
				  AND cma.`ci07_idcobromasivo` = ci_idCobro) AS ci_numerofolio ,				  		
				  
				   IF(
					     cm.`ci07_monto` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
				  
				  fp.`ci35_tipopago` 
				FROM
				  `ci07_cobromasivo` cm 
				  INNER JOIN `ci04_rut` r 
				    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cli 
				    ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
				  INNER JOIN `ci35_formapago` fp 
				    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				$where3
				  AND fp.`ci35_idformapago` BETWEEN '3' AND '6';";
		
		$datos = mysql_query ( $sql );
		
		$listadoCobrosPec = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_numerofolio'] = $row ['ci_numerofolio'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			
			$listadoCobrosPec [] = $entry;
		}
		
		return $listadoCobrosPec;
	}
		
	public function obtenerCobrosByRut($idRut) 
	{
		$sql = "SELECT 
				  c.`ci05_idcobroindividual` AS ci_idCobro,
				  c.`ci05_fechacobro` AS ci_fechaCobro,
				  c.`ci05_glosa` AS ci_glosaCobro,
				  
				  r.`ci04_rut`,
				
				  c.`ci05_monto` AS ci_montouf,
				
				  c.`ci05_valormoneda` AS ci_monto,
				
				   (SELECT 
				    op.`ci22_dinerorecibido` 
				  FROM
				    `ci22_ordenpago` op 
				    INNER JOIN `ci25_detalleindividualordenpago` dip 
				      ON op.`ci22_idordenpago` = dip.`ci22_idordenpago` 
				    INNER JOIN `ci05_cobroindividual` ci
				      ON dip.`ci05_idcobroindividual` = ci.`ci05_idcobroindividual`
				  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_montopago,
				
				   IF(
					    c.`ci05_monto` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
				
				  fp.`ci35_tipopago`,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso 
				FROM
				  `ci05_cobroindividual` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci35_formapago` fp 
				    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				WHERE r.`ci04_idrrut` = '" . $idRut . "' 
				  AND r.`ci04_estadodisponibilidad` = '1' 
				UNION
				SELECT 
				  h.`ci06_idhonorario` AS ci_idCobro,
				  IF(
				    h.`ci33_idconcepto` = '4' 
				    OR h.`ci33_idconcepto` = '5',
				    (
				      DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y')
				    ),
				    h.`ci06_fechacobro`
				  ) AS ci_fechaCobro,
						
				  h.`ci06_glosa` AS ci_glosaCobro,
				  r.`ci04_rut`,
				  h.`ci06_monto` AS ci_montouf,
				   h.`ci06_valormoneda` AS ci_monto,
				  '' AS ci_montopago,						
				 IF(
					     h.`ci06_monto` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
						
				 
				  fp.`ci35_tipopago`,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso 
				FROM
				  `ci06_honorario` h 
				  INNER JOIN `ci04_rut` r 
				    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci35_formapago` fp 
				    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				WHERE r.`ci04_idrrut` = '" . $idRut . "' 
				  AND r.`ci04_estadodisponibilidad` = '1'
				UNION
				SELECT 
				  cm.`ci07_idcobromasivo` AS ci_idCobro,
				  cm.`ci07_fechapago` AS ci_fechaCobro,
				  cc.`ci33_nombre` AS ci_glosaCobro,
				  r.`ci04_rut`,
				  '-' AS ci_montouf,
				  cm.`ci07_monto` AS ci_monto,
				  (SELECT 
				    op.`ci22_dinerorecibido` 
				  FROM
				    `ci22_ordenpago` op 
				    INNER JOIN `ci23_detallemasivoordenpago` dmp 
				      ON op.`ci22_idordenpago` = dmp.`ci22_idordenpago` 
				    INNER JOIN `ci07_cobromasivo` cma 
				      ON dmp.`ci07_idcobromasivo` = cma.`ci07_idcobromasivo` 
				  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_montopago,				
				   IF(
					     cm.`ci07_monto` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
						
				  fp.`ci35_tipopago`,
				  'Masivo' AS ci33_tipo_ingreso 
				FROM
				  `ci07_cobromasivo` cm 
				  INNER JOIN `ci04_rut` r 
				    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cli 
				    ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
				  INNER JOIN `ci35_formapago` fp 
				    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				WHERE r.`ci04_idrrut`='" . $idRut . "' 
				  AND r.`ci04_estadodisponibilidad` = '1' ;";
		
		$datos = mysql_query ( $sql );
		
		$datosCobros = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosaCobro'] = $row ['ci_glosaCobro'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci_montouf'] = $row ['ci_montouf'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_montopago'] = $row ['ci_montopago'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			
			$datosCobros [] = $entry;
		}
		
		return $datosCobros;
	}
	
	public function obtenerCobrosByCliente($idCliente) 
	{
		
		$sql = "SELECT 
			  c.`ci05_idcobroindividual` AS ci_idCobro,
			  c.`ci05_fechacobro` AS ci_fechaCobro,
			  c.`ci05_glosa` AS ci_glosaCobro,
			  r.`ci04_rut`,
			  c.`ci05_monto` AS ci_montouf,
			  c.`ci05_valormoneda` AS ci_monto,
			  (SELECT 
			    op.`ci22_dinerorecibido` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci25_detalleindividualordenpago` dip 
			      ON op.`ci22_idordenpago` = dip.`ci22_idordenpago` 
			    INNER JOIN `ci05_cobroindividual` ci 
			      ON dip.`ci05_idcobroindividual` = ci.`ci05_idcobroindividual` 
			  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_montopagado,
				
			 IF(
				c.`ci05_valormoneda` = 0,
				CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				ec.`ci53_nombreestado`
			 ) AS ci53_nombreestado,
				
			  fp.`ci35_tipopago`,
			  IF(
			    cc.`ci33_tipo_ingreso` = 1,
			    'Honorario',
			    'Cobro'
			  ) AS ci33_tipo_ingreso 
			FROM
			  `ci05_cobroindividual` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			UNION
			SELECT 
			  h.`ci06_idhonorario` AS ci_idCobro,
			  IF(
			    h.`ci33_idconcepto` = '4' 
			    OR h.`ci33_idconcepto` = '5',
			    (
			      DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y')
			    ),
			    h.`ci06_fechacobro`
			  ) AS ci_fechaCobro,
			  h.`ci06_glosa` AS ci_glosaCobro,
			  r.`ci04_rut`,
			  h.`ci06_monto` AS ci_montouf,
			  h.`ci06_valormoneda` AS ci_monto,
			  (SELECT 
			    op.`ci22_dinerorecibido` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci24_detallehonorarioordenpago` dop 
			      ON op.`ci22_idordenpago` = dop.`ci22_idordenpago` 
			    INNER JOIN `ci06_honorario` o 
			      ON dop.`ci06_idhonorario` = o.`ci06_idhonorario` 
			  WHERE o.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_montopagado,
					
			  IF(
				 h.`ci06_valormoneda` = 0,
				CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				ec.`ci53_nombreestado`
			 ) AS ci53_nombreestado,
					
			  fp.`ci35_tipopago`,
			  IF(
			    cc.`ci33_tipo_ingreso` = 1,
			    'Honorario',
			    'Cobro'
			  ) AS ci33_tipo_ingreso 
			FROM
			  `ci06_honorario` h 
			  INNER JOIN `ci04_rut` r 
			    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1'
			
				UNION
			SELECT 
			  cm.`ci07_idcobromasivo` AS ci_idCobro,
			  cm.`ci07_fechapago` AS ci_fechaCobro,
			  cc.`ci33_nombre` AS ci_glosaCobro,
			  r.`ci04_rut`,
			  '-' AS ci_montouf,
			  cm.`ci07_monto` AS ci_monto,
			 (SELECT 
			    op.`ci22_dinerorecibido` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci23_detallemasivoordenpago` dmp
			      ON op.`ci22_idordenpago` = dmp.`ci22_idordenpago`
			    INNER JOIN `ci07_cobromasivo` cma
			      ON dmp.`ci07_idcobromasivo` =cma.`ci07_idcobromasivo`
			  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_montopagado,
			   IF(
				  cm.`ci07_monto` = 0,
				CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				ec.`ci53_nombreestado`
			 ) AS ci53_nombreestado,
					
			  fp.`ci35_tipopago`,
			  'Masivo' AS ci33_tipo_ingreso 
			FROM
			  `ci07_cobromasivo` cm 
			  INNER JOIN `ci04_rut` r 
			    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cli 
			    ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE cli.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1' ;";
		
		$datos = mysql_query ( $sql );
		
		$datosCobros = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosaCobro'] = $row ['ci_glosaCobro'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			
			$entry ['ci_montouf'] = $row ['ci_montouf'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_montopagado'] = $row ['ci_montopagado'];
			
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			
			$datosCobros [] = $entry;
		}
		
		return $datosCobros;
	}

	public function obtieneHonorarios($idCliente,$fecha)
	{
		$sql="SELECT 
			  r.`ci04_idrrut` AS id_rut,
			  r.`ci04_razonsocial`,
			  CONCAT(cc.`ci33_nombre`, '') AS ci33_nombre,
			  SUM(h.`ci06_monto`) AS ci_montouf,
			  SUM(h.`ci06_valormoneda`) AS ci_monto,
			  cc.`ci33_idconcepto` 
			FROM
			  `ci06_honorario` h 
			  INNER JOIN `ci04_rut` r 
			    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND h.`ci53_idestadocobro` = '1' 
			  AND cc.`ci33_idconcepto` = h.`ci33_idconcepto` 
			  AND DATE_FORMAT(
			    STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y'),
			    '%m-%Y'
			  ) = DATE_FORMAT(
			    STR_TO_DATE('".$fecha."', '%m-%Y'),
			    '%m-%Y'
			  ) 
			  AND cc.`ci33_tipo_agrupable` = '1' 
			GROUP BY id_rut,
			  `ci33_idconcepto` 
			UNION
			SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  cc.`ci33_nombre`,
			  h.`ci06_monto` AS ci_montouf,
			  h.`ci06_valormoneda` AS ci_monto,
			  cc.`ci33_idconcepto` 
			FROM
			  `ci06_honorario` h 
			  INNER JOIN `ci04_rut` r 
			    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."'
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND DATE_FORMAT(
			    STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y'),
			    '%m-%Y'
			  ) = DATE_FORMAT(
			    STR_TO_DATE('".$fecha."', '%m-%Y'),
			    '%m-%Y'
			  ) 
			  AND cc.`ci33_tipo_agrupable` = '2' 
			  AND cc.`ci33_idconcepto` = h.`ci33_idconcepto` 
			  AND h.`ci53_idestadocobro` = '1' 
			ORDER BY `ci33_idconcepto` ;";
		
		$datos = mysql_query ( $sql );
		
		$datosHonorario = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) 
		{
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
			$entry ['ci_montouf'] = $row ['ci_montouf'];
			$entry ['ci_monto'] = $row ['ci_monto'];			
				
			$datosHonorario [] = $entry;
		}
		
		return $datosHonorario;
	}
		
	public function obtieneRazonSocialHonorariosbyCliente($idCliente,$fecha)
	{
		$sql="SELECT 
			  cl.`ci03_idcliente`,
			  r.`ci04_razonsocial` 
			FROM
			  `ci06_honorario` h 
			  INNER JOIN `ci04_rut` r 
			    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
 			  AND h.`ci53_idestadocobro`='1'
			  AND DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('".$fecha."','%m-%Y'),'%m-%Y')
			GROUP BY `ci04_razonsocial` 
			ORDER BY `ci04_razonsocial`; ";
		
		$datos = mysql_query ( $sql );
		
		$datosHonorarioRazon = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$datosHonorarioRazon [] = $entry;
		}
		
		return $datosHonorarioRazon;
	}
	
	public function obtieneDetalleOtros($idCliente,$fecha)
	{
		$sql="SELECT 
			  r.`ci04_idrrut` AS id_rut,
			  r.`ci04_razonsocial`,
			  SUM(c.`ci05_monto`) AS ci_montouf,
			  SUM(c.`ci05_valormoneda`) AS ci_monto,
			  CONCAT(cc.`ci33_nombre`, '') AS ci33_nombre,
			  fp.`ci35_tipopago`,
			  cc.`ci33_idconcepto`,
  			  DATE_FORMAT(STR_TO_DATE(c.`ci05_fechacobro`, '%d-%m-%Y'),'%m-%Y') AS ci_fecha
			FROM
			  `ci05_cobroindividual` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND c.`ci53_idestadocobro` = '1' 
			  AND cc.`ci33_tipo_agrupable` = '1' 
			  AND fp.`ci35_idformapago` = '1' 
			 AND STR_TO_DATE(c.`ci05_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('31-".$fecha."', '%d-%m-%Y')   	
			GROUP BY id_rut,
			  ci33_idconcepto 			    		
			
			UNION
			    		
			SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  c.`ci05_monto` AS ci_montouf,
			  c.`ci05_valormoneda`  AS ci_monto,
			  cc.`ci33_nombre`,
			  fp.`ci35_tipopago`,
			  cc.`ci33_idconcepto`,
  			  DATE_FORMAT(STR_TO_DATE(c.`ci05_fechacobro`, '%d-%m-%Y'),'%m-%Y') AS ci_fecha
			 		
			FROM
			  `ci05_cobroindividual` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."'  
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND c.`ci53_idestadocobro` = '1' 
			  AND cc.`ci33_tipo_agrupable` = '2' 
			  AND fp.`ci35_idformapago` = '1' 			  
			   AND STR_TO_DATE(c.`ci05_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('31-".$fecha."', '%d-%m-%Y')
			   		
			UNION
			   		
			SELECT 
			  r.`ci04_idrrut` AS id_rut,
			  r.`ci04_razonsocial`,
			  SUM(h.`ci06_monto`) AS ci_montouf,
			  SUM(h.`ci06_valormoneda`) AS ci_monto,
			  CONCAT(cc.`ci33_nombre`, '') AS ci33_nombre,
			  fp.`ci35_tipopago`,
			  cc.`ci33_idconcepto`,
			  DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y'),'%m-%Y')  AS ci_fecha
			FROM
			  `ci06_honorario` h 
			  INNER JOIN `ci04_rut` r 
			    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."'  
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND h.`ci53_idestadocobro` = '1' 
			  AND cc.`ci33_tipo_agrupable` = '1' 
			  AND STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y') 
			GROUP BY id_rut,
			  `ci33_idconcepto` 
			  		
			UNION
			  		
			SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  h.`ci06_monto` AS ci_montouf,			    		
			  h.`ci06_valormoneda` AS ci_monto,
			  CONCAT(cc.`ci33_nombre`, '') AS ci33_nombre,
			  fp.`ci35_tipopago`,
			  cc.`ci33_idconcepto`,
 			  DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y'),'%m-%Y')  AS ci_fecha
			FROM
			  `ci06_honorario` h 
			  INNER JOIN `ci04_rut` r 
			    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			WHERE cl.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND h.`ci53_idestadocobro` = '1' 
			  AND cc.`ci33_tipo_agrupable` = '2' 			  
			  AND STR_TO_DATE(h.`ci06_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y') 
			  		
			UNION
			  		
			SELECT 
			  r.`ci04_idrrut`,
			  r.`ci04_razonsocial`,
			  '' AS ci_montouf,
			  ROUND(cm.`ci07_monto`, 0) AS ci_monto,
			  cc.`ci33_nombre`,
			  fp.`ci35_tipopago`,
			  cc.`ci33_idconcepto`,
  			  cm.`ci07_fechapago` AS ci_fecha 
			FROM
			  `ci07_cobromasivo` cm 
			  INNER JOIN `ci04_rut` r 
			    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cli 
			    ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
			  INNER JOIN `ci35_formapago` fp 
			    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE cli.`ci03_idcliente` = '".$idCliente."' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  AND ec.`ci53_idestadocobro` = '1' 
			  AND fp.`ci35_idformapago` = '1' 
			  AND cc.`ci33_tipo_agrupable` = '2' 
			  AND STR_TO_DATE(CONCAT('01-', cm.`ci07_fechapago`), '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y')
			
		   ORDER BY id_rut;";
		
		
		$datos = mysql_query ( $sql );
		
		$datosDetalleOtro = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
			$entry ['ci_montouf'] = $row ['ci_montouf'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			$entry ['ci_fecha'] = $row ['ci_fecha'];
			
			$datosDetalleOtro [] = $entry;
		}
		
		return $datosDetalleOtro;
	}
	
	public function obtieneRazonSocialOtros($idCliente,$fecha)
	{
		$sql="SELECT 
			  rs.`ci04_idrrut`,
			  rs.`ci03_idcliente`,
			  rs.`ci04_razonsocial` 
			FROM
			  (SELECT 
			    r.`ci04_idrrut`,
			    r.`ci04_razonsocial`,
			    cl.`ci03_idcliente` 
			  FROM
			    `ci05_cobroindividual` c 
			    INNER JOIN `ci04_rut` r 
			      ON c.`ci04_idrrut` = r.`ci04_idrrut` 
			    INNER JOIN `ci03_cliente` cl 
			      ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			    INNER JOIN `ci53_estadocobro` ec 
			      ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  WHERE cl.`ci03_idcliente` = '".$idCliente."' 
			    AND r.`ci04_estadodisponibilidad` = '1' 
			    AND c.`ci53_idestadocobro` = '1' 
			  	AND c.`ci35_idformapago`='1'				    
			 	AND STR_TO_DATE(c.`ci05_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('31-".$fecha."', '%d-%m-%Y')
			 UNION
			  SELECT 
			    r.`ci04_idrrut`,
			    r.`ci04_razonsocial`,
			    cl.`ci03_idcliente` 
			  FROM
			    `ci06_honorario` h 
			    INNER JOIN `ci04_rut` r 
			      ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			    INNER JOIN `ci03_cliente` cl 
			      ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			    INNER JOIN `ci53_estadocobro` ec 
			      ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  WHERE cl.`ci03_idcliente` = '".$idCliente."'  
			    AND r.`ci04_estadodisponibilidad` = '1' 
			    AND ec.`ci53_idestadocobro` = '1' 
			 	AND STR_TO_DATE( h.`ci06_fechacobro`, '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y')
			 UNION
			  SELECT 
			    r.`ci04_idrrut`,
			    r.`ci04_razonsocial`,
			    cli.`ci03_idcliente` 
			  FROM
			    `ci07_cobromasivo` cm 
			    INNER JOIN `ci04_rut` r 
			      ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
			    INNER JOIN `ci03_cliente` cli 
			      ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
			    INNER JOIN `ci53_estadocobro` ec 
			      ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  WHERE cli.`ci03_idcliente` = '".$idCliente."'  
			    AND r.`ci04_estadodisponibilidad` = '1' 
			    AND ec.`ci53_idestadocobro` = '1' 
			  	AND cm.`ci35_idformapago`='1'
			  	
			  	 AND STR_TO_DATE(CONCAT('01-', cm.`ci07_fechapago`), '%d-%m-%Y') < STR_TO_DATE('01-".$fecha."', '%d-%m-%Y')	
			  		
			 
			    		) AS rs 
			GROUP BY `ci04_idrrut` 
			ORDER BY `ci04_idrrut` ;";
		
		$datos = mysql_query ( $sql );
		
		$datosOtrosRazon = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci03_idcliente'] = $row ['ci03_idcliente'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$datosOtrosRazon [] = $entry;
		}
		
		return $datosOtrosRazon;
		
	}
	
	public function filtrarMovimientos($data)
	{
		$filtroOrigen="";
		$whereIndividual ="";
		$whereHonorario ="";
		$whereMasivo ="";
		
		if($data['origen']=='1')
		{
			$filtroOrigen="cl.`ci03_idcliente`";
		}
		else
		{
			$filtroOrigen="r.`ci04_idrrut`";
		}
		
		if($data['idEstado']!='')
		{
			$whereIndividual .=" AND ec.`ci53_idestadocobro` = '".$data['idEstado']."' ";
			$whereHonorario .=" AND ec.`ci53_idestadocobro` = '".$data['idEstado']."' ";
			$whereMasivo .=" AND ec.`ci53_idestadocobro` = '".$data['idEstado']."' ";
		}		
		
		if($data['glosa']!='')
		{
			$whereIndividual .=" AND c.`ci05_glosa` LIKE '".$data['glosa']."%' ";
			$whereHonorario .=" AND h.`ci06_glosa` LIKE '".$data['glosa']."%' ";
			$whereMasivo .=" AND cc.`ci33_nombre` LIKE '".$data['glosa']."%' ";
		}		
		
		if($data['fechaInicial']!='' && $data['fechaFinal']!='')
		{
			$whereIndividual .=" AND STR_TO_DATE(c.`ci05_fechacobro`,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$data['fechaInicial']."','%d-%m-%Y') AND STR_TO_DATE('".$data['fechaFinal']."','%d-%m-%Y') ";
			$whereHonorario .=" AND STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$data['fechaInicial']."','%d-%m-%Y') AND STR_TO_DATE('".$data['fechaFinal']."','%d-%m-%Y') ";
			$whereMasivo .=" AND cm.`ci07_fechapago` BETWEEN DATE_FORMAT(STR_TO_DATE('".$data['fechaInicial']."','%d-%m-%Y'),'%m-%Y') AND DATE_FORMAT(STR_TO_DATE('".$data['fechaFinal']."','%d-%m-%Y'),'%m-%Y') ";
		}
		
		
		
		$sql="SELECT 
			  c.`ci05_idcobroindividual` AS ci_idCobro,
			  c.`ci05_fechacobro` AS ci_fechaCobro,
			  c.`ci05_glosa` AS ci_glosaCobro,
			  r.`ci04_rut`,
			  c.`ci05_monto` AS ci_montouf,
			  c.`ci05_valormoneda` AS ci_monto,
			  (SELECT 
			    op.`ci22_dinerorecibido` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci25_detalleindividualordenpago` dip 
			      ON op.`ci22_idordenpago` = dip.`ci22_idordenpago` 
			    INNER JOIN `ci05_cobroindividual` ci 
			      ON dip.`ci05_idcobroindividual` = ci.`ci05_idcobroindividual` 
			  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_montopagado,
				 IF(
				     c.`ci05_valormoneda` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,				
			  fp.`ci35_tipopago`,
			  IF(
			    cc.`ci33_tipo_ingreso` = 1,
			    'Honorario',
			    'Cobro'
			  ) AS ci33_tipo_ingreso 
			FROM				
			 `ci03_cliente` cl 
			  INNER JOIN `ci04_rut` r 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci05_cobroindividual` c 
			    ON r.`ci04_idrrut` = c.`ci04_idrrut` 
			  INNER JOIN `ci35_formapago` fp 
			    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto`
			WHERE ".$filtroOrigen." ='".$data['id']."'
			   AND r.`ci04_estadodisponibilidad` = '1' 
			   $whereIndividual
			   
			UNION			  		
			  		
			SELECT 
			  h.`ci06_idhonorario` AS ci_idCobro,
			  
			  IF(
			    h.`ci33_idconcepto` = '4' 
			    OR h.`ci33_idconcepto` = '5',
			    (
			      DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y')
			    ),
			    h.`ci06_fechacobro`
			  ) AS ci_fechaCobro,
			  
			  h.`ci06_glosa` AS ci_glosaCobro,
			  r.`ci04_rut`,
			  h.`ci06_monto` AS ci_montouf,
			  h.`ci06_valormoneda` AS ci_monto,
			  (SELECT 
			    op.`ci22_dinerorecibido` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci24_detallehonorarioordenpago` dop 
			      ON op.`ci22_idordenpago` = dop.`ci22_idordenpago` 
			    INNER JOIN `ci06_honorario` o 
			      ON dop.`ci06_idhonorario` = o.`ci06_idhonorario` 
			  WHERE o.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_montopagado,
			  
			  IF(
			    h.`ci06_valormoneda` = 0,
			    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
			    ec.`ci53_nombreestado`
			  ) AS ci53_nombreestado,
			  		
			  fp.`ci35_tipopago`,
			  IF(
			    cc.`ci33_tipo_ingreso` = 1,
			    'Honorario',
			    'Cobro'
			  ) AS ci33_tipo_ingreso 
			FROM
			  `ci03_cliente` cl 
			  INNER JOIN `ci04_rut` r 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci06_honorario` h 
			    ON r.`ci04_idrrut` = h.`ci04_idrrut` 
			  INNER JOIN `ci35_formapago` fp 
			    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE ".$filtroOrigen." = '".$data['id']."'
			  AND r.`ci04_estadodisponibilidad` = '1' 
			  $whereHonorario	
			UNION
			SELECT 
			  cm.`ci07_idcobromasivo` AS ci_idCobro,
			  cm.`ci07_fechapago` AS ci_fechaCobro,
			  cc.`ci33_nombre` AS ci_glosaCobro,
			  r.`ci04_rut`,
			  '-' AS ci_montouf,
			  cm.`ci07_monto` AS ci_monto,
			  (SELECT 
			    op.`ci22_dinerorecibido` 
			  FROM
			    `ci22_ordenpago` op 
			    INNER JOIN `ci23_detallemasivoordenpago` dmp 
			      ON op.`ci22_idordenpago` = dmp.`ci22_idordenpago` 
			    INNER JOIN `ci07_cobromasivo` cma 
			      ON dmp.`ci07_idcobromasivo` = cma.`ci07_idcobromasivo` 
			  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_montopagado,
			  ec.`ci53_nombreestado` AS ci53_nombreestado,
			  fp.`ci35_tipopago`,
			  'Masivo' AS ci33_tipo_ingreso 
			FROM
			 `ci03_cliente` cl 
			  INNER JOIN `ci04_rut` r 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` cm 
			    ON r.`ci04_idrrut` = cm.`ci04_idrrut` 
			  INNER JOIN `ci35_formapago` fp 
			    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci33_conceptocobro` cc 
			    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
			WHERE ".$filtroOrigen." = '".$data['id']."' 
 			  $whereMasivo		
			  AND r.`ci04_estadodisponibilidad` = '1';";
		
		$datos = mysql_query ( $sql );
		
		$datosCobros = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosaCobro'] = $row ['ci_glosaCobro'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
				
			$entry ['ci_montouf'] = $row ['ci_montouf'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_montopagado'] = $row ['ci_montopagado'];
				
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
				
			$datosCobros [] = $entry;
		}
		
		return $datosCobros;
	}
	
	public function detalleCobrosByIdCliente($idCliente) 
	{
		$sql = "SELECT 
			  ROUND(c.`ci05_monto` * c.`ci05_valoruf`,0) AS ci_monto,
			  '0' AS ci_saldo 
			FROM
			  `ci05_cobroindividual` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			WHERE cl.`ci03_idcliente` = '" . $idCliente . "' 
			  AND ec.`ci53_idestadocobro` = '1' 
			  AND r.`ci04_estadodisponibilidad` = '1' 
			UNION
			SELECT 
			  ROUND(h.`ci06_monto` * h.`ci06_valoruf`,0) AS ci_monto,
			  '0' AS ci_saldo 
			FROM
			  `ci06_honorario` h 
			  INNER JOIN `ci04_rut` r 
			    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
			  INNER JOIN `ci03_cliente` cl 
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			WHERE cl.`ci03_idcliente` = '" . $idCliente . "' 
			  AND ec.`ci53_idestadocobro` = '1' 
			  AND r.`ci04_estadodisponibilidad` = '1' ;";
		
		$datos = mysql_query ( $sql );
		
		$detalleCobros = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_saldo'] = $row ['ci_saldo'];
			
			$detalleCobros [] = $entry;
		}
		
		return $detalleCobros;
	}

	public function obtenerHonorarioById($idCobro) 
	{
		$sql = "SELECT 
				  o.`ci04_idrrut`,
				  r.`ci04_rut`,
				  o.`ci33_idconcepto`,
				  o.`ci35_idformapago`,
				  o.`ci06_fechacobro`,
				  o.`ci06_glosa`,
				  o.`ci06_monto`,
		     	  o.`ci06_valoruf`,
				  o.`ci06_observacion`,
				  o.`ci06_valormoneda`
				FROM
				  `ci06_honorario` o 
				  INNER JOIN `ci04_rut` r 
				    ON o.`ci04_idrrut` = r.`ci04_idrrut` 
				WHERE o.`ci06_idhonorario` = '" . $idCobro . "' ;";
		
		$datoCobro = mysql_query ( $sql );
		
		$conceptos = array ();
		
		while ( $row = mysql_fetch_array ( $datoCobro ) ) {
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci33_idconcepto'] = $row ['ci33_idconcepto'];
			$entry ['ci35_idformapago'] = $row ['ci35_idformapago'];
			$entry ['ci06_fechacobro'] = $row ['ci06_fechacobro'];
			$entry ['ci06_glosa'] = $row ['ci06_glosa'];
			$entry ['ci06_monto'] = $row ['ci06_monto'];
			$entry ['ci06_valoruf'] = $row ['ci06_valoruf'];
			$entry ['ci06_observacion'] = $row ['ci06_observacion'];
			$entry ['ci06_valormoneda'] = $row ['ci06_valormoneda'];
			
			$cobro [] = $entry;
		}
		
		return $cobro;
	}
	
	public function obtenerCobroIndividualById($idCobro) 
	{
		$sql = "SELECT 
			ci.`ci04_idrrut`, 
			r.`ci04_rut`,
			ci.`ci33_idconcepto`,
			ci.`ci35_idformapago`,
			ci.`ci05_fechacobro`,
			ci.`ci05_glosa`,
			ci.`ci05_monto`,
			ci.`ci05_valoruf`,
			ci.`ci05_observacion`,
			ci.`ci05_valormoneda`	
			FROM 
			`ci05_cobroindividual` ci INNER JOIN `ci04_rut` r 
			ON ci.`ci04_idrrut`=r.`ci04_idrrut` WHERE ci.`ci05_idcobroindividual`='" . $idCobro . "';";
		
		$datoCobro = mysql_query ( $sql );
		
		$conceptos = array ();
		
		while ( $row = mysql_fetch_array ( $datoCobro ) ) 
		{
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci33_idconcepto'] = $row ['ci33_idconcepto'];
			$entry ['ci35_idformapago'] = $row ['ci35_idformapago'];
			$entry ['ci05_fechacobro'] = $row ['ci05_fechacobro'];
			$entry ['ci05_glosa'] = $row ['ci05_glosa'];
			$entry ['ci05_monto'] = $row ['ci05_monto'];
			$entry ['ci05_valoruf'] = $row ['ci05_valoruf'];
			$entry ['ci05_observacion'] = $row ['ci05_observacion'];
			$entry ['ci05_valormoneda'] = $row ['ci05_valormoneda'];
			
			$cobro [] = $entry;
		}
		
		return $cobro;
	}

	public function obtenerDatosCobroEmail($idCobro, $tipoCobro)
	{
		if ($tipoCobro == 'Honorario') 
		{
			$sql = "SELECT 
				  cc.`ci33_nombre`,
				  r.`ci04_razonsocial`,
				  h.`ci06_monto` AS ci_monto,
				  h.`ci06_valoruf` AS ci_valoruf,
  				  h.`ci35_idformapago`,
				  h.ci06_valormoneda AS ci_valormoneda
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `ci06_honorario` h 
				    ON r.`ci04_idrrut` = h.`ci04_idrrut` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				WHERE h.`ci06_idhonorario` = '" . $idCobro . "';";
		} 		
		else 
		{
			$sql = "SELECT 
				  cc.`ci33_nombre`,
				  r.`ci04_razonsocial`,
				  ci.`ci05_monto` AS ci_monto,
				  ci.`ci05_valoruf` AS ci_valoruf,
  				  ci.`ci35_idformapago`,
				  ci.ci05_valormoneda AS ci_valormoneda
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `ci05_cobroindividual` ci 
				    ON r.`ci04_idrrut` = ci.`ci04_idrrut` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON ci.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				WHERE ci.`ci05_idcobroindividual` =  '" . $idCobro . "';";
		}
		
		$datoCobro = mysql_query ( $sql );
		
		$datosCobro = array ();
		
		while ( $row = mysql_fetch_array ( $datoCobro ) ) 
		{
			$entry ['ci33_nombre'] = $row ['ci33_nombre'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_valoruf'] = $row ['ci_valoruf'];
			$entry ['ci35_idformapago'] = $row ['ci35_idformapago'];
			$entry ['ci_valormoneda'] = $row ['ci_valormoneda'];
			
			$datosCobro [] = $entry;
		}
		
		return $datosCobro;
	}
	
	public function modificaHonorario($data)
	{
		$sql = "UPDATE 
				`ci06_honorario`
				SET 
				`ci33_idconcepto`='" . $data ['idConceptoCobro'] . "',
				`ci35_idformapago`='" . $data ['pagoCobro'] . "',
				`ci06_fechacobro`='" . $data ['fechaCobro'] . "',
				`ci06_glosa`='" . $data ['glosaCobro'] . "',
				`ci06_monto`='" . $data ['montoCobro'] . "',
				`ci06_observacion`='" . $data ['observacionCobro'] . "',
				`ci06_valoruf`='".$data ['valorUF']."',
				`ci06_valormoneda`='".$data ['valorMoneda']."'		
						
				WHERE
				`ci06_idhonorario`='" . $data ['idCobro'] . "';";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public function modificaCobroIndividual($data)
	{
		$sql = "UPDATE 
				`ci05_cobroindividual` 
				SET 
				`ci33_idconcepto`='" . $data ['idConceptoCobro'] . "',
				`ci35_idformapago`='" . $data ['pagoCobro'] . "',
				`ci05_fechacobro`='" . $data ['fechaCobro'] . "',
				`ci05_glosa`='" . $data ['glosaCobro'] . "',
				`ci05_monto`='" . $data ['montoCobro'] . "',
				`ci05_observacion`='" . $data ['observacionCobro'] . "',
				`ci05_valoruf`='".$data ['valorUF']."',
				`ci05_valormoneda`='".$data ['valorMoneda']."'
				WHERE 
				`ci05_idcobroindividual`='" . $data ['idCobro'] . "';";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function registroModificacionCobroIndividual($data) 
	{
		$sql = "INSERT INTO 
				ci46_registromodificaindividual (
				`lc01_idusuario`,
				`ci05_idcobroindividual`,
				`ci46_fecha`)
				VALUES(
				'" . $data ['idUsuario'] . "',
				'" . $data ['idCobro'] . "',
				DATE(NOW())
				);";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function registroModificacionHonorario($data) 
	{
		$sql = "INSERT INTO 
			 `ci45_registromodificahonorario` (
			 `lc01_idusuario`,
			 `ci06_idhonorario`,
			 `ci45_fecha`)
			 VALUES(
			 '" . $data ['idUsuario'] . "',
			 '" . $data ['idCobro'] . "',
			 DATE(NOW()))";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function modificarEstadoCobroHonorario($estadoCobro, $idCobro) 
	{
		$sql = "UPDATE 
			 `ci06_honorario` 
			 SET 
			 `ci53_idestadocobro`='" . $estadoCobro . "' 
			 WHERE 
			 `ci06_idhonorario`='" . $idCobro . "';";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function modificarEstadoCobroMasivo($estadoCobro, $idCobro)
	{
		$sql = "UPDATE
			`ci07_cobromasivo`
			 SET
			 `ci53_idestadocobro`='" . $estadoCobro . "'
			 WHERE
			 `ci07_idcobromasivo`='" . $idCobro . "';";
	
		$res = mysql_query ( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function modificarEstadoCobroIndividual($estadoCobro, $idCobro) 
	{
		$sql = "UPDATE 
			 `ci05_cobroindividual` 
			 SET 
			 `ci53_idestadocobro`='" . $estadoCobro . "' 
			 WHERE 
			 `ci05_idcobroindividual`='" . $idCobro . "';";
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function eliminarCobro($idCobro, $tipoCobro) 
	{
		if ($tipoCobro == '1') {
			$sql = "DELETE FROM `ci06_honorario` WHERE  `ci06_idhonorario`='" . $idCobro . "';";
		} else {
			$sql = "DELETE FROM `ci05_cobroindividual` WHERE `ci05_idcobroindividual` ='" . $idCobro . "';";
		}
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function autorizaPagoExtraordinario($data) 
	{
		if ($data ['tipoCobro'] == '1') {
			$sql = "UPDATE 
				  `ci06_honorario` 
				SET
				  `ci06_autorizapago` = '1' 
				WHERE `ci06_idhonorario` = '" . $data ['idCobro'] . "';";
		} else if ($data ['tipoCobro'] == '2') {
			$sql = "UPDATE 
			  `ci05_cobroindividual` 
			SET
			  `ci05_autorizapago` = '1' 
			WHERE `ci05_idcobroindividual` = '" . $data ['idCobro'] . "';";
		}
		else{
			$sql="UPDATE 
				  `ci07_cobromasivo` 
				SET
				  `ci07_autorizapago` = '1' 
				WHERE `ci07_idcobromasivo` = '" . $data ['idCobro'] . "';";
		}
		
		$res = mysql_query ( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public function verificaAutorizacionPago($tipoCobro,$idCobro) 
	{
		if ($tipoCobro == '1') 
		{
			$sql = "SELECT
				 `ci06_autorizapago` AS ci_autoriza
				FROM
				  `ci06_honorario`
				WHERE `ci06_idhonorario` = '" . $idCobro . "';";
		} 
		else if ($tipoCobro == '2') 
		{
			$sql = "SELECT
				  `ci05_autorizapago` AS ci_autoriza
				FROM
				  `ci05_cobroindividual`
				WHERE `ci05_idcobroindividual` = '" . $idCobro . "';";
		}
		else 
		{
			$sql="SELECT 
				  `ci07_autorizapago` AS ci_autoriza 
				FROM
				  `ci07_cobromasivo` 
				WHERE `ci07_idcobromasivo` = '" . $idCobro . "';";
		}
		
		$datos = mysql_query ( $sql );
		
		$autoriza = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$autoriza = $row ['ci_autoriza'];
		}
		
		return $autoriza;
	}
	
	public function obtenerEstadosCobro()
	{	
		$sql = "SELECT 
				  ci53_idestadocobro,
				  ci53_nombreestado,
				  ci53_descripcion 
				FROM
				  ci53_estadocobro ";

	
		$datos = mysql_query ( $sql );
	
		$estados = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$estados['ci53_idestadocobro'] = $row ['ci53_idestadocobro'];
			$estados['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$estados['ci53_descripcion'] = $row ['ci53_descripcion'];
			
			$arreglo[] = $estados;
		}
	
		return $arreglo;
	}
		
	public function buscarCobrosCompensacion($filtros)
	{
		$where = '';
		$whereFecha1 ="";
		$whereFecha2 ="";
		$whereFecha3 ="";
		$montoDesde1 ="";
		$montoDesde2 ="";
		$montoDesde3 ="";
		$montoHasta1 = "";
		$montoHasta2 = "";
		$montoHasta3 = "";
		$estado1 = "";
		$estado2 = "";
		$estado3 = "";

		if( $filtros['id_ejecutivo']!= "")
		{
			$where .= " AND u.lc01_idUsuario = '". $filtros['id_ejecutivo']."' ";
		}
		
		if(	$filtros['id_cliente'] != "")
		{
			$where .= " AND cl.ci03_idcliente = '". $filtros['id_cliente']."' ";
		}
		
		if(	$filtros['id_rut'] !=  "")
		{
			$where .= " AND r.ci04_idrrut = '". $filtros['id_rut']."' ";
		}
		
		if(	$filtros['fecha'] !=  "")
		{
			$fecha = explode("-",$filtros['fecha']);
			$fechaInicio=str_replace("/","-",$fecha[0]);
			$fechaFinal=str_replace("/","-",$fecha[1]);
			
			$whereFecha1 = " AND STR_TO_DATE(c.ci05_fechacobro,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$fechaInicio."','%d-%m-%Y') AND STR_TO_DATE('".$fechaFinal."','%d-%m-%Y') ";
			$whereFecha2 = " AND STR_TO_DATE(h.ci06_fechacobro,'%d-%m-%Y') BETWEEN STR_TO_DATE('".$fechaInicio."','%d-%m-%Y') AND STR_TO_DATE('".$fechaFinal."','%d-%m-%Y') ";
			$whereFecha3 = " AND DATE_FORMAT(cm.ci07_fechapago,'%m-%Y') BETWEEN DATE_FORMAT(STR_TO_DATE('".$fechaInicio."','%d-%m-%Y'),'%m-%Y') AND DATE_FORMAT(STR_TO_DATE('".$fechaFinal."','%d-%m-%Y'),'%m-%Y') ";
		}
		
		if(	$filtros['tipo_cobro'] != "")
		{
			$where .= " AND ci33_tipo_ingreso = '".$filtros['tipo_cobro']."' ";
		}
		
		if(	$filtros['monto_desde'] !=  "")
		{
			$montoDesde1 .= " AND CAST(c.ci05_valormoneda AS SIGNED) >= '".$filtros['monto_desde']."' ";
			$montoDesde2 .= " AND CAST(h.ci06_valormoneda AS SIGNED) >= '".$filtros['monto_desde']."' ";
			$montoDesde3 .= " AND CAST(cm.ci07_monto AS SIGNED) >= '".$filtros['monto_desde']."' ";
		}
		
		if(	$filtros['monto_hasta'] != "")
		{
			$montoHasta1 .= " AND CAST(c.ci05_valormoneda AS SIGNED) <=  '".$filtros['monto_hasta']."' ";
			$montoHasta2 .= " AND CAST(h.ci06_valormoneda AS SIGNED) <=  '".$filtros['monto_hasta']."' ";
			$montoHasta3 .= " AND CAST(cm.ci07_monto AS SIGNED) <=  '".$filtros['monto_hasta']."' ";
		}
		
		if(	$filtros['estado_cobro'] != "")
		{
			$estado1 .= " AND c.ci53_idestadocobro =  '".$filtros['estado_cobro']."' ";
			$estado2 .= " AND h.ci53_idestadocobro =  '".$filtros['estado_cobro']."' ";
			$estado3 .= " AND cm.ci53_idestadocobro =  '".$filtros['estado_cobro']."' ";
		}
				
	
		$sql = "SELECT 
				  c.`ci05_idcobroindividual` AS ci_idCobro,
				
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
				
				  c.`ci05_fechacobro` AS ci_fechaCobro,
				
				  c.`ci05_glosa` AS ci_glosa,
				
				  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
				
				  r.`ci04_rut`,
				
				  (SELECT 
				    cla.`ci11_previred` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci05_cobroindividual` ci 
				      ON ru.`ci04_idrrut` = ci.`ci04_idrrut` 
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND ru.`ci04_previred` = '1' 
				    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_claveprevired,
				
				  (SELECT 
				    cla.`ci11_sii` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci05_cobroindividual` ci 
				      ON ru.`ci04_idrrut` = ci.`ci04_idrrut` 
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND ru.`ci04_iva` = '1' 
				    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_clavesii,
				
				  '' AS `ci_numerocuenta`,
				
				   c.`ci05_valormoneda` AS ci_monto,
				
				  IF(
				    c.ci05_montoCompensado IS NULL,
				    0,
				    c.ci05_montoCompensado
				  ) AS ci_dinerorecibido,
				
				
				  IF(
				     c.`ci05_valormoneda` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				
				
				  fp.`ci35_tipopago`,
				
				  '-' AS ci_numerofactura,
				
				  c.`ci05_observacion` AS ci_observacion,
				
				  '1' tipoCobro,
				
				  c.ci04_idrrut idRut,
				
				  c.ci33_idconcepto idConcepto,
				
				  c.ci35_idformapago idFormaPago,
				
				   (SELECT ci12_canje.ci12_numerodocumento
					FROM ci12_canje
					INNER JOIN ci59_detallecanjeindividual ON ci59_detallecanjeindividual.ci12_idcanje = ci12_canje.ci12_idcanje
					WHERE ci59_detallecanjeindividual.ci05_idcobroindividual = c.ci05_idcobroindividual) AS documento_canje
				
				FROM
				  `ci05_cobroindividual` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cl 
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `lc01_usuario` u 
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
				  INNER JOIN `ci35_formapago` fp 
				    ON c.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				  INNER JOIN `ci36_estadocompensacion` ecom 
				    ON c.`ci36_idestadocomepnsacion` = ecom.`ci36_idEstadoCompensacion` 
				WHERE r.`ci04_estadodisponibilidad` = '1' " . $where.$whereFecha1.$montoDesde1.$montoHasta1.$estado1." 
				  AND c.`ci35_idformapago`='1'
						
				UNION
						
				SELECT 
				  h.`ci06_idhonorario` AS ci_idCobro,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
						
				   IF(
				    h.`ci33_idconcepto` = '4' 
				    OR h.`ci33_idconcepto` = '5',
				    (
				      DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y')
				    ),
				    h.`ci06_fechacobro`
				  ) AS ci_fechaCobro,
				  
				  h.`ci06_glosa` AS ci_glosa,
				  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT 
				    cla.`ci11_previred` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci06_honorario` hon 
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut` 
				  WHERE ru.`ci04_previred` = '1' 
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_claveprevired,
				 (SELECT 
				    cla.`ci11_sii` 
				  FROM
				    `ci04_rut` ru 
				    INNER JOIN `ci11_clave` cla 
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci06_honorario` hon 
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut` 
				  WHERE ru.`ci04_iva` = '1' 
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_clavesii,
				  '' AS `ci_numerocuenta`,						
						
				  h.`ci06_valormoneda` AS ci_monto,
						
				  IF(
				    h.ci06_montoCompensado IS NULL,
				    0,
				    h.ci06_montoCompensado
				  ) AS ci_dinerorecibido,
						
						 IF(
					      h.`ci06_valormoneda` = 0,
					    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
					    ec.`ci53_nombreestado`
					  ) AS ci53_nombreestado,
						
				  fp.`ci35_tipopago`,
				  (SELECT 
				    fac.`ci34_numerofcactura` 
				  FROM
				    `ci06_honorario` hon 
				    INNER JOIN `ci34_factura` fac 
				      ON hon.`ci06_idhonorario` = fac.`ci06_idhonorario` 
				  WHERE hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_numerofactura,
				  h.`ci06_observacion` AS ci_observacion,
				  '2' tipoCobro,
				  h.ci04_idrrut idRut,
				  h.ci33_idconcepto idConcepto,
				  h.ci35_idformapago idFormaPago,
				   (SELECT ci12_canje.ci12_numerodocumento
					FROM ci12_canje
					INNER JOIN ci60_detallecanjehonorario ON ci60_detallecanjehonorario.ci06_idhonorario = ci12_canje.ci12_idcanje
					WHERE ci60_detallecanjehonorario.ci06_idhonorario = h.ci06_idhonorario) AS documento_canje
				FROM
				  `ci06_honorario` h 
				  INNER JOIN `ci04_rut` r 
				    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cl 
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `lc01_usuario` u 
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
				  INNER JOIN `ci35_formapago` fp 
				    ON h.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				  INNER JOIN `ci36_estadocompensacion` ecom 
				    ON h.`ci36_idestadocomepnsacion` = ecom.`ci36_idEstadoCompensacion` 
				WHERE r.`ci04_estadodisponibilidad` = '1' " . $where.$whereFecha2.$montoDesde2.$montoHasta2.$estado2." 
				 
				  AND h.`ci35_idformapago`='5'	
						
				UNION						
						
				SELECT 
				  cm.`ci07_idcobromasivo` AS ci_idCobro,
				  'Masivo' AS ci33_tipo_ingreso,
				  cm.`ci07_fechapago` AS ci_fechaCobro,
				  cc.`ci33_nombre` AS ci_glosa,
				  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT 
				    cla.`ci11_previred` 
				  FROM
				    `ci04_rut` rut 
				    INNER JOIN `ci11_clave` cla 
				      ON rut.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci07_cobromasivo` cma 
				      ON rut.`ci04_idrrut` = cma.`ci04_idrrut` 
				  WHERE rut.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND rut.`ci04_previred` = '1' 
				    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_claveprevired,
				  (SELECT 
				    cla.`ci11_sii` 
				  FROM
				    `ci04_rut` rut 
				    INNER JOIN `ci11_clave` cla 
				      ON rut.`ci04_idrrut` = cla.`ci04_idrrut` 
				    INNER JOIN `ci07_cobromasivo` cma 
				      ON rut.`ci04_idrrut` = cma.`ci04_idrrut` 
				  WHERE rut.`ci04_idrrut` = r.`ci04_idrrut` 
				    AND rut.`ci04_iva` = '1' 
				    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_clavesii,
				  '' AS `ci_numerocuenta`,
				  cm.`ci07_monto` AS ci_monto,
				  IF(
				    cm.ci07_montoCompensado IS NULL,
				    0,
				    cm.ci07_montoCompensado
				  ) AS ci_dinerorecibido,						
				   IF(
			     cm.`ci07_monto` = 0,
			    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
			    ec.`ci53_nombreestado`
			  ) AS ci53_nombreestado,
				
				  fp.`ci35_tipopago`,
				  '-' AS ci_numerofactura,
				  '' AS ci_observacion,
				  '3' tipoCobro,
				  cm.ci04_idrrut idRut,
				  cm.ci33_idconcepto idConcepto,
				  cm.ci35_idformapago idFormaPago,
				  (SELECT ci12_canje.ci12_numerodocumento
					FROM ci12_canje
					INNER JOIN ci61_detallecanjemasivo ON ci61_detallecanjemasivo.ci12_idcanje = ci12_canje.ci12_idcanje
					WHERE ci61_detallecanjemasivo.ci07_idcobromasivo = cm.ci07_idcobromasivo) AS documento_canje
				FROM
				  `ci07_cobromasivo` cm 
				  INNER JOIN `ci04_rut` r 
				    ON cm.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cl 
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `lc01_usuario` u 
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario` 
				  INNER JOIN `ci35_formapago` fp 
				    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
				  INNER JOIN `ci53_estadocobro` ec 
				    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				  INNER JOIN `ci36_estadocompensacion` ecom 
				    ON cm.`ci36_idestadocomepnsacion` = ecom.`ci36_idEstadoCompensacion` 
				WHERE r.`ci04_estadodisponibilidad` = '1' " . $where.$whereFecha3.$montoDesde3.$montoHasta3.$estado3." 
				
				  AND cm.`ci35_idformapago`='1'; ";
	
		$datos = mysql_query ( $sql );
	
		$listadoCobros = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
				
			$entry ['ci_claveprevired'] = $row ['ci_claveprevired'];
			$entry ['ci_clavesii'] = $row ['ci_clavesii'];
			$entry ['ci_numerocuenta'] = $row ['ci_numerocuenta'];
				
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_dinerorecibido'] = $row ['ci_dinerorecibido'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
				
			$entry ['ci_numerofactura'] = $row ['ci_numerofactura'];
				
			$entry ['ci_observacion'] = $row ['ci_observacion'];
			$entry ['tipoCobro'] = $row ['tipoCobro'];
			$entry ['idRut'] = $row ['idRut'];
			$entry ['idConcepto'] = $row ['idConcepto'];
			$entry ['idFormaPago'] = $row ['idFormaPago'];
			$entry ['documento_canje'] = $row ['documento_canje'];
				
			$listadoCobros [] = $entry;
		}
	
		return $listadoCobros;
	}
	
	public function compensaCobros($cobros,$movimiento,$totalCompensado,$flagCompensaConSaldo,$idCliente,$montoSaldoAnterior)
	{
		$sql="";
		$tabla ="";
		$colMontoCompensado ="";
		$colIdCobro ="";
		
		foreach($cobros as $key => $value)
		{
			$monto = $value['monto'];
			$flagpasarverificado = $value['pasarverificado'];
			$llave = explode('-',$value['llave']);
			
			
			$tipoCobro = $llave[0];
			$idCobro = $llave[1];
			$montoTotalCobro =  $llave[2];
			$montoYaCompensado = $llave[3];
			
			$montoFinal = $montoYaCompensado + $monto;
			
			$flagEstadoCompensacion="";
			$flagEstadoCobro="";
			
			if($montoFinal <  $montoTotalCobro)
			{
				$flagEstadoCompensacion = '3';
				$flagEstadoCobro="1";
			}elseif($montoFinal ==  $montoTotalCobro){
				$flagEstadoCompensacion = '2';
				$flagEstadoCobro ="2";
			}
			
			
			if($flagpasarverificado)
			{
				$flagEstadoCobro ="4";
			}
			
			switch ($tipoCobro) {
				case 1:
					$tabla = "ci05_cobroindividual";
					$colMontoCompensado = "ci05_montoCompensado";
					$colIdCobro ="ci05_idcobroindividual";
					break;
				case 2:
					$tabla = "ci06_honorario";
					$colMontoCompensado = "ci06_montoCompensado";
					$colIdCobro ="ci06_idhonorario";
					break;
				case 3:
					$tabla = "ci07_cobromasivo";
					$colMontoCompensado = "ci07_montoCompensado";
					$colIdCobro ="ci07_idcobromasivo";
					break;
			}		
			
			$sql .= "UPDATE ".$tabla." 
					SET ci36_idestadocomepnsacion ='$flagEstadoCompensacion',
						ci53_idestadocobro = '$flagEstadoCobro',
						$colMontoCompensado = '$montoFinal' 
					WHERE $colIdCobro = '$idCobro'; ";
			
			
			if($flagCompensaConSaldo ==1){
				
				$sql .= "INSERT INTO ci63_compensacionSaldo (
															ci03_idCliente,
															ci63_montoCompensacion,
															ci63_saldoAnterior,
															ci63_tipoCobro,
															ci63_idCobro,
															ci63_fechaCompensacion
															)
															VALUES (
															'$idCliente',
															'$monto',
															'$montoSaldoAnterior',
															'$tipoCobro',
															'$idCobro',
															 NOW()
															);";
				
				$sql .= "UPDATE ci03_cliente
							SET ci03_saldo = (ci03_saldo-$monto)
							WHERE ci03_idcliente = '$idCliente';";
				
			}else{
				$sql .= "INSERT INTO ci08_compensacion (ci08_tipoCobro,
														ci08_idCobro,
														ci08_idMovimientoCartola,
														ci08_montoCompensacion) 
												VALUES ('$tipoCobro',
														'$idCobro',
														'$movimiento',
														'$monto');";
			}
		}
		
		
		if($flagCompensaConSaldo == 0){
		
		$sql .= "UPDATE ci17_detallecartola
				SET ci17_montoCompensado = (ci17_montoCompensado+$totalCompensado),
					ci36_idEstadoCompensacion = IF(ci17_montoCompensado = (ci17_cheque_cargo + ci17_deposito_abono),2,3),
					ci17_fechaUltimaModificacion = CURRENT_TIMESTAMP
				WHERE ci17_idDetalleCartola = '$movimiento';";
	
		}
		
		
		
		$res = mysqli_multi_query ($this->_link, $sql );
		
		
		if ($res) {
			return true;
		} else {
			return false;
		}
		
	}

	public function traspasoMontoaSaldo($monto,$movimiento,$cliente)
	{

		$sql = "	UPDATE ci03_cliente
					SET ci03_saldo = (ci03_saldo+$monto)
					WHERE ci03_idcliente = '$cliente';";
				
		
		$sql .= "	UPDATE ci17_detallecartola
					SET ci17_montoCompensado = (ci17_montoCompensado+$monto),
						ci36_idEstadoCompensacion = 2,
						ci17_fechaUltimaModificacion = CURRENT_TIMESTAMP
					WHERE ci17_idDetalleCartola = '$movimiento';";
	
		$sql .= "	INSERT INTO ci08_compensacion (
								ci08_idCliente,
								ci08_idMovimientoCartola,
								ci08_montoCompensacion)
						VALUES (
								'$cliente',
								'$movimiento',
								'$monto');";
	
		$res = mysqli_multi_query ($this->_link, $sql );
	
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	
	}
	
	public function obtieneDetalleCompensacion($movimiento)
	{
		
		$sql = "SELECT 	ci08_compensacion.ci08_idCompensacion,
						ci08_compensacion.ci08_tipoCobro,
						ci08_compensacion.ci08_idCobro,
						(CASE ci08_compensacion.ci08_tipoCobro
							WHEN '1' THEN (SELECT ci05_cobroindividual.ci05_glosa FROM ci05_cobroindividual WHERE ci05_cobroindividual.ci05_idcobroindividual = ci08_compensacion.ci08_idCobro)
							WHEN '2' THEN (SELECT ci06_honorario.ci06_glosa FROM ci06_honorario WHERE ci06_honorario.ci06_idhonorario = ci08_compensacion.ci08_idCobro)		
							WHEN '3' THEN (SELECT ci33_conceptocobro.ci33_nombre FROM ci07_cobromasivo INNER JOIN ci33_conceptocobro ON ci07_cobromasivo.ci33_idconcepto = ci33_conceptocobro.ci33_idconcepto WHERE ci07_cobromasivo.ci07_idcobromasivo = ci08_compensacion.ci08_idCobro)
						END) glosa,
						(CASE ci08_compensacion.ci08_tipoCobro
							WHEN '1' THEN (SELECT ci04_rut.ci04_rut FROM ci05_cobroindividual INNER JOIN ci04_rut ON ci05_cobroindividual.ci04_idrrut = ci04_rut.ci04_idrrut WHERE ci05_cobroindividual.ci05_idcobroindividual = ci08_compensacion.ci08_idCobro)
							WHEN '2' THEN (SELECT ci04_rut.ci04_rut FROM ci06_honorario INNER JOIN ci04_rut ON ci06_honorario.ci04_idrrut = ci04_rut.ci04_idrrut WHERE ci06_honorario.ci06_idhonorario = ci08_compensacion.ci08_idCobro)		
							WHEN '3' THEN (SELECT ci04_rut.ci04_rut FROM ci07_cobromasivo INNER JOIN ci04_rut ON ci07_cobromasivo.ci04_idrrut = ci04_rut.ci04_idrrut WHERE ci07_cobromasivo.ci07_idcobromasivo = ci08_compensacion.ci08_idCobro)
						END) rut,
						ci08_compensacion.ci08_idCliente,
						(SELECT ci03_cliente.ci03_nombre 
								FROM ci03_cliente 
								WHERE ci03_cliente.ci03_idcliente = ci08_compensacion.ci08_idCliente ) ci03_nombre,
						ci08_compensacion.ci08_idMovimientoCartola,
						ci08_compensacion.ci08_montoCompensacion,
						ci08_compensacion.ci08_fechaCompensacion,
						IF(
						    ci08_compensacion.ci08_idCliente IS NULL,
						    IF(ci08_compensacion.ci26_idpagoproveedor IS NULL,'COMPENSACION A COBRO','PAGO A PROVEEDOR'),
						    'TRASPASO A SALDO CLIENTE'
						 ) tipo_compensacion 
					FROM ci08_compensacion
					WHERE ci08_idMovimientoCartola = $movimiento;";
	
		$datos = mysql_query ( $sql );
	
		$lineas = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci08_idCompensacion'] = $row ['ci08_idCompensacion'];
			$entry ['ci08_tipoCobro'] = $row ['ci08_tipoCobro'];
			$entry ['ci08_idCobro'] = $row ['ci08_idCobro'];
			$entry ['glosa'] = $row ['glosa'];
			$entry ['rut'] = $row ['rut'];
			$entry ['ci08_idCliente'] = $row ['ci08_idCliente'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];	
			$entry ['ci08_idMovimientoCartola'] = $row ['ci08_idMovimientoCartola'];
			$entry ['ci08_montoCompensacion'] = $row ['ci08_montoCompensacion'];
			$entry ['ci08_fechaCompensacion'] = $row ['ci08_fechaCompensacion'];
			$entry ['tipo_compensacion'] = $row ['tipo_compensacion'];
	
			$lineas [] = $entry;
		}
	
		return $lineas;
	}
	
	
	// lista todos los cobros en vista buscar cobros
	public function listadoCobro()
	{
		$sql = "SELECT
				  c.`ci05_idcobroindividual` AS ci_idCobro,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
				
				  c.`ci05_fechacobro` AS ci_fechaCobro,
				
				  c.`ci05_glosa` AS ci_glosa,
				  IF(
				    r.`ci04_numerosociedad` = '0',
				    'PN',
				    r.`ci04_numerosociedad`
				  ) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT
				    cla.`ci11_previred`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci05_cobroindividual` ci
				      ON ru.`ci04_idrrut` = ci.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_previred` = '1'
				    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_claveprevired,
				  (SELECT
				    cla.`ci11_sii`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci05_cobroindividual` ci
				      ON ru.`ci04_idrrut` = ci.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_iva` = '1'
				    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_clavesii,
	IF(
				      ec.`ci53_idestadocobro` = '2',
				      IFNULL(
				        (SELECT 
				          `ci62_cuentaCteAngkor`.`ci62_nroCuenta` 
				        FROM
				          `ci08_compensacion` 
				          INNER JOIN `ci17_detallecartola` 
				            ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
				          INNER JOIN `ci16_cartola` 
				            ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
				          INNER JOIN `ci62_cuentaCteAngkor` 
				            ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
				        WHERE `ci08_compensacion`.`ci08_tipoCobro` = '1' 
				          AND `ci08_compensacion`.`ci08_idCobro` = c.`ci05_idcobroindividual`
				          GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro` 
				        ),
				        '-'
				      ),
				      '-'
				    ) AS `ci_numerocuenta`,
				  (
				    ROUND(
				      c.`ci05_monto` * c.`ci05_valoruf`,
				      0
				    )
				  ) AS ci_monto,
				  (SELECT
				    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido
				  FROM
				    `ci05_cobroindividual` ci
				    INNER JOIN `ci25_detalleindividualordenpago` dci
				      ON ci.`ci05_idcobroindividual` = dci.`ci05_idcobroindividual`
				    INNER JOIN `ci22_ordenpago` op
				      ON dci.`ci22_idordenpago` = op.`ci22_idordenpago`
				  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_dinerorecivido,
				  IF(
				    c.`ci05_monto` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`,
				  '-' AS ci_numerofactura,
				  c.`ci05_observacion` AS ci_observacion,
				  (SELECT
				    ci03_cliente.ci03_saldo
				  FROM
				    ci03_cliente
				  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente
				FROM
				  `ci05_cobroindividual` c
				  INNER JOIN `ci04_rut` r
				    ON c.`ci04_idrrut` = r.`ci04_idrrut`
				  INNER JOIN `ci36_estadocompensacion` eco
				    ON c.`ci36_idestadocomepnsacion` = eco.`ci36_idEstadoCompensacion`
				  INNER JOIN `ci03_cliente` cl
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente`
				  INNER JOIN `lc01_usuario` u
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario`
				  INNER JOIN `ci35_formapago` fp
				    ON c.`ci35_idformapago` = fp.`ci35_idformapago`
				  INNER JOIN `ci53_estadocobro` ec
				    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro`
				  INNER JOIN `ci33_conceptocobro` cc
				    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto`
				WHERE r.`ci04_estadodisponibilidad` = '1'
				  AND ec.`ci53_idestadocobro` BETWEEN '1'
				  AND '5'
				  AND eco.`ci36_idEstadoCompensacion` = '1'
				UNION
				SELECT
				  h.`ci06_idhonorario` AS ci_idCobro,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
				
				  
				  IF(
				    h.`ci33_idconcepto` = '4' 
				    OR h.`ci33_idconcepto` = '5',
				    (
				      DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y')
				    ),
				    h.`ci06_fechacobro`
				  ) AS ci_fechaCobro,
				  h.`ci06_glosa` AS ci_glosa,
				  IF(
				    r.`ci04_numerosociedad` = '0',
				    'PN',
				    r.`ci04_numerosociedad`
				  ) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT
				    cla.`ci11_previred`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci06_honorario` hon
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_previred` = '1'
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_claveprevired,
				  (SELECT
				    cla.`ci11_sii`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci06_honorario` hon
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_iva` = '1'
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_clavesii,
				  IF(
				    ec.`ci53_idestadocobro` = '2',
				    IFNULL(
				      (SELECT 
				        `ci62_cuentaCteAngkor`.`ci62_nroCuenta`
				      FROM
				        `ci08_compensacion` 
				        INNER JOIN `ci17_detallecartola` 
				          ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
				        INNER JOIN `ci16_cartola` 
				          ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
				        INNER JOIN `ci62_cuentaCteAngkor` 
				          ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
				      WHERE `ci08_compensacion`.`ci08_tipoCobro` = '2' 
				        AND `ci08_compensacion`.`ci08_idCobro` = h.`ci06_idhonorario`
				        GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro`),
				      '-'
				    ),
				    '-'
				  ) AS `ci_numerocuenta`,
				  (
				    ROUND(
				      h.`ci06_monto` * h.`ci06_valoruf`,
				      0
				    )
				  ) AS ci_monto,
				  (SELECT
				    SUM(op.`ci22_dinerorecibido`)
				  FROM
				    `ci06_honorario` ho
				    INNER JOIN `ci24_detallehonorarioordenpago` dho
				      ON ho.`ci06_idhonorario` = dho.`ci06_idhonorario`
				    INNER JOIN `ci22_ordenpago` op
				      ON dho.`ci22_idordenpago` = op.`ci22_idordenpago`
				  WHERE ho.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_dinerorecivido,
				  IF(
				    h.`ci06_monto` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`,
				  (SELECT
				    fac.`ci34_numerofcactura`
				  FROM
				    `ci06_honorario` hon
				    INNER JOIN `ci34_factura` fac
				      ON hon.`ci06_idhonorario` = fac.`ci06_idhonorario`
				  WHERE hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_numerofactura,
				  h.`ci06_observacion` AS ci_observacion,
				  (SELECT
				    ci03_cliente.ci03_saldo
				  FROM
				    ci03_cliente
				  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente
				FROM
				  `ci06_honorario` h
				  INNER JOIN `ci04_rut` r
				    ON h.`ci04_idrrut` = r.`ci04_idrrut`
				  INNER JOIN `ci36_estadocompensacion` eco
				    ON h.`ci36_idestadocomepnsacion` = eco.`ci36_idEstadoCompensacion`
				  INNER JOIN `ci03_cliente` cl
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente`
				  INNER JOIN `lc01_usuario` u
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario`
				  INNER JOIN `ci35_formapago` fp
				    ON h.`ci35_idformapago` = fp.`ci35_idformapago`
				  INNER JOIN `ci53_estadocobro` ec
				    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro`
				  INNER JOIN `ci33_conceptocobro` cc
				    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto`
				WHERE r.`ci04_estadodisponibilidad` = '1'
				  AND ec.`ci53_idestadocobro` BETWEEN '1'
				  AND '5'
				  AND eco.`ci36_idEstadoCompensacion` = '1'
				UNION
				SELECT
				  cm.`ci07_idcobromasivo` AS ci_idCobro,
				  'Masivo' AS ci33_tipo_ingreso,
				  cm.`ci07_fechapago` AS ci_fechaCobro,
				  cc.`ci33_nombre` AS ci_glosa,
				  IF(
				    r.`ci04_numerosociedad` = '0',
				    'PN',
				    r.`ci04_numerosociedad`
				  ) AS ci04_numerosociedad,
				  r.`ci04_rut`,
				  (SELECT
				    cla.`ci11_previred`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci07_cobromasivo` cma
				      ON ru.`ci04_idrrut` = cma.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_previred` = '1'
				    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_claveprevired,
				  (SELECT
				    cla.`ci11_sii`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci07_cobromasivo` cma
				      ON ru.`ci04_idrrut` = cma.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_iva` = '1'
				    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_clavesii,
				   IF(
				        ec.`ci53_idestadocobro` = '2',
				        IFNULL(
				          (SELECT 
				            `ci62_cuentaCteAngkor`.`ci62_nroCuenta` 
				          FROM
				            `ci08_compensacion` 
				            INNER JOIN `ci17_detallecartola` 
				              ON `ci08_compensacion`.`ci08_idMovimientoCartola` = `ci17_detallecartola`.`ci17_idDetalleCartola` 
				            INNER JOIN `ci16_cartola` 
				              ON `ci17_detallecartola`.`ci16_idcartola` = `ci16_cartola`.`ci16_idcartola` 
				            INNER JOIN `ci62_cuentaCteAngkor` 
				              ON `ci16_cartola`.`ci62_idCuenta` = `ci62_cuentaCteAngkor`.`ci62_idCuenta` 
				          WHERE `ci08_compensacion`.`ci08_tipoCobro` = '3' 
				            AND `ci08_compensacion`.`ci08_idCobro` = cm.`ci07_idcobromasivo`
				            GROUP BY ci62_nroCuenta, ci08_compensacion.`ci08_idCobro` 
				          ),
				          '-'
				        ),
				        '-'
				      ) AS `ci_numerocuenta`,
				  cm.`ci07_monto` AS ci_monto,
				  (SELECT
				    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido
				  FROM
				    `ci07_cobromasivo` cma
				    INNER JOIN `ci23_detallemasivoordenpago` dmp
				      ON cma.`ci07_idcobromasivo` = dmp.`ci07_idcobromasivo`
				    INNER JOIN `ci22_ordenpago` op
				      ON dmp.`ci22_idordenpago` = op.`ci22_idordenpago`
				  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_dinerorecivido,
				  IF(
				    cm.`ci07_monto` = 0,
				    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`,
				  '-' AS ci_numerofactura,
				  '' AS ci_observacion,
				  (SELECT
				    ci03_cliente.ci03_saldo
				  FROM
				    ci03_cliente
				  WHERE ci03_cliente.ci03_idcliente = r.ci03_idcliente) AS saldo_cliente
				FROM
				  `ci07_cobromasivo` cm
				  INNER JOIN `ci04_rut` r
				    ON cm.`ci04_idrrut` = r.`ci04_idrrut`
				  INNER JOIN `ci03_cliente` cl
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente`
				  INNER JOIN `lc01_usuario` u
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario`
				  INNER JOIN `ci35_formapago` fp
				    ON cm.`ci35_idformapago` = fp.`ci35_idformapago`
				  INNER JOIN `ci53_estadocobro` ec
				    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro`
				  INNER JOIN `ci33_conceptocobro` cc
				    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto`
				  INNER JOIN `ci36_estadocompensacion` ecom
				    ON cm.`ci36_idestadocomepnsacion` = ecom.`ci36_idEstadoCompensacion`
				WHERE r.`ci04_estadodisponibilidad` = '1'
				  AND ecom.`ci36_idEstadoCompensacion` = '1'
				  AND ec.`ci53_idestadocobro` BETWEEN '1'
				  AND '5';";
	
		$datos = mysql_query ( $sql );
	
		$listadoCobros = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
	
			$entry ['ci_claveprevired'] = $row ['ci_claveprevired'];
			$entry ['ci_clavesii'] = $row ['ci_clavesii'];
	
			$entry ['ci_numerocuenta'] = $row ['ci_numerocuenta'];
	
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_dinerorecivido'] = $row ['ci_dinerorecivido'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
	
			$entry ['ci_numerofactura'] = $row ['ci_numerofactura'];
	
			$entry ['ci_observacion'] = $row ['ci_observacion'];
			$entry ['saldo_cliente'] = $row ['saldo_cliente'];
	
			$listadoCobros [] = $entry;
		}
	
		return $listadoCobros;
	}
	
	public function buscarCobroHonorarios()
	{
		$sql = "SELECT
				  h.`ci06_idhonorario` AS ci_idCobro,
				  IF(
				    cc.`ci33_tipo_ingreso` = 1,
				    'Honorario',
				    'Cobro'
				  ) AS ci33_tipo_ingreso,
				
				  IF(
				    h.`ci33_idconcepto` = '4' 
				    OR h.`ci33_idconcepto` = '5',
				    (
				      DATE_FORMAT(STR_TO_DATE(h.`ci06_fechacobro`,'%d-%m-%Y'), '%m-%Y')
				    ),
				    h.`ci06_fechacobro`
				  ) AS ci_fechaCobro,
				
				 h.`ci06_glosa` AS ci_glosa,
	
				  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
	
				  r.`ci04_rut`,
				  (SELECT
				    cla.`ci11_previred`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci06_honorario` hon
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_previred` = '1'
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_claveprevired,
				  (SELECT
				    cla.`ci11_sii`
				  FROM
				    `ci04_rut` ru
				    INNER JOIN `ci11_clave` cla
				      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
				    INNER JOIN `ci06_honorario` hon
				      ON ru.`ci04_idrrut` = hon.`ci04_idrrut`
				  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
				    AND ru.`ci04_iva` = '1'
				    AND hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_clavesii,
				  '' AS `ci_numerocuenta`,
				  (
				    ROUND(
				      h.`ci06_monto` * h.`ci06_valoruf`,
				      0
				    )
				  ) AS ci_monto,
				  (SELECT
				    SUM(op.`ci22_dinerorecibido`)
				  FROM
				    `ci06_honorario` ho
				    INNER JOIN `ci24_detallehonorarioordenpago` dho
				      ON ho.`ci06_idhonorario` = dho.`ci06_idhonorario`
				    INNER JOIN `ci22_ordenpago` op
				      ON dho.`ci22_idordenpago` = op.`ci22_idordenpago`
				  WHERE ho.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_dinerorecivido,
				  IF(
				    h.`ci06_monto` = 0,
				    IF(
				      ec.`ci53_nombreestado` = 'verificado',
				      ec.`ci53_nombreestado`,
				      'S/M'
				    ),
				    ec.`ci53_nombreestado`
				  ) AS ci53_nombreestado,
				  fp.`ci35_tipopago`,
				  (SELECT
				    fac.`ci34_numerofcactura`
				  FROM
				    `ci06_honorario` hon
				    INNER JOIN `ci34_factura` fac
				      ON hon.`ci06_idhonorario` = fac.`ci06_idhonorario`
				  WHERE hon.`ci06_idhonorario` = h.`ci06_idhonorario`) AS ci_numerofactura,
				  h.`ci06_observacion` AS ci_observacion
				FROM
				  `ci06_honorario` h
				  INNER JOIN `ci04_rut` r
				    ON h.`ci04_idrrut` = r.`ci04_idrrut`
				  INNER JOIN `ci03_cliente` cl
				    ON cl.`ci03_idcliente` = r.`ci03_idcliente`
				  INNER JOIN `lc01_usuario` u
				    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario`
				  INNER JOIN `ci35_formapago` fp
				    ON h.`ci35_idformapago` = fp.`ci35_idformapago`
				  INNER JOIN `ci53_estadocobro` ec
				    ON h.`ci53_idestadocobro` = ec.`ci53_idestadocobro`
				  INNER JOIN `ci33_conceptocobro` cc
				    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto`
				WHERE r.`ci04_estadodisponibilidad` = '1'
				  AND ec.`ci53_idestadocobro` BETWEEN '1'
				  AND '5' ;";
	
		$datos = mysql_query ( $sql );
	
		$listadoCobros = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
				
			$entry ['ci_claveprevired'] = $row ['ci_claveprevired'];
			$entry ['ci_clavesii'] = $row ['ci_clavesii'];
				
			$entry ['ci_numerocuenta'] = $row ['ci_numerocuenta'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_dinerorecivido'] = $row ['ci_dinerorecivido'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
				
			$entry ['ci_numerofactura'] = $row ['ci_numerofactura'];
				
			$entry ['ci_observacion'] = $row ['ci_observacion'];
				
			$listadoCobros [] = $entry;
		}
	
		return $listadoCobros;
	}
	
	// filtro que es combo "tipo cobro" (en vista buscar cobros apartado de compensacion)
	public function buscarCobroIndividual()
	{
		$sql = "SELECT
			  c.`ci05_idcobroindividual` AS ci_idCobro,
			  IF(
			    cc.`ci33_tipo_ingreso` = 1,
			    'Honorario',
			    'Cobro'
			  ) AS ci33_tipo_ingreso,
			  c.`ci05_fechacobro` AS ci_fechaCobro,
			  c.`ci05_glosa` AS ci_glosa,
			 IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  (SELECT
			    cla.`ci11_previred`
			  FROM
			    `ci04_rut` ru
			    INNER JOIN `ci11_clave` cla
			      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
			    INNER JOIN `ci05_cobroindividual` ci
			      ON ru.`ci04_idrrut` = ci.`ci04_idrrut`
			  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
			    AND ru.`ci04_previred` = '1'
			    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_claveprevired,
			  (SELECT
			    cla.`ci11_sii`
			  FROM
			    `ci04_rut` ru
			    INNER JOIN `ci11_clave` cla
			      ON ru.`ci04_idrrut` = cla.`ci04_idrrut`
			    INNER JOIN `ci05_cobroindividual` ci
			      ON ru.`ci04_idrrut` = ci.`ci04_idrrut`
			  WHERE ru.`ci04_idrrut` = r.`ci04_idrrut`
			    AND ru.`ci04_iva` = '1'
			    AND ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_clavesii,
			  '' AS `ci_numerocuenta`,
			  (
			    ROUND(
			      c.`ci05_monto` * c.`ci05_valoruf`,
			      0
			    )
			  ) AS ci_monto,
			  (SELECT
			    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido
			  FROM
			    `ci05_cobroindividual` ci
			    INNER JOIN `ci25_detalleindividualordenpago` dci
			      ON ci.`ci05_idcobroindividual` = dci.`ci05_idcobroindividual`
			    INNER JOIN `ci22_ordenpago` op
			      ON dci.`ci22_idordenpago` = op.`ci22_idordenpago`
			  WHERE ci.`ci05_idcobroindividual` = c.`ci05_idcobroindividual`) AS ci_dinerorecivido,
			
			  IF(
			     c.`ci05_monto` = 0,
			    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
			    ec.`ci53_nombreestado`
			  ) AS ci53_nombreestado,
	
			  fp.`ci35_tipopago`,
			  '-' AS ci_numerofactura,
			  c.`ci05_observacion` AS ci_observacion
			FROM
			  `ci05_cobroindividual` c
			  INNER JOIN `ci04_rut` r
			    ON c.`ci04_idrrut` = r.`ci04_idrrut`
			  INNER JOIN `ci03_cliente` cl
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente`
			  INNER JOIN `lc01_usuario` u
			    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario`
			  INNER JOIN `ci35_formapago` fp
			    ON c.`ci35_idformapago` = fp.`ci35_idformapago`
			  INNER JOIN `ci53_estadocobro` ec
			    ON c.`ci53_idestadocobro` = ec.`ci53_idestadocobro`
			  INNER JOIN `ci33_conceptocobro` cc
			    ON c.`ci33_idconcepto` = cc.`ci33_idconcepto`
			WHERE r.`ci04_estadodisponibilidad` = '1'
			  AND ec.`ci53_idestadocobro` BETWEEN '1'
			  AND '5'
			UNION
				SELECT
			  cm.`ci07_idcobromasivo` AS ci_idCobro,
			  'Masivo' AS ci33_tipo_ingreso,
			  cm.`ci07_fechapago` AS ci_fechaCobro,
			  cc.`ci33_nombre` AS ci_glosa,
			  IF(r.`ci04_numerosociedad`=0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  (SELECT
			    cla.`ci11_previred`
			  FROM
			    `ci04_rut` r
			    INNER JOIN `ci11_clave` cla
			      ON r.`ci04_idrrut` = cla.`ci04_idrrut`
			    INNER JOIN `ci07_cobromasivo` cma
			      ON r.`ci04_idrrut` = cma.`ci04_idrrut`
			  WHERE r.`ci04_idrrut` = r.`ci04_idrrut`
			    AND r.`ci04_previred` = '1'
			    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_claveprevired,
			  (SELECT
			    cla.`ci11_sii`
			  FROM
			    `ci04_rut` r
			    INNER JOIN `ci11_clave` cla
			      ON r.`ci04_idrrut` = cla.`ci04_idrrut`
			    INNER JOIN `ci07_cobromasivo` cma
			      ON r.`ci04_idrrut` = cma.`ci04_idrrut`
			  WHERE r.`ci04_idrrut` = r.`ci04_idrrut`
			    AND r.`ci04_iva` = '1'
			    AND cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_clavesii,
			  '' AS `ci_numerocuenta`,
			  cm.`ci07_monto` AS ci_monto,
			  (SELECT
			    SUM(op.`ci22_dinerorecibido`) AS ci_dinerorecibido
			  FROM
			    `ci07_cobromasivo` cma
			    INNER JOIN `ci23_detallemasivoordenpago` dmp
			      ON cma.`ci07_idcobromasivo` = dmp.`ci07_idcobromasivo`
			    INNER JOIN `ci22_ordenpago` op
			      ON dmp.`ci22_idordenpago` = op.`ci22_idordenpago`
			  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_dinerorecivido,
			  IF(
			     cm.`ci07_monto` = 0,
			    CONCAT(ec.`ci53_nombreestado`, '(S/M)'),
			    ec.`ci53_nombreestado`
			  ) AS ci53_nombreestado,
		
			  fp.`ci35_tipopago`,
			  '-' AS ci_numerofactura,
			  '' AS ci_observacion
			FROM
			  `ci07_cobromasivo` cm
			  INNER JOIN `ci04_rut` r
			    ON cm.`ci04_idrrut` = r.`ci04_idrrut`
			  INNER JOIN `ci03_cliente` cl
			    ON cl.`ci03_idcliente` = r.`ci03_idcliente`
			  INNER JOIN `lc01_usuario` u
			    ON cl.`lc01_idUsuario` = u.`lc01_idUsuario`
			  INNER JOIN `ci35_formapago` fp
			    ON cm.`ci35_idformapago` = fp.`ci35_idformapago`
			  INNER JOIN `ci53_estadocobro` ec
			    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro`
			  INNER JOIN `ci33_conceptocobro` cc
			    ON cm.`ci33_idconcepto` = cc.`ci33_idconcepto`
			  INNER JOIN `ci36_estadocompensacion` ecom
			    ON cm.`ci36_idestadocomepnsacion` = ecom.`ci36_idEstadoCompensacion`
			WHERE r.`ci04_estadodisponibilidad` = '1'
			  AND ecom.`ci36_idEstadoCompensacion` = '1'
			  AND ec.`ci53_idestadocobro` BETWEEN '1'
			  AND '5'; ";
	
		$datos = mysql_query ( $sql );
	
		$listadoCobros = array ();
	
		while ( $row = mysql_fetch_array ( $datos ) ) {
			$entry ['ci_idCobro'] = $row ['ci_idCobro'];
			$entry ['ci33_tipo_ingreso'] = $row ['ci33_tipo_ingreso'];
			$entry ['ci_fechaCobro'] = $row ['ci_fechaCobro'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
				
			$entry ['ci_claveprevired'] = $row ['ci_claveprevired'];
			$entry ['ci_clavesii'] = $row ['ci_clavesii'];
			$entry ['ci_numerocuenta'] = $row ['ci_numerocuenta'];
				
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci_dinerorecivido'] = $row ['ci_dinerorecivido'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
				
			$entry ['ci_numerofactura'] = $row ['ci_numerofactura'];
				
			$entry ['ci_observacion'] = $row ['ci_observacion'];
				
			$listadoCobros [] = $entry;
		}
	
		return $listadoCobros;
	}
	
}



