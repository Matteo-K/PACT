<?php
$page = isset($_POST['page']) ? $_POST['page'] : 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $page != -1) {

}



if ($page == -1 || $page == 0) {
  // redirige page d'acceuil
} else {
  
}
header("Location: manageOffer.php?page=" . $page);
exit();
?>