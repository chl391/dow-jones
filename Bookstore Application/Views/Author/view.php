<?php

$this->title = 'CNBC Authors';

// Current URL for sorting link
$currentURL = "index.php?controller=author&action=view";

// Sort authors catalog by last name by default
$lastNames = array();
foreach ($this->author as $entry)
{
	$lastNames[] = $entry['author']['lastName'];
}
array_multisort($lastNames, SORT_ASC, $this->author);

if (isset($_GET['sortby']))
{
	if ($_GET['sortby'] == "lastName")
	{
		if ($_GET['sort'] == "desc")
		{
			array_multisort($lastNames, SORT_DESC, $this->author);
		}
	}

	if ($_GET['sortby'] == "firstName")
	{
		$firstNames = array();
		foreach ($this->author as $entry)
		{
			$firstNames[] = $entry['author']['firstName'];
		}

		if ($_GET['sort'] == "asc")
		{
			array_multisort($firstNames, SORT_ASC, $this->author);
		}
		if ($_GET['sort'] == "desc")
		{
			array_multisort($firstNames, SORT_DESC, $this->author);
		}
	}
}

?>

<br>
<h1>CNBC Authors</h1>

<p align="center">

<table border="1" id="sortTable">
	<thead>
		<tr>
			<th>First Name </th>
			<th>Last Name </th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
		</thead>
		<tbody>
		
<?php 

$count = 0;
foreach($this->author as $a)
{
	$a['author']['firstName'] = str_replace('"', "&#34;", $a['author']['firstName']);
	$a['author']['lastName'] = str_replace('"', "&#34;", $a['author']['lastName']);
	$deleteFirstName = str_replace("'", "", $a['author']['firstName']);
	$deleteLastName = str_replace("'", "", $a['author']['lastName']);
	
	echo '<tr>';
	echo '<td id = \'firstName'.$a['author']['id'].'\'>';
	print_r($a['author']['firstName']);
	echo '</td>';
	echo '<td id = \'lastName'.$a['author']['id'].'\'>';
	print_r($a['author']['lastName']);
	echo '</td>';
	echo '<td id = \'edit'.$a['author']['id'].'\'><a href = "javascript:void(0)" id="editLink" onclick="openEdit(\'';
	print_r($a['author']['id']);
	echo '\')">Edit</a></td>';
	echo '<td><a class="delete" href="javascript:void(0)" onclick="openDelete(';
	print_r("'".$a['author']['id']."','".$deleteFirstName.' '.$deleteLastName."'");
	echo ')">Delete</a></td>';
	echo '</tr>';
	$count++;
}

?>
</tbody>		
</table>
<br><b><?php print($count." authors in database"); ?></b>

<script src="/js/TSorter_1.js" type="text/javascript"></script>
<script src="/js/author.js"></script>

<?php
