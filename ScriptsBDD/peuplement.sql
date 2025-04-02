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
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'),
       ('ojnaemjhrfpouhqzefpouihqzrpouh.zogufoiygUEFOGIUTAdvzriutfzhh'),
       ('$2y$10$Ic14flPz8mbIIuNCYV.hdOdf97dwdGoYzv2bJaRWTjuuRaa0aGyuC'), 
       ('$2y$10$UCrpwG.yZKqiAC4K2JctOeFwiar3nhNH1HWkx1NivF1KYUfnwxYTa'), 
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
       (25, '0614569852', 'utilisateur25@mail.com '),
       (26, '0678654126', 'anonyme@anonyme.com'),
       (27, '0614569852', 'jordancarter@mail.com'),
       (28, '0298012345', 'contact@breizh-tourisme.bzh'),  
       (29, '0298546789', 'info@armor-restauration.fr'),
       (30, '0298546789', 'reservation@lepetitport.fr'),  
       (31, '0298654321', 'info@hotelarmorique.bzh'),
       (32, '0298741256', 'contact@aqua-breizh.fr'),
       (33, '0298456723', 'reservation@creperie-kerlann.fr'),
       (34, '0298567894', 'contact@forestadventure.bzh'), 
       (35, '0298123456', 'contact@terrenatae.bzh'); 


-- Peuplement de la table _pro
INSERT INTO _pro (idU, denomination) 
VALUES (3, 'EntreprisePro1'),
       (4, 'Patrick'),
       (27, 'Jordan Carter'),
       (28, 'Breizh Tourisme'),
       (29, 'Cabac Park'),
       (30, 'La Récrée des 3 Curés'),
       (31, 'Forêt Adrénaline'),
       (32, 'Karting Ouest'),
       (33, 'Crêperie Kerlann'),
       (34, 'Le Café du Port'),
       (35, 'Les Terres de Natae');

-- Peuplement de la table _membre
INSERT INTO _membre (idU, pseudo, nom, prenom) 
VALUES (5, 'membre1', 'Dupont', 'Jean'),
       (6, 'Paul29', 'Durant', 'Paul'),
       (7, 'chantal47', 'Langlois', 'Chantal'),
       (8, 'nono53', 'Broux', 'Nolan'),
       (9, 'jean2', 'Kerebel', 'Jean'),
       (10, 'Redwin', 'Jain', 'Ewen'),
       (11, 'gab', 'Froc', 'Gabriel'),
       (12, 'Arkade', 'Guillerm', 'Antoine'),
       (13, 'Traxsab', 'Cochet', 'Iwan'),
       (14, 'mattaque', 'Kervadec', 'mattéo'),
       (15, 'BZHKylian', 'Houedec', 'kylian'),
       (16, 'Marcdu89', 'Durand', 'Marc'),
       (17, 'James04', 'legrand', 'James'),
       (18, 'Amelielp', 'lePetit', 'Amelie'),
       (19, 'benji35', 'Girard', 'Benjamin'),
       (20, 'nico', 'Latifi', 'Nicolas'),
       (21, 'Bernard', 'jojo', 'Bernard'),
       (22, 'IbraTV', 'ibra', 'tv'),
       (23, 'Marius', 'Duclos', 'Marius'),
       (24, 'HenryDu56', 'legurrec', 'Henry'),
       (25, 'Jacques1', 'lebris', 'Jacques'),
       (26, 'Ancien Membre', 'Anonyme', 'Ancien Membre');

-- Peuplement de la table _public
INSERT INTO _public (idU) 
VALUES (3);

-- Peuplement de la table _privee
INSERT INTO _privee (idU, siren) 
VALUES (4, '321654987'),
       (27, '852963741'),
       (28, '963258147'),
       (29, '741852963'),
       (30, '159753486'),
       (31, '357159486'),
       (32, '951357258'),
       (33, '268357149'),
       (34, '753468219'),
       (35, '624897135');

-- Peuplement de la table _adresse
INSERT INTO _adresse (numeroRue, rue, ville, pays, codePostal) 
VALUES (1, 'Rue Édouard Branly', 'Lannion', 'France', '22300'),
       (4, 'Allée des acacias', 'Fréhel', 'France', '22000'),
	(2, 'Rue de Kerniflet', 'Lannion', 'France', '22300'),
	('1','Route du cap', 'Fréhel', 'France','22000'),
	('5','Rue Beauchamp','Lannion','France','22300'),
	('1','Rue du port','Erquy','France','22430'),
	('1', 'Rue des anciens membres anonymes', 'Lannion', 'France', '22300'),

       -- New
       ('5', 'convenant roussel', 'Pluzunet', 'France', '22140'),
       ('23', 'Rue de Merlin', 'Trégastel', 'France', '22730'),
       ('3', 'Rue de Église', 'Saint-Alban', 'France', '22400'),
       ('14', 'Rue de la Clarté', 'Perros-Guirec', 'France', '22700'),
       ('28', 'Rue de la Latte', 'Plévenon', 'France', '22240'),
       ('23', 'Rue des Grèves', 'Langueux', 'France', '22360'),
       ('27', 'Kerouziel', 'Plouha', 'France', '22580'),
       ('17', 'Rue de Trolay', 'Perros-Guirec', 'France', '22700'),
       (1, 'Pointe de l Arcouest', 'Ploubazlanec', 'France', '22620'),
       (1, 'Le Bois du Cobac', 'Mesnil-Roc h', 'France', '35720'),
       (1, '1 Trois Curés', 'Milizac-Guipronvel', 'France', '29290'),
       (1, 'Base de loisirs des Gayeulles', 'Rennes', 'France', '35700'),
       (1, 'Fontainebleau', 'Carnac', 'France', '56340'),
       (1, 'Kervadail', 'Ploumoguer', 'France', '29810'),
       (1, 'Rue Saint-Georges', 'Rennes', 'France', '35000'),
       (1, 'Quai de Doëlan', 'Clohars-Carnoët', 'France', '29360'),
       (1, 'Kersamedi', 'Pont-Scorff', 'France', '56620'),
       (1, 'Rue Roland Morillot', 'Lorient', 'France', '56100'),
       ('12', 'Rue des Goélands', 'Vannes', 'France','56000' );

