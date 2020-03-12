<?php
	require "public_module.php";
	$DeBugModel = 0;

	$name    = seizeor('name', 'n');      // 笔记名字
	$info    = seizeor('info', 'i');      // 笔记内容
	$read    = seizeor('read', 'r');      // 打开分享
	$comt    = seizeor('comt', 'c');      // 评论内容
	$keep    = seizeor('sub_keep', 'k');  // 是否保存
	$share   = seizeor('sub_share', 's'); // 进行分享
	$refresh = seizeor('sub_flash', 'f'); // 刷新内容
	$hist    = seizeor('sub_hist', 'h');  // 历史记录
	$histime = seizeor('time', 't');      // 历史时间
	$delete  = seizeor('sub_del', 'd');   // 删除记录
	$time    = time();                    // 时间戳
	$IP      = getIP();                   // 用户IP

	if ($name)
	{
		processName(); // 处理名字  名字::分享码   名字_
	}

	if ($share != NULL && $name && $info != NULL) // 分享
	{
		keeper();
		addVal("share");
		$tname = sqlsafe($name);
		$row = row("SELECT * FROM notes WHERE name = '$tname'");
		if ($row)
			header("Location:" . $_SERVER['PHP_SELF'] . '?r=' . $row['code']);
		else
			require "sharer.php";
	}
	else if ($read == "null" && $name)
	{
		addVal("share");
		$tname = sqlsafe($name);
		$sql = "SELECT * FROM notes WHERE name = '$tname'";
		$row = row($sql);
		if ($row && $row['code'])
		{
			$url = "Location:" . $_SERVER['PHP_SELF'] . '?r=' . $row['code'];
			$url = str_replace("/index.php", "", $url);
			header($url);
		}
		else
		{
			$ttname = urldecode($tname);
			$row = row("SELECT * FROM notes WHERE name = '$ttname'");
			if ($row)
			{
				$url = "Location:" . $_SERVER['PHP_SELF'] . '?r=' . $row['code'];
				$url = str_replace("/index.php", "", $url);
				header($url);
			}
			else
			{
				echo "当前页没有分享内容。<br />请保存后再试。";
			}
			// echo "当前页没有分享内容。<br />请保存后再试。";
		}
//			header("Location:" . $_SERVER['PHP_SELF'] . '?n=' . $tname);
	} 
	else if ($read)                               // 只读
	{
		addVal("read");
		$tread = sqlsafe($read);
		$row = row("SELECT * FROM notes WHERE code = '$tread'");
		if ($row) $info = $row['info'];
		else
		{
			$ttread = urldecode($tread);
			$row = row("SELECT * FROM notes WHERE code = '$ttread'");
			if ($row)
			{
				$tread = $ttread;
				$info = $row['info'];
			}
			else
			{
				$ttread = urlencode($tread);
				$row = row("SELECT * FROM notes WHERE code = '$ttread'");
				if ($row)
				{
					$tread = $ttread;
					$info = $row['info'];
				}
				else
				{
					echo "当前页没有分享内容。<br />请保存后再试。";
				}
			}
		}
		require "sharer.php";
	}
	else if ($refresh != NULL)
	{
		$tname = sqlsafe($name);
		$row = row("SELECT * FROM notes WHERE name = '$tname'");
		if ($row) $info = $row['info'];
		require "editor.php";
	}
	else if ($name && $histime != NULL)
	{
		$tname = sqlsafe($name);
		$row = row("SELECT * FROM history WHERE name = '$tname' AND time = '$histime'");
		if ($row) $info = $row['info'];
		require "editor.php";
	}
	else if ($name && $hist != NULL)
	{
		require "history.php";
	}
	else if ($name && $delete != NULL)
	{
		$tname = sqlsafe($name);
		$sql = "DELETE FROM notes WHERE name = '$tname'";
		query($sql);
		delRecentCookie($name);
		//header("Location:" . $_SERVER['PHP_SELF']);
	}
	else if ($name && $info != NULL)              // 保存
	{
		keeper();
		require "editor.php";
	}
	else if ($name && $comt != NULL)              // 评论
	{
		require "comments.php";
	}
	else if ($name)                               // 进入
	{
		nullAndAdd();
		addVal("open");
		require "editor.php";
	}
	else                                          // 入口
	{
		require "editor.php";
	}
