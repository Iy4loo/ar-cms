<?php

$handleN = fopen("numberOfMarkers.txt", "r");
$numberOfMarkers = fgets($handleN);
$numberOfMarkers = $numberOfMarkers +1;
fclose($handleN);

$handle = 'markerdata.txt';

$name = $_POST['fileTextName'];

$name = strtoupper($name);

file_put_contents($handle, $name."\n", FILE_APPEND);

$ext = "";
$fileName = $_FILES['markerImage']['name'];

$fileName = strtolower($fileName);

if(strpos($fileName, '.jpg' ) || strpos($fileName, '.jpeg')){
    
    $ext = '.jpg';
}
    
else if(strpos($fileName, '.png')){

       $ext = '.png';
}

$uploadfile = 'marker/' . $numberOfMarkers . $ext;
if (move_uploaded_file($_FILES['markerImage']['tmp_name'], $uploadfile)) {
    echo "success";
    
    $handleg = fopen("numberOfMarkers.txt", "w");
    fwrite($handleg, $numberOfMarkers);
    
}else{
    
    echo "error";
}

//reading
$handleR = fopen("markerdata.txt", "r");

echo "<br>";
while (($line = fgets($handleR)) !== false) {
    echo $line .'<br>';

}
fclose($handleR);

?>