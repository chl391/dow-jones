<?php

if ($this->status)
{
	header("Location: index.php?controller=author&action=view");
    exit;
}
else
{
	echo 'ERROR: Author entry not saved';
	echo $this->errorMessage;
}