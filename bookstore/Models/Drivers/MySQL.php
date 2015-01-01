<?php

/**
 * Included and instantiated from Model.php
 * 
 * @author Charles Lin
 */

class MySQL extends PDO 
{
	private $dbh = null;

	/**
	 * Instantiated from model.php
	 * 
	 * @param array $connectionParameters
	 * @param String $table
	 */
	public function __construct($connectionParameters, $table)
	{
		parent::__construct(
				"mysql:host={$connectionParameters['host']};dbname={$connectionParameters['schema']}",
				$connectionParameters['username'],
				$connectionParameters['password'],
				$options = array(
	    			parent::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',)
		);
		$this->table = $table;
	}
	
	/**
	 * $data is $_POST from corresponding specific controller
	 * $data['submit'] is unset because that parameter is not needed for INSERT statement
	 * Creates new entry in $this-> table via prepared statements
	 * 
	 * @param string $data
	 * @return boolean
	 */
	public function create($object) 
	{
		
		try 
		{
			/*
			$defineOff = "set define off";
			$defineOff = $this->prepare($defineOff);
			$defineOff->execute();
			*/
			
			$sql = "insert into {$this->table} ({$object->getColumns()}) values ({$object->getCreateValues()}) ";
			echo $sql;
			$stmnt = $this->prepare($sql);
			
			$stmnt->execute();
			
			// If errorCode() is '00000', then everything is working fine
			if ($stmnt->errorCode() == '00000') 
			{
				return true;
			} 
			// Else post error message
			else 
			{
				var_dump($stmnt->errorInfo());
				return false;
			}
		} 
		catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}
	
	/**
	 * Helper method for listAll()
	 * Searches table and returns associative array
	 * 
	 * @param array $fields
	 * @param array $conditions
	 * @param array $order
	 * @param array $joins
	 */
	public function search($fields = null, $conditions = null, $order = null, $joins = null)
	{
		try
		{
			if ($fields === null) 
			{
				$fields = '*';
			}
			$sql = "SELECT $fields FROM $this->table";
			$stmt = $this->query($sql);
			return ($stmt->fetchAll(PDO::FETCH_ASSOC));
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}
	}


	/**
	 * Search method, which aliases fields and table names
	 * 
	 * @param array $fields
	 * @param array $where
	 * @param array $order
	 * @param array $joins
	 */
	public function select($fields = null, $where = null, $order = null, $joins = null) 
	{
		$fields = $this->aliasFields($this->table,$fields);
		$join = "";
		if ($joins !== null)
		{
			foreach ($joins as $table => $options) 
			{
				$currentFields = $this->aliasFields ($table, $options['fields'], $options['alias']);
				
				foreach ($currentFields as $key => $value)
				{
					$fields[] = $value;
				}
	
				if (!isset($options['alias'])) 
				{
					$options['alias'] = $table;
				}
				$join = $join . ' JOIN ' . $table . ' as ' . $options['alias'] . ' ON ' . $options['on'];
				if (isset($options['where'])) 
				{
					$where .= ' ' . $options['where'];
				}
			}
		}
		
		if ($where != "")
		{
			$sql = 'SELECT ' . implode(',', $fields) . " FROM {$this->table} $join WHERE $where";
		}
		else
		{
			$sql = 'SELECT ' . implode(',', $fields) . " FROM {$this->table} $join";
		}
		
		//echo $sql; 
		
		$stmt = $this->query($sql);
			
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			
		$dataset = array();
		foreach ($result as $entry => $columns)
		{
			$row = array();
			foreach ($columns as $key => $value)
			{
				$tmp = explode ('__', $key);
				$tableName = $tmp[0];
				$fieldName = $tmp[1];
				$row[$tableName][$fieldName] = $value;
			}
			$dataset[] = $row;
		}
		
		return $dataset;
	}

	/**
	 * Helper method that creates field part of the query with alias names
	 * 
	 * @param array $table
	 * @param array $fields
	 * @param array $alias
	 */
	protected function aliasFields ($table, $fields, $alias = null) 
	{
		if ($fields === null || (is_array($fields) && count($fields) == 0) 
				|| (is_array($fields) && count($fields) == 1 && $fields[0] == '*'))
		{
			$fields = $this->getTableFieldNames($table);
		}
		
		if ($alias === null)
		{
			$alias = $table;
		}
		
		foreach ($fields as $key => $f) 
		{
			$fields[$key] = $table . '.' . trim($f) . ' as ' . $alias . '__' . trim($f);
		}
		
		return $fields;
	}

	/**
	 * Helper method that returns column names of table in array
	 * 
	 * @param string $tableName
	 * @return array
	 */
	protected function getTableFieldNames($tableName) 
	{
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$tableName'";
		$stmt = $this->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $position => $array)
		{
			$fields[] = $array['COLUMN_NAME'];
		}
		return $fields;
	}
	
	/**
	 * Get all entries as associative array where id is the key
	 * and the needed 'id' => 'displayName'
	 * 
	 * @param string $displayName
	 */
	public function listAll($displayName) 
	{
		$list = array();
		$result = $this->search("id, $displayName");
		//print_r($result);
		if (is_array($result) && count($result) > 0) 
		{
			foreach($result as $row) 
			{
				$list[$row['id']] = $row['displayName'] ;
			}
		}
		//print_r($list);
		return $list;
	}
	
	public function delete($id)
	{
		try
		{
			$sql = "DELETE FROM $this->table WHERE id = $id";
			$stmnt = $this->prepare($sql);
			$stmnt->execute();
			
			// If errorCode() is '00000', then everything is working fine
			if ($stmnt->errorCode() == '00000')
			{
				return true;
			}
			// Else post error message
			else
			{
				var_dump($stmnt->errorInfo());
				return false;
			}
			
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function edit($object)
	{
		try
		{		
			$defineOff = "set define off";
			$defineOff = $this->prepare($defineOff);
			$defineOff->execute();
			
			$sql = "UPDATE {$this->table} SET ";
			$sql .= $object->getEditValues();
			$sql .= " WHERE id = ".$object->getId();

			print($sql);
			
			$stmnt = $this->prepare($sql);
			$stmnt->execute();
				
			// If errorCode() is '00000', then everything is working fine
			if ($stmnt->errorCode() == '00000')
			{
				return true;
			}
			// Else post error message
			else
			{
				var_dump($stmnt->errorInfo());
				return false;
			}
				
		}
		catch (Exception $e)
		{
			die($e->getMessage());
		}
	}
}