-- Peuplement de la table _abonnement
INSERT INTO _abonnement (nomAbonnement, tarif) 
VALUES ('Basique', 1.67), 
       ('Premium', 3.34),
	('Gratuit', 0.0);

-- Peuplement de la table _statut
INSERT INTO _statut (statut) 
VALUES ('actif'), 
       ('inactif'),
       ('delete');

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
-- Peuplement de la table _offre
INSERT INTO _offre (idU, statut, nom, description, mail, telephone, affiche, urlSite, resume, dateCrea) 
VALUES (3, 'actif', 'Parc Génial de saint paul', 'Le meilleur parc d’attractions de la ville.', 'parc@mail.com', '0123456790', TRUE, 'http://parc.com', 'Divertissement familial', CURRENT_TIMESTAMP),
       (4, 'actif', 'Thomas Angelvy', 'Un spectacle incroyable avec des performances éblouissantes.', 'spectacle@mail.com', '0123456791', TRUE, 'http://spectacle.com', 'Divertissement exceptionnel', CURRENT_TIMESTAMP),
       (4, 'actif', 'La Potinière', 'Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. Découvrez la gastronomie locale. ', 'restaurant@mail.com', '0123456793', TRUE, NULL, 'Cuisine raffinée', CURRENT_TIMESTAMP),
       (3, 'actif', 'Activité Culturelle', 'Explorez la culture locale à travers une activité immersive.', 'activite@mail.com', '0123456794', TRUE, 'http://activite.com', 'Immersion culturelle aves la population local', CURRENT_TIMESTAMP),
       (4, 'actif', 'Visite Guidée du cap fréhel', 'Visite guidée des plus beaux sites du cap Fréhel.', 'visite@mail.com', '0123456795', TRUE, NULL, 'Découverte guidée', CURRENT_TIMESTAMP),
       (4, 'inactif', 'Brain l escape game', 'L unique escape game de Lannion.', 'escape.game@mail.com', '0987654321', TRUE, 'http://escape-game.com', 'Divertissement familial', CURRENT_TIMESTAMP),
       (4, 'inactif', 'Baie de Lannion', 'Venez voir la mangnifique Baie de Lannion', 'baie-lannuin@mail.com', '0267842542', TRUE, 'http://baiedelannion.com', 'Divertissement familial', CURRENT_TIMESTAMP),

       -- New
       (27, 'actif', 'Randonnée Quad en Côtes dArmor', 'Partez à la conquête de nouveaux espaces lors dune randonnée en Quad à travers la campagne bretonne, dans un cadre naturel exceptionnel. Vous serez accompagné par un guide qualifié.', 'quad-cotesdarmor@mail.com', '0296123450', TRUE, 'http://randoneequadbzh.com', 'Aventure en plein air et découverte de la nature', CURRENT_TIMESTAMP),
       (4, 'actif', 'La Grève Blanche', 'Restaurant situé à Trégastel, offrant une vue imprenable sur la mer. Spécialité de fruits de mer frais et plats traditionnels bretons.', 'greveblanche@mail.com', '0296123451', TRUE, 'http://greveblanche.com', 'Cuisine traditionnelle de la mer', CURRENT_TIMESTAMP),
       (4, 'actif', 'La Crêperie du Village Saint-Alban', 'Savourez de délicieuses galettes et crêpes bretonnes dans cette crêperie chaleureuse au cœur de Saint-Alban, idéale pour un repas en famille ou entre amis.', 'creperie-village@mail.com', '0296123452', TRUE, 'http://creperie-saintalban.com', 'Spécialités bretonnes authentiques', CURRENT_TIMESTAMP),
       (27, 'actif', 'Armor Navigation', 'Embarquez pour une excursion autour des célèbres Sept-Îles en vedette et découvrez un des plus beaux sites naturels de Bretagne, un véritable paradis pour les amateurs doiseaux.', 'armor-navigation@mail.com', '0296123453', TRUE, 'http://armor-navigation.com', 'Excursion en mer et observation des oiseaux', CURRENT_TIMESTAMP),
       (4, 'actif', 'Fort La Latte', 'Visitez ce magnifique château fortifié du 14ème siècle, avec une vue spectaculaire sur la mer et une riche histoire. Idéal pour les passionnés de patrimoine et darchitecture médiévale.', 'fort-latte@mail.com', '0296123454', TRUE, 'http://fort-latte.com', 'Histoire et architecture médiévale', CURRENT_TIMESTAMP),
       (4, 'actif', 'Crêperie des Grèves Langueux', 'Une crêperie qui ravira les amateurs de galettes et de crêpes, avec une vue sur les plages de Langueux. Un cadre agréable pour déguster des plats typiques de la Bretagne.', 'creperie-grèves@mail.com', '0296123455', TRUE, 'http://creperie-desgreves.com', 'Crêpes bretonnes authentiques', CURRENT_TIMESTAMP),
       (27, 'actif', 'Les Falaises de Plouha', 'Explorez les impressionnantes falaises de Plouha, un site naturel remarquable offrant des panoramas époustouflants sur la mer. Idéal pour une randonnée en bord de mer et pour admirer la beauté sauvage de la Côte dArmor.', 'falaises-plouha@mail.com', '0296123456', TRUE, 'http://falaises-plouha.com', 'Randonnée et panoramas en bord de mer', CURRENT_TIMESTAMP),
       (27, 'actif', 'Côte de Granit Rose', 'Découvrez lun des plus beaux sites naturels de Bretagne avec ses roches granitiques aux formes spectaculaires, entre Ploumanac’h et Perros-Guirec. À explorer en randonnée ou à vélo.', 'granite-rose@mail.com', '0296123457', TRUE, 'http://cotedegranitrose.com', 'Randonnée et découverte des paysages', CURRENT_TIMESTAMP),
       (28, 'actif', 'Croisière vers l’Île de Bréhat', 'Partez en excursion maritime vers l’Île de Bréhat avec une traversée commentée et la découverte des paysages côtiers.', 'contact@armor-navigation.com', '0296123450', TRUE, 'http://armor-navigation.com', 'Excursion en mer et découverte de Bréhat', CURRENT_TIMESTAMP),
       (29, 'actif', 'Cobac Parc', 'Un parc d’attractions familial en plein cœur de la Bretagne avec des manèges et un parc aquatique.', 'contact@cobac-parc.com', '0299794829', TRUE, 'http://cobac-parc.com', 'Parc d’attractions et aquatique', CURRENT_TIMESTAMP),
       (30, 'actif', 'La Récré des 3 Curés', 'Un parc de loisirs incontournable avec des attractions pour petits et grands, et un espace aquatique.', 'contact@recredes3cures.com', '0298058080', TRUE, 'http://recredes3cures.com', 'Loisirs en famille et sensations fortes', CURRENT_TIMESTAMP),
       (31, 'actif', 'Forêt Adrénaline Rennes', 'Un parc accrobranche pour une aventure dans les arbres avec des parcours adaptés à tous les âges.', 'contact@foret-adrenaline.com', '0299168523', TRUE, 'http://foret-adrenaline.com', 'Aventure et accrobranche', CURRENT_TIMESTAMP),
       (31, 'actif', 'Forêt Adrénaline Carnac', 'Un parc accrobranche pour une aventure dans les arbres avec des parcours adaptés à tous les âges.', 'contact@foret-adrenaline.com', '0299168523', TRUE, 'http://foret-adrenaline.com', 'Aventure et accrobranche', CURRENT_TIMESTAMP),
       (32, 'actif', 'Karting de Ploumoguer', 'Un circuit de karting en extérieur pour des sensations fortes en toute sécurité.', 'contact@kartingploumoguer.com', '0298654785', TRUE, 'http://kartingploumoguer.com', 'Sport mécanique et adrénaline', CURRENT_TIMESTAMP),
       (34, 'actif', 'Le Café du Port', 'Un restaurant avec vue sur le port de Doëlan, proposant des spécialités de fruits de mer et plats bretons.', 'reservation@cafeduport.com', '0298976521', TRUE, 'http://cafeduport.com', 'Cuisine bretonne et fruits de mer', CURRENT_TIMESTAMP),
       (33, 'actif', 'Crêperie Kerlann', 'Crêperie moderne à Rennes proposant des galettes revisitées et des crêpes gourmandes.', 'contact@creperiesaintgeorges.com', '0299923648', TRUE, 'http://creperiesaintgeorges.com', 'Crêpes et galettes bretonnes revisitées', CURRENT_TIMESTAMP),
       (35, 'actif', 'Les Terres de Nataé', 'Un parc animalier engagé dans la préservation des espèces menacées. Découvrez plus de 150 animaux dans un cadre naturel exceptionnel.', 'contact@terresdenatae.com', '0297826541', TRUE, 'http://terresdenatae.com', 'Parc animalier et conservation', CURRENT_TIMESTAMP),
       (28, 'actif', 'La Cité de la Voile Éric Tabarly', 'Un musée interactif sur la navigation et la voile, dédié au célèbre navigateur Éric Tabarly.', 'contact@citevoile-tabarly.com', '0297642900', TRUE, 'http://citevoile-tabarly.com', 'Musée et découverte maritime', CURRENT_TIMESTAMP);

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
       ('./img/imageOffre/6/1.jpeg', 'brain1'), /* ajout brain */
       ('./img/imageAvis/1/0.png','Avis1-0'),
       ('./img/imageAvis/3/0.png','Avis3-0'),
       ('./img/imageAvis/7/0.png','Avis7-0'),


       --New
       ('./img/imageOffre/8/1.jpg', 'newOffre'),
       ('./img/imageOffre/9/1.jpg', 'newOffre'),
       ('./img/imageOffre/10/1.jpg', 'newOffre'),
       ('./img/imageOffre/11/1.jpg', 'newOffre'),
       ('./img/imageOffre/12/1.jpg', 'newOffre'),
       ('./img/imageOffre/13/1.jpg', 'newOffre'),
       ('./img/imageOffre/14/1.jpg', 'newOffre'),
       ('./img/imageOffre/15/1.jpg', 'newOffre'),
       
       ('./img/imageOffre/16/0.png', 'newOffre'),
       ('./img/imageOffre/16/1.png', 'newOffre'),
       ('./img/imageOffre/16/2.png', 'newOffre'),
       ('./img/imageOffre/17/0.png', 'newOffre'),
       ('./img/imageOffre/17/1.png', 'newOffre'),
       ('./img/imageOffre/18/0.png', 'newOffre'),
       ('./img/imageOffre/18/1.png', 'newOffre'),
       ('./img/imageOffre/19/0.png', 'newOffre'),
       ('./img/imageOffre/19/1.png', 'newOffre'),
       ('./img/imageOffre/20/0.png', 'newOffre'),
       ('./img/imageOffre/20/1.png', 'newOffre'),
       ('./img/imageOffre/21/0.png', 'newOffre'),
       ('./img/imageOffre/22/0.png', 'newOffre'),
       ('./img/imageOffre/23/0.png', 'newOffre'),
       ('./img/imageOffre/24/0.png', 'newOffre'),
       ('./img/imageOffre/24/1.png', 'newOffre'),
       ('./img/imageOffre/24/2.png', 'newOffre'),
       ('./img/imageOffre/25/0.png', 'newOffre');





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
       (5,'./img/imageOffre/5/2.png'),
       (6,'./img/imageOffre/6/1.jpeg'), /* essaie pour brain */

       --New
       (8,'./img/imageOffre/8/1.jpg'),
       (9,'./img/imageOffre/9/1.jpg'),
       (10,'./img/imageOffre/10/1.jpg'),
       (11,'./img/imageOffre/11/1.jpg'),
       (12,'./img/imageOffre/12/1.jpg'),
       (13,'./img/imageOffre/13/1.jpg'),
       (14,'./img/imageOffre/14/1.jpg'),
       (15,'./img/imageOffre/15/1.jpg'),

       (16, './img/imageOffre/16/0.png'),
       (16, './img/imageOffre/16/1.png'),
       (16, './img/imageOffre/16/2.png'),
       (17, './img/imageOffre/17/0.png'),
       (17, './img/imageOffre/17/1.png'),
       (18, './img/imageOffre/18/0.png'),
       (18, './img/imageOffre/18/1.png'),
       (19, './img/imageOffre/19/0.png'),
       (19, './img/imageOffre/19/1.png'),
       (20, './img/imageOffre/20/0.png'),
       (20, './img/imageOffre/20/1.png'),
       (21, './img/imageOffre/21/0.png'),
       (22, './img/imageOffre/22/0.png'),
       (23, './img/imageOffre/23/0.png'),
       (24, './img/imageOffre/24/0.png'),
       (24, './img/imageOffre/24/1.png'),
       (24, './img/imageOffre/24/2.png'),
       (25, './img/imageOffre/25/0.png');


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
       (25, './img/profile_picture/default.svg'),
       (26, './img/profile_picture/default.svg'),
       (27, './img/profile_picture/default.svg'),
       (28, './img/profile_picture/default.svg'),
       (29, './img/profile_picture/default.svg'),
       (30, './img/profile_picture/default.svg'),
       (31, './img/profile_picture/default.svg'),
       (32, './img/profile_picture/default.svg'),
       (33, './img/profile_picture/default.svg'),
       (34, './img/profile_picture/default.svg'),
       (35, './img/profile_picture/default.svg');

