<?php

/**
 * index file for bookstore
 * 
 * @author Charles Lin
 */

// APP_ROOT can now be used as substitute for root file path
// __FILE__ is a magic constant that gives full path and constant of file
// dirname(__FILE__) gives current file name, which is C:/wamp/www/bookstore/public
// dirname is called twice because the parent path is needed, C:/wamp/www/bookstore
define("APP_ROOT",dirname(dirname(__FILE__)));


// Check controller parameters
// isset() checks to see if a controller has been passed through URL (associative array)
// empty() makes sure that the controller that is passed is not empty
if (isset($_GET['controller']) && !empty($_GET['controller']))
{	
	// Controller input needs to be cleaned up to match case requirements
	// strtolower() makes the input all lower case
	// ucfirst() makes the first letter capitalized
	// this is stored in @var $controller
	$controller = ucfirst(strtolower($_GET['controller']));

	//echo 'Controller parameter "'.$controller.'" has been passed<br>';

	// Since the controller file will be used repeatedly, save path in @var $controllerFile
	// and @var $baseController File
	// 'Controller' needs to be concatenated because controller input will not
	// have 'Controller'attached at the end, which is required
	$controllerFile = "/Controllers/$controller".'Controller';
	$baseControllerFile = "/Controllers/Controller";

	// Checks to see if controller file exists in directory
	if (file_exists(APP_ROOT.$controllerFile.'.php'))
	{
		//echo 'Contoller file "'.$controllerFile.'" exists<br>';

		// Since controller file exists, include the class
		__autoload($baseControllerFile);
		__autoload($controllerFile);
		
		// Store class name in @var $className
		// 'Controller' needs to be concatenated because input will not have
		// "Controller' attached at the end
		$className = $controller."Controller";

		// Create a new instance of the controller in @var $c
		$c = new $className();
		
		// Set @var $methodName as "IndexAction" for default
		// @var $methodName will be changed in later code if 'action' is set
		$methodName = "IndexAction";

		// Check if action has been passed (associative array) and is not blank
		if (isset($_GET['action']) && !empty($_GET['action']))
		{
			$action = ucfirst(strtolower($_GET['action']));			

			//echo 'Action parameter "'.$action.'" has been passed<br>';

			// method_exists() expects parameters for class and method name
			// and checks to see if method exists
			if (method_exists($c,$action."Action"))
			{
				//echo 'Action method '.$action."Action".' exists in '.$className."<br>";
				
				// Since method name exists, change the methodName variable to 
				// corresponding method													
				$methodName = $action."Action";
			}
			// Else the method does not exist											
			else
			{
				// Since the method does not exist, demonstrate 404 error and exit the program
				
				//echo 'Action method '.$action."Action".'does not exist, invoking default (index) action';
				//echo "<br>".$methodName." invoked<br>";
				
				$e404 = "HTTP/1.0 404 Not Found";
				echo $e404;
				header($e404);
				
				//echo $e404;
				
				exit;
			}
		}
		// Else controller file does not exist in directory
		else
		{
			//echo 'Action parameter has not been passed, invoking default (index) action';
			
			$action = 'Index';
			
			//echo "<br>".$methodName." invoked<br>";
		}
		
		// Finally, invoke the method
		$c->$methodName();

		// Invoke rendering mechanism
		$c->View->render($controller, $action);
	}
	// Else file does not exist
	else
	{
		echo 'Controller file does not exist';
	}

}
else
{
	$e404 = "HTTP/1.0 404 Not Found";
	header($e404);
	echo $e404;
	echo "<br>Controller parameter not passed<br>Click <a href='index.php?controller=book&action=search'>here</a> to view bookstore home.";
	exit;
}

/**
 * This function autoloads classes to avoid error in simple include statements
 * This function has not be implemented completely throughout the applicaiton
 * due to unexpected errors
 * 
 * @param string $class_name
 */
function __autoload($class_name) 
{
	include_once APP_ROOT . $class_name . '.php';
}