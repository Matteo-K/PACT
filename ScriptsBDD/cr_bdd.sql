DROP SCHEMA IF EXISTS pact CASCADE;

CREATE SCHEMA pact;

SET SCHEMA 'pact';

-- Création des comptes --

CREATE TABLE _utilisateur (
  idU SERIAL PRIMARY KEY,
  password VARCHAR(255) NOT NULL,
  apikey VARCHAR(255) DEFAULT NULL,
  tokken VARCHAR(255) DEFAULT NULL
);

CREATE TABLE _admin (
  idU INT PRIMARY KEY,
  login VARCHAR(255) UNIQUE,
  CONSTRAINT _admin_fk_utilisateur
      FOREIGN KEY (idU) 
      REFERENCES _utilisateur(idU)
);

CREATE TABLE _nonAdmin (
  idU INT PRIMARY KEY,
  telephone VARCHAR(255) NOT NULL,
  mail VARCHAR(255) NOT NULL UNIQUE,
  CONSTRAINT _nonAdmin_fk_utilisateur
      FOREIGN KEY (idU) 
      REFERENCES _utilisateur(idU)
);

CREATE TABLE _pro (
  idU INT PRIMARY KEY,
  denomination VARCHAR(255) UNIQUE NOT NULL,
  CONSTRAINT _pro_fk_nonAdmin
      FOREIGN KEY (idU) 
      REFERENCES _nonAdmin(idU)
);

CREATE TABLE _membre (
  idU INT PRIMARY KEY,
  pseudo VARCHAR(255) NOT NULL,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  CONSTRAINT _membre_fk_nonAdmin
      FOREIGN KEY (idU) 
      REFERENCES _nonAdmin(idU)
);

-- Mise à jour de la table historiqueMessage --
CREATE TABLE _historiqueMessage (
  id SERIAL PRIMARY KEY,
  heure TIMESTAMP NOT NULL,
  content VARCHAR(500) NOT NULL,
  contentLength INT NOT NULL,
  idExpediteur INT NOT NULL, -- Identifiant de l'expéditeur
  typeExpediteur VARCHAR(10) NOT NULL, -- "membre" ou "pro"
  lu BOOLEAN DEFAULT false,
  CONSTRAINT _historiqueMessage_check_typeExpediteur
      CHECK (typeExpediteur IN ('membre', 'pro'))
);

CREATE TABLE _tchatator (
  idMembre INT NOT NULL,
  idPro INT NOT NULL,
  idMessage INT NOT NULL,
  CONSTRAINT pk_tchatator PRIMARY KEY (idMembre, idPro, idMessage),
  CONSTRAINT _tchatator_fk_idMembre
      FOREIGN KEY (idMembre) 
      REFERENCES _membre(idU),
  CONSTRAINT _tchatator_fk_idPro
      FOREIGN KEY (idPro) 
      REFERENCES _pro(idU),
  CONSTRAINT _tchatator_fk_idMessage
      FOREIGN KEY (idMessage) 
      REFERENCES _historiqueMessage(id)
);

CREATE TABLE _public (
  idU INT PRIMARY KEY,
  CONSTRAINT _public_fk_pro
      FOREIGN KEY (idU) 
      REFERENCES _pro(idU)
);

CREATE TABLE _privee (
  idU INT PRIMARY KEY,
  siren VARCHAR(255) UNIQUE NOT NULL,
  CONSTRAINT _privee_fk_pro
      FOREIGN KEY (idU) 
      REFERENCES _pro(idU)
);

-- Création de la partie des offres --

CREATE TABLE _adresse (
  codePostal VARCHAR(255) NOT NULL,
  ville VARCHAR(255) NOT NULL,
  pays VARCHAR(255) NOT NULL,
  rue VARCHAR(255) NOT NULL,
  numeroRue VARCHAR(255) NOT NULL,
  PRIMARY KEY (numeroRue, rue, ville, pays, codePostal)
);

CREATE TABLE _abonnement (
  nomAbonnement VARCHAR(255) PRIMARY KEY,
  tarif FLOAT NOT NULL
);

CREATE TABLE _statut (
  statut VARCHAR(255) PRIMARY KEY
);

CREATE TABLE _tag (
  nomTag VARCHAR(255) PRIMARY KEY
);

CREATE TABLE _jour (
  jour VARCHAR(255) PRIMARY KEY
);

CREATE TABLE _option (
  nomOption VARCHAR(255) PRIMARY KEY,
  prixOffre FLOAT NOT NULL,
  dureeOption INTEGER NOT NULL
);

CREATE TABLE _langue (
  langue VARCHAR(255) PRIMARY KEY
);

CREATE TABLE _offre (
  idU INT NOT NULL,
  statut VARCHAR(255),
  idOffre SERIAL PRIMARY KEY,
  nom VARCHAR(255),
  description VARCHAR(1000),
  mail VARCHAR(255),
  telephone VARCHAR(255),
  affiche BOOLEAN,
  urlSite VARCHAR(255),
  resume VARCHAR(255),
  dateCrea TIMESTAMP,
  CONSTRAINT _offre_fk_pro
      FOREIGN KEY (idU)
      REFERENCES _pro(idU),
  CONSTRAINT _offre_fk_statut
      FOREIGN KEY (statut)
      REFERENCES _statut(statut)
);

CREATE TABLE _image (
  url VARCHAR(255) PRIMARY KEY,
  nomImage VARCHAR(255) NOT NULL
);

CREATE TABLE _illustre (
  idOffre INT,
  url VARCHAR(255),
  PRIMARY KEY (idOffre, url),
  CONSTRAINT _illustre_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre),
  CONSTRAINT _illustre_fk_image
      FOREIGN KEY (url)
      REFERENCES _image(url)
);

CREATE TABLE _photo_profil (
  idU INT,
  url VARCHAR(255),
  PRIMARY KEY (idU, url),
  CONSTRAINT _photo_profil_fk_utilisateur
      FOREIGN KEY (idU)
      REFERENCES _utilisateur(idU),
  CONSTRAINT _photo_profil_fk_image
      FOREIGN KEY (url)
      REFERENCES _image(url)
);

