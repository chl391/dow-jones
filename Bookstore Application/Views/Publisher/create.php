<?php

$this->title = 'Add Publishers';

 // Corresponding file for index=publisher&action=create
 // Stored as $this->content
 // Included from View.php
 // Echoed from default.php

$renderForm = true;

// $this-> status from corresponding controller extension
// Boolean that returns whether create function was successful
if ($this->status) 
{
	$renderForm = false;
	echo 'Publisher created';
} 

if ($this->errorMessage != "")
{
	$this->errorMessage = "ERROR: ".$this->errorMessage;
}
echo "$this->errorMessage";

if ($renderForm) 
{
?>
<form method="POST">
	<label for='name'>Publisher Name: </label><input type='text' id='name' name='name' required><br>
	<input type='submit' name='submit' id='submit' value='Submit'>
</form>
<?php 
}