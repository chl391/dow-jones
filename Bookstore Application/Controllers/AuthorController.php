<?php

/**
 * AuthorController class is included from index.php
 * Controller classes "control" model and view
 * 
 * @author Charles Lin
 */  			
class AuthorController extends Controller 
{
	/**
	 * AuthorController constructor creates instance of Controller,
	 * which autoloads View.php and creates a new instance of View
	 *
	 * Also autoloads Author model and creates a new instance of Author model object
	 */
	public function __construct() 
	{
		parent::__construct();
		
		include APP_ROOT.'/Models/Author.php';
		$this->Author = new Author();
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
		
		if (isset($_POST['submit'])) 
		{
			if(isset($_POST['firstname']))
			{
				if (empty($_POST['firstname']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "First Name cannot be empty. ";
				}
				else
				{
					$_POST['firstname'] = str_replace("'", "''", $_POST['firstname']);
					$this->Author->setFirstName($_POST['firstname']);
				}
			}
			
			if(isset($_POST['lastname']))
			{
				if (empty($_POST['lastname']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Last Name cannot be empty. ";
				}
				else
				{
					$_POST['lastname'] = str_replace("'", "''", $_POST['lastname']);
					$this->Author->setLastName($_POST['lastname']);
				}
			}
			
			$this->Author->setCreateValues();
			
			if ($this->View->errorStatus == "1")
			{
				// do nothing
			}
			else
			{
				$this->View->status = $this->Author->create($this->Author);
			}
		}
	}
	
	/**
	 * Invoked when action parameter is "view"
	 */
	public function ViewAction()
	{
		$this->View->author = $this->Author->select();
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
				$result = $this->Author->verify("id",$_GET['id']);
				if (empty($_GET['id']) || sizeof($result) != 1)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Invalid author. ";
				}
				else
				{
					$this->Author->setId($_GET['id']);
				}
			}
			if(isset($_GET['firstName']))
			{
				if (empty($_GET['firstName']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "First Name cannot be empty. ";
				}
				else
				{
					$_GET['firstName'] = substr($_GET['firstName'],1,-1);
					$_GET['firstName'] = str_replace("'", "''", $_GET['firstName']);
					$_GET['firstName'] = "'".$_GET['firstName']."'";
					$this->Author->setFirstName($_GET['firstName']);
				}
			}
			if(isset($_GET['LastName']))
			{
				if (empty($_GET['LastName']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Last Name cannot be empty. ";
				}
				else
				{
					$_GET['LastName'] = substr($_GET['LastName'],1,-1);
					$_GET['LastName'] = str_replace("'", "''", $_GET['LastName']);
					$_GET['LastName'] = "'".$_GET['LastName']."'";
					$this->Author->setLastName($_GET['LastName']);
				}
			}
			
			$this->Author->setEditValues();
			
			if ($this->View->errorStatus == "1")
			{
				// do nothing
			}
			else
			{
				$this->View->status = $this->Author->edit($this->Author);
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
			$this->View->status = $this->Author->delete($_GET['id']);
		}
	}
}