?><?php
	
	function specialName() // 处理特殊的名字
	{
		global $name;
		// 名字_  打印列表
		

		return 0;
	}

	function processName()
	{
		global $name;
		
		if (strlen($name) >= 4 && $pos = strpos($name, "::"))
		{
			global $time, $IP;

			$purename = substr($name, 0, $pos);
			$sharecode = substr($name, $pos+2, strlen($name)-$pos-2);
			$name = $purename;

			$sql = "SELECT * FROM notes WHERE code = '$sharecode'";
			if ($row = row($sql))
			{
				echo "该分享码已经存在";
			}
			else
			{
				$sql = "SELECT * FROM notes WHERE name = '$purename'";
				if (!($row = row($sql))) // 记录不存在
				{
					$sql = "INSERT into notes (name, code, create_time, IP) values ('$purename', '$sharecode', '$time', '$IP')";
					if (!query($sql))
					{
						echo "添加到数据库失败" . mysql_error();
					}
				}
				else // 记录已经存在，更新即可
				{
					$sql = "UPDATE notes set code = '$sharecode' WHERE name = '$purename'";
					fecho(query($sql), "保存到数据库失败" . mysql_error());
					addHist();
				}
				
			}
		}
		else if (strlen($name) >= 2 && substr($name, strlen($name)-1, 1) == "_")
		{
			global $info;
			if ($info == NULL) // 直接进入列表
			{
				require "lists.php";
				exit;
			}
			else // 保存为新页面
			{
				$pageNum = 0;
				do {
					++$pageNum;
					$tName = $name . $pageNum;
					$sql = "SELECT * FROM notes WHERE name = '$tName'";
					$row = row($sql);
				} while($row);
				$name = $tName;
			}
		}
	}

	function adder() // 添加记录
	{
		global $name, $info, $time, $IP;
		$tname = sqlsafe($name);
		$tinfo = sqlsafe($info);
		$code = createcode();
		$sql = "SELECT * FROM notes WHERE name = '$tname'";
		if (!($row = row($sql)))
		{
			$sql = "INSERT INTO notes (name, info, code, create_time, IP) VALUES ('$tname', '$tinfo', '$code', '$time', '$IP')";
			fecho(query($sql), "添加到数据库失败" . error());
		}

		addHist();
	}

	function keeper() // 保存记录
	{
		setRecentCookie();

		
		global $name, $info, $time, $IP;
		$sql = "SELECT * FROM notes WHERE name = '$name'";
		$tname = sqlsafe($name);
		$tinfo = sqlsafe($info);
		
		if (!($row = row($sql)))  // 记录不存在
		{
			adder();
			return 0;
		}
		$save = $row['save'] + 1; // 修改次数+1

		$sql = "UPDATE notes SET info = '$tinfo', alter_time = '$time', save = '$save', IP = '$IP' WHERE name = '$tname'";
		fecho(query($sql), "" . error());

		addHist();
	}

	function nullAndAdd()
	{
		global $name;
		$sql = "SELECT * FROM notes WHERE name = '$name'";
		$tname = sqlsafe($name);
		if (!($row = row($sql)))  // 记录不存在
		{
			adder();
		}
	}

	function addHist()
	{
		global $name, $info, $time, $IP;
		$early_time = 0;
		$early_info = "";
		$early_time2 = 0;
		$early_info2 = "";

		$result = query("SELECT * FROM history WHERE name = '$name' ORDER BY time DESC");
		if ($row = $result->fetch_assoc()) // 获取最新记录
		{
			$early_time = $row['time'];
			$early_info = $row['info'];
		}
		if ($row = $result->fetch_assoc()) // 获取第二新记录
		{
			$early_time2 = $row['time'];
			$early_info2 = $row['info'];
		}
		if ($info == $early_info) // 一样的，不进行操作
			;
		else if ( $early_time2 > 0       // 避免初始内容
		  && $early_time2 > $time-600    // 十分钟内
		  && abs(mb_strlen($early_info2, "utf-8")-mb_strlen($info, "utf-8")) < 50   // 比上次不超过50字
		  && abs(mb_strlen($early_info, "utf-8")-mb_strlen($info, "utf-8")) < 50 )  // 比最新不超过50字
		{
			//echo "【1】";
			$sql = "UPDATE history SET info = '$info', time = '$time' WHERE name = '$name' AND time = '$early_time'";
			fecho(query($sql), "覆盖最新记录出错" . error());
		}
		else
		{
			//echo "【2】";
			$sql = "INSERT INTO history (name, info, time, IP) VALUES ('$name', '$info', '$time', '$IP')";
			fecho(query($sql), "添加到记录出错" . error());
		}
	}

	function abs11($x)
	{
		return $x > 0 ? $x : -$x;
	}

	function addVal($s) // 某个数值+1
	{
		global $name, $read, $IP;

		if ($name)
		{
			$sql = "SELECT * from notes where name = '$name' and IP = '$IP'"; // IP 相同
			if (row($sql)) {
				return ;
			}
		}
		else if ($read)
		{
			$sql = "SELECT * from notes where code = '$read' and IP = '$IP'"; // IP 相同
			if (row($sql)) {
				return ;
			}
		}

		if ($name && $row = row("SELECT * FROM notes WHERE name = '$name'"))
		{
			$times = $row[$s] + 1;
			fecho(query("UPDATE notes SET `$s` = '$times' where name = '$name'"), "增加 " . $s . " 出错" . error());
			// echo "add $name $s";
		}
		else if ($row = row("SELECT * FROM notes WHERE code = '$read'"))
		{
			$times = $row[$s] + 1;
			fecho(query("UPDATE notes SET `$s` = '$times' where code = '$read'"), "增加 " . $s . " 出错" . error());
			// echo "add $read $s";
		}
		// else echo "add $name $s false";
	}

	function getShareUrl($name = "")
	{
		global $name, $code;
		if ($name == "") return $_SERVER['PHP_SELF'];
		$sql = "SELECT * FROM notes WHERE name='$name'";
		$row = row($sql);
		if ($row)
		{
			$code = $row['code'];
		}
		return $_SERVER['PHP_SELF'] . "?read=$code";
	}

	function createcode() // 生成六位数随机码
	{
		global $code;
		$time = 0;
		$full = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

		do {
			$s = "";
			for ($i = 0; $i < 6; $i++)
			{
				$x = rand(0, 62);
				$s .= substr($full, $x, 1);
			}
			$code = $s;

			$sql = "SELECT * FROM notes WHERE code = '$code'";
			$row = row($sql);
		} while ($row);

		return $code;
	}
	
	function setRecentCookie() // 记录最近编辑的内容，保留一周
	{
		global $name;
		if (!$name) return ;
		
		$start = seizecookie("recent_start");
		$end = seizecookie('recent_end');

		if ($start == null)
		{
			setcookie('recent_start', '1', time()+31622400);
			$start = 1; 
		}
		if ($end == null)
		{
			setcookie('recent_end', '2', time()+31622400);    // 不包含结尾
			setcookie('recent_1', $name, time()+604800); // 3600*24*7
			return ;
		}
		
		if (seizecookie('recent_'.($end-1)) == $name)    // 已经是最新的了
			return ;
		
		for ($i = $start; $i < $end; $i++)               // 更新起始位置
			if (seizecookie('recent_'.$i) != null)
				break;
		if ($start != $i) // start的位置已经过期
		{
			$start = $i;
			setcookie('recent_start', $start, time()+31622400);
		}
		
		for ($i = $start; $i < $end; $i++)
		{
			$cname = seizecookie('recent_' . $i);
			if ($cname == $name) // 在这个位置
			{
				setcookie('recent_'.$i, '', time()-3600);
				break;
			}
		}
		
		setcookie('recent_'.$end, $name, time()+604800);
		setcookie('recent_end', $end+1, time()+31622400);
	}
	
	function delRecentCookie($name)
	{
		if (!$name) return ;
		
		$start = seizecookie("recent_start");
		$end = seizecookie('recent_end');

		if ($start == null || $end == null)
			return ;
		
		for ($i = $start; $i < $end; $i++)   // 更新起始位置
			if (seizecookie('recent_'.$i) == $name)
			{
				setcookie('recent_'.$i, "", time()-3600);
				break;
			}
	}

?>