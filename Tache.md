### À faire ENSEMBLE 
- Définir ensemble le schéma de données (les tables/entités)
- Créer le repo GitHub, la structure de dossiers

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
- Dépot / Retrait / Transfert (ces écrans appellent la fonction de calcul de frais faite par Personne A — d'où l'intérêt de définir cette interface ensemble à l'étape 0)
- Écran historique des opérations

