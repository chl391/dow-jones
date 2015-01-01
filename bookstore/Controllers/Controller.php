<?php

/**
 * Controller class is included from index.php
 * A new instance of Controller is instantiated from corresponding controller
 * 
 * @author Charles Lin
 */

class Controller 
{
	
	/**
	 * View.php is autoloaded and an instance of View is created
	 * when Controller is instantiated from corresponding controller file
	 * 
	 * @var $this Controller is passed as @param when View is instantiated
	 * so that View can have access to $this Controller
	 */
	public function __construct()
	{
		$this->name = substr(get_class($this), 0, -10);
		__autoload('/Views/View');
		$this->View = new View($this);
	}
}