-- Peuplement de la table _consulter
INSERT INTO _consulter (idU, idOffre, dateConsultation) 
VALUES (5, 1, '2024-10-10'), 
       (5, 2, '2024-10-12');

-- Peuplement des tables spécifiques aux types d'offres (chaque offre appartient à une seule catégorie)
-- Parc d'attraction (idOffre 1)
INSERT INTO _parcAttraction (idOffre, ageMin, nbAttraction, prixMinimal, urlPlan) 
VALUES (1, 5, 20, 15.0, './img/imagePlan/1/0.jpg'),
       (17, 3, 35, 11, './img/imagePlan/17/0.jpg'),
       (18, 3, 35, 11, './img/imagePlan/18/0.jpg'),
       (19, 3, 35, 11, './img/imagePlan/19/0.jpg'),
       (20, 3, 35, 11, './img/imagePlan/20/0.jpg');

-- Spectacle (idOffre 2)
INSERT INTO _spectacle (idOffre, duree, nbPlace, prixMinimal) 
VALUES (2, 120, 100, 25.0);

-- Restaurant (idOffre 3)
INSERT INTO _restauration (idOffre, gammeDePrix) 
VALUES (3, '€€€'),

       --New
       (9, '€€'),
       (10, '€€'),
       (13, '€€€'),
       (22, '€'),
       (23, '€€');

