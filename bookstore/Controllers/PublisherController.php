<?php

/**
 * PublisherController class is included from index.php
 * Controller classes "control" model and view
 * 
 * @author Charles Lin
 */

class PublisherController extends Controller
{
	/**
	 * PublisherController constructor creates instance of Controller,
	 * which autoloads View.php and creates a new instance of View
	 *
	 * Also autoloads Publisher model and creates a new instance of Publisher model object
	 */
	public function __construct()
	{
		parent::__construct();

		include APP_ROOT.'/Models/Publisher.php';
		$this->Publisher = new Publisher();
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
			if (isset($_POST['name']))
			{
				if (empty($_POST['name']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Publisher Name cannot be empty. ";
				}
				else
				{
					$_POST['name'] = str_replace("'", "''", $_POST['name']);
					$this->Publisher->setName($_POST['name']);
				}
			}
			
			$this->Publisher->setCreateValues();
				
			if ($this->View->errorStatus == "1")
			{
				// do nothing
			}
			else
			{
				$this->View->status = $this->Publisher->create($this->Publisher);
			}
		}
	}
	
	public function ViewAction()
	{
		$this->View->publisher = $this->Publisher->select();
	}
	
	public function EditAction()
	{
		$this->View->status = false;
		$this->View->errorMessage = "";
		$this->View->errorStatus = 0;
		
		// If form has been submitted
		if (isset($_GET))
		{
			if (isset($_GET['id']))
			{
				$result = $this->Publisher->verify("id",$_GET['id']);
				if (empty($_GET['id']) || sizeof($result) != 1)
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Publisher id not valid. ";
				}
				else
				{
					$this->Publisher->setId($_GET['id']);
				}
			}
			
			if (isset($_GET['name']))
			{
				if (empty($_GET['name']))
				{
					$this->View->errorStatus = "1";
					$this->View->errorMessage .= "Publisher Name cannot be empty. ";
				}
				else
				{
					$_GET['name'] = substr($_GET['name'],1,-1);
					$_GET['name'] = str_replace("'", "''", $_GET['name']);
					$_GET['name'] = "'".$_GET['name']."'";
					$this->Publisher->setName($_GET['name']);
				}
			}
			
			$this->Publisher->setEditValues();
				
			if ($this->View->errorStatus == "1")
			{
				// do nothing
			}
			else
			{
				$this->View->status = $this->Publisher->edit($this->Publisher);
			}
		}
	}
	
	public function DeleteAction()
	{
		// If form has been submitted
		if(isset($_GET['id']))
		{
			// Save data to table
			$this->View->status = $this->Publisher->delete($_GET['id']);
		}
	}
}