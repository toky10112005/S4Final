-- Clean up si réinitialisation
DROP VIEW IF EXISTS situation_clients;
DROP VIEW IF EXISTS gains_retrait;
DROP VIEW IF EXISTS gains_transfert;
DROP VIEW IF EXISTS v_gains_interne;
DROP VIEW IF EXISTS v_gains_autres_operateurs;
DROP VIEW IF EXISTS v_compensation_operateurs;
DROP VIEW IF EXISTS v_solde_clients;

-- 1. Nouvelle table : Opérateurs télécoms
CREATE TABLE IF NOT EXISTS operateurs_partenaires (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    est_reseau_propre BOOLEAN NOT NULL DEFAULT 0, -- 1 pour votre réseau, 0 pour un concurrent
    commission_pourcentage REAL NOT NULL DEFAULT 0.0
    
);

INSERT INTO operateurs_partenaires (nom, est_reseau_propre, commission_pourcentage,promotion) VALUES
('NotreRéseau', 1, 0.0, 5.0),
('Orange', 0, 5.0, 0.0),  -- 5% de commission extra sur les transferts vers Orange
('Airtel', 0, 3.0, 0.0);
  -- 3% de commission extra sur les transferts vers Airtel

-- 2. Table prefixes modifiée : liée à un opérateur
CREATE TABLE IF NOT EXISTS prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(10) NOT NULL UNIQUE,
    id_operateur INTEGER NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateurs_partenaires(id)
);

INSERT INTO prefixes (prefixe, id_operateur) VALUES
('037', 1), -- Votre réseau
('034', 1), -- Votre réseau
('032', 2), -- Orange
('033', 3), -- Airtel
('031', 3); -- Airtel

-- 3. Types d'opérations
CREATE TABLE IF NOT EXISTS type_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
);

INSERT OR IGNORE INTO type_operations (nom) VALUES ('depot'), ('retrait'), ('transfert');

-- 4. Barème de frais de base
CREATE TABLE IF NOT EXISTS bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operations(id)
);

INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais)
SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 100, 1000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 1001, 5000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 5001, 10000, 100
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 100, 1000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 1001, 5000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 5001, 10000, 100;

-- 5. Utilisateurs système
CREATE TABLE IF NOT EXISTS operateur(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    role VARCHAR(20) NOT NULL DEFAULT 'operateur',
    password VARCHAR(255) NOT NULL
);

INSERT OR IGNORE INTO operateur (username, password) VALUES ('admin', 'admin');

-- 6. Clients
CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone VARCHAR(15) NOT NULL UNIQUE,
    nom VARCHAR(100) DEFAULT 'Client',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO clients (telephone, nom) VALUES
('0371234567', 'Client A (NotreRéseau)'),
('0339876543', 'Client B (Airtel)'),
('0321122334', 'Client C (Orange)');

-- 7. Transactions évoluées (ajoute les frais supplémentaires et l'opérateur destinataire)
CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    id_expediteur INTEGER NOT NULL,
    id_destinataire INTEGER NULL,
    id_operateur_cible INTEGER NULL,             -- Opérateur du destinataire (si transfert)
    montant REAL NOT NULL,
    promotion REAL NOT NULL DEFAULT 0.0,
    frais REAL NOT NULL DEFAULT 0.0,              -- Frais de base (barème)
    frais_commission REAL NOT NULL DEFAULT 0.0,   -- Frais de commission extra (si inter-opérateur)
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_type_operation) REFERENCES type_operations(id),
    FOREIGN KEY (id_expediteur) REFERENCES clients(id),
    FOREIGN KEY (id_destinataire) REFERENCES clients(id),
    FOREIGN KEY (id_operateur_cible) REFERENCES operateurs_partenaires(id)
);

-- Exemples de transactions V2 :
INSERT INTO transactions (id_type_operation, id_expediteur, id_destinataire, id_operateur_cible, montant, promotion, frais, frais_commission) VALUES
(1, 1, NULL, NULL, 10000,5.0, 0, 0),                        -- Dépôt 10 000
(3, 1, 2, 3, 2000,0.0, 50, 60),                            -- Transfert vers Airtel (2000 Ar, Frais base=50, Comm 3%=60)
(3, 1, 3, 2, 3000,0.0, 50, 150);                           -- Transfert vers Orange (3000 Ar, Frais base=50, Comm 5%=150)


-- A. Calcul du Solde des Clients (Tient compte de montant + frais + commission)
CREATE VIEW v_solde_clients AS
SELECT 
    c.id AS client_id,
    c.telephone,
    (
        COALESCE((SELECT SUM(montant) FROM transactions WHERE id_expediteur = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'depot')), 0)
        + COALESCE((SELECT SUM(montant) FROM transactions WHERE id_destinataire = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'transfert')), 0)
        - COALESCE((SELECT SUM(montant + frais + frais_commission) FROM transactions WHERE id_expediteur = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'retrait')), 0)
        - COALESCE((SELECT SUM(montant + frais + frais_commission - (frais*0.05)) FROM transactions WHERE id_expediteur = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'transfert')), 0)
    ) AS solde
FROM clients c;

-- B. Vue situation_clients
CREATE VIEW situation_clients AS 
SELECT c.id AS client_id, c.telephone, vsc.solde
FROM clients c JOIN v_solde_clients vsc ON c.id = vsc.client_id;

-- C. V1 EXIGENCE : Gains globaux par type d'operation
CREATE VIEW gains_retrait AS
SELECT
    COALESCE(SUM(t.frais + t.frais_commission), 0) AS total_gains
FROM transactions t
JOIN type_operations o ON o.id = t.id_type_operation
WHERE o.nom = 'retrait';

CREATE VIEW gains_transfert AS
SELECT
    COALESCE(SUM(t.frais + t.frais_commission), 0) AS total_gains
FROM transactions t
JOIN type_operations o ON o.id = t.id_type_operation
WHERE o.nom = 'transfert';

-- C. V2 EXIGENCE : Gains Réseau Propre vs Autres Opérateurs
CREATE VIEW v_gains_interne AS
SELECT 
    COALESCE(SUM(frais), 0) AS total_gains_frais_base
FROM transactions
WHERE id_operateur_cible IS NULL OR id_operateur_cible = (SELECT id FROM operateurs_partenaires WHERE est_reseau_propre = 1);

CREATE VIEW v_gains_autres_operateurs AS
SELECT 
    op.nom AS operateur,
    COUNT(t.id) AS nombre_transferts,
    COALESCE(SUM(t.frais), 0) AS gains_frais_base,
    COALESCE(SUM(t.frais_commission), 0) AS gains_commissions_extra,
    COALESCE(SUM(t.frais + t.frais_commission), 0) AS total_gains
FROM operateurs_partenaires op
LEFT JOIN transactions t ON t.id_operateur_cible = op.id
WHERE op.est_reseau_propre = 0
GROUP BY op.id, op.nom;

-- D. V2 EXIGENCE : Compensation / Montants à envoyer à chaque opérateur
CREATE VIEW v_compensation_operateurs AS
SELECT 
    op.id AS operateur_id,
    op.nom AS operateur_nom,
    COUNT(t.id) AS nombre_transactions,
    COALESCE(SUM(t.montant), 0) AS montant_total_a_reverser
FROM operateurs_partenaires op
LEFT JOIN transactions t ON t.id_operateur_cible = op.id
WHERE op.est_reseau_propre = 0
GROUP BY op.id, op.nom;