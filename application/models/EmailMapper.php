<?php

class Application_Model_EmailMapper
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
        
	public function ingresarEmail($data)
	{		
		$sqlBitacora = "INSERT INTO 
						`ci47_bitacoracorreo` 
						(
							`lc01_idusuario`,
							`ci47_emailenvio`,
							`ci47_asunto`,
							`ci47_fechaenvio`
						)
						VALUES(
						'".$data['idUsuario']."',
						'".$data['textoCuerpo']."',
						'".$data['asunto']."',
						DATE(NOW()))";		
	
		$res = mysql_query( $sqlBitacora );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public function listadoEmailCobro($idCliente) {
	
		  $sql = "SELECT 
				  b.`ci47_idbitacora` AS id_bitacora,
				  r.`ci04_rut`,
				  'Renta' AS ci33_nombre,
				  DATE_FORMAT(b.`ci47_fechaenvio`,'%d-%m-%Y') AS ci_fechaEnvio,
				  (SELECT 
				    `lc01_nombreUsuario` 
				  FROM
				    `lc01_usuario` 
				  WHERE `lc01_idUsuario` = b.`lc01_idusuario`) AS ci_nombreUsuario,
				  b.`ci47_emailenvio`,
				  b.`ci47_asunto` 
				FROM
				  `ci03_cliente` c 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
				  INNER JOIN `ci57_detalleemailrenta` der 
				    ON r.`ci04_idrrut` = der.`ci04_idrrut` 
				  INNER JOIN `ci47_bitacoracorreo` b 
				    ON der.`ci47_idbitacora` = b.`ci47_idbitacora` 
				WHERE c.`ci03_idcliente` = '".$idCliente."'  
				UNION
				SELECT 
				  b.`ci47_idbitacora` AS id_bitacora,
				  r.`ci04_rut`,
				  cc.`ci33_nombre`,
				  DATE_FORMAT(b.`ci47_fechaenvio`,'%d-%m-%Y') AS ci_fechaEnvio,
				  (SELECT 
				    `lc01_nombreUsuario` 
				  FROM
				    `lc01_usuario` 
				  WHERE `lc01_idUsuario` = b.`lc01_idusuario`) AS ci_nombreUsuario,
				  b.`ci47_emailenvio`,
				  b.`ci47_asunto` 
				FROM
				  `ci47_bitacoracorreo` b 
				  INNER JOIN `ci50_detallecorreohonorario` dh 
				    ON b.`ci47_idbitacora` = dh.`ci47_idbitacora` 
				  INNER JOIN `ci06_honorario` h 
				    ON dh.`ci06_idhonorario` = h.`ci06_idhonorario` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON h.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				  INNER JOIN `ci04_rut` r 
				    ON h.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cli 
				    ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
				WHERE cli.`ci03_idcliente` ='".$idCliente."' 
				UNION
				SELECT 
				  b.`ci47_idbitacora` AS id_bitacora,
				  r.`ci04_rut`,
				  cc.`ci33_nombre`,
				  DATE_FORMAT(b.`ci47_fechaenvio`,'%d-%m-%Y') AS ci_fechaEnvio,
				  (SELECT 
				    `lc01_nombreUsuario` 
				  FROM
				    `lc01_usuario` 
				  WHERE `lc01_idUsuario` = b.`lc01_idusuario`) AS ci_nombreUsuario,
				  b.`ci47_emailenvio`,
				  b.`ci47_asunto` 
				FROM
				  `ci47_bitacoracorreo` b 
				  INNER JOIN `ci51_detallecorreoindividual` di 
				    ON b.`ci47_idbitacora` = di.`ci47_idbitacora` 
				  INNER JOIN `ci05_cobroindividual` ci 
				    ON di.`ci05_idcobroindividual` = ci.`ci05_idcobroindividual` 
				  INNER JOIN `ci33_conceptocobro` cc 
				    ON ci.`ci33_idconcepto` = cc.`ci33_idconcepto` 
				  INNER JOIN `ci04_rut` r 
				    ON ci.`ci04_idrrut` = r.`ci04_idrrut` 
				  INNER JOIN `ci03_cliente` cli 
				    ON r.`ci03_idcliente` = cli.`ci03_idcliente` 
				WHERE cli.`ci03_idcliente` = '".$idCliente."' 
				UNION
				SELECT 
				  b.`ci47_idbitacora` AS id_bitacora,
				  r.`ci04_rut`,
				  dcm.`ci47_descripcion`,
				  DATE_FORMAT(b.`ci47_fechaenvio`,'%d-%m-%Y') AS ci_fechaEnvio,
				  (SELECT 
				    `lc01_nombreUsuario` 
				  FROM
				    `lc01_usuario` 
				  WHERE `lc01_idUsuario` = b.`lc01_idusuario`) AS ci_nombreUsuario,
				  b.`ci47_emailenvio`,
				  b.`ci47_asunto` 
				FROM
				  `ci47_bitacoracorreo` b 
				  INNER JOIN `ci49_detallecorreomasivo` dcm 
				    ON b.`ci47_idbitacora` = dcm.`ci47_idbitacora` 
				  INNER JOIN `ci03_cliente` c 
				    ON dcm.`ci03_idcliente` = c.`ci03_idcliente` 
				  INNER JOIN `ci04_rut` r 
				    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
				WHERE c.`ci03_idcliente` = '".$idCliente."' 
				ORDER BY id_bitacora DESC;";
	
		$listadoEmail = mysql_query ( $sql );
		
		$listadoEmailCobro = array ();
		
		if($listadoEmail){	
			
			while ( $row = mysql_fetch_array ( $listadoEmail ) ) {
			
				$entry ['ci04_rut'] = $row ['ci04_rut'];
				$entry ['ci33_nombre'] = $row ['ci33_nombre'];
				$entry ['ci47_fechaenvio'] = $row ['ci_fechaEnvio'];
				$entry ['ci_nombreUsuario'] = $row ['ci_nombreUsuario'];
				$entry ['ci47_emailenvio'] = $row ['ci47_emailenvio'];
				$entry ['ci47_asunto'] = $row ['ci47_asunto'];
			
				$listadoEmailCobro [] = $entry;
			}
			
		}	
	
		return $listadoEmailCobro;
	}

	public function registraDetalleEmailHonorario($idCobroHonorario,$idBitacora)
	{
		$sql="INSERT INTO `ci50_detallecorreohonorario`(`ci06_idhonorario`,`ci47_idbitacora`) VALUES('".$idCobroHonorario."','".$idBitacora."');";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	
	}
	
	public function registraDetalleEmailInidividual($idCobroIndividual,$idBitacora)
	{
		$sql="INSERT INTO `ci51_detallecorreoindividual`(`ci05_idcobroindividual`,`ci47_idbitacora`)VALUES('".$idCobroIndividual."','".$idBitacora."');";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	
	}

	public function registraDetalleEmailRenta($idRut,$idBitacora)
	{
		$sql="INSERT INTO `ci57_detalleemailrenta` (
				  `ci47_idbitacora`,
				  `ci04_idrrut`
				) 
				VALUES
				  ('".$idBitacora."',
				   '".$idRut."');";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}

	public function registraDetalleEmailMasivo($idCliente,$idBitacora,$descripcion,$nombre)
	{
		$sql="INSERT INTO `ci49_detallecorreomasivo` (
			  `ci03_idcliente`,
			  `ci47_idbitacora`,
			  `ci47_descripcion`,
			  `ci47_nombrepdf`
			) 
			VALUES
			  ('.$idCliente.',
			   '.$idBitacora.',
			   '$descripcion',
			   '$nombre');";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
}
