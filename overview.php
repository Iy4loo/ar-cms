<?php





//%%%%%%%%%%%%%%%%%%%%%%%%%%  FUNCTIONS  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function readAllFromFile(){

	$textSegment = '';
	$handle = fopen("filename.txt", "r");

	if ($handle) {
    	while (($line = fgets($handle)) !== false) {
    	    $textSegment = $textSegment.$line;
    	}
	}
	fclose($handle);

	return $textSegment;
}






function findMarker($tag, $text){

	$textSegment = '';
	$readingBegin = false;

    foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $line){
	    if($readingBegin && strpos($line, $tag) == false){$textSegment = $textSegment.$line.PHP_EOL;}
	    if (strpos($line, $tag) !== false) {$readingBegin = !$readingBegin;}
	}

	return $textSegment;
}





function findTag($tag, $text){

	$textSegment = '';

    foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $line){

	    if(strpos($line, $tag) !== false){
	        $textSegment = substr($line,strpos($line, $tag) + strlen($tag)+1, strlen($line) - strlen($tag)*2 -5);
	        break;
	    }
	}

	return $textSegment;
}




function getTypeOfMarker($markerText){

	$textSegment = '';
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $markerText) as $line){

    	if(strpos($line, '3dObject') !== false){
	        $textSegment = '3dObject';
	        break;
	    }
	    if(strpos($line, 'musicObject') !== false){
	        $textSegment = 'musicObject';
	        break;
	    }

	    if(strpos($line, 'pictureObject') !== false){
	        $textSegment = 'pictureObject';
	        break;
	    }
	    if(strpos($line, 'videoObject') !== false){
	        $textSegment = 'videoObject';
	        break;
	    }

	}

	return $textSegment;
}





function checkLogin(){
	session_start();
	if(!isset($_SESSION['user']) || !isset($_SESSION['psw'])) {
    	die('Please <a href="login.php">sign in</a>.');
	}

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
                return 1;
                break;
            }
            $count++;
    }
    fclose($handle);

	}
	return 0;
}




//%%%%%%%%%%%%%%%%%%%%%%%%%%  FUNCTIONS  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

















//-----MAIN-----

 if(checkLogin() == 1){


	echo '<br>';
	echo '<font color="black" size="6">Overview:</font>';
	echo '<br>';
    
    $handleN = fopen("numberOfMarkers.txt", "r");
    $numberOfMarkers = fgets($handleN);
    
    $numberOfMarkers = (int)$numberOfMarkers;
    
    //reading
    $handleR = fopen("markerdata.txt", "r");
     
    for ($i = 1; $i <= $numberOfMarkers; $i++){
        

        
        $markerName = fgets($handleR);
        
        
        

        
        

		$markerID = 'marker'.$i;

		$allText = readAllFromFile();


		$marker = findMarker($markerID, $allText);
		$markerType = getTypeOfMarker($marker);
		$link = findTag('link', $marker);
        $position = findTag('position', $marker);
        $scale = findTag('scale', $marker);

		if($marker !== ''){
			echo '<br>';
			echo '<ins>'.$markerName.':</ins>';
			echo '<br>';
			echo '<img src="/marker/'.$i.'.jpg" hspace="10" style="float:left;width:50px;height:75px;">';
			echo 'Type of the marker: '.$markerType;
			echo '<br>';
			echo 'Content-URL: '.$link;
            if($position !== ""){
                echo '<br>';
                echo 'Position X_Y_Z: '.$position;
            }
            if($scale !== ""){
                echo '<br>';
                echo 'Scale X_Y_Z: '.$scale;
            }


			if($markerType == '3dObject'){
				$opt1 = findTag('rotatingEffect', $marker);
				$opt2 = findTag('pulsatingEffect', $marker);
				$textrLink = findTag('texturelink', $marker);

				echo '<br>';
    			echo 'rotatingEffect: '.$opt1;
    			echo '<br>';
    			echo 'pulsatingEffect: '.$opt2;
    			echo '<br>';
    			echo 'Texture-URL: '.$textrLink;
			}

			if($markerType == 'musicObject'){
				$opt1 = findTag('displayingTitle', $marker);
				$opt2 = findTag('hidePlayerBar', $marker);
				$sngTitle = findTag('songtitle', $marker);

				echo '<br>';
    			echo 'displayingTitle: '.$opt1;
    			echo '<br>';
    			echo 'hidePlayerBar: '.$opt2;
    			echo '<br>';
    			echo 'Song-title: '.$text = str_replace('_', ' ', $sngTitle);
			}

			if($markerType == 'pictureObject'){
				$opt1 = findTag('fadingInEffect', $marker);
				$opt2 = findTag('growingEffect', $marker);
                $mapMode = findTag('mapmode', $marker);

				echo '<br>';
    			echo 'Fading In Effect: '.$opt1;
    			echo '<br>';
    			echo 'Growing Effect: '.$opt2;
                echo '<br>';
                echo 'Map Mode: '.$mapMode;
			}

			if($markerType == 'videoObject'){
				$opt1 = findTag('fadingInEffect', $marker);
				$opt2 = findTag('growingEffect', $marker);

				echo '<br>';
    			echo 'fadingInEffect: '.$opt1;
    			echo '<br>';
    			echo 'growingEffect: '.$opt2;
			}

			echo '<br>';
		}else{
			echo '<br>';
			echo '<ins>'.$markerName.':</ins>';
			echo '<br>';
			echo '<img src="/marker/'.$i.'.jpg" hspace="10" style="float:left;width:50px;height:75px;">';
			echo '<font color="red">This marker has no contents!</font>';
			echo '<br>';
			echo '<br>';
			echo '<br>';
			echo '<br>';
		}

	}
     fclose($handleR);

}

//-----MAIN-----
?>


