SET SCHEMA 'pact';

-- Peuplement de la table _utilisateur
INSERT INTO _utilisateur (password) 
VALUES ('motdepasse1'), ('motdepasse2'), ('motdepasse3'), ('$2y$10$UCrpwG.yZKqiAC4K2JctOeFwiar3nhNH1HWkx1NivF1KYUfnwxYTa'), ('motdepasse5');

-- Peuplement de la table _admin
INSERT INTO _admin (idU, login) 
VALUES (1, 'admin1'), (2, 'admin2');

-- Peuplement de la table _nonAdmin
INSERT INTO _nonAdmin (idU, telephone, mail) 
VALUES (3, '0123456789', 'utilisateur1@mail.com'),
       (4, '0585956535', 'toto@wanadoo.fr'),
       (5, '0123456791', 'utilisateur3@mail.com');

-- Peuplement de la table _pro
INSERT INTO _pro (idU, denomination) 
VALUES (3, 'EntreprisePro1'),
       (4, 'Patrick LÉtoile De Mer');

-- Peuplement de la table _membre
INSERT INTO _membre (idU, pseudo, nom, prenom) 
VALUES (5, 'membre1', 'Dupont', 'Jean');

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
VALUES ('Basique', 9.99), 
       ('Premium', 19.99),
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
       ('vegetarien'),
       ('animaux'),
       ('thématique'),
       ('aquatique'),
       ('interactif'),
       ('spectacles inclus'),
       ('sensations fortes');
       ('Local'),
       ('international'),
       ('insolite'),
       ('populaire'),
       ('exclusif'),
       ('vegetarien'),
       ('authentique'),
       ('romantique'),
       ('festif'),
       ('calme'),
        -------------------------------------------------------------------------------------

        /*    "", "", "", "", "", "",
    "", "", "", "", "Intimiste", "Ludique",
    "Traditionnel", "Contemporain", "Convivial", "En extérieur", "En intérieur",
    "Urbain", "Rural", "En bord de mer", "Montagne", "Patrimonial",
    "Historique", "Culturel", "Moderne", "Médiéval", "Naturel", "Industriel",
    "Féérique", "Nocturne", "Diurne", "Week-end", "Vacances scolaires",
    "Estival", "Hivernal", "Saisonnier", "Couple", "Enfants", "Adolescents",
    "Seniors", "Groupes", "Solo", "Amateurs de sensations"*/

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
INSERT INTO _option (nomOption, prixOffre, dureeOption,dateLancement) 
VALUES ('ALaUne', 19.99, 7,CURRENT_DATE), 
       ('EnRelief', 14.99, 7,CURRENT_DATE);

-- Peuplement de la table _langue
INSERT INTO _langue (langue) 
VALUES ('Anglais'), 
       ('Français'), 
       ('Espagnol');

-- Peuplement de la table _offre (une offre par catégorie)
INSERT INTO _offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume, dateCrea) 
VALUES (3, 'actif', 'Parc Génial de saint paul', 'Le meilleur parc d’attractions de la ville.', 'parc@mail.com', '0123456790', TRUE, 'http://parc.com', 'Divertissement familial', CURRENT_TIMESTAMP),
       (4, 'actif', 'Thomas angelvy', 'Un spectacle incroyable avec des performances éblouissantes.', 'spectacle@mail.com', '0123456791', TRUE, 'http://spectacle.com', 'Divertissement exceptionnel', CURRENT_TIMESTAMP),
       (3, 'actif', 'LaPotiniere', 'Découvrez la gastronomie locale.', 'restaurant@mail.com', '0123456793', TRUE, NULL, 'Cuisine raffinée', CURRENT_TIMESTAMP),
       (3, 'actif', 'Activité Culturelle', 'Explorez la culture locale à travers une activité immersive.', 'activite@mail.com', '0123456794', TRUE, 'http://activite.com', 'Immersion culturelle aves la population local', CURRENT_TIMESTAMP),
       (4, 'actif', 'Visite Guidée du cap fréhel', 'Visite guidée des plus beaux sites du cap Fréhel.', 'visite@mail.com', '0123456795', TRUE, NULL, 'Découverte guidée', CURRENT_TIMESTAMP);

-- Peuplement de la table _image
INSERT INTO _image (url, nomImage) 
VALUES ('./img/profile_picture/default.svg', 'default.svg'), 
       ('./img/imageOffre/3-0.png', 'laPotiniere0'),
       ('./img/imageOffre/3-1.png', 'laPotiniere1'),
       ('./img/imageOffre/3-2.png', 'laPotiniere2'),
       ('./img/imageOffre/3-3.png', 'laPotiniere3'),
       ('./img/imageOffre/3-4.png', 'laPotiniere4'),
       ('./img/imageOffre/4-0.png', 'activite4'),
       ('./img/imageOffre/1-0.png', 'parc0'),
       ('./img/imageOffre/1-1.png', 'parc1'),
       ('./img/imageOffre/1-2.png', 'parc2'),
       ('./img/imageOffre/2-0.png', 'spectacle0'),
       ('./img/imageOffre/5-0.png', 'visite0');

