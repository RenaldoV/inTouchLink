<?php
if ($_POST['dropdownValue']){
//call the function or execute the code
    $_SESSION['store'] = $_POST['dropdownValue'];
    $_SESSION["cmbstore"] = $_POST['dropdownValue'];
    echo $_SESSION['store'];
}
?>