-- Activité (idOffre 4)
INSERT INTO _activite (idOffre, duree, ageMin, prixMinimal) 
VALUES (4, 180, 10, 20.0),
       (6, 180, 10, 20.0),

       --New
       (11, 180, 10, 5.0),
       (8, 180, 15, 40.0),
       (21, 120, 2, 15.0),
       (24, 230, 0, 20.0),
       (25, 150, 3, 11);

-- Visite (idOffre 5)
INSERT INTO _visite (idOffre, guide, duree, prixMinimal, accessibilite) 
VALUES (5, TRUE, 120, 30.0, TRUE),
       (7, TRUE, 120, 30.0, FALSE),

       --New
       (12, TRUE, 120, 10.0, FALSE),
       (14, TRUE, 120, 10.0, FALSE),
       (15, TRUE, 120, 10.0, FALSE),
       (16, TRUE, 120, 10.0, FALSE);

-- Peuplement de la table _horaireSoir
INSERT INTO _horaireSoir (jour, idOffre, heureOuverture, heureFermeture) 
VALUES ('Lundi', 3, '19:00', '21:00'),
       ('Mardi', 3, '19:00', '21:00'), 
       ('Mercredi', 3, '19:00', '21:00'),
       ('Jeudi', 3, '19:00', '21:00'), 
       ('Vendredi', 3, '19:00', '21:00'),
       ('Samedi', 3, '19:00', '21:00'),

       --New
       ('Lundi', 9, '19:00', '21:00'),
       ('Mardi', 9, '19:00', '21:00'), 
       ('Mercredi', 9, '19:00', '21:00'),
       ('Jeudi', 9, '19:00', '21:00'), 
       ('Vendredi', 9, '19:00', '21:00'),
       ('Samedi', 9, '19:00', '21:00'),

       ('Lundi', 10, '19:00', '21:00'),
       ('Mardi', 10, '19:00', '21:00'), 
       ('Mercredi', 10, '19:00', '21:00'),
       ('Jeudi', 10, '19:00', '21:00'), 
       ('Vendredi', 10, '19:00', '21:00'),
       ('Samedi', 10, '19:00', '21:00'),

       ('Lundi', 13, '19:00', '21:00'),
       ('Mardi', 13, '19:00', '21:00'), 
       ('Mercredi', 13, '19:00', '21:00'),
       ('Jeudi', 13, '19:00', '21:00'), 
       ('Vendredi', 13, '19:00', '21:00'),
       ('Samedi', 13, '19:00', '21:00');

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
       ('Samedi', 5, '10:00', '17:00'),

       --New
       ('Lundi', 9, '12:00', '15:00'), 
       ('Mardi', 9, '12:00', '15:00'), 
       ('Mercredi', 9, '12:00', '15:00'),
       ('Jeudi', 9, '12:00', '15:00'), 
       ('Vendredi', 9, '12:00', '15:00'),
       ('Samedi', 9, '12:00', '15:00'),

       ('Lundi', 10, '12:00', '15:00'), 
       ('Mardi', 10, '12:00', '15:00'), 
       ('Mercredi', 10, '12:00', '15:00'),
       ('Jeudi', 10, '12:00', '15:00'), 
       ('Vendredi', 10, '12:00', '15:00'),
       ('Samedi', 10, '12:00', '15:00'),

       ('Lundi', 13, '12:00', '15:00'), 
       ('Mardi', 13, '12:00', '15:00'), 
       ('Mercredi', 13, '12:00', '15:00'),
       ('Jeudi', 13, '12:00', '15:00'), 
       ('Vendredi', 13, '12:00', '15:00'),
       ('Samedi', 13, '12:00', '15:00'),
       
       ('Lundi', 16, '10:00', '18:00'), 
       ('Mardi', 16, '10:00', '18:00'), 
       ('Mercredi', 16, '10:00', '18:00'),
       ('Jeudi', 16, '10:00', '18:00'), 
       ('Vendredi', 16, '10:00', '18:00'),
       ('Samedi', 16, '10:00', '17:00'),

       ('Lundi', 17, '12:00', '15:00'), 
       ('Mardi', 17, '12:00', '15:00'), 
       ('Mercredi', 17, '12:00', '15:00'),
       ('Jeudi', 17, '12:00', '15:00'), 
       ('Vendredi', 17, '12:00', '15:00'),
       ('Samedi', 17, '12:00', '15:00'),

       ('Lundi', 18, '12:00', '15:00'), 
       ('Mardi', 18, '12:00', '15:00'), 
       ('Mercredi', 18, '12:00', '15:00'),
       ('Jeudi', 18, '12:00', '15:00'), 
       ('Vendredi', 18, '12:00', '15:00'),
       ('Samedi', 18, '12:00', '15:00'),

       ('Lundi', 19, '10:00', '18:00'), 
       ('Mardi', 19, '10:00', '18:00'), 
       ('Mercredi', 19, '10:00', '18:00'),
       ('Jeudi', 19, '10:00', '18:00'), 
       ('Vendredi', 19, '10:00', '18:00'),
       ('Samedi', 19, '10:00', '17:00'),

       ('Lundi', 20, '12:00', '15:00'), 
       ('Mardi', 20, '12:00', '15:00'), 
       ('Mercredi', 20, '12:00', '15:00'),
       ('Jeudi', 20, '12:00', '15:00'), 
       ('Vendredi', 20, '12:00', '15:00'),
       ('Samedi', 20, '12:00', '15:00'),

       ('Lundi', 21, '12:00', '15:00'), 
       ('Mardi', 21, '12:00', '15:00'), 
       ('Mercredi', 21, '12:00', '15:00'),
       ('Jeudi', 21, '12:00', '15:00'), 
       ('Vendredi', 21, '12:00', '15:00'),
       ('Samedi', 21, '12:00', '15:00'),

       ('Lundi', 22, '10:00', '18:00'), 
       ('Mardi', 22, '10:00', '18:00'), 
       ('Mercredi', 22, '10:00', '18:00'),
       ('Jeudi', 22, '10:00', '18:00'), 
       ('Vendredi', 22, '10:00', '18:00'),
       ('Samedi', 22, '10:00', '17:00'),

       ('Lundi', 23, '12:00', '15:00'), 
       ('Mardi', 23, '12:00', '15:00'), 
       ('Mercredi', 23, '12:00', '15:00'),
       ('Jeudi', 23, '12:00', '15:00'), 
       ('Vendredi', 23, '12:00', '15:00'),
       ('Samedi', 23, '12:00', '15:00'),

       ('Lundi', 24, '12:00', '15:00'), 
       ('Mardi', 24, '12:00', '15:00'), 
       ('Mercredi', 24, '12:00', '15:00'),
       ('Jeudi', 24, '12:00', '15:00'), 
       ('Vendredi', 24, '12:00', '15:00'),
       ('Samedi', 24, '12:00', '15:00'),

       ('Lundi', 25, '10:00', '18:00'), 
       ('Mardi', 25, '10:00', '18:00'), 
       ('Mercredi', 25, '10:00', '18:00'),
       ('Jeudi', 25, '10:00', '18:00'), 
       ('Vendredi', 25, '10:00', '18:00'),
       ('Samedi', 25, '10:00', '17:00');

