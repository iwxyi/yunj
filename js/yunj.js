var sync = false;
var sync_auto = true;
var sync_last_text = "";
var save_time = 0;
var save_text = "";

function remindBeforeClose(){
	if (document.getElementById("info").value != save_text)
		alert("0");
	else
		alert("1");

	if (document.getElementById("info").value != save_text)
    	return '您可能有数据没有保存';
    else
    	return false;
}

/*window.onbeforeunload = function(event){
	event.returnValue = "您有数据未保存";
	return false;
	
	if (document.getElementById("info").value != save_text)
    	return '您可能有数据没有保存';
    else
    	return false;
}*/

function nameClick() {
	var e = event || window.event || arguments.callee.caller.arguments[0];
	if (!e) return ;
	if (e.keyCode == 13) // 回车
	{
		if (document.getElementById("name").value == initname && document.getElementById("info").value == initinfo)
			document.getElementById("info").focus();
		else
			refresh();
	}
	else if (e.keyCode == 9 && !(e.altKey || e.ctrlKey || e.shiftKey)) // tab
	{
		document.getElementById("info").focus();
		e.preventDefault();
		e.returnValue = false;
		return false;
	}
	else if (e.keyCode == 83 && (e.altKey || e.ctrlKey) ) // 保存
	{
		saveInfo();
		e.preventDefault();
		e.returnValue = false;
		return false;
	}
}

function infoClick() {
	var e = event || window.event || arguments.callee.caller.arguments[0];
	// alert(e.keyCode);
	if (!e) return ;
	if (e.keyCode == 83 && (e.altKey || e.ctrlKey) ) // 保存
	{
		saveInfo();
		e.preventDefault();
		e.returnValue = false;
		return false;
	}
	else if (e.keyCode == 9 && !(e.altKey || e.ctrlKey || e.shiftKey)) // tab
	{
		e.preventDefault();
		e.returnValue = false;
		return false;
	}
}

function stopDefault( e ) {
   // Prevent the default browser action (W3C)
   if ( e && e.preventDefault )
      e.preventDefault();
   else
   // A shortcut for stoping the browser action in IE
      window.event.returnValue = false;
   return false;
}

