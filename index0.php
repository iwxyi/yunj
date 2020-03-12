<?php
	require "public_module.php";
	
	$sql = "SELECT * from notes where name = '123'";
	$row = row($sql);

	echo $row['info'];