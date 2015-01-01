<?php

if ($this->status)
{
	header("Location: index.php?controller=book&action=search");
    exit;
}
else
{
	echo 'ERROR: Book entry not saved<br>';
	echo $this->errorMessage;
}