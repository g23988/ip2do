<?php
//global save option
$dirConf = "domain.conf";
$doConf = "test.conf";
$newConf = "test.conf.new";
$optionArray = getOption();

//main
$ipArray = array();
$domainArray = array();
readDirConf();
$openOldConf = fopen($doConf,"r");
$openNewConf = fopen($newConf,"w");
if($openOldConf){
	while(!feof($openOldConf)){
		$contents = fgets($openOldConf);
		foreach($ipArray as $key => $value){
			if(ereg($value,$contents)){
				$contents = ereg_replace($value,$domainArray[$key],$contents);
				break;
			}
		}
		fprintf($openNewConf,$contents);
	}
}
fclose($openOldConf);
fclose($openNewConf);


function readDirConf(){
	global $ipArray;
	global $domainArray;
	global $dirConf;
	$opendir = fopen($dirConf,"r");
	$contents = "";
	if($opendir){
		while(!feof($opendir)){
			$contents = fgets($opendir);
			$contents = trim($contents);
			if($contents == ""){break;}
			$result = explode(":",$contents);
			array_push($ipArray,$result[0]);
			array_push($domainArray,$result[1]);
		}
	}
	fclose($opendir);
}

function getOption(){
	global $dirConf;
	global $doConf;
	global $newConf;
	$param_arr = getopt('d:f:b:');
	$result = $param_arr;
	if(count($result)!=3){
		echo "EX: php ip2do.php -d [Dictionary] -f [Source config] -b [Destination config]\n";
		exit();
	}
	if($result["d"]!=NULL)$dirConf = $result["d"];
	if($result["f"]!=NULL) $doConf = $result["f"];
	if($result["b"]!=NULL) $newConf = $result["b"];
	return $result;
}
?>