CREATE TABLE _consulter (
  idU INT,
  idOffre INT,
  dateConsultation TIMESTAMP,
  PRIMARY KEY (idU, idOffre),
  CONSTRAINT _consulter_fk_membre
      FOREIGN KEY (idU)
      REFERENCES _membre(idU),
  CONSTRAINT _consulter_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

-- Tables spécifiques aux types d'offres --

CREATE TABLE _parcAttraction (
  idOffre INT PRIMARY KEY,
  ageMin INT NOT NULL,
  nbAttraction INT NOT NULL,
  prixMinimal FLOAT NOT NULL,
  urlPlan VARCHAR(255) NOT NULL,
  CONSTRAINT _parcAttraction_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _spectacle (
  idOffre INT PRIMARY KEY,
  duree INT NOT NULL,
  nbPlace INT NOT NULL,
  prixMinimal FLOAT NOT NULL,
  CONSTRAINT _spectacle_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _activite (
  idOffre INT PRIMARY KEY,
  duree INT NOT NULL,
  ageMin INT NOT NULL,
  prixMinimal FLOAT NOT NULL,
  CONSTRAINT _activite_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _restauration (
  idOffre INT PRIMARY KEY,
  gammeDePrix VARCHAR(3) NOT NULL,
  CONSTRAINT _restauration_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _menu (
  menu VARCHAR(255),
  idOffre INT,
  CONSTRAINT _restauration_fk_menu
      FOREIGN KEY (idOffre)
      REFERENCES _restauration(idOffre)
);

CREATE TABLE _visite (
  idOffre INT PRIMARY KEY,
  guide BOOLEAN NOT NULL,
  duree INT NOT NULL,
  prixMinimal FLOAT NOT NULL,
  accessibilite BOOLEAN NOT NULL,
  CONSTRAINT _visite_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

-- Tables pour les horaires --

CREATE TABLE _horaireSoir (
  jour VARCHAR(255),
  idOffre INT,
  heureOuverture VARCHAR(255) NOT NULL,
  heureFermeture VARCHAR(255) NOT NULL,
  PRIMARY KEY (jour, idOffre),
  CONSTRAINT _horaireSoir_fk_jour
      FOREIGN KEY (jour)
      REFERENCES _jour(jour),
  CONSTRAINT _horaireSoir_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _horaireMidi (
  jour VARCHAR(255),
  idOffre INT,
  heureOuverture VARCHAR(255) NOT NULL,
  heureFermeture VARCHAR(255) NOT NULL,
  PRIMARY KEY (jour, idOffre),
  CONSTRAINT _horaireMidi_fk_jour
      FOREIGN KEY (jour)
      REFERENCES _jour(jour),
  CONSTRAINT _horaireMidi_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _horairePrecise (
  jour VARCHAR(255) NOT NULL,
  idOffre INT NOT NULL,
  heureDebut VARCHAR(255) NOT NULL,
  heureFin VARCHAR(255) NOT NULL,
  DateRepresentation DATE NOT NULL,
  PRIMARY KEY(jour,idOffre,heureDebut),
  CONSTRAINT _horairePrecise_fk_jour
      FOREIGN KEY (jour)
      REFERENCES _jour(jour),
  CONSTRAINT _horairePrecise_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _tag_parc (
  idOffre INTEGER,
  nomTag VARCHAR(255),
  PRIMARY KEY(idOffre,nomTag),
  CONSTRAINT _tag_parc_fk_offre
        FOREIGN KEY(idOffre)
        REFERENCES _parcAttraction(idOffre),
  CONSTRAINT _tag_parc_fk_tag
        FOREIGN KEY (nomTag)
        REFERENCES _tag(nomTag)
  );

CREATE TABLE _tag_spec (
  idOffre INTEGER,
  nomTag VARCHAR(255),
  PRIMARY KEY(idOffre,nomTag),
  CONSTRAINT _tag_spec_fk_offre
        FOREIGN KEY(idOffre)
        REFERENCES _spectacle(idOffre),
  CONSTRAINT _tag_spec_fk_tag
        FOREIGN KEY (nomTag)
        REFERENCES _tag(nomTag)
  );
  
CREATE TABLE _tag_Act (
  idOffre INTEGER,
  nomTag VARCHAR(255),
  PRIMARY KEY(idOffre,nomTag),
  CONSTRAINT _tag_Act_fk_offre
        FOREIGN KEY(idOffre)
        REFERENCES _activite(idOffre),
  CONSTRAINT _tag_Act_fk_tag
        FOREIGN KEY (nomTag)
        REFERENCES _tag(nomTag)
  );
  
CREATE TABLE _tag_restaurant (
  idOffre INTEGER,
  nomTag VARCHAR(255),
  PRIMARY KEY(idOffre,nomTag),
  CONSTRAINT _tag_restaurant_fk_offre
        FOREIGN KEY(idOffre)
        REFERENCES _restauration(idOffre),
  CONSTRAINT _tag_restaurant_fk_tag
        FOREIGN KEY (nomTag)
        REFERENCES _tag(nomTag)
  );
  
CREATE TABLE _tag_visite (
  idOffre INTEGER,
  nomTag VARCHAR(255),
  PRIMARY KEY(idOffre,nomTag),
  CONSTRAINT _tag_visite_fk_offre
        FOREIGN KEY(idOffre)
        REFERENCES _visite(idOffre),
  CONSTRAINT _tag_visite_fk_tag
        FOREIGN KEY (nomTag)
        REFERENCES _tag(nomTag)
  );

-- Contraintes --

CREATE TABLE _habite (
  idU INT PRIMARY KEY,
  codePostal VARCHAR(255),
  ville VARCHAR(255),
  pays VARCHAR(255),
  rue VARCHAR(255),
  numeroRue VARCHAR(255),
  CONSTRAINT _habite_fk_nonAdmin
      FOREIGN KEY (idU)
      REFERENCES _nonAdmin(idU),
  CONSTRAINT _habite_fk_adresse
      FOREIGN KEY (numeroRue, rue, ville, pays, codePostal)
      REFERENCES _adresse(numeroRue, rue, ville, pays, codePostal)
);

CREATE TABLE _localisation (
  idOffre INT,
  codePostal VARCHAR(255) NOT NULL,
  ville VARCHAR(255) NOT NULL,
  pays VARCHAR(255) NOT NULL,
  rue VARCHAR(255) NOT NULL,
  numeroRue VARCHAR(255) NOT NULL,
  PRIMARY KEY (idOffre,numeroRue, rue, ville, pays, codePostal),
  CONSTRAINT _localisation_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre),
  CONSTRAINT _localisation_fk_adresse
      FOREIGN KEY (numeroRue, rue, ville, pays, codePostal)
      REFERENCES _adresse(numeroRue, rue, ville, pays, codePostal)
);

-- Table pour les abonnements et options --

CREATE TABLE _abonner (
  idOffre INT PRIMARY KEY,
  nomAbonnement VARCHAR(255),
  CONSTRAINT _abonner_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre),
  CONSTRAINT _abonner_fk_abonnement
      FOREIGN KEY (nomAbonnement)
      REFERENCES _abonnement(nomAbonnement)
);

-- Triggers pour gérer les options et langues des visites --

CREATE TABLE _visite_langue (
  idOffre INT,
  langue VARCHAR(255),
  PRIMARY KEY (idOffre, langue),
  CONSTRAINT _visite_langue_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _visite(idOffre),
  CONSTRAINT _visite_langue_fk_langue
      FOREIGN KEY (langue)
      REFERENCES _langue(langue)
);

CREATE TABLE _accessibilite (
  nomAccess VARCHAR(255) PRIMARY KEY NOT NULL
);

CREATE TABLE _offreAccess (
  idOffre INT NOT NULL,
  nomAccess VARCHAR(255) NOT NULL,
  PRIMARY KEY (idOffre,nomAccess),
  CONSTRAINT _offreAccess_nom
      FOREIGN KEY (nomAccess)
      REFERENCES _accessibilite(nomAccess),
  CONSTRAINT _offreAccess_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _prestation (
  nomPresta VARCHAR(255) PRIMARY KEY NOT NULL
);

CREATE TABLE _offrePrestation_non_inclu (
  idOffre INT NOT NULL,
  nomPresta VARCHAR(255) NOT NULL,
  PRIMARY KEY (idOffre,nomPresta),
  CONSTRAINT _offrePrestation_nom
      FOREIGN KEY (nomPresta)
      REFERENCES _prestation(nomPresta),
  CONSTRAINT _offrePrestation_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _offrePrestation_inclu (
  idOffre INT NOT NULL,
  nomPresta VARCHAR(255) NOT NULL,
  PRIMARY KEY (idOffre,nomPresta),
  CONSTRAINT _offrePrestation_nom
      FOREIGN KEY (nomPresta)
      REFERENCES _prestation(nomPresta),
  CONSTRAINT _offrePrestation_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _commentaire(
  idU INT NOT NULL,
  idC SERIAL PRIMARY KEY,
  content VARCHAR(1000) NOT NULL,
  datePublie TIMESTAMP NOT NULL,
  nbLike INT DEFAULT 0,
  nbDislike INT DEFAULT 0,
  CONSTRAINT _commentaire_fk_idU
      FOREIGN KEY (idU)
      REFERENCES _nonAdmin(idU)
);

CREATE TABLE _avis(
  idC INT NOT NULL,
  idOffre INT NOT NULL,
  note INT NOT NULL,
  companie VARCHAR(255) NOT NULL,
  mois VARCHAR (20) NOT NULL,
  annee VARCHAR(4) NOT NULL,
  titre VARCHAR(255) NOT NULL,
  lu BOOLEAN NOT NULL,
  PRIMARY KEY (idC),
  CONSTRAINT _avis_fk_idC
      FOREIGN KEY (idC)
      REFERENCES _commentaire(idC),
  CONSTRAINT _offre_fk_idOffre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _reponse(
  idC INT NOT NULL,
  ref INT NOT NULL,
  PRIMARY KEY (idC,ref),
  CONSTRAINT _reponse_fk_idC
      FOREIGN KEY (idC)
      REFERENCES _commentaire(idC),
  CONSTRAINT _reponse_fk_avis
      FOREIGN KEY (ref)
      REFERENCES _avis(idC)
);

CREATE TABLE _avisImage(
  idC INT NOT NULL,
  url VARCHAR(255) NOT NULL,
  PRIMARY KEY (idC,url),
  CONSTRAINT _avisImage_fk_image
      FOREIGN KEY (url)
      REFERENCES _image(url),
  CONSTRAINT _avisImage_fk_avis
      FOREIGN KEY (idC)
      REFERENCES _avis(idC)
);

CREATE TABLE _dateOption(
  idOption SERIAL PRIMARY KEY,
  dateLancement DATE,
  dateFin DATE,
  duree INT NOT NULL,
  prix float NOT NULL
);

CREATE TABLE _option_offre(
  idOption INT NOT NULL,
  idOffre INT NOT NULL,
  nomOption VARCHAR(255) NOT NULL,
  PRIMARY KEY(idOffre, nomOption, idOption),
  CONSTRAINT _historiqueOption_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre),
  CONSTRAINT _historiqueOption_fk_option
      FOREIGN KEY (nomOption)
      REFERENCES _option(nomOption),
  CONSTRAINT _historiqueOption_fk_date
      FOREIGN KEY (idOption)
      REFERENCES _dateOption(idOption)
);

CREATE TABLE _historiqueStatut(
  idOffre INT NOT NULL,
  idStatut SERIAL PRIMARY KEY,
  dateLancement DATE NOT NULL,
  dureeEnLigne INT,
  prixDuree float,
  CONSTRAINT _historiqueOption_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _facturation(
  idFacture SERIAL PRIMARY KEY,
  dateFactue DATE NOT NULL,
  idOffre int NOT NULL,
  CONSTRAINT _facturation_fk_offre
      FOREIGN KEY (idOffre)
      REFERENCES _offre(idOffre)
);

CREATE TABLE _signalementC(
  idU INT NOT NULL,
  idC INT NOT NULL,
  dtSignalement TIMESTAMP NOT NULL,
  raison VARCHAR(255) NOT NULL,
  complement VARCHAR(499),
  CONSTRAINT _pk_signalementC PRIMARY KEY (idU,idC),
  CONSTRAINT _signalementC_fk_user
      FOREIGN KEY (idU)
      REFERENCES _nonAdmin(idU),
  CONSTRAINT _signalementC_fk_commentaire
      FOREIGN KEY (idC)
      REFERENCES _commentaire(idC)
);

-- Création des vues pour chaque catégorie d'offres

CREATE VIEW admin AS
    SELECT a.*, u.password, p.url 
    FROM _admin a 
    JOIN _utilisateur u ON a.idU = u.idU JOIN _photo_profil p on u.idU=p.idU;

CREATE VIEW membre AS
    SELECT m.*, u.password, h.numeroRue, h.rue, h.ville, h.pays, h.codepostal, n.telephone, n.mail, p.url
    FROM _membre m 
    JOIN _utilisateur u ON m.idU = u.idU 
    JOIN _habite h ON m.idU = h.idU 
    JOIN _nonAdmin n ON m.idU = n.idU
    JOIN _photo_profil p on u.idU=p.idU;

CREATE VIEW proPublic AS
    SELECT p.*, u.password, n.telephone, n.mail, h.numeroRue, h.rue, h.ville, h.pays, h.codepostal, ph.url
    FROM _pro p 
    JOIN _public pu ON p.idU = pu.idU 
    JOIN _utilisateur u ON p.idU = u.idU 
    JOIN _nonAdmin n ON p.idU = n.idU 
    LEFT JOIN _habite h ON p.idU = h.idU
    JOIN _photo_profil ph on u.idU=ph.idU;

CREATE VIEW proPrive AS
    SELECT p.*, pr.siren, u.password, n.telephone, n.mail, h.numeroRue, h.rue, h.ville, h.pays, h.codepostal, ph.url
    FROM _pro p 
    JOIN _privee pr ON p.idU = pr.idU 
    JOIN _utilisateur u ON p.idU = u.idU 
    LEFT JOIN _nonAdmin n ON p.idU = n.idU 
    JOIN _habite h ON p.idU = h.idU
    JOIN _photo_profil ph on u.idU=ph.idU;

-- Vue pour les parcs d'attractions
CREATE VIEW parcs_attractions AS
    SELECT o.idU, o.idOffre, o.nom AS nom_offre, o.description, o.statut, o.mail, o.telephone, o.affiche, o.urlSite, o.resume, 
           p.ageMin, p.nbAttraction, p.prixMinimal, p.urlPlan, ab.nomAbonnement AS abonnement, ab.tarif 
    FROM _offre o 
    JOIN _parcAttraction p ON o.idOffre = p.idOffre 
    JOIN _abonner a ON o.idOffre = a.idOffre 
    JOIN _abonnement ab ON a.nomAbonnement = ab.nomAbonnement;

-- Vue pour les spectacles
CREATE VIEW spectacles AS
    SELECT o.idU,o.idOffre, o.nom AS nom_offre, o.description, o.statut, o.mail, o.telephone, o.affiche, o.urlSite, o.resume, 
           s.duree, s.nbPlace, s.prixMinimal, ab.nomAbonnement AS abonnement, ab.tarif 
    FROM _offre o 
    JOIN _spectacle s ON o.idOffre = s.idOffre 
    JOIN _abonner a ON o.idOffre = a.idOffre 
    JOIN _abonnement ab ON a.nomAbonnement = ab.nomAbonnement;

-- Vue pour les activités
CREATE VIEW activites AS
    SELECT o.idU,o.idOffre, o.nom AS nom_offre, o.description, o.statut, o.mail, o.telephone, o.affiche, o.urlSite, o.resume, 
           a.duree, a.ageMin, a.prixMinimal, ab.nomAbonnement AS abonnement, ab.tarif 
    FROM _offre o 
    JOIN _activite a ON o.idOffre = a.idOffre 
    JOIN _abonner abo ON o.idOffre = abo.idOffre 
    JOIN _abonnement ab ON abo.nomAbonnement = ab.nomAbonnement;

-- Vue pour les restaurants
CREATE VIEW restaurants AS
    SELECT o.idU,o.idOffre, o.nom AS nom_offre, o.description, o.statut, o.mail, o.telephone, o.affiche, o.urlSite, o.resume, 
           r.gammeDePrix, m.menu, ab.nomAbonnement AS abonnement, ab.tarif 
    FROM _offre o 
    JOIN _restauration r ON o.idOffre = r.idOffre 
    JOIN _abonner a ON o.idOffre = a.idOffre 
    JOIN _abonnement ab ON a.nomAbonnement = ab.nomAbonnement
    JOIN _menu m on a.idOffre=m.idOffre;

-- Vue pour les visites
CREATE VIEW visites AS
    SELECT o.idU,o.idOffre, o.nom AS nom_offre, o.description, o.statut, o.mail, o.telephone, o.affiche, o.urlSite, o.resume, 
           v.guide, v.duree, v.prixMinimal, v.accessibilite, ab.nomAbonnement AS abonnement, ab.tarif 
    FROM _offre o 
    JOIN _visite v ON o.idOffre = v.idOffre 
    JOIN _abonner a ON o.idOffre = a.idOffre 
    JOIN _abonnement ab ON a.nomAbonnement = ab.nomAbonnement;

-- Vue pour les localisations d'offres
CREATE VIEW localisations_offres AS
    SELECT o.idOffre, o.nom AS nom_offre, l.numeroRue, l.rue, l.ville, l.pays, l.codePostal 
    FROM _offre o 
    JOIN _localisation l ON o.idOffre = l.idOffre;
    
CREATE VIEW offres AS
SELECT 
    o.idOffre,
    o.idU,
    o.nom,
    o.description,
    o.resume,
	o.mail,
	o.telephone,
	o.urlsite,
	o.datecrea,
    ab.nomabonnement,
    ARRAY_AGG(DISTINCT i.url) AS listImage,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'jour', hm.jour,
        'heureOuverture', hm.heureOuverture,
        'heureFermeture', hm.heureFermeture
    )::TEXT, ';') FILTER (WHERE hm.jour IS NOT NULL AND hm.heureOuverture IS NOT NULL AND hm.heureFermeture IS NOT NULL) AS listHoraireMidi,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'jour', hs.jour,
        'heureOuverture', hs.heureOuverture,
        'heureFermeture', hs.heureFermeture
    )::TEXT, ';') FILTER (WHERE hs.jour IS NOT NULL AND hs.heureOuverture IS NOT NULL AND hs.heureFermeture IS NOT NULL) AS listHoraireSoir,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'jour', hp.jour,
        'heureOuverture', hp.heureDebut,
        'heureFin', hp.heureFin,
        'dateRepresentation', hp.dateRepresentation
    )::TEXT, ';') FILTER (WHERE hp.jour IS NOT NULL AND hp.heureDebut IS NOT NULL AND hp.dateRepresentation IS NOT NULL) AS listeHorairePrecise,
    l.ville,
    l.numeroRue,
    l.rue,
    l.pays,
    l.codePostal,
    r.gammeDePrix,
    ARRAY_CAT(
        ARRAY_CAT(
            ARRAY_CAT(
                ARRAY_CAT(
                    ARRAY_AGG(DISTINCT ts.nomTag) FILTER (WHERE ts.nomTag IS NOT NULL),
                    ARRAY_AGG(DISTINCT tr.nomTag) FILTER (WHERE tr.nomTag IS NOT NULL)
                ),
                ARRAY_AGG(DISTINCT tv.nomTag) FILTER (WHERE tv.nomTag IS NOT NULL)
            ),
            ARRAY_AGG(DISTINCT ta.nomTag) FILTER (WHERE ta.nomTag IS NOT NULL)
        ),
        ARRAY_AGG(DISTINCT tp.nomTag) FILTER (WHERE tp.nomTag IS NOT NULL)
    ) AS all_tags,
    o.statut,
    CASE
        WHEN EXISTS (SELECT 1 FROM _tag_spec ts WHERE ts.idOffre = o.idOffre) THEN 'Spectacle'
        WHEN EXISTS (SELECT 1 FROM _tag_restaurant tr WHERE tr.idOffre = o.idOffre) THEN 'Restaurant'
        WHEN EXISTS (SELECT 1 FROM _tag_visite tv WHERE tv.idOffre = o.idOffre) THEN 'Visite'
        WHEN EXISTS (SELECT 1 FROM _tag_act ta WHERE ta.idOffre = o.idOffre) THEN 'Activité'
        WHEN EXISTS (SELECT 1 FROM _tag_parc tp WHERE tp.idOffre = o.idOffre) THEN 'Parc Attraction'
        ELSE 'Autre'
    END AS categorie
FROM 
    _offre o
LEFT JOIN 
    _illustre i ON o.idOffre = i.idOffre
LEFT JOIN 
    _horaireSoir hs ON o.idOffre = hs.idOffre
LEFT JOIN 
    _horaireMidi hm ON o.idOffre = hm.idOffre
LEFT JOIN 
    _localisation l ON o.idOffre = l.idOffre
LEFT JOIN 
    _restauration r ON o.idOffre = r.idOffre
LEFT JOIN 
    _tag_spec ts ON o.idOffre = ts.idOffre
LEFT JOIN 
    _tag_restaurant tr ON o.idOffre = tr.idOffre
LEFT JOIN 
    _tag_visite tv ON o.idOffre = tv.idOffre
LEFT JOIN 
    _tag_act ta ON o.idOffre = ta.idOffre
LEFT JOIN 
    _tag_parc tp ON o.idOffre = tp.idOffre
LEFT JOIN
    _horairePrecise hp ON o.idOffre = hp.idOffre
LEFT JOIN
    _abonner ab ON o.idOffre = ab.idOffre
GROUP BY 
    o.idOffre, l.ville, l.numeroRue, l.rue, l.pays, l.codePostal, r.gammeDePrix, ab.nomabonnement
ORDER BY o.dateCrea DESC;

CREATE VIEW offresComplete AS
SELECT 
    o.idOffre,
    o.idU,
    o.nom,
    o.description,
    o.resume,
	o.mail,
	o.telephone,
	o.urlsite,
	o.datecrea,
    ab.nomabonnement,
	pa.urlplan,
    ARRAY_AGG(DISTINCT i.url) AS listImage,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'jour', hm.jour,
        'heureOuverture', hm.heureOuverture,
        'heureFermeture', hm.heureFermeture
    )::TEXT, ';') FILTER (WHERE hm.jour IS NOT NULL AND hm.heureOuverture IS NOT NULL AND hm.heureFermeture IS NOT NULL) AS listHoraireMidi,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'jour', hs.jour,
        'heureOuverture', hs.heureOuverture,
        'heureFermeture', hs.heureFermeture
    )::TEXT, ';') FILTER (WHERE hs.jour IS NOT NULL AND hs.heureOuverture IS NOT NULL AND hs.heureFermeture IS NOT NULL) AS listHoraireSoir,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'jour', hp.jour,
        'heureOuverture', hp.heureDebut,
        'heureFin', hp.heureFin,
        'dateRepresentation', hp.dateRepresentation
    )::TEXT, ';') FILTER (WHERE hp.jour IS NOT NULL AND hp.heureDebut IS NOT NULL AND hp.dateRepresentation IS NOT NULL) AS listeHorairePrecise,
    l.ville,
    l.numeroRue,
    l.rue,
    l.pays,
    l.codePostal,
    r.gammeDePrix,
    ARRAY_CAT(
        ARRAY_CAT(
            ARRAY_CAT(
                ARRAY_CAT(
                    ARRAY_AGG(DISTINCT ts.nomTag) FILTER (WHERE ts.nomTag IS NOT NULL),
                    ARRAY_AGG(DISTINCT tr.nomTag) FILTER (WHERE tr.nomTag IS NOT NULL)
                ),
                ARRAY_AGG(DISTINCT tv.nomTag) FILTER (WHERE tv.nomTag IS NOT NULL)
            ),
            ARRAY_AGG(DISTINCT ta.nomTag) FILTER (WHERE ta.nomTag IS NOT NULL)
        ),
        ARRAY_AGG(DISTINCT tp.nomTag) FILTER (WHERE tp.nomTag IS NOT NULL)
    ) AS all_tags,
    o.statut,
    CASE
        WHEN EXISTS (SELECT 1 FROM _tag_spec ts WHERE ts.idOffre = o.idOffre) THEN 'Spectacle'
        WHEN EXISTS (SELECT 1 FROM _tag_restaurant tr WHERE tr.idOffre = o.idOffre) THEN 'Restaurant'
        WHEN EXISTS (SELECT 1 FROM _tag_visite tv WHERE tv.idOffre = o.idOffre) THEN 'Visite'
        WHEN EXISTS (SELECT 1 FROM _tag_act ta WHERE ta.idOffre = o.idOffre) THEN 'Activité'
        WHEN EXISTS (SELECT 1 FROM _tag_parc tp WHERE tp.idOffre = o.idOffre) THEN 'Parc Attraction'
        ELSE 'Autre'
    END AS categorie
