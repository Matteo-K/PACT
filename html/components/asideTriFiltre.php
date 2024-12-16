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
            <div>
                <h2>Trier</h2>
                <img src="img/icone/croix_blanche.png" alt="Fermer l'onglet tri">
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
                    <label for="dateCreationRecent">Créé récemment</label>
                    <input type="radio" name="tri" id="dateCreationAncien">
                    <label for="dateCreationAncien">Plus anciennne</label>
                </div>
            </div>
        </aside>
        <aside id="filtre" class="asdTriFiltre">
            <div>
                <h2>Filtrer</h2>
                <img src="img/icone/croix_blanche.png" alt="Fermer l'onglet filtre">
            </div>
            <div class="blcTriFiltre">
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
                    <ul>
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
                        <input type="time" name="heureDebut" id="heureDebut" value="08:00">
                    </div>
                    <div>
                        <label for="dateDepart">Fin&nbsp;:&nbsp;</label>
                        <input type="date" name="dateFin" id="dateFin" value="<?php echo date('Y-m-d', strtotime('+7 day')); ?>" min="<?php echo date('Y-m-d'); ?>">
                        <input type="time" name="heureFin" id="heureFin" value="23:00">
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
        
        // Ouvre et ferme le pop-up tri et filtre pour la partie mobile
        const btnFiltre = document.querySelector("#btnFiltre");
        const btnTri = document.querySelector("#btnTri");
        const asideTri = document.querySelector("#tri");
        const asideFiltre = document.querySelector("#filtre");
        const fermeTri = document.querySelector("#fermeTri");
        const fermeFiltre = document.querySelector("#fermeFiltre");
        const body = document.body;
        
        /**
         * Ouvre et ferme le aside au format mobile
         * Empêche le scroll
         */
        function toggleAside(this) {
            console.log(this);
        }
        
        fermeTri.addEventListener("click", () => toggleAside(asideTri));
        fermeFiltre.addEventListener("click", () => toggleAside(asideFiltre));
        btnTri.addEventListener("click", () => toggleAside(asideTri));
        btnFiltre.addEventListener("click", () => toggleAside(asideFiltre));
        
    });
</script>