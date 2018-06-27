<HTML>
<HEAD>
    <META NAME="GENERATOR" Content="Microsoft Visual Studio">
    <TITLE>云笺 <?php echo $name; ?></TITLE>
</HEAD>
<BODY>

    <link type="text/css" rel="stylesheet" href="SyntaxHighlighter/Styles/SyntaxHighlighter.css" />
    <script language="javascript" src="SyntaxHighlighter/Scripts/shCore.js"></script>
    <script language="javascript" src="SyntaxHighlighter/Scripts/shBrushCSharp.js"></script>

    <script language="javascript">
        window.onload = function ()
        {
            dp.SyntaxHighlighter.ClipboardSwf = 'SyntaxHighlighter/Scripts/clipboard.swf';
            dp.SyntaxHighlighter.HighlightAll('code');
        }
    </script>

    <pre name="code" class="c-sharp"><?php echo $info; ?></pre>
</BODY>
</HTML>