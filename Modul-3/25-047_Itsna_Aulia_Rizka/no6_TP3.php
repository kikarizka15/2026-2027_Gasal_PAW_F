<?php
$array1 = array("A");
echo "Array awal: (\"A\")<br>";
array_push($array1, "B");
echo "Hasil array_push: " . implode(" ", $array1) . "<br><br>";

$array2_a = array("A", "B");
$array2_b = array("C");
echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")<br>";
$hasil_merge = array_merge($array2_a, $array2_b);
echo "Hasil array_merge: " . implode(" ", $hasil_merge) . "<br><br>";

$array3 = array("x" => 1, "y" => 2);
echo "Array awal: (\"x\" => 1, \"y\" => 2)<br>";
$hasil_values = array_values($array3);
echo "Hasil array_values: " . implode(" ", $hasil_values) . "<br><br>";

$array4 = array("A", "B", "C");
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>";
$hasil_search = array_search("B", $array4);
echo "Hasil array_search: " . $hasil_search . "<br><br>";

$array5 = array(0, 1, false, 2, "", 3, "array");
echo "Array awal: (0, 1, false, 2, \"\", 3, \"array\")<br>";
$hasil_filter = array_filter($array5);
echo "Hasil array_filter: ";
foreach ($hasil_filter as $val) {
    if ($val === true) {
        echo "1 ";
    } else {
        echo $val . " ";
    }
}
echo "<br><br>";

$array6 = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
$sort_asc = $array6;
sort($sort_asc);
echo "Hasil sort: " . implode(" ", $sort_asc) . "<br>";

$sort_desc = $array6;
rsort($sort_desc);
echo "Hasil rsort: " . implode(" ", $sort_desc) . "<br><br>";

$array7 = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo "Array awal: (\"Peter\"=>35, \"Ben\"=>37, \"Joe\"=>43)<br>";

$asort_arr = $array7;
asort($asort_arr);
$result_asort = array();
foreach ($asort_arr as $k => $v) {
    $result_asort[] = "$k=> $v";
}
echo "Hasil asort: " . implode(", ", $result_asort) . ",<br>";

$ksort_arr = $array7;
ksort($ksort_arr);
$result_ksort = array();
foreach ($ksort_arr as $k => $v) {
    $result_ksort[] = "$k=> $v";
}
echo "Hasil ksort: " . implode(", ", $result_ksort) . ",<br>";

$arsort_arr = $array7;
arsort($arsort_arr);
$result_arsort = array();
foreach ($arsort_arr as $k => $v) {
    $result_arsort[] = "$k=> $v";
}
echo "Hasil arsort: " . implode(", ", $result_arsort) . ",<br>";

$krsort_arr = $array7;
krsort($krsort_arr);
$result_krsort = array();
foreach ($krsort_arr as $k => $v) {
    $result_krsort[] = "$k=> $v";
}
echo "Hasil krsort: " . implode(", ", $result_krsort) . ",";
?>