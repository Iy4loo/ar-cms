<?php 
session_start();
if(isset($_GET['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    $count = 0;// = even -> username; = odd -> pswd,
    $handle = fopen("userdata.txt", "r");
    $login_success = 0;
    if ($handle) {
        $lgn_correct = 0;
        $psw_correct = 0;
        while (($line = fgets($handle)) !== false) {
            $line =  preg_replace('/[^A-Za-z0-9\-]/', '', $line);
            if(($count % 2) == 0){$lgn_correct = 0;}
            if(($count % 2) == 1){$psw_correct = 0;}

            if(strcmp($line, $email) == 0 && ($count % 2) == 0){//every even line is a username
                $lgn_correct = 1;
            }
            if(strcmp($line, $passwort) == 0 && ($count % 2) == 1){//every odd line is a password
                $psw_correct = 1;
            }
            if( $psw_correct == 1 &&  $lgn_correct == 1){// success!
                $_SESSION['user'] = password_hash($email, PASSWORD_DEFAULT); 
                $_SESSION['psw'] = password_hash($passwort, PASSWORD_DEFAULT); 
                fclose($handle);
                echo "<script>top.window.location = '/index.php'</script>";
                die;
                $login_success = 1;
                break;
            }
            $count++;
        }
        if($login_success == 0){session_destroy();die( '-Wrong password or username, please try again.');}
        fclose($handle);
    }else{die( '<br>-Error while checking the password has occuried!');}
    

}
?>


<!DOCTYPE html> 
<html> 
<head>
  <title>Login</title>    
</head> 
<body>
 
<?php 
if(isset($errorMessage)) {
    echo $errorMessage;
}
?>

<form action="?login=1" method="post">
Login:<br>
<input type="login" size="40" maxlength="250" name="email"><br><br>
 
Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>
 
<input type="submit" value="Login">

</form> 
</body>
</html>













<style type="text/css">
  label {
    width: 10em;
    display: block;
    float: center;
    text-align: right;
    padding-right: 10px;
  }

  form {
    background-color: #EDEEF0;
    padding-left: 50px;
    padding-right: 50px;
    padding-top: 20px;
    border: 1px solid silver;
}
input[type=submit] {
  border: none; /*rewriting standard style, it is necessary to be able to change the size*/
  height: 40px;
  width: 80px;
  float: center;
  background:#5183B7; 
  color: white;
  cursor:pointer;
  font-size: 15px;
  border-radius: 8px;
  -moz-border-radius: 8px;
}

body {   
  color: ##222222;

}  


legend{color:#285572}
</style>