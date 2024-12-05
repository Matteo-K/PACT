SET SCHEMA 'pact';

-- Peuplement de la table _utilisateur
INSERT INTO _utilisateur (password) 
VALUES ('motdepasse1'),
       ('motdepasse2'), 
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'), 
       ('$2y$10$UCrpwG.yZKqiAC4K2JctOeFwiar3nhNH1HWkx1NivF1KYUfnwxYTa'), 
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC');

-- Peuplement de la table _admin
INSERT INTO _admin (idU, login) 
VALUES (1, 'admin1'), (2, 'admin2');

-- Peuplement de la table _nonAdmin
INSERT INTO _nonAdmin (idU, telephone, mail) 
VALUES (3, '0123456789', 'utilisateur1@mail.com'),
       (4, '0585956535', 'toto@wanadoo.fr'),
       (5, '0123456791', 'utilisateur3@mail.com'),
       (6, '0601547889', 'utilisateur6@mail.com'),
       (7, '0670589632', 'utilisateur7@mail.com'),
       (8, '0643521221', 'utilisateur8@mail.com'),
       (9, '0798556234', 'utilisateur9@mail.com'),
       (10, '0741136525', 'utilisateur10@mail.com'),
       (11, '0659334225', 'utilisateur11@mail.com'),
       (12, '0684881254', 'utilisateur12@mail.com'),
       (13, '0696452378', 'utilisateur13@mail.com'),
       (14, '0673415488', 'utilisateur14@mail.com'),
       (15, '0645490651', 'utilisateur15@mail.com'),
       (16, '0631333012', 'utilisateur16@mail.com'),
       (17, '0699658712', 'utilisateur17@mail.com'),
       (18, '0712184562', 'utilisateur18@mail.com'),
       (19, '0766679802', 'utilisateur19@mail.com'),
       (20, '0631394771', 'utilisateur20@mail.com'),
       (21, '0678451225', 'utilisateur21@mail.com'),
       (22, '0698653212', 'utilisateur22@mail.com'),
       (23, '0685527412', 'utilisateur23@mail.com'),
       (24, '0736987445', 'utilisateur24@mail.com'),
       (25, '0614569852', 'utilisateur25@mail.com');

-- Peuplement de la table _pro
INSERT INTO _pro (idU, denomination) 
VALUES (3, 'EntreprisePro1'),
       (4, 'Patrick LÉtoile De Mer');

-- Peuplement de la table _membre
INSERT INTO _membre (idU, pseudo, nom, prenom) 
VALUES (5, 'membre1', 'Dupont', 'Jean'),
       (6, 'Les kheys', 'tk', '76'),
       (7, 'le kyks', 'kyky', 'bapé'),
       (8, 'nono53', 'laden', 'nolan'),
       (9, 'jean2', '2macour', 'Jean'),
       (10, 'Redwin', 'Jain', 'Ewen'),
       (11, 'alolo12', 'histe', 'rique'),
       (12, 'Arkade', 'guillerm', 'Antoine'),
       (13, 'Traxsab', 'Cochet', 'Iwan'),
       (14, 'mattaque', 'Kervadec', 'mattéo'),
       (15, 'BZHKylian', 'Houedec', 'kylian'),
       (16, 'totoWolf', 'Wolf', 'toto'),
       (17, 'Wolf2.0', 'Wolf', 'James'),
       (18, 'chili', 'Sainz', 'Carlos'),
       (19, 'checko', 'Perez', 'Sergio'),
       (20, 'latigoat', 'Latifi', 'Nicholas'),
       (21, 'jojoBernard', 'Bernard', 'jojo'),
       (22, 'IbraTV', 'ibra', 'tv'),
       (23, 'Lasalle', 'lasalle', 'Jean'),
       (24, 'FuriousJumper', 'furious', 'jumper'),
       (25, 'Siphano', 'siph', 'ano');

-- Peuplement de la table _public
INSERT INTO _public (idU) 
VALUES (3);

-- Peuplement de la table _privee
INSERT INTO _privee (idU, siren) 
VALUES (4, '321654987');

-- Peuplement de la table _adresse
INSERT INTO _adresse (numeroRue, rue, ville, pays, codePostal) 
VALUES (1, 'Rue Édouard Branly', 'Lannion', 'France', '22300'),
       (4, 'Allée des acacias', 'Fréhel', 'France', '22000'),
	     (123, 'rue de l ananas au fond de la mer', 'Fond de l eau', 'France', '22300'),
	     ('1','Route du cap', 'Fréhel', 'France','22000'),
	     ('5','Rue Beauchamp','Lannion','France','22300'),
	     ('1','Rue du port','Erquy','France','22430');

-- Peuplement de la table _abonnement
INSERT INTO _abonnement (nomAbonnement, tarif) 
VALUES ('Basique', 1.67), 
       ('Premium', 3.34),
	     ('Gratuit', 0.0);

-- Peuplement de la table _statut
INSERT INTO _statut (statut) 
VALUES ('actif'), 
       ('inactif');

