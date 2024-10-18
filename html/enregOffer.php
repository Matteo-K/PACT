<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

}

$page = isset($_POST['page']) ? $_POST['page'] : 1;

header("Location: manageOffer.php?page=" . $page);
exit();
?>