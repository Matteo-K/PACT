<?php require_once __DIR__."/config.php"; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['typeUser']) && isset($_POST['idU'])) {
    
    $_SESSION['idUser'] = htmlspecialchars($_POST['idU']);
    $_SESSION['typeUser'] = $_POST['typeUser'];

    if (isset($_POST["idoffre"])) {
      ?>
      <form action="detailsOffer.php" method="post" id="goToOffer">
        <input type="hidden" name="idoffre" value="<?= $_POST["idoffre"] ?>">
      </form>
      <script>
        document.getElementById("goToOffer").submit();
      </script>
      <?php
    } else {
      header("Location: index.php");
      exit();
    }

  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Backdoor</title>
  <style>
    .typeUser {
      background-color: black;
      color: white;
    }

    [type="submit"] {
      background-color: red;
      color: white;
      padding : 10px 20px;
    }

  </style>
</head>
<body>
  <a href="logout.php">Déconnexion</a>
  <table class="studentInfo">
    <tbody>
      <!-- Admin -->
      <tr>
        <td class="typeUser" colspan="2">Admin</td>
      </tr>
      <?php 
          $stmt = $conn->prepare("SELECT * from pact.admin");
          $stmt->execute();
          while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <tr>
          <td><?php echo $result["login"] ?></td>
          <td>
            <form action="backdoor.php" method="post">
              <input type="hidden" name="idU" value="<?php echo $result["idu"] ?>">
              <input type="hidden" name="typeUser" value="admin">
              <input type="text" name="idoffre" placeholder="idOffre">
              <input type="submit" value="Connexion">
            </form>
          </td>
      </tr>
      <?php
        }
      ?>
      <!-- Pro privé -->
      <tr>
        <td class="typeUser" colspan="2">Pro Privée</td>
      </tr>
      <?php 
          $stmt = $conn->prepare("SELECT * from pact.proprive");
          $stmt->execute();
          while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <tr>
          <td><?php echo $result["denomination"] ?></td>
          <td>
            <form action="backdoor.php" method="post">
              <input type="hidden" name="idU" value="<?php echo $result["idu"] ?>">
              <input type="hidden" name="typeUser" value="pro_prive">
              <input type="text" name="idoffre" placeholder="idOffre">
              <input type="submit" value="Connexion">
            </form>
          </td>
      </tr>
      <?php
        }
      ?>
      <!-- Pro public -->
      <tr>
        <td class="typeUser" colspan="2">Pro Public</td>
      </tr>
      <?php 
          $stmt = $conn->prepare("SELECT * from pact.propublic");
          $stmt->execute();
          while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <tr>
          <td><?php echo $result["denomination"] ?></td>
          <td>
            <form action="backdoor.php" method="post">
              <input type="hidden" name="idU" value="<?php echo $result["idu"] ?>">
              <input type="hidden" name="typeUser" value="pro_public">
              <input type="text" name="idoffre" placeholder="idOffre">
              <input type="submit" value="Connexion">
            </form>
          </td>
      </tr>
      <?php
        }
      ?>
      <!-- Membre -->
      <tr>
        <td class="typeUser" colspan="2">Membre</td>
      </tr>
      <?php 
          $stmt = $conn->prepare("SELECT * from pact.membre");
          $stmt->execute();
          while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <tr>
          <td><?php echo $result["pseudo"] . " - " . $result["nom"] . " " . $result["prenom"] ?></td>
          <td>
            <form action="backdoor.php" method="post">
              <input type="hidden" name="idU" value="<?php echo $result["idu"] ?>">
              <input type="hidden" name="typeUser" value="membre">
              <input type="text" name="idoffre" placeholder="idOffre">
              <input type="submit" value="Connexion">
            </form>
          </td>
      </tr>
      <?php
        }
      ?>
    </tbody>
  </table>
</body>
</html>