<?php

$this->title = 'CNBC Publishers';

$currentURL = "index.php?controller=publisher&action=view";

// Sort book catalog by title by default
$names = array();
foreach ($this->publisher as $entry)
{
	$names[] = $entry['publisher']['name'];
}
array_multisort($names, SORT_ASC, $this->publisher);

if(isset($_GET["sort"]) && $_GET["sort"] == "desc")
{
	array_multisort($names, SORT_DESC, $this->publisher);
}

?>

<br>
<h1>CNBC Publishers</h1>

<p align="center">

<table border="1" id="sortTable">
	<thead>
		<tr>
			<th>Publisher Name </th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
		</thead>
		<tbody>
		
<?php 

$count = 0;

foreach($this->publisher as $p)
{
	$p['publisher']['name'] = str_replace('"', "&#34;", $p['publisher']['name']);
	$deleteName = str_replace("'", "", $p['publisher']['name']);
	
	echo '<tr>';
	echo '<td id = \'name'.$p['publisher']['id'].'\'>';
	print_r($p['publisher']['name']);
	echo '</td>';
	echo '<td id = \'edit'.$p['publisher']['id'].'\'><a href = "javascript:void(0)" id="editLink" onclick="openEdit(\'';
	print_r($p['publisher']['id']);
	echo '\')">Edit</a></td>';
	echo '<td><a class="delete" href="javascript:void(0)" onclick="openDelete(';
	print_r("'".$p['publisher']['id']."','".$deleteName."'");
	echo ')">Delete</a></td>';
	echo '</tr>';
	$count++;
}

?>
</tbody>	
</table>
<br><b><?php print($count." publishers in database"); ?></b>

<script src="/js/TSorter_1.js" type="text/javascript"></script>
<script src="/js/publisher.js"></script>

<?php