FROM 
    _offre o
LEFT JOIN
	_parcattraction pa on o.idoffre = pa.idoffre
LEFT JOIN 
    _illustre i ON o.idOffre = i.idOffre
LEFT JOIN 
    _horaireSoir hs ON o.idOffre = hs.idOffre
LEFT JOIN 
    _horaireMidi hm ON o.idOffre = hm.idOffre
LEFT JOIN 
    _localisation l ON o.idOffre = l.idOffre
LEFT JOIN 
    _restauration r ON o.idOffre = r.idOffre
LEFT JOIN 
    _tag_spec ts ON o.idOffre = ts.idOffre
LEFT JOIN 
    _tag_restaurant tr ON o.idOffre = tr.idOffre
LEFT JOIN 
    _tag_visite tv ON o.idOffre = tv.idOffre
LEFT JOIN 
    _tag_act ta ON o.idOffre = ta.idOffre
LEFT JOIN 
    _tag_parc tp ON o.idOffre = tp.idOffre
LEFT JOIN
    _horairePrecise hp ON o.idOffre = hp.idOffre
LEFT JOIN
    _abonner ab ON o.idOffre = ab.idOffre
WHERE
    l.ville IS NOT NULL AND
    l.numeroRue IS NOT NULL AND
    l.rue IS NOT NULL AND
    l.pays IS NOT NULL AND
    l.codePostal IS NOT NULL AND
    ab.nomabonnement IS NOT NULl
