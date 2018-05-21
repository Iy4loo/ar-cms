<?php


//---------------------- FUNCTIONS -----------------------



//if success -> return nothing, else return error msg
function checkFormat($objectType, $fileName){
    $err = '';
    
    $fileName = strtolower($fileName);
  
    if($objectType == '3d-Object' ){
      if(strpos($fileName, '.obj') !== FALSE){return '';}else{$err = '-This type of markers suppurts only obj-files!';}
    }
    if($objectType == 'Music' ){
      if(strpos($fileName, '.ogg') !== FALSE){return '';}else{$err = '-This type of markers suppurts only ogg-files!';}
    }
    if($objectType == 'Picture' ){
      if(strpos($fileName, '.jpg') !== FALSE){return '';}else{
          if(strpos($fileName, '.jpeg') !== FALSE){return '';}else{
             if(strpos($fileName, '.png') !== FALSE){return '';}else{$err = '-This type of markers suppurts only jpg or png files!';}}
      }
    }
    if($objectType == 'Video' ){
      if(strpos($fileName, '.mp4') !== FALSE){return '';}else{$err = '-This type of markers suppurts only mp4-files!';}
    }
    if($objectType == 'Texture' ){
      if(strpos($fileName, '.jpg') !== FALSE){return '';}else{$err = '-This type of markers suppurts only jpg-textures!';}
    }

    return $err;
}





function time_to_ticks($time) {
    return number_format(($time * 10000000) + 621355968000000000 , 0, '.', '');
}






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







