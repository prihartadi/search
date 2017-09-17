<!DOCTYPE html>
<html>
<body>
<h2>Mesin Pencari Becak</h2>
<form action="" method="post">
<input type="text" placeholder="Ketik becak disini, tekan->" name="terms">
<input type="submit" value="Cari">
</form>

<?php

require 'vendor/autoload.php';
 
// Meload library Sastrawi
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();
 
// Menjalankan stemming pada kalimat yang ditentukan
//$sentence = 'Perekonomian Indonesia sedang dalam pertumbuhan yang membanggakan';
//$output   = $stemmer->stem($sentence);
 
//echo $output . "\n"; // Menampilkan hasil stemming
echo "<br>";
if(isset($_POST['terms'])){
	$terms = $_POST['terms'];
	echo $terms;
	echo "<br>";
	$term = explode(" ", $terms);
	//echo "<br>";
	for($i=0;$i<count($term);$i++){
		$term[$i] = $stemmer->stem($term[$i]);
	}
	//var_dump($term);
	echo $term[0];
	echo "<br>";
	$dir = 'sources';
	$files = scandir($dir);
	//print_r($files);
	//echo count($files);
	//echo "<br>";
	//echo "<br>";
	$stoplist = file("stoplist.txt",FILE_IGNORE_NEW_LINES);
	//print_r($stoplist);
	//echo "<br>";
	echo "<br>";
	$string = array();
	$string_clean = array();
	$word = array();
	$result = array();
	$count = array();
	$tf = array();
	for($i=2;$i<count($files);$i++){
		$count[$i] = 0;
		$string[$i] = file_get_contents("sources/".$files[$i]);
		$string_clean[$i] = preg_replace('/[^a-z]+/i', '_', $string[$i]); 
		//echo $word_clean[$i];
		//print_r($string_clean[$i]);
		//echo "<br>";
		//echo "<br>";
		$word[$i] = explode("_", $string_clean[$i]);
		//print_r($word[$i]);
		echo "<br>";
		echo "<br>";
		$result[$i] = array_values(array_diff($word[$i], $stoplist));
		for($j=0;$j<count($result[$i]);$j++){
			$output[$i][$j] = $stemmer->stem($result[$i][$j]);
			if($term[0] == $output[$i][$j])
				$count[$i]++;
		}
		print_r($output[$i]);
		echo "<br>";
		echo "file ke-".($i+1)." = ".$count[$i];
		echo "<br>";
		$tf[$i] = log10((double)$count);
		//echo "<br>";
		//echo $tf[$i];
		//echo "<br>";
		}
	echo "<br>";
	arsort($count);
	foreach ($count as $key => $val) {
    		//echo ($key+1)." = $val <br>";
    /*
    ?>
    		<a href="sources/<?php echo ($key+1).".txt"; ?>">Visit W3Schools</a><br>
	<?php
	*/
		echo "<a href='sources/".($key+1).".txt'> link dokumen ke-".($key+1);
		echo "<br>";
	}

}
	

?>

</body>
</html>