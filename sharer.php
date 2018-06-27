<?php
	if (stripos($info, "<HTML>") !== false && stripos($info, "<HTML>") < 5) // HTML
	{
		echo $info;
	}
	else if (stripos($info, "<TEXT>") !== false && stripos($info, "<TEXT>") < 5) // TEXT
	{
		header("Content-type: text/plain; charset=utf-8"); // 纯文本
		$info = substr($info, stripos($info, "<TEXT>")+6, strlen($info)-6);
		cutRN($info);
		echo $info;
	}
	else if (stripos($info, "<CODE>") !== false && stripos($info, "<CODE>") < 5) // CODE
	{
		$info = substr($info, stripos($info, "<CODE>")+6, strlen($info)-6);
		cutRN($info);
		if (stripos($info, "<") !== false && stripos($info, "<") < 3 && stripos($info, ">") > stripos($info, "<") + 1 && stripos($info, ">") < 10) // CODE + LANGUAGE
		{
			$left = stripos($info, "<");
			$right = stripos($info, ">");
			$codelang = substr($info, $left+1, $right - $left-1);
			$info = substr($info, $right+1, strlen($info)-$right-1);
			cutRN($info);
			$info = getPureText($info, 0);
			require "coder.php";
		}
		else
		{
			$info = getPureText($info, 0);
			require "code.php";
		}
	}
	else if (stripos($info, "<MD>") !== false && stripos($info, "<MD>") < 5) // MarkDown
	{
		$info = substr($info, stripos($info, "<MD>")+4, strlen($info)-4);
		cutRN($info);
		echo $info;
	}
	else if (stripos($info, "<MARKDOWN>") !== false && stripos($info, "<MARKDOWN>") < 5) // MarkDown
	{
		$info = substr($info, stripos($info, "<MD>")+10, strlen($info)-10);
		cutRN($info);
		echo $info;
	}
	else if (stripos($info, "<URL>") !== false && stripos($info, "<URL>") < 5) // URL
	{
		$info = trim(substr($info, stripos($info, "<URL>")+5, strlen($info)-stripos($info, "<URL>")-5));
		$pos = stripos($info, "</URL>");
		$pos0 = stripos($info, "\n");
		if ($pos === false && $pos0 === false)
			$pos = strlen($info);
		else if ($pos > $pos0 && $pos0 !== false)
			$pos = $pos0;
		else if ($pos === false && $pos0 !== false)
			$pos = $pos0;
		$info = trim(substr($info, 0, $pos));
		if (strpos($info, "://") === false)
			$info = "http://" . $info;
		echo $info;
		header("Location: $info");
	}
	else 
	{
		if (stripos($info, "<TITLE>") === false)
			echo "<title>云笺 - 分享</title>";
		
		echo getPureText($info);
	}

	function cutRN(&$s)
	{
		if (ord(substr($s, 0, 1)) == 10) // \n
		{
			$s = substr($s, 1, strlen($s)-1);
			/*if (ord(substr($s, 0, 1)) == 13)
				$s = substr($s, 1, strlen($s)-1);*/
		}
		else if (ord(substr($s, 0, 1)) == 13) // \r\n
		{
			$s = substr($s, 1, strlen($s)-1);
			if (ord(substr($s, 0, 1)) == 10)
				$s = substr($s, 1, strlen($s)-1);
		}
		return $s;
	}

	function getPureText($s, $table = 1/*是否增加标签*/)
	{
		$len     = strlen($s);
		$inAngle = 0;
		$inRound = 0;
		$istext  = 0;
		$res     = "";

		for ($i = 0; $i < $len; $i++)
		{
			$c = substr($s, $i, 1);

			if ($i < $len-1) $cl = substr($s, $i+1, 1);
			else $cl = "";

			if ($c == ' ')
			{
				if (!$inAngle)
					$res .= "&nbsp;";
				else
					$res .= " ";
			}
			else if ($c == "\n")
			{
				/*if ($cl == "#")
					$istext = 1;
				else
					$istext = 0;*/

				if ($table && ($istext || !$inAngle))
					$res .= "<br />";
				else
					$res .= "\n";
				$inAngle = $inRound = $istext = 0;
			}
			else if ($c == "#")
			{
				$istext = 1;
				$res .= "#";
			}
			else if ($c == "\t")
			{
				if (!$inAngle)
					$res .= "&nbsp;&nbsp;&nbsp;&nbsp;";
				else
					$res .= "\t";
			}
			else if ($c == "(")
			{
				$inRound++;
				$res .= "(";
			}
			else if ($c == ")")
			{
				if ($inRound > 0)
					$inRound--;
				$res .= ")";
			}
			else if ($c == "<")
			{
				if ($istext || $inRound)
					$res .= "&lt;";
				else
				{
					if ($cl == "<")
					{
						$res .= "&lt;&lt;";
						$i++;
					}
					else
					{
						$res .= "<";
						$inAngle++;
					}
				}
			}
			else if ($c == ">")
			{
				if ($cl == ">")
				{
					$res .= "&gt;&gt;";
					$i++;
				}
				else if ($istext || $inAngle <= 0 || $inRound)
					$res .= "&gt";
				else
					$res .= ">";
				if ($inAngle > 0)
					$inAngle--;
			}
			else
			{
				$res .= $c;
			}
		}
		return $res;
	}
?>