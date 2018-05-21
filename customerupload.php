<?php

$handleN = fopen("numberOfMarkers.txt", "r");
$numberOfMarkers = fgets($handleN);
$numberOfMarkers = $numberOfMarkers +1;
fclose($handleN);

$format = $_POST['format'];

$handle = 'customercontent/'. $format.'/customerdata.txt';

$name = $_POST['fileTextName'];

$name = strtoupper($name);

$link1 = '';
$link1 = $_SERVER['SERVER_NAME'].'/customercontent/'.$format.'/'.rawurlencode($_FILES['markerImage']['name']);

file_put_contents($handle, $link1."\n", FILE_APPEND);

$ext = "";
$fileName = $_FILES['markerImage']['name'];

$fileName = strtolower($fileName);

if(strpos($fileName, '.jpg' ) || strpos($fileName, '.jpeg')){
    
    $ext = '.jpg';
}
    
else if(strpos($fileName, '.png')){

       $ext = '.png';
}

$folder = 'customercontent/';
$directory = scandir($folder);
$found = 0;

foreach($directory as $d){
    
    if($d == $format){
        
        $found = 1;
        break;
    }
}
        
if($found == 1){
        
        $uploadfile = 'customercontent/'.$format.'/' . $fileName;
        if (move_uploaded_file($_FILES['markerImage']['tmp_name'], $uploadfile)) {
            echo "success";

        }
        
        else{

            echo "error";
        }
    //}
}
    
    else{
        
        mkdir('customercontent/'.$format);
        fopen('customercontent/'. $format.'/customerdata.txt','a+');
        
                $uploadfile = 'customercontent/'.$format.'/' . $fileName;
        if (move_uploaded_file($_FILES['markerImage']['tmp_name'], $uploadfile)) {
            echo "success";

        }
        
        else{

            echo "error";
        }
    }


//reading
$handleR = fopen('customercontent/'. $format.'/customerdata.txt', 'r');

echo "<br>";
while (($line = fgets($handleR)) !== false) {
    echo $line .'<br>';

}
fclose($handleR);

?>