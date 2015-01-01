<?php

$this->title = 'Add Books';

 // Corresponding file for index=book&action=create
 // Stored as $this->content
 // Included from View.php
 // Echoed from default.php

$renderForm = true;

// $this-> status from corresponding controller extension
// Boolean that returns whether create function was successful

if ($this->status) 
{
	$renderForm = false;
	echo 'Book created';
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
	<label for='title'>Title: </label><input type='text' id='title' name='title' required><br>
	<label for='author_id'>Author: </label>
	<select id='author_id' name='author_id'>
		<!--  Uncomment this when JavaScript is added -->
		<!--  <option value='-1'>Select Author</option> --!>
	
<?php 
	
	// sort array by value but maintain index position
	asort($this->author);
	
	foreach ($this->author as $key => $value) 
	{
		echo '<option value=' . $key . '>' . $value . '</option>';
	}
	

?>

	</select>
	<br>
	<label for='publisher_id'>Publisher:</label>
	<select id='publisher_id' name='publisher_id'>
		<!--  Uncomment this when JavaScript is added -->
		<!-- <option value='-1'>Select Publisher</option> --!>
	
<?php 
	
	// sort array by value but maintain index position
	asort($this->publisher);	

	foreach ($this->publisher as $key => $value) 
	{
		echo '<option value=' . $key . '>' . $value . '</option>';
	}
	

?>
	
	</select>
	<br>
	<label for='published_date'>Published Date: </label><input type='text' id='published_date' name='published_date' value="YYYY-MM-DD" required>
	<br>
	<label for='price'>Price: </label><input type='text' id='price' name='price' required>
	<br>
	<label for='ISBN'>ISBN: </label><input type='text' id='ISBN' name='ISBN' required>
	<br>
	<input type='submit' name='submit' id='submit' value='Submit'>
</form>

<?php 
}