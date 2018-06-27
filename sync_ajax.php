<?php
	require "public_module.php";

	set_time_limit(0);

	$name     = seizeor('name', 'n');      // 笔记名字
	$info     = seizeor('info', 'i');      // 笔记内容
	$info2    = seize('info2');            // 上次接收的内容

	// if ($name == null || $name == "")
	// 	die;

	$sql      = "SELECT * from notes where name = '$name'";
	$row      = row($sql);
	$sql_info = $row['info'];

	$times = 0;
	while ($sql_info == $info || $sql_info == $info2)
	{
		if ($times > 60) // 一分钟没有消息，结束
			break;
		sleep(1);
		// addLog(time());
		$times++;

		$row = row($sql);
		$sql_info = $row['info'];
	}

	if ($times <= 60)
		echo $sql_info;
?><?php

function addLog($msg)
{
	$sql = "INSERT into log (msg) values ('$msg')";
	query($sql);
}

?>