-- Peuplement de la table _tag_parc
INSERT INTO _tag_parc (idOffre, nomTag) 
VALUES (1, 'familial'),
       (17, 'sensations_fortes'),
       (17, 'familial'),
       (17, 'aquatique'),
       (17, 'en_extérieur'),
       (17, 'breton'),
       (17, 'estival'),
       (18, 'sensations_fortes'),
       (18, 'familial'),
       (18, 'aquatique'),
       (18, 'en_extérieur'),
       (18, 'breton'),
       (18, 'estival'),
       (19, 'aventure'),
       (19, 'sensations_fortes'),
       (19, 'naturel'),
       (19, 'en_extérieur'),
       (19, 'familial'),
       (20, 'aventure'),
       (20, 'sensations_fortes'),
       (20, 'naturel'),
       (20, 'en_extérieur'),
       (20, 'familial');
-- Peuplement de la table _tag_spec
INSERT INTO _tag_spec (idOffre, nomTag) 
VALUES (2, 'stand-up'),
       (2, 'intimiste'),
       (2, 'convivial');

-- Peuplement de la table _tag_restaurant
INSERT INTO _tag_restaurant (idOffre, nomTag) 
VALUES (3, 'cuisine_locale'),

       --New
       (9, 'cuisine_locale'),
       (10, 'cuisine_locale'),
       (13, 'cuisine_locale'),
       (22, 'convivial'),
       (22, 'traditionnel'),
       (22, 'authentique'),
       (22, 'calme'),
       (23, 'cuisine_locale'),
       (23, 'breton'),
       (23, 'traditionnel'),
       (23, 'convivial'),
       (23, 'familial');