GROUP BY 
    o.idOffre, l.ville, l.numeroRue, l.rue, l.pays, l.codePostal, r.gammeDePrix, ab.nomabonnement, pa.urlplan
ORDER BY o.dateCrea DESC;

-- Vue pour tous les avis avec offres
CREATE VIEW avis AS
    SELECT  
    m.pseudo,
    c.idC,
    c.content,
    c.datePublie,
    a.idOffre,
    a.note,
    a.companie,
    a.mois,
    a.annee,
    a.titre,
    a.lu,
	c.nblike,
	c.nbdislike,
    ARRAY_AGG(DISTINCT ai.url) FILTER (WHERE ai.url IS NOT NULL) AS listImage
    FROM _avis a 
    JOIN _commentaire c ON a.idC = c.idC
    LEFT JOIN _avisImage ai ON a.idC = ai.idC
    JOIN _membre m ON c.idU = m.idU
    GROUP BY 
    m.pseudo, 
    c.idC, 
    c.content, 
    c.datePublie, 
    a.idOffre,
    a.note,
    a.companie,
    a.mois,
    a.annee,
    a.lu,
    a.titre,
	c.nblike,
	c.nbdislike;

CREATE VIEW reponse AS
    SELECT  
	p.idu as idPro,
    p.denomination,
    c1.idC as idC_reponse,
    c1.content as contenuReponse,
    c1.datePublie as reponseDate,
    r.ref as idC_avis,
    c2.content as contenuAvis,
    c2.datePublie as avisDate,
    a.note,
	c1.nblike as nblikepro,
	c1.nbdislike as nbdislikepro
    FROM _reponse r 
    JOIN _commentaire c1 ON r.idC = c1.idC
    JOIN _pro p ON c1.idU = p.idU
    JOIN _avis a ON r.ref = a.idC
    JOIN _commentaire c2 ON a.idC = c2.idC;
    