-- Peuplement de la table _tag
INSERT INTO _tag (nomTag) 
VALUES ('familial'), 
       ('aventure'),
       ('culturel'), 
       ('romantique'),
       ('poisson'),
       ('local'),
       ('rapide'),
       ('breton'),
       ('animaux'),
       ('thématique'),
       ('aquatique'),
       ('interactif'),
       ('spectacles_inclus'),
       ('sensations_fortes'),
       ('international'),
       ('insolite'),
       ('populaire'),
       ('exclusif'),
       ('authentique'),
       ('festif'),
       ('calme'),
       ('intimiste'),
       ('ludique'),
       ('traditionnel'),
       ('contemporain'),
       ('convivial'),
       ('en_extérieur'),
       ('en_intérieur'),
       ('urbain'),
       ('rural'),
       ('en_bord_de_mer'),
       ('montagne'),
       ('patrimonial'),
       ('historique'),
       ('moderne'),
       ('médiéval'),
       ('naturel'),
       ('industriel'),
       ('féérique'),
       ('nocturne'),
       ('diurne'),
       ('week-end'),
       ('vacances_scolaires'),
       ('estival'),
       ('hivernal'),
       ('saisonnier'),
       ('couple'),
       ('enfants'),
       ('adolescents'),
       ('seniors'),
       ('groupes'),
       ('solo'),
       ('amateurs_de_sensations'),
       ('théâtre'),
       ('concert'),
       ('opéra'),
       ('cirque'),
       ('comédie_musicale'),
       ('danse'),
       ('magie'),
       ('stand-up'),
       ('sport_nautique'),
       ('randonnée'),
       ('atelier_créatif'),
       ('activité_immersive'),
       ('escape_game'),
       ('jeux_d’équipe'),
       ('découverte_sportive'),
       ('detente'),
       ('bien-être'),
       ('musée'),
       ('lieu_insolite'),
       ('monument'),
       ('panoramique'),
       ('éducative'),
       ('immersive'),
       ('paranormale'),
       ('cuisine_locale'),
       ('cuisine_gastronomique'),
       ('street_food'),
       ('brunch'),
       ('végétarien'),
       ('vegan'),
       ('à_thème'),
       ('fruit_de_mer'),
       ('cuisine_asiatique'),
       ('cuisine_africaine'),
       ('cuisine_americaine'),
       ('cuisine_orientale'),
       ('cuisine_francaise'),
       ('cuisine_mediteranéenne');




-- Peuplement de la table _jour
INSERT INTO _jour (jour) 
VALUES ('Lundi'), 
       ('Mardi'), 
       ('Mercredi'),
       ('Jeudi'),
       ('Vendredi'),
       ('Samedi'),
       ('Dimanche');

-- Peuplement de la table _option
INSERT INTO _option (nomOption, prixOffre, dureeOption) 
VALUES ('ALaUne', 16.68, 1), 
       ('EnRelief', 8.34, 1);

-- Peuplement de la table _langue
INSERT INTO _langue (langue) 
VALUES ('Anglais'), 
       ('Français'), 
       ('Espagnol');

-- Peuplement de la table _offre (une offre par catégorie)
INSERT INTO _offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume, dateCrea) 
VALUES (3, 'actif', 'Parc Génial de saint paul', 'Le meilleur parc d’attractions de la ville.', 'parc@mail.com', '0123456790', TRUE, 'http://parc.com', 'Divertissement familial', CURRENT_TIMESTAMP),
       (4, 'actif', 'Thomas Angelvy', 'Un spectacle incroyable avec des performances éblouissantes.', 'spectacle@mail.com', '0123456791', TRUE, 'http://spectacle.com', 'Divertissement exceptionnel', CURRENT_TIMESTAMP),
       (4, 'actif', 'La Potinière', 'Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. ', 'restaurant@mail.com', '0123456793', TRUE, NULL, 'Cuisine raffinée', CURRENT_TIMESTAMP),
       (3, 'actif', 'Activité Culturelle', 'Explorez la culture locale à travers une activité immersive.', 'activite@mail.com', '0123456794', TRUE, 'http://activite.com', 'Immersion culturelle aves la population local', CURRENT_TIMESTAMP),
       (4, 'actif', 'Visite Guidée du cap fréhel', 'Visite guidée des plus beaux sites du cap Fréhel.', 'visite@mail.com', '0123456795', TRUE, NULL, 'Découverte guidée', CURRENT_TIMESTAMP);

-- Peuplement de la table _image
INSERT INTO _image (url, nomImage) 
VALUES ('./img/profile_picture/default.svg', 'default.svg'), 
       ('./img/imageOffre/3/0.png', 'laPotiniere0'),
       ('./img/imageOffre/3/1.png', 'laPotiniere1'),
       ('./img/imageOffre/3/2.png', 'laPotiniere2'),
       ('./img/imageOffre/3/3.png', 'laPotiniere3'),
       ('./img/imageOffre/3/4.png', 'laPotiniere4'),
       ('./img/imageOffre/4/0.png', 'activite4'),
       ('./img/imageOffre/1/0.png', 'parc0'),
       ('./img/imageOffre/1/1.png', 'parc1'),
       ('./img/imageOffre/1/2.png', 'parc2'),
       ('./img/imageOffre/2/0.png', 'spectacle0'),
       ('./img/imageOffre/5/0.png', 'visite0'),
       ('./img/imageOffre/5/1.png', 'visite0'),
       ('./img/imageOffre/5/2.png', 'visite0'),
       ('./img/imageAvis/1/0.png','Avis1-0'),
       ('./img/imageAvis/3/0.png','Avis3-0'),
       ('./img/imageAvis/7/0.png','Avis7-0');
  
-- Peuplement de la table _illustre
INSERT INTO _illustre (idOffre, url) 
VALUES (3, './img/imageOffre/3/0.png'),
       (3, './img/imageOffre/3/1.png'),
       (3, './img/imageOffre/3/2.png'),
       (3, './img/imageOffre/3/3.png'),
       (3, './img/imageOffre/3/4.png'),
       (1, './img/imageOffre/1/0.png'),
       (1, './img/imageOffre/1/1.png'),
       (1, './img/imageOffre/1/2.png'),
       (2, './img/imageOffre/2/0.png'),
       (4, './img/imageOffre/4/0.png'),
       (5, './img/imageOffre/5/0.png'),
       (5,'./img/imageOffre/5/1.png'),
       (5,'./img/imageOffre/5/2.png');

