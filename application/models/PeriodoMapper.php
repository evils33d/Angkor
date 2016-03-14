<?php

class Application_Model_PeriodoMapper
{
    protected $_dbTable;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable($tabla)
    {
        $this->setDbTable($tabla);
        return $this->_dbTable;
    }
    
    
    public function BuscarPeriodos()
    {

    	$entries = array();
    	$db = $this->getDbTable("Application_Model_DbTable_Periodo");
    	
        $select = $db->select();
   

        //$select = $select->where("PeriodoEstudio = ?",$periodo);
        
       
        
        $select = $select->order("PeriodoEstudio");
        
        $result = $db->fetchAll($select);
        
        //print $result;
        
       /* $entries = array();*/

        foreach ($result as $k => $row)
        {
        	
        	$entry["PeriodoEstudio"] = $row->PeriodoEstudio;        	

            $entries[] = $entry;   
        }
         
        return $entries;
    
    }
    
    public function GenerarMuestra($periodo="2014",$delegacion="1")
    {
    	$db = $this->getDbTable("Application_Model_DbTable_Periodo");
    	$spParams = array($periodo,$delegacion);
    	$stmt = $db->query("CALL GenerarMuestra(?, ?)", $spParams);
    	return $stmt;
    	//Fetches a row from the result set
    	/*$row = $stmt->fetch();
    	
    	//Returns an array containing all of the result set rows
    	$rows = $stmt->fetchAll();
    	
    	$stmt->closeCursor();*/
    	
    	
    }
}
