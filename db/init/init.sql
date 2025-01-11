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
    admin BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    adresse_id INT,
    intervenant_id INT,
    CONSTRAINT fk_adresse FOREIGN KEY (adresse_id) REFERENCES Adresses(id) ON DELETE SET NULL,
    CONSTRAINT fk_intervenant FOREIGN KEY (intervenant_id) REFERENCES Intervenants(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Demandes (
    id SERIAL PRIMARY KEY,
    date_debut TIMESTAMP NOT NULL,
    date_fin TIMESTAMP NOT NULL,
    user_id INT,
    intervenant_id INT,
    modele_id INT,
    localisation_id INT,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
    CONSTRAINT fk_intervenant FOREIGN KEY (intervenant_id) REFERENCES Intervenants(id) ON DELETE SET NULL,
    CONSTRAINT fk_modele FOREIGN KEY (modele_id) REFERENCES Modeles(id) ON DELETE SET NULL,
    CONSTRAINT fk_localisation FOREIGN KEY (localisation_id) REFERENCES Localisations(id) ON DELETE SET NULL
);