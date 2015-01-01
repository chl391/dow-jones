<?php

// include parent class
require_once 'Model.php';

class Author extends Model 
{
	// Set $table variable so that query knows what table to use
	protected $table = 'author';
	protected $displayName = "CONCAT(lastName, ', ', firstName) as displayName";
	
	private $id = 0;
	private $firstName = "";
	private $lastName = "";
	private $columns = "firstName, lastName";
	private $createValues = "";
	private $editValues = "";
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getFirstName()
	{
		return $this->firstName;
	}
	
	public function getLastName()
	{
		return $this->lastName;
	}
	
	public function getColumns()
	{
		return $this->columns;
	}
	
	public function getCreateValues()
	{
		return $this->createValues;
	}
	
	public function getEditValues()
	{
		return $this->editValues;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}
	
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}
	
	public function setCreateValues()
	{
		$this->createValues = "'{$this->getfirstName()}', '{$this->getlastName()}'";
	}
	
	public function setEditValues()
	{
		$this->editValues = "firstName = ".$this->getFirstName().", lastName = ".$this->getLastName();
	}
	
	public function create($author) 
	{
		return $this->datasource->create($author);
	}
	
	public function select()
	{
		$fields = array("id","firstName",'lastName');
		$where = null;
		$order = null;
		$joins = null;
		return $this->datasource->select($fields,$where,$order,$joins);
	}
	
	public function edit($author)
	{
		return $this->datasource->edit($author);
	}
	
	public function delete($id)
	{
		return $this->datasource->delete($id);
	}
	
	public function verify($column,$value)
	{
		$fields = array($column);
		$where = $column." = $value";
		$order = null;
		$joins = null;
		return $this->datasource->select($fields,$where,$order,$joins);
	}
}