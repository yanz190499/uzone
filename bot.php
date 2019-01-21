<?php

function curl($url,$data=null,$bearer=null){

	$h = array();

	$h[] = "Content-Type: application/x-www-form-urlencoded";

	if($bearer != null) $h[] = "Authorization: Bearer ".$bearer;

	$h[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";

	$h[] = "User-Agent: Mozilla/5.0 (Linux; Android 6.0.1; vivo 1606 Build/MMB29M)";

	$h[] = "Host: apis.uzone.id";

	$h[] = "Connection: Keep-Alive";

	$h[] = "Accept-Encoding: gzip";

	$h[] = "Content-Length: ".strlen($data);

	$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, $h);

	if($data != null){

	 curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	}

$asw = curl_exec($ch);

curl_close($ch);

	return $asw;

}

function timeline($category){

	$c = "category/$category";

	if($category=="homepage") $c = "homepage";

	$nf = array("newsfeed_1","newsfeed_2","newsfeed_3","newsfeed_4","newsfeed_5","newsfeed_6","newsfeed_7");

	$tl = file_get_contents("https://apis.uzone.id/uzone/".$c."/");

	$tl = json_decode($tl,true);

	$pid = array();

	for($a=0;$a<count($nf);$a++){

		$nfi = $tl['datas'][$nf[$a]]['newsfeed'];

		$re = true;

		if(!$nfi) $re = false;

		if($re==false){

		}else{

			for($i=0;$i<@count($nfi);$i++){

				$pip = $nfi[$i]['post_id'];

				$pid[] = $pip;

				$h=fopen("pid.txt","a+");

				fwrite($h,$pip."\n");

				fclose($h);

			}

		}

	}

	if($re==false){

		return false;

	}else{

		return $pid;

	}

}

echo "?Bearer		";

$bearer = trim(fgets(STDIN));

$url = "https://apis.uzone.id/users/comment/";

$category = array("homepage","hangout","entertainment","games","technology","health","travel");

while($oo=true){

for($i=0;$i<count($category);$i++){

	if(!file_exists("pid.txt")){

		$timeline = timeline($category[$i]);

	}else{

		$timeline = file_get_contents("pid.txt");

		$timeline = explode("\n",$timeline);

	}

	if($timeline==false) continue;

	for($a=0;$a<(count($timeline)-1);$a++){

		$fk = @explode("\n",@file_get_contents("komen.txt"));

		$komen = urlencode($fk[rand(0,(count($fk)-1))]);

		$pid = $timeline[$a];

		$data = "post_id=$pid&comment=$komen&";

		$komen = curl($url,$data,$bearer);

		print_r($komen);

		echo "\n";

		sleep(10); //rubah angka dalam kurung untuk delay

	}

}

}