-- Peuplement de la table _tag_Act
INSERT INTO _tag_Act (idOffre, nomTag) 
VALUES (4, 'culturel'),
       (8,'solo'),
       (8,'groupes'),
       (11,'calme'),
       (11,'familial'),
       (24,'familial'),
       (24,'éducative'),
       (24,'naturel'),
       (24,'immersive'),
       (24,'en_extérieur'),
       (25,'interactif'),
       (25,'aquatique'),
       (25,'éducative'),
       (25,'familial'),
       (25,'immersive'),
       (25,'patrimonial'),
       (21,'sensations_fortes'),
       (21,'adolescents'),
       (21,'groupes'),
       (21,'activité_immersive'),
       (21,'en_extérieur');

-- Peuplement de la table _tag_visite
INSERT INTO _tag_visite (idOffre, nomTag) 
VALUES (5, 'culturel'),
       (12,'solo'),
       (12,'groupes'),
       (12,'populaire'),
       (14,'solo'),
       (14,'groupes'),
       (14,'populaire'),
       (15,'solo'),
       (15,'groupes'),
       (15,'populaire'),
       (16,'familial'),
       (16,'romantique'),
       (16,'aventure'),
       (16,'en_bord_de_mer'),
       (16,'local'),
       (16,'saisonnier');

