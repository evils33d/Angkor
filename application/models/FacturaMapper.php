<?php
class Application_Model_FacturaMapper {
	
	protected $_dbTable;
	protected $_link;
	protected $_host;
	protected $_username;
	protected $_password;
	protected $_dbname;
	
	public function __construct() 
	{
		
		$bootstrap = Zend_Controller_Front::getInstance ()->getParam ( 'bootstrap' );
		$options = $bootstrap->getOptions ();
		
		$this->_host = $options ['resources'] ['db'] ['params'] ['host'];
		$this->_username = $options ['resources'] ['db'] ['params'] ['username'];
		$this->_password = $options ['resources'] ['db'] ['params'] ['password'];
		$this->_dbname = $options ['resources'] ['db'] ['params'] ['dbname'];
		
		//$link = mysql_connect ( $host, $username, $password ) or die ( mysql_error () );
		//mysql_select_db ( $dbname, $link ) or die ( mysql_error () );
		
		$this->_link = mysqli_connect($this->_host,$this->_username,$this->_password, $this->_dbname) or die ( mysqli_error($this->_link));
		
		$sql1 = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
		
		mysql_query($sql1);
	}
			
	public function registrarNumeroFactura($numero,$data)
	{		
		$sql="";
		
		foreach ($data as $i => $datos):
		
			$sql.="INSERT INTO `ci34_factura` (
				  `ci06_idhonorario`,
				  `ci34_numerofcactura`
				)
				VALUES
				  ('".$datos['id']."',
				   '".$numero."') ;";
			
		endforeach;		
		
		$res=mysqli_multi_query ($this->_link, $sql );
		
		if($res)
		{
			return  true;
		}
		else 
		{
			return false;
		}		
	}
	
	public function verificaNumeroFactura($numeroFactura)
	{
		$sql="SELECT 
			   * 
			FROM
			  `ci34_factura` 
			WHERE `ci34_numerofcactura` = '".$numeroFactura."';";
			
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public function verificaIdCobro($data)
	{		
		$id="";		
		
		foreach ($data as $i => $datos):
		
			$id=$datos['id'];
		
		endforeach;
		
		$sql="SELECT
	    		  `ci34_idfactura`
			   FROM
				  `ci34_factura`
			   WHERE `ci06_idhonorario` = '".$id."';";
		
		if(mysql_num_rows(mysql_query($sql))>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function modificaNumeroFactura($numero,$data)
	{		
		
		$id="";
		
		foreach ($data as $i => $datos):
		
		$id=$datos['id'];
		
		endforeach;
		
		
		$sql="UPDATE 
			  `ci34_factura` 
			 SET
			  `ci34_numerofcactura` = '".$numero."' 
			 WHERE `ci06_idhonorario` = '".$id."';";	
		
		$res=mysql_query($sql);
		
		if($res)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
