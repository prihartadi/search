<!DOCTYPE html>
<html>
<head>
<title>Mesin Pencari Becak</title>
<!-- <link href="main2.css" rel="stylesheet"> -->
</head>
<body>
<main>
	<header>
		<h1>Mesin Pencari Becak</h1>
	</header>
	<form action="" method="post">
		<input id="search_bar" type="text" placeholder="Ketik 'keyword' disini, lalu tekan enter" name="terms">
	</form>	

<?php
/*
This app uses open source component
Copyright (c) 2015 Andy Librian
Original link: https://github.com/sastrawi/sastrawi
*/
require 'vendor/autoload.php';
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();

$dir_sources = 'sources';
$dir_json = 'json';
$count_dir_sources = scandir($dir_sources); echo count($count_dir_sources); echo "<br>";
$count_dir_json = scandir($dir_json); echo count($count_dir_json); echo "<br>";
$stoplist = file("stoplist.txt",FILE_IGNORE_NEW_LINES);

if(count($count_dir_json) != count($count_dir_sources)){
	echo "asdasdasd";
	for($i=(count($count_dir_json)-2);$i<(count($count_dir_sources)-2);$i++){
		$count[$i] = 0;
		//tokenizing
		$string[$i] = file_get_contents("sources/".($i+1).".txt");
		$string_clean[$i] = preg_replace('/[^a-z]+/i', '_', $string[$i]); 
		$word[$i] = explode("_", $string_clean[$i]);
		//filtering
		$result[$i] = array_values(array_diff($word[$i], $stoplist));
		for($j=0;$j<count($result[$i]);$j++){
			//stemming
			$output[$i][$j] = $stemmer->stem($result[$i][$j]);
			//if($term[0] == $output[$i][$j])
				//$count[$i]++;
		}
		$output2[$i] = array_count_values($output[$i]);
		$fp = fopen('json/'.($i+1).'.json', 'w');
		fwrite($fp, json_encode($output2[$i]));
		fclose($fp);
	}
}

if(isset($_POST['terms'])){
	$terms = $_POST['terms'];
	$term = explode(" ", $terms);
	for($i=0;$i<count($term);$i++){
		$term[$i] = $stemmer->stem($term[$i]);
	}
	echo "<section class='card' id='term'><h1>".$terms." => ".$term[0]."</h1></section>";

	//if(count($count_dir_json) == count($count_dir_sources)){
		for($i=0;$i<(count($count_dir_sources)-2);$i++){
			$json[$i] = json_decode(file_get_contents("json/".($i+1).".json"),true);
			//print_r($json[$i]);
			//echo "<br>";
			//echo "<br>";
			if(array_key_exists($term[0], $json[$i]))
				$count2[$i] = $json[$i][$term[0]];
		}
		// for($j=0;$j<(count($count_dir_sources)-2);$j++){
		// 	$count2[$j] = array_search('$term[0]', $json[$j]);
		// }
		
		arsort($count2);
		print_r($count2);
		//echo "<br>";
		//echo "<br>";
		$temp = 0;
		foreach ($count2 as $key => $val) {
			if($temp<5){
				echo "<div class='card'><h2><a id='link' target='blank' href='sources/".($key+1).".txt'> link dokumen ke-".($key+1)."</a></h2>	</div>";
				$temp++;
			}
			else
				break;
		}

	//}

	//print_r($json[0]['becak']);

	
	// arsort($count);
	// echo "<br>"; echo "count";echo "<br>";
	// print_r($count);
	// $temp = 0;
	// foreach ($count as $key => $val) {
	// 	if($temp<5){
	// 		echo "<div class='card'><h2><a id='link' target='blank' href='sources/".($key+1).".txt'> link dokumen ke-".($key+1)."</a></h2>	</div>";
	// 		$temp++;
	// 	}
	// 	else
	// 		break;
	// }
}
?>
</main>
</body>
</html>