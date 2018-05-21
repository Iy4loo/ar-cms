<?php 

if(isset($_GET['logout'])) {
  session_start();
  session_destroy();
  echo "<script>top.window.location = '/index.php'</script>";
die;
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">

<html>



<body style="background-color:#C63B0A;">
<body background="auob.png"/>
<label>Content Managment System </label>
  <br>
  Auob Country Lodge AR App
   <br>

<!--<img src="auob.png" align="right">!-->

</body>
</html>


<?php
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['psw'])): ?>
    <a href="login.php" target="content">Login</a>
<?php else : ?>
  <a href="formular.php" target="content">Upload</a>
  <a href="overview.php" target="content">Overview</a>
  <a href="newmarker.php" target="content">Add New Marker</a>
  <a href="customercontent.php" target="content">Add Customer Media</a>
  <a href="?logout=1">Logout</a>
<?php endif; ?>







<style type="text/css">
body{
    color: #D7E2EC;
}
label{ 
    font-size: 25px;
    color: white;
    font-family:Arial;
}
h1{
   padding-bottom: 0px;
}
a:visited { 
    color: #F0F8FF;
}
a {
    color: #F0F8FF;
}
</style>
