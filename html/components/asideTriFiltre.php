
<section id="trifiltre" class="asdTriFiltre">
    <div id="btnAside">
        <a tabindex="0" id="btnTri">
            <img src="img/icone/tri.png" alt="tri">
        </a>
        <a tabindex="0" id="btnFiltre">
            <img src="img/icone/filtre.png" alt="filtre">
            <span id="filtreApplique"></span>
        </a>
        <a tabindex="0" id="btnCarte">
            <img src="img/icone/lieu.png" alt="icone du pointer pour la carte">
        </a>
        <a tabindex="0" id="btnReset">
            <img src="img/icone/reset.png" alt="rénitialisation tris, filtres et recherche">
        </a>
    </div>
    <div>
        <aside id="tri">
            <div id="titreAside">
                <h2>Trier</h2>
                <img tabindex="0" src="img/icone/croix.png" alt="Fermer l'onglet tri" onclick="fermeAside()">
            </div>
            <div class="blcTriFiltre">
                <div>
                    <input type="radio" name="tri" id="miseEnAvant" checked>
                    <label tabindex="0" for="miseEnAvant">Mise en avant</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="noteCroissant">
                    <label tabindex="0" for="noteCroissant">Note croissant</label>
                    <input type="radio" name="tri" id="noteDecroissant">
                    <label tabindex="0" for="noteDecroissant">Note décroissant</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="prixCroissant">
                    <label tabindex="0" for="prixCroissant">Prix croissant</label>
                    <input type="radio" name="tri" id="prixDecroissant">
                    <label tabindex="0" for="prixDecroissant">Prix décroissant</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="avisCroissant">
                    <label tabindex="0" for="avisCroissant">Moins noté</label>
                    <input type="radio" name="tri" id="avisDecroissant">
                    <label tabindex="0" for="avisDecroissant">Plus noté</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="dateCreationRecent">
                    <label tabindex="0" for="dateCreationRecent">Creé récemment</label>
                    <input type="radio" name="tri" id="dateCreationAncien">
                    <label tabindex="0" for="dateCreationAncien">Plus anciennne</label>
                </div>
            </div>
        </aside>
        <aside id="filtre" class="asdTriFiltre">
            <div id="titreAside">
                <h2>Filtrer</h2>
                <img tabindex="0" src="img/icone/croix.png" alt="Fermer l'onglet filtre" onclick="fermeAside()">
            </div>
            <div class="blcTriFiltre">
                <?php if ($typeUser == "pro_public" || $typeUser == "pro_prive") { ?>
                    <div id="statut">
                        <h3>En ligne / Hors ligne</h3>
                        <div>
                            <label tabindex="0" for="enLigne">
                                En ligne
                                <input type="checkbox" name="statutEnLigneHorsLigne" id="enLigne">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label tabindex="0" for="horsLigne">
                                Hors ligne
                                <input type="checkbox" name="statutEnLigneHorsLigne" id="horsLigne">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                   <?php } ?>
                <div id="note">
                    <h3>Par note</h3>
                    <div id="blcStarAside">
                        <div>
                            <label tabindex="0" for="star1" class="blocStar">
                                <input type="checkbox" name="star1" id="star1">
                                <span class="checkStar star vide">1</span>
                            </label>
                        </div>
                        <div>
                            <label tabindex="0" for="star2" class="blocStar">
                                <input type="checkbox" name="star2" id="star2">
                                <span class="checkStar star vide">2</span>
                            </label>
                        </div>
                        <div>    
                            <label tabindex="0" for="star3" class="blocStar">
                                <input type="checkbox" name="star3" id="star3">
                                <span class="checkStar star vide">3</span>
                            </label>
                        </div>
                        <div>
                            <label tabindex="0" for="star4" class="blocStar">
                                <input type="checkbox" name="star4" id="star4">
                                <span class="checkStar star vide">4</span>
                            </label>
                        </div>
                        <div>
                            <label tabindex="0" for="star5" class="blocStar">
                                <input type="checkbox" name="star5" id="star5">
                                <span class="checkStar star vide">5</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div id="blcPrixStatut">
                    <div id="prix">
                        <h3>Par prix</h3>
                        <div>
                            <label for="prixMin">De</label>
                            <select name="prixMin" id="prixMin">
                                <option value="0" selected>0€</option>
                                <option value="10">10€</option>
                                <option value="20">20€</option>
                                <option value="30">30€</option>
                                <option value="40">40€</option>
                                <option value="50">50€</option>
                                <option value="60">60€</option>
                                <option value="70">70€</option>
                                <option value="999999">80€ et +</option>
                            </select>
                        </div>
                        <div>
                            <label for="prixMax">à</label>
                            <select name="prixMax" id="prixMax">
                                <option value="0">0€</option>
                                <option value="10">10€</option>
                                <option value="20">20€</option>
                                <option value="30">30€</option>
                                <option value="40">40€</option>
                                <option value="50">50€</option>
                                <option value="60">60€</option>
                                <option value="70">70€</option>
                                <option value="999999" selected>80€ et +</option>
                            </select>
                        </div>
                    </div>
                    <div id="statut">
                        <h3>Par statut</h3>
                        <div>
                            <label tabindex="0" for="ouvert">
                                Ouvert
                                <input type="checkbox" name="statut" id="ouvert">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div id="categorie">
                    <h3>Par catégorie</h3>
                    <ul id="idCategorie">
                        <li>
                            <label tabindex="0" for="Visite">
                                <input type="checkbox" name="categorie" id="Visite">
                                <span class="checkmark"></span>
                                Visite
                            </label>
                        </li>
                        <li>
                            <label tabindex="0" for="Activite">
                                <input type="checkbox" name="categorie" id="Activite">
                                <span class="checkmark"></span>
                                Activité
                            </label>
                        </li>
                        <li>
                            <label tabindex="0" for="Spectacle">
                                <input type="checkbox" name="categorie" id="Spectacle">
                                <span class="checkmark"></span>
                                Spectacle
                            </label>
                        </li>
                        <li>
                            <label tabindex="0" for="Restauration">
                                <input type="checkbox" name="categorie" id="Restauration">
                                <span class="checkmark"></span>
                                Restauration
                            </label>
                        </li>
                        <li>
                            <label tabindex="0" for="Parc">
                                <input type="checkbox" name="categorie" id="Parc">
                                <span class="checkmark"></span>
                                Parc d’attractions
                            </label>
                        </li>
                    </ul>
                </div>
                <div id="date">
                    <h3>Par date</h3>
                    <div>
                        <label for="dateDepart">Départ&nbsp;:&nbsp;</label>
                        <input type="date" name="dateDepart" id="dateDepart" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <label for="dateDepart">Fin&nbsp;:&nbsp;</label>
                        <input type="date" name="dateFin" id="dateFin" value="<?php echo date('Y-m-d', strtotime('+7 day')); ?>" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div id="heure">
                    <h3>Par heure</h3>
                    <div>
                        <label for="dateDepart">Départ&nbsp;:&nbsp;</label>
                        <input type="time" name="heureDebut" id="heureDebut">
                    </div>
                    <div>
                        <label for="dateDepart">Fin&nbsp;:&nbsp;</label>
                        <input type="time" name="heureFin" id="heureFin">
                    </div>
                </div>
            </div>
        </aside>
        <aside id="carte_offres">
            <div id="map">
                <img tabindex="0" src="img/icone/croix.png" alt="Fermer l'onglet tri" onclick="fermeAside()">
            </div>
            
        </aside>
    </div>
