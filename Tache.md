## Répartition des tâches (binôme, via GitHub)

Le piège classique en binôme, c'est de couper "toi le back, moi le front" ou "toi opérateur, moi client" **sans avoir défini le socle commun d'abord** — ça crée des conflits de merge et des incohérences de modèle de données. Voici une répartition qui évite ça :

### Étape 0 — À faire ENSEMBLE avant de vous séparer (30-60 min)
- Définir ensemble le schéma de données (les tables/entités : Client, Compte, Opérateur, Préfixe, TypeOperation, Bareme/Tranche, Transaction/Historique)
- Créer le repo GitHub, la structure de dossiers, un fichier `README.md` avec les règles (branche `main` protégée, chaque feature dans sa branche, pull request avant de merger)
- Se mettre d'accord sur la stack technique

### Répartition des taches 

**Personne A — "Moteur & Opérateur"**
- Modèles de données + logique métier centrale (calcul des frais selon le barème et la tranche)
- Gestion des préfixes (CRUD)
- Gestion des types d'opérations et barèmes (CRUD, avec modification des tranches)
- Écran "situation des gains" (somme des frais perçus, filtrable par type d'opération)
- Écran "situation des comptes clients" (liste des comptes, soldes)

**Personne B — "Client & Transactions"**
- Login automatique par numéro de téléphone (création de compte à la volée)
- Écran solde
- Dépôt / Retrait / Transfert (ces écrans **appellent** la fonction de calcul de frais faite par Personne A — d'où l'intérêt de définir cette interface ensemble à l'étape 0)
- Écran historique des opérations

