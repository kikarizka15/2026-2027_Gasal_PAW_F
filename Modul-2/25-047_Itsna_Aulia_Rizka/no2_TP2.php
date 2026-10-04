<?php  
$matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];
foreach ($matkul as $nama) {
	switch ($nama) {
        case "PTI":
            echo "Saya suka $nama <br>";
            break;
        case "ALPRO":
            echo "Saya suka $nama <br>";
            break;
        case "DPW":
            echo "Saya suka $nama <br>";
            break;
        case "STRUKDAT":
            echo "Saya suka $nama <br>";
            break;
        case "JARKOM":
            echo "Saya suka $nama <br>";
            break;
        case "PAW":
            echo "Saya suka $nama <br>";
            break;
        default:
            echo "Saya tidak mengambil matkul $nama <br>";
            break;
    }
}
?>