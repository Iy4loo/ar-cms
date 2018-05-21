<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>

  <title>CMS</title>

  <script language="JavaScript">
<!--
function testOnload(obj) {
 document.getElementById("frameset").rows=document.getElementById("top").contentWindow.document.body.scrollHeight+ 'px, *';
}

//-->
</script>




</head>


  <frameset rows="1%, *" onLoad="javascript:testOnload(this)" id="frameset">
    <frame src="topframe.php" name="top" id="top">
    <frameset cols="80%,*">
        <frame src="formular.php" name="content">
  	    <frame src="help_page.html" name="help">
    </frameset>

  </frameset>
</html>


