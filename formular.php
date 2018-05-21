<?php

//Function to load each marker's name and display it.
function getMarkerName($j){
    
    //Read number of markers in CMS
    $handleN = fopen("numberOfMarkers.txt", "r");
    $numberOfMarkers = fgets($handleN);
    
    $numberOfMarkers = (int)$numberOfMarkers;
    
    //Read marker names and return them to the calling function.
    $handleR = fopen("markerdata.txt", "r");
    
    for ($i = 1; $i <= $numberOfMarkers; $i++){
        
        $line = fgets($handleR);
        
        if($i == $j){
                fclose($handleR);
                return $line;
            }
    }
    fclose($handleR);
}

session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['psw'])) {
    die('Please <a href="login.php">sign in</a>.');
}

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

<form action="auswertung.php" method="post" enctype="multipart/form-data">
    
<?php 
    
    //Loop to populate the page with all loaded markers.
    $handleM = fopen("numberOfMarkers.txt", "r");
    $markerCount = fgets($handleM);
    
    $markerCount = (int)$markerCount;?>

<?php 
    
    //Loop to populate the page with all loaded markers.
    $handleN = fopen("numberOfMarkers.txt", "r");
    $numberOfMarkers = fgets($handleN);
    
    $numberOfMarkers = (int)$numberOfMarkers;
    
    
    for ($i = 1; $i <= $numberOfMarkers; $i++): ?>

<!--
Field set that contains the loaded markers with the option to upload and associate content to the markers.
!-->
<fieldset>
  <legend><b>Marker: <?php echo getMarkerName($i); ?></b></legend>
  <img src="/marker/<?php echo $i; ?>.jpg" hspace="10" style="float:left;width:50px;height:70px;">

    Select the type of the marker:
  <select name="type<?php echo $i; ?>"  id="sel<?php echo $i; ?>">
    <option>3d-Object
    <option>Music
    <option>Picture
    <option>Video
    <option selected>Don't change
    <option>Nothing
  </select>
  &nbsp

 
    <br>
  <input type="checkbox" name="map_mode<?php echo $i; ?>" value="on" id="map_check<?php echo $i; ?>">
     <label id="map_text<?php echo $i; ?>">Map-mode:</label>
  <br>  <br>
  <strong id="opttext<?php echo $i; ?>">Options:</strong>
  <ins id="eff<?php echo $i; ?>1"> effect: Rotating</ins> <input type="checkbox" name="effekte<?php echo $i; ?>[]" value="eff1" id="check<?php echo $i; ?>1"> 
  <ins id="eff<?php echo $i; ?>2"> effect: Pulsating</ins> <input type="checkbox" name="effekte<?php echo $i; ?>[]" value="eff2" id="check<?php echo $i; ?>2"> 

  <br>
  <br>
    
  <label id="pos<?php echo $i; ?>">Position:</label>
  <input value="0.0" type="text" name="posX<?php echo $i; ?>" id="posX<?php echo $i; ?>" >
  <input value="0.0" type="text" name="posY<?php echo $i; ?>" id="posY<?php echo $i; ?>" >
  <input value="0.0" type="text" name="posZ<?php echo $i; ?>" id="posZ<?php echo $i; ?>" >
    
  <br>
  <br>
    
  <label id="scale<?php echo $i; ?>">Scale:</label>
  <input value="0.0" type="text" name="scaleX<?php echo $i; ?>" id="scaleX<?php echo $i; ?>" >
  <input value="0.0" type="text" name="scaleY<?php echo $i; ?>" id="scaleY<?php echo $i; ?>" >
  <input value="0.0" type="text" name="scaleZ<?php echo $i; ?>" id="scaleZ<?php echo $i; ?>" >
    
  <br>
  <br>
    
  <label id="dattext<?php echo $i; ?>">File:</label>
  <input type="file" name="data<?php echo $i; ?>" id="fileinput<?php echo $i; ?>"/>

  <br>
  <br>
  <label id="add<?php echo $i; ?>1" type="hidden"></label>
  <input type="hidden" name="songTitle<?php echo $i; ?>" id="add<?php echo $i; ?>2" >

  <label id="add<?php echo $i; ?>3">Texture:</label>
  <input type="file" name="texture<?php echo $i; ?>" id="add<?php echo $i; ?>4" />

</fieldset>


<?php endfor; ?>


  <p>
    <input type="submit" value="Submit">
  </p>
 
</form>

