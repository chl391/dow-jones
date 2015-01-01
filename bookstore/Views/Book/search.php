<?php

$this->title = 'Search CNBC Books';

if (isset($_GET['page']))
{ 
	// filter everything but numbers for security
	$page = preg_replace('#[^0-9]#i', '', $_GET['page']);
}
else
{ 
	// set default to page 1
	$page = 1;
}

// Current URL for sorting link
$currentURL = "index.php?controller=book&action=search";

if (isset($_GET['title']) && isset($_GET['author']) && isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['startPrice']) && isset($_GET['endPrice']))
{
	$currentURL .= "&title=".$_GET['title']."&author=".$_GET['author']."&startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."&startPrice=".$_GET['startPrice']."&endPrice=".$_GET['endPrice']."&submit=Submit";
}

$currentURLNoPage = $currentURL;

if (isset($_GET['page']))
{
	$currentURL .= "&page=".$_GET['page'];
}

$currentURLnosort = $currentURL;

if (isset($_GET['sortby']) && isset($_GET['sort']))
{
	$currentURL .= "&sortby=".$_GET['sortby']."&sort=".$_GET['sort'];
	$currentURLNoPage .= "&sortby=".$_GET['sortby']."&sort=".$_GET['sort'];
}

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

		if ($_GET['sort'] == "desc")
		{
			array_multisort($authors, SORT_DESC, $this->book);
		}
		else
		{
			array_multisort($authors, SORT_ASC, $this->book);
		}
	}

	if ($_GET['sortby'] == "publisher")
	{
		$publishers = array();
		foreach ($this->book as $entry)
		{
			$publishers[] = $entry['publisher']['name'];
		}

		if ($_GET['sort'] == "desc")
		{
			array_multisort($publishers, SORT_DESC, $this->book);
		}
		else
		{
			array_multisort($publishers, SORT_ASC, $this->book);
		}
	}

	if ($_GET['sortby'] == "price")
	{
		$prices = array();
		foreach ($this->book as $entry)
		{
			$prices[] = $entry['book']['price'];
		}

		if ($_GET['sort'] == "desc")
		{
			array_multisort($prices, SORT_DESC, $this->book);
		}
		else
		{
			array_multisort($prices, SORT_ASC, $this->book);
		}
	}

	if ($_GET['sortby'] == "publisheddate")
	{
		$publisheddates = array();
		foreach ($this->book as $entry)
		{
			$publisheddates[] = $entry['book']['published_date'];
		}

		if ($_GET['sort'] == "desc")
		{
			array_multisort($publisheddates, SORT_DESC, $this->book);
		}
		else
		{
			array_multisort($publisheddates, SORT_ASC, $this->book);
		}
	}
}

?>

<br>
<h1>Search CNBC Books</h1>
<br>

<form method="GET" action="http://bookstore.com/index.php?controller=book&action=search&">
	<input type="hidden" name="controller" value="book">
	<input type="hidden" name="action" value="search">
	<label for='title'>Title: </label><input type='text' id='searchTitle' name='title'>
	<br>
	<label for='author'>Author: </label><input type='text' id='searchAuthor' name='author'>
	<br>
	<label for='date'>Published Date (YYYY-MM-DD): </label>
	<input type='date' id='searchStartDate' name='startDate'> - 
	<input type='date' id='searchEndDate' name='endDate'>
	<br>
	<label for='price'>Price: </label><input type='text' id='searchStartPrice' name='startPrice'> - 
	<input type='text' id='searchEndPrice' name='endPrice'><br>
	<input type='submit' name='submit' id='submit' value='Submit'><br>
</form>

<?php 

$submit = "";

echo "<p align='center'>";

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