-- Peuplement de la table _habite
INSERT INTO _habite (idU, codePostal, ville, pays, rue, numeroRue) 
VALUES (5, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
       (3, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
	(4, '22300', 'Lannion', 'France', 'Rue de Kerniflet', 2),
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
	(25, '22300', 'Lannion', 'France', 'Rue Édouard Branly', 1),
       (26, '22300', 'Lannion', 'France', 'Rue des anciens membres anonymes', 1),
       (28, '56000', 'Vannes', 'France', 'Rue des Goélands', '12'),
       (29, '35720', 'Mesnil-Roc h', 'France', 'Le Bois du Cobac', '1'),
       (30, '29290', 'Milizac-Guipronvel', 'France', '1 Trois Curés', '1'),
       (31, '35700', 'Rennes', 'France', 'Base de loisirs des Gayeulles', '1'),
       (32, '29810', 'Ploumoguer', 'France', 'Kervadail', '1'),
       (34, '35000', 'Rennes', 'France', 'Rue Saint-Georges', '1'),
       (33, '29360', 'Clohars-Carnoët', 'France', 'Quai de Doëlan', '1'),
       (35, '56620', 'Pont-Scorff', 'France', 'Kersamedi', '1');


-- Peuplement de la table _localisation
INSERT INTO _localisation (idOffre, codePostal, ville, pays, rue, numeroRue) 
VALUES (1, '22300', 'Lannion', 'France', 'Rue Édouard Branly', '1'),
       (2, '22430', 'Erquy', 'France', 'Rue du port', '1'),
       (3, '22000', 'Fréhel', 'France', 'Allée des acacias', '4'),
       (4, '22300', 'Lannion', 'France', 'Rue Beauchamp', '5'),
       (5, '22000', 'Fréhel', 'France', 'Route du cap', '1'),

       -- New
       (8, '22140', 'Pluzunet', 'France', 'convenant roussel', '5'),
       (9, '22730', 'Trégastel', 'France', 'Rue de Merlin', '23'),
       (10, '22400', 'Saint-Alban', 'France', 'Rue de Église', '3'),
       (11, '22700', 'Perros-Guirec', 'France', 'Rue de la Clarté', '14'),
       (12, '22240', 'Plévenon', 'France', 'Rue de la Latte', '28'),
       (13, '22360', 'Langueux', 'France', 'Rue des Grèves', '23'),
       (14, '22580', 'Plouha', 'France', 'Kerouziel', '27'),
       (15, '22700', 'Perros-Guirec', 'France', 'Rue de Trolay', '17'),
       (16, '22620', 'Ploubazlanec', 'France', 'Pointe de l Arcouest', '1'),
       (17, '35720', 'Mesnil-Roc h', 'France', 'Le Bois du Cobac', '1'),
       (18, '29290', 'Milizac-Guipronvel', 'France', '1 Trois Curés', '1'),
       (19, '35700', 'Rennes', 'France', 'Base de loisirs des Gayeulles', '1'),
       (20, '56340', 'Carnac', 'France', 'Fontainebleau', '1'),
       (21, '29810', 'Ploumoguer', 'France', 'Kervadail', '1'),
       (22, '35000', 'Rennes', 'France', 'Rue Saint-Georges', '1'),
       (23, '29360', 'Clohars-Carnoët', 'France', 'Quai de Doëlan', '1'),
       (24, '56620', 'Pont-Scorff', 'France', 'Kersamedi', '1'),
       (25, '56100', 'Lorient', 'France', 'Rue Roland Morillot', '1');
       

-- Peuplement de la table _abonner
INSERT INTO _abonner (idOffre, nomAbonnement) 
VALUES (1, 'Premium'), 
       (2, 'Basique'), 
       (3, 'Premium'), 
       (4, 'Basique'), 
       (5, 'Premium'),
       (6,'Premium'),

       --New
       (7,'Premium'),
       (8,'Premium'),
       (9,'Premium'),
       (10,'Premium'),
       (11,'Premium'),
       (12,'Premium'),
       (13,'Premium'),
       (14,'Premium'),
       (15,'Premium'),
       (16,'Premium'),
       (17,'Premium'),
       (18,'Premium'),
       (19,'Premium'),
       (20,'Premium'),
       (21,'Premium'),
       (22,'Premium'),
       (23,'Premium'),
       (24,'Premium'),
       (25,'Premium');


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
       ('2024-11-01','2024-11-22',3,50.04);

-- Peuplement de la table _option_offre
INSERT INTO _option_offre (idOption, idOffre, nomOption) 
VALUES (1, 3, 'ALaUne'), 
       (2, 2, 'ALaUne'),
       (3, 1, 'EnRelief'),
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
       (5, 'Anglais'),

       --New
       (14, 'Français'), 
       (15, 'Français'),
       (16, 'Français'),
       (16, 'Anglais');

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
       (25, 'Une activité ludique et très divertissante.', '2024-12-05 16:00:00'),

       --offre 16 avis et reponse
       (3, 'Superbe journée en famille, attractions variées et ambiance agréable.', '2024-11-15 09:30:00'),
       (5, 'Trop d’attente aux attractions, c’était frustrant.', '2024-11-18 14:42:00'),
       (7, 'Personnel très sympa, on a passé un excellent moment.', '2024-12-02 10:25:00'),
       (10, 'Parc propre mais manque de bancs pour se reposer.', '2024-12-10 16:10:00'),
       (12, 'Les attractions sont bien mais les prix sont trop élevés.', '2024-12-20 11:50:00'),
       (15, 'Trop de monde, on a fait seulement 3 attractions en 4h.', '2025-01-05 15:30:00'),
       (18, 'Excellent parc d’attractions ! Les enfants ont adoré.', '2025-01-10 10:20:00'),
       (20, 'La nourriture est chère et pas terrible...', '2025-02-14 13:45:00'),
       (22, 'L’espace aquatique est génial, surtout par beau tem.', '2025-03-03 11:10:00'),
       (25, 'Manque de signalisation, on s’est perdus plusieurs fois.', '2025-03-28 14:55:00'),
       
       (30, 'Nous comprenons votre frustration et travaillons sur un système de file virtuelle.', '2024-11-18 17:00:00'),
       (30, 'Ravi que notre personnel ait rendu votre visite agréable !', '2024-12-02 13:00:00'),
       (30, 'Nous allons améliorer la gestion du flux des visiteurs.', '2025-01-05 18:00:00'),
       (30, 'Nous allons améliorer la signalisation, merci pour votre retour.', '2025-03-28 17:20:00'),

       --offre 14
       (6, 'Un moment magique avec mon/ma partenaire, je recommande !', '2025-05-10 14:32:00'),
       (15, 'C’était bien, mais le prix est un peu excessif pour la prestation.', '2025-06-18 09:15:23'),
       (22, 'Les enfants ont adoré, et l’équipe était super sympa.', '2025-07-22 16:47:11'),
       (9, 'J’ai trouvé ça assez ennuyeux, je m’attendais à mieux...', '2025-08-05 11:03:45'),
       (25, 'Très mauvaise organisation, beaucoup d’attente et peu d’explications.', '2025-09-14 18:22:30'),
       
       --offre 15
       (12, 'Les enfants ont adoré ! Journée parfaite en famille.', '2022-06-20 10:15:30'),
       (18, 'Beaucoup d’attente aux attractions, mais dans l’ensemble c’était fun.', '2022-07-15 14:45:10'),
       (7, 'Moment agréable avec mon/ma partenaire, à refaire.', '2022-08-12 16:32:45'),
       (23, 'Peu d’infrastructures adaptées aux professionnels, on ne reviendra pas.', '2023-09-25 11:57:20'),
       (5, 'Trop de monde, file d’attente interminable... Pas une bonne expérience.', '2023-10-03 18:05:50'),
       (11, 'Super parc aquatique ! Mes enfants ne voulaient plus partir.', '2024-06-18 09:45:20'),
       (21, 'Les attractions sont bien, mais la restauration est trop chère.', '2024-07-10 15:25:00'),
       (9, 'Beaucoup de monde, mais bonne ambiance !', '2024-08-05 13:30:50'),
       (14, 'Le personnel était très sympathique, mais il manque des attractions pour adultes.', '2024-09-20 16:55:30'),
       (16, 'J’ai perdu mon téléphone dans une attraction, mais le service client a été très réactif !', '2024-07-22 10:00:00'),
       (22, 'Trop de files d’attente, surtout en été. Il faudrait plus d’ombre dans la file.', '2024-08-15 14:20:10'),
       (17, 'Manque de tables de pique-nique, difficile de trouver un endroit pour manger.', '2023-09-05 12:40:00'),
       (20, 'Topissime ! Je recommande vivement.', '2023-06-29 17:20:00');

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
       (113, 4, 4, 'solo', 'novembre', '2024', 'Activité sympa et agréable', FALSE),
       
       -- offre 16
       (114, 18, 5, 'famille', 'novembre', 2024, 'Une sortie réussie !', FALSE),
       (115, 18, 2, 'amis', 'novembre', 2024, 'Beaucoup trop d’attente', FALSE),
       (116, 18, 4, 'couple', 'decembre', 2024, 'Personnel au top', FALSE),
       (117, 18, 3, 'solo', 'decembre', 2024, 'Manque d’aménagements', FALSE),
       (118, 18, 2, 'famille', 'janvier', 2025, 'Prix trop élevés', FALSE),
       (119, 18, 2, 'amis', 'janvier', 2025, 'Trop de monde', FALSE),
       (120, 18, 5, 'couple', 'fevrier', 2025, 'Génial pour les enfants', FALSE),
       (121, 18, 2, 'affaire', 'fevrier', 2025, 'Restauration décevante', FALSE),
       (122, 18, 5, 'famille', 'mars', 2025, 'Superbe espace aquatique', FALSE),
       (123, 18, 3, 'amis', 'mars', 2025, 'Problème de signalisation', FALSE),

       --offre 14
       (133, 16, 5, 'couple', 5, 2025, 'Une escapade romantique parfaite', FALSE),
       (134, 16, 3, 'amis', 6, 2025, 'Sympa mais un peu cher', FALSE),
       (135, 16, 4, 'famille', 7, 2025, 'Idéal pour les enfants', FALSE),
       (136, 16, 2, 'solo', 8, 2025, 'Pas ce à quoi je m’attendais...', FALSE),
       (137, 16, 1, 'affaire', 9, 2025, 'Mauvaise organisation, décevant', FALSE),

       --offre 15
       (138, 17, 5, 'famille', 'juin', 2023, 'Les enfants ont adoré ! Journée parfaite en famille.', FALSE),
       (139, 17, 3, 'amis', 'juillet', 2023, 'Beaucoup d’attente aux attractions, mais dans l’ensemble c’était fun.', FALSE),
       (140, 17, 4, 'couple', 'août', 2023, 'Moment agréable avec mon/ma partenaire, à refaire.', FALSE),
       (141, 17, 2, 'affaire', 'septembre', 2023, 'Peu d’infrastructures adaptées aux professionnels, on ne reviendra pas.', FALSE),
       (142, 17, 1, 'solo', 'octobre', 2023, 'Trop de monde, file d’attente interminable... Pas une bonne expérience.', FALSE),
       (143, 17, 5, 'famille', 'juin', 2024, 'Super parc aquatique ! Mes enfants ne voulaient plus partir.', FALSE),
       (144, 17, 3, 'amis', 'juillet', 2024, 'Les attractions sont bien, mais la restauration est trop chère.', FALSE),
       (145, 17, 4, 'couple', 'août', 2024, 'Beaucoup de monde, mais bonne ambiance !', FALSE);

INSERT INTO _reponse(idC,ref)
VALUES (11,3),
       (12,5),
       (13,9),
       (124, 115),
       (125, 116),
       (126, 119),
       (127, 123);

-- On fait en sorte que les avis qui ont une réponse soient considérés comme lus
UPDATE pact._avis 
       SET lu = true 
       where idc in 
              (select a.idc 
              from pact.avis AS a 
              join pact._reponse AS r 
              on a.idc = r.ref);
       
INSERT INTO _avisImage(idC,url)
VALUES (1,'./img/imageAvis/1/0.png'),
       (3,'./img/imageAvis/3/0.png'),
       (7,'./img/imageAvis/7/0.png');

INSERT INTO _historiqueStatut(idOffre,dateLancement,dureeEnLigne,prixDuree)
VALUES (1,'2024-11-01',6,20.04),
       (1,'2024-11-15',NULL,NULL),
       (2,'2024-11-01',4,6.68),
       (2,'2024-11-15',NULL,NULL),
       (3,'2024-11-10',NULL,NULL),
       (4,'2024-11-01',NULL,NULL),
       (5,'2024-11-01',NULL,NULL),
       (1,'2024-10-01',6,20.04),
       (1,'2024-10-15',17,56.78),
       (2,'2024-10-01',4,6.68),
       (2,'2024-10-15',17,28.39),
       (3,'2024-10-10',22,73.48),
       (4,'2024-10-01',31,51.77),
       (5,'2024-10-01',31,103.54),
       (1,'2024-09-01',6,20.04),
       (1,'2024-09-15',10,33.4),
       (2,'2024-09-01',4,6.68),
       (2,'2024-09-15',10,16.7),
       (3,'2024-09-10',20,66.8),
       (4,'2024-09-01',30,50.1),
       (5,'2024-09-01',30,100.20);

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
       (5,'muet'),
       (6,'sourd');

INSERT INTO _prestation(nomPresta)
VALUES ('Repas'),
       ('Transport');

INSERT INTO _offrePrestation_non_inclu(idOffre,nomPresta)
VALUES (4,'Transport'),
(6,'Repas');

INSERT INTO _offrePrestation_inclu(idOffre,nomPresta)
VALUES (4,'Repas'),
(6,'Transport');

INSERT INTO _parametre(dureeBlacklistage,uniteblacklist)
VALUES (365,'jours');

--EXEMPLE essaie de historique

--insertion d'un message
--INSERT INTO vueMessages (idExpediteur, contenuMessage, dateMessage, typeExpediteur, idReceveur)
--VALUES (5, 'Bonjour, avez-vous un devis ?', CURRENT_TIMESTAMP, 'membre', 4); 

--insertion d'un tokken
--UPDATE _utilisateur SET tokken ='tokken' WHERE idU = 14;

--insertion d'un message pour le membre ayant le tokken
--INSERT INTO vueMessages (nomExpediteur, idExpediteur, contenuMessage, dateMessage, typeExpediteur, nomReceveur, idReceveur)
--VALUES ('mattaque', 14, 'Bonjour, avez-vous un devis ?', CURRENT_TIMESTAMP, 'membre', 'Patrick', 4); 

--test requete en fonction du tokken
--SELECT vueMessages.idMessage, vueMessages.dateMessage, vueMessages.contenuMessage,vueMessages.nomExpediteur, vueMessages.nomReceveur
--FROM pact.vueMessages
--JOIN pact._utilisateur ON _utilisateur.idU = vueMessages.idReceveur OR _utilisateur.idU = vueMessages.idExpediteur
--WHERE _utilisateur.tokken = 'tokken'
--ORDER BY vueMessages.dateMessage DESC;


