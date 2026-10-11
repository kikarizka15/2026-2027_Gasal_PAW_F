<?php   
$height = [
"Andy" => "176",
"Barry" => "165",
"Charlie" => "170"
];

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

print_r($height);
echo "<br>";

$keys = array_keys($height);
$last_key = end($keys);
echo "Nilai dengan indeks terakhir: $height[$last_key] <br><br>";

unset($height["Barry"]);

print_r($height);
echo "<br>";

$keys = array_keys($height);
$last_key = end($keys);
echo "Nilai dengan indeks terakhir setelah dihapus: $height[$last_key] <br><br>";

$weight = [
"Andy" => "70",
"Barry" => "65",
"Charlie" => "75"
];

print_r($weight);
echo "<br>";

$weight_keys = array_keys($weight);
echo "Data kedua: " . $weight[$weight_keys[1]];
?>