<?php

/**
 * IndexController class is included from index.php
 * Controller classes "control" model and view
 * 
 * @author Charles Lin
 */  			
class IndexController extends Controller
{
	/**
	 * IndexController constructor creates instance of Controller,
	 * which autoloads View.php and creates a new instance of View
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Invoked when action parameter is not passed or is "index"
	 * Currently empty
	 */
	public function IndexAction()
	{
	}
}