<?php

if ($this->status)
{
	header("Location: index.php?controller=publisher&action=view");
    exit;
}
else
{
	echo 'ERROR: Publisher entry not saved';
	echo $this->errorMessage;
}