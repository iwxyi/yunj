<?php

?><html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="renderer" content="webkit" />
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="theme-color" content="slateblue" />
	
	<title>云笺</title>
	
	<link rel="stylesheet" href="css/mdui.min.css" />
	<script src="js/mdui.min.js"></script>
	<script src="js/yunj.js"></script>
	<link rel="stylesheet" href="css/yunj.css" />
	<!--<script src="js/jquery.min.js"></script>-->
</head>
<body class="mdui-appbar-with-toolbar yunj-background" ><!-- onbeforeunload="return false;" -->
	
	<!--滚动响应，未设置-->
	<!--<div mdui-headroom style="position: fixed; top: 0; left: 0; right: 0; height: 30px;"></div>-->
	
	<!--应用栏-->
	<div class="mdui-appbar mdui-appbar-fixed">
		<div class="mdui-toolbar mdui-color-indigo">
			<a mdui-drawer="{target: '#left-drawer'}" class="mdui-btn mdui-ripple mdui-btn-icon"><i class="mdui-icon material-icons">menu</i></a>
			<a mdui-drawer="{target: '#left-drawer'}" class="mdui-typo-headline mdui-hidden-sm-down">云笺</a>
			
			<div class="mdui-toolbar-spacer mdui-hidden-sm-down"></div>
			
			<!--笔记名字-->
			<a class="mdui-typo-headline"><input class="mdui-textfield-input" id="name" onkeydown="nameClick();" type="text" value="<?php global $name; echo $name; ?>" style="text-align: center; color: white; border: none; font-size: 22px; min-width: 10%;" placeholder="当前页入口名" /></a>
			
			<div class="mdui-toolbar-spacer"></div>
			
			<a onclick="saveInfo();" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">save</i></a>
			<a onclick="openShare();" target="_blank" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">send</i></a>
			<a mdui-menu="{target: '#menu'}" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">more_vert</i></a>
			
			<!--菜单-->
			<ul class="mdui-menu" id="menu">
				
				<!--<li class="mdui-menu-item">
					<a onclick="refresh();" class="mdui-ripple">
						<i class="mdui-menu-item-icon mdui-icon material-icons">refresh</i>刷新
					</a>
				</li>-->
				
				<li class="mdui-menu-item">
					<a onclick="page_next();" class="mdui-ripple">
						<i class="mdui-menu-item-icon mdui-icon material-icons">navigate_next</i>下一页
					</a>
				</li>
				
				<li class="mdui-menu-item">
					<a onclick="openHistory();" class="mdui-ripple">
						<i class="mdui-menu-item-icon mdui-icon material-icons">history</i>保存历史
					</a>
				</li>

				<li class="mdui-divider"></li>

				<li class="mdui-menu-item">
					<a onclick="switch_sync();" class="mdui-ripple">
						<i class="mdui-menu-item-icon mdui-icon material-icons">sync</i>实时同步
					</a>
				</li>
				
				<li class="mdui-divider"></li>
				
				<li class="mdui-menu-item">
					<a onclick="deleteNote();" class="mdui-ripple">
						<i class="mdui-menu-item-icon mdui-icon material-icons">delete</i>删除
					</a>
				</li>
			</ul>
		</div>
	</div>
	
	
	<!--抽屉式导航-->
	<div class="mdui-drawer mdui-drawer-close" id="left-drawer">
		<ul class="mdui-list" mdui-collapse="{accordion: true}">
			<!--<li class="mdui-list-item mdui-ripple">
				<i class="mdui-list-item-icon mdui-icon material-icons">home</i>
				<div class="mdui-list-item-content" onclick="openYunj2('n=');">云笺</div>
			</li>
		
			<li class="mdui-list-item mdui-ripple">
				<i class="mdui-list-item-icon mdui-icon material-icons">format_list_bulleted</i>
				<div class="mdui-list-item-content" onclick="openContent();">目录</div>
			</li>-->
		
			<li class="mdui-collapse-item mdui-collapse-item-open">
				<div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
					<i class="mdui-list-item-icon mdui-icon material-icons">access_time</i>
					<div class="mdui-list-item-content">最近编辑</div>
					<i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
				</div>
				<ul class="mdui-collapse-item-body mdui-list mdui-list-dense">
					<li class="mdui-list-item mdui-ripple" onclick="openContent();">
						<i class="mdui-list-item-icon mdui-icon material-icons">format_list_bulleted</i>笺目录
					</li>
					<?php
						$pat = "/^.+_\d{1,3}$/";
						if (preg_match($pat, $name, $ans)) // 上一页 下一页
						{
							$pos = strrpos($name, "_")+1; // 倒找 数字 的位置
							$pagenum = substr($name, $pos, strlen($name)-$pos);
							$pagename = substr($name, 0, $pos);
							if ($pagenum > 0)
								echo "<li class='mdui-list-item mdui-ripple' onclick='openYunj2(" . '"' . "n=" . $pagename . ($pagenum-1) . '"' . ");'><i class='mdui-list-item-icon mdui-icon material-icons'>navigate_before</i>上一页 " . ($pagenum-1) . "</li>";
							echo "<li class='mdui-list-item mdui-ripple' onclick='openYunj2(" . '"' . "n=" . $pagename . ($pagenum+1) . '"' . ");'><i class='mdui-list-item-icon mdui-icon material-icons'>navigate_next</i>下一页 " . ($pagenum+1) . "</li>";
						}
					
						$start = seizecookie('recent_start');
						$end = seizecookie('recent_end');
						$havediv = 0;
						$alltimes = 0;
						for ($i = $end-1; $i >= $start; $i--)
						{
							$alltimes++;
							$cname = seizecookie('recent_' . $i);
							if ($cname != null && $cname != "")
							{
								if (!$havediv)
								{
									$havediv = 1;
									echo "<div class='mdui-divider'></div>";
								}
								echo "<li class='mdui-list-item mdui-ripple' onclick='openYunj2(\"n=$cname\");'>$cname</li>";
							}
							else if ($alltimes > 100) // 没用的次数太多了
								break;
						}
					?>
				</ul>
			</li>
			
			<li class="mdui-collapse-item">
				<div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
					<i class="mdui-list-item-icon mdui-icon material-icons">help</i>
					<div class="mdui-list-item-content">使用帮助</div>
					<i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
				</div>
				<ul class="mdui-collapse-item-body mdui-list mdui-list-dense">
					<li class="mdui-list-item mdui-ripple" onclick="openYunj('r=yunj/page');">页码</li>
					<li class="mdui-list-item mdui-ripple" onclick="openYunj('r=yunj/programmar');">语法</li>
					<li class="mdui-list-item mdui-ripple" onclick="openYunj('r=yunj/about');">有关云笺</li>
					<li class="mdui-list-item mdui-ripple" onclick="openYunj('r=yunj/terms');">使用条款</li>
				</ul>
			</li>
			
			<li class="mdui-collapse-item">
				<div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
					<i class="mdui-list-item-icon mdui-icon material-icons">device_hub</i>
					<div class="mdui-list-item-content">其他</div>
					<i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
				</div>
				<ul class="mdui-collapse-item-body mdui-list mdui-list-dense">
					<li class="mdui-list-item mdui-ripple"><?php // 打开次数
	                    global $name;
	                    if (($row = row("SELECT * FROM notes WHERE name = '$name'")) != NULL && $row['read'] > 0)
	                        echo $row['open'] . " 次打开";
						else echo "未曾打开";
	                ?></li>
	                <li class="mdui-list-item mdui-ripple"><?php // 保存次数
	                    global $name;
	                    if (($row = row("SELECT * FROM notes WHERE name = '$name'")) != NULL && $row['read'] > 0)
	                        echo $row['save'] . " 次保存";
						else echo "未曾保存";
	                ?></li>
					<li class="mdui-list-item mdui-ripple"><?php // 分享阅读次数
	                    global $name;
	                    if (($row = row("SELECT * FROM notes WHERE name = '$name'")) != NULL && $row['read'] > 0)
	                        echo ($row['read'] - $row['share']) . " 次阅读";
						else echo "未曾分享";
	                ?></li>
					<li class="mdui-list-item mdui-ripple" onclick="openURL('http://coolapk.com/apk/com.MZFY');">码字风云</li>
				</ul>
			</li>
		
			<!--<li class="mdui-list-item mdui-ripple">
				<i class="mdui-list-item-icon mdui-icon material-icons">exit_to_app</i>
				<div class="mdui-list-item-content">退出</div>
			</li>-->
		
		</ul>
		
	</div>
	
	
	<!--FAB 悬浮按钮-->
	<!--<div class="mdui-fab-wrapper" id="fab">
		<button class="mdui-fab mdui-ripple mdui-color-theme-accent">
			<i class="mdui-icon material-icons">add</i>
			<i class="mdui-icon mdui-fab-opened material-icons">close</i>
		</button>
		<div class="mdui-fab-dial">
			<button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red"><i class="mdui-icon material-icons">bookmark</i></button>
			<button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-orange"><i class="mdui-icon material-icons">navigate_next</i></button>
			<button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-blue"><i class="mdui-icon material-icons">add</i></button>
		</div>
	</div>-->
	
	
	<!--SnackBar-->
	
	
	<!--正式内容-->
	<div class="mdui-card" style="top: 1.2%; left: 2%; width: 96%;">
		<div class="mdui-textfield" style="left: 2%; width: 96%;">
			<textarea class="mdui-textfield-input" id="info" onkeydown="infoClick();" placeholder="当前页内容，alt+S保存" style="min-height: 92%; border: none;"><?php // 读取信息
                global $name, $info;
                    if ($info == NULL)
                        if (($row = row("SELECT * FROM notes WHERE name = '$name'")) != NULL)
                            $info = $row['info'];
                    echo $info;
                ?></textarea>

	<footer style="text-align: center;"><a href="http://www.beian.miit.gov.cn">浙ICP备17046339号-5</a></footer>

		</div>
	</div>

	<script>
		var initname = "<?php global $name; echo urlencode($name); ?>";
		var initinfo = "<?php global $info; if ($info != null) echo urlencode($info);?>";
		initname = decodeURI(initname);
		initinfo = decodeURI(initinfo);
		if (initname == "")
			document.getElementById('name').focus();
		else
			document.getElementById('info').focus();
		
		var fab = new mdui.Fab('#fab'); // 初始化FAB
		var drawer = new mdui.Drawer('#left-draw', {swipe:true}); // 初始化抽屉导航
		var $$ = mdui.JQ;
		
		save_text = document.getElementById('info').focus();
	</script>


</html>