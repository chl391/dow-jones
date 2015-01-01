<?php

// Corresponding file for index=book&action=index
// Stored as $this->content
// Included from View.php
// Echoed from default.php


?>

<p align='center'>
<?php 

// Sort book catalog by title by default
$titles = array();
foreach ($this->book as $entry) 
{
	$titles[] = $entry['book']['title'];
}
array_multisort($titles, SORT_ASC, $this->book);

if (isset($_GET['sortby']))
{
	if ($_GET['sortby'] == "title")
	{
		if ($_GET['sort'] == "desc")
		{
			array_multisort($titles, SORT_DESC, $this->book);
		}
	}
	
	if ($_GET['sortby'] == "author")
	{
		$authors = array();
		foreach ($this->book as $entry) 
		{
			$authors[] = $entry['author']['lastName'];
		}
		
		if ($_GET['sort'] == "asc")
		{
			array_multisort($authors, SORT_ASC, $this->book);
		}
		if ($_GET['sort'] == "desc")
		{
			array_multisort($authors, SORT_DESC, $this->book);
		}
	}
	
	if ($_GET['sortby'] == "publisher")
	{
		$publishers = array();
		foreach ($this->book as $entry)
		{
			$publishers[] = $entry['publisher']['name'];
		}
		
		if ($_GET['sort'] == "asc")
		{
			array_multisort($publishers, SORT_ASC, $this->book);
		}
		if ($_GET['sort'] == "desc")
		{
			array_multisort($publishers, SORT_DESC, $this->book);
		}
	}
	
	if ($_GET['sortby'] == "price")
	{
		$prices = array();
		foreach ($this->book as $entry)
		{
			$prices[] = $entry['book']['price'];
		}
		
		if ($_GET['sort'] == "asc")
		{
			array_multisort($prices, SORT_ASC, $this->book);
		}
		if ($_GET['sort'] == "desc")
		{
			array_multisort($prices, SORT_DESC, $this->book);
		}
	}
	
	if ($_GET['sortby'] == "publisheddate")
	{
		$publisheddates = array();
		foreach ($this->book as $entry)
		{
			$publisheddates[] = $entry['book']['published_date'];
		}
		
		if ($_GET['sort'] == "asc")
		{
			array_multisort($publisheddates, SORT_ASC, $this->book);
		}
		if ($_GET['sort'] == "desc")
		{
			array_multisort($publisheddates, SORT_DESC, $this->book);
		}
	}
}

?>

<span id="authorCode" hidden><?php 
	
	// sort array by value but maintain index position
	asort($this->author);
	
	foreach ($this->author as $key => $value) 
	{
		echo '<option value=' . $key . '>' . $value . '</option>';
	}
	

?></select>

</span> 

<span id="publisherCode" hidden><?php 
	
	asort($this->publisher);	

	foreach ($this->publisher as $key => $value) 
	{
		echo '<option value=' . $key . '>' . $value . '</option>';
	}
	

?></select></span> 

<table cellpadding="0" cellspacing="0" border="1" class="display" id="sortTable">
	<thead>
	<tr>
		<th> Title </th>
		<th>Author </th>
		<th>Publisher </th>
		<th>Price </th>
		<th>ISBN</th>
		<th>Published Date </th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
	</thead>

<?php
echo '<tbody>';
if (isset($_GET['page'])) 
{ // Get pn from URL vars if it is present
	$page = preg_replace('#[^0-9]#i', '', $_GET['page']); // filter everything but numbers for security(new)
	//$pn = ereg_replace("[^0-9]", "", $_GET['pn']); // filter everything but numbers for security(deprecated)
} 
else { // If the pn URL variable is not present force it to be value of page number 1
	$page = 1;
}
$beginning = ($page-1) * 10;
for($x = $beginning; $x<$beginning+10; $x++)
{
	if($x < sizeof($this->book))
	{
		echo '<tr>';
		echo '<td id = \'title'.$this->book[$x]['book']['id'].'\'>';
		print_r($this->book[$x]['book']['title']);
		echo '</td>';
		echo '<td id = \'author'.$this->book[$x]['book']['id'].'\'>';
		print_r($this->book[$x]['author']['lastName']);
		echo ', ';
		print_r($this->book[$x]['author']['firstName']);
		echo '</td>';
		echo '<td id = \'publisher'.$this->book[$x]['book']['id'].'\'>';
		print_r($this->book[$x]['publisher']['name']);
		echo '</td>';
		echo '<td id = \'price'.$this->book[$x]['book']['id'].'\'>';
		print_r($this->book[$x]['book']['price']);
		echo '</td>';
		echo '<td id = \'ISBN'.$this->book[$x]['book']['id'].'\'>';
		print_r($this->book[$x]['book']['ISBN']);
		echo '</td>';
		echo '<td id = \'published_date'.$this->book[$x]['book']['id'].'\'>';
		print_r($this->book[$x]['book']['published_date']);
		echo '</td>';
		echo '<td id = \'edit'.$this->book[$x]['book']['id'].'\'><a href = "javascript:void(0)" id="editLink" onclick="openEdit(\'';
		print_r($this->book[$x]['book']['id']);
		echo '\')">Edit</a></td>';
		echo '<td><a class="delete" href="javascript:void(0)" onclick="openDelete(';
		print_r("'".$this->book[$x]['book']['id']."','".$this->book[$x]['book']['title']."'");
		echo ')">Delete</a></td>';
		echo '</tr>';
	}
}
echo '</tbody>';
print("</table><br><b>");

$centerPages = "";
$sub1 = $page - 1;
$sub2 = $page - 2;
$add1 = $page + 1;
$add2 = $page + 2;
$itemsPerPage = 10;
$lastPage = ceil(sizeof($this->book) / $itemsPerPage);
if ($page == 1) 
{
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $add1 . '">' . $add1 . '</a> &nbsp;';
} 
else if ($page == $lastPage) 
{
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
} 
else if ($page > 2 && $page < ($lastPage - 1)) 
{
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $add1 . '">' . $add1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $add2 . '">' . $add2 . '</a> &nbsp;';
} 
else if ($page > 1 && $page < $lastPage) 
{
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $add1 . '">' . $add1 . '</a> &nbsp;';
}

$paginationDisplay = ""; // Initialize the pagination output variable
// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
if ($lastPage != "1")
{
	// This shows the user what page they are on, and the total number of pages
	$paginationDisplay .= 'Page <strong>' . $page . '</strong> of ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
	// If we are not on page 1 we can place the Back button
	if ($page != 1) 
	{
		$previous = $page - 1;
		$paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $previous . '"> Back</a> ';
	}
	// Lay in the clickable numbers display here between the Back and Next links
	$paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
	// If we are not on the very last page we can place the Next button
	if ($page != $lastPage) {
		$nextPage = $page + 1;
		$paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?controller=book&action=search&page=' . $nextPage . '"> Next</a> ';
	}
}

print(sizeof($this->book)." books in database</b>");
print("</p>");
?>
   
      <div style="margin-left:58px; margin-right:58px; padding:6px; background-color:#FFF; border:#999 1px solid;"><?php echo $paginationDisplay; ?></div>

<?php