CREATE VIEW option AS
    SELECT
    oo.idOffre,
    d.idOption,
    d.dateLancement,
    d.dateFin,
    d.duree as duree_total,
    d.prix as prix_total,
    o.nomOption,
    o.prixOffre as prix_base,
    o.dureeOption as duree_base
FROM 
    _dateOption d
JOIN
    _option_offre oo ON d.idOption = oo.idOption
JOIN
    _option o ON oo.nomOption = o.nomOption;

CREATE VIEW facture AS
    SELECT
    f.idFacture,
    f.dateFactue,
    o.nom,
    o.idOffre,
    o.idU,
    p.denomination,
    h.numeroRue,
    h.rue,
    h.ville,
    h.codePostal,
    h.pays,
    l.numeroRue as numeroRueL,
    l.rue as rueL,
    l.ville as villeL,
    l.codePostal as codePostalL,
    l.pays as paysL,
    a.nomAbonnement,
    a.tarif,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'ID', ht.idStatut,
        'Lancement', ht.dateLancement,
        'Duree', ht.dureeEnLigne,
        'Prix', ht.prixduree
    )::TEXT, ';') FILTER (WHERE ht.idStatut IS NOT NULL 
      AND ht.dateLancement IS NOT NULL
      AND ht.dureeEnLigne IS NOT NULL
      AND ht.dateLancement >= date_trunc('month', f.dateFactue) - INTERVAL '1 month'
      AND ht.dateLancement < date_trunc('month', f.dateFactue)) 
      AS historiqueStatut,
    STRING_AGG(DISTINCT JSONB_BUILD_OBJECT(
        'ID', da.idOption,
        'lancement', da.dateLancement,
        'fin', da.dateFin,
        'duree', da.duree,
        'prix', da.prix,
        'option', op.nomOption,
        'prixBase', op.prixOffre,
        'dureeBase', op.dureeOption
    )::TEXT, ';') FILTER (WHERE da.idOption IS NOT NULL 
      AND da.dateLancement IS NOT NULL 
      AND da.dateFin IS NOT NULL 
      AND da.duree IS NOT NULL 
      AND da.prix IS NOT NULL 
      AND op.nomOption IS NOT NULL 
      AND op.prixOffre IS NOT NULL 
      AND op.dureeOption IS NOT NULL
      AND da.dateFin >= date_trunc('month', f.dateFactue) - INTERVAL '1 month'
      AND da.dateFin < date_trunc('month', f.dateFactue)) 
      AS historiqueOption
    FROM _facturation f
    LEFT JOIN _offre o ON f.idOffre = o.idOffre
    LEFT JOIN _abonner ab ON o.idOffre = ab.idOffre
    LEFT JOIN _abonnement a ON ab.nomAbonnement=a.nomAbonnement
    LEFT JOIN _pro p ON o.idU = p.idU
    LEFT JOIN _habite h ON p.idU = h.idU
    LEFT JOIN _historiqueStatut ht ON o.idOffre = ht.idOffre
    LEFT JOIN _option_offre oo ON o.idOffre = oo.idOffre
    LEFT JOIN _dateOption da ON oo.idOption = da.idOption
    LEFT JOIN _option op ON oo.nomOption = op.nomOption
    LEFT JOIN _localisation l ON o.idOffre = l.idOffre
    GROUP BY 
    f.idFacture,
    f.dateFactue,
    o.nom,
    o.idOffre,
    p.denomination,
    h.numeroRue,
    h.rue,
    l.numeroRue,
    l.rue,
    l.ville,
    l.codePostal,
    l.pays,
    h.ville,
    a.nomAbonnement,
    a.tarif,
    h.codePostal,
    h.pays;

CREATE OR REPLACE VIEW vueMessages AS
SELECT 
    hm.id AS idMessage,
    hm.heure AS dateMessage,
    hm.content AS contenuMessage,
    hm.idExpediteur,
    hm.lu,
    CASE 
        WHEN hm.typeExpediteur = 'membre' THEN m1.pseudo
        WHEN hm.typeExpediteur = 'pro' THEN p1.denomination
    END AS nomExpediteur,
    hm.typeExpediteur,
    CASE
        WHEN hm.typeExpediteur = 'pro' THEN t.idmembre
        WHEN hm.typeExpediteur = 'membre' THEN t.idpro
    END AS idReceveur,
    CASE 
        WHEN hm.typeExpediteur = 'membre' THEN p2.denomination
        WHEN hm.typeExpediteur = 'pro' THEN m2.pseudo
    END AS nomReceveur
FROM 
    _historiqueMessage hm
LEFT JOIN _tchatator t ON t.idMessage = hm.id
LEFT JOIN _membre m1 ON hm.idExpediteur = m1.idU
LEFT JOIN _pro p1 ON hm.idExpediteur = p1.idU
LEFT JOIN _membre m2 ON t.idMembre = m2.idU
LEFT JOIN _pro p2 ON t.idPro = p2.idU;

    
-- Fonction associée au trigger
CREATE OR REPLACE FUNCTION trigger_insert_into_vueMessages()
RETURNS TRIGGER AS $$
DECLARE
    newMessageId INT;
BEGIN
    -- Insérer dans _historiqueMessage
    INSERT INTO pact._historiqueMessage (heure, content, contentLength, idExpediteur, typeExpediteur)
    VALUES (
        NEW.dateMessage,
        NEW.contenuMessage,
        CHAR_LENGTH(NEW.contenuMessage),
        NEW.idExpediteur,
        NEW.typeExpediteur
    )
    RETURNING id INTO newMessageId;

    -- Insérer dans _tchatator
    IF NEW.typeExpediteur = 'membre' THEN
        -- Si l'expéditeur est un membre, le receveur est un pro
        INSERT INTO _tchatator (idMembre, idPro, idMessage)
        VALUES (NEW.idExpediteur, NEW.idReceveur, newMessageId);
    ELSIF NEW.typeExpediteur = 'pro' THEN
        -- Si l'expéditeur est un pro, le receveur est un membre
        INSERT INTO _tchatator (idMembre, idPro, idMessage)
        VALUES (NEW.idReceveur, NEW.idExpediteur, newMessageId);
    END IF;

    RETURN NULL; -- La vue ne stocke pas directement les données
END;
$$ LANGUAGE plpgsql;