<script type="text/javascript">

  function selectChanged(sel) {
    var idSelect = sel.name.replace("type", "");;

    var eff1 = document.getElementById('eff'+idSelect+'1');
    var eff2 = document.getElementById('eff'+idSelect+'2');

    var selectedType = sel.options[sel.selectedIndex].value;
    if(selectedType == "3d-Object"){
        eff1.innerHTML = "effect: Rotating";
        eff2.innerHTML = "effect: Pulsating";
        var add1 = document.getElementById('add'+idSelect+'1');
        add1.innerHTML = "";
        var add2 = document.getElementById('add'+idSelect+'2');
        add2.type = "hidden";
        var add3 = document.getElementById('add'+idSelect+'3');
        add3.innerHTML = "Texture:";
        var add4 = document.getElementById('add'+idSelect+'4');
        add4.type = "file";
        //
        var add5 = document.getElementById('opttext'+idSelect);
        add5.innerHTML = "Options:";
        var add6 = document.getElementById('check'+idSelect+ '1');
        add6.type = "checkbox";
        var add7 = document.getElementById('check'+idSelect+ '2');
        add7.type = "checkbox";
        var add8 = document.getElementById('dattext'+idSelect);
        add8.innerHTML = "File:";
        var add9 = document.getElementById('fileinput'+idSelect);
        add9.type = "file";
        var add10 = document.getElementById('map_check'+idSelect);
        add10.type = "checkbox";
        var add11 = document.getElementById('map_text'+idSelect);
        add11.innerHTML = "Map-mode:";
        var positionText = document.getElementById('pos'+idSelect);
        positionText.innerHTML = "Position:";
        var posX = document.getElementById('posX'+idSelect);
        posX.type = "text";
        var posY = document.getElementById('posY'+idSelect);
        posY.type = "text";
        var posZ = document.getElementById('posZ'+idSelect);
        posZ.type = "text";
        var scaleText = document.getElementById('scale'+idSelect);
        scaleText.innerHTML = "Scale:";
        var scaleX = document.getElementById('scaleX'+idSelect);
        scaleX.type = "text";
        var scaleY = document.getElementById('scaleY'+idSelect);
        scaleY.type = "text";
        var scaleZ = document.getElementById('scaleZ'+idSelect);
        scaleZ.type = "text";
    }
    if(selectedType == "Music"){
        eff1.innerHTML = "Show song-title";
        eff2.innerHTML = "Hide player-bar";
        var add1 = document.getElementById('add'+idSelect+'1');
        add1.innerHTML = "Song-title:";
        var add2 = document.getElementById('add'+idSelect+'2');
        add2.type = "text";
        var add3 = document.getElementById('add'+idSelect+'3');
        add3.innerHTML = "";
        var add4 = document.getElementById('add'+idSelect+'4');
        add4.type = "hidden";
        //
        var add5 = document.getElementById('opttext'+idSelect);
        add5.innerHTML = "Options:";
        var add6 = document.getElementById('check'+idSelect+ '1');
        add6.type = "checkbox";
        var add7 = document.getElementById('check'+idSelect+ '2');
        add7.type = "checkbox";
        var add8 = document.getElementById('dattext'+idSelect);
        add8.innerHTML = "File:";
        var add9 = document.getElementById('fileinput'+idSelect);
        add9.type = "file";
        var add10 = document.getElementById('map_check'+idSelect);
        add10.type = "checkbox";
        var add11 = document.getElementById('map_text'+idSelect);
        add11.innerHTML = "Map-mode:";
        var positionText = document.getElementById('pos'+idSelect);
        positionText.innerHTML = "Position:";
        var posX = document.getElementById('posX'+idSelect);
        posX.type = "text";
        var posY = document.getElementById('posY'+idSelect);
        posY.type = "text";
        var posZ = document.getElementById('posZ'+idSelect);
        posZ.type = "text";
        var scaleText = document.getElementById('scale'+idSelect);
        scaleText.innerHTML = "Scale:";
        var scaleX = document.getElementById('scaleX'+idSelect);
        scaleX.type = "text";
        var scaleY = document.getElementById('scaleY'+idSelect);
        scaleY.type = "text";
        var scaleZ = document.getElementById('scaleZ'+idSelect);
        scaleZ.type = "text";
    }
    if(selectedType == "Picture"){
        eff1.innerHTML = "Fading-in Effect";
        eff2.innerHTML = "Growing Effect";
        var add1 = document.getElementById('add'+idSelect+'1');
        add1.innerHTML = "";
        var add2 = document.getElementById('add'+idSelect+'2');
        add2.type = "hidden";
        var add3 = document.getElementById('add'+idSelect+'3');
        add3.innerHTML = "";
        var add4 = document.getElementById('add'+idSelect+'4');
        add4.type = "hidden";
        //
        var add5 = document.getElementById('opttext'+idSelect);
        add5.innerHTML = "Options:";
        var add6 = document.getElementById('check'+idSelect+ '1');
        add6.type = "checkbox";
        var add7 = document.getElementById('check'+idSelect+ '2');
        add7.type = "checkbox";
        var add8 = document.getElementById('dattext'+idSelect);
        add8.innerHTML = "File:";
        var add9 = document.getElementById('fileinput'+idSelect);
        add9.type = "file";
        var add10 = document.getElementById('map_check'+idSelect);
        add10.type = "checkbox";
        var add11 = document.getElementById('map_text'+idSelect);
        add11.innerHTML = "Map-mode:";
        var positionText = document.getElementById('pos'+idSelect);
        positionText.innerHTML = "Position:";
        var posX = document.getElementById('posX'+idSelect);
        posX.type = "text";
        var posY = document.getElementById('posY'+idSelect);
        posY.type = "text";
        var posZ = document.getElementById('posZ'+idSelect);
        posZ.type = "text";
        var scaleText = document.getElementById('scale'+idSelect);
        scaleText.innerHTML = "Scale:";
        var scaleX = document.getElementById('scaleX'+idSelect);
        scaleX.type = "text";
        var scaleY = document.getElementById('scaleY'+idSelect);
        scaleY.type = "text";
        var scaleZ = document.getElementById('scaleZ'+idSelect);
        scaleZ.type = "text";
    }
    if(selectedType == "Video"){
        eff1.innerHTML = "Fading-in Effect";
        eff2.innerHTML = "Growing Effect";
        var add1 = document.getElementById('add'+idSelect+'1');
        add1.innerHTML = "";
        var add2 = document.getElementById('add'+idSelect+'2');
        add2.type = "hidden";
        var add3 = document.getElementById('add'+idSelect+'3');
        add3.innerHTML = "";
        var add4 = document.getElementById('add'+idSelect+'4');
        add4.type = "hidden";
        //
        var add5 = document.getElementById('opttext'+idSelect);
        add5.innerHTML = "Options:";
        var add6 = document.getElementById('check'+idSelect+ '1');
        add6.type = "checkbox";
        var add7 = document.getElementById('check'+idSelect+ '2');
        add7.type = "checkbox";
        var add8 = document.getElementById('dattext'+idSelect);
        add8.innerHTML = "File:";
        var add9 = document.getElementById('fileinput'+idSelect);
        add9.type = "file";
        var add10 = document.getElementById('map_check'+idSelect);
        add10.type = "checkbox";
        var add11 = document.getElementById('map_text'+idSelect);
        add11.innerHTML = "Map-mode:";
        var positionText = document.getElementById('pos'+idSelect);
        positionText.innerHTML = "Position:";
        var posX = document.getElementById('posX'+idSelect);
        posX.type = "text";
        var posY = document.getElementById('posY'+idSelect);
        posY.type = "text";
        var posZ = document.getElementById('posZ'+idSelect);
        posZ.type = "text";
        var scaleText = document.getElementById('scale'+idSelect);
        scaleText.innerHTML = "Scale:";
        var scaleX = document.getElementById('scaleX'+idSelect);
        scaleX.type = "text";
        var scaleY = document.getElementById('scaleY'+idSelect);
        scaleY.type = "text";
        var scaleZ = document.getElementById('scaleZ'+idSelect);
        scaleZ.type = "text";
    }
    if(selectedType == "Nothing"){
        eff1.innerHTML = "";
        eff2.innerHTML = "";
        var add1 = document.getElementById('add'+idSelect+'1');
        add1.innerHTML = "";
        var add2 = document.getElementById('add'+idSelect+'2');
        add2.type = "hidden";
        var add3 = document.getElementById('add'+idSelect+'3');
        add3.innerHTML = "";
        var add4 = document.getElementById('add'+idSelect+'4');
        add4.type = "hidden";
        //
        var add5 = document.getElementById('opttext'+idSelect);
        add5.innerHTML = "";
        var add6 = document.getElementById('check'+idSelect+ '1');
        add6.type = "hidden";
        var add7 = document.getElementById('check'+idSelect+ '2');
        add7.type = "hidden";
        var add8 = document.getElementById('dattext'+idSelect);
        add8.innerHTML = "";
        var add9 = document.getElementById('fileinput'+idSelect);
        add9.type = "hidden";
        var add10 = document.getElementById('map_check'+idSelect);
        add10.type = "hidden";
        var add11 = document.getElementById('map_text'+idSelect);
        add11.innerHTML = "";
        var positionText = document.getElementById('pos'+idSelect);
        positionText.innerHTML = "";
        var posX = document.getElementById('posX'+idSelect);
        posX.type = "hidden";
        var posY = document.getElementById('posY'+idSelect);
        posY.type = "hidden";
        var posZ = document.getElementById('posZ'+idSelect);
        posZ.type = "hidden";
        var scaleText = document.getElementById('scale'+idSelect);
        scaleText.innerHTML = "";
        var scaleX = document.getElementById('scaleX'+idSelect);
        scaleX.type = "hidden";
        var scaleY = document.getElementById('scaleY'+idSelect);
        scaleY.type = "hidden";
        var scaleZ = document.getElementById('scaleZ'+idSelect);
        scaleZ.type = "hidden";

    }
        if(selectedType == "Don't change"){
        eff1.innerHTML = "";
        eff2.innerHTML = "";
        var add1 = document.getElementById('add'+idSelect+'1');
        add1.innerHTML = "";
        var add2 = document.getElementById('add'+idSelect+'2');
        add2.type = "hidden";
        var add3 = document.getElementById('add'+idSelect+'3');
        add3.innerHTML = "";
        var add4 = document.getElementById('add'+idSelect+'4');
        add4.type = "hidden";
        //
        var add5 = document.getElementById('opttext'+idSelect);
        add5.innerHTML = "";
        var add6 = document.getElementById('check'+idSelect+ '1');
        add6.type = "hidden";
        var add7 = document.getElementById('check'+idSelect+ '2');
        add7.type = "hidden";
        var add8 = document.getElementById('dattext'+idSelect);
        add8.innerHTML = "";
        var add9 = document.getElementById('fileinput'+idSelect);
        add9.type = "hidden";
        var add10 = document.getElementById('map_check'+idSelect);
        add10.type = "hidden";
        var add11 = document.getElementById('map_text'+idSelect);
        add11.innerHTML = "";
        var positionText = document.getElementById('pos'+idSelect);
        positionText.innerHTML = "";
        var posX = document.getElementById('posX'+idSelect);
        posX.type = "hidden";
        var posY = document.getElementById('posY'+idSelect);
        posY.type = "hidden";
        var posZ = document.getElementById('posZ'+idSelect);
        posZ.type = "hidden";
        var scaleText = document.getElementById('scale'+idSelect);
        scaleText.innerHTML = "";
        var scaleX = document.getElementById('scaleX'+idSelect);
        scaleX.type = "hidden";
        var scaleY = document.getElementById('scaleY'+idSelect);
        scaleY.type = "hidden";
        var scaleZ = document.getElementById('scaleZ'+idSelect);
        scaleZ.type = "hidden";

    }
  }
    
  var markCount = <?php echo $markerCount?>;
    
  var num;
  var select = [document.getElementById('sel1')];
    
  for(i=2;i<=markCount;i++){
    
      select.push(document.getElementById('sel'+i));
      
  }
  /*    
  select.push(document.getElementById('sel2')); 
  select.push(document.getElementById('sel3')); 
  select.push(document.getElementById('sel4')); 
  select.push(document.getElementById('sel5')); 
  select.push(document.getElementById('sel6'));
  select.push(document.getElementById('sel7')); 
  select.push(document.getElementById('sel8')); 
  select.push(document.getElementById('sel9')); 
  select.push(document.getElementById('sel10'));  
  select.push(document.getElementById('sel11')); 
  select.push(document.getElementById('sel12')); 
  select.push(document.getElementById('sel13')); 
  select.push(document.getElementById('sel14')); */

  select.forEach(function(element) {
    element.onchange = function () {
        selectChanged(element)
    }
  });



</script>

<style type="text/css">
  label {
    width: 10em;
    display: block;
    float: left;
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
  height: 50px;
  width: 150px;
  background:#c63b0a; 
  color: white;
  cursor:pointer;
  font-size: 15px;
  border-radius: 8px;
  -moz-border-radius: 8px;
}

fieldset {   
  background-color: #FFFFFF;
  color: ##222222;
  border-color: #D7D8DB;
  border-style: solid;
  -moz-border-radius:10px;  
  border-radius: 10px;  
  -webkit-border-radius: 10px; 
}  

img{
  border-color: #EDEEF0;
  border-style: solid;
   background:#D7D8DB; 
     -moz-border-radius:10px;  
  border-radius: 10px;  
  -webkit-border-radius: 10px; 
}

legend{color:#285572}
</style>


<?php else : ?>
<?php endif; ?>