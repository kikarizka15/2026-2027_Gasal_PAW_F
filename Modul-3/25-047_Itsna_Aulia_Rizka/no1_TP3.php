<?php  
$fruits = ["Avocado", "Blueberry", "Cherry"];
array_push($fruits, "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

echo 'fruits = ("' . implode(", ", $fruits) . '")<br>';
$indeks = count($fruits) -1;
echo "Nilai dengan indeks tertinggi: $fruits[$indeks]";
echo "<br><br>";

unset($fruits[1]);
echo 'fruits = ("' . implode(", ", $fruits) . '")<br>';
$indeks = count($fruits);
echo "Nilai dengan indeks tertinggi: $fruits[$indeks]";
?>