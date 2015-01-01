<?php

$this->title = 'Add Authors';

 // Corresponding file for index=author&action=create
 // Stored as $this->content
 // Included from View.php
 // Echoed from default.php

$renderForm = true;

// $this-> status from corresponding controller extension
// Boolean that returns whether create function was successful

if ($this->status) 
{
	$renderForm = false;
	echo 'Author created';
} 

if ($this->errorMessage != "")
{
	$this->errorMessage = "ERROR: ".$this->errorMessage;
}
echo $this->errorMessage;

if ($renderForm) 
{
?>
<form method="POST">
	<label for='firstname'>First name: </label><input type='text' id='firstname' name='firstname' required><br>
	<label for='lastname'>Last name: </label><input type="text" id="lastname" name="lastname" required><br/>
	<input type='submit' name='submit' id='submit' value='Submit'>
</form>
<?php 
}