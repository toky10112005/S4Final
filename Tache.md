# Répartition des tâches — Projet Mobile Money

## Commun (préalable)
- Schéma de données (tables/entités) — à définir à deux
- Repo GitHub + arborescence des dossiers

## V1 — Répartition

### Personne A (Opérateur) Toky
- Modèles + logique métier : calcul frais (barème par tranche)
- CRUD préfixes opérateur
- CRUD types d'opération + barèmes (tranches modifiables)
- Écran gains : somme des frais perçus, filtre par type d'opération
- Écran comptes clients : liste + soldes

### Personne B (Client) Rindra
- Auth : login auto par n° téléphone (création compte à la volée, sans inscription)
- Écran solde
- Dépôt / Retrait / Transfert → appelle la fonction de calcul de frais de Personne A (interface à caler ensemble en amont)
- Écran historique des opérations

## V2 — Répartition

### Personne A Toky
- CRUD préfixes autres opérateurs (réutilise le code des préfixes existants)
- Config barème : ajout champ commission % supplémentaire
- Requête gains : split "même opérateur" / "autre opérateur"
- Écran montants à reverser par opérateur : somme des transferts sortants, groupés par opérateur destinataire

### Personne B Rindra
- Fonction utilitaire : détection même-opérateur / autre-opérateur pour un n° destinataire (basée sur préfixes de A) — réutilisée par les 2 points suivants
- Envoi multiple : formulaire multi-numéros, frais calculés une seule fois sur le montant total puis répartis entre destinataires ; bloqué si un n° est chez un autre opérateur
- Checkbox "inclure frais de retrait" sur formulaire transfert existant, masquée/désactivée si destinataire chez un autre opérateur