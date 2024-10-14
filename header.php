<header>
    <ul>
        <li><img src="img/logo.png" title="Logo du site"></li>
        <li>
            <?php if(isset($_SESSION(["idUser"]))){

            } else{
                ?>
                <a href="./login.php">Connexion</a>
            <?php
            }
            ?>
        </li>
    </ul>
</header>