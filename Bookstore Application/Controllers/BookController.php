<?php

/**
 * BookController class is included from index.php
 * Controller classes "control" model and view
 * 
 * @author Charles Lin
 */  			
class BookController extends Controller
{	
	/**
	 * BookController constructor creates instance of Controller,
	 * which autoloads View.php and creates a new instance of View
	 * 
	 * Also autoloads Book, Author, and Publisher models
	 * and creates a new instance of each
	 * 
	 * Since it is necessary to have all of authors and publishers listed
	 * in order to add a book, create a variable in $this->View that
	 * corresponds to the list of all of each in order to easily load in dropdown
	 */
	public function __construct()
	{
		parent::__construct();
	
		__autoload('/Models/Book');
		__autoload('/Models/Author');
		__autoload('/Models/Publisher');
		
		$this->Book = new Book();
		$this->Author = new Author();
		$this->Publisher = new Publisher();
		
		$this->View->author = $this->Author->listAll();
		$this->View->publisher = $this->Publisher->listAll();	
	}

	/**
	 * Since book's index lists all of the books and authors,
	 * create a variable in $this->View named $book that uses
	 * Book's function search() to retrieve all of the books
	 * from the driver, in this case MySQL. 
	 * 
	 * The driver's search function returns all entries in the corresponding
	 * table as an associative array
	 */
	public function IndexAction()
	{
	}

	/**
	 * Invoked when action parameter is "create"
	 * 
	 * This function handles data that is inputted in the form
	 * $status is a boolean that returns whether create function
	 * was successful in adding entry to table
	 */
	public function CreateAction()
	{
		$this->View->status = false;
		$this->View->errorMessage = "";
		$this->View->errorStatus = 0;
		
		if(isset($_POST['submit']))
		{
			if(isset($_POST['title']))
			{
				if (empty($_POST['title']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Title cannot be empty. ";
				}
				else
				{
					$_POST['title'] = str_replace("'", "''", $_POST['title']);
					$this->Book->setTitle($_POST['title']);
				}
			}
			
			if(isset($_POST['author_id']))
			{
				// CHECK IF AUTHOR ID IS IN DATABASE
				$result = $this->Author->verify("id",$_POST['author_id']);
				if (count($result) == 1)
				{
					$this->Book->setAuthorId($_POST['author_id']);
				}
				else
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Author not in database. ";
				}
			}
			
			if(isset($_POST['price']))
			{
				if (!is_numeric($_POST['price']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Price must be a number. ";
				}
				else if ($_POST['price'] < 0)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Price must be a positive number. ";
				}
				else
				{
					$this->Book->setPrice($_POST['price']);
				}
			}
			
			if(isset($_POST['ISBN']))
			{
				if (!is_numeric($_POST['ISBN']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "ISBN must be a number. ";
				}
				else if ($_POST['ISBN'] < 0)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "ISBN must be a positive number. ";
				}
				else
				{
					$this->Book->setISBN($_POST['ISBN']);
				}
			}
			
			if(isset($_POST['publisher_id']))
			{
				
				// CHECK IF PUBLISHER ID IS IN DATABASE
				$result = $this->Publisher->verify("id",$_POST['publisher_id']);
				if (count($result) == 1)
				{
					$this->Book->setPublisherId($_POST['publisher_id']);
				}
				else
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Publisher not in database. ";
				}
			}
			
			if(isset($_POST['published_date']))
			{
				if (!is_numeric(substr($_POST['published_date'],0,4)) || substr($_POST['published_date'],0,4) > 3000 || $_POST['published_date'][4] != "-" || !is_numeric(substr($_POST['published_date'],5,2)) || substr($_POST['published_date'],5,2) > 12 || $_POST['published_date'][7] != "-" || !is_numeric(substr($_POST['published_date'],-2)) || substr($_POST['published_date'],-2) > 31)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Published Date must be a valid date. ";
				}
				$this->Book->setPublishedDate($_POST['published_date']);
			}
			
			$this->Book->setCreateValues();

