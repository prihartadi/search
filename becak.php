<!DOCTYPE html>
<html>
<head>
<title>Mesin Pencari Becak</title>
<link href="main.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">
</head>
<body>
<div id="main_container">
	<div id="name_logo">
		<img id="logo" src="becak.png">
		<h2 id="name">Mesin Pencari Becak</h2>	
	</div>
	<div id="main">
		<form action="" method="post">
			<input id="search_bar" type="text" placeholder="Ketik becak disini, tekan->" name="terms">
			<input id="search_button" type="submit" value="Cari">
		</form>	
	</div>

<?php
require 'vendor/autoload.php';
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();
echo "<br>";
if(isset($_POST['terms'])){
	$terms = $_POST['terms'];
	//echo $terms;
	//echo "<br>";
	$term = explode(" ", $terms);
	for($i=0;$i<count($term);$i++){
		$term[$i] = $stemmer->stem($term[$i]);
	}
	echo $terms." => ".$term[0];
	echo "<br>";
	$dir = 'sources';
	$files = scandir($dir);
	//print_r($files);
	$stoplist = file("stoplist.txt",FILE_IGNORE_NEW_LINES);
	//echo "<br>";
	$string = array();
	$string_clean = array();
	$word = array();
	$result = array();
	$count = array();
	//$tf = array();

	

	for($i=0;$i<(count($files)-2);$i++){
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
			if($term[0] == $output[$i][$j])
				$count[$i]++;
		}
		// print_r($output[$i]);
		// echo "<br>";
		// echo "<br>";
		$output2[$i] = array_count_values($output[$i]);
		print_r($output2[$i]);
		echo "<br>";
		echo "<br>";
		$fp = fopen('json/'.($i+1).'.json', 'w');
		fwrite($fp, json_encode($output2[$i]));
		fclose($fp);
		//echo "file ke-".($i+1)." = ".$count[$i];
		//echo "<br>";
		//$tf[$i] = log10((double)$count);
	}
	echo "<br>";
	arsort($count);
	$temp = 0;
	foreach ($count as $key => $val) {
		if($temp<5){
			echo "<div id='search_result'><a id='link' href='sources/".($key+1).".txt'> link dokumen ke-".($key+1)."</a></div>";
			echo "<br>";
			$temp++;
		}
		else
			break;
	}
}
?>
</div>
</body>
</html>