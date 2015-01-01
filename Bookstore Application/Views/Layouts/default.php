<?php 

 // Default layout for bookstore
 // Included from view.php

?>

<doctype>
<html>
<head>
	<title><?php echo $this->title; ?></title>
	<link rel="stylesheet" type="text/css" href="/css/default.css">
</head>
<body>

<div id="header">
	<img src='img/cnbclogo.png' />
	<img src='img/books.jpg' width='200' id='right' />
</div>
<br>
<?php echo $this->content; ?>
<p id='center'>
<a href=<?php echo $this->Html->url(array('controller' =>"book", 'action' => 'search'));?>>Home</a>&nbsp&nbsp&nbsp
<a href=<?php echo $this->Html->url(array('controller' =>"author", 'action' => 'view'));?>>Edit Authors</a>&nbsp&nbsp&nbsp
<a href=<?php echo $this->Html->url(array('controller' =>"publisher", 'action' => 'view'));?>>Edit Publishers</a>&nbsp&nbsp&nbsp
<a href=<?php echo $this->Html->url(array('controller' =>"book", 'action' => 'create'));?>>Add Books</a>&nbsp&nbsp&nbsp
<a href=<?php echo $this->Html->url(array('controller' =>"author", 'action' => 'create'));?>>Add Authors</a>&nbsp&nbsp&nbsp
<a href=<?php echo $this->Html->url(array('controller' =>"publisher", 'action' => 'create'));?>>Add Publishers</a><br><br>
</p>
</body>
</html>