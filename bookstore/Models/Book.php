<?php
require_once 'Model.php';

/**
 * Included and instantiated from corresponding controller extension
 * Contains all methods necessary for book
 * 
 * @author Charles Lin
 */
class Book extends Model 
{
	protected $table = 'book';
	
	private $id = 0;
	private $title = "";
	private $authorId = 0;
	private $price = 0;
	private $ISBN = 0;
	private $publisherId = 0;
	private $publishedDate = "";
	private $columns = "title, author_id, price, ISBN, publisher_id, published_date";
	private $createValues = "";
	private $editValues = "";
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getAuthorId()
	{
		return $this->authorId;
	}
	
	public function getPrice()
	{
		return $this->price;
	}
	
	public function getISBN()
	{
		return $this->ISBN;
	}
	
	public function getPublisherId()
	{
		return $this->publisherId;
	}
	
	public function getPublishedDate()
	{
		return $this->publishedDate;
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
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function setAuthorId($authorId)
	{
		$this->authorId = $authorId;
	}
	
	public function setPrice($price)
	{
		$this->price = $price;
	}
	
	public function setISBN($ISBN)
	{
		$this->ISBN = $ISBN;
	}
	
	public function setPublisherId($publisherId)
	{
		$this->publisherId = $publisherId;
	}
	
	public function setPublishedDate($publishedDate)
	{
		$this->publishedDate = $publishedDate;
	}
	
	public function setCreateValues()
	{
		$this->createValues = "'{$this->getTitle()}', {$this->getAuthorId()}, {$this->getPrice()}, {$this->getISBN()}, {$this->getpublisherId()}, '{$this->getPublishedDate()}'";
	}
	
	public function setEditValues()
	{
		$this->editValues = "title = ".$this->getTitle().", author_id = ".$this->getAuthorId(). ", price = ".$this->getPrice(). ", ISBN = ".$this->getISBN().", publisher_id = ".$this->getPublisherId().", published_date = ".$this->getPublishedDate();
	}
	
	public function create($book) 
	{
		return $this->datasource->create($book);
	}
	
	public function search()
	{
		return $this->datasource->search();
	}
	
	public function select($where)
	{
		$fields = array("id","title","author_id","published_date","publisher_id","price","ISBN");
		$order = null;
		$joins = array('author' => array(
				'fields' => array("id","firstName","lastName"),
				'on' => 'book.author_id = author.id',
				'alias' => null));
		$joins['publisher'] = array(
				'fields' => array("id","name"),
				'on' => 'book.publisher_id = publisher.id',
				'alias' => null);
		return $this->datasource->select($fields,$where,$order,$joins);
	}
	
	public function listAll()
	{
		$result = $this->datasource->search();
		return $result;
	}
	
	public function edit($book)
	{
		return $this->datasource->edit($book);
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