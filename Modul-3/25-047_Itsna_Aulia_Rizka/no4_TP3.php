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
echo "<br><br>";

foreach ($height as $nama => $tinggi) {
    echo "$nama is $tinggi cm tall <br>";
}
echo "<br>";

$weight = [
"Andy" => "70",
"Barry" => "65",
"Charlie" => "75"
];

print_r($weight);
echo "<br><br>";

$keys = array_keys($weight);
$jumlahData = count($keys);

for ($i = 0; $i < $jumlahData; $i++) {
    $nama = $keys[$i];
    $berat = $weight[$nama];
    
    echo "$nama is $berat kg.<br>";
}
?>