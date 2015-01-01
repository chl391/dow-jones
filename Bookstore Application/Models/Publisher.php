<?php

// include parent class
require_once 'Model.php';

class Publisher extends Model 
{
	// Set $table variable so that query knows what table to use
	protected $table = 'publisher';
	protected $displayName = 'name as displayName';
	
	private $id = 0;
	private $name = "";
	private $columns = "name";
	private $createValues = "";
	private $editValues = "";
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
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
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setCreateValues()
	{
		$this->createValues = "'{$this->getName()}'";
	}
	
	public function setEditValues()
	{
		$this->editValues = "name = ".$this->getName();
	}
	
	public function create($publisher)
	{
		return $this->datasource->create($publisher);
	}
	
	public function select()
	{
		$fields = array("id","name");
		$where = null;
		$order = null;
		$joins = null;
		return $this->datasource->select($fields,$where,$order,$joins);
	}
	
	public function edit($publisher)
	{
		return $this->datasource->edit($publisher);
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