-- Création du trigger sur la vue
CREATE TRIGGER trigger_insert_vueMessages
INSTEAD OF INSERT ON vueMessages
FOR EACH ROW
EXECUTE FUNCTION trigger_insert_into_vueMessages();

CREATE OR REPLACE FUNCTION validate_expediteur()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.typeExpediteur = 'membre' THEN
        -- Vérifier que idExpediteur existe dans _membre
        IF NOT EXISTS (SELECT 1 FROM pact._membre WHERE idU = NEW.idExpediteur) THEN
            RAISE EXCEPTION 'idExpediteur % not found in _membre', NEW.idExpediteur;
        END IF;
    ELSIF NEW.typeExpediteur = 'pro' THEN
        -- Vérifier que idExpediteur existe dans _pro
        IF NOT EXISTS (SELECT 1 FROM pact._pro WHERE idU = NEW.idExpediteur) THEN
            RAISE EXCEPTION 'idExpediteur % not found in _pro', NEW.idExpediteur;
        END IF;
    ELSE
        RAISE EXCEPTION 'Invalid typeExpediteur: %', NEW.typeExpediteur;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Associer le trigger à la table _historiqueMessage
CREATE TRIGGER validate_expediteur_trigger
BEFORE INSERT OR UPDATE ON _historiqueMessage
FOR EACH ROW
EXECUTE FUNCTION validate_expediteur();


CREATE OR REPLACE FUNCTION compte_langue_offre()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM _visite_langue WHERE idOffre = NEW.idOffre) = 1 THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir 0 langue pour une visite';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_compte_langue_offre
BEFORE DELETE ON _visite_langue
FOR EACH ROW
EXECUTE FUNCTION compte_langue_offre();

CREATE OR REPLACE FUNCTION compte_langue()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT COUNT(*) FROM _visite_langue WHERE langue = NEW.langue) = 1 THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir des langues avec 0 visite';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_compte_langue
BEFORE DELETE ON _visite_langue
FOR EACH ROW
EXECUTE FUNCTION compte_langue();

CREATE OR REPLACE FUNCTION ajout_pro_prive()
RETURNS TRIGGER AS $$
DECLARE
  iduser INT;
BEGIN
    IF EXISTS (SELECT 1 FROM pact._pro WHERE denomination = NEW.denomination)THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnel privée ayant la même dénomination';
    END IF;
    IF EXISTS (SELECT 1 FROM pact._nonAdmin WHERE mail = NEW.mail)THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnel privée ayant le même mail';
    END IF;
    IF EXISTS (SELECT 1 FROM pact._privee WHERE siren = NEW.siren)THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnel privée ayant le même siren';
    END IF;
    INSERT INTO pact._utilisateur (password) VALUES (NEW.password) RETURNING idU into iduser;
    INSERT INTO pact._nonAdmin (idU, telephone, mail) VALUES (iduser,NEW.telephone,NEW.mail);
    INSERT INTO pact._pro (idU, denomination) VALUES (iduser,NEW.denomination);
    INSERT INTO pact._privee (idU, siren) VALUES (iduser,NEW.siren);
    INSERT INTO pact._photo_profil(idU,url) VALUES (iduser,NEW.url);
    IF NOT EXISTS (SELECT 1 FROM pact._adresse WHERE numeroRue = NEW.numeroRue AND rue = NEW.rue AND ville = NEW.ville AND pays = NEW.pays AND codePostal = NEW.codePostal) THEN
        INSERT INTO pact._adresse (numeroRue, rue, ville, pays, codePostal) VALUES (NEW.numeroRue, NEW.rue, NEW.ville, NEW.pays, NEW.codePostal);
    END IF;
    INSERT INTO pact._habite (idU, codePostal, ville, pays, rue, numeroRue) VALUES (iduser, NEW.codePostal, NEW.ville, NEW.pays, NEW.rue, NEW.numeroRue);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_pro_prive
INSTEAD OF INSERT ON pact.proprive
FOR EACH ROW
EXECUTE FUNCTION ajout_pro_prive();

CREATE OR REPLACE FUNCTION ajout_pro_public()
RETURNS TRIGGER AS $$
DECLARE
  iduser INT;
BEGIN
    IF EXISTS (SELECT 1 FROM pact._pro WHERE denomination = NEW.denomination)THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnel privée ayant la même dénomination';
    END IF;
    IF EXISTS (SELECT 1 FROM pact._nonAdmin WHERE mail = NEW.mail)THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnel privée ayant le même mail';
    END IF;
    INSERT INTO pact._utilisateur (password) VALUES (NEW.password) RETURNING idU into iduser;
    INSERT INTO pact._nonAdmin (idU, telephone, mail) VALUES (iduser,NEW.telephone,NEW.mail);
    INSERT INTO pact._pro (idU, denomination) VALUES (iduser,NEW.denomination);
    INSERT INTO pact._public (idU) VALUES (iduser);
    INSERT INTO pact._photo_profil(idU,url) VALUES (iduser,NEW.url);
    IF NOT EXISTS (SELECT 1 FROM pact._adresse WHERE numeroRue = NEW.numeroRue AND rue = NEW.rue AND ville = NEW.ville AND pays = NEW.pays AND codePostal = NEW.codePostal) THEN
        INSERT INTO pact._adresse (numeroRue, rue, ville, pays, codePostal) VALUES (NEW.numeroRue, NEW.rue, NEW.ville, NEW.pays, NEW.codePostal);
    END IF;
    INSERT INTO pact._habite (idU, codePostal, ville, pays, rue, numeroRue) VALUES (iduser, NEW.codePostal, NEW.ville, NEW.pays, NEW.rue, NEW.numeroRue);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER trigger_ajout_pro_public
INSTEAD OF INSERT ON pact.proPublic
FOR EACH ROW
EXECUTE FUNCTION ajout_pro_public();

CREATE OR REPLACE FUNCTION ajout_offre_restaurant()
RETURNS TRIGGER AS $$
DECLARE
  id_offre INT;
BEGIN
    IF (SELECT nom_offre FROM pact.restaurants WHERE idU=NEW.idU) = NEW.nom_offre THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux offre ayant le même nom';
    END IF;
    INSERT INTO pact._offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume) VALUES (NEW.idU, NEW.statut, NEW.nom_offre, NEW.description, NEW.mail, NEW.telephone, NEW.affiche, NEW.urlSite, NEW.resume)RETURNING idOffre INTO id_offre;
    INSERT INTO pact._restauration (idOffre, gammeDePrix, urlMenu) VALUES (id_offre, NEW.gammeDePrix, NEW.urlMenu);
    INSERT INTO pact._abonner (idOffre, nomAbonnement) VALUES (id_offre, NEW.abonnement);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_offre_restaurant
INSTEAD OF INSERT ON pact.restaurants
FOR EACH ROW
EXECUTE FUNCTION ajout_offre_restaurant();

CREATE OR REPLACE FUNCTION ajout_offre_activites()
RETURNS TRIGGER AS $$
DECLARE
  id_offre INT;
BEGIN
    IF (SELECT nom_offre FROM pact.activites WHERE idU=NEW.idU) = NEW.nom_offre THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux offre ayant le même nom';
    END IF;
    INSERT INTO pact._offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume) VALUES (NEW.idU, NEW.statut, NEW.nom_offre, NEW.description, NEW.mail, NEW.telephone, NEW.affiche, NEW.urlSite, NEW.resume)RETURNING idOffre INTO id_offre;
    INSERT INTO pact._activite (idOffre, duree, ageMin, prixMinimal, prestation) VALUES (id_offre, NEW.duree, NEW.ageMin, NEW.prixMinimal,NEW.prestation);
    INSERT INTO pact._abonner (idOffre, nomAbonnement) VALUES (id_offre, NEW.abonnement);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_offre_activites
INSTEAD OF INSERT ON pact.activites
FOR EACH ROW
EXECUTE FUNCTION ajout_offre_activites();

CREATE OR REPLACE FUNCTION ajout_offre_parcs_attraction()
RETURNS TRIGGER AS $$
DECLARE
  id_offre INT;
