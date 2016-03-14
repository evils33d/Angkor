<?php
// application/models/UserMapper.php
class Application_Model_UserMapper
{
	protected $_dbTable;

	protected function _hydrate($row)
	{
		$user = new Application_Model_User();
		$user->setId($row->id)
		->setEmail($row->email)
		->setPassword($row->password)
		->setRole($row->role);

		return $user;
	}

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

	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_User');
		}

		return $this->_dbTable;
	}

	public function save(Application_Model_User $user)
	{
		$data = array(
				'email'       => $user->getEmail(),
				'password'    => $user->getPassword(),
				'role'        => $user->getRole()
		);

		if (null === ($id = $user->getId())) {
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('ap02_idUsuario = ?' => $id));
		}
	}

	public function find($id)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();

		return $this->_hydrate($row);
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entries[] = $this->_hydrate($row);
		}

		return $entries;
	}
}