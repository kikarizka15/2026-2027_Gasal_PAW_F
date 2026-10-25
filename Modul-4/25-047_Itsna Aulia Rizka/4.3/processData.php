 <!-- <h3>Posted data: </h3> -->
<?php
// foreach ($_POST as $key => $value) {
//     echo "({$key}) => ({$value}) <br/>";
// }


// require 'validate.inc'; 
// if (validateName_simple($_POST, 'surname'))
//     echo 'Data OK!';
// else
//     echo 'Data invalid!';


require 'validate.inc';
$errors = array();
validateName($errors, $_POST, 'surname'); 
if ($errors)
{
    echo 'Errors:<br/>';
    foreach ($errors as $field => $error)
        echo "$field $error<br/>";
} else
    echo 'Data OK!';
?>