function checkLogin(){
	session_start();
	if(!isset($_SESSION['user']) || !isset($_SESSION['psw'])) {
    	die('Please login first <a href="login.php">sign in</a>.');
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










function getXmlForMarker($id,$hash,$link1,$effect3_str){
	$type = '';
	$effect1_tag = '';
	$effect2_tag = '';
	$map_mode = '';



	if($_POST['type'.$id] != 'Nothing' ){//if "nothing" was selected -> do nothing

		if($_POST['type'.$id] == "Don't change" ){//if "Do not change" was selected -> do not change the contents of the marker
    		echo '<br>';
			echo 'Marker'.$id.": don't change!";
			echo '<br>';

			$markerID = 'marker'.$id;
    		$allText = readAllFromFile();
    		$marker = findMarker($markerID, $allText);
   			if($marker == ''){//if the user dont want to change the data of the marker - but there are data for this marker
    			$errorMSG = "-Error: marker Nr. $id not found!\n";
    			return $errorMSG;
    		}
    		$marker = PHP_EOL .'<marker'.$id.'>'. PHP_EOL . $marker . '</marker'.$id.'>'. PHP_EOL ;
    		return $marker;
		}
        
        $posX = $_POST['posX'.$id];
        $posY = $_POST['posY'.$id];
        $posZ = $_POST['posZ'.$id];
        
        $scaleX = $_POST['scaleX'.$id];
        $scaleY = $_POST['scaleY'.$id];
        $scaleZ = $_POST['scaleZ'.$id];
        
		if($_POST['map_mode'.$id]){
			echo 'Map-mode is turned on for this marker!';
			$map_mode = '<mapmode>on</mapmode>'. PHP_EOL;
		}else{
			$map_mode = '<mapmode>off</mapmode>'. PHP_EOL;
		}


		if($_POST['type'.$id] == '3d-Object' ){
  			$type = '3dObject';
  			$effect1_tag = 'rotatingEffect';
  			$effect2_tag = 'pulsatingEffect';
		}


		if($_POST['type'.$id] == 'Music' ){
  			$type = 'musicObject';
 			$effect1_tag = 'displayingTitle';
  			$effect2_tag = 'hidePlayerBar';
  			//if link2 was setted:
  			if($_POST['songTitle'.$id] != ""){
    			$effect3_str = '<songtitle>' .str_replace(' ', '_', $_POST['songTitle'.$id]). '</songtitle>'. PHP_EOL   ;
  			}
		}


		if($_POST['type'.$id] == 'Picture' ){
  			$type = 'pictureObject';
  			$effect1_tag = 'fadingInEffect';
  			$effect2_tag = 'growingEffect';
		}


		if($_POST['type'.$id] == 'Video' ){
  			$type = 'videoObject';
  			$effect1_tag = 'fadingInEffect';
  			$effect2_tag = 'growingEffect';
		}


		$effects = $_POST['effekte'.$id];
		$effect1 = 'off';
		$effect2 = 'off';

		if($effects != NULL){//check whether the array is empty or not
  			foreach ($effects as $eff){ 
      			if($eff == 'eff1'){$effect1 = 'on';}
      			if($eff == 'eff2'){$effect2 = 'on';}
  			}
		}

		$marker= PHP_EOL .'<marker'.$id.'>'. PHP_EOL .
              				'<'.$type.'>'. PHP_EOL .
                 				'<hash>'.$hash.'</hash>'. PHP_EOL .
                 				'<link>' .$link1. '</link>'. PHP_EOL .
                 				'<'.$effect1_tag.'>' .$effect1. '</'.$effect1_tag.'>' .PHP_EOL .
                 				'<'.$effect2_tag.'>' .$effect2. '</'.$effect2_tag.'>' .PHP_EOL .
                 				$map_mode.
                 				$effect3_str. 
                                '<position>'.$posX.'_'.$posY.'_'.$posZ.'</position>' .PHP_EOL .
                                '<scale>'.$scaleX.'_'.$scaleY.'_'.$scaleZ.'</scale>' .PHP_EOL.
              				'</'.$type.'>'. PHP_EOL .
         				'</marker'.$id.'>'. PHP_EOL ;


        return $marker;
	}else{return "";}//nothing selected
}




//---------------------- FUNCTIONS -----------------------












//-----MAIN-----

 if(checkLogin() == 1){

	$date_ticks = time_to_ticks(time());
	$date_xml = '<date>'.$date_ticks. '</date>';

	error_reporting(E_ALL ^ E_NOTICE);

	$contentOfXML = $date_xml . PHP_EOL;
	$errorMSG = '';
    
    $handleN = fopen("numberOfMarkers.txt", "r");
    $numberOfMarkers = fgets($handleN);
    
    $numberOfMarkers = (int)$numberOfMarkers;
    
    
    for ($i = 1; $i <= $numberOfMarkers; $i++){
		$link1 = '';
	    $hash = '';
	    $effect3_string = '';

	    //----uploading
	    if($_POST['type'.$i] != 'Nothing' && $_POST['type'.$i] != "Don't change" ){//checking wehther the upload is needed
			$uploadfile = 'contents/' .rawurlencode( basename($_FILES['data'.$i]['name']));

			$errFormat = checkFormat($_POST['type'.$i], $uploadfile);
			if($errFormat != ''){$errorMSG = $errFormat;break;}

			if (move_uploaded_file($_FILES['data'.$i]['tmp_name'], $uploadfile)) {
    			echo '<br>';
    			echo "-File Nr. $i was successful uploaded.\n";
    			$link1 = $_SERVER['SERVER_NAME'].'/contents/'.rawurlencode($_FILES['data'.$i]['name']);
   		 		$hash = md5_file('contents/'.rawurlencode($_FILES['data'.$i]['name']));

   		 		//-----texture (for 3d-objects)
   		 		if($_POST['type'.$i] == '3d-Objekt'){
   		 			$uploadfile = 'contents/' . rawurlencode(basename($_FILES['texture'.$i]['name']));
  					$errFormat = checkFormat('Texture', $uploadfile);
  					if($errFormat != ''){$errorMSG = $errFormat;break;}

  					//upload texture for the 3d-object
  					if (move_uploaded_file($_FILES['texture'.$i]['tmp_name'], $uploadfile)) {
     					echo '<br>';
      					echo "-Texture-file Nr. $i was successful uploaded.\n";
      					$effect3_string = '<texturelink>'. $_SERVER['SERVER_NAME'].'/contents/'.rawurlencode($_FILES['texture'.$i]['name'] ).'</texturelink>'. PHP_EOL;
  					}else {
      					echo '<br>';
      					$errorMSG = "-Error occured while uploading the texture-file Nr. $i !\n";
      					break;
  					}
  				}
  				//-----texture
				}else {
    				echo '<br>';
    				$errorMSG = "-Error occured while uploading the file Nr. $i !\n";
    				break;
				}
		}
		//----uploading



		$marker = getXmlForMarker($i,$hash,$link1,$effect3_string );
		if(!(strpos($marker, "-Error") === false)){
		    $errorMSG = $marker;
    		break;
		}
			
		$contentOfXML = $contentOfXML .$marker; 

	}

	if($errorMSG == ''){
		echo '<br>';
		echo '<br>';
		echo '<br>';
		echo 'The contents of the generated XML-file: ' . PHP_EOL;
		echo '<br>';
		echo '<pre>';
		echo htmlspecialchars($contentOfXML);
		file_put_contents("filename.txt", $contentOfXML);
	}else{
		echo PHP_EOL . $errorMSG . PHP_EOL;
	}


}

//-----MAIN-----

?>