BEGIN
    IF (SELECT nom_offre FROM pact.parcs_attractions WHERE idU=NEW.idU) = NEW.nom_offre THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux offre ayant le même nom';
    END IF;
    INSERT INTO pact._offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume) VALUES (NEW.idU, NEW.statut, NEW.nom_offre, NEW.description, NEW.mail, NEW.telephone, NEW.affiche, NEW.urlSite, NEW.resume)RETURNING idOffre INTO id_offre;
    INSERT INTO pact._parcAttraction (idOffre, ageMin, nbAttraction, prixMinimal, urlPlan) VALUES (id_offre, NEW.ageMin, NEW.nbAttraction, NEW.prixMinimal, NEW.urlPlan);
    INSERT INTO pact._abonner (idOffre, nomAbonnement) VALUES (id_offre, NEW.abonnement);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_offre_parcs_attraction
INSTEAD OF INSERT ON pact.parcs_attractions
FOR EACH ROW
EXECUTE FUNCTION ajout_offre_parcs_attraction();

CREATE OR REPLACE FUNCTION ajout_offre_spectacle()
RETURNS TRIGGER AS $$
DECLARE
  id_offre INT;
BEGIN
    IF (SELECT nom_offre FROM pact.spectacles WHERE idU=NEW.idU) = NEW.nom_offre THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux offre ayant le même nom';
    END IF;
    INSERT INTO pact._offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume) VALUES (NEW.idU, NEW.statut, NEW.nom_offre, NEW.description, NEW.mail, NEW.telephone, NEW.affiche, NEW.urlSite, NEW.resume)RETURNING idOffre INTO id_offre;
    INSERT INTO pact._spectacle (idOffre, duree, nbPlace, prixMinimal) VALUES (id_offre, NEW.duree, NEW.nbPlace, NEW.prixMinimal);
    INSERT INTO pact._abonner (idOffre, nomAbonnement) VALUES (id_offre, NEW.abonnement);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_offre_spectacle
INSTEAD OF INSERT ON pact.spectacles
FOR EACH ROW
EXECUTE FUNCTION ajout_offre_spectacle();

CREATE OR REPLACE FUNCTION ajout_offre_visite()
RETURNS TRIGGER AS $$
DECLARE
  id_offre INT;
BEGIN
    IF (SELECT nom_offre FROM pact.visites WHERE idU=NEW.idU) = NEW.nom_offre THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux offre ayant le même nom';
    END IF;
    INSERT INTO pact._offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume) VALUES (NEW.idU, NEW.statut, NEW.nom_offre, NEW.description, NEW.mail, NEW.telephone, NEW.affiche, NEW.urlSite, NEW.resume)RETURNING idOffre INTO id_offre;
    INSERT INTO pact._visite (idOffre, guide, duree, prixMinimal, accessibilite) VALUES (id_offre, NEW.guide, NEW.duree, NEW.prixMinimal, NEW.accessibilite);
    INSERT INTO pact._abonner (idOffre, nomAbonnement) VALUES (id_offre, NEW.abonnement);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_offre_visite
INSTEAD OF INSERT ON pact.visites
FOR EACH ROW
EXECUTE FUNCTION ajout_offre_visite();



CREATE OR REPLACE FUNCTION ajout_membre()
RETURNS TRIGGER AS $$
DECLARE
  iduser INT;
BEGIN
    IF EXISTS (SELECT 1 FROM pact.membre WHERE pseudo = NEW.pseudo) THEN
        RAISE EXCEPTION 'Vous ne pouvez pas avoir deux membre ayant le même pseudo';
    END IF;
    INSERT INTO pact._utilisateur (password) VALUES (NEW.password) RETURNING idU into iduser;
    INSERT INTO pact._nonAdmin (idU, telephone, mail) VALUES (iduser, NEW.telephone, NEW.mail);
    INSERT INTO pact._membre (idU,pseudo,nom,prenom) VALUES (iduser, NEW.pseudo, NEW.nom, NEW.prenom);
    INSERT INTO pact._photo_profil(idU,url) VALUES (iduser,NEW.url);
    IF NOT EXISTS (SELECT 1 FROM pact._adresse WHERE numeroRue = NEW.numeroRue AND rue = NEW.rue AND ville = NEW.ville AND pays = NEW.pays AND codePostal = NEW.codePostal) THEN
        INSERT INTO pact._adresse (numeroRue, rue, ville, pays, codePostal) VALUES (NEW.numeroRue, NEW.rue, NEW.ville, NEW.pays, NEW.codePostal);
    END IF;
    INSERT INTO pact._habite (idU, codePostal, ville, pays, rue, numeroRue) VALUES (iduser, NEW.codePostal, NEW.ville, NEW.pays, NEW.rue, NEW.numeroRue);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_membre
INSTEAD OF INSERT ON pact.membre
FOR EACH ROW
EXECUTE FUNCTION ajout_membre();

CREATE OR REPLACE FUNCTION update_membre()
RETURNS TRIGGER AS $$
DECLARE
    iduser INT;
BEGIN
    -- Récupérer l'ID utilisateur correspondant au pseudo actuel
    SELECT idU INTO iduser FROM pact._membre WHERE pseudo = OLD.pseudo;
    IF iduser IS NULL THEN
        RAISE EXCEPTION 'Utilisateur avec le pseudo % introuvable', OLD.pseudo;
    END IF;

    -- Mise à jour des informations utilisateur
    UPDATE pact._utilisateur
    SET password = COALESCE(NEW.password, pact._utilisateur.password)
    WHERE idU = iduser;

    -- Mise à jour des informations non-administratives
    UPDATE pact._nonAdmin
    SET telephone = COALESCE(NEW.telephone, pact._nonAdmin.telephone),
        mail = COALESCE(NEW.mail, pact._nonAdmin.mail)
    WHERE idU = iduser;

    -- Mise à jour des informations de membre
    UPDATE pact._membre
    SET pseudo = COALESCE(NEW.pseudo, pact._membre.pseudo),
        nom = COALESCE(NEW.nom, pact._membre.nom),
        prenom = COALESCE(NEW.prenom, pact._membre.prenom)
    WHERE idU = iduser;

    -- Mise à jour de la photo de profil
    
    UPDATE pact._photo_profil
    SET url = COALESCE(NEW.url, pact._photo_profil.url)
    WHERE idU = iduser;

    -- Vérifier si l'adresse existe, sinon la créer
    IF NOT EXISTS (
        SELECT 1 FROM pact._adresse
        WHERE numeroRue = NEW.numeroRue AND rue = NEW.rue AND ville = NEW.ville AND pays = NEW.pays AND codePostal = NEW.codePostal
    ) THEN
        INSERT INTO pact._adresse (numeroRue, rue, ville, pays, codePostal)
        VALUES (NEW.numeroRue, NEW.rue, NEW.ville, NEW.pays, NEW.codePostal);
    END IF;

    -- Mettre à jour l'adresse de résidence
    UPDATE pact._habite
    SET codePostal = COALESCE(NEW.codePostal, pact._habite.codePostal),
        ville = COALESCE(NEW.ville, pact._habite.ville),
        pays = COALESCE(NEW.pays, pact._habite.pays),
        rue = COALESCE(NEW.rue, pact._habite.rue),
        numeroRue = COALESCE(NEW.numeroRue, pact._habite.numeroRue)
    WHERE idU = iduser;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Création du trigger associé
CREATE TRIGGER trigger_update_membre
INSTEAD OF UPDATE ON pact.membre
FOR EACH ROW
EXECUTE FUNCTION update_membre();


CREATE OR REPLACE FUNCTION ajout_option()
RETURNS TRIGGER AS $$
DECLARE
  id_option INT;
BEGIN
    INSERT INTO pact._dateOption (dateLancement,dateFin,duree,prix) VALUES (NEW.dateLancement,NEW.dateFin, NEW.duree_total, NEW.prix_total) RETURNING idOption into id_option;
    INSERT INTO pact._option_offre (idOffre, idOption, nomOption) VALUES (NEW.idOffre, id_option, NEW.nomOption);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_option
INSTEAD OF INSERT ON pact.option
FOR EACH ROW
EXECUTE FUNCTION ajout_option();

CREATE OR REPLACE FUNCTION ajout_avis()
RETURNS TRIGGER AS $$
DECLARE
  idcomment INT;
