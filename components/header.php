<header>
        <ul>
            <li><a href="index.php"><img id="logo" src="./img/....." title="logo du site"></a></li>
            <li id="auth">
                <?php if ($isLoggedIn): ?>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM _user WHERE id = ?");
                    $stmt->execute([$_SESSION["idUser"]]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    $profilePicture = $user['profile_picture'] ?: './img/profile_picture/default.svg';
                    ?>
                    <div id="profileMenu" class="container_img">
                        <img id="profil_pict" src="<?php echo htmlspecialchars($profilePicture); ?>" onclick="toggleMenu()">
                    </div>
                    <div id="slidingMenu" class="sliding-menu">
                        <div class="personnalInfo">
                            <?php
                                $username = $user["pseudo"];
                            ?>
                            <div class="container_img">
                                <img id="menuPict" src="<?php echo htmlspecialchars($profilePicture); ?>">
                            </div>
                            
                            <p><?php echo htmlspecialchars($username); ?></p>
                        </div>
                        <div class="menu-items">
                            <a href="index.php">Accueil</a>
                            <a href="link2.php">Lien 2</a>
                            <a href="link3.php">Lien 3</a>
                        </div>
                        <a href="settings.php"><img src="./img/icone/setting.svg">Paramètres</a>
                        <button class="logout-button" onclick="window.location.href='logout.php'">Déconnexion</button>
                    </div>
                <?php else: ?>
                    <a href="login.php"><div id="btnConn">Connexion</div></a>
                <?php endif; ?>
            </li>
        </ul>
    </header>