-- Peuplement de la table _photo_profil
INSERT INTO _photo_profil (idU, url) 
VALUES (1, './img/profile_picture/default.svg'),
	   (2, './img/profile_picture/default.svg'),
	   (3, './img/profile_picture/default.svg'),
	   (4, './img/profile_picture/default.svg'),
       (5, './img/profile_picture/default.svg'),
       (6, './img/profile_picture/default.svg'),
       (7, './img/profile_picture/default.svg'),
       (8, './img/profile_picture/default.svg'),
       (9, './img/profile_picture/default.svg'),
       (10, './img/profile_picture/default.svg'),
       (11, './img/profile_picture/default.svg'),
       (12, './img/profile_picture/default.svg'),
       (13, './img/profile_picture/default.svg'),
       (14, './img/profile_picture/default.svg'),
       (15, './img/profile_picture/default.svg'),
       (16, './img/profile_picture/default.svg'),
       (17, './img/profile_picture/default.svg'),
       (18, './img/profile_picture/default.svg'),
       (19, './img/profile_picture/default.svg'),
       (20, './img/profile_picture/default.svg'),
       (21, './img/profile_picture/default.svg'),
       (22, './img/profile_picture/default.svg'),
       (23, './img/profile_picture/default.svg'),
       (24, './img/profile_picture/default.svg'),
       (25, './img/profile_picture/default.svg');

-- Peuplement de la table _consulter
INSERT INTO _consulter (idU, idOffre, dateConsultation) 
VALUES (5, 1, '2024-10-10'), 
       (5, 2, '2024-10-12');

-- Peuplement des tables spécifiques aux types d'offres (chaque offre appartient à une seule catégorie)
-- Parc d'attraction (idOffre 1)
INSERT INTO _parcAttraction (idOffre, ageMin, nbAttraction, prixMinimal, urlPlan) 
VALUES (1, 5, 20, 15.0, './img/imagePlan/1/0.jpg');

-- Spectacle (idOffre 2)
INSERT INTO _spectacle (idOffre, duree, nbPlace, prixMinimal) 
VALUES (2, 120, 100, 25.0);

-- Restaurant (idOffre 3)
INSERT INTO _restauration (idOffre, gammeDePrix) 
VALUES (3, '€€€');

-- Activité (idOffre 4)
INSERT INTO _activite (idOffre, duree, ageMin, prixMinimal, prestation) 
VALUES (4, 180, 10, 20.0, 'Immersion culturelle');

-- Visite (idOffre 5)
INSERT INTO _visite (idOffre, guide, duree, prixMinimal, accessibilite) 
VALUES (5, TRUE, 120, 30.0, TRUE);

-- Peuplement de la table _horaireSoir
INSERT INTO _horaireSoir (jour, idOffre, heureOuverture, heureFermeture) 
VALUES ('Lundi', 3, '19:00', '21:00'),
       ('Mardi', 3, '19:00', '21:00'), 
       ('Mercredi', 3, '19:00', '21:00'),
       ('Jeudi', 3, '19:00', '21:00'), 
       ('Vendredi', 3, '19:00', '21:00'),
       ('Samedi', 3, '19:00', '21:00');
       
--peuplement horaire precise
INSERT INTO _horairePrecise (jour, idOffre, heureDebut, heureFin, DateRepresentation) 
VALUES ('Lundi', 2, '16:00', '21:00', '2024-12-02'),
       ('Mardi', 2, '19:00', '21:00', '2024-12-03'), 
       ('Mercredi', 2, '19:00', '21:00', '2024-12-04'),
       ('Jeudi', 2, '19:00', '21:00', '2024-12-05'), 
       ('Vendredi', 2, '19:00', '21:00', '2024-12-06'),
       ('Samedi', 2, '19:00', '21:00', '2024-12-07'),
       ('Lundi', 4, '19:00', '21:00', '2024-12-09'),
       ('Mardi', 4, '19:00', '21:00', '2024-12-10'), 
       ('Mercredi', 4, '19:00', '21:00', '2024-12-11'),
       ('Jeudi', 4, '19:00', '21:00', '2024-12-12'), 
       ('Vendredi', 4, '19:00', '21:00', '2024-12-13'),
       ('Samedi', 4, '19:00', '21:00', '2024-12-14');

-- Peuplement de la table _horaireMidi
INSERT INTO _horaireMidi (jour, idOffre, heureOuverture, heureFermeture) 
VALUES ('Lundi', 1, '10:00', '18:00'), 
       ('Mardi', 1, '10:00', '18:00'), 
       ('Mercredi', 1, '10:00', '18:00'),
       ('Jeudi', 1, '10:00', '18:00'), 
       ('Vendredi', 1, '10:00', '18:00'),
       ('Samedi', 1, '10:00', '17:00'),
       ('Lundi', 3, '12:00', '15:00'), 
       ('Mardi', 3, '12:00', '15:00'), 
       ('Mercredi', 3, '12:00', '15:00'),
       ('Jeudi', 3, '12:00', '15:00'), 
       ('Vendredi', 3, '12:00', '15:00'),
       ('Samedi', 3, '12:00', '15:00'),
       ('Lundi', 5, '10:00', '18:00'), 
       ('Mardi', 5, '10:00', '18:00'), 
       ('Mercredi', 5, '10:00', '18:00'),
       ('Vendredi', 5, '10:00', '18:00'),
       ('Samedi', 5, '10:00', '17:00');