-- Peuplement de la table _illustre
INSERT INTO _illustre (idOffre, url) 
VALUES (3, './img/imageOffre/3-0.png'),
       (3, './img/imageOffre/3-1.png'),
       (3, './img/imageOffre/3-2.png'),
       (3, './img/imageOffre/3-3.png'),
       (3, './img/imageOffre/3-4.png'),
       (1, './img/imageOffre/1-0.png'),
       (1, './img/imageOffre/1-1.png'),
       (1, './img/imageOffre/1-2.png'),
       (2, './img/imageOffre/2-0.png'),
       (4, './img/imageOffre/4-0.png'),
       (5, './img/imageOffre/5-0.png');

-- Peuplement de la table _photo_profil
INSERT INTO _photo_profil (idU, url) 
VALUES (1, './img/profile_picture/default.svg'),
	   (2, './img/profile_picture/default.svg'),
	   (3, './img/profile_picture/default.svg'),
	   (4, './img/profile_picture/default.svg'),
       (5, './img/profile_picture/default.svg');

-- Peuplement de la table _consulter
INSERT INTO _consulter (idU, idOffre, dateConsultation) 
VALUES (5, 1, '2024-10-10'), 
       (5, 2, '2024-10-12');

-- Peuplement des tables spécifiques aux types d'offres (chaque offre appartient à une seule catégorie)
-- Parc d'attraction (idOffre 1)
INSERT INTO _parcAttraction (idOffre, ageMin, nbAttraction, prixMinimal, urlPlan) 
VALUES (1, 5, 20, 15.0, 'http://planparc.com');

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

-- Peuplement de la table _horaireMidi
INSERT INTO _horaireMidi (jour, idOffre, heureOuverture, heureFermeture) 
VALUES ('Lundi', 1, '10:00', '18:00'), 
       ('Mardi', 1, '10:00', '18:00'), 
       ('Mercredi', 1, '10:00', '18:00'),
       ('Jeudi', 1, '10:00', '18:00'), 
       ('Vendredi', 1, '10:00', '18:00'),
       ('Samedi', 1, '10:00', '17:00'),
       ('Lundi', 2, '10:00', '12:00'), 
       ('Mardi', 2, '10:00', '12:00'), 
       ('Mercredi', 2, '10:00', '12:00'),
       ('Jeudi', 2, '10:00', '12:00'), 
       ('Vendredi', 2, '10:00', '12:00'),
       ('Samedi', 2, '10:00', '12:00'),
       ('Lundi', 3, '12:00', '15:00'), 
       ('Mardi', 3, '12:00', '15:00'), 
       ('Mercredi', 3, '12:00', '15:00'),
       ('Jeudi', 3, '12:00', '15:00'), 
       ('Vendredi', 3, '12:00', '15:00'),
       ('Samedi', 3, '12:00', '15:00'),
       ('Lundi', 4, '09:00', '12:00'), 
       ('Mardi', 4, '14:00', '17:00'), 
       ('Mercredi', 4, '16:00', '19:00'),
       ('Jeudi', 4, '14:00', '17:00'), 
       ('Vendredi', 4, '08:00', '11:00'),
       ('Samedi', 4, '14:00', '17:00'),
       ('Lundi', 5, '10:00', '18:00'), 
       ('Mardi', 5, '10:00', '18:00'), 
       ('Mercredi', 5, '10:00', '18:00'),
       ('Vendredi', 5, '10:00', '18:00'),
       ('Samedi', 5, '10:00', '17:00');

-- Peuplement de la table _tag_parc
INSERT INTO _tag_parc (idOffre, nomTag) 
VALUES (1, 'familial'), 
       (1, 'animaux'),
       (1,'thématique'),
       (1,'aquatique'),
       (1,'interactif'),
       (1,'spectacles inclus'),
       (1,'sensations fortes');
--(sensations fortes",
-- Peuplement de la table _tag_spec
INSERT INTO _tag_spec (idOffre, nomTag) 
VALUES (2, 'romantique');

-- Peuplement de la table _tag_restaurant
INSERT INTO _tag_restaurant (idOffre, nomTag) 
VALUES (3, 'familial'),
       (3, 'breton'),
       (3, 'local'),
       (3, 'vegetarien'),
       (3, 'poisson');

-- Peuplement de la table _tag_Act
INSERT INTO _tag_Act (idOffre, nomTag) 
VALUES (4, 'culturel');

-- Peuplement de la table _tag_visite
INSERT INTO _tag_visite (idOffre, nomTag) 
VALUES (5, 'culturel');

-- Peuplement de la table _habite
INSERT INTO _habite (idU, codePostal, ville, pays, rue, numeroRue) 
VALUES (5, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
		(4, '22300', 'Fond de l eau', 'France', 'rue de l ananas au fond de la mer', 123);

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

-- Peuplement de la table _option_offre
INSERT INTO _option_offre (idOffre, nomOption) 
VALUES (1, 'EnRelief'), 
       (2, 'ALaUne');
       
INSERT INTO _visite_langue (idOffre, langue) 
VALUES (5, 'Français'), 
       (5, 'Anglais');
       
INSERT INTO _menu(idOffre, menu)
VALUES (3,'./img/3-menu.png');

select * from offres;