<!-- <form action="../upload.php" method="post">
  <fieldset>
    <legend>Update</legend>
    input:text
  </fieldset>
  <input type="submit" value="Envoyer">
</form> -->

<form action="../upload.php" method="post">
  <fieldset>
    <legend>Delete</legend>

    <input type="text" name="fileName" value="<?php $_GET["fileName"] ?? "" ?>">
    <input type="text" name="idOffre" value="<?php $_GET["idOffre"] ?? "" ?>">
    <select name="dossierImg">
      <option value="img/imageOffre/">img/imageOffre/</option>
      <option value="img/imageMenu/">img/imageMenu/</option>
      <option value="img/imagePlan/">img/imagePlan/</option>
    </select>

    <input type="hidden" name="action" value="delete">
  </fieldset>
  <input type="submit" value="Envoyer">
</form>

