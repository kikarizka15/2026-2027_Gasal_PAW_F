<?php  
$fruits = ["Avocado", "Blueberry", "Cherry"];

for ($i=1; $i <=5 ; $i++) { 
	$buah = "buah tambahan $i";
	array_push($fruits, $buah);
}

$arrlength = count($fruits);
for($x = 0; $x <$arrlength; $x++){
	echo $fruits[$x];
	echo "<br>";
}

echo "<br><br>";
$vegies = ["Carrot", "Broccoli", "Spinach"];
$arrlength = count($vegies);

for($x = 0; $x <$arrlength; $x++){
	echo $vegies[$x];
	echo "<br>";
}
?>
