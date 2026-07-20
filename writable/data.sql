CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(10) NOT NULL UNIQUE
);


CREATE TABLE type_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE -- 'depot', 'retrait', 'transfert'
);

INSERT OR IGNORE INTO type_operations (nom) VALUES
('depot'),
('retrait'),
('transfert');

CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operations(id)
);

CREATE TABLE operateur(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    role VARCHAR(20) NOT NULL DEFAULT 'operateur',
    password VARCHAR(255) NOT NULL
);

INSERT INTO operateur (username, password) VALUES
('admin', 'admin');

CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone VARCHAR(15) NOT NULL UNIQUE,
    nom VARCHAR(100) DEFAULT 'Client',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    id_expediteur INTEGER NOT NULL,            -- Client qui initie l'opération
    id_destinataire INTEGER NULL,               -- Client destinataire (si transfert)
    montant REAL NOT NULL,
    frais REAL NOT NULL DEFAULT 0.0,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_type_operation) REFERENCES type_operations(id),
    FOREIGN KEY (id_expediteur) REFERENCES clients(id),
    FOREIGN KEY (id_destinataire) REFERENCES clients(id)
);

INSERT INTO transactions (id_type_operation, id_expediteur, id_destinataire, montant, frais) VALUES
(1, 1, NULL, 1000, 0), -- Dépôt de 1000 par le client avec id 1
(2, 1, NULL, 500, 50), -- Retrait de 500 par le client avec id 1 (frais de 50)
(3, 1, 2, 2000, 100); -- Transfert de 2000 du client avec id 1 vers le client avec id 2 (frais de 100)

INSERT INTO type_operations (nom) VALUES
('depot'),
('retrait'),
('transfert');

INSERT INTO clients (telephone, nom) VALUES
('0371234567', 'Client A'),
('0339876543', 'Client B');

INSERT INTO prefixes (prefixe) VALUES
('037'),
('033');

INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais) VALUES
(2, 100, 1000, 50),
(2, 1001, 5000, 50),
(2, 5001, 10000, 100),
(2, 10001, 25000, 200),
(2, 25001, 50000, 400),
(2, 50001, 100000, 800),
(2, 10001, 250000, 1500),
(2, 250001, 500000, 1500),
(2, 500001, 1000000, 2500),
(2, 1000001, 2000000, 3000),
(3, 100, 1000, 50),
(3, 1001, 5000, 50),
(3, 5001, 10000, 100),
(3, 10001, 25000, 200),
(3, 25001, 50000, 400),
(3, 50001, 100000, 800),
(3, 10001, 250000, 1500),
(3, 250001, 500000, 1500),
(3, 500001, 1000000, 2500),
(3, 1000001, 2000000, 3000);
DELETE FROM bareme_frais
WHERE id_type_operation IN (
    SELECT id FROM type_operations WHERE nom IN ('retrait', 'transfert')
);

INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais)
SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 100, 1000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 1001, 5000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 5001, 10000, 100
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 10001, 25000, 200
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 25001, 50000, 400
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 50001, 100000, 800
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 10001, 250000, 1500
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 250001, 500000, 1500
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 500001, 1000000, 2500
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'retrait'), 1000001, 2000000, 3000
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 100, 1000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 1001, 5000, 50
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 5001, 10000, 100
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 10001, 25000, 200
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 25001, 50000, 400
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 50001, 100000, 800
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 10001, 250000, 1500
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 250001, 500000, 1500
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 500001, 1000000, 2500
UNION ALL SELECT (SELECT id FROM type_operations WHERE nom = 'transfert'), 1000001, 2000000, 3000;

CREATE VIEW IF NOT EXISTS v_solde_clients AS
SELECT 
    c.id AS client_id,
    c.telephone,
    (
        -- Total Dépôts reçus
        COALESCE((SELECT SUM(montant) FROM transactions WHERE id_expediteur = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'depot')), 0)
        -- Total Transferts reçus
        + COALESCE((SELECT SUM(montant) FROM transactions WHERE id_destinataire = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'transfert')), 0)
        -- Moins Total Retraits (Montant + Frais)
        - COALESCE((SELECT SUM(montant + frais) FROM transactions WHERE id_expediteur = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'retrait')), 0)
        -- Moins Total Transferts envoyés (Montant + Frais)
        - COALESCE((SELECT SUM(montant + frais) FROM transactions WHERE id_expediteur = c.id AND id_type_operation = (SELECT id FROM type_operations WHERE nom = 'transfert')), 0)
    ) AS solde
FROM clients c;

-- 2. Vue de la situation des gains de l'opérateur (Gain total via les frais)
CREATE VIEW IF NOT EXISTS v_gains_operateur AS
SELECT 
    t_op.nom AS type_operation,
    COUNT(t.id) AS nombre_operations,
    COALESCE(SUM(t.frais), 0) AS total_gains
FROM type_operations t_op
LEFT JOIN transactions t ON t.id_type_operation = t_op.id
GROUP BY t_op.id, t_op.nom;

--Total gains 'retrait'
CREATE VIEW IF NOT EXISTS gains_retrait AS
SELECT 
    COALESCE(SUM(t.frais), 0) AS total_gains
FROM type_operations t_op
LEFT JOIN transactions t ON t.id_type_operation = t_op.id
WHERE t_op.nom = 'retrait'
GROUP BY t_op.id, t_op.nom;

--Total gains 'transfert'
CREATE VIEW IF NOT EXISTS gains_transfert AS
SELECT 
    COALESCE(SUM(t.frais), 0) AS total_gains
FROM type_operations t_op
LEFT JOIN transactions t ON t.id_type_operation = t_op.id
WHERE t_op.nom = 'transfert'
GROUP BY t_op.id, t_op.nom;

--Situation de Chaque Client
CREATE VIEW IF NOT EXISTS situation_clients AS 
SELECT
    c.id AS client_id,
    c.telephone,
    vsc.solde
FROM  clients c
JOIN v_solde_clients vsc ON c.id = vsc.client_id