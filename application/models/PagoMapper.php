<?php
class Application_Model_PagoMapper 
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
		
	public function ingresarOrdenPago($data) 
	{
	   $sql = "INSERT INTO `ci22_ordenpago` (
			  `ci58_idbanco`,
			  `ci22_numerocheque`,
			  `ci22_titular`,
			  `ci22_dinerorecibido`,
			  `ci22_verificado`,
			  `ci22_numerofolio`,
			  `ci22_fechaordenpago`
			) 
			VALUES
			  (
			    '" .$data ['banco']."',
			    '".$data['numeroCheque']."',
			    '".$data['titular']."',
			    '".$data['montoOrden']."',
			    '2',
			    '0',
			    DATE(NOW())
			  ) ;";
					
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
	
	public function ingresarOrdenDetalleHonorario($idCobro,$idOrdenPago)
	{
		$sql = "INSERT INTO 
				`ci24_detallehonorarioordenpago`
				(`ci06_idhonorario`,`ci22_idordenpago`)
				VALUES('".$idCobro."','".$idOrdenPago."')";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function ingresarOrdenDetalleMasivo($idCobro,$idOrdenPago)
	{
		$sql = "INSERT INTO
				`ci23_detallemasivoordenpago`
				(`ci07_idcobromasivo`,`ci22_idordenpago`)
				VALUES('".$idCobro."','".$idOrdenPago."')";
	
		$res = mysql_query( $sql );
	
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function ingresarOrdenDetalleIndividual($idCobro,$idOrdenPago)
	{
		$sql = "INSERT INTO 
				`ci25_detalleindividualordenpago` 
				(`ci05_idcobroindividual`,`ci22_idordenpago`)VALUES('".$idCobro."','".$idOrdenPago."');";
		
		$res = mysql_query( $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}
	}
	
	public function verificaOrdenPagoInidivdual($idCobro)
	{
		$sql="SELECT * FROM `ci25_detalleindividualordenpago` WHERE `ci05_idcobroindividual`='".$idCobro."';";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function verificaOrdenPagoHonorario($idCobro)
	{
		$sql="SELECT * FROM `ci24_detallehonorarioordenpago` WHERE `ci06_idhonorario`='".$idCobro."';";
	
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function verificaNumeroDeCheque($numeroCheque)
	{
		$sql="SELECT `ci22_numerocheque` FROM `ci22_ordenpago` WHERE `ci22_numerocheque`='".$numeroCheque."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function listadosOrdenesPago($idCliente,$idRut,$fechaInicio,$fechaFinal,$idUsuario)
	{		
		$where="";			
		
		if($idUsuario!='')
		{
			$where .=" WHERE c.`lc01_idUsuario`='".$idUsuario."'";
		}
		
		if($idCliente!='')
		{
			$where .=" AND c.`ci03_idcliente` = '".$idCliente."' ";
		}

		if($idRut!='')
		{
			$where .=" AND r.`ci04_idrrut` = '".$idRut."' ";
		}
		
		if($fechaInicio!='' && $fechaFinal!='')
		{				
			if($idUsuario!='')
			{
				$where .=" AND op.`ci22_fechaordenpago` BETWEEN '".$fechaInicio."' AND '".$fechaFinal."'";
			}
			else
			{
				$where .="WHERE op.`ci22_fechaordenpago` BETWEEN '".$fechaInicio."' AND '".$fechaFinal."'";
			}
		}
		
		$sql=" SELECT 
			  ci.`ci05_idcobroindividual` ci_idcobro,
			  'Cobro' AS ci_tipocobro,
			  op.`ci22_idordenpago`,
			  DATE_FORMAT(op.`ci22_fechaordenpago`,'%d-%m-%Y') AS ci22_fechaordenpago,
			  ci.`ci05_glosa` AS ci_glosa,
				
			  IF(r.`ci04_numerosociedad`= 0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
			  
			  r.`ci04_rut`,
			  (
			    ROUND(
			      ci.`ci05_monto` * ci.`ci05_valoruf`,
			      0
			    )
			  ) AS ci_monto,
			  op.`ci22_dinerorecibido`,
			  (SELECT 
			    op.`ci22_numerofolio` 
			  FROM
			    `ci05_cobroindividual` coi 
			    INNER JOIN `ci25_detalleindividualordenpago` dhi 
			      ON coi.`ci05_idcobroindividual` = dhi.`ci05_idcobroindividual` 
			    INNER JOIN `ci22_ordenpago` op 
			      ON dhi.`ci22_idordenpago` = op.`ci22_idordenpago` 
			  WHERE coi.`ci05_idcobroindividual` = ci.`ci05_idcobroindividual`) AS ci_numerofolio,
			  ec.`ci53_nombreestado`,
			  fp.`ci35_tipopago`,
			  ci.`ci05_observacion` ci_observacion 
			FROM
			  `ci03_cliente` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci05_cobroindividual` ci 
			    ON r.`ci04_idrrut` = ci.`ci04_idrrut` 
			  INNER JOIN `ci35_formapago` fp 
			    ON ci.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON ci.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci25_detalleindividualordenpago` dhi 
			    ON ci.`ci05_idcobroindividual` = dhi.`ci05_idcobroindividual` 
			  INNER JOIN `ci22_ordenpago` op 
			    ON dhi.`ci22_idordenpago` = op.`ci22_idordenpago` 
			 ".$where."
			 		
			  UNION 
			 		
			  	SELECT 
			  cm.`ci07_idcobromasivo` AS ci_idcobro,
			  'Masivo' AS ci_tipocobro,
			  op.`ci22_idordenpago`,
			  DATE_FORMAT(op.`ci22_fechaordenpago`,'%d-%m-%Y') AS ci22_fechaordenpago,
			  cc.`ci33_nombre` AS ci_glosa,
			  IF(r.`ci04_numerosociedad`= 0,'PN',r.`ci04_numerosociedad`) AS ci04_numerosociedad,
			  r.`ci04_rut`,
			  cm.`ci07_monto`,
			  op.`ci22_dinerorecibido`,
			  
			  (SELECT 
			    op.`ci22_numerofolio` 
			  FROM
			    `ci07_cobromasivo` cma
			    INNER JOIN `ci23_detallemasivoordenpago` dmp
			      ON cma.`ci07_idcobromasivo` = dmp.`ci07_idcobromasivo`
			    INNER JOIN `ci22_ordenpago` op 
			      ON dmp.`ci22_idordenpago` = op.`ci22_idordenpago` 
			  WHERE cma.`ci07_idcobromasivo` = cm.`ci07_idcobromasivo`) AS ci_numerofolio,
			  
			  ec.`ci53_nombreestado`,
			  fp.`ci35_tipopago`,
			  '' ci_observacion 
			  
			FROM
			  `ci03_cliente` c 
			  INNER JOIN `ci04_rut` r 
			    ON c.`ci03_idcliente` = r.`ci03_idcliente` 
			  INNER JOIN `ci07_cobromasivo` cm 
			    ON r.`ci04_idrrut` = cm.`ci04_idrrut` 
			  INNER JOIN `ci35_formapago` fp 
			    ON cm.`ci35_idformapago` = fp.`ci35_idformapago` 
			  INNER JOIN `ci53_estadocobro` ec 
			    ON cm.`ci53_idestadocobro` = ec.`ci53_idestadocobro` 
			  INNER JOIN `ci23_detallemasivoordenpago` dmp 
			    ON cm.`ci07_idcobromasivo` = dmp.`ci07_idcobromasivo`
			  INNER JOIN `ci22_ordenpago` op 
			    ON dmp.`ci22_idordenpago` = op.`ci22_idordenpago` 
			   INNER JOIN `ci33_conceptocobro` cc ON cm.`ci33_idconcepto`=cc.`ci33_idconcepto`
			".$where.";";
		
		$datos = mysql_query ( $sql );
		
		$listadoOrdenesPago = array ();
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci_idcobro'] = $row ['ci_idcobro'];
			$entry ['ci_tipocobro'] = $row ['ci_tipocobro'];
			$entry ['ci22_idordenpago'] = $row ['ci22_idordenpago']; 
			$entry ['ci22_fechaordenpago'] = $row ['ci22_fechaordenpago'];
			$entry ['ci_glosa'] = $row ['ci_glosa'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci_monto'] = $row ['ci_monto'];
			$entry ['ci22_dinerorecibido'] = $row ['ci22_dinerorecibido'];
			$entry ['ci_numerofolio'] = $row ['ci_numerofolio'];
			$entry ['ci53_nombreestado'] = $row ['ci53_nombreestado'];
			$entry ['ci35_tipopago'] = $row ['ci35_tipopago'];
			$entry ['ci_observacion'] = $row ['ci_observacion'];
		
			$listadoOrdenesPago [] = $entry;
		}
		
		return $listadoOrdenesPago;
	}

	public function registraFolio($folio,$idOrdenPago)
	{
		$sql="UPDATE `ci22_ordenpago` SET `ci22_verificado` ='1' , `ci22_numerofolio`='".$folio."' WHERE `ci22_idordenpago` ='".$idOrdenPago."';";
	
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

	public function registraFolioPec($folio)
	{
		$sql="INSERT INTO `ci54_foliopec` (`ci54_numerofolio`) values('".$folio."');";
	
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
	
	public function registraDetalleFolioPecMasivo($idFolio,$idCobro)
	{
		$sql="INSERT INTO `ci56_detallefoliomasivo` (`ci54_idfoliopec`,`ci07_idcobromasivo`) VALUES('".$idFolio."','".$idCobro."');";
		
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
	
	public function registraDetalleFolioPecInidividual($idFolio,$idCobro)
	{
		$sql="INSERT INTO `ci55_detallefolioinidividual`(`ci54_idfoliopec`,`ci05_idcobroindividual`)VALUES('".$idFolio."','".$idCobro."');";
		
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
	
	public function verificarNumeroFolioPec($folio)
	{
		$sql="SELECT * FROM `ci54_foliopec` WHERE `ci54_numerofolio`='".$folio."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function obtieneDatosOrdenPago($idCobro, $tipoCobro)
	{		
		if($tipoCobro=='1')
		{
			$sql="SELECT
				  op.`ci58_idbanco`,
				  op.`ci22_titular`,
				  op.`ci22_numerocheque`,
				  op.`ci22_idordenpago`,					
  				  op.`ci22_dinerorecibido`
				FROM
				  `ci22_ordenpago` op
				  INNER JOIN `ci24_detallehonorarioordenpago` dhp
				    ON op.`ci22_idordenpago` = dhp.`ci22_idordenpago`
				  INNER JOIN `ci06_honorario` h
				    ON dhp.`ci06_idhonorario` = h.`ci06_idhonorario`
				  WHERE h.`ci06_idhonorario`='".$idCobro."';";
		}
		else 
		{
			$sql="SELECT
				  op.`ci58_idbanco`,
				  op.`ci22_titular`,
				  op.`ci22_numerocheque`,
				  op.`ci22_idordenpago`,					
  				  op.`ci22_dinerorecibido`
				FROM
				  `ci22_ordenpago` op
				  INNER JOIN `ci25_detalleindividualordenpago` dip
				    ON op.`ci22_idordenpago` = dip.`ci22_idordenpago`
				  INNER JOIN `ci05_cobroindividual` ci
				    ON dip.`ci05_idcobroindividual` = ci.`ci05_idcobroindividual`
				WHERE ci.`ci05_idcobroindividual` = '".$idCobro."';";
		}	
		
		$ordenesPago = array ();
		
		$datos = mysql_query ( $sql );
		
		while ( $row = mysql_fetch_array ( $datos ) )
		{
			$entry ['ci58_idbanco'] = $row ['ci58_idbanco'];
			$entry ['ci22_titular'] = $row ['ci22_titular'];
			$entry ['ci22_numerocheque'] = $row ['ci22_numerocheque'];	
			$entry ['ci22_idordenpago'] = $row ['ci22_idordenpago'];
			$entry ['ci22_dinerorecibido'] = $row ['ci22_dinerorecibido'];
			
			$ordenesPago[]=$entry;
		}
		
		return $ordenesPago;
		
	}
	
	


	

}
