<?php

class Application_Model_ImpuestoMapper
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
	
	public function busquedaImpuestoUnico($data)
	{		
		$filtro="";
		
		if($data['idEjecutivo']!='')
		{
			$filtro.=" AND `lc01_usuario`.`lc01_idUsuario`='".$data['idEjecutivo']."' ";
		}
		
		if($data['idCliente']!='')
		{
			$filtro.=" AND `ci03_cliente`.`ci03_idcliente`='".$data['idCliente']."' ";
		}
		
		if($data['idRut']!='')
		{
			$filtro.=" AND `ci04_rut`.`ci04_idrrut`='".$data['idRut']."' ";
		}
		
		if($data['fecha']!='')
		{
			$filtro.=" AND `ci64_impuestounico`.`ci64_fecharegistro`='".$data['fecha']."' ";
		}
		
		$sql="SELECT		      
		      `ci04_rut`.`ci04_idrrut`,
			  `ci64_impuestounico`.`ci64_idimpuestounico`,
			  `ci03_cliente`.`ci03_nombre`,
			  `ci04_rut`.`ci04_rut`,
			  `ci04_rut`.`ci04_numerosociedad`,
			  `ci04_rut`.`ci04_razonsocial`,
			  `ci40_sociedad`.`ci40_tiposociedad`,
			  `ci64_impuestounico`.`ci64_valorimpuesto`,
			  `ci64_impuestounico`.`ci64_fecharegistro` 
			FROM
			  `ci03_cliente` 
			  INNER JOIN `ci04_rut` 
			    ON `ci03_cliente`.`ci03_idcliente` = `ci04_rut`.`ci03_idcliente` 
			  INNER JOIN `ci64_impuestounico` 
			    ON `ci04_rut`.`ci04_idrrut` = `ci64_impuestounico`.`ci04_idrut` 
			  INNER JOIN `ci40_sociedad` 
			    ON `ci04_rut`.`ci40_idsociedad` = `ci40_sociedad`.`ci40_idsociedad` 
			  INNER JOIN `lc01_usuario` 
    			ON `ci03_cliente`.`lc01_idUsuario` = `lc01_usuario`.`lc01_idUsuario` 
			WHERE `ci04_rut`.`ci04_f29`='1' $filtro ORDER BY `ci64_impuestounico`.`ci64_idimpuestounico`;";
		
		$res=mysql_query($sql);
		
		$dataImpuesto=array();
		
		while ( $row = mysql_fetch_array ( $res ) )
		{
			$entry ['ci64_idimpuestounico'] = $row ['ci64_idimpuestounico'];
			$entry ['ci03_nombre'] = $row ['ci03_nombre'];
			$entry ['ci04_rut'] = $row ['ci04_rut'];
			$entry ['ci04_numerosociedad'] = $row ['ci04_numerosociedad'];
			$entry ['ci04_razonsocial'] = $row ['ci04_razonsocial'];
			$entry ['ci40_tiposociedad'] = $row ['ci40_tiposociedad'];
			$entry ['ci64_valorimpuesto'] = $row ['ci64_valorimpuesto'];
			$entry ['ci64_fecharegistro'] = $row ['ci64_fecharegistro'];
			$entry ['ci04_idrrut'] = $row ['ci04_idrrut'];
	
			$dataImpuesto [] = $entry;
		}	
		
		return $dataImpuesto;
	}
    
	public function verificaImpuestoIngresadoCobroMasivo($idRut,$fecha)
	{
		$sql="SELECT 
			  `ci07_impuestounico` 
			FROM
			  `ci07_cobromasivo` 
			WHERE `ci04_idrrut` = '".$idRut."' 
			  AND `ci07_fechapago` = '".$fecha."' 
			  AND `ci33_idconcepto`='1';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function modificaImpuestoUnico($data)
	{
		$sql="";		
		
		foreach ($data as $i => $impuesto):		
		
			$sql.="UPDATE 
					  `ci64_impuestounico` 
				   SET
					  `ci64_valorimpuesto` = '".$impuesto['valorImpuesto']."' 
				   WHERE `ci64_idimpuestounico` = '".$impuesto['idImpuesto']."';";
		
		endforeach;
				
		$res = mysqli_multi_query ($this->_link, $sql );
		
		if ($res) {
			return true;
		} else {
			return false;
		}		
	}
}