function saveInfo() {
	var XHR;
	var FD = new FormData();
	var name = document.getElementById("name").value;
	var info = document.getElementById("info").value;
	
	if (name == "") return ;
	// 去掉首尾空格
	var noblank_name = name.replace(/(^\s*)|(\s*$)/g,"");
	if (noblank_name != name){
		name = noblank_name;
		document.getElementById("name").value = name;
	}
	
	XHR = null;
	
	if (window.XMLHttpRequest)
	{
		XHR = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		XHR = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	FD.append("name", name);
	FD.append("info", info);
	
	XHR.onreadystatechange = function ()
	{
		if (XHR.readyState == 4 && XHR.status == 200)
		{
			mdui.snackbar({
				message: '保存成功',
				buttonText: '历史',
				onButtonClick: function() {
					window.open("index.php?h=1&n=" + encodeURI(name));
				},
			});

			save_time = (new Date()).getTime();
			save_text = info;
		}
	}
	
	XHR.open('POST', 'index.php', true);
	
	XHR.send(FD);
	
	document.getElementById('info').focus();
	
	return false;
}

function deleteNote()
{	
	var name = document.getElementById("name").value;
	if (name == "") return ;
	
	/*window.location.href="index.php?sub_del=1&n=" + encodeURI(name);
	return ;*/
	/*if (name != initname) // 修改了名字，不能确定是要删除新的还是旧的
	{
		mdui.snackbar({
				message: '已修改名字，请刷新后再试',
				buttonText: '刷新',
				onButtonClick: function() {
					refresh();
				},
			});
		return ;
	}*/
	
	var XHR = null;
	var FD = new FormData();
	
	// 去掉首尾空格
	var noblank_name = name.replace(/(^\s*)|(\s*$)/g,"");
	if (noblank_name != name){
		name = noblank_name;
		document.getElementById("name").value = name;
	}
	
	if (window.XMLHttpRequest)
	{
		XHR = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		XHR = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	FD.append("name", name);
	FD.append("sub_del", "1");
	
	XHR.onreadystatechange = function ()
	{
		if (XHR.readyState == 4 && XHR.status == 200)
		{
			mdui.snackbar({
				message: '删除成功',
				buttonText: '撤销',
				onButtonClick: function() {
					saveInfo();
				},
			});
		}
	}
	
	XHR.open('POST', 'index.php', true);
	
	XHR.send(FD);
	
//	deleteNoteCookie(name);
	
	return false;
}

function refresh() {
	var name = document.getElementById("name").value;
	if (name == "") return ;
	window.location.href="index.php?n=" + encodeURI(name);
	
	return false;
}

function openHistory() {
	var name = document.getElementById("name").value;
	if (name == "") return ;
	window.open("index.php?h=1&n=" + encodeURI(name));
	
	return false;
}

function openContent() {
	var name = document.getElementById("name").value;
	if (name == "") return ;
	var url = "";
	var pat = /(.+_)\d{1,2}/;
	if (pat.test(name))
		url = name.slice(0, name.lastIndexOf('_')+1);
	else if (name.lastIndexOf('_') == name.length-1)
		url = name;
	else
		url = name + "_";
	
	window.open("index.php?n=" + encodeURI(url));
	
	return false;
}

function openYunj(s) {
	window.open("index.php?" + encodeURI(s));
	
	return false;
}

function openYunj2(s) {
	if (document.getElementById("info").value == save_text) // 已经保存，在本页打开
	{
		window.open("index.php?" + encodeURI(s));
	}
	else // 未保存，新页面打开
	{
		window.location.href="index.php?" + encodeURI(s);
	}

	return false;
}

function openURL(s) {
	window.open(s);
	
	return false;
}

function openShare() {
	var name = document.getElementById("name").value;
	if (name == "") return false;
	openYunj("r=null&n=" + encodeURI(name));
}

function page_next() {
	var name = document.getElementById("name").value;
	if (name == "") return ;
	var url = "";
	var pat = /(.+_)\d{1,2}/;
	if (pat.test(name))
	{
		url = name.slice(0, name.lastIndexOf('_')+1);
		pagenum = name.slice(name.lastIndexOf("_")+1, name.length);
		pagenum = parseFloat(pagenum) + 1;
		url = url + pagenum;
	}
	else if (name.lastIndexOf('_') == name.length-1)
		url = name + "1";
	else
		url = name + "_1";
	
	openYunj2("index.php?n=" + encodeURI(url));
	
	return false;
}

function switch_sync() // 开关实时同步
{
	if (sync == false)
	{
		sync = true;

		mdui.snackbar({
						message: '已开启当前页同步',
						buttonText: '帮助',
						onButtonClick: function() {
							;
						},
					});

		sendAjax();
	}
	else
	{
		sync = false;

		mdui.snackbar({
						message: '已关闭当前页同步',
						buttonText: '帮助',
						onButtonClick: function() {
							;
						},
					});
	}
}


function sendAjax()
{
	if (sync == false)
		return ;

	var name = document.getElementById("name").value;
	var info = document.getElementById("info").value;

	if (name == "") return ;
	
	var XHR = null;
	var FD = new FormData();
	
	// 去掉首尾空格
	var noblank_name = name.replace(/(^\s*)|(\s*$)/g,"");
	if (noblank_name != name){
		name = noblank_name;
		document.getElementById("name").value = name;
	}
	
	if (window.XMLHttpRequest)
	{
		XHR = new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		XHR = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	FD.append("name", name);
	FD.append("info", info);
	FD.append("info2", sync_last_text);
	
	XHR.onreadystatechange = function ()
	{
		if (XHR.readyState == 4 && XHR.status == 200)
		{
			if (sync == false) return ; // 连接中关闭
			var new_info = XHR.responseText; // 新的信息
			// new_info = new_info.replace(/\r/, ""); // 返回的信息莫名多了 \r，还不能直接去掉
			new_info = decodeURI((encodeURI(new_info)).replace(/%0D/g, ""));

			// alert(info.length + "  " + new_info.length + "\n上传:【" + (info) + "】\n\n返回:【" + (new_info) + "】\n\n现在:【" + (document.getElementById("info").value) + "】");

			if (new_info == "") // 时间到、空文本，继续AJAX
			{
				;
			}
			else if (document.getElementById("name").value != name) // 已修改名字，发送新的AJAX
			{
				;
			}
			else if (info == new_info) // 内容一样，自己保存 *******这一块还有问题*******
			{
				;
			}
			else if (document.getElementById("info").value == new_info) // 后来添加的信息一样
			{
				;
			}
			else if (sync_last_text == new_info) // 收到上次一样的数据
			{
				;
			}
			else if ((new Date()).getTime() <= save_time + 3000) // 3秒钟内保存的，可能是自己的
			{
				;
			}
			else if (save_text == new_info) // 收到自己的内容
			{
				;
			}
			else
			{
				var myDate = new Date();

				if (sync_auto == true  // 自动修改
					&& document.getElementById("info").value == save_text // 文本和上次保存的内容相同，即没有自动修改
					)
				{
					document.getElementById("info").value = new_info;
					save_text = new_info;

					mdui.snackbar({
									message: '已更新 '+myDate.getHours()+":"+myDate.getMinutes()+":"+myDate.getSeconds(),
									buttonText: '撤销并关闭自动修改',
									onButtonClick: function() {
										document.getElementById("info").value = info;
										save_text = info;
										sync_auto = false;
									},
								});
				}
				else
				{
					mdui.snackbar({
									message: '收到更新'+myDate.getHours()+":"+myDate.getMinutes()+":"+myDate.getSeconds(),
									buttonText: '修改',
									onButtonClick: function() {
										document.getElementById("info").value = new_info;
										save_text = new_info;
									},
								});
					
				}
			}

			sync_last_text = new_info;

			sendAjax(); // 继续AJAX
		}
	}
	
	XHR.open('POST', 'sync_ajax.php', true);
	
	XHR.send(FD);
	
	// document.getElementById('info').focus();
	
	return false;
}
