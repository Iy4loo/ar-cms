<?php

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
echo '<font color="black" size="6">Self Help Uploads:</font>';
echo '<br>';
echo "<br>";
?>

<form action="customerupload.php" method="post" enctype="multipart/form-data">

    <fieldset>
        Select the associated marker:

        <?php

        //Read number of markers in CMS
        $handleN = fopen("numberOfMarkers.txt", "r");
        $numberOfMarkers = fgets($handleN);

        $numberOfMarkers = (int)$numberOfMarkers;

        $markerArray = array(getMarkerName(1));

        for ($i = 2; $i <= $numberOfMarkers; $i++){

            array_push($markerArray,getMarkerName($i));
        }

        echo "<select name='format' id='sel'>";
        for($c = 0; $c < $numberOfMarkers; $c++){
            echo "<option>$markerArray[$c]</option>";
        }
        echo "</select>";?>

        <br>
        <br>

        <input type="file" name="markerImage" id="add 4" />
        <br><br>File Name:<br>
        <input  size="25"  maxlength="250" name="fileTextName">

        <br>
        <br>

        <input type="submit" value="Submit">

    </fieldset>
</form>

<img src='auob.png' class="center" style="float:left;width:250px;height:125px;">

<br>
<br>