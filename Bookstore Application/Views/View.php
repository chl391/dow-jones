<?php

/**
 * View class is included from Controller.php
 *
 * @author Charles Lin
 */
class View 
{
	
	/**
	 * @param Controller $controller is passed as a reference just in case 
	 * want to change actual controller itself later on
	 * 
	 * HtmlHelper included and instantiated as @var $Html
	 */
	public function __construct(&$controller) 
	{
		$this->Controller =& $controller;
		
		require_once APP_ROOT . '/Views/Helper/HtmlHelper.php';
		$this->Html = new HtmlHelper($this);
	}
	/**
	 * Render is the class that actually outputs the HTML to the web
	 * The layout for the specific class and method is stored in $content
	 * 
	 * default.php is included, which stores the default layout with
	 * a call to $content so that $content can be included in html
	 * 
	 * @param string $class
	 * @param string $method
	 */
	public function render($class, $method) 
	{
		// Set title for HTML
		$this->title = $class . 'Controller::' . $method . 'Action'; //BooksController::IndexAction
		
		// Store .php file for the method called so that it can be rendered
		$viewPath= APP_ROOT . '/Views/' . $class . '/' . $method . '.php';
		//echo $viewPath;
		
		// Turn on output buffering
		// Without output buffering, your HTML is sent to the browser in pieces 
		// as PHP processes through your script.
		// With output buffering, your HTML is stored in a variable and sent 
		// to the browser as one piece at the end of your script.
		ob_start();
		
		// Render .php file for specific method
		require_once $viewPath;
		
		// Get the buffer contents and delete it
		$this->content = ob_get_clean();
		
		// default.php is the default file for layout
		include APP_ROOT . '/Views/Layouts/default.php';
	}
}