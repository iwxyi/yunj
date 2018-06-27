var XHR;

function saveInfo()
{
    clearTimeout(save_t); // 取消上次保存的提示

    var FD = new FormData();
    var name = document.getElementById("name").value;
    var info = document.getElementById("info").value;
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
            //alert("提交成功");
            //document.getElementById("myDiv").innerHTML = XHR.responseText;
        }
    }

    XHR.open('POST', 'http://writerfly.cn/yunotes/index.php', true);

    XHR.send(FD);

    var save = document.getElementById('save');
    if (save === null) alert("null");
    save.innerHTML="　保存成功";

    var save_t = setTimeout("document.getElementById('save').innerHTML='　　保存　'",2000);

    return false;
}


