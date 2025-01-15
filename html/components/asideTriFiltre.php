<section id="trifiltre" class="asdTriFiltre">
    <div id="btnAside">
        <a onclick="toggleAside(this)" id="btnTri">
            <img src="img/icone/tri.png" alt="tri">
        </a>
        <a onclick="toggleAside(this)" id="btnFiltre">
            <img src="img/icone/filtre.png" alt="filtre">
        </a>
    </div>
    <div>
        <aside id="tri">
            <div id="titreAside">
                <h2>Trier</h2>
                <img src="img/icone/croix_blanche.png" alt="Fermer l'onglet tri" onclick="fermeAside()">
            </div>
            <div class="blcTriFiltre">
                <div>
                    <input type="radio" name="tri" id="miseEnAvant" checked>
                    <label for="miseEnAvant">Mise en avant</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="noteCroissant">
                    <label for="noteCroissant">Note croissant</label>
                    <input type="radio" name="tri" id="noteDecroissant">
                    <label for="noteDecroissant">Note décroissant</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="prixCroissant">
                    <label for="prixCroissant">Prix croissant</label>
                    <input type="radio" name="tri" id="prixDecroissant">
                    <label for="prixDecroissant">Prix décroissant</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="avisCroissant">
                    <label for="avisCroissant">Moins noté</label>
                    <input type="radio" name="tri" id="avisDecroissant">
                    <label for="avisDecroissant">Plus noté</label>
                </div>
                <div>
                    <input type="radio" name="tri" id="dateCreationRecent">
                    <label for="dateCreationRecent">Creé récemment</label>
                    <input type="radio" name="tri" id="dateCreationAncien">
                    <label for="dateCreationAncien">Plus anciennne</label>
                </div>
            </div>
        </aside>
        <aside id="filtre" class="asdTriFiltre">
            <div id="titreAside">
                <h2>Filtrer</h2>
                <img src="img/icone/croix_blanche.png" alt="Fermer l'onglet filtre" onclick="fermeAside()">
            </div>
            <div class="blcTriFiltre">
                <?php if ($typeUser == "pro_public" || $typeUser == "pro_prive") { ?>
                    <div id="statut">
                        <h3>En ligne / Hors ligne</h3>
                        <div>
                            <label for="enLigne">
                                En ligne
                                <input type="checkbox" name="statutEnLigneHorsLigne" id="enLigne">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label for="horsLigne">
                                Hors ligne
                                <input type="checkbox" name="statutEnLigneHorsLigne" id="horsLigne">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                   <?php } ?>
                <div id="note">
                    <h3>Par note</h3>
                    <div>
                        <div>
                            <label for="star1" class="blocStar">
                                <input type="checkbox" name="star1" id="star1">
                                <span class="checkmark"></span>
                                <div class="star"></div>
                            </label>
                        </div>
                        <div>
                            <label for="star2" class="blocStar">
                                <input type="checkbox" name="star2" id="star2">
                                <span class="checkmark"></span>
                                <div class="star"></div>
                                <div class="star"></div>
                            </label>
                        </div>
                        <div>    
                            <label for="star3" class="blocStar">
                                <input type="checkbox" name="star3" id="star3">
                                <span class="checkmark"></span>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            </label>
                        </div>
                        <div>
                            <label for="star4" class="blocStar">
                                <input type="checkbox" name="star4" id="star4">
                                <span class="checkmark"></span>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            </label>
                        </div>
                        <div>
                            <label for="star5" class="blocStar">
                                <input type="checkbox" name="star5" id="star5">
                                <span class="checkmark"></span>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
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
                            <label for="ouvert">
                                Ouvert
                                <input type="checkbox" name="statut" id="ouvert">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label for="ferme">
                                Fermé
                                <input type="checkbox" name="statut" id="ferme">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div id="categorie">
                    <h3>Par catégorie</h3>
                    <ul id="idCategorie">
                        <li>
                            <label for="Visite">
                                <input type="checkbox" name="categorie" id="Visite">
                                <span class="checkmark"></span>
                                Visite
                            </label>
                        </li>
                        <li>
                            <label for="Activite">
                                <input type="checkbox" name="categorie" id="Activite">
                                <span class="checkmark"></span>
                                Activité
                            </label>
                        </li>
                        <li>
                            <label for="Spectacle">
                                <input type="checkbox" name="categorie" id="Spectacle">
                                <span class="checkmark"></span>
                                Spectacle
                            </label>
                        </li>
                        <li>
                            <label for="Restauration">
                                <input type="checkbox" name="categorie" id="Restauration">
                                <span class="checkmark"></span>
                                Restauration
                            </label>
                        </li>
                        <li>
                            <label for="Parc">
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
    </div>
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
    const btnTri = document.getElementById('btnTri');
    const btnFiltre = document.getElementById('btnFiltre');
    
    function toggleAside(element) {

        btnTri.classList.remove('btnAsideOpen');
        btnFiltre.classList.remove('btnAsideOpen');
        
        if (element.id === 'btnTri') {
            if (asideTri.classList.contains("openAside")) {
                asideTri.classList.remove('openAside');
            } else {
                asideTri.classList.add('openAside');
                asideFiltre.classList.remove('openAside');
                btnTri.classList.add('btnAsideOpen');
            }
        } else {
            if (asideFiltre.classList.contains("openAside")) {
                asideFiltre.classList.remove('openAside');
            } else {
                asideFiltre.classList.add('openAside');
                asideTri.classList.remove('openAside');
                btnFiltre.classList.add('btnAsideOpen');
            }
        }
    }
    
    
    function fermeAside() {
        btnTri.classList.remove('btnAsideOpen');
        btnFiltre.classList.remove('btnAsideOpen');
        asideTri.classList.remove('openAside');
        asideFiltre.classList.remove('openAside');
    }
</script>