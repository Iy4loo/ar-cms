<?php

session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['psw'])) {
    die('Please <a href="login.php">sign in</a>.');
}

//Code to check if user has an active session or the login was valid.
$login_success = 0;

if(isset($_SESSION['user']) && isset($_SESSION['psw'])){
    $handle = fopen("userdata.txt", "r");
    $count = 0;// = even -> username; = odd -> pswd
    $lgn_correct = 0;
    $psw_correct = 0;
    while (($line = fgets($handle)) !== false) {
            $line =  preg_replace('/[^A-Za-z0-9\-]/', '', $line);
            if(($count % 2) == 0){$lgn_correct = 0;}
            if(($count % 2) == 1){$psw_correct = 0;}

            if(password_verify ( $line , $_SESSION['user'] ) && ($count % 2) == 0){//every even line is a username
                $lgn_correct = 1;
            }
            if(password_verify ( $line , $_SESSION['psw'] ) && ($count % 2) == 1){//every even line is a username
                $psw_correct = 1;
            }
            if( $psw_correct == 1 &&  $lgn_correct == 1){// success!
                $login_success = 1;
                break;
            }
            $count++;
    }
    fclose($handle);

}

 if($login_success == 1): ?>

<form action="newupload.php" method="post" enctype="multipart/form-data">

    <fieldset>
        <input type="file" name="markerImage" id="add 4" />
        
        <br><br>File Name:<br>
        <input  size="25"  maxlength="250" name="fileTextName"><br>
    </fieldset>
    
    <input type="submit" value="Submit">

</form>



<?php else : ?>
<?php endif; ?>