			if ($this->View->errorStatus == "1")
			{
				// do nothing
			}
			else
			{
				$this->View->status = $this->Book->create($this->Book);
			}
		}
	}
	
	/**
	 * Invoked when action parameter is "view"
	 */
	public function ViewAction()
	{
		$where = "";
		$this->View->book = $this->Book->select($where);
	}
	
	/**
	 * Invoked when action parameter is "search"
	 */
	public function SearchAction()
	{
		$where = "";
		if(isset($_GET['title']) && !empty($_GET['title']))
		{
			if($where != "")
			{
				$where .= " AND ";
			}
			$where .= "book.title LIKE '%".$_GET['title']."%'";
		}
		if(isset($_GET['author']) && !empty($_GET['author']))
		{
			if($where != "")
			{
				$where .= " AND ";
			}
			$where .= "author.firstName LIKE '%".$_GET['author']."%' OR author.lastName LIKE '%".$_GET['author']."%'";
		}
		if(isset($_GET['startDate']) && !empty($_GET['startDate']))
		{
			if($where != "")
			{
				$where .= " AND ";
			}
			$where .= "book.published_date >= '".$_GET['startDate']."'";
		}
		if(isset($_GET['endDate']) && !empty($_GET['endDate']))
		{
			if($where != "")
			{
				$where .= " AND ";
			}
			$where .= "book.published_date <= '".$_GET['endDate']."'";
		}
		if(isset($_GET['startPrice']) && !empty($_GET['startPrice']))
		{
			if($where != "")
			{
				$where .= " AND ";
			}
			$where .= "book.price >= '".$_GET['startPrice']."'";
		}
		if(isset($_GET['endPrice']) && !empty($_GET['endPrice']))
		{
			if($where != "")
			{
				$where .= " AND ";
			}
			$where .= "book.price <= '".$_GET['endPrice']."'";
		}
		$this->View->book = $this->Book->select($where);
	}
	
	/**
	 * Invoked when action parameter is "edit"
	 *
	 * This function handles data this is inputted in the form
	 * $status is a boolean that returns whether create function
	 * was successful in editing entry in table
	 */
	public function EditAction()
	{
		$this->View->status = false;
		$this->View->errorMessage = "";
		$this->View->errorStatus = 0;
		
		// If form has been submitted
		if(isset($_GET))
		{
			if(isset($_GET['id']))
			{
				// CHECK IF BOOK ID IS IN DATABASE
				$result = $this->Book->verify("id",$_GET['id']);
				if (empty($_GET['id']) || sizeof($result) != 1)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Invalid book. ";
				}
				else
				{
					$this->Book->setId($_GET['id']);
				}
			}
			
			if(isset($_GET['title']))
			{
				if (strlen($_GET['title']) == 2)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Title cannot be empty. ";
				}
				else
				{
					$_GET['title'] = substr($_GET['title'],1,-1);
					$_GET['title'] = str_replace("'", "''", $_GET['title']);
					$_GET['title'] = "'".$_GET['title']."'";
					$this->Book->setTitle($_GET['title']);
				}
			}
			
			if(isset($_GET['author_id']))
			{
				// CHECK IF AUTHOR ID IS IN DATABASE
				$result = $this->Author->verify("id",$_GET['author_id']);
				if (count($result) == 1)
				{
					$this->Book->setAuthorId($_GET['author_id']);
				}
				else
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Author not in database. ";
				}
			}
			
			if(isset($_GET['price']))
			{
				if (!is_numeric($_GET['price']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Price must be a number. ";
				}
				else if ($_GET['price'] < 0)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Price must be a positive number. ";
				}
				else
				{
					$this->Book->setPrice($_GET['price']);
				}
			}
			
			if(isset($_GET['ISBN']))
			{
				if (!is_numeric($_GET['ISBN']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "ISBN must be a number. ";
				}
				else if ($_GET['ISBN'] < 0)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "ISBN must be a positive number. ";
				}
				else
				{
					$this->Book->setISBN($_GET['ISBN']);
				}
			}
			
			if(isset($_GET['publisher_id']))
			{
				
				// CHECK IF PUBLISHER ID IS IN DATABASE
				$result = $this->Publisher->verify("id",$_GET['publisher_id']);
				if (count($result) == 1)
				{
					$this->Book->setPublisherId($_GET['publisher_id']);
				}
				else
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Publisher not in database. ";
				}
			}
			
			if(isset($_GET['published_date']))
			{
				$date = $_GET['published_date'];
				$_GET['published_date'] = substr($_GET['published_date'],1,10);
				if (!is_numeric(substr($_GET['published_date'],0,4)) || substr($_GET['published_date'],0,4) > 3000 || $_GET['published_date'][4] != "-" || !is_numeric(substr($_GET['published_date'],5,2)) || substr($_GET['published_date'],5,2) > 12 || $_GET['published_date'][7] != "-" || !is_numeric(substr($_GET['published_date'],-2)) || substr($_GET['published_date'],-2) > 31)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Published Date must be a valid date. ";
				}
				$this->Book->setPublishedDate($date);
			}
			
			$this->Book->setEditValues();

			if ($this->View->errorStatus == "1")
			{
				// do nothing
			}
			else
			{
				$this->View->status = $this->Book->edit($this->Book);
			}
		}
	}
	
	/**
	 * Invoked when action parameter is "delete"
	 *
	 * $status is a boolean that returns whether create function
	 * was successful in deleting entry in table
	 */
	public function DeleteAction()
	{
		// If form has been submitted
		if(isset($_GET['id']))
		{
			// Save data to table
			$this->View->status = $this->Book->delete($_GET['id']);
		}
	}
}