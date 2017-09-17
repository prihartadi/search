<!DOCTYPE html>
<html>
<head>
<title>Mesin Pencari Becak</title>
<link href="main2.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">
</head>
<body>
<main>
	<header>
		<h1>Mesin Pencari Becak</h1>
	</header>
	<<!-- div id="name_logo">
		<img id="logo" src="becak.png">
		<h2 id="name">Mesin Pencari Becak</h2>	
	</div> -->
	
	<!-- <div id="main"> -->
	<form action="" method="post">

		<input id="search_bar" type="text" placeholder="Ketik 'keyword' disini, lalu tekan enter" name="terms">
			<!-- <input id="search_button" type="submit" value="Cari"> -->
		</form>	
	<!-- </div> -->

<?php
require 'vendor/autoload.php';
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();
if(isset($_POST['terms'])){
	$terms = $_POST['terms'];
	$term = explode(" ", $terms);
	for($i=0;$i<count($term);$i++){
		$term[$i] = $stemmer->stem($term[$i]);
	}
	echo "<section class='card' id='term'><h1>".$terms." => ".$term[0]."</h1></section>";
	$dir = 'sources';
	$files = scandir($dir);
	$stoplist = file("stoplist.txt",FILE_IGNORE_NEW_LINES);

	//read json

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
		$output2[$i] = array_count_values($output[$i]);
		$fp = fopen('json/'.($i+1).'.json', 'w');
		fwrite($fp, json_encode($output2[$i]));
		fclose($fp);
	}
	arsort($count);
	$temp = 0;
	foreach ($count as $key => $val) {
		if($temp<5){
			echo "<div class='card'><h2><a id='link' target='blank' href='sources/".($key+1).".txt'> link dokumen ke-".($key+1)."</a></h2>	</div>";
			$temp++;
		}
		else
			break;
	}
}
?>
</main>
</body>
</html>