<table cellpadding="0" cellspacing="0" border="1" class="display" id="sortTable" width="1500">
	<thead>
	<tr>
		<th>Title <a href="<?php echo "$currentURLnosort"?>&sortby=title&sort=asc"><img src="/img/arrowup.gif"></a>
		<a href="<?php echo "$currentURLnosort"?>&sortby=title&sort=desc"><img src="/img/arrowdown.gif"></a></th>
		<th>Author <a href="<?php echo "$currentURLnosort"?>&sortby=author&sort=asc"><img src="/img/arrowup.gif"></a>
		<a href="<?php echo "$currentURLnosort"?>&sortby=author&sort=desc"><img src="/img/arrowdown.gif"></a></th>
		<th>Publisher <a href="<?php echo "$currentURLnosort"?>&sortby=publisher&sort=asc"><img src="/img/arrowup.gif"></a>
		<a href="<?php echo "$currentURLnosort"?>&sortby=publisher&sort=desc"><img src="/img/arrowdown.gif"></a></th>
		<th>Price <a href="<?php echo "$currentURLnosort"?>&sortby=price&sort=asc"><img src="/img/arrowup.gif"></a>
		<a href="<?php echo "$currentURLnosort"?>&sortby=price&sort=desc"><img src="/img/arrowdown.gif"></a></th>
		<th>ISBN </th>
		<th>Published Date <a href="<?php echo "$currentURLnosort"?>&sortby=publisheddate&sort=asc"><img src="/img/arrowup.gif"></a>
		<a href="<?php echo "$currentURLnosort"?>&sortby=publisheddate&sort=desc"><img src="/img/arrowdown.gif"></a></th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
	</thead>

	<?php
	
		echo '<tbody>';
		$itemsPerPage = 10;
		$beginning = ($page-1) * $itemsPerPage;
		$count = 0;
		for($x = $beginning; $x<$beginning+$itemsPerPage; $x++)
		{
			if($x < sizeof($this->book))
			{
				$this->book[$x]['book']['title'] = str_replace('"', "&#34;", $this->book[$x]['book']['title']);
				$this->book[$x]['book']['title'] = str_replace("'", "&#39;", $this->book[$x]['book']['title']);
				$deleteTitle = str_replace("&#39;", "", $this->book[$x]['book']['title']);
				
				echo '<tr height="20">';
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
				print_r("'".$this->book[$x]['book']['id']."','".$deleteTitle."'");
				echo ')">Delete</a></td>';
				echo '</tr>';
				$count++;
			}
		}
		echo '</tbody>';
		print("</table>");
		for ($x = 0; $x < 10-$count; $x++)
		{
			echo "<br style='line-height:22px;'>";
		}
	echo '<p id="center"><b>';
	if ($submit == "no submit")
	{
		// Do not print search footnote if form is empty
	}
	else
	{
		if (sizeof($this->book) == 0)
		{
			print("Book not found");
		}
		else if (sizeof($this->book) == 1)
		{
			print("1 book found");
		}
		else
		{
			print(sizeof($this->book)." books found");
		}
	}
	echo "</b>";

$centerPages = "";
$sub1 = $page - 1;
$sub2 = $page - 2;
$add1 = $page + 1;
$add2 = $page + 2;
$lastPage = ceil(sizeof($this->book) / $itemsPerPage);
if ($page == 1)
{
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $add1 . '">' . $add1 . '</a> &nbsp;';
}
else if ($page == $lastPage)
{
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
}
else if ($page > 2 && $page < ($lastPage - 1))
{
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $add1 . '">' . $add1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $add2 . '">' . $add2 . '</a> &nbsp;';
}
else if ($page > 1 && $page < $lastPage)
{
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
	$centerPages .= '&nbsp; <span class="pagNumActive">' . $page . '</span> &nbsp;';
	$centerPages .= '&nbsp; <a href="' . $currentURLNoPage . '&page=' . $add1 . '">' . $add1 . '</a> &nbsp;';
}

$paginationDisplay = ""; // Initialize the pagination output variable
// Code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
if ($lastPage != "1")
{
	// Shows the user what page they are on, and the total number of pages
	$paginationDisplay .= 'Page <strong>' . $page . '</strong> of ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
	// If we are not on page 1 we can place the Back button
	if ($page != 1)
	{
		$previous = $page - 1;
		$paginationDisplay .=  '&nbsp;  <a href="' . $currentURLNoPage . '&page=' . $previous . '"> Back</a> ';
	}
	// Lay in the clickable numbers display here between the Back and Next links
	$paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
	// If not on the very last page we can place the Next button
	if ($page != $lastPage) 
	{
		$nextPage = $page + 1;
		$paginationDisplay .=  '&nbsp;  <a href="' . $currentURLNoPage . '&page=' . $nextPage . '"> Next</a> ';
	}
}

if ($lastPage != 1 && $lastPage != 0)
{
?>

<!--<script src="/js/TSorter_1.js" type="text/javascript"></script>--!>
<script src="/js/book.js"></script>

<div style="margin-left:58px; margin-right:58px; padding:6px; background-color:#FFF; border:#999 1px solid;"><?php echo $paginationDisplay; ?></div>

<?php 
}