BEGIN
    INSERT INTO pact._commentaire (idU,content,datePublie) VALUES (NEW.idU,NEW.content, NEW.datePublie) RETURNING idC into idcomment;
    INSERT INTO pact._avis (idC, idOffre, note, companie, mois, annee, titre, lu) VALUES (idcomment, NEW.idOffre, NEW.note, NEW.companie, NEW.mois, NEW.annee, NEW.titre, NEW.lu);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_avis
INSTEAD OF INSERT ON pact.avis
FOR EACH ROW
EXECUTE FUNCTION ajout_avis();

CREATE OR REPLACE FUNCTION ajout_reponse()
RETURNS TRIGGER AS $$
DECLARE
  idcomment INT;
BEGIN
    INSERT INTO pact._commentaire (idU,content,datePublie) VALUES (NEW.idpro,NEW.contenureponse, CURRENT_TIMESTAMP) RETURNING idC into idcomment;
    INSERT INTO pact._reponse (idC, ref) VALUES (idcomment, NEW.idc_avis);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_ajout_reponse
INSTEAD OF INSERT ON pact.reponse
FOR EACH ROW
EXECUTE FUNCTION ajout_reponse();


CREATE OR REPLACE FUNCTION update_pro_public()
RETURNS TRIGGER AS $$
BEGIN
    -- Vérification : éviter les doublons sur la dénomination
    IF NEW.denomination IS DISTINCT FROM OLD.denomination THEN
        IF EXISTS (SELECT 1 FROM pact._pro WHERE denomination = NEW.denomination) THEN
            RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnels privés ayant la même dénomination';
        END IF;
    END IF;

    -- Vérification : éviter les doublons sur le mail
    IF NEW.mail IS DISTINCT FROM OLD.mail THEN
        IF EXISTS (SELECT 1 FROM pact._nonAdmin WHERE mail = NEW.mail) THEN
            RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnels privés ayant le même mail';
        END IF;
    END IF;

    -- Mettre à jour les informations dans pact._utilisateur
    UPDATE pact._utilisateur
    SET password = COALESCE(NEW.password, OLD.password)
    WHERE idU = OLD.idU;

    -- Mettre à jour les informations dans pact._nonAdmin
    UPDATE pact._nonAdmin
    SET telephone = COALESCE(NEW.telephone, OLD.telephone),
        mail = COALESCE(NEW.mail, OLD.mail)
    WHERE idU = OLD.idU;

    -- Mettre à jour les informations dans pact._pro
    UPDATE pact._pro
    SET denomination = COALESCE(NEW.denomination, OLD.denomination)
    WHERE idU = OLD.idU;

    -- Mettre à jour les informations dans pact._photo_profil
    UPDATE pact._photo_profil
    SET url = COALESCE(NEW.url, OLD.url)
    WHERE idU = OLD.idU;

    -- Vérifier et insérer l'adresse si elle n'existe pas déjà
    IF NOT EXISTS (
        SELECT 1
        FROM pact._adresse
        WHERE numeroRue = COALESCE(NEW.numeroRue, OLD.numeroRue)
          AND rue = COALESCE(NEW.rue, OLD.rue)
          AND ville = COALESCE(NEW.ville, OLD.ville)
          AND pays = COALESCE(NEW.pays, OLD.pays)
          AND codePostal = COALESCE(NEW.codePostal, OLD.codePostal)
    ) THEN
        INSERT INTO pact._adresse (numeroRue, rue, ville, pays, codePostal)
        VALUES (
            COALESCE(NEW.numeroRue, OLD.numeroRue),
            COALESCE(NEW.rue, OLD.rue),
            COALESCE(NEW.ville, OLD.ville),
            COALESCE(NEW.pays, OLD.pays),
            COALESCE(NEW.codePostal, OLD.codePostal)
        );
    END IF;

    -- Mettre à jour les informations dans pact._habite
    UPDATE pact._habite
    SET codePostal = COALESCE(NEW.codePostal, OLD.codePostal),
        ville = COALESCE(NEW.ville, OLD.ville),
        pays = COALESCE(NEW.pays, OLD.pays),
        rue = COALESCE(NEW.rue, OLD.rue),
        numeroRue = COALESCE(NEW.numeroRue, OLD.numeroRue)
    WHERE idU = OLD.idU;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER trigger_update_pro_public
INSTEAD OF UPDATE ON pact.proPublic
FOR EACH ROW
EXECUTE FUNCTION update_pro_public();

CREATE OR REPLACE FUNCTION update_pro_prive()
RETURNS TRIGGER AS $$
BEGIN
    -- Vérification : éviter les doublons sur la dénomination
    IF NEW.denomination IS DISTINCT FROM OLD.denomination THEN
        IF EXISTS (SELECT 1 FROM pact._pro WHERE denomination = NEW.denomination) THEN
            RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnels privés ayant la même dénomination';
        END IF;
    END IF;

    -- Vérification : éviter les doublons sur le mail
    IF NEW.mail IS DISTINCT FROM OLD.mail THEN
        IF EXISTS (SELECT 1 FROM pact._nonAdmin WHERE mail = NEW.mail) THEN
            RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnels privés ayant le même mail';
        END IF;
    END IF;

    -- Vérification : éviter les doublons sur le SIREN
    IF NEW.siren IS DISTINCT FROM OLD.siren THEN
        IF EXISTS (SELECT 1 FROM pact._privee WHERE siren = NEW.siren) THEN
            RAISE EXCEPTION 'Vous ne pouvez pas avoir deux professionnels privés ayant le même SIREN';
        END IF;
    END IF;

    -- Mettre à jour les informations dans pact._utilisateur
    UPDATE pact._utilisateur
    SET password = COALESCE(NEW.password, OLD.password)
    WHERE idU = OLD.idU;

    -- Mettre à jour les informations dans pact._nonAdmin
    UPDATE pact._nonAdmin
    SET telephone = COALESCE(NEW.telephone, OLD.telephone),
        mail = COALESCE(NEW.mail, OLD.mail)
    WHERE idU = OLD.idU;

    -- Mettre à jour les informations dans pact._pro
    UPDATE pact._pro
    SET denomination = COALESCE(NEW.denomination, OLD.denomination)
    WHERE idU = OLD.idU;

    -- Mettre à jour les informations dans pact._privee
    UPDATE pact._privee
    SET siren = COALESCE(NEW.siren, OLD.siren)
    WHERE idU = OLD.idU;

    -- Mettre à jour les informations dans pact._photo_profil
    UPDATE pact._photo_profil
    SET url = COALESCE(NEW.url, OLD.url)
    WHERE idU = OLD.idU;

    -- Vérifier et insérer l'adresse si elle n'existe pas déjà
    IF NOT EXISTS (
        SELECT 1
        FROM pact._adresse
        WHERE numeroRue = COALESCE(NEW.numeroRue, OLD.numeroRue)
          AND rue = COALESCE(NEW.rue, OLD.rue)
          AND ville = COALESCE(NEW.ville, OLD.ville)
          AND pays = COALESCE(NEW.pays, OLD.pays)
          AND codePostal = COALESCE(NEW.codePostal, OLD.codePostal)
    ) THEN
        INSERT INTO pact._adresse (numeroRue, rue, ville, pays, codePostal)
        VALUES (
            COALESCE(NEW.numeroRue, OLD.numeroRue),
            COALESCE(NEW.rue, OLD.rue),
            COALESCE(NEW.ville, OLD.ville),
            COALESCE(NEW.pays, OLD.pays),
            COALESCE(NEW.codePostal, OLD.codePostal)
        );
    END IF;

    -- Mettre à jour les informations dans pact._habite
    UPDATE pact._habite
    SET codePostal = COALESCE(NEW.codePostal, OLD.codePostal),
        ville = COALESCE(NEW.ville, OLD.ville),
        pays = COALESCE(NEW.pays, OLD.pays),
        rue = COALESCE(NEW.rue, OLD.rue),
        numeroRue = COALESCE(NEW.numeroRue, OLD.numeroRue)
    WHERE idU = OLD.idU;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_pro_prive
INSTEAD OF UPDATE ON pact.proprive
FOR EACH ROW
EXECUTE FUNCTION update_pro_prive();
