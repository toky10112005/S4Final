CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(10) NOT NULL UNIQUE
);
INSERT INTO prefixes (prefixe) VALUES
('037'),
('033');

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