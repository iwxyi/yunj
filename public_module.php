<?php /* 公用模块，使用说明
<?php
	require "public_module.php";
	php_start();

	#code

	php_end();
?>
*/ ?><?php // 宏定义    // 
	header("Content-type: text/html; charset=utf-8"); // 允许中文

	define("MySQL_servername", "localhost");          // 数据库连接名
	define("MySQL_username", "root");                 // 数据库用户名
	define("MySQL_password", "root");                 // 数据库密码
	define("MySQL_database", "yunj");                 // 数据库

	define("T", "OK");                                // 成功后输出
	define("F", "Bad");                               // 失败后输出
	
	$con = NULL;
	$is_connected = 0;
	$DeBugModel = 1;
?><?php // 全局控制函数
	function php_start()                  // 开启操作：安全验证
	{
		verify();                             // 验证安全验证码
		reverify();                           // 返回安全验证码
	}

	function php_end()                    // 结束操作：关闭数据库
	{
		global $is_connected, $con;
		if ($is_connected) mysql_close($con); // 若数据库已连接，则关闭
	}

?><?php // 获取表单操作

	function seize()                  // 函数重载吧……	返回：值，空，NULL；1，0
	{
		$args = func_get_args();
		if (func_num_args() == 1)
			if (isset($_REQUEST[$args[0]]))
				return format_input($_REQUEST[$args[0]], 1);
			else
				return NULL;
		else                              // 多个，仅判断是否全部都存在
			{
				$num = func_num_args();
				for ($i = 0; $i < $num; $i++)
		if (!isset($_REQUEST[$args[$i]])) // 有一个没有值
						return 0;
				return 1;
			}
	}

	function seizeor() // 多个表单有一个就行了
	{
		$args = func_get_args();
		if (func_num_args() == 1)
			if (isset($_REQUEST[$args[0]]))
				return format_input($_REQUEST[$args[0]], 1);
			else
				return NULL;
		else                            // 多个，仅判断存不存在
		{
			$num = func_num_args();	
			for ($i = 0; $i < $num; $i++)
				if (isset($_REQUEST[$args[$i]]) && $_REQUEST[$args[$i]] != "") // 有一个值
				{
					return format_input($_REQUEST[$args[$i]], 1);
				}
			return NULL;
		}
	}

	function seize0($s, $blank = 0) // 获取必须存在且非空的表单，如果没有则强制退出
	{
		if (isset($_REQUEST[$s]) && $_REQUEST[$s] != "")
			return format_input($_REQUEST[$s], $blank);
		/*else if (isset($_COOKIE[$s]))
			return $s;*/
		else
			die ;
	}

	function seize1($s, $blank = 0) // 获取一个表单
	{
		if (isset($_REQUEST[$s]))
			return format_input($_REQUEST[$s], $blank);
		/*else if (isset($_COOKIE[$s]))
			return $s;*/
		else
			return NULL;
	}

	function seize2($s, &$a, $blank = 0) // 获取表单并引用赋值
	{
		if (isset($_REQUEST[$s]))
			return ($a = format_input($_REQUEST[$s], $blank));
		/*else if (isset($_COOKIE[$s]))
			return ($a = $s);*/
		else
			return ($a = NULL);
	}

	function seizeval($s, $blank = 0) // 获取一个非空表单，否则为NULL
	{
		if (isset($_REQUEST[$s]) && $_REQUEST[$s] != "")
			return format_input($_REQUEST[$s], $blank);
		/*else if (isset($_COOKIE[$s]))
			return $s;*/
		else
			return NULL;
	}
	
	function seizecookie($s, $blank = 0) // 获取一个表单
	{
		if (isset($_COOKIE[$s]))
			return format_input($_COOKIE[$s], $blank);
		/*else if (isset($_COOKIE[$s]))
			return $s;*/
		else
			return NULL;
	}

	function is_set($s) // 有没有存在对应的内容
	{
		if (isset($_REQUEST[$s]))
			return 1;
		else if (isset($_COOKIE[$s]))
			return 2;
		else return 0;
	}

	function enull($s) // 表单为空时也是NULL
	{
		if ($s == NULL || trim($s) == "")
			return NULL;
		else
			return $s;
	}

	function fuck($s = "") // 全部操作退出
	{
		die("00000");
	}

?><?php // 安全验证操作

	function check_account()
	{
		;
	}

	function verify()                     // 开始验证，版本号是必须的
	{
		;
	}

	function reverify()                   // 发送加密后的验证码
	{
		echo verify_encode() . "\n";
	}


	function verify_uncode($s)            // 解密
	{
		;
	}

	function verify_encode()              // 返回加密后的验证码
	{
		;
	}

	function format_input($s, $blank = 0) // 格式化输入内容
	{
		if (!$blank)
			$s = trim($s);         // 去空格
		$s = stripslashes($s);     // 去转义
		$s - htmlspecialchars($s); // 防注入
		return $s;
	}

?><?php // 输出控制操作
	function println($s)                // 输出一行
	{
		echo $s . "\n";
	}

	function printres($b)               // 输出结果，OK / Bad
	{
		if ($b)
		{
			echo T . "\n";
			return 1;
		}
		else
		{
			echo F . "\n";
			return 0;
		}
	}

	function die_if($b, $s = "")        // 必须OK，否则结束程序
	{
		if ($b)
		{
			die($s);
		}
	}

	function fecho($b, $s = "操作失败") // 失败时输出错误内容
	{
		if (!$b)
		{
			echo $s;
		}
	}

	function dbout($s, $line = 1)
	{
		global $DeBugModel;
		if ($DeBugModel)
		{
			echo $s;
			if ($line) echo "\n";
		}
	}

?><?php // 各种取值操作

	function getIP() // 获取真实的IP
	{
		$ip = "";
		if (getenv('HTTP_CLIENT_IP'))
		{
			$ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR'))
		{
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED'))
		{
			$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR'))
		{
			$ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED'))
		{
			$ip = getenv('HTTP_FORWARDED');
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function gettime() // 获取当前时间文本
	{
		date_default_timezone_set('PRC'); // 临时设置成中国时区
		$time = date("y-m-d h:i:s", time());
		return $time;
	}

?><?php // 数据库操作

	function connect_sql() // 连接数据库
	{
		global $con, $is_connected;
		if ($is_connected) // 避免多次连接
		{ return NULL; }

		$con = mysql_connect(MySQL_servername, MySQL_username, MySQL_password);
		if (!$con)
		{ die("数据库连接失败"); }

		$is_connected = 1;
		
//		mysql_query("SET NAMES 'utf8'");

		// 选择库
		mysql_select_db(MySQL_database, $con);

		return $con;
	}

	function query($sql) // 查询语句
	{
		global $con, $is_connected;
		if (!$is_connected)
		{
			connect_sql();
			$is_connected = 1;
		}

		$result = mysql_query($sql, $con);
		return $result;
	}

	function row($sql) // 查询一行，数据是否存在
	{
		global $con, $is_connected;
		if (!$is_connected)
		{
			connect_sql();
			$is_connected = 1;
		}

		if ($result = mysql_query($sql))
		{
			$row = mysql_fetch_array($result);
			return $row;
		}
		else
		{
			return NULL;
		}
	}

	function sqlsafe($s)
	{
		$s = str_replace("'", "''", $s);
		return $s;
	}

	function error()
	{
		global $con, $is_connected;
		if (!$is_connected)
		{
			connect_sql();
			$is_connected = 1;
		}

		return mysql_error();
	}
	
?>