<footer>
  <div>
    <div>
      <figure>
        <img src="../img/logoTripEnArvor.png" alt="logo TripEnArvor">
        <figcaption>TripEnArvor&nbsp;---&nbsp;<span>PACT</span></figcaption>
      </figure>
      <p>Toute ressemblance avec un site web existant ou ayant existé serait purement fortuite et ne pourrait être que le fruit d'une pure coïncidence.</p>
    </div>
    <address>
      <h4><a href="mentionsLegales.php">Mentions légales</a></h4>
      <div>
        Equipe de conception / dev “ The Void - A21 “<br>
        Rue Edouard Branly, 22300 Lannion, France <br>
        ewen@jain-etudiants.univ-rennes1.com
      </div>
    </address>
  </div>
  <div>
    <a href="../backdoor.php" style="color:var(--bloc)" class="backdoor">Backdoor</a>
    <a href="../planDuSite.php">Plan du site</a>
    <p id="copyrightFooter">Copyright&nbsp;&copy;&nbsp;2024 TripEnArvor</p>
  </div>
</footer>



<script>

try {
    
    const logo = document.getElementById("logo");
    const liens = document.querySelectorAll("a, input[type='submit']");

    liens.forEach(lien => {
        lien.addEventListener("click", function () {
            logo.classList.add("chargementActif");

            // On supprime l'animation au bout de 6 si on a toujours pas changé de page 
            setTimeout(() => {
                logo.classList.remove("chargementActif");
            }, 6000);
        });
    });

} catch (error) {
    
}

</script>