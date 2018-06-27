<html lang="zh" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="blue">

    <TITLE>羽·笔记 <?php echo $name; ?></TITLE>
    <link rel="stylesheet" type="text/css" href="css/sidebar.css" />
    <link rel="stylesheet" type="text/css" href="css/edit_note.css" />
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/edit_note.js"></script>
    <script type="text/javascript" src="js/edit_head.js"></script>
</head>

<body>

    <form action="index.php" method="post">
        <div class="bar">
        <a class="save" id="save" onclick="saveInfo();">　　保存</a>
            <div align="center" id="hheader" onmouseover="$('.hbutton').show(); $('.hbutton').removeClass('hbtnHide'); $('.hbutton').addClass('hbtnShow');" onmouseout="$('.hbutton').removeClass('hbtnShow'); $('.hbutton').addClass('hbtnHide');">
                
                <a  class="hbutton" id="hbtn1" name="sub_reflash" href="<?php global $name; echo 'index.php?n=' . urlencode($name); ?>" hint="刷新（不保存）"><img class="himg" src="img/reflash.png" /></a>
                
                <input type="text" id="name" name="name" class="name" placeholder="笔记名" value="<?php global $name; echo $name; ?>" onmouseover="this.style.backgroundColor='#44b6ec'" onmouseout="this.style.background='transparent'" />
                
                <a class="hbutton" id="hbtn2" name="sub_share" href="<?php echo getShareUrl(); ?>" target="_blank" onclick="saveInfo()" hint="分享"><img class="himg" src="img/share.png" /></a>
                <!--<input type="submit" class="save" id="botton" name="sub_keep" value="保存" onclick="return saveInfo()" />-->
            </div>
        </div><!-- 顶栏 -->

        <div class="menu-espanso"><!-- 侧栏 -->

            <div id="menu" class="menu"></div>

                <div class="voci-menu">
                    <ul>
                        <li><a href="<?php echo getShareUrl(); ?>" target="_blank">　　　分享此页　　　</a></li>
                        <li><a href="<?php global $name; echo 'index.php?h=1&n=' . urlencode($name); ?>" target="_blank">　　　查看历史　　　</a></li>
                        <!-- <li><a onclick="window.clipboardData.setData('Text', '<?php echo getShareUrl(); ?>');alert('复制分享网址成功！');">　　　复制链接　　　</a></li> -->
                        <li><a><?php // 分享阅读次数
                                    global $name;
                                    if (($row = row("SELECT * FROM notes WHERE name = '$name'")) != NULL && $row['read'] > 0)
                                        echo "　　　" . $row['read'] . " 次阅读　　　";
                                ?></a></li>
                        <li><a href="index.php?r=yunj/help" target="_blank">　　　　帮助　　　　</a></li>
                        <li><a href="index.php?r=yunj/about" target="_blank">　　　　关于　　　　</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="cont-txt"> <!-- 正文 -->
            <textarea name="info" id="info" placeholder="请输入任意内容，再点保存按钮。下次进入时会读取"><?php // 读取信息
                global $name, $info;
                    if ($info == NULL)
                        if (($row = row("SELECT * FROM notes WHERE name = '$name'")) != NULL)
                            $info = $row['info'];
                    echo $info;
                ?></textarea><br />
        </div>

        <!-- <div class="footer"> <!- 底部菜单，一个div一列 ->
        <div> 
            <ul>
                <li><span>Footerasdasd</span></li>
            </ul>
        </div>-->
    </form>

    <script type="text/javascript">
        //<![CDATA[
        window.jQuery || document.write('<script src="js/jquery-2.1.1.min.js"><\/script>')
        //]]>
    </script>
    <script type="text/javascript">
        $(function(){
            $('.menu , .linee').on('click', function() {
              $('.menu').toggleClass('over')
              $('.linea1').toggleClass('overL1')
              $('.linea2').toggleClass('overL2')
              $('.linea3').toggleClass('overL3')
              $('.voci-menu').toggleClass('overvoci')
            });
        })
    </script>
    <script type="text/javascript" src="js/ani.js"></script>

    <!-- <script type="text/javascript" src="http://blogparts.giffy.me/0013/parts.js"></script> -->

</body>
</html>