DROP TABLE IF EXISTS Demandes;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Utilisateurs;
DROP TABLE IF EXISTS Adresses;
DROP TABLE IF EXISTS Intervenants;
DROP TABLE IF EXISTS HorairesTravail;
DROP TABLE IF EXISTS Localisations;


CREATE TABLE IF NOT EXISTS Modeles (
    id SERIAL PRIMARY KEY,
    marque VARCHAR(255) NOT NULL,
    modele VARCHAR(255) NOT NULL,
    type VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Localisations (
    id SERIAL PRIMARY KEY,
    adresse VARCHAR(255),
    ville VARCHAR(255),
    codePostal VARCHAR(20),
    complement TEXT
);

CREATE TABLE IF NOT EXISTS HorairesTravail (
    id SERIAL PRIMARY KEY,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL
);

CREATE TABLE IF NOT EXISTS Intervenants (
    id SERIAL PRIMARY KEY,
    notes TEXT,
    horaire_id INT,
    CONSTRAINT fk_horaires_travail FOREIGN KEY (horaire_id) REFERENCES HorairesTravail(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Adresses (
    id SERIAL PRIMARY KEY,
    adresse VARCHAR(255),
    ville VARCHAR(255),
    codePostal VARCHAR(20),
    complement TEXT
);

CREATE TABLE IF NOT EXISTS Utilisateurs (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    -- roles: technicien, user, admin
    role VARCHAR(50) DEFAULT 'user',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    adresse_id INT,
    intervenant_id INT,
    is_verified BOOLEAN NOT NULL DEFAULT FALSE,
    verification_token VARCHAR(255),
    verification_expires TIMESTAMP,
    CONSTRAINT fk_adresse FOREIGN KEY (adresse_id) REFERENCES Adresses(id) ON DELETE SET NULL,
    CONSTRAINT fk_intervenant FOREIGN KEY (intervenant_id) REFERENCES Intervenants(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Services (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT
);


CREATE TABLE IF NOT EXISTS Demandes (
    id SERIAL PRIMARY KEY,
    date_debut TIMESTAMP NOT NULL,
    statut VARCHAR(50) DEFAULT 'En attente',
    date_fin TIMESTAMP,
    user_id INT,
    intervenant_id INT,
    modele_id INT,
    localisation_id INT,
    services_id INT,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
    CONSTRAINT fk_intervenant FOREIGN KEY (intervenant_id) REFERENCES Intervenants(id) ON DELETE SET NULL,
    CONSTRAINT fk_modele FOREIGN KEY (modele_id) REFERENCES Modeles(id) ON DELETE SET NULL,
    CONSTRAINT fk_localisation FOREIGN KEY (localisation_id) REFERENCES Localisations(id) ON DELETE SET NULL,
    CONSTRAINT fk_services FOREIGN KEY (services_id) REFERENCES Services(id) ON DELETE SET NULL
);

CREATE TABLE Reclamations (
    id SERIAL PRIMARY KEY,
    type VARCHAR(255) NOT NULL, -- 'avis' ou 'reclamation'
    description TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'en_attente', -- 'en_attente', 'en_cours', 'resolue', 'rejetee', 'acceptee'
    note INTEGER CHECK (note >= 1 AND note <= 5), -- Pour les avis
    user_id INT,
    demande_id INT,
    reponse TEXT,
    date_reponse TIMESTAMP,
    moderateur_id INT REFERENCES Utilisateurs(id),
    CONSTRAINT fk_demande FOREIGN KEY (demande_id) REFERENCES Demandes(id) ON DELETE SET NULL,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES Utilisateurs(id) ON DELETE SET NULL
);

-- Exemple de données

-- Services
INSERT INTO Services (nom, description)
VALUES 
  ('Réparation', 'Réparation de votre véhicule'),
  ('Dépannage', 'Dépannage de votre véhicule'),
  ('Maintenance', 'Maintenance de votre véhicule');

-- Insertion des adresses
INSERT INTO Adresses (adresse, ville, codePostal, complement) VALUES
  ('123 rue des Lilas', 'Paris', '75001', 'Bâtiment A'),
  ('45 avenue des Roses', 'Lyon', '69002', 'Étage 2'),
  ('78 boulevard des Pins', 'Marseille', '13001', NULL),
  ('12 rue du Commerce', 'Bordeaux', '33000', 'Bureau 3'),
  ('56 avenue de la Paix', 'Lille', '59000', NULL);

-- Insertion des localisations (pour les lieux d'intervention)
INSERT INTO Localisations (adresse, ville, codePostal, complement) VALUES
  ('34 rue des Artisans', 'Paris', '75002', 'Zone industrielle'),
  ('89 avenue du Travail', 'Lyon', '69003', 'Près du centre commercial'),
  ('67 rue de l''Industrie', 'Marseille', '13002', 'Port autonome'),
  ('23 boulevard des Ateliers', 'Bordeaux', '33100', NULL),
  ('90 rue du Garage', 'Lille', '59100', 'Zone d''activité Nord');

-- Insertion des modèles de véhicules
INSERT INTO Modeles (marque, modele, type) VALUES
  ('Renault', 'Clio', 'Citadine'),
  ('Peugeot', '308', 'Berline'),
  ('Citroën', 'C3', 'Citadine'),
  ('Volkswagen', 'Golf', 'Berline'),
  ('Toyota', 'Yaris', 'Citadine');

-- Insertion des horaires de travail
INSERT INTO HorairesTravail (heure_debut, heure_fin) VALUES
  ('08:00', '17:00'),
  ('09:00', '18:00'),
  ('10:00', '19:00'),
  ('07:00', '16:00'),
  ('11:00', '20:00');

-- Insertion des intervenants
INSERT INTO Intervenants (notes, horaire_id) VALUES
  ('Expert en mécanique générale', 1),
  ('Spécialiste électronique', 2),
  ('Expert en carrosserie', 3),
  ('Généraliste confirmé', 4),
  ('Spécialiste diagnostic', 5);

-- Insertion des utilisateurs (y compris les techniciens)
INSERT INTO Utilisateurs (username, email, nom, prenom, password, role, adresse_id, intervenant_id, is_verified) VALUES
  -- Techniciens
  ('tech1', 'tech1@example.com', 'Martin', 'Pierre', 'aaaaaaaaaa', 'technicien', 1, 1, true),
  ('tech2', 'tech2@example.com', 'Bernard', 'Marie', 'aaaaaaaaaa', 'technicien', 2, 2, true),
  ('tech3', 'tech3@example.com', 'Dubois', 'Jean', 'aaaaaaaaaa', 'technicien', 3, 3, true),
  ('tech4', 'tech4@example.com', 'Robert', 'Sophie', 'aaaaaaaaaa', 'technicien', 4, 4, true),
  ('tech5', 'tech5@example.com', 'Petit', 'Lucas', 'aaaaaaaaaa', 'technicien', 5, 5, true),
  -- Clients
  ('client1', 'client1@example.com', 'Dupont', 'Alice', 'aaaaaaaaaa', 'user', 1, NULL, true),
  ('client2', 'client2@example.com', 'Durand', 'Thomas', 'aaaaaaaaaa', 'user', 2, NULL, true),
  ('client3', 'client3@example.com', 'Lefebvre', 'Emma', 'aaaaaaaaaa', 'user', 3, NULL, true);

-- Insertion des demandes (sur les 12 derniers mois)
INSERT INTO Demandes (date_debut, date_fin, user_id, intervenant_id, modele_id, localisation_id, services_id) VALUES
  -- Demandes récentes (dernier mois)
  (CURRENT_DATE - INTERVAL '5 days', CURRENT_DATE - INTERVAL '3 days', 6, 1, 1, 1, 1),
  (CURRENT_DATE - INTERVAL '10 days', CURRENT_DATE - INTERVAL '8 days', 7, 2, 2, 2, 2),
  (CURRENT_DATE - INTERVAL '15 days', CURRENT_DATE - INTERVAL '14 days', 8, 3, 3, 3, 3),
  
  -- Demandes du mois dernier
  (CURRENT_DATE - INTERVAL '40 days', CURRENT_DATE - INTERVAL '39 days', 6, 1, 1, 1, 1),
  (CURRENT_DATE - INTERVAL '45 days', CURRENT_DATE - INTERVAL '44 days', 7, 2, 2, 2, 2),
  
  -- Demandes plus anciennes
  (CURRENT_DATE - INTERVAL '90 days', CURRENT_DATE - INTERVAL '89 days', 8, 3, 3, 3, 3),
  (CURRENT_DATE - INTERVAL '120 days', CURRENT_DATE - INTERVAL '119 days', 6, 4, 4, 4, 1),
  (CURRENT_DATE - INTERVAL '150 days', CURRENT_DATE - INTERVAL '149 days', 7, 5, 5, 5, 2),
  
  -- Demandes très anciennes
  (CURRENT_DATE - INTERVAL '180 days', CURRENT_DATE - INTERVAL '179 days', 8, 1, 1, 1, 3),
  (CURRENT_DATE - INTERVAL '210 days', CURRENT_DATE - INTERVAL '209 days', 6, 2, 2, 2, 1),
  (CURRENT_DATE - INTERVAL '240 days', CURRENT_DATE - INTERVAL '239 days', 7, 3, 3, 3, 2),
  (CURRENT_DATE - INTERVAL '270 days', CURRENT_DATE - INTERVAL '269 days', 8, 4, 4, 4, 3),
  
  -- Demandes en cours
  (CURRENT_DATE - INTERVAL '2 days', CURRENT_DATE + INTERVAL '3 days', 6, 1, 1, 1, 1),
  (CURRENT_DATE - INTERVAL '1 day', CURRENT_DATE + INTERVAL '2 days', 7, 2, 2, 2, 2),
  (CURRENT_DATE, CURRENT_DATE + INTERVAL '5 days', 8, 3, 3, 3, 3);

-- Insertion des Réclamations
INSERT INTO Reclamations (type, description, statut, note, user_id, demande_id) VALUES
('avis', 'Très satisfait du service, technicien professionnel', 'en_attente', 5, 6, 1),
('avis', 'Service correct mais délai un peu long', 'en_attente', 3, 7, 2),
('reclamation', 'Problème non résolu après intervention', 'en_attente', NULL, 8, 3),
('reclamation', 'Retard important du technicien', 'en_cours', NULL, 6, 4),
('avis', 'Excellent travail, je recommande', 'en_attente', 5, 7, 5);