-- Peuplement de la table _tag_parc
INSERT INTO _tag_parc (idOffre, nomTag) 
VALUES (1, 'familial');
--(sensations fortes",
-- Peuplement de la table _tag_spec
INSERT INTO _tag_spec (idOffre, nomTag) 
VALUES (2, 'romantique'),
       (2, 'street_food');

-- Peuplement de la table _tag_restaurant
INSERT INTO _tag_restaurant (idOffre, nomTag) 
VALUES (3, 'cuisine_locale');


 

-- Peuplement de la table _tag_Act
INSERT INTO _tag_Act (idOffre, nomTag) 
VALUES (4, 'culturel');

-- Peuplement de la table _tag_visite
INSERT INTO _tag_visite (idOffre, nomTag) 
VALUES (5, 'culturel');

-- Peuplement de la table _habite
INSERT INTO _habite (idU, codePostal, ville, pays, rue, numeroRue) 
VALUES (5, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(4, '22300', 'Fond de l eau', 'France', 'rue de l ananas au fond de la mer', 123),
		(6, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(7, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(8, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(9, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(10, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(11, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(12, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(13, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(14, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(15, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(16, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(17, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(18, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(19, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(20, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(21, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(22, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(23, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(24, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(25, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1);

-- Peuplement de la table _localisation
INSERT INTO _localisation (idOffre, codePostal, ville, pays, rue, numeroRue) 
VALUES (1, '22300', 'Lannion', 'France', 'Rue Édouard Branly', '1'),
       (2, '22430', 'Erquy', 'France', 'Rue du port', '1'),
       (3, '22000', 'Fréhel', 'France', 'Allée des acacias', '4'),
       (4, '22300', 'Lannion', 'France', 'Rue Beauchamp', '5'),
       (5, '22000', 'Fréhel', 'France', 'Route du cap', '1');

-- Peuplement de la table _abonner
INSERT INTO _abonner (idOffre, nomAbonnement) 
VALUES (1, 'Premium'), 
       (2, 'Basique'), 
       (3, 'Premium'), 
       (4, 'Basique'), 
       (5, 'Premium');
       

INSERT INTO _dateOption(dateLancement,dateFin,duree,prix)
VALUES ('2024-11-01','2024-11-08',1,16.68),
       ('2024-11-01','2024-11-15',2,33.36),
       ('2024-11-01','2024-11-15',2,16.68),
       ('2024-11-01','2024-11-22',3,25.02),
       ('2024-09-01','2024-09-08',1,16.68),
       ('2024-09-01','2024-09-15',2,33.36),
       ('2024-09-01','2024-09-15',2,16.68),
       ('2024-09-01','2024-09-22',3,25.02),
       ('2024-10-01','2024-10-08',1,16.68),
       ('2024-10-01','2024-10-15',2,33.36),
       ('2024-10-01','2024-10-15',2,16.68),
       ('2024-11-01','2024-11-22',3,25.02);

-- Peuplement de la table _option_offre
INSERT INTO _option_offre (idOption, idOffre, nomOption) 
VALUES (3, 1, 'EnRelief'), 
       (2, 2, 'ALaUne'),
       (2, 2, 'EnRelief'),
       (1, 3, 'ALaUne'),
       (4, 5, 'EnRelief'), 
       (5, 4, 'ALaUne'),
       (6, 3, 'EnRelief'),
       (7, 1, 'ALaUne'),
       (8, 4, 'EnRelief'), 
       (9, 4, 'ALaUne'),
       (10, 2, 'EnRelief'),
       (11, 5, 'ALaUne'),
       (12, 3, 'ALaUne');
       
INSERT INTO _visite_langue (idOffre, langue) 
VALUES (5, 'Français'), 
       (5, 'Anglais');
   
INSERT INTO _menu(idOffre, menu)
VALUES (3,'./img/imageMenu/3/0.png');

INSERT INTO _commentaire (idU,content,datePublie)
VALUES (5,'J’ai adoré ce parc d’attraction, je reviendrai.','2024-11-27 15:55:59'),
       (6,'Je recommande ce parc d’attractions.',CURRENT_TIMESTAMP),
       (5,'J’ai adoré cette visite.',CURRENT_TIMESTAMP),
       (6,'À faire.',CURRENT_TIMESTAMP),
       (5,'Spectacle inoubliable.',CURRENT_TIMESTAMP),
       (6,'Mais quelle humoriste donner-lui un oscar !',CURRENT_TIMESTAMP),
       (5,'la cuisson de la viande était parfaite.',CURRENT_TIMESTAMP),
       (6,'Je recommande.',CURRENT_TIMESTAMP),
       (5,'Activité ennuyante.',CURRENT_TIMESTAMP),
       (6,'Personnel désagréable.',CURRENT_TIMESTAMP),
       (4,'Merci beaucoup, à bientôt.',CURRENT_TIMESTAMP),
       (3,'Merci beaucoup, à bientôt.',CURRENT_TIMESTAMP),
       (3,'Avec des personnes comme vous, compliqué de la rendre intéressante.',CURRENT_TIMESTAMP),
       (6, 'Restaurant incroyable, j’y reviendrai !', '2024-12-01 12:00:00'),
       (6, 'Spectacle époustouflant, bravo aux artistes !', '2024-12-02 14:00:00'),
       (6, 'Super parc d’attraction, expérience inoubliable.', '2024-12-03 10:30:00'),
       (6, 'Visite guidée très enrichissante.', '2024-12-04 09:00:00'),
       (6, 'Activité amusante, à refaire.', '2024-12-05 11:15:00'),

       (7, 'Délicieux repas, je recommande ce restaurant.', '2024-12-01 12:15:00'),
       (7, 'Un spectacle qui m’a beaucoup touché.', '2024-12-02 14:15:00'),
       (7, 'Ce parc est parfait pour les enfants.', '2024-12-03 10:45:00'),
(7, 'Guide très compétent, super visite.', '2024-12-04 09:15:00'),
(7, 'Une activité bien organisée et divertissante.', '2024-12-05 11:30:00'),

-- Membre 8
(8, 'Repas satisfaisant et service impeccable.', '2024-12-01 12:30:00'),
(8, 'Artistes impressionnants, bravo pour le spectacle.', '2024-12-02 14:30:00'),
(8, 'Un parc d’attractions vraiment amusant.', '2024-12-03 11:00:00'),
(8, 'Une visite agréable et bien structurée.', '2024-12-04 09:30:00'),
(8, 'Une activité intéressante et bien pensée.', '2024-12-05 11:45:00'),

-- Membre 9
(9, 'Cuisine exceptionnelle, félicitations au chef.', '2024-12-01 12:45:00'),
(9, 'Performance scénique de haute qualité.', '2024-12-02 14:45:00'),
(9, 'Moments inoubliables dans ce parc.', '2024-12-03 11:15:00'),
(9, 'Guide passionné, expérience enrichissante.', '2024-12-04 09:45:00'),
(9, 'L’activité était divertissante et bien organisée.', '2024-12-05 12:00:00'),

-- Membre 10
(10, 'Le restaurant était parfait.', '2024-12-01 13:00:00'),
(10, 'Bravo aux comédiens pour ce spectacle incroyable.', '2024-12-02 15:00:00'),
(10, 'Une journée merveilleuse au parc d’attraction.', '2024-12-03 11:30:00'),
(10, 'Merci au guide pour ses explications fascinantes.', '2024-12-04 10:00:00'),
(10, 'Activité enrichissante et bien organisée.', '2024-12-05 12:15:00'),

-- Membre 11
(11, 'Excellent dîner au restaurant, bravo !', '2024-12-01 13:15:00'),
(11, 'Spectacle divertissant et bien mis en scène.', '2024-12-02 15:15:00'),
(11, 'Un parc d’attractions vraiment incroyable.', '2024-12-03 11:45:00'),
(11, 'Visite enrichissante et très intéressante.', '2024-12-04 10:15:00'),
(11, 'Une belle activité pour toute la famille.', '2024-12-05 12:30:00'),

-- Membre 12
(12, 'Cuisine savoureuse, moment agréable.', '2024-12-01 13:30:00'),
(12, 'Une belle surprise avec ce spectacle.', '2024-12-02 15:30:00'),
(12, 'Un parc qui plaira à tout le monde.', '2024-12-03 12:00:00'),
(12, 'Une visite passionnante et instructive.', '2024-12-04 10:30:00'),
(12, 'Super activité, très bien encadrée.', '2024-12-05 12:45:00'),

-- Membre 13
(13, 'Un repas parfait, service au top.', '2024-12-01 13:45:00'),
(13, 'Spectacle vraiment inoubliable.', '2024-12-02 15:45:00'),
(13, 'Le parc d’attraction est idéal pour les enfants.', '2024-12-03 12:15:00'),
(13, 'Une visite très bien organisée.', '2024-12-04 10:45:00'),
(13, 'Une activité divertissante et enrichissante.', '2024-12-05 13:00:00'),

-- Membre 14
(14, 'Délicieuse cuisine, un grand merci !', '2024-12-01 14:00:00'),
(14, 'Un spectacle captivant et émouvant.', '2024-12-02 16:00:00'),
(14, 'Une journée mémorable au parc.', '2024-12-03 12:30:00'),
(14, 'Une visite vraiment impressionnante.', '2024-12-04 11:00:00'),
(14, 'Activité bien pensée et très agréable.', '2024-12-05 13:15:00'),

-- Membre 15
(15, 'Un excellent repas, je recommande.', '2024-12-01 14:15:00'),
(15, 'Spectacle incroyable, félicitations aux artistes.', '2024-12-02 16:15:00'),
(15, 'Une super journée au parc d’attraction.', '2024-12-03 12:45:00'),
(15, 'Merci au guide pour cette visite exceptionnelle.', '2024-12-04 11:15:00'),
(15, 'Une belle activité qui restera dans nos mémoires.', '2024-12-05 13:30:00'),

(16, 'Cuisine excellente et cadre agréable.', '2024-12-01 14:30:00'),
(16, 'Une performance scénique inoubliable.', '2024-12-02 16:30:00'),
(16, 'Une expérience formidable au parc.', '2024-12-03 13:00:00'),
(16, 'Visite guidée très intéressante.', '2024-12-04 11:30:00'),
(16, 'Une activité bien organisée et plaisante.', '2024-12-05 13:45:00'),

-- Membre 17
(17, 'Un repas délicieux et un service impeccable.', '2024-12-01 14:45:00'),
(17, 'Un spectacle captivant, je recommande !', '2024-12-02 16:45:00'),
(17, 'Ce parc d’attraction est un incontournable.', '2024-12-03 13:15:00'),
(17, 'Une visite très enrichissante.', '2024-12-04 11:45:00'),
(17, 'Une activité très amusante et divertissante.', '2024-12-05 14:00:00'),

-- Membre 18
(18, 'Restaurant fabuleux, plats exquis.', '2024-12-01 15:00:00'),
(18, 'Spectacle exceptionnel, bravo aux artistes.', '2024-12-02 17:00:00'),
(18, 'Un parc d’attractions à couper le souffle.', '2024-12-03 13:30:00'),
(18, 'Visite guidée mémorable.', '2024-12-04 12:00:00'),
(18, 'Une activité agréable et bien encadrée.', '2024-12-05 14:15:00'),

-- Membre 19
(19, 'Cuisine raffinée, moment agréable.', '2024-12-01 15:15:00'),
(19, 'Un spectacle plein de surprises.', '2024-12-02 17:15:00'),
(19, 'Un parc qui ravira petits et grands.', '2024-12-03 13:45:00'),
(19, 'Une visite fascinante et bien racontée.', '2024-12-04 12:15:00'),
(19, 'Une activité intéressante et bien pensée.', '2024-12-05 14:30:00'),

-- Membre 20
(20, 'Repas savoureux dans une ambiance chaleureuse.', '2024-12-01 15:30:00'),
(20, 'Une belle mise en scène pour ce spectacle.', '2024-12-02 17:30:00'),
(20, 'Une journée mémorable au parc d’attraction.', '2024-12-03 14:00:00'),
(20, 'Guide très compétent, visite agréable.', '2024-12-04 12:30:00'),
(20, 'Une activité bien organisée et amusante.', '2024-12-05 14:45:00'),

-- Membre 21
(21, 'Excellent repas, je recommande ce restaurant.', '2024-12-01 15:45:00'),
(21, 'Un spectacle vraiment émouvant.', '2024-12-02 17:45:00'),
(21, 'Un parc très bien aménagé, parfait pour les familles.', '2024-12-03 14:15:00'),
(21, 'Une visite guidée très enrichissante.', '2024-12-04 12:45:00'),
(21, 'Une activité originale et plaisante.', '2024-12-05 15:00:00'),

-- Membre 22
(22, 'Un dîner savoureux, merci pour cette expérience.', '2024-12-01 16:00:00'),
(22, 'Un spectacle dynamique et bien orchestré.', '2024-12-02 18:00:00'),
(22, 'Un parc d’attractions inoubliable.', '2024-12-03 14:30:00'),
(22, 'Une visite captivante et pleine d’histoire.', '2024-12-04 13:00:00'),
(22, 'Une activité qui m’a beaucoup plu.', '2024-12-05 15:15:00'),

-- Membre 23
(23, 'Une cuisine exceptionnelle, bravo au chef.', '2024-12-01 16:15:00'),
(23, 'Un spectacle divertissant et bien écrit.', '2024-12-02 18:15:00'),
(23, 'Une journée amusante au parc.', '2024-12-03 14:45:00'),
(23, 'Merci pour cette visite guidée passionnante.', '2024-12-04 13:15:00'),
(23, 'Une activité parfaite pour se détendre.', '2024-12-05 15:30:00'),

-- Membre 24
(24, 'Une expérience culinaire mémorable.', '2024-12-01 16:30:00'),
(24, 'Un spectacle très bien interprété.', '2024-12-02 18:30:00'),
(24, 'Un parc d’attractions fantastique.', '2024-12-03 15:00:00'),
(24, 'Une visite guidée très enrichissante.', '2024-12-04 13:30:00'),
(24, 'Une activité géniale pour toute la famille.', '2024-12-05 15:45:00'),

-- Membre 25
(25, 'Un repas délicieux et bien présenté.', '2024-12-01 16:45:00'),
(25, 'Spectacle de grande qualité, bravo !', '2024-12-02 18:45:00'),
(25, 'Une journée au parc qui restera gravée dans ma mémoire.', '2024-12-03 15:15:00'),
(25, 'Merci pour cette visite intéressante et bien animée.', '2024-12-04 13:45:00'),
(25, 'Une activité ludique et très divertissante.', '2024-12-05 16:00:00');
       
INSERT INTO _avis(idC,idOffre,note,companie,mois,annee,titre,lu)
VALUES (1,1,5,'solo','novembre','2024','ouai pas mal',FALSE),
       (2,1,4,'solo','novembre','2024','ouai pas mal',FALSE),
       (3,5,5,'solo','novembre','2024','ouai pas mal',FALSE),
       (4,5,4,'solo','novembre','2024','ouai pas mal',FALSE),
       (5,2,5,'solo','novembre','2024','ouai pas mal',FALSE),
       (6,2,4,'solo','novembre','2024','ouai pas mal',FALSE),
       (7,3,5,'solo','novembre','2024','ouai pas mal',FALSE),
       (8,3,4,'solo','novembre','2024','ouai pas mal',FALSE),
       (9,4,2,'solo','novembre','2024','ouai nan!!',FALSE),
       (10,4,1,'solo','novembre','2024','ouai nan!!',FALSE),
       (14, 3, 5, 'solo', 'novembre', '2024', 'Super restaurant, je recommande', FALSE),
(15, 2, 4, 'solo', 'novembre', '2024', 'Spectacle génial', FALSE),
(16, 1, 5, 'solo', 'novembre', '2024', 'Un parc inoubliable', FALSE),
(17, 5, 4, 'solo', 'novembre', '2024', 'Visite très agréable', FALSE),
(18, 4, 5, 'solo', 'novembre', '2024', 'Activité très amusante', FALSE),

-- Membre 7
(19, 3, 4, 'solo', 'novembre', '2024', 'Très bon restaurant', FALSE),
(20, 2, 5, 'solo', 'novembre', '2024', 'Spectacle impressionnant', FALSE),
(21, 1, 4, 'solo', 'novembre', '2024', 'Un parc sympa', FALSE),
(22, 5, 3, 'solo', 'novembre', '2024', 'Visite un peu longue', FALSE),
(23, 4, 5, 'solo', 'novembre', '2024', 'Activité top', FALSE),

-- Membre 8
(24, 3, 5, 'solo', 'novembre', '2024', 'Restaurant excellent', FALSE),
(25, 2, 4, 'solo', 'novembre', '2024', 'Spectacle drôle', FALSE),
(26, 1, 5, 'solo', 'novembre', '2024', 'Parc parfait', FALSE),
(27, 5, 5, 'solo', 'novembre', '2024', 'Visite enrichissante', FALSE),
(28, 4, 4, 'solo', 'novembre', '2024', 'Activité sympa', FALSE),

-- Membre 9
(29, 3, 5, 'solo', 'novembre', '2024', 'Excellent repas', FALSE),
(30, 2, 5, 'solo', 'novembre', '2024', 'Spectacle incroyable', FALSE),
(31, 1, 4, 'solo', 'novembre', '2024', 'Parc amusant', FALSE),
(32, 5, 4, 'solo', 'novembre', '2024', 'Visite très intéressante', FALSE),
(33, 4, 5, 'solo', 'novembre', '2024', 'Activité géniale', FALSE),

-- Membre 10
(34, 3, 5, 'solo', 'novembre', '2024', 'Cuisine top', FALSE),
(35, 2, 4, 'solo', 'novembre', '2024', 'Spectacle bien joué', FALSE),
(36, 1, 5, 'solo', 'novembre', '2024', 'Parc génial', FALSE),
(37, 5, 5, 'solo', 'novembre', '2024', 'Visite parfaite', FALSE),
(38, 4, 4, 'solo', 'novembre', '2024', 'Activité plaisante', FALSE),

-- Membre 11
(39, 3, 5, 'solo', 'novembre', '2024', 'Repas délicieux', FALSE),
(40, 2, 4, 'solo', 'novembre', '2024', 'Spectacle captivant', FALSE),
(41, 1, 4, 'solo', 'novembre', '2024', 'Parc amusant', FALSE),
(42, 5, 4, 'solo', 'novembre', '2024', 'Visite agréable', FALSE),
(43, 4, 5, 'solo', 'novembre', '2024', 'Activité divertissante', FALSE),

-- Membre 12
(44, 3, 4, 'solo', 'novembre', '2024', 'Très bon repas', FALSE),
(45, 2, 5, 'solo', 'novembre', '2024', 'Spectacle sensationnel', FALSE),
(46, 1, 5, 'solo', 'novembre', '2024', 'Parc génial', FALSE),
(47, 5, 3, 'solo', 'novembre', '2024', 'Visite assez longue', FALSE),
(48, 4, 4, 'solo', 'novembre', '2024', 'Activité sympathique', FALSE),

-- Membre 13
(49, 3, 5, 'solo', 'novembre', '2024', 'Restaurant parfait', FALSE),
(50, 2, 4, 'solo', 'novembre', '2024', 'Spectacle agréable', FALSE),
(51, 1, 5, 'solo', 'novembre', '2024', 'Parc top', FALSE),
(52, 5, 4, 'solo', 'novembre', '2024', 'Visite intéressante', FALSE),
(53, 4, 5, 'solo', 'novembre', '2024', 'Activité fun', FALSE),

-- Membre 14
(54, 3, 5, 'solo', 'novembre', '2024', 'Excellente expérience culinaire', FALSE),
(55, 2, 4, 'solo', 'novembre', '2024', 'Spectacle captivant', FALSE),
(56, 1, 5, 'solo', 'novembre', '2024', 'Parc magnifique', FALSE),
(57, 5, 4, 'solo', 'novembre', '2024', 'Visite agréable et éducative', FALSE),
(58, 4, 5, 'solo', 'novembre', '2024', 'Activité géniale', FALSE),

-- Membre 15
(59, 3, 5, 'solo', 'novembre', '2024', 'Cuisine parfaite', FALSE),
(60, 2, 5, 'solo', 'novembre', '2024', 'Spectacle impressionnant', FALSE),
(61, 1, 4, 'solo', 'novembre', '2024', 'Parc agréable', FALSE),
(62, 5, 5, 'solo', 'novembre', '2024', 'Visite incroyable', FALSE),
(63, 4, 4, 'solo', 'novembre', '2024', 'Activité amusante', FALSE),

-- Membre 16
(64, 3, 5, 'solo', 'novembre', '2024', 'Repas fantastique', FALSE),
(65, 2, 4, 'solo', 'novembre', '2024', 'Spectacle superbe', FALSE),
(66, 1, 5, 'solo', 'novembre', '2024', 'Parc top', FALSE),
(67, 5, 5, 'solo', 'novembre', '2024', 'Visite inoubliable', FALSE),
(68, 4, 4, 'solo', 'novembre', '2024', 'Activité bien pensée', FALSE),

-- Membre 17
(69, 3, 5, 'solo', 'novembre', '2024', 'Restaurant délicieux', FALSE),
(70, 2, 5, 'solo', 'novembre', '2024', 'Spectacle incroyable', FALSE),
(71, 1, 4, 'solo', 'novembre', '2024', 'Parc d’attractions fun', FALSE),
(72, 5, 4, 'solo', 'novembre', '2024', 'Visite captivante', FALSE),
(73, 4, 5, 'solo', 'novembre', '2024', 'Activité géniale', FALSE),

-- Membre 18
(74, 3, 5, 'solo', 'novembre', '2024', 'Cuisine exceptionnelle', FALSE),
(75, 2, 4, 'solo', 'novembre', '2024', 'Spectacle divertissant', FALSE),
(76, 1, 5, 'solo', 'novembre', '2024', 'Parc magnifique', FALSE),
(77, 5, 4, 'solo', 'novembre', '2024', 'Visite fascinante', FALSE),
(78, 4, 5, 'solo', 'novembre', '2024', 'Activité top', FALSE),

-- Membre 19
(79, 3, 5, 'solo', 'novembre', '2024', 'Un dîner parfait', FALSE),
(80, 2, 4, 'solo', 'novembre', '2024', 'Spectacle bien joué', FALSE),
(81, 1, 5, 'solo', 'novembre', '2024', 'Parc incroyable', FALSE),
(82, 5, 4, 'solo', 'novembre', '2024', 'Visite très enrichissante', FALSE),
(83, 4, 5, 'solo', 'novembre', '2024', 'Activité amusante', FALSE),

-- Membre 20
(84, 3, 5, 'solo', 'novembre', '2024', 'Cuisine de qualité', FALSE),
(85, 2, 5, 'solo', 'novembre', '2024', 'Spectacle mémorable', FALSE),
(86, 1, 4, 'solo', 'novembre', '2024', 'Parc superbe', FALSE),
(87, 5, 5, 'solo', 'novembre', '2024', 'Visite agréable', FALSE),
(88, 4, 4, 'solo', 'novembre', '2024', 'Activité super', FALSE),

-- Membre 21
(89, 3, 5, 'solo', 'novembre', '2024', 'Repas délicieux', FALSE),
(90, 2, 4, 'solo', 'novembre', '2024', 'Spectacle surprenant', FALSE),
(91, 1, 5, 'solo', 'novembre', '2024', 'Parc superbe', FALSE),
(92, 5, 4, 'solo', 'novembre', '2024', 'Visite très instructive', FALSE),
(93, 4, 5, 'solo', 'novembre', '2024', 'Activité très amusante', FALSE),

-- Membre 22
(94, 3, 5, 'solo', 'novembre', '2024', 'Excellente expérience culinaire', FALSE),
(95, 2, 5, 'solo', 'novembre', '2024', 'Spectacle fantastique', FALSE),
(96, 1, 4, 'solo', 'novembre', '2024', 'Parc d’attractions amusant', FALSE),
(97, 5, 4, 'solo', 'novembre', '2024', 'Visite incroyable', FALSE),
(98, 4, 5, 'solo', 'novembre', '2024', 'Activité très divertissante', FALSE),

-- Membre 23
(99, 3, 5, 'solo', 'novembre', '2024', 'Un repas parfait', FALSE),
(100, 2, 4, 'solo', 'novembre', '2024', 'Spectacle divertissant', FALSE),
(101, 1, 5, 'solo', 'novembre', '2024', 'Parc magnifique', FALSE),
(102, 5, 4, 'solo', 'novembre', '2024', 'Visite très intéressante', FALSE),
(103, 4, 5, 'solo', 'novembre', '2024', 'Activité très amusante', FALSE),

-- Membre 24
(104, 3, 5, 'solo', 'novembre', '2024', 'Repas délicieux', FALSE),
(105, 2, 5, 'solo', 'novembre', '2024', 'Spectacle à ne pas manquer', FALSE),
(106, 1, 4, 'solo', 'novembre', '2024', 'Parc amusant et agréable', FALSE),
(107, 5, 4, 'solo', 'novembre', '2024', 'Visite intéressante', FALSE),
(108, 4, 5, 'solo', 'novembre', '2024', 'Activité super fun', FALSE),

-- Membre 25
(109, 3, 5, 'solo', 'novembre', '2024', 'Restaurant excellent, je recommande', FALSE),
(110, 2, 4, 'solo', 'novembre', '2024', 'Spectacle drôle et agréable', FALSE),
(111, 1, 5, 'solo', 'novembre', '2024', 'Parc d’attractions parfait', FALSE),
(112, 5, 5, 'solo', 'novembre', '2024', 'Visite très enrichissante', FALSE),
(113, 4, 4, 'solo', 'novembre', '2024', 'Activité sympa et agréable', FALSE);

       
INSERT INTO _reponse(idC,ref)
VALUES (11,3),
       (12,5),
       (13,9);
       
INSERT INTO _avisImage(idC,url)
VALUES (1,'./img/imageAvis/1/0.png'),
       (3,'./img/imageAvis/3/0.png'),
       (7,'./img/imageAvis/7/0.png');
       
INSERT INTO _historiqueStatut(idOffre,dateLancement,dureeEnLigne)
VALUES (1,'2024-11-01',6),
       (1,'2024-11-15',NULL),
       (2,'2024-11-01',4),
       (2,'2024-11-15',NULL),
       (3,'2024-11-10',NULL),
       (4,'2024-11-01',NULL),
       (5,'2024-11-01',NULL),
       (1,'2024-10-01',6),
       (1,'2024-10-15',17),
       (2,'2024-10-01',4),
       (2,'2024-10-15',17),
       (3,'2024-10-10',22),
       (4,'2024-10-01',31),
       (5,'2024-10-01',31),
       (1,'2024-09-01',6),
       (1,'2024-09-15',10),
       (2,'2024-09-01',4),
       (2,'2024-09-15',10),
       (3,'2024-09-10',20),
       (4,'2024-09-01',30),
       (5,'2024-09-01',30);
       
INSERT INTO _facturation(dateFactue,idOffre)
VALUES ('2024-12-01',1),
       ('2024-12-01',2),
       ('2024-12-01',3),
       ('2024-12-01',4),
       ('2024-12-01',5),
       ('2024-11-01',1),
       ('2024-11-01',2),
       ('2024-11-01',3),
       ('2024-11-01',4),
       ('2024-11-01',5),
       ('2024-10-01',1),
       ('2024-10-01',2),
       ('2024-10-01',3),
       ('2024-10-01',4),
       ('2024-10-01',5);
       
INSERT INTO _accessibilite(nomAccess)
VALUES ('sourd'),
       ('malentendant'),
       ('muet');
       
INSERT INTO _offreAccess(idOffre,nomAccess)
VALUES (4,'malentendant'),
       (4,'sourd'),
       (5,'muet');
       
INSERT INTO _prestation(nomPresta)
VALUES ('Repas'),
       ('Transport');
       
INSERT INTO _offrePrestation_non_inclu(idOffre,nomPresta)
VALUES (4,'Transport');
       
INSERT INTO _offrePrestation_inclu(idOffre,nomPresta)
VALUES (4,'Repas');


SELECT a.*,
    AVG(a.note) OVER() AS moynote,
    COUNT(a.note) OVER() AS nbnote,
    SUM(CASE WHEN a.note = 1 THEN 1 ELSE 0 END) OVER() AS note_1,
    SUM(CASE WHEN a.note = 2 THEN 1 ELSE 0 END) OVER() AS note_2,
    SUM(CASE WHEN a.note = 3 THEN 1 ELSE 0 END) OVER() AS note_3,
    SUM(CASE WHEN a.note = 4 THEN 1 ELSE 0 END) OVER() AS note_4,
    SUM(CASE WHEN a.note = 5 THEN 1 ELSE 0 END) OVER() AS note_5,
    SUM(CASE WHEN a.lu = FALSE then 1 else 0 end) over() as avisnonlus,
	SUM(CASE WHEN r.idc_reponse is null then 1 else 0 end) over() as avisnonrepondus,
    m.url AS membre_url,
    r.idc_reponse,
    r.denomination AS reponse_denomination,
    r.contenureponse,
    r.reponsedate,
    r.idpro
FROM 
    pact.avis a
LEFT JOIN 
    pact.membre m ON m.pseudo = a.pseudo
LEFT JOIN 
    pact.reponse r ON r.idc_avis = a.idc
WHERE 
    a.idoffre = 3
ORDER BY 
    a.datepublie desc;
