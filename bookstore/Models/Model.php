<?php

/**
 * Included from specific model extensions
 * 
 * @author Charles Lin
 */
class Model 
{
	// Initiatlize $table as null in base model class
	// Variable in set when extension class calls this class
	protected $table = null;
	
	/**
	 * Includes file that has database connection credentials ($driver and $connection)
	 * Autoloads corresponding driver (MySQL.php)
	 * Creates a new instance of driver (MySQL.php) and stores in $datasource
	 */
	public function __construct() 
	{
		// Include file that connects to database
		include APP_ROOT . '/config/database.php';
		
		// Include driver which is in database.php
		// In this case, it is MySQL		
		__autoload('/Models/Drivers/' . $driver );
		
		// Store driver in variable $datasource
		// In this case, it sends the connection and table
		// $connection can be found in database.php
		// $table can be found in the extension class of Model
		$this->datasource = new $driver($connection, $this->table);
	}
	
	/**
	 * default create($data) function directs to $datasource->create($data)
	 * 
	 * @param String $data
	 */
	public function create ($data) 
	{
		$this->datasource->create($data);
	}
	 
	/**
	 * default listAll function directs to $datasource->listAll($this->displayName)
	 * listAll is not overridden by corresponding model extension
	 * $displayName is stored in corresponding model extension
	 */
	public function listAll() 
	{
		return $this->datasource->listAll($this->displayName);
	}
}