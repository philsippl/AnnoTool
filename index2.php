<?php

function cors() {

		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}

		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

			exit(0);
		}

	}

function getFilename(){
	if(!isset($_GET["key"])){
		return null;
	}
	return "csv/".$_GET["key"].".csv";
}

cors();

if(!isset($_GET['action'])){
  die();
}

if($_GET['action'] == 'next'){

  if (($handle = fopen(getFilename(), "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          if($data[1] === "0"){
            echo $data[0];
            fclose($handle);
            return;
          }
      }
      fclose($handle);
  }

  echo "error";
}

if($_GET['action'] == 'set'){

  $csvfile = getFilename();
  $tempfile = tempnam(".", "tmp");

  if(!$input = fopen($csvfile,'r')){
      die('could not open existing csv file');
  }
  if(!$output = fopen($tempfile,'w')){
      die('could not open temporary output file');
  }

  while(($data = fgetcsv($input)) !== FALSE){
      if($data[0] == $_GET['id']){
          $data[1] = $_GET['value'];
      }
      fputcsv($output,$data);
  }

  fclose($input);
  fclose($output);

  unlink($csvfile);
  //rename($csvfile,uniqid().$csvfile);
  rename($tempfile,$csvfile);
}

if($_GET['action'] == 'total'){
	$file=getFilename();
	$linecount = 0;
	$handle = fopen($file, "r");
	while(!feof($handle)){
	  $line = fgets($handle);
	  $linecount++;
	}
	fclose($handle);
	echo $linecount;
}

if($_GET['action'] == 'open'){
	$file=getFilename();
	$linecount = 0;
	$handle = fopen($file, "r");
	while(!feof($handle)){
	  $line = fgets($handle);
		if(strpos($line, ",0") !== FALSE){
				$linecount++;
		}
	}
	fclose($handle);
	echo $linecount;
}



?>