</section>
<section class="modal">
    <section class="modal-content">
        <span class="close" onclick="resetModal_close()">×</span>
        <section class="titre">
            <h2>⚠️ Attention : la réinitialisation des filtres, tris et des paramètres de recherche vont effacer toutes vos sélections en cours.</h2>
            <p class="taille">Souhaitez-vous continuer ?</p>
        </section>
        <section id="btn-action">
            <div>
                <button class="modifierBut" onclick="resetModal_close()">Annuler</button>
            </div>
            <div class="taillebtn">
                <button class="modifierBut" onclick="resetModal_close()">Effacer les sélections</button>
            </div>
        </section>
    </section>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        
        document.querySelector('#prixMin').addEventListener('change', inverseValuesPrix);
        document.querySelector('#prixMax').addEventListener('change', inverseValuesPrix);
                
        /**
         * Switch les valeurs des prix maximum et minimum si prix maximum < prix minimum
         */
        function inverseValuesPrix () {
            const selectMin = document.querySelector('#prixMin');
            const selectMax = document.querySelector('#prixMax');
            const valueMin = parseInt(selectMin.value);
            const valueMax = parseInt(selectMax.value);
            
            if (valueMin > valueMax) {
                selectMin.value = valueMax;
                selectMax.value = valueMin;
            }
        }    
    });

    /**
     * Ouvre et ferme le aside
     */
    const asideTri = document.querySelector("#tri");
    const asideFiltre = document.querySelector("#filtre");
    const asideCarte = document.querySelector("#carte_offres");
    const btnTri = document.getElementById('btnTri');
    const btnFiltre = document.getElementById('btnFiltre');
    
    let previousAside = null;

    function toggleAside(element) {
        const asideElements = {
            'btnTri': asideTri,
            'btnFiltre': asideFiltre,
            'btnCarte': asideCarte
        };

        const clickedAside = asideElements[element.id];

        if (previousAside === clickedAside && clickedAside.classList.contains('openAside')) {
            clickedAside.classList.remove('openAside');
            element.classList.remove('btnAsideOpen');
            previousAside = null;
            return;
        }

        Object.keys(asideElements).forEach(id => {
            document.getElementById(id).classList.remove('btnAsideOpen');
        });

        Object.values(asideElements).forEach(aside => {
            aside.classList.remove('openAside');
        });

        if (clickedAside) {
            clickedAside.classList.add('openAside');
            element.classList.add('btnAsideOpen');
            previousAside = clickedAside;
        }
    }


    function fermeAside() {
        const buttons = document.querySelectorAll('[id^="btn"]');
        buttons.forEach(button => {
            button.classList.remove('btnAsideOpen');
        });

        // Fermer tous les aside
        const asideElements = [asideTri, asideFiltre, asideCarte];
        asideElements.forEach(aside => {
            aside.classList.remove('openAside');
        });
    }

    const btnResetModal = document.getElementById("btnReset");
    const modal = document.querySelector("#trifiltre + .modal");
    function resetModal() {
        if (btnResetModal.getAttibute("disabled")) {
            modal.style.display = "flex";
            document.body.classList.add("no-scroll");
        }
    }
    
    function resetModal_close() {
        modal.style.display = "none";
        document.body.classList.remove("no-scroll");
    }
    
    let map = L.map('map', {
        center: [48.46, -2.85], 
        zoom: 9
    });
    L.tileLayer('/components/proxy.php?z={z}&x={x}&y={y}', {
          maxZoom: 22
      }).addTo(map);
    let markers = new L.MarkerClusterGroup();

    
    const btnAside = document.querySelectorAll("#btnAside a") || [];
    if (btnAside.length > 0) {
        btnAside.forEach(element => {
            if (element.id !== "btnReset") {
                element.addEventListener("click", () => toggleAside(element));
                element.addEventListener("keydown", (event) => {
                    if (event.key === "Enter") {
                        toggleAside(element);
                    }
                });
            } else {
                element.addEventListener("click", () => resetModal());
                element.addEventListener("keydown", (event) => {
                    if (event.key === "Enter") {
                        resetModal();
                    }
                });
            }
        });
    }

    const img_fermeAside = document.querySelectorAll("aside img");
    img_fermeAside.forEach(img => {
        img.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                fermeAside();
            }
        });
    });

    document.getElementById('btnCarte').addEventListener('click', function() {
        // Attendre un court instant avant de recalculer la taille
        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    });

</script>
